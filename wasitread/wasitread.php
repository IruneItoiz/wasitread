<?php
/*
Plugin Name: Was It Read?
Description: Enable tracking in Google Analytics to see how far down the page the user scrolled
Version: 1.0.0
License: GPL-2.0+
*/

use WasItRead\Plugin;
use WasItRead\SettingsPage;
use WasItRead\JSManager;

spl_autoload_register( 'wasitread_autoloader' ); // Register autoloader

function wasitread_autoloader( $class_name ) {

    if ( false !== strpos( $class_name, 'WasItRead' ) ) {
        $classes_dir = realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
        $class_file = str_replace( '\\', DIRECTORY_SEPARATOR, $class_name ) . '.php';

        require_once $classes_dir . $class_file;
    }
}

add_action( 'plugins_loaded', 'wasitread_init' ); // Hook initialization function
function wasitread_init() {
	$plugin = new Plugin();
	$plugin['path'] = realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR;
	$plugin['url'] = plugin_dir_url( __FILE__ );
	$plugin['version'] = '1.0.0';
	$plugin['settings_page_properties'] = array(
		'parent_slug' => 'options-general.php',
		'page_title' =>  'Was It Read - Settings',
		'menu_title' =>  'Was It Read',
		'capability' => 'manage_options',
		'menu_slug' => 'wasitread-settings',
		'option_group' => 'wasitread_option_group',
		'option_name' => 'wasitread_option_name'
	);
	
	$plugin['settings_page'] = function ( $plugin ) {
        return new SettingsPage( $plugin['settings_page_properties'] );
    };

    $plugin['add_js_scripts'] = function ( $plugin ) {
        return new JSManager( plugin_dir_url( __FILE__ ), $plugin['settings_page_properties']  );
    };
	$plugin->run();
}
