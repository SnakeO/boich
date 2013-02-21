<?php

/*
Plugin Name: Jake - API
Plugin URI:
Description: Jake's API Framework
Author: Jake Chapa
Version:
Author URI:
*/

// use sessions
@session_start();

define('API_URL', WP_PLUGIN_URL."/".dirname( plugin_basename( __FILE__ ) ) );
define('API_PATH', WP_PLUGIN_DIR."/".dirname( plugin_basename( __FILE__ ) ) );

// TimThumb. use it like this: TIMTHUMB?src=X
define('TIMTHUMB', API_URL . '/timthumb.php');

// PHPQuery
require_once(API_PATH . '/phpQuery-onefile.php');

// RedBean
if( !class_exists('R') )
{
    require_once(API_PATH . '/rb.php');
    R::setup("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
}

/* -- SCRIPTS -- */
function api_js_and_css() 
{
    // js
    wp_enqueue_script('api_js', API_URL . '/js/api.js', array('jquery'));
    wp_enqueue_script('api_jquery_form_js', API_URL . '/js/jquery.form.js', array('jquery'));
}
add_action('wp_enqueue_scripts', 'api_js_and_css');

// json, or return
global $api_result_mode;
$api_result_mode = 'json';

function api_set_mode($mode)
{
    global $api_result_mode;
    $api_result_mode = $mode;
}

// simple JSON api
function api_result($success, $result=array())
{
    global $api_result_mode;
    
    $result['success'] = $success;
    
    if( $api_result_mode == 'json' )
    {
        $json = json_encode($result);

        // jsonp?
        $callback = @$_REQUEST['callback'];
        if( $callback != null ) 
        {
            $json = $callback . "($json)";
            header("Content-Type: text/javascript");
        }

        echo $json;
    }
    else if( $api_result_mode == 'return' )
    {
        // use StdClass
        return json_decode(json_encode($result));
    }
    else echo "bad result mode: $api_result_mode";
}

function api_success($result=array())
{
    return api_result(true, $result);
}

function api_fail($msg, $result=array())
{
    $result['message'] = $msg;

    if( !@$result['reason'] )
        $result['reason'] = 'api_die';

    return api_result(false, $result);
}

function api_die($msg, $result=array())
{
    return api_fail($msg, $result=array());
    die();
}

?>