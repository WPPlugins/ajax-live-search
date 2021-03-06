<?php

add_action( 'admin_menu', 'als_add_page' );
function als_add_page () {
	
$als_page = add_menu_page( 'Ajax Live Search', 
								  'Ajax Live Search', 
								  'manage_options', 
								  'ajax-live-search', 
								  'als_admin_render'
								);

//Set our hook  suffix and active element types
	WE_als( 'ajax-live-search' )->set_instance_args( array(
		'hook_suffix' => $als_page,
		'element_types' => array( 'select', 'color'), 
	));
	
}
	
//Register elements	 
add_action( 'init', 'als_admin_init' );

function als_admin_init() {
	include 'fields.php';
}

function als_admin_render() {
	WE_als( 'ajax-live-search' )->render();	
}