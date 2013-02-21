<?php
/*
Plugin Name: Jake Login Redirect
Description: Handle redirect after login
Version: 1.0
Author: Jake Chapa
 */

define ( 'JAKE_LOGIN_URL',  WP_PLUGIN_URL . '/' . end( explode( DIRECTORY_SEPARATOR, dirname( __FILE__ ) ) ) );

function jake_login_redirect($redirect)
{
	if( @$_SESSION['jake_redirect'] )
	{
		$redirect = $_SESSION['jake_redirect'];
		unset($_SESSION['jake_redirect']);
		return $redirect;
	}

	return $redirect;
}

add_filter('login_redirect', 'jake_login_redirect');

// helper function in case you want to setup the redirect
function jake_login_url($redirect)
{
	return JAKE_LOGIN_URL . '/setup-redirect.php?redirect=' . urlencode($redirect);
}

?>