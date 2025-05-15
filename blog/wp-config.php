<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u112236579_YWL9P' );
/** MySQL database username */
define( 'DB_USER', 'u112236579_bSMj7' );
/** MySQL database password */
define( 'DB_PASSWORD', 'Mnf2swKiuM' );
/** MySQL hostname */
define( 'DB_HOST', '127.0.0.1' );
/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );
/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );
/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'aD]YNwLJXQ1.n/A[<-dc$~=m@m{;Kgulc5|>=MH:K$cO.RdGeSoSi$oJkG:Ew,?P' );
define( 'SECURE_AUTH_KEY',   'Kq;xb*.[ETd{JGnc<Z3Z4<l+;InvNPnM5(sM2g(6;Eqw)trmY:9x24F]MM.|X|+l' );
define( 'LOGGED_IN_KEY',     'dl1.Zj,<m2^G *<chij2/| 7um|H&K*R[RRCae|WeOTE-(.CVM,}0-N-B>6YjR0E' );
define( 'NONCE_KEY',         '|BHyP#q./9`jR8f!n.[m%=-yKI`7#rgm!qZ0nXo<lw|KJV$46o&Hw0-FxM+mTS]%' );
define( 'AUTH_SALT',         '@CTD9^`B8l0rU~LJJM.@u%2Z~P$WOeH*{0*LVlFn,0z$bVzo?r@U!i^AAj55Iaue' );
define( 'SECURE_AUTH_SALT',  'Ek!pV~t,JhML-p-?u)Q#?sUPI)wvrPu-2o/e@EN^,]-?i>AB-p5@!~n7[MlSzeDo' );
define( 'LOGGED_IN_SALT',    'kre+$[Q?p6&GE~9-H}*Y8MC3sEfWHD9<~62rpZ!**$8mhWrdLLCsm1Z5{<YPrV%,' );
define( 'NONCE_SALT',        'epmv./@eA%+^p}J.]hqG<@q6l:<VA[}rFDY/7~fajZk5|?OV*ca|Ybh^/Rin]IQB' );
define( 'WP_CACHE_KEY_SALT', '[c5F926iZVZ N0%;vYRlEHpPWgML)ak|4mC.g1gL]=MoXersTd:@;3@M9CZ7dR9I' );
/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
define( 'FS_METHOD', 'direct' );
/* That's all, stop editing! Happy publishing. */
/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}
/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';