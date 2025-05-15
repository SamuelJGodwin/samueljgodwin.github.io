<?php

$hostingerLoginData = [
    'email' => 'samuelwjgodwin@gmail.com',
    'redirect_location' => 'hpanel',
    'client_id' => '33386635',
    'acting_client_id' => '',
    'username' => 'u112236579',
    'domain' => 'blog.samueljgodwin.co.uk',
    'directory' => '',
    'source' => 'wordpress_overview',
    'callback_url' => 'https://hpanel.hostinger.com/api/rest-hosting/v3/wordpress/login/callback/06f96c0fa72dd597dc7e01f65f463887378ebf4b',
    'autologin_file' => __FILE__,
];

if ( !empty($_GET['is_check']) ) {
    http_response_code(200);
    header('Access-Control-Allow-Origin: *');
    echo 'Success!';
    exit();
}

// Initialize WordPress
define( 'WP_USE_THEMES', true );
$timeSinceScriptCreation = time() - stat( __FILE__ )['mtime'];

if ( ! isset( $wp_did_header ) ) {
    $wp_did_header = true;
    // Load the WordPress library.
    require_once( dirname( __FILE__ ) . '/wp-load.php' );

    if ( preg_match( '/www\./', admin_url() ) && ! preg_match( '/www\.|preview-domain\.|hostingersite\./', $_SERVER['SCRIPT_URI'] ) ) {
        $part = parse_url($_SERVER['SCRIPT_URI']);
        $link = $part['scheme'] . '://www.' . $part['host'] . $part['path'];
        wp_redirect( $link );

        exit();
    }

    // Delete itself to make sure it is executed only once
    unlink( __FILE__ );

    //Workaround to fix deactivating plugins after autologin if NextGEN Gallery plugin is enabled.
    if ( class_exists( 'C_NextGEN_Bootstrap' ) ) {
        define( 'DOING_AJAX', true );
    }

    add_filter( 'option_active_plugins' , function ( $plugins ) {

        return array_filter( $plugins , function ( $item ) {
            return strpos( $item, 'hostinger' ) !== false;
        });
    });

    if ( is_user_logged_in() ) {
        $current_user = wp_get_current_user();

        if ( ! in_array( 'administrator', $current_user->roles ) ) {
            wp_logout();
            hostinger_auto_login( $hostingerLoginData );
        }

        $redirect_page = hostinger_get_login_link( $hostingerLoginData );

        $hostingerLoginData['redirect_page'] = $redirect_page;
        do_action( 'hostinger_autologin_user_logged_in', $hostingerLoginData );

        hostinger_callback( $hostingerLoginData );
        wp_redirect( $redirect_page );

        exit();
    }

    if ( $timeSinceScriptCreation < 900 ) {
        hostinger_auto_login( $hostingerLoginData );
    }

    wp();
    // Load the theme template
    require_once( ABSPATH . WPINC . '/template-loader.php' );

    hostinger_callback( $hostingerLoginData );
}

function hostinger_auto_login( $args ) {
    if ( ! is_user_logged_in() ) {
        $user_id       = hostinger_get_user_id( $args['email'] );
        $user          = get_user_by( 'ID', $user_id );

        $redirect_page = hostinger_get_login_link( $args );
        if ( ! $user ) {
            hostinger_callback( $args );
            wp_redirect( $redirect_page );

            exit();
        }
        $login_username = $user->user_login;
        wp_set_current_user( $user_id, $login_username );
        wp_set_auth_cookie( $user_id );
        do_action( 'wp_login', $login_username, $user );
        // Go to admin area
        $args['redirect_page'] = $redirect_page;
        do_action( 'hostinger_autologin', $args );

        hostinger_callback( $args );
        wp_redirect( $redirect_page );

        exit();
    }
}

function hostinger_get_user_id( $email )
{
    $admins = get_users( [
        'role' => 'administrator',
        'search' => '*' . $email . '*',
        'search_columns' => ['user_email'],
    ] );
    if (isset($admins[0]->ID)) {
        return $admins[0]->ID;
    }

    $admins = get_users( [ 'role' => 'administrator' ] );
    if (isset($admins[0]->ID)) {
        return $admins[0]->ID;
    }

    return null;
}

function hostinger_get_login_link( $args )
{
    $query_args = [
        'platform' => $args['redirect_location'],
    ];

    if (!empty($args['client_id'])) {
        $query_args['client_id'] = $args['client_id'];
    }

    if (!empty($args['acting_client_id'])) {
        $query_args['acting_client_id'] = $args['acting_client_id'];
    }

    return add_query_arg( $query_args, admin_url() );
}

function hostinger_callback( $args )
{
    if ( empty($args['callback_url']) ) {
        return;
    }

    wp_remote_post( $args['callback_url'], ['body' => $args] );
}