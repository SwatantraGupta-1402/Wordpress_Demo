<?php
/**
* This file is used for creating tables in database on the activation hook.
*
* @author  Tech Banker
* @package wp-backup-bank/lib
* @version 3.1
*/
if(!defined("ABSPATH")) exit; // Exit if accessed directly
if(!is_user_logged_in())
{
	return;
}
else
{
	if(!current_user_can("manage_options"))
	{
		return;
	}
	else
	{
		/*
		Class Name: dbHelper_install_script_backup_bank
		Parameters: No
		Description: This Class is used for Insert Update operations.
		Created On: 05-02-2016 11:40
		Created By: Tech Banker Team
		*/

		if(!class_exists("dbHelper_install_script_backup_bank"))
		{
			class dbHelper_install_script_backup_bank
			{
				/*
				Function Name: insertCommand
				Parameters: Yes($table_name,$data)
				Description: This Function is used for Insert data in database.
				Created On: 05-02-2016 11:40
				Created By: Tech Banker Team
				*/

				function insertCommand($table_name,$data)
				{
					global $wpdb;
					$wpdb->insert($table_name,$data);
					return $wpdb->insert_id;
				}

				/*
				Function Name: updateCommand
				Parameters: Yes($table_name,$data,$where)
				Description: This function is used for Update data.
				Created On: 05-02-2016 11:40
				Created By: Tech Banker Team
				*/

				function updateCommand($table_name,$data,$where)
				{
					global $wpdb;
					$wpdb->update($table_name,$data,$where);
				}
			}
		}

		if(!function_exists("backup_bank_table"))
		{
			function backup_bank_table()
			{
				global $wpdb;
				$obj_dbHelper_backup_bank_parent = new dbHelper_install_script_backup_bank();
				$sql = "CREATE TABLE IF NOT EXISTS ".backup_bank()."
				(
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`type` varchar(100) NOT NULL,
					`parent_id` int(11) NOT NULL,
					PRIMARY KEY (`id`)
				)
				ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
				dbDelta($sql);

				$data = "INSERT INTO ". backup_bank() ." (`type`, `parent_id`) VALUES
				('backups', 0),
				('general_settings', 0),
				('email_templates', 0),
				('roles_and_capabilities', 0)";
				dbDelta($data);

				$backup_bank_parent_table = $wpdb->get_results
				(
					"SELECT * FROM " .backup_bank()
				);
				if(isset($backup_bank_parent_table) && count($backup_bank_parent_table) > 0)
				{
					foreach($backup_bank_parent_table as $row)
					{
						switch($row->type)
						{
							case "general_settings":
								$general_settings = array();
								$general_settings["alert_setup"] = $row->id;
								$general_settings["other_settings"] = $row->id;
								$general_settings["dropbox_settings"] = $row->id;
								$general_settings["email_settings"] = $row->id;
								$general_settings["ftp_settings"] = $row->id;
								$general_settings["amazons3_settings"] = $row->id;
								$general_settings["onedrive_settings"] = $row->id;
								$general_settings["rackspace_settings"] = $row->id;
								$general_settings["azure_settings"] = $row->id;
								$general_settings["google_drive"] = $row->id;

								foreach($general_settings as $key => $value)
								{
									$general_settings_data = array();
									$general_settings_data["type"] = $key;
									$general_settings_data["parent_id"] = $value;
									$obj_dbHelper_backup_bank_parent->insertCommand(backup_bank(),$general_settings_data);
								}
							break;
						}
					}
				}
			}
		}

		if(!function_exists("backup_bank_meta_table"))
		{
			function backup_bank_meta_table()
			{
				$obj_dbHelper_install_script_backup_bank = new dbHelper_install_script_backup_bank();
				global $wpdb;
				$sql = "CREATE TABLE IF NOT EXISTS ".backup_bank_meta()."
				(
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`meta_id` int(11) NOT NULL,
					`meta_key` varchar(255) NOT NULL,
					`meta_value` longtext NOT NULL,
					PRIMARY KEY (`id`)
				)
				ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
				dbDelta($sql);

				$admin_email = get_option("admin_email");
				$admin_name = get_option("blogname");

				$backup_bank_table_data = $wpdb->get_results
				(
					"SELECT * FROM " .backup_bank()
				);
				if(isset($backup_bank_table_data) && count($backup_bank_table_data) > 0)
				{
					foreach($backup_bank_table_data as $row)
					{
						switch($row->type)
						{
							case "alert_setup":
								$alert_setup_data_array = array();
								$alert_setup_data_array["email_when_backup_scheduled_successfully"] = "enable";
								$alert_setup_data_array["email_when_backup_generated_successfully"] = "enable";
								$alert_setup_data_array["email_when_backup_failed"] = "enable";
								$alert_setup_data_array["email_when_restore_completed_successfully"] = "enable";
								$alert_setup_data_array["email_when_restore_failed"] = "enable";

								$alert_setup_data = array();
								$alert_setup_data["meta_id"] = $row->id;
								$alert_setup_data["meta_key"] = "alert_setup";
								$alert_setup_data["meta_value"] = serialize($alert_setup_data_array);
								$obj_dbHelper_install_script_backup_bank->insertCommand(backup_bank_meta(),$alert_setup_data);
							break;

							case "other_settings":
								$other_settings_data_array = array();
								$other_settings_data_array["automatic_plugin_updates"] = "disable";
								$other_settings_data_array["remove_tables_at_uninstall"] = "enable";

								$other_settings_data = array();
								$other_settings_data["meta_id"] = $row->id;
								$other_settings_data["meta_key"] = "other_settings";
								$other_settings_data["meta_value"] = serialize($other_settings_data_array);
								$obj_dbHelper_install_script_backup_bank->insertCommand(backup_bank_meta(),$other_settings_data);
							break;

							case "amazons3_settings":
								$amazons3_settings_data_array = array();
								$amazons3_settings_data_array["backup_to_amazons3"] = "disable";
								$amazons3_settings_data_array["access_key_id"] = "";
								$amazons3_settings_data_array["secret_key"] = "";
								$amazons3_settings_data_array["bucket_name"] = "";

								$amazons3_settings_data = array();
								$amazons3_settings_data["meta_id"] = $row->id;
								$amazons3_settings_data["meta_key"] = "amazons3_settings";
								$amazons3_settings_data["meta_value"] = serialize($amazons3_settings_data_array);
								$obj_dbHelper_install_script_backup_bank->insertCommand(backup_bank_meta(),$amazons3_settings_data);
							break;

							case "dropbox_settings":
								$dropbox_settings_data_array = array();
								$dropbox_settings_data_array["backup_to_dropbox"] = "disable";
								$dropbox_settings_data_array["api_key"] = "";
								$dropbox_settings_data_array["secret_key"] = "";

								$dropbox_settings_data = array();
								$dropbox_settings_data["meta_id"] = $row->id;
								$dropbox_settings_data["meta_key"] = "dropbox_settings";
								$dropbox_settings_data["meta_value"] = serialize($dropbox_settings_data_array);
								$obj_dbHelper_install_script_backup_bank->insertCommand(backup_bank_meta(),$dropbox_settings_data);
							break;

							case "email_settings":
								$email_settings_data_array = array();
								$email_settings_data_array["backup_to_email"] = "disable";
								$email_settings_data_array["email_address"] = $admin_email;
								$email_settings_data_array["cc_email"] = "";
								$email_settings_data_array["bcc_email"] = "";
								$email_settings_data_array["email_subject"] = "[backup_type] Successfully Generated - Backup Bank";
								$email_settings_data_array["email_message"] = "<p>Hi Admin,</p><p>Kindly find attached Compressed Backup for <strong>[backup_type]</strong> in <strong>[compression_type]</strong> Format with Detailed Log executed by <strong>[username]</strong> on <strong>[start_time]</strong> for your website <strong>[site_url]</strong>.</p><p><u>Here are the details for the Backup :-</u></p><p><strong>Archive Name: </strong>[archive_name]</p><p><strong>Backup Name</strong>: [backup_name]</p><p><strong>Backup Type:</strong> [backup_type]</p><p><strong>Exclude List</strong>: [exclude_list]</p><p><strong>Compression Type</strong>: [compression_type]</p><p><strong>Backup Tables</strong>: [backup_tables]</p><p>Thank you for using <strong>Backup Bank</strong> Plugin.</p><p><strong>Thanks & Regards</strong></p><p><strong>Support Team</strong><br /> <strong>Tech Banker<br /> </strong></p>";

								$email_settings_data = array();
								$email_settings_data["meta_id"] = $row->id;
								$email_settings_data["meta_key"] = "email_settings";
								$email_settings_data["meta_value"] = serialize($email_settings_data_array);
								$obj_dbHelper_install_script_backup_bank->insertCommand(backup_bank_meta(),$email_settings_data);
							break;

							case "ftp_settings":
								$ftp_settings_data_array = array();
								$ftp_settings_data_array["backup_to_ftp"] = "disable";
								$ftp_settings_data_array["protocol"] = "ftp";
								$ftp_settings_data_array["host"] = "";
								$ftp_settings_data_array["login_type"] = "username_password";
								$ftp_settings_data_array["ftp_username"] = "";
								$ftp_settings_data_array["ftp_password"] = "";
								$ftp_settings_data_array["port"] = "";
								$ftp_settings_data_array["remote_path"] = "";
								$ftp_settings_data_array["ftp_mode"] = "false";

								$ftp_settings_data = array();
								$ftp_settings_data["meta_id"] = $row->id;
								$ftp_settings_data["meta_key"] = "ftp_settings";
								$ftp_settings_data["meta_value"] = serialize($ftp_settings_data_array);
								$obj_dbHelper_install_script_backup_bank->insertCommand(backup_bank_meta(),$ftp_settings_data);
							break;

							case "onedrive_settings":
								$onedrive_settings_data_array = array();
								$onedrive_settings_data_array["backup_to_onedrive"] = "disable";
								$onedrive_settings_data_array["client_id"] = "";
								$onedrive_settings_data_array["client_secret"] = "";

								$onedrive_settings_data = array();
								$onedrive_settings_data["meta_id"] = $row->id;
								$onedrive_settings_data["meta_key"] = "onedrive_settings";
								$onedrive_settings_data["meta_value"] = serialize($onedrive_settings_data_array);
								$obj_dbHelper_install_script_backup_bank->insertCommand(backup_bank_meta(),$onedrive_settings_data);
							break;

							case "rackspace_settings":
								$rackspace_settings_data_array = array();
								$rackspace_settings_data_array["backup_to_rackspace"] = "disable";
								$rackspace_settings_data_array["username"] = "";
								$rackspace_settings_data_array["api_key"] = "";
								$rackspace_settings_data_array["container"] = "";
								$rackspace_settings_data_array["region"] = "DFW";

								$rackspace_settings_data = array();
								$rackspace_settings_data["meta_id"] = $row->id;
								$rackspace_settings_data["meta_key"] = "rackspace_settings";
								$rackspace_settings_data["meta_value"] = serialize($rackspace_settings_data_array);
								$obj_dbHelper_install_script_backup_bank->insertCommand(backup_bank_meta(),$rackspace_settings_data);
							break;
							case "azure_settings":
								$ms_azure_data_array = array();
								$ms_azure_data_array["backup_to_ms_azure"] = "disable";
								$ms_azure_data_array["account_name"] = "";
								$ms_azure_data_array["access_key"] = "";
								$ms_azure_data_array["container"] = "";

								$ms_azure_data = array();
								$ms_azure_data["meta_id"] = $row->id;
								$ms_azure_data["meta_key"] = "azure_settings";
								$ms_azure_data["meta_value"] = serialize($ms_azure_data_array);
								$obj_dbHelper_install_script_backup_bank->insertCommand(backup_bank_meta(),$ms_azure_data);
							break;

							case "google_drive":
								$google_drive_data_array = array();
								$google_drive_data_array["backup_to_google_drive"] = "disable";
								$google_drive_data_array["client_id"] = "";
								$google_drive_data_array["secret_key"] = "";
								$google_drive_data_array["redirect_uri"] = admin_url()."admin.php?page=bb_google_drive";

								$google_drive_data = array();
								$google_drive_data["meta_id"] = $row->id;
								$google_drive_data["meta_key"] = "google_drive";
								$google_drive_data["meta_value"] = serialize($google_drive_data_array);
								$obj_dbHelper_install_script_backup_bank->insertCommand(backup_bank_meta(),$google_drive_data);
							break;

							case "email_templates":
								$email_templates = array();
								$email_templates["template_for_backup_successful_generated"] = "<p>Hi,</p><p>A Backup has been Successfully Generated for your website <strong>[site_url]</strong> by <strong>[username] </strong>at <strong>[start_time]</strong>.</p><p><u>Here are the Details for the Backup :-</u></p><p><strong>Backup Name: </strong>[backup_name]</p><p><strong>Backup Type:</strong> [backup_type]</p><p><strong>Exclude List:</strong> [exclude_list]</p><p><strong>File Compression Type:</strong> [file_compression_type]</p><p><strong>DB Compression Type:</strong> [db_compression_type]</p><p><strong>Backup Tables:</strong> [backup_tables]</p><p><strong>Archive Name:</strong> [archive_name]</p><p><strong>Backup Destination:</strong> [backup_destination]</p><p><strong>Folder Location:</strong> [folder_location]</p><p><strong>Thanks & Regards</strong></p><p><strong>Support Team</strong><br /> <strong>Tech Banker<br /> </strong></p>";
								$email_templates["template_for_scheduled_backup"] = "<p>Hi,</p><p>A Backup has been Successfully Scheduled to run on your website <strong>[site_url]</strong> starting <strong>[start_date]</strong> at <strong>[start_time]</strong> ending <strong>[end_on]</strong> according to Time Zone <strong>[time_zone]</strong>.</p><p><u>Here is the Detailed footprint at the Request :-</u></p><p><strong>Start On:</strong> [start_date]/[start_time]</p><p><strong>Duration: </strong>[duration]</p><p><strong>End On:</strong> [end_on]</p><p><strong>Repeat Every:</strong> [repeat_every]</p><p><strong>Time Zone:</strong> [time_zone]</p><p><u>Here are the Details for the Backup :-</u></p><p><strong>Backup Name: </strong>[backup_name]</p><p><strong>Backup Type:</strong> [backup_type]</p><p><strong>Exclude List:</strong> [exclude_list]</p><p><strong>File Compression Type:</strong> [file_compression_type]</p><p><strong>DB Compression Type:</strong> [db_compression_type]</p><p><strong>Backup Tables:</strong> [backup_tables]</p><p><strong>Archive Name:</strong> [archive_name]</p><p><strong>Backup Destination:</strong> [backup_destination]</p><p><strong>Folder Location:</strong> [folder_location]</p><p><strong>Thanks & Regards</strong></p><p><strong>Support Team</strong><br /> <strong>Tech Banker<br /> </strong></p>";
								$email_templates["template_for_restore_successfully"] = "<p>Hi,</p><p>A Backup has been Successfully Restored to your website <strong>[site_url]</strong> by <strong>[username]</strong> at <strong>[start_time]</strong>.</p><p><u>Here are the Details for the Backup :-</u></p><p><strong>Backup Name:</strong> [backup_name]</p><p><strong>Backup Type:</strong> [backup_type]</p><p><strong>Backup Source:</strong> [backup_source]</p><p><strong>Time Taken:</strong> [time_taken]</p><p><strong>Status:</strong> [status]</p><p><strong>Thanks & Regards</strong></p><p><strong>Support Team</strong><br /> <strong>Tech Banker<br /> </strong></p>";
								$email_templates["template_for_backup_failure"] = "<p>Hi,</p><p>A Backup has been Failed to Generate to your website <strong>[site_url]</strong> by <strong>[username]</strong> at <strong>[start_time]</strong>.</p><p><u>Here are the Details for the Backup :-</u></p><p><strong>Backup Name: </strong>[backup_name]</p><p><strong>Backup Type:</strong> [backup_type]</p><p><strong>Exclude List:</strong> [exclude_list]</p><p><strong>File Compression Type:</strong> [file_compression_type]</p><p><strong>DB Compression Type:</strong> [db_compression_type]</p><p><strong>Backup Tables:</strong> [backup_tables]</p><p><strong>Archive Name:</strong> [archive_name]</p><p><strong>Backup Destination:</strong> [backup_destination]</p><p><strong>Folder Location:</strong> [folder_location]</p><p><strong>Thanks & Regards</strong></p><p><strong>Support Team</strong><br /> <strong>Tech Banker<br /> </strong></p>";
								$email_templates["template_for_restore_failure"] = "<p>Hi,</p><p>A Backup has been Failed to Restore to your website <strong>[site_url]</strong> by <strong>[username]</strong> at <strong>[start_time]</strong>.</p><p><u>Here are the Details for the Backup :-</u></p><p><strong>Backup Name: </strong>[backup_name]</p><p><strong>Backup Type:</strong> [backup_type]</p><p><strong>Backup Source:</strong> [backup_source]</p><p><strong>Time Taken:</strong> [time_taken]</p><p><p><strong>Status:</strong> [status]</p><p><strong>Thanks & Regards</strong></p><p><strong>Support Team</strong><br /> <strong>Tech Banker<br /> </strong></p>";

								$email_templates_message = array("Backup Successfully Generated Notification - Backup Bank","Backup Successfully Scheduled Notification - Backup Bank","Backup Restore Success Notification - Backup Bank","Backup Failure Notification - Backup Bank","Backup Restore Failure Notification - Backup Bank");
								$count = 0;
								foreach($email_templates as $key => $value)
								{
									$email_templates_scheduled_backup_array = array();
									$email_templates_scheduled_backup_array["email_send_to"] = $admin_email;
									$email_templates_scheduled_backup_array["email_cc"] = "";
									$email_templates_scheduled_backup_array["email_bcc"] = "";
									$email_templates_scheduled_backup_array["email_subject"] = $email_templates_message[$count];
									$email_templates_scheduled_backup_array["email_message"] = $value;
									$count++;

									$email_templates_for_scheduled_backup = array();
									$email_templates_for_scheduled_backup["meta_id"] = $row->id;
									$email_templates_for_scheduled_backup["meta_key"] = $key;
									$email_templates_for_scheduled_backup["meta_value"] = serialize($email_templates_scheduled_backup_array);
									$obj_dbHelper_install_script_backup_bank->insertCommand(backup_bank_meta(),$email_templates_for_scheduled_backup);
								}
							break;

							case "roles_and_capabilities":
								$roles_capabilities_data_array = array();
								$roles_capabilities_data_array["roles_and_capabilities"] = "1,1,1,0,0,0";
								$roles_capabilities_data_array["show_backup_bank_top_bar_menu"] = "enable";
								$roles_capabilities_data_array["administrator_privileges"] = "1,1,1,1,1,1,1,1,1";
								$roles_capabilities_data_array["author_privileges"] = "0,1,0,0,0,1,0,0,0";
								$roles_capabilities_data_array["editor_privileges"] = "0,1,1,0,0,1,0,1,0";
								$roles_capabilities_data_array["contributor_privileges"] = "0,0,0,0,0,1,0,0,0";
								$roles_capabilities_data_array["subscriber_privileges"] = "0,0,0,0,0,0,0,0,0";
								$roles_capabilities_data_array["others_full_control_capability"] = "0";
								$roles_capabilities_data_array["other_privileges"] = "0,0,0,0,0,0,0,0,0";

								$user_capabilities = get_others_capabilities_backup_bank();
								$other_roles_array = array();
								$other_roles_access_array = array(
									"manage_options",
									"edit_plugins",
									"edit_posts",
									"publish_posts",
									"publish_pages",
									"edit_pages",
									"read"
								);
								foreach($other_roles_access_array as $role)
								{
									if(in_array($role,$user_capabilities))
									{
										array_push($other_roles_array,$role);
									}
								}
								$roles_capabilities_data_array["capabilities"] = $other_roles_array;

								$roles_data_array = array();
								$roles_data_array["meta_id"] = $row->id;
								$roles_data_array["meta_key"] = "roles_and_capabilities";
								$roles_data_array["meta_value"] = serialize($roles_capabilities_data_array);
								$obj_dbHelper_install_script_backup_bank->insertCommand(backup_bank_meta(),$roles_data_array);
							break;
						}
					}
				}
			}
		}

		if(!function_exists("get_backup_details"))
		{
			function get_backup_details($manage_data)
			{
				$backup_id = array();
				$backup_details = array();
				if(count($manage_data) > 0)
				{
					foreach($manage_data as $row)
					{
						array_push($backup_id,$row->backup_id);
					}
					$backup_id = array_unique($backup_id,SORT_REGULAR);
					foreach($backup_id as $id)
					{
						$backup = get_backup_data($id,$manage_data);
						array_push($backup_details,$backup);
					}
				}
				return array_unique($backup_details,SORT_REGULAR);
			}
		}

		if(!function_exists("get_backup_data"))
		{
			function get_backup_data($id,$backup_details)
			{
				$get_single_detail = array();
				if(count($backup_details) > 0)
				{
					foreach($backup_details as $row)
					{
						if($row->backup_id == $id)
						{
							$get_single_detail["$row->meta_key"] = $row->meta_value;
							$get_single_detail["backup_id"] = $row->backup_id;
						}
					}
				}
				return $get_single_detail;
			}
		}

		if(!function_exists("backup_bank_table_restore"))
		{
			function backup_bank_table_restore()
			{
				global $wpdb;
				$obj_dbHelper_install_script_backup_bank = new dbHelper_install_script_backup_bank();
				$sql = "CREATE TABLE IF NOT EXISTS ".backup_bank_restore()."
				(
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`meta_key` varchar(100) NOT NULL,
					`meta_value` longtext,
					PRIMARY KEY (`id`)
				)
				ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
				dbDelta($sql);

				$maintenance_mode_settings = array();
				$maintenance_mode_settings["message_when_restore"] = "Site in Maintenance Mode";
				$maintenance_mode_settings["restoring"] = "disable";

				$maintenance_mode_settings_data = array();
				$maintenance_mode_settings_data["meta_key"] = "maintenance_mode_settings";
				$maintenance_mode_settings_data["meta_value"] = serialize($maintenance_mode_settings);
				$obj_dbHelper_install_script_backup_bank->insertCommand(backup_bank_restore(),$maintenance_mode_settings_data);
			}
		}

		$obj_dbHelper_install_script_backup_bank = new dbHelper_install_script_backup_bank();
		require_once ABSPATH ."wp-admin/includes/upgrade.php";
		$backup_bank_version_number = get_option("backup-bank-version-number");

		switch($backup_bank_version_number)
		{
			case "":

				backup_bank_table();
				backup_bank_meta_table();
				backup_bank_table_restore();

			break;

			case "1.0":

				if (count($wpdb->get_var("SHOW TABLES LIKE '" . $wpdb->prefix."backup_details" . "'")) != 0 && count($wpdb->get_var("SHOW TABLES LIKE '" . $wpdb->prefix."backup_meta" . "'")) != 0)
				{
					$backup_bank_data = $wpdb->get_results
					(
						$wpdb->prepare
						(
							"SELECT * FROM " .$wpdb->prefix."backup_meta". "
							 INNER JOIN ".$wpdb->prefix."backup_details". " ON "
							.$wpdb->prefix."backup_meta".".backup_id = " .$wpdb->prefix."backup_details".".id
							 WHERE ".$wpdb->prefix."backup_details".".type = %s",
							"backup"
						)
					);

					$backup_data = get_backup_details($backup_bank_data);

					$wpdb->query("DROP TABLE ".$wpdb->prefix."backup_details");
					$wpdb->query("DROP TABLE ".$wpdb->prefix."backup_meta");

					backup_bank_table();
					backup_bank_meta_table();
					backup_bank_table_restore();

					$backups_id = $wpdb->get_var
					(
						$wpdb->prepare
						(
							"SELECT id FROM ".backup_bank()." WHERE type=%s",
							"backups"
						)
					);

					if(count($backup_data) > 0)
					{
						foreach ($backup_data as $array)
						{
							if($array["backup_status"] == "Success")
							{
								switch ($array["backup_option"])
								{
									case "1":
										$backup_type = "only_database";
									break;

									case "2":
										$backup_type = "only_filesystem";
									break;
								}

								if(isset($array["db_compression"]))
								{
									$db_compression = ".sql";
								}
								else
								{
									$db_compression = "";
								}

								if(isset($array["file_compression"]))
								{
									$file_compression = ".zip";
								}
								else
								{
									$file_compression = "";
								}

								$old_backup_bank_data = array();
								$old_backup_bank_data["type"] = "manual_backup";
								$old_backup_bank_data["parent_id"] = $backups_id;
								$last_id = $obj_dbHelper_install_script_backup_bank->insertCommand(backup_bank(),$old_backup_bank_data);

								$backup_bank_data = array();
								$backup_bank_data["timezone_difference"] = -19800;
								$backup_bank_data["backup_name"] =  $array["backup_title"];
								$backup_bank_data["backup_type"] = $backup_type;
								$backup_bank_data["exclude_list"] =  isset($array["exclude_file_ext"]) ? $array["exclude_file_ext"] : "";
								$backup_bank_data["file_compression_type"] = $file_compression;
								$backup_bank_data["db_compression_type"] = $db_compression;
								$backup_bank_data["backup_tables"] = isset($array["backup_tables"]) ? str_replace(";", ",",$array["backup_tables"]) : "";
								$backup_bank_data["archive"] = serialize(array($array["archive_name"]));
								$backup_bank_data["archive_name"] = serialize(array(pathinfo($array["log_file"], PATHINFO_FILENAME)));
								$backup_bank_data["backup_destination"] = "local_folder";
								$backup_bank_data["folder_location"] = $array["local_folder_path"];
								$backup_bank_data["execution"] = "manual";
								$backup_bank_data["backup_urlpath"] = trailingslashit(dirname($array["backup_path"]));
								$backup_bank_data["log_filename"] = serialize(array($array["log_file"]));
								$backup_bank_data["executed_time"] = $array["backup_destination_time"];
								$backup_bank_data["status"] = "completed_successfully";
								$backup_bank_data["meta_id"] =  $last_id;
								$backup_bank_data["execution_time"] = serialize(array($array["backup_destination_time"]));
								$backup_bank_data["executed_in"] = $array["backup_destination_time"]-$array["backup_start_time"];
								$backup_bank_data["total_size"] = round(filesize($array["backup_local_folder"])/1048576,1)."Mb";
								$backup_bank_data["old_backup"] = "old_backup";
								$backup_bank_data["old_backup_logfile"] = $array["log_file_path"];

								$old_backup_bank_insert_data = array();
								$old_backup_bank_insert_data["meta_key"] = "manual_backup_meta";
								$old_backup_bank_insert_data["meta_value"] = serialize($backup_bank_data);
								$old_backup_bank_insert_data["meta_id"] = $last_id;
								$obj_dbHelper_install_script_backup_bank->insertCommand(backup_bank_meta(),$old_backup_bank_insert_data);
							}
						}
					}
				}
			break;

			default:

				if (count($wpdb->get_var("SHOW TABLES LIKE '" . backup_bank() . "'")) != 0 && count($wpdb->get_var("SHOW TABLES LIKE '" . backup_bank_meta() . "'")) != 0)
				{
					$backup_bank_data = $wpdb->get_results
					(
						$wpdb->prepare
						(
							"SELECT * FROM " . backup_bank() . " inner join " . backup_bank_meta() . " on " . backup_bank() .".id = " . backup_bank_meta().".meta_id where " . backup_bank(). ".type = %s",
							"manual_backup"
						)
					);

					$backup_bank_settings_data = $wpdb->get_results
					(
						$wpdb->prepare
						(
							"SELECT * FROM " . backup_bank() . " inner join " . backup_bank_meta() . " on " . backup_bank() .".id = " . backup_bank_meta().".meta_id where " . backup_bank(). ".type != %s",
							"manual_backup"
						)
					);

					$wpdb->query("DROP TABLE " . backup_bank());
					$wpdb->query("DROP TABLE " . backup_bank_meta());

					backup_bank_table();
					backup_bank_meta_table();

					$backups_id = $wpdb->get_var
					(
						$wpdb->prepare
						(
							"SELECT id FROM ".backup_bank()."
							WHERE type = %s",
							"backups"
						)
					);
					if(count($backup_bank_data) > 0)
					{
						foreach ($backup_bank_data as $total_data)
						{
							$extract_data = unserialize($total_data->meta_value);
							$insert_manual_data = array();
							$insert_manual_data["type"] = "manual_backup";
							$insert_manual_data["parent_id"] = $backups_id;
							$last_id = $obj_dbHelper_install_script_backup_bank->insertCommand(backup_bank(),$insert_manual_data);

							$backup_bank_data = array();
							$backup_bank_data["timezone_difference"] = isset($extract_data["timezone_difference"]) ? $extract_data["timezone_difference"] : -19800;
							$backup_bank_data["backup_name"] = isset($extract_data["backup_name"]) ? $extract_data["backup_name"] : "Backup from Backup Bank";
							$backup_bank_data["backup_type"] = isset($extract_data["backup_type"]) ? $extract_data["backup_type"] : "only_themes";
							$backup_bank_data["exclude_list"] = isset($extract_data["exclude_list"]) ? $extract_data["exclude_list"] : ".svn-base, .git, .ds_store";
							$backup_bank_data["file_compression_type"] = isset($extract_data["file_compression_type"]) ? $extract_data["file_compression_type"] : ".zip";
							$backup_bank_data["db_compression_type"] = isset($extract_data["db_compression_type"]) ? $extract_data["db_compression_type"] : ".sql";
							$backup_bank_data["backup_tables"] = isset($extract_data["backup_tables"]) ? $extract_data["backup_tables"] : "";
							$backup_bank_data["archive"] = isset($extract_data["archive"]) ? $extract_data["archive"] : serialize(array());
							$backup_bank_data["archive_name"] = isset($extract_data["archive_name"]) ? $extract_data["archive_name"] : serialize(array());
							$backup_bank_data["backup_destination"] = isset($extract_data["backup_destination"]) ? $extract_data["backup_destination"] : "local_folder";
							$backup_bank_data["folder_location"] = isset($extract_data["folder_location"]) ? $extract_data["folder_location"] : "";
							$backup_bank_data["execution"] = isset($extract_data["execution"]) ? $extract_data["execution"] : "manual";
							$backup_bank_data["backup_urlpath"] = isset($extract_data["backup_urlpath"]) ? $extract_data["backup_urlpath"] : "";
							$backup_bank_data["log_filename"] = isset($extract_data["log_filename"]) ? $extract_data["log_filename"] : serialize(array());
							$backup_bank_data["executed_time"] = isset($extract_data["executed_time"]) ? $extract_data["executed_time"] : time() - $backup_bank_data["timezone_difference"];
							$backup_bank_data["status"] = isset($extract_data["status"]) ? $extract_data["status"] : "completed_successfully";
							$backup_bank_data["meta_id"] = isset($extract_data["meta_id"]) ? $extract_data["meta_id"] : $last_id;
							$backup_bank_data["execution_time"] = isset($extract_data["execution_time"]) ? $extract_data["execution_time"] : serialize(array());
							$backup_bank_data["executed_in"] = isset($extract_data["executed_in"]) ? $extract_data["executed_in"] : "";
							$backup_bank_data["total_size"] = isset($extract_data["total_size"]) ? $extract_data["total_size"] : "";

							$backup_bank_insert_data = array();
							$backup_bank_insert_data["meta_key"] = "manual_backup_meta";
							$backup_bank_insert_data["meta_value"] = serialize($backup_bank_data);
							$backup_bank_insert_data["meta_id"] = $last_id;
							$obj_dbHelper_install_script_backup_bank->insertCommand(backup_bank_meta(),$backup_bank_insert_data);
						}
					}
					if(isset($backup_bank_settings_data) && count($backup_bank_settings_data) > 0)
					{
						foreach ($backup_bank_settings_data as $settings_data)
						{
							$extract_settings_data = unserialize($settings_data->meta_value);
							$data = array();
							$where = array();
							switch($settings_data->type)
							{
								case "alert_setup":

									$data["email_when_backup_scheduled_successfully"] = isset($extract_settings_data["email_when_backup_scheduled_successfully"]) ? $extract_settings_data["email_when_backup_scheduled_successfully"] : "enable";
									$data["email_when_backup_generated_successfully"] = isset($extract_settings_data["email_when_backup_generated_successfully"]) ? $extract_settings_data["email_when_backup_scheduled_successfully"] : "enable";
									$data["email_when_backup_failed"] = isset($extract_settings_data["email_when_backup_failed"]) ? $extract_settings_data["email_when_backup_failed"] : "enable";
									$data["email_when_restore_completed_successfully"] = isset($extract_settings_data["email_when_restore_completed_successfully"]) ? $extract_settings_data["email_when_restore_completed_successfully"] : "enable";
									$data["email_when_restore_failed"] = isset($extract_settings_data["email_when_restore_failed"]) ? $extract_settings_data["email_when_restore_failed"] : "enable";

									$alert_setup_data = array();
									$where["meta_key"] = "alert_setup";
									$alert_setup_data["meta_value"] = serialize($data);
									$obj_dbHelper_install_script_backup_bank->updateCommand(backup_bank_meta(),$alert_setup_data,$where);

								break;

								case "other_settings":

									$data["automatic_plugin_updates"] = isset($extract_settings_data["automatic_plugin_updates"]) ? $extract_settings_data["automatic_plugin_updates"] : "disable";
									$data["remove_tables_at_uninstall"] = isset($extract_settings_data["remove_tables_at_uninstall"]) ? $extract_settings_data["remove_tables_at_uninstall"] : "enable";

									$other_settings_data = array();
									$where["meta_key"] = "other_settings";

									$other_settings_data["meta_value"] = serialize($data);
									$obj_dbHelper_install_script_backup_bank->updateCommand(backup_bank_meta(),$other_settings_data,$where);

								break;

								case "email_settings":

									$data["backup_to_email"] = isset($extract_settings_data["backup_to_email"]) ? $extract_settings_data["backup_to_email"] : "disable";
									$data["email_address"] = isset($extract_settings_data["email_address"]) ? $extract_settings_data["email_address"] : $admin_email;
									$data["cc_email"] = isset($extract_settings_data["cc_email"]) ? $extract_settings_data["cc_email"] : "";
									$data["bcc_email"] = isset($extract_settings_data["bcc_email"]) ? $extract_settings_data["bcc_email"] : "";
									$data["email_subject"] = isset($extract_settings_data["email_subject"]) ? $extract_settings_data["email_subject"] : "[backup_type] Successfully Generated - Backup Bank";
									$data["email_message"] = isset($extract_settings_data["email_message"]) ? $extract_settings_data["email_message"] : "<p>Hi Admin,</p><p>Kindly find attached Compressed Backup for <strong>[backup_type]</strong> in <strong>[compression_type]</strong> Format with Detailed Log executed by <strong>[username]</strong> on <strong>[start_time]</strong> for your website <strong>[site_url]</strong>.</p><p><u>Here are the details for the Backup :-</u></p><p><strong>Archive Name: </strong>[archive_name]</p><p><strong>Backup Name</strong>: [backup_name]</p><p><strong>Backup Type:</strong> [backup_type]</p><p><strong>Exclude List</strong>: [exclude_list]</p><p><strong>Compression Type</strong>: [compression_type]</p><p><strong>Backup Tables</strong>: [backup_tables]</p><p>Thank you for using <strong>Backup Bank</strong> Plugin.</p><p><strong>Thanks & Regards</strong></p><p><strong>Support Team</strong><br /> <strong>Tech Banker<br /> </strong></p>";

									$email_settings_data = array();
									$where["meta_key"] = "email_settings";
									$email_settings_data["meta_value"] = serialize($data);
									$obj_dbHelper_install_script_backup_bank->updateCommand(backup_bank_meta(),$email_settings_data,$where);

								break;

								case "ftp_settings":

									$data["backup_to_ftp"] = isset($extract_settings_data["backup_to_ftp"]) ? $extract_settings_data["backup_to_ftp"] : "disable";
									$data["protocol"] = isset($extract_settings_data["protocol"]) ? $extract_settings_data["protocol"] : "ftp";
									$data["host"] = isset($extract_settings_data["host"]) ? $extract_settings_data["host"] : "";
									$data["login_type"] = isset($extract_settings_data["login_type"]) ? $extract_settings_data["login_type"] : "username_password";
									$data["ftp_username"] = isset($extract_settings_data["ftp_username"]) ? $extract_settings_data["ftp_username"] : "";
									$data["ftp_password"] = isset($extract_settings_data["ftp_password"]) ? $extract_settings_data["ftp_password"] : "";
									$data["port"] = isset($extract_settings_data["port"]) ? $extract_settings_data["port"] : "";
									$data["remote_path"] = isset($extract_settings_data["remote_path"]) ? $extract_settings_data["remote_path"] : "";
									$data["ftp_mode"] = isset($extract_settings_data["ftp_mode"]) ? $extract_settings_data["ftp_mode"] : "false";

									$ftp_settings_data = array();
									$where["meta_key"] = "ftp_settings";
									$ftp_settings_data["meta_value"] = serialize($data);
									$obj_dbHelper_install_script_backup_bank->updateCommand(backup_bank_meta(),$ftp_settings_data,$where);

								break;
							}
						}
					}
				}
				if (count($wpdb->get_var("SHOW TABLES LIKE '" . backup_bank_restore() . "'")) != 0)
				{
					$bb_restore_unserialized_data = $wpdb->get_row
					(
						"SELECT * FROM " . backup_bank_restore()
					);

					$bb_restore_data = unserialize($bb_restore_unserialized_data->meta_value);

					$wpdb->query("DROP TABLE " . backup_bank_restore());

					backup_bank_table_restore();

					$maintenance_mode_settings = array();
					$where = array();
					if(count($bb_restore_data) > 0)
					{
						$maintenance_mode_settings["message_when_restore"] = isset($bb_restore_data["message_when_restore"]) ? $bb_restore_data["message_when_restore"] : "Site in Maintenance Mode";
						$maintenance_mode_settings["restoring"] = isset($bb_restore_data["restoring"]) ? $bb_restore_data["restoring"] : "disable";
						$where["meta_key"] = "maintenance_mode_settings";

						$maintenance_mode_settings_data = array();
						$maintenance_mode_settings_data["meta_key"] = "maintenance_mode_settings";
						$maintenance_mode_settings_data["meta_value"] = serialize($maintenance_mode_settings);
						$obj_dbHelper_install_script_backup_bank->updateCommand(backup_bank_restore(),$maintenance_mode_settings_data,$where);
					}
				}
			break;
		}
		update_option("backup-bank-version-number","3.0.2");
	}
}
