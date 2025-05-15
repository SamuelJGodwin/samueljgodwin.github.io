<?php
/**
 * Where we do the searching and replacing.
 *
 * @package Search_Replace_WPCode
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WSRW_Search_Replace
 */
class WSRW_Search_Replace {

	/**
	 * The number of rows to process at a time.
	 *
	 * @var int
	 */
	public $page_size = 1000;

	/**
	 * The process data.
	 *
	 * @var array
	 */
	public $process;

	/**
	 * WSRW_Search_Replace constructor.
	 */
	public function __construct() {
		$this->ajax_hooks();
	}

	/**
	 * Add the ajax hooks.
	 *
	 * @return void
	 */
	public function ajax_hooks() {
		add_action( 'wp_ajax_wsrw_start_search_replace', array( $this, 'ajax_prepare_search_replace' ) );
		add_action( 'wp_ajax_wsrw_do_search_replace', array( $this, 'ajax_do_search_replace' ) );
	}

	/**
	 * The callback for the ajax endpoint to start the search & replace process.
	 *
	 * @return void
	 */
	public function ajax_prepare_search_replace() {
		check_admin_referer( 'wsrw_admin', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'You do not have permission to do this.', 'search-replace-wpcode' ) );
		}

		$search           = isset( $_POST['search'] ) ? sanitize_text_field( wp_unslash( $_POST['search'] ) ) : '';
		$replace          = isset( $_POST['replace'] ) ? sanitize_text_field( wp_unslash( $_POST['replace'] ) ) : '';
		$dry_run          = ! isset( $_POST['dry_run'] ) || boolval( $_POST['dry_run'] );
		$case_insensitive = isset( $_POST['case_insensitive'] );

		if ( empty( $search ) ) {
			wp_send_json_error( __( 'Please enter a search term.', 'search-replace-wpcode' ) );
		}

		$tables = isset( $_POST['tables'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['tables'] ) ) : array();

		$tables = $this->validate_tables( $tables );

		global $wpdb;

		if ( in_array( $wpdb->options, $tables ) ) {
			$tables   = array_diff( $tables, array( $wpdb->options ) );
			$tables   = array_values( $tables );
			$tables[] = $wpdb->options;
		}

		$response = array(
			'search'           => $search,
			'replace'          => $replace,
			'pages'            => $this->get_all_pages( $tables ),
			'tables'           => $tables,
			'page'             => 0,
			'table'            => 0,
			'table_page'       => 0,
			'dry_run'          => $dry_run,
			'case_insensitive' => $case_insensitive,
		);

		if ( isset( $_POST['checked_items'] ) ) {
			$response['checked_items'] = json_decode( sanitize_text_field( wp_unslash( $_POST['checked_items'] ) ), true );
		}

		update_option( 'wsrw_process', $response, false );

		do_action( 'wsrw_start_search_replace', $response );

		wp_send_json_success( $response );
	}

	/**
	 * Get the process data.
	 *
	 * @return array
	 */
	public function get_process() {
		if ( ! isset( $this->process ) ) {
			$this->process = get_option( 'wsrw_process', array() );
		}

		return $this->process;
	}

	/**
	 * Highlight the search results.
	 *
	 * @param string $needle The search term.
	 * @param string $haystack The content to search in.
	 * @param string $color The color of the highlight.
	 * @param array  $positions The positions of the search term.
	 * @param string $replaced_string The string that was replaced.
	 * @param bool   $case_insensitive Whether the search is case insensitive.
	 *
	 * @return array
	 */
	public function highlight_results( $needle, $haystack, $color = 'yellow', $positions = array(), $replaced_string = '', $case_insensitive = false ) {

		$offset        = 0;
		$string_offset = 0;
		$search        = $case_insensitive ? 'stripos' : 'strpos';
		if ( empty( $positions ) ) {
			$positions = array();

			$pos = $search( $haystack, $needle, $offset );
			while ( false !== $pos ) {
				// Let's make sure we get the correct position here.
				$positions[] = $pos;
				$offset      = $pos + 1;
				$pos         = $search( $haystack, $needle, $offset );
			}
		} else {
			$string_offset = strlen( $replaced_string ) - strlen( $needle );
		}

		$trimmed_contents = array();
		foreach ( $positions as $i => $pos ) {
			$pos           = $pos - $string_offset * ( $i );
			$start         = max( 0, $pos - 50 ); // 50 characters before
			$length        = strlen( $needle ) + 100; // The replace string and 50 characters after.
			$trimmed       = substr( $haystack, $start, $length );
			$actual_length = $pos - $start;
			$actual_end    = $length - $actual_length - strlen( $needle );
			// Let's add the highlight span.
			$trimmed            = substr_replace( $trimmed, '<span class="wsrw-highlight wsrw-highlight-' . $color . '">', $actual_length, 0 );
			$trimmed            = substr_replace( $trimmed, '</span>', - $actual_end, 0 );
			$trimmed_contents[] = $trimmed;
		}

		$haystack = implode( '... ...', $trimmed_contents );

		return array(
			'positions'   => $positions,
			'highlighted' => $haystack,
		);
	}


	/**
	 * The callback for the ajax endpoint to do the search & replace process.
	 *
	 * @return void
	 */
	public function ajax_do_search_replace() {
		check_admin_referer( 'wsrw_admin', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'You do not have permission to do this.', 'search-replace-wpcode' ) );
		}

		// Let's see if we have the process data saved.
		$process = $this->get_process();

		// If we don't have any process data, we can't do anything.
		if ( empty( $process ) ) {
			wp_send_json_error( __( 'No process data found.', 'search-replace-wpcode' ) );
		}

		$table      = $process['table'];
		$table_name = $process['tables'][ $table ];
		// Escape the table name since we support WordPress versions that don't have the %i placeholder added in WP 6.2.
		$table_name = esc_sql( $table_name );

		// Let's get the page we are currently on.
		$table_page = $process['table_page'];

		global $wpdb;

		$page_size = $this->get_page_size();
		$offset    = $page_size * $table_page;

		// Let's get all the rows in the table with a limit from $this->get_page_size() and the offset from the current page.
		$rows = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name LIMIT %d, %d", $offset, $page_size ) ); // phpcs:ignore

		$columns_data = self::get_table_columns( $table_name );
		$columns      = $columns_data['columns'];
		$primary_key  = $columns_data['primary_key'];

		$updated_data = array();

		foreach ( $rows as $row ) {
			$where_clause  = array();
			$update_clause = array();

			if ( 'siteurl' === $row->option_name ) {
				continue;
			}

			foreach ( $columns as $column ) {
				$content = $row->$column;

				if ( $table_name === $wpdb->options ) {
					if ( isset( $skip ) && true === $skip ) {
						$skip = false;
						continue;
					}

					if ( 'wsrw_process' === $content ) {
						$skip = true;
						continue;
					}
				}

				if ( $primary_key === $column ) {
					$where_clause[] = $column . '= "' . $this->mysql_real_escape_string( $content ) . '"';
					continue;
				}

				if ( apply_filters( 'wsrw_skip_guids', 'guid' === $column ) ) {
					continue;
				}

				$case_insensitive = boolval( $process['case_insensitive'] );
				$replaced_content = $this->run_replace( $process['search'], $process['replace'], $content, $case_insensitive );

				if ( $content !== $replaced_content ) {
					$update_clause[] = $column . ' = "' . $this->mysql_real_escape_string( $replaced_content ) . '"';

					$highlighted_results = $this->highlight_replacements( $process['search'], $process['replace'], $content, $replaced_content, $case_insensitive );

					$operation_data = array(
						'table'  => $table_name,
						'column' => $column,
						'row'    => $row->$primary_key,
						'old'    => $highlighted_results['old'],
						'new'    => $highlighted_results['new'],
					);

					$updated_data[] = $operation_data;

					do_action( 'wsrw_performed_search_replace', $process, $content, $operation_data );

				}
			}

			if ( ! $process['dry_run'] && ! empty( $update_clause ) ) {
				// Let's update the row.
				// If we do a prepared query here or attempt to use $wpdb->update, we will have issues with serialized data or in general break the values of many types of content, so instead we escape the values when the arrays are built.
				$update_sql = "UPDATE $table_name SET " . implode( ', ', $update_clause ) . " WHERE " . implode( ' AND ', $where_clause ); // phpcs:ignore
				$wpdb->query( $update_sql ); // phpcs:ignore
			}
		}

		// Let's update the process data.
		$process['table_page'] = $table_page + 1;
		$process['page']       = $process['page'] + 1;

		if ( count( $rows ) < $this->get_page_size() ) {
			$process['table']      = $process['table'] + 1;
			$process['table_page'] = 0;
		}

		if ( $process['table'] >= count( $process['tables'] ) ) {
			global $wpdb;
			if ( in_array( $wpdb->options, $process['tables'], true ) ) {

				// Process 'siteurl' now.
				$siteurl_value    = get_option( 'siteurl' );
				$case_insensitive = boolval( $process['case_insensitive'] );
				$replaced_siteurl = $this->run_replace( $process['search'], $process['replace'], $siteurl_value, $case_insensitive );

				if ( $siteurl_value !== $replaced_siteurl ) {
					// Update the 'siteurl' option.
					if ( ! $process['dry_run'] ) {
						update_option( 'siteurl', $replaced_siteurl );
					}

					$highlighted_results = $this->highlight_replacements( $process['search'], $process['replace'], $siteurl_value, $replaced_siteurl, $case_insensitive );

					$option    = $wpdb->get_row( $wpdb->prepare( "SELECT option_id FROM {$wpdb->options} WHERE option_name = %s", 'siteurl' ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery
					$option_id = $option ? $option->option_id : null;

					$operation_data = array(
						'table'  => $wpdb->options,
						'column' => 'option_value',
						'row'    => $option_id, // Use the option_id here.
						'old'    => $highlighted_results['old'],
						'new'    => $highlighted_results['new'],
					);

					$updated_data[] = $operation_data;

					do_action( 'wsrw_performed_search_replace', $process, $siteurl_value, $operation_data );
				}
			}

			// Search and replace process is complete; delete the process data.
			delete_option( 'wsrw_process' );

			// Send the final success response.
			wp_send_json_success(
				array(
					'updated_data' => $updated_data,
					'page'         => $process['page'],
					'pages'        => $process['pages'],
					'message'      => esc_html__( 'Search and replace completed successfully.', 'search-replace-wpcode' ),
					'complete'     => true,
				)
			);
		} else {
			// Update the process data.
			update_option( 'wsrw_process', $process );

			// Send the partial success response.
			wp_send_json_success(
				array(
					'updated_data' => $updated_data,
					'page'         => $process['page'],
					'table_page'   => $table_page,
					'table'        => $table,
					'pages'        => $process['pages'],
					// translators: %1$s is the table name %2$s is current database pagination number %3$s is total database pagination pages number.
					'message'      => sprintf( esc_html__( 'Processing table %1$s %2$s/%3$s', 'search-replace-wpcode' ), $table_name, $process['page'], $process['pages'] ),
					'complete'     => false,
				)
			);
		}
	}

	/**
	 * Highlight the search results.
	 *
	 * @param string $search The search term.
	 * @param string $replace The replace term.
	 * @param string $content The content to search in.
	 * @param string $replaced_content The content after the replace.
	 * @param bool   $case_insensitive Whether the search is case insensitive.
	 *
	 * @return array
	 */
	public function highlight_replacements( $search, $replace, $content, $replaced_content, $case_insensitive ) {

		$old = $this->highlight_results( $search, esc_html( $content ), 'red', array(), '', $case_insensitive );
		$new = $this->highlight_results( $replace, esc_html( $replaced_content ), 'green', $old['positions'], $search );

		return array(
			'old' => $old['highlighted'],
			'new' => $new['highlighted'],
		);
	}

	public function reverse_replace( $original_value, $search_string, $replace_string ) {
		// Replace the $replace_string with the $search_string in the original_value.
		$modified_value = str_replace( $original_value, $search_string, $replace_string );

		return $modified_value;
	}

	/**
	 * Run the search and replace.
	 *
	 * @param string $search The search term.
	 * @param string $replace The replace term.
	 * @param string $content The content to search in.
	 * @param bool   $case_insensitive Whether the search is case insensitive.
	 *
	 * @return array|mixed|string|string[]
	 */
	public function run_replace( $search, $replace, $content, $case_insensitive = false ) {
		// Let's run a search and replace while supporting serialized data.
		$replaced_content = $content;
		if ( is_serialized( $content ) ) {
			$replaced_content = $this->maybe_unserialize( $content );
			if ( is_array( $replaced_content ) ) {
				$replaced_content = $this->array_replace_recursive( $search, $replace, $replaced_content, $case_insensitive );
			} elseif ( is_object( $replaced_content ) ) {
				$_tmp = clone $replaced_content;
				$keys = get_object_vars( $replaced_content );
				foreach ( $keys as $key => $value ) {
					if ( is_int( $key ) ) {
						continue;
					}
					if ( is_string( $key ) && strpos( $key, "\0" ) !== false ) {
						continue;
					}
					$_tmp->$key = $this->run_replace( $search, $replace, $value, $case_insensitive );
				}
				$replaced_content = $_tmp;
				unset( $_tmp );
			} else {
				$replaced_content = $this->str_replace( $search, $replace, $replaced_content, $case_insensitive );
			}
			// We need this to be serialized as we got it serialized.
			$replaced_content = serialize( $replaced_content ); // phpcs:ignore
		} elseif ( is_string( $content ) ) {
			$replaced_content = $this->str_replace( $search, $replace, $content, $case_insensitive );
		}

		return $replaced_content;
	}

	/**
	 * Recursively replace values in an array.
	 *
	 * @param mixed $search The search term.
	 * @param mixed $replace The replace term.
	 * @param mixed $subject The content to search in.
	 * @param bool  $case_insensitive Whether the search is case insensitive.
	 *
	 * @return array|mixed
	 */
	public function array_replace_recursive( $search, $replace, $subject, $case_insensitive = false ) {
		if ( is_array( $subject ) ) {
			foreach ( $subject as $key => $value ) {
				$subject[ $key ] = $this->array_replace_recursive( $search, $replace, $value );
			}
		} elseif ( is_object( $subject ) ) {
			$_tmp = clone $subject;
			$keys = get_object_vars( $subject );
			foreach ( $keys as $key => $value ) {
				if ( is_int( $key ) ) {
					continue;
				}
				if ( is_string( $key ) && strpos( $key, "\0" ) !== false ) {
					continue;
				}
				$_tmp->$key = $this->array_replace_recursive( $search, $replace, $value );
			}
			$subject = $_tmp;
			unset( $_tmp );
		} elseif ( is_string( $subject ) ) {
			$subject = $this->str_replace( $search, $replace, $subject, $case_insensitive );
		}

		return $subject;
	}

	/**
	 * Local version of str_replace.
	 *
	 * @param string $search The search term.
	 * @param string $replace The replace term.
	 * @param string $subject The content to search in.
	 * @param bool   $case_insensitive Whether the search is case insensitive.
	 *
	 * @return array|mixed|string|string[]
	 */
	public function str_replace( $search, $replace, $subject, $case_insensitive = false ) {
		if ( $case_insensitive ) {
			return str_ireplace( $search, $replace, $subject );
		}

		return str_replace( $search, $replace, $subject );
	}

	/**
	 * Get the columns of a table.
	 *
	 * @param string $table_name The name of the table.
	 *
	 * @return array
	 */
	public static function get_table_columns( $table_name ) {
		global $wpdb;

		$primary_key  = false;
		$column_names = array();
		$table_name   = esc_sql( $table_name ); // We can't use prepare with %i since we support older versions of WordPress.
		$columns      = $wpdb->get_results( "DESCRIBE $table_name" ); // phpcs:ignore

		foreach ( $columns as $column ) {
			if ( 'PRI' === $column->Key ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				$primary_key = $column->Field; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			}
			$column_names[] = $column->Field; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		}

		return array(
			'primary_key' => $primary_key,
			'columns'     => $column_names,
		);
	}

	/**
	 * Calculate the number of pages we need to process.
	 *
	 * @param array $tables The tables to search in.
	 *
	 * @return int
	 */
	protected function get_all_pages( $tables ) {
		// Let's get the total number of rows in the tables.
		$total_rows = 0;
		foreach ( $tables as $table ) {
			$total_rows += $this->get_table_pages( $table );
		}

		return $total_rows;
	}

	/**
	 * Get the number of pages for a table.
	 *
	 * @param string $table The table name.
	 *
	 * @return int
	 */
	private function get_table_pages( $table ) {
		global $wpdb;

		$rows = absint( $wpdb->get_var( "SELECT COUNT(*) FROM $table" ) ); // phpcs:ignore

		if ( 0 === $rows ) {
			$rows = 1; // We need at least 1 page for each table.
		}

		return ceil( $rows / $this->get_page_size() );
	}

	/**
	 * Get the page size.
	 *
	 * @return int
	 */
	public function get_page_size() {
		return $this->page_size;
	}

	/**
	 * Get all the tables in the database.
	 *
	 * @return array
	 */
	public static function get_all_tables() {
		global $wpdb;

		$all_tables = $wpdb->get_results( "SHOW TABLES", ARRAY_N ); // phpcs:ignore

		$table_names = array();

		foreach ( $all_tables as $table ) {
			if ( empty( $table[0] ) ) {
				continue;
			}
			if ( strpos( $table[0], 'wsrw_' ) !== false ) {
				continue;
			}
			$table_names[] = $table[0];
		}

		// Allow other plugins to exclude their tables here.
		$table_names = apply_filters( 'wsrw_get_all_tables', $table_names );

		return $table_names;
	}

	/**
	 * Go through a list of tables as passed from the admin and validate them against the actual database.
	 * Defaults to all tables for now.
	 *
	 * @param array $tables Array of table names to include in the search.
	 *
	 * @return array A validated list of tables.
	 */
	public function validate_tables( $tables ) {
		// Let's get a list of all the actual tables in the database.
		$valid_tables = self::get_all_tables();

		// If we don't have any tables, we can't do anything.
		if ( empty( $valid_tables ) ) {
			return array();
		}

		$validated_tables = array();
		foreach ( $tables as $table ) {
			if ( in_array( $table, $valid_tables, true ) ) {
				$validated_tables[] = $table;
			}
		}

		return $validated_tables;
	}

	/**
	 * Unserialize method that makes sure we set allowed_classes to false.
	 *
	 * @param mixed $data The data to unserialize.
	 *
	 * @return mixed
	 */
	public function maybe_unserialize( $data ) {
		if ( is_serialized( $data ) ) {
			return @unserialize( trim( $data ), array( // phpcs:ignore
				'allowed_classes' => false,
			) ); // phpcs:ignore
		}

		return $data;
	}

	/**
	 * Local version of mysql_real_escape_string.
	 *
	 * @param mixed $string The string to escape.
	 *
	 * @return array|mixed|string|string[]
	 */
	public function mysql_real_escape_string( $string ) {
		if ( is_array( $string ) ) {
			return array_map( __METHOD__, $string );
		}
		if ( ! empty( $string ) && is_string( $string ) ) {
			return str_replace(
				array( '\\', "\0", "\n", "\r", "'", '"', "\x1a" ),
				array(
					'\\\\',
					'\\0',
					'\\n',
					'\\r',
					"\\'",
					'\\"',
					'\\Z',
				),
				$string
			);
		}

		return $string;
	}
}
