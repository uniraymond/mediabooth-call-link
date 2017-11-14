<?php
/**
 * Plugin Name: Mediabooth Call Link
 * Plugin URI: http://mediabooth.com.au/plugins/mediabooth-call-link
 * Author: Raymond F.
 * Version: 0.0.1
 * License: GPLv2
 * Description: A call link plugin for wordpress mobile size contact call or send email.
 */

define('MCL_VERSION', '0.0.1');
define('MCL_PLUGIN', __FILE__);
define('MCL_PLUGIN_BASENAME',plugin_basename(MCL_PLUGIN));
define('MCL_PLUGIN_NAME',trim(dirname(MCL_PLUGIN_BASENAME), '/'));
define('MCL_PLUGIN_DIR', untrailingslashit(dirname(MCL_PLUGIN )));

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

// Deprecated, not used in the plugin core. Use mcl_plugin_url() instead.
define( 'MCL_PLUGIN_URL', untrailingslashit( plugins_url( '', MCL_PLUGIN ) ) );

require_once MCL_PLUGIN_DIR . '/settings.php';