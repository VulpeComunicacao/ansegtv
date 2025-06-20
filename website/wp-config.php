<?php
define( 'WP_CACHE', true );

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u724932974_11EVP' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          '~vk`n/@dI~<4p4^w^p)q[jWbnkBnAoa46Z<)02cA:Pv*BjwNlR{lu9vImt:G.6#y' );
define( 'SECURE_AUTH_KEY',   'B3G?ADOEjHg.#UW*GF}TZZc F{yyyhOPaZw~4uIF8Iuz$9/0K?u9OREAa1<^*V9!' );
define( 'LOGGED_IN_KEY',     '8#QIXemYe])s#d[*c+TwXZ?#J8VR@t0+MF,RUbJ8^/o_XG-SZqbEmT#3q(j/D.<>' );
define( 'NONCE_KEY',         '$<JG3,*SDr8l/|RDL<lZIxs [gNSOs-5m5l%kcSXw?08n?7v(Q*fS~_G}%n-mJ#l' );
define( 'AUTH_SALT',         'i{W4RSC.f7*tv&*[ZAI0I&67fF$:r]~=tsPTl QO}w6M03lP_dOs&xCKXr$zn0m ' );
define( 'SECURE_AUTH_SALT',  'u?q||qbQsbb!kSi<hkMb3(Jk^=`a?oZ#~urmpmU,E1I<E[@|_K/1gv dka}sf]0k' );
define( 'LOGGED_IN_SALT',    'LK)JWZ=.bM4gKwv:d%&Iz0kFrI;hJV-c(}_n!>s:Q}_(4Qru.51N9p5zPnKlA_0*' );
define( 'NONCE_SALT',        '~_s8oc}=R&vcFz(5|&{qECE7@LzxxuknMOl.0Wr%aF<YkS:$$+B1K^$g~+s6T^RX' );
define( 'WP_CACHE_KEY_SALT', 'r(L63vXa)D@RWE])0h8LwfO*RF0IAl[9l3Tp[i,PgA>.btGh$;.x>GH&M56#9_)#' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'FS_METHOD', 'direct' );
define( 'COOKIEHASH', '068bde6d04c3705439b8860b0810d618' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
