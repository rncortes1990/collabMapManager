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
define('DB_NAME', 'projecto');

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
define('AUTH_KEY',         'hzO^}H_@g8ZV/4 .iv5)(91p0;z~~jHnuUpG:XA;P]D?j}G:RL*nW<npMQ{U:Lmw');
define('SECURE_AUTH_KEY',  '@DmJ63rF;)6|C>CxR76Ul}`z+Ycjq5Q[j.BY:?8?~pEfhi9FRp{r1Xdk)>{0-?ls');
define('LOGGED_IN_KEY',    '>D1>A%k|eYQiz1;O^NI%7g3/S|x<O0!-&7V#p}E)(YXfaI3d&HGU&az1lt4 Kvp;');
define('NONCE_KEY',        'sc[S-Q!Yk?r SG-%VQvm`>u5t07qTIDFS}VO;1Py%({P)sS2JrEzPD:wSG}3^h.A');
define('AUTH_SALT',        '_`#R7[ZSwB|}m[&1]Wq+rC%>d:Y$F ODVKK/za6p2#fiGt~(0CS=&;,Tw/k_cjl~');
define('SECURE_AUTH_SALT', 'z(Q#JH|lgkSwDg h?jc9:-b+z{X]m~kydc^Fk^KfD_j9;EWc~9p[2vafsgu|`n2 ');
define('LOGGED_IN_SALT',   '6dzjQu@uh*G8M`kbVGue;Z^F4$Y#Xq>Sn N95}8 eWv/UI=LIl4yciAH5eUv5K2q');
define('NONCE_SALT',       'TI~:pnzu[C8b~:){jjA~N)4p*4!ooxuuV];C+OYV8QE3w)y/jP|lN$TS@MAF,F!N');

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
