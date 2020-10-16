<?php
/*
Plugin Name: Always-307 Plugin
Plugin URI: https://github.com/tinjaw/Always-307
Description: Send a 307 (temporary) redirect instead of 301 (permanent) for sites where shortlinks may change.
Version: 1.0
Author: Tinjaw
Author URI: https://github.com/tinjaw
*/

// No direct call
if( !defined( 'YOURLS_ABSPATH' ) ) die();

yourls_add_filter( 'redirect_code', 'tinjaw_307_redirection' );

function tinjaw_307_redirection( $code, $location ) {
    return 307;
}