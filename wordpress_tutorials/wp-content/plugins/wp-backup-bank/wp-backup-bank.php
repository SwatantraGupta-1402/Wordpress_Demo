<?php
/*
Plugin Name: Backup Bank
Plugin URI: https://beta.tech-banker.com
Description: Easy Backup Plugin for WordPress to create, download and restore backups of your WordPress website.
Author: Tech Banker
Author URI: https://beta.tech-banker.com
Version: 3.0.16
License: GPLv3
*/
if(!defined("ABSPATH")) exit; // Exit if accessed directly
$current_year = date("Y");
$current_month = date("m");
$current_date = date("d");

/* Constant Declaration */
if(!defined("BACKUP_BANK_DIR_PATH")) define("BACKUP_BANK_DIR_PATH",plugin_dir_path(__FILE__));
if(!defined("BACKUP_BANK_CONTENT_DIR")) define("BACKUP_BANK_CONTENT_DIR", dirname(dirname(BACKUP_BANK_DIR_PATH)));
if(!defined("BACKUP_BANK_BACKUPS_DIR")) define("BACKUP_BANK_BACKUPS_DIR", BACKUP_BANK_CONTENT_DIR."/wp-backup-bank");
if(!defined("BACKUP_BANK_BACKUPS_YEAR_DIR")) define("BACKUP_BANK_BACKUPS_YEAR_DIR", BACKUP_BANK_BACKUPS_DIR."/".$current_year);
if(!defined("BACKUP_BANK_BACKUPS_MONTH_DIR")) define("BACKUP_BANK_BACKUPS_MONTH_DIR", BACKUP_BANK_BACKUPS_YEAR_DIR."/".$current_month);
if(!defined("BACKUP_BANK_BACKUPS_DATE_DIR")) define("BACKUP_BANK_BACKUPS_DATE_DIR", BACKUP_BANK_BACKUPS_MONTH_DIR."/".$current_date);
if(!defined("BACKUP_BANK_URL_PATH")) define("BACKUP_BANK_URL_PATH",plugins_url(__FILE__));
if(!defined("BACKUP_BANK_PLUGIN_DIRNAME")) define("BACKUP_BANK_PLUGIN_DIRNAME", plugin_basename(dirname(__FILE__)));
if(!defined("wp_backup_bank")) define("wp_backup_bank", "wp-backup-bank");

if(!defined("BACKUP_BANK_FOLDER_DROPBOX")) define("BACKUP_BANK_FOLDER_DROPBOX", "wp-backup-bank/".$current_year."/".$current_month."/".$current_date."/");
if(!defined("BACKUP_BANK_SET_TIME_LIMIT")) define("BACKUP_BANK_SET_TIME_LIMIT", 0);
if(!defined("BACKUP_BANK_WARN_DB_ROWS")) define("BACKUP_BANK_WARN_DB_ROWS", 150000);
if(!defined("BACKUP_BANK_WARN_FILE_SIZE")) define("BACKUP_BANK_WARN_FILE_SIZE", 1024*1024*250);

if(is_ssl())
{
	if(!defined("tech_banker_url")) define("tech_banker_url", "https://tech-banker.com");
	if(!defined("tech_banker_beta_url")) define("tech_banker_beta_url", "https://beta.tech-banker.com");
}
else
{
	if(!defined("tech_banker_url")) define("tech_banker_url", "http://tech-banker.com");
	if(!defined("tech_banker_beta_url")) define("tech_banker_beta_url", "http://beta.tech-banker.com");
}

if(!function_exists("backup_folders_for_backup_bank"))
{
	function backup_folders_for_backup_bank()
	{
		if(!is_dir(BACKUP_BANK_BACKUPS_DIR))
		{
			wp_mkdir_p(BACKUP_BANK_BACKUPS_DIR);
		}
	}
}

/*
Function Name: install_script_for_backup_bank
Parameters: No
Description: This function is used to create tables in database.
Created On: 05-02-2016 11:34
Created By: Tech Banker Team
*/

