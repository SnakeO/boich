<?php

// Display Stripe iFrame Page

define('WP_USE_THEMES', false);
require('../../../../wp-blog-header.php');

header('Content-Type: text/html; charset=' . get_bloginfo( 'charset') );

do_action( 'social_connect_form' );

?>