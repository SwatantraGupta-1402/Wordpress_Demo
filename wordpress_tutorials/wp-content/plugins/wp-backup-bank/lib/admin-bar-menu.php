<?php
/**
* This file is used for creating admin bar menu.
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
	if(isset($user_role_permission) &&  count($user_role_permission) > 0)
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
				$flag = $capabilities[0];
			break;

			case "author":
				$flag = $capabilities[1];
			break;

			case "editor":
				$flag = $capabilities[2];
			break;

			case "contributor":
				$flag = $capabilities[3];
			break;

			case "subscriber":
				$flag = $capabilities[4];
			break;

			default:
				$flag = $capabilities[5];
			break;
		}

		if($flag == "1")
		{
			global $wp_version;
			$icon = $wp_version < "3.8" ? "<img style=\"width:16px; height:16px; vertical-align:middle; margin-right:3px;\" src=".plugins_url("assets/global/img/icons.png",dirname(__FILE__))."> " : "<span style=\"font-family: dashicons;\" class=\"dashicons-backup ab-icon\" ></span> ";
			$wp_admin_bar->add_menu(array
			(
				"id"=>"wp_backup_bank",
				"title"=>$icon."<span class=\"ab-label\">".$wp_backup_bank."</span>",
				"href"=>admin_url("admin.php?page=bb_manage_backups")
			));
			$wp_admin_bar->add_menu(array
			(
				"parent"=>"wp_backup_bank",
				"id"=>"backups_bank",
				"title"=>$bb_backups,
				"href"=>admin_url("admin.php?page=bb_manage_backups")
			));
			$wp_admin_bar->add_menu(array
			(
				"parent"=>"wp_backup_bank",
				"id"=>"general_settings_backup_bank",
				"title"=>$bb_general_settings,
				"href"=>admin_url("admin.php?page=bb_alert_setup")
			));
			$wp_admin_bar->add_menu(array
			(
				"parent"=>"wp_backup_bank",
				"id"=>"email_templates_backup_bank",
				"title"=>$bb_email_templates,
				"href"=>admin_url("admin.php?page=bb_email_templates")
			));
			$wp_admin_bar->add_menu(array
			(
				"parent"=>"wp_backup_bank",
				"id"=>"roles_and_capabilities_backup_bank",
				"title"=>$bb_roles_and_capabilities,
				"href"=>admin_url("admin.php?page=bb_roles_and_capabilities")
			));
			$wp_admin_bar->add_menu(array
			(
				"parent"=>"wp_backup_bank",
				"id"=>"feature_requests_backup_bank",
				"title"=>$bb_feature_requests,
				"href"=>admin_url("admin.php?page=bb_feature_requests")
			));
			$wp_admin_bar->add_menu(array
			(
				"parent"=>"wp_backup_bank",
				"id"=>"system_information_backup_bank",
				"title"=>$bb_system_information,
				"href"=>admin_url("admin.php?page=bb_system_information")
			));
			$wp_admin_bar->add_menu(array
			(
				"parent"=>"wp_backup_bank",
				"id"=>"premium_editions_backup_bank",
				"title"=>$bb_premium_editions,
				"href"=>admin_url("admin.php?page=bb_premium_editions")
			));
		}
	}
}
?>
