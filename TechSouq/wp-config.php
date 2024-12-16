<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'TechSouq' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'T-@o;9r25YRJuoeezNUaHCz>{T=$Ik`Tqf&<)z>-3q?l$=),AR>rWk#Sx0$9jfI%' );
define( 'SECURE_AUTH_KEY',  '$&Z2[jibQwPnFzx9p!m0z5N*<q~X90x80=/msy<,o<|Z]JjDLhdgbb^JSJ[$=Z1k' );
define( 'LOGGED_IN_KEY',    '=O=Z{mmnLMTG:H(Re*MgX<Ju~^w/_OHYpOOs,hX%Oc,,8]d]PsN|&a]WWjn`z=&[' );
define( 'NONCE_KEY',        'eHZ$3|6:j`a[D67+O@?C<>x4 0KFY:H#A&_gBaw~/t0>1t<m2dhgu#:%u*q,`w5L' );
define( 'AUTH_SALT',        '>9L^[,Pe39O0l:<nj3bq^RtldhtnFY7@&&~T!u=%wWNqv+2r_<IX*=[r+Hf=V/BD' );
define( 'SECURE_AUTH_SALT', 'vSpoNn:]#9,P7$J=j-I/0>usu*L`Y>kqxAQ.@E+v&!{UDI&yFCuzqb<e(-<WMez3' );
define( 'LOGGED_IN_SALT',   'EKybcOh1%pd?}B.ce59psov{5+!5+:U/*6:#G (%]&mh77_;4QqPw3,4}Q|0O9zb' );
define( 'NONCE_SALT',       '`TS$80-k3]J^%-Q.@`-!dqO*MU]I3Eu24$4|SGIpW4*#D,Y,d;QhhOqrc=x,uq 4' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
