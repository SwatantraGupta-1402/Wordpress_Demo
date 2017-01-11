<?php

/*
  Plugin Name: AlphansoTech Plugin
  Description: Plugin for testing purpose
  Version: 1
  Author: AlphansoTech
  Author URI: http://alphansotech.com
 */
add_action('admin_menu', 'at_alphansotech_menu');

function at_alphansotech_menu() {
    add_menu_page('Employee Listing', //page title
            'Employee', //menu title
            'manage_options', //capabilities
            'Employee', //menu slug
            employee_list //function
    );
    add_submenu_page('Employee', //parent slug
            'Add New Employee', //page title
            'Add New', //menu title
            'manage_options', //capability
            'add_new_employee', //menu slug
            add_new_employee);
}

global $at_db_version;
$at_db_version = '1.0';

function at_datatable() {
    global $wpdb;
    global $at_db_version;

    $table_name = $wpdb->prefix . 'employee_list';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		name(100) DEFAULT '' NOT NULL,
		role(100) DEFAULT '' NOT NULL,
		contact(100) DEFAULT '' NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);

    add_option('at_db_version', $at_db_version);
}

register_activation_hook(__FILE__, 'at_datatable');

define('ROOTDIR', plugin_dir_path(__FILE__)); // returns the root directory path of particular plugin
require_once(ROOTDIR . 'employee_list.php');
require_once(ROOTDIR . 'add_employee.php');
?>