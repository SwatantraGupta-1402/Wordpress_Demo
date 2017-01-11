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
define('DB_NAME', 'wordpress_tutorials');

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
define('AUTH_KEY',         'F>&VgcxN2wS]CYd!LuHjNmilhKjkcE(^z3Vh1^@OGf*P.nhmP[v0?37DZU?#qds>');
define('SECURE_AUTH_KEY',  't0><N86Tq5pi]*Ue*D B//O/2F`uInG;j>O+u:vA >PtICSA8y3Y0m].,p{LgL9X');
define('LOGGED_IN_KEY',    'b]}%K)w6b/ uAPT>xJ7|UL2b%7+Z/-3P=&1F.}c?$,n1FXM$AZ|9LhOmIq-1AHTF');
define('NONCE_KEY',        'T]AG|?{.P8{ghOJ~2-|Z4)D^l0i,)jCZT)BP=|)i<L<3GpfHgs11fXv6A&`Q)^yl');
define('AUTH_SALT',        'Q7-G.U+H13@r.@>s?8A;N_RcH#wP[#2Q?ZN(C(Ji*rtEE#6O-Phet*:&^CG/A>4[');
define('SECURE_AUTH_SALT', 'k`>&JNG[brE&KT:LMw___{![X2R]*|=s|i53OkL^i8FT@-[KuN#7M`l&5jBhMjmj');
define('LOGGED_IN_SALT',   '|}@`B_aK-,2WrzNEIr%Fl&Ze^W9=%F*!4Bi).I%[v Y`^W|<f(RWO/Dz^*_XaKY5');
define('NONCE_SALT',       '_:}4M$Wmp971-$7`M=j)5J}W.~JY!/_`UL}y-/_K.@Qsc[#Nr[0EV{([bp{4%3fR');

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
