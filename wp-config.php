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
define('DB_NAME', 'wordpresstest');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

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
define('AUTH_KEY',         'lJT,Hq=,4NCN4-] q:_*-Tq.Ejdy^ EXl`(Le:[s8e:8|:|u9_@c.4Yj7,sjE-2(');
define('SECURE_AUTH_KEY',  '|`_U,WXAm%PQ<}[Ddm|)p+Of]ZtRPz:^-|d(V=*[g/^:t-+0|h`yS4${,3-ApfTL');
define('LOGGED_IN_KEY',    'hkgaiM$=RK~)UAJ!%UBYhfN>-;]@xuWxWWa<!g9{I27=0r(BzXY}jz)QRm@%E*+&');
define('NONCE_KEY',        'PaL[5-8K+?(1YO=ep4T&;,gjfLYjt>E=2;mRwxu`>^(b?Ub}y%wl&Cll3)72PQF-');
define('AUTH_SALT',        'KFF/Qv!0vI0@#`~W)W1;NZ2i|7?OalF.?UZuwwXRfYMAgDgzC+aOo!CNsi^4@++^');
define('SECURE_AUTH_SALT', 'u-5JCbb^NIsm~k-ysM6+/pQ1j-#+wr3xHSj~-SrPu+P/k|[6S~bY/_,<R^<am4?>');
define('LOGGED_IN_SALT',   ' g+mMFi1_,,o|!yzq/4++R]SUL2!hYV[#&B;u3/F:fk`G+3L+@a5!w_fwFIQC+n4');
define('NONCE_SALT',       'pF[12x^O,38=>8i==)qx+#+%&UNL]?TP.c@q<?J$<BLSA:(zJn+^,P)1k 6MI+y}');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_wordpress';

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
