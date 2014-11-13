<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */



// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'newitlocator');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/** Disable all update  */
define( 'AUTOMATIC_UPDATER_DISABLED', true );

/** Disable only WP update  
define( 'WP_AUTO_UPDATE_CORE', false );	

*/

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'Vbx7@cs#{W,_=Bm--7;M47%enYELb3>5u+vLz[r6(brI)a,Jb+`,`#?}&Ue1mU}|');
define('SECURE_AUTH_KEY',  '8Irfvk`(n=&*ak-WBFLq npVJ|{w68Xw;#Jp6Xh=o%1ir2g+A/t7x|CC^Tzi.sB%');
define('LOGGED_IN_KEY',    'K`EI:K5._1Y|;Tnl1XS`<C;x[yT-/f-LEh3-N#]?in$zH>uQ!uA8n.6-,e8EOU-]');
define('NONCE_KEY',        '}wI|4YE`;Scs`&tls{(g0:rH+tYCR{BK.m62?5.1H2/V|0nf6^5/:AT-@ww58uu|');
define('AUTH_SALT',        '634|vJ GaOtyBe-3Iz3vIsXD&=_P%>+xu/Bvt?mSzgK(?ib0JC|eXr|L.&Wh)ISa');
define('SECURE_AUTH_SALT', 'vQDceKwKh#j3%>0FP|y+[NWTTqH>$hl~%s/ cJ!Ty,E {(q/CU,|P09aV.: 7V)=');
define('LOGGED_IN_SALT',   '<sgi_mYu+M9qWy1?Uu?_s~-1/Gwb(RnqM#L-%w8--0W|f;%%ZIGoP0s-cVg:5Sel');
define('NONCE_SALT',       'w<:9F}nR:Z~?0&._vBX@P-8rr-H%L+Y2ZK0_tD+<&RVaVh},qAMdB_AKB-s<I*s9');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);
// define('WP_DEBUG_DISPLAY', true);
// define('SCRIPT_DEBUG', true);
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');