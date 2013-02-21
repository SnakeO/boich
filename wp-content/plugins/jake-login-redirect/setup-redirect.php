<?php

session_start();

define('WP_USE_THEMES', false);
require('../../../wp-blog-header.php');

$_SESSION['jake_redirect'] = @$_GET['redirect'];
header("Location: " . site_url('wp-login.php'));

?>