if(!function_exists("install_script_for_backup_bank"))
{
	function install_script_for_backup_bank()
	{
		global $wpdb;
		if(is_multisite())
		{
			$blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
			foreach($blog_ids as $blog_id)
			{
				switch_to_blog($blog_id);
				$version = get_option("backup-bank-version-number");
				if($version < "3.0.2")
				{
					if(file_exists(BACKUP_BANK_DIR_PATH."lib/install-script.php"))
					{
						include BACKUP_BANK_DIR_PATH."lib/install-script.php";
					}
				}
				restore_current_blog();
			}
		}
		else
		{
			$version = get_option("backup-bank-version-number");
			if($version < "3.0.2")
			{
				if(file_exists(BACKUP_BANK_DIR_PATH."lib/install-script.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."lib/install-script.php";
				}
			}
		}
	}
}

/*
Function Name: get_others_capabilities_backup_bank
Parameters: No
Description: This function is used to get all the roles available in WordPress
Created On: 24-10-2016 03:55
Created By: Tech Banker Team
*/

if(!function_exists("get_others_capabilities_backup_bank"))
{
	function get_others_capabilities_backup_bank()
	{
		$user_capabilities = array();
		if(function_exists("get_editable_roles"))
		{
			foreach(get_editable_roles() as $role_name => $role_info)
			{
				foreach($role_info["capabilities"] as $capability => $values)
				{
					if(!in_array($capability,$user_capabilities))
					{
						array_push($user_capabilities,$capability);
					}
				}
			}
		}
		else
		{
			$user_capabilities = array(
				"manage_options",
				"edit_plugins",
				"edit_posts",
				"publish_posts",
				"publish_pages",
				"edit_pages",
				"read"
			);
		}
		return $user_capabilities;
	}
}

/*
Function Name: check_user_roles_backup_bank
Parameters: Yes($user)
Description: This function is used for checking roles of different users.
Created On: 24-10-2016 03:55
Created By: Tech Banker Team
*/

if(!function_exists("check_user_roles_backup_bank"))
{
	function check_user_roles_backup_bank($user = null)
	{
		$user = $user ? new WP_User( $user ) : wp_get_current_user();
		return $user->roles ? $user->roles[0] : false;
	}
}

/*
Function Name: backup_bank
Parameters: No
Description: This function is used for creating parent table.
Created On: 05-02-2016 11:57
Created By: Tech Banker Team
*/

if(!function_exists("backup_bank"))
{
	function backup_bank()
	{
		global $wpdb;
		return $wpdb->prefix."backup_bank";
	}
}

/*
Function Name: backup_bank_restore
Parameters: No
Description: This function is used for creating backup_bank_restore table.
Created On: 20-04-2016 05:38
Created By: Tech Banker Team
*/

if(!function_exists("backup_bank_restore"))
{
	function backup_bank_restore()
	{
		global $wpdb;
		return $wpdb->prefix."backup_bank_restore";
	}
}

/*
Function Name: backup_bank_meta
Parameters: No
Description: This function is used for creating meta table.
Created On: 05-02-2016 11:57
Created By: Tech Banker Team
*/

if(!function_exists("backup_bank_meta"))
{
	function backup_bank_meta()
	{
		global $wpdb;
		return $wpdb->prefix."backup_bank_meta";
	}
}


$backup_bank_version_number = get_option("backup-bank-version-number");
if($backup_bank_version_number == "3.0.2")
{
	/*
	Function Name: backend_js_css_for_backup_bank
	Parameters: No
	Description: This function is used for including backend js and css
	Created On: 05-02-2016 11:38
	Created By: Tech Banker Team
	*/

	if(is_admin())
	{
		if(!function_exists("backend_js_css_for_backup_bank"))
		{
			function backend_js_css_for_backup_bank($hook)
			{
				$pages_backup_bank = array
				(
					"bb_manage_backups",
					"bb_start_backup",
					"bb_schedule_backup",
					"bb_alert_setup",
					"bb_other_settings",
					"bb_dropbox_settings",
					"bb_email_settings",
					"bb_ftp_settings",
					"bb_amazons3_settings",
					"bb_onedrive_settings",
					"bb_rackspace_settings",
					"bb_ms_azure_settings",
					"bb_google_drive",
					"bb_email_templates",
					"bb_roles_and_capabilities",
					"bb_feature_requests",
					"bb_system_information",
					"bb_premium_editions"
				);
				foreach($pages_backup_bank as $page_id => $page)
				{
					if(strpos($hook,$page) !== false)
					{
						wp_enqueue_script("jquery");
						wp_enqueue_script("jquery-ui-datepicker");
						wp_enqueue_script("bootstrap.js",plugins_url("assets/global/plugins/custom/js/custom.js",__FILE__));
						wp_enqueue_script("bootstrap-tabdrop.js",plugins_url("assets/global/plugins/tabdrop/js/tabdrop.js",__FILE__));
						wp_enqueue_script("jquery.validate.js",plugins_url("assets/global/plugins/validation/jquery.validate.js",__FILE__));
						wp_enqueue_script("jquery.datatables.js",plugins_url("assets/global/plugins/datatables/media/js/jquery.datatables.js",__FILE__));
						wp_enqueue_script("jquery.fngetfilterednodes.js",plugins_url("assets/global/plugins/datatables/media/js/fngetfilterednodes.js",__FILE__));
						wp_enqueue_script("toastr.js",plugins_url("assets/global/plugins/toastr/toastr.js",__FILE__));
						wp_enqueue_style("simple-line-icons.css", plugins_url("assets/global/plugins/icons/icons.css",__FILE__));
						wp_enqueue_style("components.css", plugins_url("assets/global/css/components.css",__FILE__));
						wp_enqueue_style("wp-backup-bank-custom.css", plugins_url("assets/admin/layout/css/wp-backup-bank-custom.css",__FILE__));
						if(is_rtl())
						{
							wp_enqueue_style("backup-bank-bootstrap.css", plugins_url("assets/global/plugins/custom/css/custom-rtl.css",__FILE__));
							wp_enqueue_style("backup-bank-layout.css", plugins_url("assets/admin/layout/css/layout-rtl.css",__FILE__));
							wp_enqueue_style("wp-backup-bank-tech-banker-custom.css", plugins_url("assets/admin/layout/css/tech-banker-custom-rtl.css",__FILE__));
						}
						else
						{
							wp_enqueue_style("backup-bank-bootstrap.css", plugins_url("assets/global/plugins/custom/css/custom.css",__FILE__));
							wp_enqueue_style("backup-bank-layout.css", plugins_url("assets/admin/layout/css/layout.css",__FILE__));
							wp_enqueue_style("wp-backup-bank-tech-banker-custom.css", plugins_url("assets/admin/layout/css/tech-banker-custom.css",__FILE__));
						}
						wp_enqueue_style("backup-bank-plugins.css", plugins_url("assets/global/css/plugins.css",__FILE__));
						wp_enqueue_style("backup-bank-default.css", plugins_url("assets/admin/layout/css/themes/default.css",__FILE__));
						wp_enqueue_style("backup-bank-toastr.min.css", plugins_url("assets/global/plugins/toastr/toastr.css",__FILE__));
						wp_enqueue_style("backup-bank-jquery-ui.css", plugins_url("assets/global/plugins/datepicker/jquery-ui.css",__FILE__),false,"2.0",false);
						wp_enqueue_style("backup-bank-datatables.foundation.css", plugins_url("assets/global/plugins/datatables/media/css/datatables.foundation.css",__FILE__));
						wp_enqueue_style("backup-bank-premium-edition.css", plugins_url("assets/admin/layout/css/premium-edition.css",__FILE__));
						break;
					}
				}
			}
		}
		add_action("admin_enqueue_scripts", "backend_js_css_for_backup_bank");
	}

	/*
	Function Name: get_users_capabilities_backup_bank
	Parameters: No
	Description: This function is used to get users capabilities.
	Created On: 21-10-2016 15:21
	Created By: Tech Banker Team
	*/

	if(!function_exists("get_users_capabilities_backup_bank"))
	{
		function get_users_capabilities_backup_bank()
		{
			global $wpdb;
			$capabilities = $wpdb->get_var
			(
				$wpdb->prepare
				(
					"SELECT meta_value FROM ".backup_bank_meta()."
					WHERE meta_key = %s",
					"roles_and_capabilities"
				)
			);
			$core_roles = array(
				"manage_options",
				"edit_plugins",
				"edit_posts",
				"publish_posts",
				"publish_pages",
				"edit_pages",
				"read"
			);
			$unserialized_capabilities = unserialize($capabilities);
			return isset($unserialized_capabilities["capabilities"]) ? $unserialized_capabilities["capabilities"] : $core_roles;
		}
	}

	/*
	Function Name: helper_file_for_backup_bank
	Parameters: No
	Description: This function is used for helper file.
	Created On: 05-02-2016 11:44
	Created By: Tech Banker Team
	*/

	if(!function_exists("helper_file_for_backup_bank"))
	{
		function helper_file_for_backup_bank()
		{
			global $wpdb;
			$user_role_permission = get_users_capabilities_backup_bank();
			if(file_exists(BACKUP_BANK_DIR_PATH."lib/helper.php"))
			{
				include_once BACKUP_BANK_DIR_PATH."lib/helper.php";
			}
		}
	}

	/*
	Function Name: sidebar_menu_for_backup_bank
	Parameters: No
	Description: This function is used for sidebar menu.
	Created On: 05-02-2016 11:47
	Created By: Tech Banker Team
	*/

	if(!function_exists("sidebar_menu_for_backup_bank"))
	{
		function sidebar_menu_for_backup_bank()
		{
			global $wpdb,$current_user;
			$user_role_permission = get_users_capabilities_backup_bank();
			if(file_exists(BACKUP_BANK_DIR_PATH."includes/translations.php"))
			{
				include BACKUP_BANK_DIR_PATH."includes/translations.php";
			}
			if(file_exists(BACKUP_BANK_DIR_PATH."lib/sidebar-menu.php"))
			{
				include_once BACKUP_BANK_DIR_PATH."lib/sidebar-menu.php";
			}
		}
	}

	/*
	Function Name: topbar_menu_for_backup_bank
	Parameters: No
	Description: This function is used for topbar menu.
	Created On: 05-02-2016 11:52
	Created By: Tech Banker Team
	*/

	if(!function_exists("topbar_menu_for_backup_bank"))
	{
		function topbar_menu_for_backup_bank()
		{
			global $wpdb,$current_user,$wp_admin_bar;
			$user_role_permission = get_users_capabilities_backup_bank();
			$role_capabilities = $wpdb->get_var
			(
				$wpdb->prepare
				(
					"SELECT meta_value from ".backup_bank_meta()."
					WHERE ".backup_bank_meta()." . meta_key = %s",
					"roles_and_capabilities"
				)
			);
			$roles_and_capabilities_unserialized_data = unserialize($role_capabilities);
			$top_bar_menu = $roles_and_capabilities_unserialized_data["show_backup_bank_top_bar_menu"];

			if($top_bar_menu == "enable")
			{
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/translations.php"))
				{
					include BACKUP_BANK_DIR_PATH."includes/translations.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."lib/admin-bar-menu.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."lib/admin-bar-menu.php";
				}
			}
		}
	}

	/*
	Function Name: ajax_register_for_backup_bank
	Parameters: No
	Description: This function is used for register ajax.
	Created On: 05-02-2016 11:57
	Created By: Tech Banker Team
	*/

	if(!function_exists("ajax_register_for_backup_bank"))
	{
		function ajax_register_for_backup_bank()
		{
			global $wpdb;
			$user_role_permission = get_users_capabilities_backup_bank();
			if(file_exists(BACKUP_BANK_DIR_PATH."includes/translations.php"))
			{
				include BACKUP_BANK_DIR_PATH."includes/translations.php";
			}
			if(file_exists(BACKUP_BANK_DIR_PATH."lib/action-library.php"))
			{
				include_once BACKUP_BANK_DIR_PATH."lib/action-library.php";
			}
		}
	}

	/*
	Function Name: plugin_load_textdomain_backup_bank
	Parameters: No
	Description: This function is used to load languages.
	Created On: 05-02-2016 12:28
	Created By: Tech Banker Team
	*/

	if(!function_exists("plugin_load_textdomain_backup_bank"))
	{
		function plugin_load_textdomain_backup_bank()
		{
			if(function_exists("load_plugin_textdomain"))
			{
				load_plugin_textdomain(wp_backup_bank, false, BACKUP_BANK_PLUGIN_DIRNAME ."/languages");
			}
		}
	}

	/*
	Function Name: mailer_file_backup_bank
	Parameters: No
	Description: This function is used for include mailer class.
	Created On: 07-04-2016 03:51
	Created By: Tech Banker Team
	*/

	if(!function_exists("mailer_file_backup_bank"))
	{
		function mailer_file_backup_bank()
		{
			if(file_exists(BACKUP_BANK_DIR_PATH."lib/mailer.php"))
			{
				include_once BACKUP_BANK_DIR_PATH."lib/mailer.php";
			}
		}
	}

	/*
	Function Name: timezone_difference_backup_bank
	Parameters: Yes($timezone)
	Description: This function returns time difference.
	Created On: 16-04-2016 12:12
	Created By: Tech Banker Team
	*/

	if(!function_exists("timezone_difference_backup_bank"))
	{
		function timezone_difference_backup_bank($timezone)
		{
			switch($timezone)
			{
				case "Pacific/Midway":
					$diff = 11*60*60;
				break;

				case "Pacific/Samoa":
					$diff = 11*60*60;
				break;

				case "Pacific/Honolulu":
					$diff = 10*60*60;
				break;

				case "US/Alaska":
					$diff = 9*60*60;
				break;

				case "America/Los_Angeles":
					$diff = 8*60*60;
				break;

				case "America/Tijuana":
					$diff = 8*60*60;
				break;

				case "US/Arizona":
					$diff = 7*60*60;
				break;

				case "America/Chihuahua":
					$diff = 7*60*60;
				break;

				case "America/Mazatlan":
					$diff = 7*60*60;
				break;

				case "US/Mountain":
					$diff = 7*60*60;
				break;

				case "America/Managua":
					$diff = 6*60*60;
				break;

				case "US/Central":
					$diff = 6*60*60;
				break;

				case "America/Mexico_City":
					$diff = 6*60*60;
				break;

				case "America/Monterrey":
					$diff = 6*60*60;
				break;

				case "Canada/Saskatchewan":
					$diff = 6*60*60;
				break;

				case "America/Bogota":
					$diff = 5*60*60;
				break;

				case "US/Eastern":
					$diff = 5*60*60;
				break;

				case "US/East-Indiana":
					$diff = 5*60*60;
				break;

				case "America/Lima":
					$diff = 5*60*60;
				break;

				case "America/Bogota":
					$diff = 5*60*60;
				break;

				case "Canada/Atlantic":
					$diff = 4*60*60;
				break;

				case "America/Caracas":
					$diff = 4.5*60*60;
				break;

				case "America/La_Paz":
					$diff = 4*60*60;
				break;

				case "America/Santiago":
					$diff = 4*60*60;
				break;

				case "Canada/Newfoundland":
					$diff = 3.5*60*60;
				break;

				case "America/Sao_Paulo":
					$diff = 3*60*60;
				break;

				case "America/Argentina/Buenos_Aires":
					$diff = 3*60*60;
				break;

				case "America/Godthab":
					$diff = 3*60*60;
				break;

				case "America/Noronha":
					$diff = 2*60*60;
				break;

				case "Atlantic/Azores":
					$diff = 1*60*60;
				break;

				case "Atlantic/Cape_Verde":
					$diff = 1*60*60;
				break;

				case "Africa/Casablanca":
					$diff = -0*60*60;
				break;

				case "Europe/London":
					$diff = -0*60*60;
				break;

				case "Etc/Greenwich":
					$diff = -0*60*60;
				break;

				case "Europe/Lisbon":
					$diff = -0*60*60;
				break;

				case "Europe/London":
					$diff = -0*60*60;
				break;

				case "Africa/Monrovia":
					$diff = -0*60*60;
				break;

				case "UTC":
					$diff = -0*60*60;
				break;

				case "Europe/Amsterdam":
					$diff = -1*60*60;
				break;

				case "Europe/Belgrade":
					$diff = -1*60*60;
				break;

				case "Europe/Berlin":
					$diff = -1*60*60;
				break;

				case "Europe/Bratislava":
					$diff = -1*60*60;
				break;

				case "Europe/Brussels":
					$diff = -1*60*60;
				break;

				case "Europe/Budapest":
					$diff = -1*60*60;
				break;

				case "Europe/Copenhagen":
					$diff = -1*60*60;
				break;

				case "Europe/Ljubljana":
					$diff = -1*60*60;
				break;

				case "Europe/Madrid":
					$diff = -1*60*60;
				break;

				case "Europe/Paris":
					$diff = -1*60*60;
				break;

				case "Europe/Prague":
					$diff = -1*60*60;
				break;

				case "Europe/Rome":
					$diff = -1*60*60;
				break;

				case "Europe/Sarajevo":
					$diff = -1*60*60;
				break;

				case "Europe/Skopje":
					$diff = -1*60*60;
				break;

				case "Europe/Stockholm":
					$diff = -1*60*60;
				break;

				case "Europe/Vienna":
					$diff = -1*60*60;
				break;

				case "Europe/Warsaw":
					$diff = -1*60*60;
				break;

				case "Africa/Lagos":
					$diff = -1*60*60;
				break;

				case "Europe/Zagreb":
					$diff = -1*60*60;
				break;

				case "Europe/Athens":
					$diff = -2*60*60;
				break;

				case "Europe/Bucharest":
					$diff = -2*60*60;
				break;

				case "Africa/Cairo":
					$diff = -2*60*60;
				break;

				case "Africa/Harare":
					$diff = -2*60*60;
				break;

				case "Europe/Helsinki":
					$diff = -2*60*60;
				break;

				case "Europe/Istanbul":
					$diff = -2*60*60;
				break;

				case "Asia/Jerusalem":
					$diff = -2*60*60;
				break;

				case "Europe/Helsinki":
					$diff = -2*60*60;
				break;

				case "Africa/Johannesburg":
					$diff = -2*60*60;
				break;

				case "Europe/Riga":
					$diff = -2*60*60;
				break;

				case "Europe/Sofia":
					$diff = -2*60*60;
				break;

				case "Europe/Tallinn":
					$diff = -2*60*60;
				break;

				case "Europe/Vilnius":
					$diff = -2*60*60;
				break;

				case "Asia/Baghdad":
					$diff = -3*60*60;
				break;

				case "Asia/Kuwait":
					$diff = -3*60*60;
				break;

				case "Europe/Minsk":
					$diff = -3*60*60;
				break;

				case "Africa/Nairobi":
					$diff = -3*60*60;
				break;

				case "Asia/Riyadh":
					$diff = -3*60*60;
				break;

				case "Europe/Volgograd":
					$diff = -3*60*60;
				break;

				case "Asia/Tehran":
					$diff = -3*60*60;
				break;

				case "Asia/Muscat":
					$diff = -4*60*60;
				break;

				case "Asia/Baku":
					$diff = -4*60*60;
				break;

				case "Europe/Moscow":
					$diff = -4*60*60;
				break;

				case "Asia/Muscat":
					$diff = -4*60*60;
				break;

				case "Asia/Tbilisi":
					$diff = -4*60*60;
				break;

				case "Asia/Yerevan":
					$diff = -4*60*60;
				break;

				case "Asia/Kabul":
					$diff = -4.5*60*60;
				break;

				case "Asia/Karachi":
					$diff = -5*60*60;
				break;

				case "Asia/Tashkent":
					$diff = -5*60*60;
				break;

				case "Asia/Calcutta":
					$diff = -5.5*60*60;
				break;

				case "Asia/Kolkata":
					$diff = -5.5*60*60;
				break;

				case "Asia/Katmandu":
					$diff = -5.75*60*60;
				break;

				case "Asia/Almaty":
					$diff = -6*60*60;
				break;

				case "Asia/Dhaka":
					$diff = -6*60*60;
				break;

				case "Asia/Yekaterinburg":
					$diff = -6*60*60;
				break;

				case "Asia/Rangoon":
					$diff = -6.5*60*60;
				break;

				case "Asia/Bangkok":
					$diff = -7*60*60;
				break;

				case "Asia/Jakarta":
					$diff = -7*60*60;
				break;

				case "Asia/Novosibirsk":
					$diff = -7*60*60;
				break;

				case "Asia/Hong_Kong":
					$diff = -8*60*60;
				break;

				case "Asia/Chongqing":
					$diff = -8*60*60;
				break;

				case "Asia/Krasnoyarsk":
					$diff = -8*60*60;
				break;

				case "Asia/Kuala_Lumpur":
					$diff = -8*60*60;
				break;

				case "Australia/Perth":
					$diff = -8*60*60;
				break;

				case "Asia/Singapore":
					$diff = -8*60*60;
				break;

				case "Asia/Taipei":
					$diff = -8*60*60;
				break;

				case "Asia/Ulan_Bator":
					$diff = -8*60*60;
				break;

				case "Asia/Urumqi":
					$diff = -8*60*60;
				break;

				case "Asia/Irkutsk":
					$diff = -9*60*60;
				break;

				case "Asia/Tokyo":
					$diff = -9*60*60;
				break;

				case "Asia/Seoul":
					$diff = -9*60*60;
				break;

				case "Australia/Adelaide":
					$diff = -9.5*60*60;
				break;

				case "Australia/Darwin":
					$diff = -9.5*60*60;
				break;

				case "Australia/Brisbane":
					$diff = -10*60*60;
				break;

				case "Australia/Canberra":
					$diff = -10*60*60;
				break;

				case "Pacific/Guam":
					$diff = -10*60*60;
				break;

				case "Australia/Hobart":
					$diff = -10*60*60;
				break;

				case "Australia/Melbourne":
					$diff = -10*60*60;
				break;

				case "Pacific/Port_Moresby":
					$diff = -10*60*60;
				break;

				case "Australia/Sydney":
					$diff = -10*60*60;
				break;

				case "Asia/Yakutsk":
					$diff = -10*60*60;
				break;

				case "Asia/Vladivostok":
					$diff = -11*60*60;
				break;

				case "Pacific/Auckland":
					$diff = -12*60*60;
				break;

				case "Pacific/Fiji":
					$diff = -12*60*60;
				break;

				case "Pacific/Kwajalein":
					$diff = -12*60*60;
				break;

				case "Asia/Kamchatka":
					$diff = -12*60*60;
				break;

				case "Asia/Magadan":
					$diff = -12*60*60;
				break;

				case "Pacific/Marshall":
					$diff = -12*60*60;
				break;

				case "Asia/Caledonia":
					$diff = -12*60*60;
				break;

				case "Pacific/Wellington":
					$diff = -12*60*60;
				break;

				case "Pacific/Tongatapu":
					$diff = -13*60*60;
				break;

				default:
					$diff = 0;
			}

			return $diff;
		}
	}
	/*
	Function Name: scheduler_for_backup_bank
	Parameter: Yes($cron_name,$time_interval,$timestamp, $timezone)
	Description: This function is used for creating a scheduler for backup.
	Created On: 08-04-2016
	Created By: Tech Banker Team
	*/

	if(!function_exists("scheduler_for_backup_bank"))
	{
		function scheduler_for_backup_bank($cron_name,$time_interval,$timestamp, $timezone)
		{
			if (!wp_next_scheduled($cron_name))
			{
				$diff = timezone_difference_backup_bank($timezone);
				$time = $timestamp + $diff;
				wp_schedule_event($time,$time_interval,$cron_name);
			}
		}
	}

	if(function_exists("_get_cron_array"))
	{
		$scheduler_backup_bank = _get_cron_array();
		$current_scheduler_backup_bank = array();
		if(count($scheduler_backup_bank) > 0)
		{
			foreach($scheduler_backup_bank as $value => $key)
			{
				$arr_key = array_keys($key);
				foreach($arr_key as $value)
				{
					array_push($current_scheduler_backup_bank,$value);
				}
			}
		}

		if(isset($current_scheduler_backup_bank[0]))
		{
			if(!defined("scheduler_name")) define("scheduler_name",$current_scheduler_backup_bank[0]);

			if(strstr($current_scheduler_backup_bank[0],"backup_scheduler_"))
			{
				add_action($current_scheduler_backup_bank[0], "backup_schedule_backup_bank");
			}
		}
	}

	/*
	Function Name: backup_schedule_backup_bank
	Parameters: No
	Description: This function is used to including backup file on schedule run.
	Created On: 15-10-2015 12:05
	Created By: Tech Banker Team
	*/

	if(!function_exists("backup_schedule_backup_bank"))
	{
		function backup_schedule_backup_bank()
		{
			mailer_file_backup_bank();
			if(file_exists(BACKUP_BANK_DIR_PATH."lib/schedule-backup.php"))
			{
				global $wpdb;
				$nonce_schedule_backup = wp_create_nonce("nonce_schedule_backup");
				include_once BACKUP_BANK_DIR_PATH."lib/schedule-backup.php";
			}
		}
	}

	/*
	Function Name: cron_scheduler_for_intervals_backup_bank
	Parameters: Yes($schedules)
	Description: This function is used to cron scheduler for intervals.
	Created On: 15-10-2015 12:05
	Created By: Tech Banker Team
	*/

	if(!function_exists("cron_scheduler_for_intervals_backup_bank"))
	{
		function cron_scheduler_for_intervals_backup_bank($schedules)
		{
			$schedules["1Hour"] = array("interval" => 60*60, "display" => "Every 1 Hour");
			$schedules["2Hour"] = array("interval" => 60*60*2, "display" => "Every 2 Hours");
			$schedules["3Hour"] = array("interval" => 60*60*3, "display" => "Every 3 Hours");
			$schedules["4Hour"] = array("interval" => 60*60*4, "display" => "Every 4 Hours");
			$schedules["5Hour"] = array("interval" => 60*60*5, "display" => "Every 5 Hours");
			$schedules["6Hour"] = array("interval" => 60*60*6, "display" => "Every 6 Hours");
			$schedules["7Hour"] = array("interval" => 60*60*7, "display" => "Every 7 Hours");
			$schedules["8Hour"] = array("interval" => 60*60*8, "display" => "Every 8 Hours");
			$schedules["9Hour"] = array("interval" => 60*60*9, "display" => "Every 9 Hours");
			$schedules["10Hour"] = array("interval" => 60*60*10, "display" => "Every 10 Hours");
			$schedules["11Hour"] = array("interval" => 60*60*11, "display" => "Every 11 Hours");
			$schedules["12Hour"] = array("interval" => 60*60*12, "display" => "Every 12 Hours");
			$schedules["13Hour"] = array("interval" => 60*60*13, "display" => "Every 13 Hours");
			$schedules["14Hour"] = array("interval" => 60*60*14, "display" => "Every 14 Hours");
			$schedules["15Hour"] = array("interval" => 60*60*15, "display" => "Every 15 Hours");
			$schedules["16Hour"] = array("interval" => 60*60*16, "display" => "Every 16 Hours");
			$schedules["17Hour"] = array("interval" => 60*60*17, "display" => "Every 17 Hours");
			$schedules["18Hour"] = array("interval" => 60*60*18, "display" => "Every 18 Hours");
			$schedules["19Hour"] = array("interval" => 60*60*19, "display" => "Every 19 Hours");
			$schedules["20Hour"] = array("interval" => 60*60*20, "display" => "Every 20 Hours");
			$schedules["21Hour"] = array("interval" => 60*60*21, "display" => "Every 21 Hours");
			$schedules["22Hour"] = array("interval" => 60*60*22, "display" => "Every 22 Hours");
			$schedules["23Hour"] = array("interval" => 60*60*23, "display" => "Every 23 Hours");
			$schedules["Daily"] = array("interval" => 60*60*24 , "display" => "Daily");
			return $schedules;
		}
	}

	/*
	Function Name: unschedule_events_backup_bank
	Parameters: Yes($cron_name)
	Description: This function is used to unscheduling the events.
	Created On: 15-10-2015 12:11
	Created By: Tech Banker Team
	*/

	if(!function_exists("unschedule_events_backup_bank"))
	{
		function unschedule_events_backup_bank($cron_name)
		{
			if (wp_next_scheduled($cron_name))
			{
				$db_cron = wp_next_scheduled($cron_name);
				wp_unschedule_event($db_cron,$cron_name);
			}
		}
	}

	/*
	Function Name: admin_functions_for_backup_bank
	Parameters: No
	Description: This function is used for admin functions.
	Created On: 24-02-2016 11:57
	Created By: Tech Banker Team
	*/

	if(!function_exists("admin_functions_for_backup_bank"))
	{
		function admin_functions_for_backup_bank()
		{
			install_script_for_backup_bank();
			helper_file_for_backup_bank();
			backup_folders_for_backup_bank();
		}
	}

	/*
	Function Name: user_function_for_backup_bank
	Parameters: No
	Description: This function is used for user functions.
	Created On: 24-02-2016 12:01
	Created By: Tech Banker Team
	*/

	if(!function_exists("user_function_for_backup_bank"))
	{
		function user_function_for_backup_bank()
		{
			global $wpdb;
			$meta_values = $wpdb->get_var
			(
				$wpdb->prepare
				(
				"SELECT meta_value FROM ".backup_bank_meta().
				" WHERE meta_key = %s",
				"other_settings"
				)
			);
			$meta_data_array = array();
			$unserialize_data = unserialize($meta_values);
			if($unserialize_data["automatic_plugin_updates"] == "enable")
			{
				plugin_auto_update_backup_bank();
			}
			else
			{
				wp_clear_scheduled_hook("automatic_updates_backup_bank");
			}
			mailer_file_backup_bank();
		}
	}

	/*
	Function Name: maintenance_mode_backup_bank
	Parameters: No
	Description: This function is used to including backup file on maintenance mode.
	Created On: 24-02-2016 12:01
	Created By: Tech Banker Team
	*/

	if(!function_exists("maintenance_mode_backup_bank"))
	{
		function maintenance_mode_backup_bank()
		{
			global $wpdb;
			$enable_maintenance_mode = $wpdb->get_var
			(
				$wpdb->prepare
				(
					"SELECT meta_value FROM ".backup_bank_restore().
					" WHERE meta_key = %s",
					"maintenance_mode_settings"
				)
			);
			$enable_maintenance_mode_data = unserialize($enable_maintenance_mode);

			if($enable_maintenance_mode_data["restoring"] == "enable")
			{
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/maintenance.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/maintenance.php";
				}
			}
		}
	}

	/*
	Function Name: plugin_auto_update_backup_bank
	Parameters: No
	Description: This function is used to Update Plugin Edition.
	Created On: 17-05-2016 3:19
	Created By: Tech Banker Team
	*/

	if(!function_exists("plugin_auto_update_backup_bank"))
	{
		function plugin_auto_update_backup_bank()
		{
			if (!wp_next_scheduled("automatic_updates_backup_bank"))
			{
				wp_schedule_event(time(), "Daily", "automatic_updates_backup_bank");
			}
			add_action("automatic_updates_backup_bank", "backup_bank_plugin_autoUpdate");
		}
	}

	/*
	Function Name: backup_bank_plugin_autoUpdate
	Parameters: No
	Description: This function is used to Update Plugin Automatically.
	Created On: 17-05-2016 3:21
	Created By: Tech Banker Team
	*/

	if(!function_exists("backup_bank_plugin_autoUpdate"))
	{
		function backup_bank_plugin_autoUpdate()
		{
			try
			{
				require_once(ABSPATH . "wp-admin/includes/class-wp-upgrader.php");
				require_once(ABSPATH . "wp-admin/includes/misc.php");
				define("FS_METHOD", "direct");
				require_once(ABSPATH . "wp-includes/update.php");
				require_once(ABSPATH . "wp-admin/includes/file.php");
				wp_update_plugins();
				ob_start();
				$plugin_upgrader = new Plugin_Upgrader();
				$plugin_upgrader->upgrade("wp-backup-bank/wp-backup-bank.php");
				$output = @ob_get_contents();
				@ob_end_clean();
			}
			catch(Exception $e)
			{
			}
		}
	}

	/*
	Function Name: uninstall_script_for_backup_bank
	Parameters: No
	Description: This function is used to delete schedulers and options on Uninstall plugin.
	Created On: 24-02-2016 3:40
	Created By: Tech Banker Team
	*/

	if(!function_exists("uninstall_script_for_backup_bank"))
	{
		function uninstall_script_for_backup_bank()
		{
			global $wpdb;
			if(is_multisite())
			{
				$blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
				foreach($blog_ids as $blog_id)
				{
					switch_to_blog($blog_id);
					if(file_exists(BACKUP_BANK_DIR_PATH."lib/uninstall-script.php"))
					{
						include BACKUP_BANK_DIR_PATH."lib/uninstall-script.php";
					}
					restore_current_blog();
				}
			}
			else
			{
				if(file_exists(BACKUP_BANK_DIR_PATH."lib/uninstall-script.php"))
				{
					include BACKUP_BANK_DIR_PATH."lib/uninstall-script.php";
				}
			}
		}
	}

	/*
	Function Name: backup_data_backup_bank
	Parameters: No
	Description: This function is used to Running Backup.
	Created On: 06-05-2016 11:53
	Created By: Tech Banker Team
	*/

	/**
	* @author		 DavidAnderson <https://updraftplus.com>
	* @copyright 2011-16 David Anderson, 2010 Paul Kehrer
	* @license	 https://opensource.org/licenses/gpl-license
	* @link			 https://updraftplus.com
	* @since		 available since Release 2.1.0
	*/

	if(!function_exists("backup_data_backup_bank"))
	{
		function backup_data_backup_bank($backup_array)
		{
			global $wpdb;
			$obj_backup_data_backup_bank = new backup_data_backup_bank($backup_array);

			if($backup_array["backup_type"] == "only_database")
			{
				$backup_status = $obj_backup_data_backup_bank->database_backup_bank();
			}
			else
			{
				$backup_status = $obj_backup_data_backup_bank->get_directories_backup_bank();
			}

			$backup_bank_data = $backup_array;
			$file_name = untrailingslashit($backup_bank_data["folder_location"])."/".implode("",unserialize($backup_bank_data["archive_name"])).".json";
			$backup_size = $obj_backup_data_backup_bank->kbsize;
			$backup_data_time = $obj_backup_data_backup_bank->timetaken;
			$backup_log_timetaken = $obj_backup_data_backup_bank->log_timetaken;

			$backup_bank_data["execution_time"] = serialize(array(time() - $backup_bank_data["timezone_difference"]));
			$backup_bank_data["executed_in"] = $backup_data_time;
			$backup_bank_data["total_size"] = $backup_size."Mb";
			$backup_bank_data["executed_time"] = time() - $backup_bank_data["timezone_difference"];

			switch($backup_bank_data["backup_type"])
			{
				case "only_themes":
					$backup_filename = "Themes";
				break;

				case "only_plugins":
					$backup_filename = "Plugins";
				break;

				case "only_wp_content_folder":
					$backup_filename = "Contents";
				break;

				case "complete_backup":
					$backup_filename = "Complete";
				break;

				case "only_filesystem":
					$backup_filename = "Filesystem";
				break;

				case "only_plugins_and_themes":
					$backup_filename = "Plugins_Themes";
				break;

				case "only_database":
					$backup_filename = "Database";
				break;
			}


			$dbMailer_backup_bank_obj = new dbMailer_backup_bank();

			if($backup_status != "terminated" && $backup_status != "file_exists")
			{
				if($backup_bank_data["backup_destination"] != "local_folder")
				{
					$upload_time_start = microtime(true);
					switch($backup_bank_data["backup_destination"])
					{
						case "ftp":
							$upload_status = "uploading_to_ftp";
						break;

						case "email":
							$upload_status = "uploading_to_email";
						break;

					}

					$backup_bank_data["status"] = $upload_status;

					$backup_bank_update_data = array();
					$where = array();
					$where["meta_key"] = "manual_backup_meta";
					$backup_bank_update_data["meta_value"] = serialize($backup_bank_data);
					$where["meta_id"] = $backup_bank_data["meta_id"];
					$obj_dbHelper_backup_bank = new dbHelper_backup_bank();
					$obj_dbHelper_backup_bank->updateCommand(backup_bank_meta(),$backup_bank_update_data,$where);
					switch($backup_bank_data["backup_destination"])
					{
						case "ftp":
							$ftp_settings_data = $wpdb->get_var
							(
								$wpdb->prepare
								(
									"SELECT meta_value FROM ".backup_bank_meta().
									" WHERE meta_key=%s",
									"ftp_settings"
								)
							);
							$ftp_settings_data_array = unserialize($ftp_settings_data);
							$obj_ftp_connect = new ftp_connection_backup_bank();
							$ftp_connection = $obj_ftp_connect->ftp_connect($ftp_settings_data_array["host"],$ftp_settings_data_array["protocol"],$ftp_settings_data_array["port"]);

							if($ftp_connection != false)
							{
								$ftp_login = $obj_ftp_connect->login_ftp($ftp_connection,$ftp_settings_data_array["login_type"],$ftp_settings_data_array["ftp_username"],$ftp_settings_data_array["ftp_password"]);
								$ftp_result = $obj_ftp_connect->ftp_mkdir_recusive($ftp_connection,trailingslashit($ftp_settings_data_array["remote_path"]).BACKUP_BANK_FOLDER_DROPBOX.basename($backup_bank_data["folder_location"]));

								if($ftp_result !== false)
								{
									$backup_array = array(untrailingslashit($backup_bank_data["folder_location"])."/".implode("",unserialize($backup_bank_data["archive"])),untrailingslashit($backup_bank_data["folder_location"])."/".implode("",unserialize($backup_bank_data["archive_name"])).".txt");
									if(isset($backup_array) && count($backup_array) > 0)
									{
										foreach($backup_array as $backup_file)
										{
											$backup_name = basename($backup_file);
											@$obj_ftp_connect->custom_ftp_put($ftp_connection,$backup_file,$backup_name,$file_name,$backup_bank_data);
										}
									}
									$cloud = 2;
									$log = "<b>$backup_filename Backup</b> has been Uploaded to <b>FTP</b> Successfully.";
									$backup_status = "completed_successfully";
								}
							}
						break;

						case "email":
							$email_setting_data = $wpdb->get_var
							(
								$wpdb->prepare
								(
									"SELECT meta_value FROM ".backup_bank_meta().
									" WHERE meta_key=%s",
									"email_settings"
								)
							);
							$email_settings_array = unserialize($email_setting_data);
							$file_size = filesize(untrailingslashit($backup_bank_data["folder_location"])."/".implode("",unserialize($backup_bank_data["archive"])));

							if($file_size <= 20971520)
							{
								$dbMailer_backup_bank_obj->sending_backup_to_email($email_settings_array,$backup_bank_data);
								$log = "<b>$backup_filename Backup</b> has been sent to <b>Email</b> Successfully.";
								$backup_status = "completed_successfully";
							}
							else
							{
								$log = "<b>$backup_filename Backup</b> could not be Sent to <b>Email</b> as Backup Size is more than <b>20MB</b>.";
								$backup_status = "email_not_sent";
							}
							$cloud = 1;
						break;

					}

					$uploaded_microtime = microtime(true) - $upload_time_start;
					$uploaded_time = max(microtime(true) - $upload_time_start,0.000001);
					$logfile_path = untrailingslashit($backup_bank_data["folder_location"])."/".implode("",unserialize($backup_bank_data["archive_name"])).".txt";
					$result = 100;
					$rtime = $backup_log_timetaken + $uploaded_microtime;
					$message = "{"."\r\n";
					$message .= '"log": "'.$log.'"'.','."\r\n";
					$message .= '"perc": '.$result.','."\r\n";
					$message .= '"status": "'.$backup_status.'"'.','."\r\n";
					$message .= '"cloud": '.$cloud."\r\n";
					$message .= "}";
					file_put_contents($file_name, $message);
					file_put_contents($logfile_path,sprintf("%08.03f", round($rtime, 3))." ".strip_tags($log),FILE_APPEND);
				}
			}

			$backup_bank_data["status"] = $backup_status;
			if($backup_bank_data["backup_destination"] != "local_folder" && $backup_status != "terminated" && $backup_status != "file_exists")
			{
				$backup_bank_data["executed_in"] = $backup_data_time + $uploaded_time;
			}
			$backup_bank_update_data = array();
			$where = array();
			$where["meta_key"] = "manual_backup_meta";
			$backup_bank_update_data["meta_value"] = serialize($backup_bank_data);
			$where["meta_id"] = $backup_bank_data["meta_id"];
			$obj_dbHelper_backup_bank = new dbHelper_backup_bank();
			$obj_dbHelper_backup_bank->updateCommand(backup_bank_meta(),$backup_bank_update_data,$where);

			$alert_setup_data = $wpdb->get_var
			(
				$wpdb->prepare
				(
					"SELECT meta_value FROM ".backup_bank_meta().
					" WHERE meta_key = %s",
					"alert_setup"
				)
			);

			$alert_setup_data_array = unserialize($alert_setup_data);

			if($backup_status != "terminated" && $backup_status != "file_exists")
			{
				if($alert_setup_data_array["email_when_backup_generated_successfully"] == "enable")
				{
					$backup_generated_data = $wpdb->get_var
					(
						$wpdb->prepare
						(
							"SELECT meta_value FROM ".backup_bank_meta().
							" WHERE meta_key = %s",
							"template_for_backup_successful_generated"
						)
					);
					$backup_generated_data_array = unserialize($backup_generated_data);
					$dbMailer_backup_bank_obj->email_when_backup_generated_successfully($backup_generated_data_array,$backup_bank_data);
				}
			}
			else
			{
				if($alert_setup_data_array["email_when_backup_failed"] == "enable")
				{
					$email_backup_data = $wpdb->get_var
					(
						$wpdb->prepare
						(
							"SELECT meta_value FROM ".backup_bank_meta().
							" WHERE meta_key =%s",
							"template_for_backup_failure"
						)
					);
					$email_backup_data_array = unserialize($email_backup_data);
					$dbMailer_backup_bank_obj->email_when_backup_failed($email_backup_data_array,$backup_bank_data);
				}
			}
		}
	}
	/*
	Function Name: backup_bank_action_links
	Parameters: Yes
	Description: This function is used to create link for Pro Editions.
	Created On: 13-09-2016 11:37
	Created By: Tech Banker Team
	*/

	if(!function_exists("backup_bank_action_links"))
	{
		function backup_bank_action_links($plugin_link)
		{
			$plugin_link[] = "<a href=\"http://beta.tech-banker.com/products/wp-backup-bank/\" style=\"color: red; font-weight: bold;\" target=\"_blank\">Go Pro!</a>";
			return $plugin_link;
		}
	}

	/*hooks */

	/*
	add_action for admin_functions_for_backup_bank
	Description: This hook is used for calling the function of admin functions.
	Created On: 05-02-2016 11:38
	Created By: Tech Banker Team
	*/

	add_action("admin_init","admin_functions_for_backup_bank");

	/*
	add_action for ajax_register_for_backup_bank
	Description: This hook is used for calling the function of register ajax.
	Created On: 09-08-2016 09:17
	Created By: Tech Banker Team
	*/

	add_action("wp_ajax_backup_bank_action", "ajax_register_for_backup_bank");

	/*
	add_action for user_function_for_backup_bank
	Description: This hook is used for calling the function of user function.
	Created On: 05-02-2016 11:38
	Created By: Tech Banker Team
	*/

	add_action("init","user_function_for_backup_bank");

	/*
	add_action for sidebar_menu_for_backup_bank
	Description: This hook is used for calling the function of sidebar menu.
	Created On: 05-02-2016 11:46
	Created By: Tech Banker Team
	*/

	add_action("admin_menu","sidebar_menu_for_backup_bank");

	/*
	add_action for sidebar_menu_for_backup_bank
	Description: This hook is used for calling the function of sidebar menuin multisite case.
	Created On: 05-02-2016 11:46
	Created By: Tech Banker Team
	*/
	add_action("network_admin_menu","sidebar_menu_for_backup_bank");

	/*
	add_action for topbar_menu_for_backup_bank
	Description: This hook is used for calling the function of topbar menu.
	Created On: 05-02-2016 11:52
	Created By: Tech Banker Team
	*/

	add_action("admin_bar_menu","topbar_menu_for_backup_bank",100);

	/*
	Add Filter for cron schedules
	Description: This hook is used for calling the function of cron schedulers jobs for wordpress data and database.
	Created On Date: 13-10-2015 12:45
	Created By: Tech Banker Team
	*/

	add_filter("cron_schedules", "cron_scheduler_for_intervals_backup_bank");

	/*
	add_action for plugin_load_textdomain_backup_bank
	Description: This hook is used for calling the function of languages.
	Created On: 05-02-2016 12:28
	Created By: Tech Banker Team
	*/

	add_action("plugins_loaded", "plugin_load_textdomain_backup_bank");

	/*
	add_action for maintenance_mode_backup_bank
	Description: This hook is used for maintenance_mode_backup_bank.
	Created On: 20-04-2016 5:53
	Created By: Tech Banker Team
	*/

	add_action("template_redirect", "maintenance_mode_backup_bank");

	/*
	add_action for start_backup
	Description: This hook is used for start_backup.
	Created On: 06-05-2016 11:53
	Created By: Tech Banker Team
	*/

	add_action("start_backup", "backup_data_backup_bank");

	/* register_uninstall_hook
	Description: This hook is used for calling the function of uninstall script.
	Created On:  17-05-2016 3:20
	Created By: Tech Banker Team
	*/

	register_uninstall_hook( __FILE__, "uninstall_script_for_backup_bank");

	/* add_filter create Go Pro link for Backup Bank
	Description: This hook is used for create link for premiium Editions.
	Created On: 13-09-2016 11:34
	Created by: Tech Banker Team
	*/

	add_filter("plugin_action_links_" . plugin_basename(__FILE__), "backup_bank_action_links");
}

/*
register_activation_hook for install_script_for_backup_bank
Description: This hook is used for calling the function of install script.
Created On: 05-02-2016 11:34
Created By: Tech Banker Team
*/

register_activation_hook(__FILE__,"install_script_for_backup_bank");

/*
add_action for install_script_for_backup_bank
Description: This hook is used for calling the function of install script.
Created On: 05-02-2016 11:38
Created By: Tech Banker Team
*/

add_action("admin_init","install_script_for_backup_bank");

?>
