<?php

/**
 * Plugin Name: WP Insert Code
 * Plugin URI: https://github.com/javiervilchezl/wp-insert-code
 * Description: Wordpress plugin to insert code in the header, footer and function.php, custom code snippet.
 * Version: 1.1
 * Requires at least: 5.8
 * Requires PHP: 7.3
 * Author: Javier Vílchez Luque
 * Author URI: https://github.com/javiervilchezl/wp-insert-code
 * Licence: GPLv2 or later
 *
 * Copyright 2023-2024 WP Insert Code
 */

defined( 'ABSPATH' ) || exit;

// WP Inser Code defines.
define( 'WPIC_VERSION',               '1.1' );
define( 'WPIC_WP_VERSION',            '5.8' );
define( 'WPIC_WP_VERSION_TESTED',     '6.4' );
define( 'WPIC_PHP_VERSION',           '7.3' );
define( 'WPIC_FILE',                  __FILE__ );
define( 'WPIC_REAL_PATH',             realpath( plugin_dir_path( WPIC_FILE ) ) . '/' );
define( 'WPIC_PATH',                  plugin_dir_path(WPIC_FILE));
define( 'WPIC_ADMIN',            	  WPIC_PATH . '/admin');
define( 'WPIC_IMG',            	      WPIC_PATH . '/assets/images');
define( 'WPIC_CLASSES',               WPIC_ADMIN . '/classes');

require_once(WPIC_CLASSES.'/class-wp-insert-code.php');
require_once(WPIC_CLASSES.'/class-wp-insert-code-management.php');

function wp_insert_code_init(){
    $wpic_insert_code = new Wp_Insert_Code();
}
add_action('init', 'wp_insert_code_init');

register_activation_hook(__FILE__,'wpic_plugin_activate');
register_deactivation_hook(__FILE__,'wpic_plugin_deactivate');

/**
 * Activate wp insert code
 *
 * @since 1.0
 * @author Javier Vílchez Luque
 *
 * @return void
 */
 function wpic_plugin_activate()
{
    //create table
	$wpic_insert_code_table = new Wp_Insert_Code_Management();
	$wpic_insert_code_table->wpic_create_table();
}

/**
 * Deactivate wp insert code
 *
 * @since 1.0
 * @author Javier Vílchez Luque
 *
 * @return void
 */
 function wpic_plugin_deactivate()
{
	//create table
	$wpic_drop_code_table = new Wp_Insert_Code_Management();
	$wpic_drop_code_table->wpic_drop_table();

}

function wpic_insert_head() {
	require_once(WPIC_CLASSES.'/class-wp-insert-code-management.php');
	$wpic_insert_code_head = new Wp_Insert_Code_Management();
	$snippet_head = $wpic_insert_code_head->wpic_get_head_snippet();
	foreach ( $snippet_head as $snippet ) {
		echo $snippet['code'];
	}
}
add_action( 'wp_head', 'wpic_insert_head' );

function wpic_insert_footer() {
	require_once(WPIC_CLASSES.'/class-wp-insert-code-management.php');
	$wpic_insert_code_footer = new Wp_Insert_Code_Management();
	$snippet_footer = $wpic_insert_code_footer->wpic_get_footer_snippet();
	foreach ( $snippet_footer as $snippet ) {
		echo $snippet['code'];
	}
}
add_action( 'wp_footer', 'wpic_insert_footer' );

require_once(WPIC_CLASSES.'/class-wp-insert-code-management.php');
$wpic_insert_code_function = new Wp_Insert_Code_Management();
$snippet_function = $wpic_insert_code_function->wpic_get_function_snippet();
foreach ( $snippet_function as $snippet ) {
	eval($snippet['code']);
}

?>