<?php
/*
Plugin Name: Plugins Load Order
Plugin URI: http://reveloper.com/blog/plugins-order-wordpress-plugin/
Description: Configure the load order of your active plugins via a simple drag-drop interface
Version: 0.2
Author: Reveloper
Author URI: http://reveloper.com/
Text Domain: com.reveloper.plugins-load-order
*/

// regiester the plugin link into the admin menu
add_action('admin_menu', 'plugin_load_order_menu');

// add the plugin link into the admin menu
function plugin_load_order_menu() {
	add_options_page('Plugins load order', 'Plugins order', 'manage_options', 'com.reveloper.plugins-load-order', 'plugin_load_order_options');
}

// plugin options admin page
function plugin_load_order_options() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	
	if( $_POST['list'] ) {
		backup_plugins_order();
		save_plugins_order( json_to_array( $_POST['list'] ) );
	}
	
	list_plugins();
}


// saves the custom plugins order
function save_plugins_order( $pluginsList = array() ) {
	
	update_option( 'active_plugins_sorted', $pluginsList );
	update_option( 'active_plugins', $pluginsList );
	
}

// backup previous order - just in case. not used yet
function backup_plugins_order() {
	update_option( 'active_plugins_backup', get_option('active_plugins') );
}

// plugin admin interface - list the plugins
function list_plugins() {
	
	echo '<div class="wrap">';
	echo '<div id="com-reveloper-plugins-load-order" class="icon32"><br/></div>';
	echo '<h2>Plugins Load Order</h2>';
	
	$activePlugins = get_option('active_plugins');
	if( count($activePlugins) ) {
		echo '<p>This is the list with your active plugins. Drag and drop the liste items to order them - click "Save" when you are done.</p>';
		
		echo '<ol id="pluginsList">';
		
		foreach($activePlugins as $key => $ap) {
			$handle = @fopen(WP_PLUGIN_DIR . '/' . $ap, 'r');
			
			if ($handle) {
				while (($buffer = fgets($handle, 4096)) !== false) {
					if( stripos($buffer, 'Plugin Name:') !== false ) {
						echo '<li id="' . $ap . '" title="Drag and drop to sort the plugins">' . trim( str_ireplace( array('Plugin Name:', '/', '*', '\\'), '',  $buffer) ) . '</li>';
						break;
					}
			    }
			    
			    fclose($handle);
			}
		}
		
		echo '</ol>';
		
		echo '<form action="" method="POST" id="frmSortPlugins">' . "\n";
		echo '<input type="hidden" name="list" id="hidList" value="" />' . "\n";
		echo '<input type="button" id="saveOrder" value="Save" />' . "\n";
		echo '<input type="button" id="cancelOrder" value="Cancel" />' . "\n";
		echo '</form>' . "\n";
	} else {
		echo '<p>No active plugins found.</p>';
	}
	
	echo '<div id="contactReveloper">Do you have any questions or suggestions? <a href="http://reveloper.com/blog/plugins-order-wordpress-plugin/" target="_blank">Contact us</a>!</div>';
	echo '</div>';
	
	plugin_load_order_css();
	plugin_load_order_js();
}

// save custom sorting when a new plugin is activated
function custom_sort_plugins() {
	$newPlugins = get_option('active_plugins');
	$sortedPlugins = get_option('active_plugins_sorted');
	
	if( !empty($sortedPlugins) ) {
		$diff = array_diff($newPlugins, $sortedPlugins);
		save_plugins_order( array_merge( $sortedPlugins, $diff ) );
	} else {
		save_plugins_order( $newPlugins );
	}
}

// register to be called when new plugin is activated
add_action("activated_plugin", "custom_sort_plugins");

// update the custom sort when plugins are deactivated
// just copy the remaining plugins because the previous sort is preserved
function update_custom_sort( $pluginName = '' ) {
	$sortedPlugins = get_option('active_plugins_sorted');
	
	if( in_array( $pluginName, $sortedPlugins ) ) {
		unset( $sortedPlugins[ array_search ( $pluginName, $sortedPlugins ) ] );
	} else {
		echo 'nu stiu man...';
	}
	save_plugins_order ( $sortedPlugins );
}

// register to be called when a plugin is deactivated
add_action("deactivated_plugin", "update_custom_sort");

// load css files
function plugin_load_order_css() {
	echo '
	<link rel="stylesheet" type="text/css" href="' . plugins_url() . '/com.reveloper.plugins-load-order/css/com.reveloper.plugins-load-order.css" />
	';
}

// hook into <head>
//add_action('admin_head', 'plugin_load_order_css');

// load JS files
function plugin_load_order_js() {
	echo '<script src="' . plugins_url() . '/com.reveloper.plugins-load-order/js/jquery-1.4.2.min.js"></script>' . "\n";
	echo '<script src="' . plugins_url() . '/com.reveloper.plugins-load-order/js/jquery-ui-1.8.6.custom.min.js"></script>' . "\n";
	echo '<script src="' . plugins_url() . '/com.reveloper.plugins-load-order/js/array2json.js"></script>' . "\n";
	echo '<script src="' . plugins_url() . '/com.reveloper.plugins-load-order/js/com.reveloper.plugins-load-order.js"></script>' . "\n";
}

// hook into footer
//add_action('admin_footer', 'plugin_load_order_js');

// utility function - convert json data into PHP array
function json_to_array( $jsonData ) {

	// try native PHP json decoding - need version > 5 - or custom if not available
	if( !function_exists('json_decode') ) { // custom function
		include(WP_PLUGIN_DIR . '/com.reveloper.plugins-load-order/php/json.class.php');
		$json = new JSON();
	
		$pluginsList = $json->decode( stripslashes( $jsonData ) );
	} else { // native function
		$pluginsList = json_decode( stripslashes( $jsonData ) );
	}
	
	return $pluginsList;
}