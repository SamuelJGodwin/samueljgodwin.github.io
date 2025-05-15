<?php
/**
 * WPZOOM Instagram widget related hooks and functions.
 *
 * @since 1.2.0
 */

/**
 * Builds the WPZOOM Instagram feed list.
 *
 * @since 1.2.0
 *
 * @param string $username          The username of the feed you wish to display. Accepts any valid Instagram username.
 * @param int    $image_limit       The number of images in the carousel. Accepts any positive integer. Default 12.
 * @param bool   $show_video_thumbs Whether to display video thumbnails. Default true.
 * @param string $image_resolution  The default image resolution. Accepts 'thumbnail' (150x150px),
 *                                  'low_resolution' (320x320px), 'standard_resolution' (640x640px),
 *                                  'default_algorithm' (automatic selection). Default 'default_algorithm'.
 * @param int $image_width          The desired width of each image, when $image_resolution is 'default_algorithm'.
 *                                  Accepts any positive integer. Default 250.
 *
 * @return false|string The Instagram feed list.
 */
function ignition_instagram_items( $username, $image_limit = 12, $show_video_thumbs = true, $image_resolution = 'default_algorithm', $image_width = 250 ) {
	if ( ! class_exists( 'Wpzoom_Instagram_Widget_API' ) ) {
		return false;
	}

	$instance = Wpzoom_Instagram_Widget_API::getInstance();

	$instance_items = $instance->get_items( array(
		'image-limit'          => $image_limit,
		'image-width'          => $image_width,
		'image-resolution'     => $image_resolution,
		'username'             => $username,
		'disable-video-thumbs' => ! $show_video_thumbs,
	) );

	if ( ! $instance_items ) {
		$errors = $instance->errors->get_error_messages();

		ob_start();
		?>
		<ul class="ignition-instagram-feed-error ignition-instagram-feed-error-list">
			<?php foreach ( $errors as $error ) : ?>
				<li><?php echo esc_html( $error ); ?></li>
			<?php endforeach; ?>
		</ul>
		<?php

		return ob_get_clean();
	}

	$items = array_slice( $instance_items['items'], 0, $image_limit );

	ob_start();
	?>
	<ul class="ignition-instagram-list" data-image-width="<?php echo esc_attr( $image_width ); ?>" data-image-resolution="<?php echo esc_attr( $image_resolution ); ?>">
		<?php foreach ( $items as $item ) :
			$link         = $item['link'];
			$src          = $item['image-url'];
			$media_id     = $item['image-id'];
			$inline_attrs = '';
			$is_loading   = '';
			if ( ! empty( $media_id ) && empty( $src ) ) {
				$is_loading   = 'loading';
				$inline_attrs = sprintf( 'data-media-id="%s" data-nonce="%s"',
					esc_attr( $media_id ),
					esc_attr( wp_create_nonce( WPZOOM_Instagram_Image_Uploader::get_nonce_action( $media_id ) ) )
				);

				$src = $item['original-image-url'];
			}
			?>

			<li class="ignition-instagram-list-item" <?php echo $inline_attrs; ?>>
				<a href="<?php echo esc_url_raw( $link ); ?>" class="zoom-instagram-link <?php echo esc_attr( $is_loading ); ?>" style="background-image: url(<?php echo esc_url_raw( $src ); ?>);">
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php
	return ob_get_clean();
}
