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
define('DB_NAME', 'ecommerce');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'L)J`W]+%kzPv?=VT[C+:XGK[Py/*5|A^OquV9{Y#wZm!>EvYCa`JAu8oN|M_RIZY');
define('SECURE_AUTH_KEY',  'BM1a&,A`Q<(_kSeKJaIY(VPX-F<$EsM0b@[]P1(7Zf-iO(M@W.PkPwJB&#[[%tJ_');
define('LOGGED_IN_KEY',    'DvU6;k?.U!i!Hftt{7|Z4Y@breD:xdtllL>y#RfD}a4O/~i_kr,++3%[]~V&K4<4');
define('NONCE_KEY',        'xZpB+PO2]l6Pv#e?pJaF y`d$r~`qV/u/qn&MI&ORV<N*=xYEXei6a!p8L~BpR)I');
define('AUTH_SALT',        '3a/8_P8&h9oZq)i*w|PR+D22` }$jgNyaD#ZASY7CgD6c)FuWybi:%v^83XF$YU*');
define('SECURE_AUTH_SALT', '9 ]DvBP(|cgdncT<,Y,ur4kO0YQKxR@/d!v(tm[(}c>Z^)!)M1`ai$-V*u-,6-)<');
define('LOGGED_IN_SALT',   '>o0FlNbd([nJe-PGC!.YPp.aEcqBtfvOrjPrH7z;T.g.q*I]S:W}ZgHF8=99^Sx%');
define('NONCE_SALT',       'OF0sR#vk#U2.e^L09`E4hK<UPzbeHbG=eAF9tkX.1_U~xEh|#!9-d?Xu1{_!OU5e');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
