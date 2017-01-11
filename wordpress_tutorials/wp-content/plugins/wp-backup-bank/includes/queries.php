<?php
/**
* This file is used for fetching data from database.
*
* @author  Tech Banker
* @package wp-backup-bank/includes
* @version 3.0.1
*/
if(!defined("ABSPATH")) exit; //exit if accessed directly
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
		if(!function_exists("get_backup_bank_unserialize_data"))
		{
			function get_backup_bank_unserialize_data($manage_data)
			{
				$unserialize_complete_data = array();
				if(count($manage_data) > 0)
				{
					foreach($manage_data as $value)
					{
						$unserialize_data = unserialize($value -> meta_value);

						$unserialize_data["meta_id"] = $value -> meta_id;
						array_push($unserialize_complete_data,$unserialize_data);
					}
				}
				return $unserialize_complete_data;
			}
		}

		if(!function_exists("get_backup_bank_destinations_unserialize_data"))
		{
			function get_backup_bank_destinations_unserialize_data($manage_data)
			{
				$unserialize_complete_data = array();
				if(count($manage_data) > 0)
				{
					foreach($manage_data as $value)
					{
						$unserialize_destination_data = unserialize($value -> meta_value);
						foreach($unserialize_destination_data as $key=>$data)
						{
							$unserialize_complete_data[$key] = $data;
						}
					}
				}
				return $unserialize_complete_data;
			}
		}

		// function to get all loaded extensions of php

		if(function_exists("get_loaded_extensions"))
		{
			$all_extensions = get_loaded_extensions();
			$required_extensions = array("openssl","curl","zlib","bz2","fileinfo","zip");
			$extension_not_found = array();
			foreach($required_extensions as $extension)
			{
				if(!in_array($extension,$all_extensions))
				{
				 array_push($extension_not_found,$extension);
				}
			}
		}


		if(!function_exists("get_backup_bank_tables"))
		{
			function get_backup_bank_tables($result)
			{
				$tables = array();
				for($flag = 0; $flag < count($result); $flag++)
				{
					if($result[$flag]->Name != backup_bank_restore())
					{
						array_push($tables,$result[$flag]->Name);
					}
				}
				return $tables;
			}
		}

		if(!function_exists("get_backup_bank_schedule_time"))
		{
			function get_backup_bank_schedule_time($schedule_name)
			{
				$execution_time = "";
				$scheduler_backup_bank = _get_cron_array();
				if(count($scheduler_backup_bank) > 0)
				{
					foreach($scheduler_backup_bank as $value => $key)
					{
						$arr_key = array_keys($key);
						foreach($arr_key as $row)
						{
							if(strstr($row,$schedule_name))
							{
								$execution_time = $value;
							}
						}
					}
				}
				return $execution_time;
			}
		}

		if(isset($_GET["page"]))
		{
			switch(esc_attr($_GET["page"]))
			{
				case "bb_roles_and_capabilities" :
					$roles_data = $wpdb->get_var
					(
						$wpdb->prepare
						(
							"SELECT meta_value FROM ".backup_bank_meta().
							" WHERE meta_key=%s",
							"roles_and_capabilities"
						)
					);
					$details_roles_capabilities = unserialize($roles_data);
					$other_roles_array = $details_roles_capabilities["capabilities"];
				break;

				case "bb_start_backup":

				$total_backups = $wpdb->get_var
				(
					$wpdb->prepare
					(
						"SELECT count(type) FROM ".backup_bank().
						" WHERE type = %s",
						"manual_backup"
					)
				);

				$bb_backups_id = $wpdb->get_var
				(
					$wpdb->prepare
					(
						"SELECT id FROM ".backup_bank().
						" WHERE type = %s",
						"backups"
					)
				);

				$bb_backups_data = $wpdb->get_results
				(
					$wpdb->prepare
					(
						"SELECT * FROM ".backup_bank_meta()." INNER JOIN ".backup_bank().
						" ON ".backup_bank().".id=".backup_bank_meta().
						".meta_id WHERE parent_id = %d ORDER BY ".backup_bank().".id desc",
						$bb_backups_id
					)
				);

				if(is_multisite())
				{
					$name = "";
					$blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
					if(isset($blog_ids) && count($blog_ids) > 0)
					{
						foreach($blog_ids as $blog_id)
						{
							$name.= " AND Name NOT LIKE '". $wpdb->prefix . $blog_id ."%'";
						}
					}
					$backup_tables = "SHOW TABLE STATUS FROM `".DB_NAME."` WHERE Name LIKE '".$wpdb->prefix."%'" . $name;
				}
				else
				{
					$backup_tables = "SHOW TABLE STATUS FROM `".DB_NAME."`";
				}
				$result = $wpdb->get_results($backup_tables);
				$result = get_backup_bank_tables($result);

				$settings_data = $wpdb->get_results
				(
					$wpdb->prepare
					(
						"SELECT * FROM ".backup_bank_meta().
						" WHERE meta_key in (%s,%s,%s,%s,%s,%s,%s,%s)",
						"dropbox_settings",
						"email_settings",
						"ftp_settings",
						"amazons3_settings",
						"onedrive_settings",
						"rackspace_settings",
						"azure_settings",
						"google_drive"
					)
				);
				$settings_data_array = get_backup_bank_destinations_unserialize_data($settings_data);
				break;

				case "bb_schedule_backup":
					if(is_multisite())
					{
						$name = "";
						$blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
						if(isset($blog_ids) && count($blog_ids) > 0)
						{
							foreach($blog_ids as $blog_id)
							{
								$name.= " AND Name NOT LIKE '". $wpdb->prefix . $blog_id ."%'";
							}
						}
						$backup_tables = "SHOW TABLE STATUS FROM `".DB_NAME."` WHERE Name LIKE '".$wpdb->prefix."%'" . $name;
					}
					else
					{
						$backup_tables = "SHOW TABLE STATUS FROM `".DB_NAME."`";
					}
					$result = $wpdb->get_results($backup_tables);
					$result = get_backup_bank_tables($result);

					$settings_data = $wpdb->get_results
					(
						$wpdb->prepare
						(
							"SELECT * FROM ".backup_bank_meta().
							" WHERE meta_key in (%s,%s,%s,%s,%s,%s,%s,%s)",
							"dropbox_settings",
							"email_settings",
							"ftp_settings",
							"amazons3_settings",
							"onedrive_settings",
							"rackspace_settings",
							"azure_settings",
							"google_drive"
						)
					);
					$settings_data_array = get_backup_bank_destinations_unserialize_data($settings_data);
				break;

				case "bb_alert_setup":
					$bb_alert_setup_updated_data = $wpdb->get_var
					(
						$wpdb->prepare
						(
							"SELECT meta_value FROM ".backup_bank_meta().
							" WHERE meta_key=%s",
							"alert_setup"
						)
					);
					$bb_alert_setup_array = unserialize($bb_alert_setup_updated_data);
				break;

				case "bb_other_settings":
					$bb_other_settings_updated_data = $wpdb->get_var
					(
						$wpdb->prepare
						(
							"SELECT meta_value FROM ".backup_bank_meta().
							" WHERE meta_key=%s",
							"other_settings"
						)
					);
					$bb_other_settings_array = unserialize($bb_other_settings_updated_data);

					$bb_other_settings_maintenance_mode = $wpdb->get_var
					(
						$wpdb->prepare
						(
							"SELECT meta_value FROM ".backup_bank_restore().
							" WHERE meta_key = %s",
							"maintenance_mode_settings"
						)
					);
					$bb_other_settings_maintenance_mode_data = unserialize($bb_other_settings_maintenance_mode);
				break;

				case "bb_email_settings":
					$email_setting_data = $wpdb->get_var
					(
						$wpdb->prepare
						(
							"SELECT meta_value FROM ".backup_bank_meta().
							" WHERE meta_key=%s",
							"email_settings"
						)
					);
					$email_setting_data_array = unserialize($email_setting_data);
				break;

				case "bb_manage_backups":
					$total_backups = $wpdb->get_var
					(
						$wpdb->prepare
						(
							"SELECT count(type) FROM ".backup_bank().
							" WHERE type = %s",
							"manual_backup"
						)
					);
					$bb_backups_id = $wpdb->get_var
					(
						$wpdb->prepare
						(
							"SELECT id FROM ".backup_bank().
							" WHERE type = %s",
							"backups"
						)
					);

					$bb_backups_data = $wpdb->get_results
					(
						$wpdb->prepare
						(
							"SELECT * FROM ".backup_bank_meta()." INNER JOIN ".backup_bank().
							" ON ".backup_bank().".id=".backup_bank_meta().
							".meta_id WHERE parent_id = %d ORDER BY ".backup_bank().".id desc",
							$bb_backups_id
						)
					);
					$bb_backups_unserialized_data = get_backup_bank_unserialize_data($bb_backups_data);
				break;

				case "bb_ftp_settings":
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
				break;
			}
		}
	}
}
?>
