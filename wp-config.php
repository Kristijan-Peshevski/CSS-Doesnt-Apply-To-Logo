<?php
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
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'kristijan peshevski' );

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
define( 'AUTH_KEY',         'Zkqqfmgj:znvoY&<)0r~.8Qh|-&fw1pk3Sfw^B8XyUx7CmO!OB>{V|TC6W9IcYSI' );
define( 'SECURE_AUTH_KEY',  '!$59/`BZh7t2(hi)&/x47pGBD/s)k/!p/yj7=$iH_Ak=+AjkJ@AnY.Bu~}=yS>!:' );
define( 'LOGGED_IN_KEY',    's@AzBBwnfru=jIzw(sK0IJH->j]m#n-Rw%Rbtoqlw1Jch!IKhuDyoU`?Z<Z:~Aeh' );
define( 'NONCE_KEY',        'WiK^4:i9&,j]Vtr~c4mnw q;&xj}tTqZMbspIf*]g+EOv-vy`(`7TyiQWT-rxpTd' );
define( 'AUTH_SALT',        'Ygny/U+}Bu,fKB-yEF)lb$`i^?3T+MxJ-!i /h`|{tRI,gRq-0MgDvoy0]C05{Z.' );
define( 'SECURE_AUTH_SALT', '1=)0v &B2!ylm0[:j>d>LH;k3adRi8}MVFwij]6$^lAp$SwhgXy2emd>T[9rYzIP' );
define( 'LOGGED_IN_SALT',   'vR10ZY*#V4%oRrj3$Kl@8gPqu`<|QEAE]uzddoA6v$*E*iN=_#ghejYWcGHcb26g' );
define( 'NONCE_SALT',       'Q|tj_?(Q0n~fd~3Ofb?9R%NE{wpR$yuQ|] iWS&zd:IegbH$z]2/vffiv(KOYnv+' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'kristijanpeshevski';

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
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
