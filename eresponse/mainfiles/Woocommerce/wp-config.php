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
define('DB_NAME', 'woocommerce');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'distecna');

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
define('AUTH_KEY',         'rMqIOa_.2_*||?e8wsXC<g@My}U@N)/jlj`SUTq?GsN_C52m#D>X$Z&J:teQn!G@');
define('SECURE_AUTH_KEY',  'tEklSZtv4sU+L.+O77Ri<61J64}cQfN<#},B8OD~3o=bj(/>&Z;;{^g@#^))(.& ');
define('LOGGED_IN_KEY',    'StBV3%nia3l=-zk..:jkz3!x4uQ(8!;FTf^?BStbJ+jb?iWFe ngW:Is&oP-4G{q');
define('NONCE_KEY',        'DnDni-f-jL~|5O,4R`5WFL`H:w`#:hSqyS(+;[FZ1=<n~?)#Iw$Q9+>d7x5:e*BW');
define('AUTH_SALT',        'OD[E(ENtmHbjLt!KT4fTt<yzsdlv{jVHT3;<we-<xX9op!czX3`h8uT@}XK1b?io');
define('SECURE_AUTH_SALT', 'cea87|+vWFY9i:O&<_JzXa9atsh05e Jn$l_fg.t1D)GjC==e<;Ot7*dp6VbUAT[');
define('LOGGED_IN_SALT',   '%[:_lW2+<.|1tE71$$!j,<&dA9sVh)i2vBFU@3@5!VroL@8v_k/Y1O{M(RB*8$?c');
define('NONCE_SALT',       'G669ZCb2XwI$]aqP0RGR)zGH2N1FAh1MM?-uf|a7L7r/70DGGK[P)@vnj`qfV(&F');

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
set_time_limit(600);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
