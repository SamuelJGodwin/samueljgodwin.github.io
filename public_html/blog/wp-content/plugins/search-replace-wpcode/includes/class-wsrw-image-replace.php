<?php
/**
 * This class handles the main logic for replacing images.
 *
 * @package Search_Replace_WPCode
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WSRW_Image_Replace
 */
class WSRW_Image_Replace {

	/**
	 * The old file path.
	 *
	 * @var string
	 */
	private $old_file_path;

	/**
	 * WSRW_Image_Replace constructor.
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );

		add_filter( 'attachment_fields_to_edit', array( $this, 'add_button_to_edit_media_modal_fields_area' ), 10, 2 );
		add_filter( 'media_row_actions', array( $this, 'add_button_to_media_row_actions' ), 10, 2 );

		add_filter( 'wp_get_attachment_image_src', array( $this, 'get_attachment_url' ), 10, 4 );

		add_action( 'add_meta_boxes_attachment', array( $this, 'add_meta_boxes' ) );
	}

	/**
	 * Filters the attachment image source result.
	 *
	 * @param array|false  $image {
	 *     Array of image data, or boolean false if no image is available.
	 *
	 * @type string $0 Image source URL.
	 * @type int    $1 Image width in pixels.
	 * @type int    $2 Image height in pixels.
	 * @type bool   $3 Whether the image is a resized image.
	 * }
	 *
	 * @param int          $attachment_id Image attachment ID.
	 * @param string|int[] $size Requested image size. Can be any registered image size name, or
	 *                                    an array of width and height values in pixels (in that order).
	 * @param bool         $icon Whether the image should be treated as an icon.
	 *
	 * @since 4.3.0
	 */
	public function get_attachment_url( $image, $attachment_id, $size, $icon ) {
		if ( ! is_admin() && ( ! defined( 'REST_REQUEST' ) || ! REST_REQUEST ) ) {
			return $image;
		}
		// Let's check if the attachment has been replaced using the _wsrw_replaced meta.
		$replaced = get_post_meta( $attachment_id, '_wsrw_replaced', true );
		// If the replaced timestamp is past 24h let's just skip this.
		if ( ! empty( $replaced ) && $replaced < strtotime( '-1 day' ) ) {
			return $image;
		}

		if ( is_array( $image ) ) {
			$image[0] = add_query_arg( 'wsr', $replaced, $image[0] );
		}

		return $image;
	}

	/**
	 * Handle the image upload.
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return WP_REST_Response
	 */
	public function handle_image_upload( $request ) {
		$files = $request->get_file_params();

		$nonce = $request->get_header( 'X-WP-Nonce' );
		if ( empty( $nonce ) ) {
			$nonce = isset( $_POST['_wpnonce'] ) ? sanitize_key( $_POST['_wpnonce'] ) : '';
		}

		if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
			return new WP_REST_Response(
				array(
					'success' => false,
					'message' => esc_html__( 'Invalid nonce', 'search-replace-wpcode' ),
				),
				403
			);
		}

		if ( empty( $files['file'] ) ) {
			return new WP_REST_Response(
				array(
					'success' => false,
					'message' => esc_html__( 'No file uploaded', 'search-replace-wpcode' ),
				),
				400
			);
		}
		$file     = $files['file'];
		$media_id = isset( $_POST['media_id'] ) ? absint( $_POST['media_id'] ) : 0;

		// Let's check if the user is allowed to upload the file format.
		$allowed_mime_types = get_allowed_mime_types();
		$allowed_mime_types = apply_filters( 'upload_mimes', $allowed_mime_types );
		$ext                = pathinfo( $file['name'], PATHINFO_EXTENSION );
		$mime_type          = wp_check_filetype( $file['name'], $allowed_mime_types );
		if ( ! in_array( $mime_type['type'], $allowed_mime_types, true ) ) {
			return new WP_REST_Response(
				array(
					'success' => false,
					'message' => esc_html__( 'File type not allowed', 'search-replace-wpcode' ),
				),
				400
			);
		}

		// Let's grab the path of the current image using the media_id.
		$attachment    = get_post( $media_id );
		$old_file_path = get_attached_file( $media_id, true );

		// Let's first delete all the thumbnails for the old image.
		$metadata     = wp_get_attachment_metadata( $media_id );
		$backup_sizes = get_post_meta( $media_id, '_wp_attachment_backup_sizes', true );
		wp_delete_attachment_files( $media_id, $metadata, $backup_sizes, $old_file_path );

		// Let's upload the new file in the same directory as the old file with the same exact name.
		$new_file_path = $old_file_path;
		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
		$this->old_file_path = $old_file_path;
		// Let's grab the original upload time from the post publish date.
		$time = strtotime( $attachment->post_date );
		// Time should be formatted in yyyy/mm so that we use the same directory.
		$time = gmdate( 'Y/m', $time );
		// Let's move the file to the new location using wp_handle_upload.
		$move_file = wp_handle_upload(
			$file,
			array(
				'test_form'                => false,
				'unique_filename_callback' => array( $this, 'unique_filename_callback' ),
			),
			$time
		);

		if ( ! $move_file || isset( $move_file['error'] ) ) {
			return new WP_REST_Response(
				array(
					'success' => false,
					'message' => $move_file['error'] ?? esc_html__( 'An error occurred while uploading the file', 'search-replace-wpcode' ),
				),
				500
			);
		}

		// Let's make sure the media file is included before calling generate attachment metada.
		require_once ABSPATH . 'wp-admin/includes/image.php';

