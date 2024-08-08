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
define( 'DB_NAME', 'prados_db04' );

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
define( 'AUTH_KEY',         'vCwlS4<Pjifn54sy~MgRaRId,IjxmIfnl%NOj]J%@5EXE+QS5<r>dQ$Ksk6i{^lq' );
define( 'SECURE_AUTH_KEY',  'UbSm/+j5roAB!,%Q#I96r`=TzggG!R~n`,u?KMQ@$>70~1vM8g{t-&,eu@n3*GzZ' );
define( 'LOGGED_IN_KEY',    'f.DY6h!)0Cx>a|*~x&a>i[qdWbEN0enwyWBK,FFMdOO~,c6AXy4f~vB14umT?(h:' );
define( 'NONCE_KEY',        'FbQ>JI?XV4H,dQz{jLrVldeH9$]1_rOOH<}[UgB>mS#;`#xjU<N$j`1<|9(%i&}h' );
define( 'AUTH_SALT',        'y:J&IPV%6[qeo!CK7WD%UK<G|6Ei4Mn7`JuUq@gO-fPHhwV;R~E%6U@~|dmy0pJ;' );
define( 'SECURE_AUTH_SALT', '_rzw$f1U;T,1A/WqM s>qYOncDEtS:MkDO|3?saNIt/{.&.QF7f/^YL<okdsX8sM' );
define( 'LOGGED_IN_SALT',   'B 6H5GX.3[!&Iq#hCTt!cKNE[K>5KH/P[wVJ~?-[MhKq6j_@*3lf:}a[bCWp9UUx' );
define( 'NONCE_SALT',       'mg@|Za!VPW-wmT[!VIpE[43|Se5R=hZ1m %mbpC88.i*O(&rmT1=pi1^xIo`~hND' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
