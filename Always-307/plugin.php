<?php
/*
Plugin Name: Always-307 Plugin
Plugin URI: https://github.com/jaslin/Always-307
Description: Send a 307 (temporary) redirect instead of 301 (permanent) for sites where shortlinks may change.
Version: 1.0
Author: jaslin
Author URI: https://github.com/jaslin
*/

// No direct call
if( !defined( 'YOURLS_ABSPATH' ) ) die();

yourls_add_filter( 'redirect_code', 'jaslin_307_redirection' );

function jaslin_307_redirection( $code, $location ) {
    return 307;
}