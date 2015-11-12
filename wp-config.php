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
define('DB_NAME', 'washtime');

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
define('AUTH_KEY',         'vt.=|~Zs-lv=En.%.2J`H-M+3chn&{so:.DQ3+x?NW-Eh7X;O~5%KiFj{kw_>j|X');
define('SECURE_AUTH_KEY',  'kW0B6I]d|xoeVOkU~2Q TT$eqBC%CI+t%[{dN)tiz@W4+&wU$J8$G*gsD1O)3!3{');
define('LOGGED_IN_KEY',    'iMdO52@Z^j0JI=k-K)$0uyhOhb|yzO-mMAHA <oJixF`rpN/a**;cj40I!c9q!I4');
define('NONCE_KEY',        '_1<DxT]J+Z>su- +nY^$YT!z3 Dcv[bb-0fC2:}rxy}Dr-u37X!MNAX]|uE-Gz[#');
define('AUTH_SALT',        '#)Kz|*88nq^|0uUzw0fOf22G0uwyp_T,klWY*$zvXtwpxb#z#a<ML;&=--fEsD*N');
define('SECURE_AUTH_SALT', '5]uH%NONdM6}}p.U89}d3cd(bM%7A9qiam[Q>1QSrO<l/[$)(W%9sPL2F7!5aGar');
define('LOGGED_IN_SALT',   'Y9a*u1_;20~BF;cbw%5g6%mP(5cGw7LNrVY5+SSp@0CH4UL9W-#0m5ie]V9jHL6*');
define('NONCE_SALT',       'QP]&:o#he2v&rwe9}cCTI|V>EMP@}vXo|l(g22>E)z|e `DKHzxG3a[AIv/17ed2');

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