		add_filter( 'big_image_size_threshold', '__return_false' );
		// Let's update the attachment metadata.
		$attachment_data = wp_generate_attachment_metadata( $media_id, $new_file_path );
		wp_update_attachment_metadata( $media_id, $attachment_data );

		// Add post meta to mark this has been replaced.
		update_post_meta( $media_id, '_wsrw_replaced', time() );

		$new_media = wp_get_attachment_image_src( $media_id, 'large' );

		$message = '<p>' . esc_html__( 'File uploaded successfully', 'search-replace-wpcode' ) . '</p>';

		$message .= '<p><strong style="color:red;">' . esc_html__( 'Please note that the source file has been replaced. If you see the old file, please clear your browser cache.', 'search-replace-wpcode' ) . '</strong></p>';

		$response = array(
			'success' => true,
			'message' => $message,
		);

		if ( wp_attachment_is_image( $media_id ) ) {
			$response['image_url'] = $new_media[0] ?? wp_get_attachment_url( $media_id );
		}

		return new WP_REST_Response(
			$response,
			200
		);
	}

	/**
	 * Register the REST API routes.
	 */
	public function register_routes() {
		register_rest_route(
			'wsrw/v1',
			'/upload-image',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'handle_image_upload' ),
				'permission_callback' => function () {
					return current_user_can( 'upload_files' );
				},
			)
		);
	}

	/**
	 * Add a button to the edit media modal fields area.
	 *
	 * @param array   $form_fields The form fields.
	 * @param WP_Post $post The post object.
	 *
	 * @return array
	 */
	public function add_button_to_edit_media_modal_fields_area( $form_fields, $post ) {
		if ( ! $this->can_user_replace_image( $post ) ) {
			return $form_fields;
		}

		if ( ! wp_attachment_is_image( $post ) ) {
			return $form_fields;
		}

		$form_fields['wsrw-replace-button'] = array(
			'label'         => '',
			'input'         => 'html',
			'html'          => '<a href="' . esc_url( self::get_replace_page_url( $post ) ) . '" class="button-secondary button-large" title="' . esc_attr__( 'Replace the source file for this image', 'search-replace-wpcode' ) . '">' . esc_html_x( 'Replace Source File', 'action for a single image', 'search-replace-wpcode' ) . '</a>',
			'show_in_modal' => true,
			'show_in_edit'  => false,
			'helps'         => esc_html__( 'Directly replace the original file\'s source without creating a duplicate.', 'search-replace-wpcode' ),
		);

		return $form_fields;
	}

	/**
	 * Get the URL for the replace page.
	 *
	 * @param WP_Post $post The post object.
	 *
	 * @return string
	 */
	public static function get_replace_page_url( $post ) {
		return wp_nonce_url( admin_url( 'admin.php?page=wsrw-search-replace&view=replace_media&media_id=' . $post->ID ), 'wsrw_replace_media' );
	}

	/**
	 * Add a button to the media row actions.
	 *
	 * @param array   $actions The actions array.
	 * @param WP_Post $post The post object.
	 *
	 * @return array
	 */
	public function add_button_to_media_row_actions( $actions, $post ) {
		if ( ! $this->can_user_replace_image( $post ) ) {
			return $actions;
		}

		$actions['wsrw-replace'] = '<a href="' . esc_url( self::get_replace_page_url( $post ) ) . '" title="' . esc_attr__( 'Replace the source file.', 'search-replace-wpcode' ) . '">' . esc_html_x( 'Replace Source File', 'action in the list of attachments', 'search-replace-wpcode' ) . '</a>';

		return $actions;

	}

	/**
	 * Permissions check for a specific attachment
	 *
	 * @param WP_Post $post The post object to check for.
	 *
	 * @return bool
	 */
	public function can_user_replace_image( $post ) {
		return current_user_can( 'upload_files' ) && current_user_can( 'edit_post', $post->ID );
	}

	/**
	 * Add meta boxes for the replace media page.
	 *
	 * @param WP_Post $post The post object.
	 *
	 * @return void
	 */
	public function add_meta_boxes( $post ) {
		if ( ! $this->can_user_replace_image( $post ) ) {
			return;
		}

		add_meta_box(
			'wsrw-replace',
			__( 'Replace Source File', 'search-replace-wpcode' ),
			array(
				$this,
				'replace_meta_box',
			),
			'attachment',
			'side',
			'low'
		);
	}

	/**
	 * Output the meta box for the replace media page.
	 *
	 * @param WP_Post $post The post object.
	 *
	 * @return void
	 */
	public function replace_meta_box( $post ) {
		?>
		<p>
			<a href="<?php echo esc_url( self::get_replace_page_url( $post ) ); ?>" class="button-secondary button-large"><?php esc_html_e( 'Replace Source File', 'search-replace-wpcode' ); ?></a>
		</p>
		<p>
			<?php esc_html_e( 'Use the button above to begin the source file replacement process.', 'search-replace-wpcode' ); ?>
		</p>
		<?php
	}

	/**
	 * Override the unique filename callback so we override the original file.
	 *
	 * @param string $dir The directory path.
	 * @param string $filename The filename.
	 * @param string $ext The file extension.
	 *
	 * @return string
	 */
	public function unique_filename_callback( $dir, $filename, $ext = '' ) {
		if ( isset( $this->old_file_path ) ) {
			return basename( $this->old_file_path );
		} else {
			return $filename;
		}
	}

}

new WSRW_Image_Replace();
