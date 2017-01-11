<?php
/**
* This file is used for creating sidebar menu.
*
* @author  Tech Banker
* @package wp-backup-bank/lib
* @version 3.0.1
*/
if(!defined("ABSPATH")) exit; // Exit if accessed directly
if(!is_user_logged_in())
{
	return;
}
else
{
	$access_granted = false;
	if(isset($user_role_permission) && count($user_role_permission) > 0)
	{
		foreach($user_role_permission as $permission)
		{
			if(current_user_can($permission))
			{
				$access_granted = true;
				break;
			}
		}
	}
	if(!$access_granted)
	{
		return;
	}
	else
	{
		$flag = 0;

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
		$capabilities = explode(",",$roles_and_capabilities_unserialized_data["roles_and_capabilities"]);

		if(is_super_admin())
		{
			$bb_role = "administrator";
		}
		else
		{
			$bb_role = check_user_roles_backup_bank();
		}
		switch($bb_role)
		{
			case "administrator":
				$privileges = "administrator_privileges";
				$flag = $capabilities[0];
			break;

			case "author":
				$privileges = "author_privileges";
				$flag = $capabilities[1];
			break;

			case "editor":
				$privileges = "editor_privileges";
				$flag = $capabilities[2];
			break;

			case "contributor":
				$privileges = "contributor_privileges";
				$flag = $capabilities[3];
			break;

			case "subscriber":
				$privileges = "subscriber_privileges";
				$flag = $capabilities[4];
			break;

			default:
				$privileges = "other_privileges";
				$flag = $capabilities[5];
			break;
		}

		if(isset($roles_and_capabilities_unserialized_data) && count($roles_and_capabilities_unserialized_data) > 0)
		{
			foreach($roles_and_capabilities_unserialized_data as $key=>$value)
			{
				if($privileges == $key)
				{
					$privileges_value = $value;
					break;
				}
			}
		}

		$full_control = explode(",",$privileges_value);
		if(!defined("full_control")) define("full_control","$full_control[0]");
		if(!defined("manage_backups_backup_bank")) define("manage_backups_backup_bank","$full_control[1]");
		if(!defined("manual_backup_bank")) define("manual_backup_bank","$full_control[2]");
		if(!defined("schedule_backup_bank")) define("schedule_backup_bank","$full_control[3]");
		if(!defined("general_settings_backup_bank")) define("general_settings_backup_bank","$full_control[4]");
		if(!defined("email_templates_backup_bank")) define("email_templates_backup_bank","$full_control[5]");
		if(!defined("roles_and_capabilities_backup_bank")) define("roles_and_capabilities_backup_bank","$full_control[6]");
		if(!defined("system_information_backup_bank")) define("system_information_backup_bank","$full_control[7]");

		if($flag == "1")
		{
			global $wp_version;

			$icon = $wp_version < "3.8" ? plugins_url("assets/global/img/icons.png",dirname(__FILE__)) : "dashicons-backup";

			add_menu_page($wp_backup_bank,$wp_backup_bank,"read","bb_manage_backups","",$icon);

			add_submenu_page("bb_manage_backups",$bb_manage_backups,$bb_backups,"read","bb_manage_backups","bb_manage_backups");
			add_submenu_page($bb_start_backup,$bb_start_backup,"","read","bb_start_backup","bb_start_backup");
			add_submenu_page($bb_schedule_backup,$bb_schedule_backup,"","read","bb_schedule_backup","bb_schedule_backup");

			add_submenu_page("bb_manage_backups",$bb_alert_setup,$bb_general_settings,"read","bb_alert_setup","bb_alert_setup");
			add_submenu_page($bb_other_settings,$bb_other_settings,"","read","bb_other_settings","bb_other_settings");
			add_submenu_page($bb_dropbox_settings,$bb_dropbox_settings,"","read","bb_dropbox_settings","bb_dropbox_settings");
			add_submenu_page($bb_email_settings,$bb_email_settings,"","read","bb_email_settings","bb_email_settings");
			add_submenu_page($bb_ftp_settings,$bb_ftp_settings,"","read","bb_ftp_settings","bb_ftp_settings");
			add_submenu_page($bb_amazons3_settings,$bb_amazons3_settings,"","read","bb_amazons3_settings","bb_amazons3_settings");
			add_submenu_page($bb_onedrive_settings,$bb_onedrive_settings,"","read","bb_onedrive_settings","bb_onedrive_settings");
			add_submenu_page($bb_rackspace_settings,$bb_rackspace_settings,"","read","bb_rackspace_settings","bb_rackspace_settings");
      add_submenu_page($bb_ms_azure_settings,$bb_ms_azure_settings,"","read","bb_ms_azure_settings","bb_ms_azure_settings");

			add_submenu_page($bb_google_drive,$bb_google_drive,"","read","bb_google_drive","bb_google_drive");

			add_submenu_page("bb_manage_backups",$bb_email_templates,$bb_email_templates,"read","bb_email_templates","bb_email_templates");

			add_submenu_page("bb_manage_backups",$bb_roles_and_capabilities,$bb_roles_and_capabilities,"read","bb_roles_and_capabilities","bb_roles_and_capabilities");

			add_submenu_page("bb_manage_backups",$bb_feature_requests,$bb_feature_requests,"read","bb_feature_requests","bb_feature_requests");

			add_submenu_page("bb_manage_backups",$bb_system_information,$bb_system_information,"read","bb_system_information","bb_system_information");
			add_submenu_page("bb_manage_backups",$bb_premium_editions,$bb_premium_editions,"read","bb_premium_editions","bb_premium_editions");
		}

		/*
		Function Name: bb_start_backup
		Parameters: No
		Description: This function is used to create bb_start_backup menu.
		Created On: 05-02-2016 12:18
		Created By: Tech Banker Team
		*/

		if(!function_exists("bb_start_backup"))
		{
			function bb_start_backup()
			{
				global $wpdb;
				$user_role_permission = get_users_capabilities_backup_bank();
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/translations.php"))
				{
					include BACKUP_BANK_DIR_PATH."includes/translations.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/header.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/header.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/sidebar.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/sidebar.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/queries.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/queries.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."views/backups/start-backup.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."views/backups/start-backup.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/footer.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/footer.php";
				}
			}
		}

		/*
		Function Name: bb_schedule_backup
		Parameters: No
		Description: This function is used to create bb_schedule_backup menu.
		Created On: 18-02-2016 11:41
		Created By: Tech Banker Team
		*/

		if(!function_exists("bb_schedule_backup"))
		{
			function bb_schedule_backup()
			{
				global $wpdb;
				$user_role_permission = get_users_capabilities_backup_bank();
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/translations.php"))
				{
					include BACKUP_BANK_DIR_PATH."includes/translations.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/header.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/header.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/sidebar.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/sidebar.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/queries.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/queries.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."views/backups/schedule-backup.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."views/backups/schedule-backup.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/footer.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/footer.php";
				}
			}
		}

		/*
		Function Name: bb_manage_backups
		Parameters: No
		Description: This function is used to create bb_manage_backups menu.
		Created On: 05-02-2016 12:18
		Created By: Tech Banker Team
		*/

		if(!function_exists("bb_manage_backups"))
		{
			function bb_manage_backups()
			{
				global $wpdb;
				$user_role_permission = get_users_capabilities_backup_bank();
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/translations.php"))
				{
					include BACKUP_BANK_DIR_PATH."includes/translations.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/header.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/header.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/sidebar.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/sidebar.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/queries.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/queries.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."views/backups/manage-backups.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."views/backups/manage-backups.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/footer.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/footer.php";
				}
			}
		}

		/*
		Function Name: bb_alert_setup
		Parameters: No
		Description: This function is used to create bb_alert_setup menu.
		Created On: 05-02-2016 12:18
		Created By: Tech Banker Team
		*/

		if(!function_exists("bb_alert_setup"))
		{
			function bb_alert_setup()
			{
				global $wpdb;
				$user_role_permission = get_users_capabilities_backup_bank();
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/translations.php"))
				{
					include BACKUP_BANK_DIR_PATH."includes/translations.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/header.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/header.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/sidebar.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/sidebar.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/queries.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/queries.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."views/general-settings/alert-setup.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."views/general-settings/alert-setup.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/footer.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/footer.php";
				}
			}
		}

		/*
		Function Name: bb_other_settings
		Parameters: No
		Description: This function is used to create bb_other_settings menu.
		Created On: 19-02-2016 10:19
		Created By: Tech Banker Team
		*/

		if(!function_exists("bb_other_settings"))
		{
			function bb_other_settings()
			{
				global $wpdb;
				$user_role_permission = get_users_capabilities_backup_bank();
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/translations.php"))
				{
					include BACKUP_BANK_DIR_PATH."includes/translations.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/header.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/header.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/sidebar.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/sidebar.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/queries.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/queries.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."views/general-settings/other-settings.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."views/general-settings/other-settings.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/footer.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/footer.php";
				}
			}
		}

		/*
		Function Name: bb_dropbox_settings
		Parameters: No
		Description: This function is used to create bb_dropbox_settings menu.
		Created On: 24-02-2016 10:19
		Created By: Tech Banker Team
		*/

		if(!function_exists("bb_dropbox_settings"))
		{
			function bb_dropbox_settings()
			{
				global $wpdb;
				$user_role_permission = get_users_capabilities_backup_bank();
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/translations.php"))
				{
					include BACKUP_BANK_DIR_PATH."includes/translations.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/header.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/header.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/sidebar.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/sidebar.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/queries.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/queries.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."views/general-settings/dropbox-settings.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."views/general-settings/dropbox-settings.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/footer.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/footer.php";
				}
			}
		}


		/*
		Function Name: bb_email_settings
		Parameters: No
		Description: This function is used to create bb_email_settings menu.
		Created On: 24-02-2016 10:39
		Created By: Tech Banker Team
		*/

		if(!function_exists("bb_email_settings"))
		{
			function bb_email_settings()
			{
				global $wpdb;
				$user_role_permission = get_users_capabilities_backup_bank();
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/translations.php"))
				{
					include BACKUP_BANK_DIR_PATH."includes/translations.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/header.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/header.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/sidebar.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/sidebar.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/queries.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/queries.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."views/general-settings/email-settings.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."views/general-settings/email-settings.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/footer.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/footer.php";
				}
			}
		}

		/*
		Function Name: bb_ftp_settings
		Parameters: No
		Description: This function is used to create bb_ftp_settings menu.
		Created On: 24-02-2016 10:48
		Created By: Tech Banker Team
		*/

		if(!function_exists("bb_ftp_settings"))
		{
			function bb_ftp_settings()
			{
				global $wpdb;
				$user_role_permission = get_users_capabilities_backup_bank();
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/translations.php"))
				{
					include BACKUP_BANK_DIR_PATH."includes/translations.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/header.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/header.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/sidebar.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/sidebar.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/queries.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/queries.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."views/general-settings/ftp-settings.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."views/general-settings/ftp-settings.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/footer.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/footer.php";
				}
			}
		}

		/*
		Function Name: bb_amazons3_settings
		Parameters: No
		Description: This function is used to create bb_amazons3_settings menu.
		Created On: 04-06-2016 10:03
		Created By: Tech Banker Team
		*/

		if(!function_exists("bb_amazons3_settings"))
		{
			function bb_amazons3_settings()
			{
				global $wpdb;
				$user_role_permission = get_users_capabilities_backup_bank();
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/translations.php"))
				{
					include BACKUP_BANK_DIR_PATH."includes/translations.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/header.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/header.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/sidebar.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/sidebar.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/queries.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/queries.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."views/general-settings/amazons3-settings.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."views/general-settings/amazons3-settings.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/footer.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/footer.php";
				}
			}
		}

		/*
		Function Name: bb_rackspace_settings
		Parameters: No
		Description: This function is used to create bb_rackspace_settings menu.
		Created On: 22-08-2016 11:25
		Created By: Tech Banker Team
		*/

		if(!function_exists("bb_rackspace_settings"))
		{
			function bb_rackspace_settings()
			{
				global $wpdb;
				$user_role_permission = get_users_capabilities_backup_bank();
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/translations.php"))
				{
					include BACKUP_BANK_DIR_PATH."includes/translations.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/header.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/header.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/sidebar.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/sidebar.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/queries.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/queries.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."views/general-settings/rackspace-settings.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."views/general-settings/rackspace-settings.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/footer.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/footer.php";
				}
			}
		}
    /*
  Function Name: bb_ms_azure_settings
  Parameters: No
  Description: This function is used to create bb_ms_azure_settings menu.
  Created On: 22-08-2016 04:50
  Created By: Tech Banker Team
  */

  if(!function_exists("bb_ms_azure_settings"))
  {
    function bb_ms_azure_settings()
    {
      global $wpdb;
      $user_role_permission = get_users_capabilities_backup_bank();
      if(file_exists(BACKUP_BANK_DIR_PATH."includes/translations.php"))
      {
        include BACKUP_BANK_DIR_PATH."includes/translations.php";
      }
      if(file_exists(BACKUP_BANK_DIR_PATH."includes/header.php"))
      {
        include_once BACKUP_BANK_DIR_PATH."includes/header.php";
      }
      if(file_exists(BACKUP_BANK_DIR_PATH."includes/sidebar.php"))
      {
        include_once BACKUP_BANK_DIR_PATH."includes/sidebar.php";
      }
      if(file_exists(BACKUP_BANK_DIR_PATH."includes/queries.php"))
      {
        include_once BACKUP_BANK_DIR_PATH."includes/queries.php";
      }
      if(file_exists(BACKUP_BANK_DIR_PATH."views/general-settings/ms-azure-settings.php"))
      {
        include_once BACKUP_BANK_DIR_PATH."views/general-settings/ms-azure-settings.php";
      }
      if(file_exists(BACKUP_BANK_DIR_PATH."includes/footer.php"))
      {
        include_once BACKUP_BANK_DIR_PATH."includes/footer.php";
      }
    }
  }

		/*
		Function Name: bb_email_templates
		Parameters: No
		Description: This function is used to create bb_email_templates menu.
		Created On: 05-02-2016 12:18
		Created By: Tech Banker Team
		*/

		if(!function_exists("bb_email_templates"))
		{
			function bb_email_templates()
			{
				global $wpdb;
				$user_role_permission = get_users_capabilities_backup_bank();
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/translations.php"))
				{
					include BACKUP_BANK_DIR_PATH."includes/translations.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/header.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/header.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/sidebar.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/sidebar.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/queries.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/queries.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."views/email-templates/email-templates.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."views/email-templates/email-templates.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/footer.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/footer.php";
				}
			}
		}

		/*
		Function Name: bb_roles_and_capabilities
		Parameters: No
		Description: This function is used to create bb_roles_and_capabilities menu.
		Created On: 05-02-2016 2:14
		Created By: Tech Banker Team
		*/

		if(!function_exists("bb_roles_and_capabilities"))
		{
			function bb_roles_and_capabilities()
			{
				global $wpdb;
				$user_role_permission = get_users_capabilities_backup_bank();
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/translations.php"))
				{
					include BACKUP_BANK_DIR_PATH."includes/translations.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/header.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/header.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/sidebar.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/sidebar.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/queries.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/queries.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."views/roles-and-capabilities/roles-and-capabilities.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."views/roles-and-capabilities/roles-and-capabilities.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/footer.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/footer.php";
				}
			}
		}

		/*
		Function Name: bb_feature_requests
		Parameters: No
		Description: This function is used to create bb_feature_requests menu.
		Created On: 05-02-2016 2:14
		Created By: Tech Banker Team
		*/

		if(!function_exists("bb_feature_requests"))
		{
			function bb_feature_requests()
			{
				global $wpdb;
				$user_role_permission = get_users_capabilities_backup_bank();
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/translations.php"))
				{
					include BACKUP_BANK_DIR_PATH."includes/translations.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/header.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/header.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/sidebar.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/sidebar.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/queries.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/queries.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."views/feature-requests/feature-requests.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."views/feature-requests/feature-requests.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/footer.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/footer.php";
				}
			}
		}

		/*
		Function Name: bb_system_information
		Parameters: No
		Description: This function is used to create bb_system_information menu.
		Created On: 05-02-2016 2:14
		Created By: Tech Banker Team
		*/

		if(!function_exists("bb_system_information"))
		{
			function bb_system_information()
			{
				global $wpdb;
				$user_role_permission = get_users_capabilities_backup_bank();
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/translations.php"))
				{
					include BACKUP_BANK_DIR_PATH."includes/translations.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/header.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/header.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/sidebar.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/sidebar.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/queries.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/queries.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."views/system-information/system-information.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."views/system-information/system-information.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/footer.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/footer.php";
				}
			}
		}

		/*
		Function Name: bb_premium_editions
		Parameters: No
		Description: Description: This function is used to create bb_premium_editions menu.
		Created On: 29-06-2016 12:14
		Created By: Tech Banker Team
		*/

		if(!function_exists("bb_premium_editions"))
		{
			function bb_premium_editions()
			{
				global $wpdb;
				$user_role_permission = get_users_capabilities_backup_bank();
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/translations.php"))
				{
					include BACKUP_BANK_DIR_PATH."includes/translations.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/header.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/header.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/sidebar.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/sidebar.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/queries.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/queries.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."views/premium-editions/premium-editions.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."views/premium-editions/premium-editions.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/footer.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/footer.php";
				}
			}
		}

		/*
		Function Name: bb_onedrive_settings
		Parameters: No
		Description: This function is used to create bb_onedrive_settings menu.
		Created On: 24-05-2016 03:06
		Created By: Tech Banker Team
		*/

		if(!function_exists("bb_onedrive_settings"))
		{
			function bb_onedrive_settings()
			{
				global $wpdb;
				$user_role_permission = get_users_capabilities_backup_bank();
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/translations.php"))
				{
					include BACKUP_BANK_DIR_PATH."includes/translations.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/header.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/header.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/sidebar.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/sidebar.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/queries.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/queries.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."views/general-settings/onedrive-settings.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."views/general-settings/onedrive-settings.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/footer.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/footer.php";
				}
			}
		}

		/*
		Function Name: bb_google_drive
		Parameters: No
		Description: This function is used to create bb_google_drive menu.
		Created On: 24-05-2016 03:32
		Created By: Tech Banker Team
		*/

		if(!function_exists("bb_google_drive"))
		{
			function bb_google_drive()
			{
				global $wpdb;
				$user_role_permission = get_users_capabilities_backup_bank();
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/translations.php"))
				{
					include BACKUP_BANK_DIR_PATH."includes/translations.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/header.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/header.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/sidebar.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/sidebar.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/queries.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/queries.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."views/general-settings/google-drive.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."views/general-settings/google-drive.php";
				}
				if(file_exists(BACKUP_BANK_DIR_PATH."includes/footer.php"))
				{
					include_once BACKUP_BANK_DIR_PATH."includes/footer.php";
				}
			}
		}
	}
}
?>
