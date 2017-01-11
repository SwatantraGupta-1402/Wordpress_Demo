<?php
/**
* This file is used for managing data in database.
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

		if(isset($_REQUEST["param"]))
		{
			$obj_dbHelper_backup_bank = new dbHelper_backup_bank();
			$dbMailer_backup_bank_obj = new dbMailer_backup_bank();
			switch(esc_attr($_REQUEST["param"]))
			{
				case "backup_bank_manual_backup_module":
					if(wp_verify_nonce(isset($_REQUEST["_wp_nonce"]) ? esc_attr($_REQUEST["_wp_nonce"]) : "", "backup_bank_manual_backup"))
					{
						parse_str(base64_decode(isset($_REQUEST["data"]) ? $_REQUEST["data"] : ""),$backup_bank_data_array);
						$encrypted_tables = isset($_REQUEST["encrypted_tables"]) ? json_decode(stripslashes($_REQUEST["encrypted_tables"])) : "";
						$timezone_difference = isset($_REQUEST["timezone_difference"]) ? $_REQUEST["timezone_difference"] : "";
						$backups_id = $wpdb->get_var
						(
							$wpdb->prepare
							(
								"SELECT id FROM ".backup_bank()."
								WHERE type=%s",
								"backups"
							)
						);

						$backup_bank_data = array();
						$backup_bank_data["timezone_difference"] = $timezone_difference * 60;
						$backup_bank_data["backup_name"] = esc_html($backup_bank_data_array["ux_txt_backup_name"]);
						$backup_bank_data["backup_type"] = esc_attr($backup_bank_data_array["ux_ddl_backup_type"]);
						$backup_bank_data["exclude_list"] = esc_html($backup_bank_data_array["ux_txt_return_email"]);
						$backup_bank_data["file_compression_type"] = esc_attr($backup_bank_data_array["ux_ddl_file_compression_type"]);
						$backup_bank_data["db_compression_type"] = esc_attr($backup_bank_data_array["ux_ddl_db_compression_type"]);
						$backup_bank_data["backup_tables"] = esc_attr(implode(",",$encrypted_tables));
						$backup_bank_data["archive"] = isset($_REQUEST["archive"]) ? serialize(array(esc_html($_REQUEST["archive"]))) : "";
						$backup_bank_data["archive_name"] = isset($_REQUEST["archive_name"]) ? serialize(array(esc_html($_REQUEST["archive_name"]))) : "";
						$backup_bank_data["backup_destination"] = esc_attr($backup_bank_data_array["ux_ddl_backup_destination_type"]);
						$backup_bank_data["folder_location"] = esc_html($backup_bank_data_array["ux_txt_content_location"]).esc_html($backup_bank_data_array["ux_txt_folder_location"]);
						$backup_bank_data["execution"] = esc_attr("manual");
						$backup_bank_data["backup_urlpath"] = content_url().esc_html($backup_bank_data_array["ux_txt_folder_location"]);
						$backup_bank_data["log_filename"] = isset($_REQUEST["archive_name"]) ? serialize(array(esc_html($_REQUEST["archive_name"]).".txt")) : "";
						$backup_bank_data["executed_time"] = time() - $backup_bank_data["timezone_difference"];
						$backup_bank_data["status"] = "running";

						$insert_manual_data = array();
						$insert_manual_data["type"] = "manual_backup";
						$insert_manual_data["parent_id"] = $backups_id;
						$last_id = $obj_dbHelper_backup_bank->insertCommand(backup_bank(),$insert_manual_data);

						$backup_bank_data["meta_id"] = $last_id;

						$backup_bank_insert_data = array();
						$backup_bank_insert_data["meta_key"] = "manual_backup_meta";
						$backup_bank_insert_data["meta_value"] = serialize($backup_bank_data);
						$backup_bank_insert_data["meta_id"] = $last_id;
						$obj_dbHelper_backup_bank->insertCommand(backup_bank_meta(),$backup_bank_insert_data);

						$obj_backup_data_backup_bank = new backup_data_backup_bank();
						$obj_backup_data_backup_bank->close_browser_connection();
						do_action("start_backup",$backup_bank_data);
					}
				break;

				case "backup_bank_schedule_backup_module":
					if(wp_verify_nonce(isset($_REQUEST["_wp_nonce"]) ? esc_attr($_REQUEST["_wp_nonce"]) : "", "backup_bank_schedule_backup"))
					{
						parse_str(base64_decode(isset($_REQUEST["data"]) ? $_REQUEST["data"] : ""),$backup_bank_schedule_array);

						$start_hours = intval($backup_bank_schedule_array["ux_ddl_start_hours"]);
						$start_min = intval($backup_bank_schedule_array["ux_ddl_start_minutes"]);
						$start_time = $start_hours + $start_min;
						$encrypted_tables = isset($_REQUEST["encrypted_tables"]) ? json_decode(stripslashes($_REQUEST["encrypted_tables"])) : "";

						$schedule_id = $wpdb->get_var
						(
							$wpdb->prepare
							(
								"SELECT id From " .backup_bank()."
								WHERE type= %s",
								"backups"
							)
						);

						$backup_bank_schedule = array();
						$backup_bank_schedule["start_on"] = strtotime(esc_attr($backup_bank_schedule_array["ux_txt_start_on"]));
						$backup_bank_schedule["start_time"] = $start_time;
						$backup_bank_schedule["schedule_duration"] = esc_attr($backup_bank_schedule_array["ux_ddl_duration"]);
						$backup_bank_schedule["end_on"] = esc_attr($backup_bank_schedule_array["ux_ddl_end_time"]);
						$backup_bank_schedule["end_date"] = strtotime(esc_attr($backup_bank_schedule_array["ux_txt_schedule_end_date"]));
						$backup_bank_schedule["repeat_every"] = esc_attr($backup_bank_schedule_array["ux_ddl_repeat_every"]);
						$backup_bank_schedule["time_zone"] = esc_attr($backup_bank_schedule_array["ux_ddl_time_zone"]);
						$backup_bank_schedule["backup_name"] = esc_html($backup_bank_schedule_array["ux_txt_backup_name"]);
						$backup_bank_schedule["backup_type"] = esc_attr($backup_bank_schedule_array["ux_ddl_backup_type"]);
						$backup_bank_schedule["exclude_list"] = esc_html($backup_bank_schedule_array["ux_txt_exclude_list"]);
						$backup_bank_schedule["file_compression_type"] = esc_attr($backup_bank_schedule_array["ux_ddl_file_compression_type"]);
						$backup_bank_schedule["db_compression_type"] = esc_attr($backup_bank_schedule_array["ux_ddl_db_compression_type"]);
						$backup_bank_schedule["backup_tables"] = esc_attr(implode(",",$encrypted_tables));
						$backup_bank_schedule["archive"] = isset($_REQUEST["archive"]) ? serialize(array(esc_html($_REQUEST["archive"]))) : "";
						$backup_bank_schedule["archive_name"] = isset($_REQUEST["archive_name"]) ? serialize(array(esc_html($_REQUEST["archive_name"]))) : "";
						$backup_bank_schedule["backup_destination"] = esc_attr($backup_bank_schedule_array["ux_ddl_backup_destination_type"]);
						$backup_bank_schedule["folder_location"] = esc_html($backup_bank_schedule_array["ux_txt_content_location"]).esc_html($backup_bank_schedule_array["ux_txt_folder_location"]);
						$backup_bank_schedule["execution"] = esc_attr("scheduled");
						$backup_bank_schedule["achive_format"] = esc_attr($backup_bank_schedule_array["ux_txt_archive_name"]);
						$backup_bank_schedule["status"] = esc_attr("not_yet_executed");
						$backup_bank_schedule["backup_urlpath"] = content_url().esc_html($backup_bank_schedule_array["ux_txt_folder_location"]);
						$backup_bank_schedule["log_filename"] = isset($_REQUEST["archive_name"]) ? serialize(array(esc_html($_REQUEST["archive_name"].".txt"))) : "";

						$backup_bank_schedule["timezone_difference"] = timezone_difference_backup_bank($backup_bank_schedule["time_zone"]);

						$insert_schedule_data = array();
						$insert_schedule_data["type"] = "schedule_backup";
						$insert_schedule_data["parent_id"] = $schedule_id;
						$last_id = $obj_dbHelper_backup_bank->insertCommand(backup_bank(),$insert_schedule_data);

						$time_interval = esc_attr($backup_bank_schedule_array["ux_ddl_duration"]);
						if($time_interval == "Hourly")
						{
							$time_interval = esc_attr($backup_bank_schedule_array["ux_ddl_repeat_every"]);
						}
						$cron_name = "backup_scheduler_".$last_id;
						$backup_bank_schedule["meta_id"] = $last_id;

						$database_timestamp = strtotime(esc_attr($backup_bank_schedule_array["ux_txt_start_on"])) + $start_time;
						$timezone = esc_attr($backup_bank_schedule_array["ux_ddl_time_zone"]);
						scheduler_for_backup_bank($cron_name,$time_interval,$database_timestamp,$timezone);

						$backup_bank_insert_schedule_data = array();
						$backup_bank_insert_schedule_data["meta_key"] = "backup_schedule_meta";
						$backup_bank_insert_schedule_data["meta_value"] = serialize($backup_bank_schedule);
						$backup_bank_insert_schedule_data["meta_id"] = $last_id;
						$obj_dbHelper_backup_bank->insertCommand(backup_bank_meta(),$backup_bank_insert_schedule_data);

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
						if($alert_setup_data_array["email_when_backup_scheduled_successfully"] == "enable")
						{
							$backup_generated_data = $wpdb->get_var
							(
								$wpdb->prepare
								(
									"SELECT meta_value FROM ".backup_bank_meta().
									" WHERE meta_key = %s",
									"template_for_scheduled_backup"
								)
							);

							$backup_generated_data_array = unserialize($backup_generated_data);
							$dbMailer_backup_bank_obj->email_when_backup_scheduled_successfully($backup_generated_data_array,$backup_bank_schedule);
						}
					}
				break;

				case "backup_bank_change_email_template_module":
					if(wp_verify_nonce(isset($_REQUEST["_wp_nonce"]) ? esc_attr($_REQUEST["_wp_nonce"]) : "", "backup_bank_change_template"))
					{
						$template_type = isset($_REQUEST["data"]) ? esc_attr($_REQUEST["data"]) : "";

						$email_templates_data_array = $wpdb->get_results
						(
							$wpdb->prepare
							(
								"SELECT * FROM " . backup_bank_meta(). " WHERE "
								.backup_bank_meta().".meta_key = %s",
								"$template_type"
							)
						);
						$email_templates_data = get_backup_bank_unserialize_data($email_templates_data_array);
						echo json_encode($email_templates_data);
					}
				break;

				case "backup_bank_alert_setup_module":
					if(wp_verify_nonce(isset($_REQUEST["_wp_nonce"]) ? esc_attr($_REQUEST["_wp_nonce"]) : "", "backup_bank_alert_setup"))
					{
						parse_str(isset($_REQUEST["data"]) ? $_REQUEST["data"] : "",$backup_bank_alert_setup_data);

						$bb_alert_setup_id = $wpdb->get_var
						(
							$wpdb->prepare
							(
								"SELECT id FROM ".backup_bank().
								" WHERE type=%s",
								"alert_setup"
							)
						);

						$bb_alert_setup_data = array();
						$bb_alert_setup_data["email_when_backup_scheduled_successfully"] = esc_attr($backup_bank_alert_setup_data["ux_ddl_backup_scheduled_successfull"]);
						$bb_alert_setup_data["email_when_backup_generated_successfully"] = esc_attr($backup_bank_alert_setup_data["ux_ddl_backup_generated_successfull"]);
						$bb_alert_setup_data["email_when_backup_failed"] = esc_attr($backup_bank_alert_setup_data["ux_ddl_backup_failed"]);
						$bb_alert_setup_data["email_when_restore_completed_successfully"] = esc_attr($backup_bank_alert_setup_data["ux_ddl_restore_completed_successfull"]);
						$bb_alert_setup_data["email_when_restore_failed"] = esc_attr($backup_bank_alert_setup_data["ux_ddl_restore_failed"]);

						$update_alert_setup = array();
						$where = array();
						$where["meta_id"] = $bb_alert_setup_id;
						$where["meta_key"] = "alert_setup";
						$update_alert_setup["meta_value"] = serialize($bb_alert_setup_data);
						$obj_dbHelper_backup_bank->updateCommand(backup_bank_meta(),$update_alert_setup,$where);
					}
				break;

				case "backup_bank_other_settings_module":
					if(wp_verify_nonce(isset($_REQUEST["_wp_nonce"]) ? esc_attr($_REQUEST["_wp_nonce"]) : "", "backup_bank_other_settings"))
					{
						parse_str(base64_decode(isset($_REQUEST["data"]) ? $_REQUEST["data"] : ""),$backup_bank_other_settings_data);

						$bb_other_settings_id = $wpdb->get_var
						(
							$wpdb->prepare
							(
								"SELECT id FROM ".backup_bank().
								" WHERE type = %s",
								"other_settings"
							)
						);

						$bb_other_settings_data = array();
						$bb_other_settings_data["automatic_plugin_updates"] = esc_attr($backup_bank_other_settings_data["ux_ddl_automatic_plugin_updates"]);
						$bb_other_settings_data["remove_tables_at_uninstall"] = esc_attr($backup_bank_other_settings_data["ux_ddl_remove_tables"]);

						$update_other_settings = array();
						$where = array();
						$where["meta_id"] = $bb_other_settings_id;
						$where["meta_key"] = "other_settings";
						$update_other_settings["meta_value"] = serialize($bb_other_settings_data);
						$obj_dbHelper_backup_bank->updateCommand(backup_bank_meta(),$update_other_settings,$where);

						$bb_other_settings_maintenance_mode = array();
						$bb_other_settings_maintenance_mode["message_when_restore"] = esc_html($backup_bank_other_settings_data["ux_txt_maintenance_mode_message"]);
						$bb_other_settings_maintenance_mode["restoring"] = esc_attr($backup_bank_other_settings_data["ux_ddl_maintenance_mode"]);
						$update_other_settings_maintenance_mode = array();
						$where = array();
						$where["meta_key"] = "maintenance_mode_settings";
						$update_other_settings_maintenance_mode["meta_value"] = serialize($bb_other_settings_maintenance_mode);
						$obj_dbHelper_backup_bank->updateCommand(backup_bank_restore(),$update_other_settings_maintenance_mode,$where);
					}
				break;

				case "backup_bank_email_settings_module":
					if(wp_verify_nonce(isset($_REQUEST["_wp_nonce"]) ? esc_attr($_REQUEST["_wp_nonce"]) : "", "backup_bank_email_settings"))
					{
						parse_str(base64_decode(isset($_REQUEST["data"]) ? $_REQUEST["data"] : ""),$email_settings_form_data);

						$bb_email_settings_id = $wpdb->get_var
						(
							$wpdb->prepare
							(
								"SELECT id FROM ".backup_bank().
								" WHERE type = %s",
								"email_settings"
							)
						);
						$update_email_setting_data = array();
						$update_email_setting_data["backup_to_email"] = esc_attr($email_settings_form_data["ux_ddl_email_settings_enable_disable"]);
						$update_email_setting_data["email_address"] = esc_html($email_settings_form_data["ux_txt_email_address"]);
						$update_email_setting_data["cc_email"] = esc_html($email_settings_form_data["ux_txt_email_cc"]);
						$update_email_setting_data["bcc_email"] = esc_html($email_settings_form_data["ux_txt_email_bcc"]);
						$update_email_setting_data["email_subject"] = esc_html($email_settings_form_data["ux_txt_email_subject"]);
						$update_email_setting_data["email_message"] = htmlspecialchars_decode(esc_attr($email_settings_form_data["ux_txt_email_settings_message"]));

						$email_setting_data = array();
						$where = array();
						$where["meta_id"] = $bb_email_settings_id;
						$where["meta_key"] = "email_settings";
						$email_setting_data["meta_value"] = serialize($update_email_setting_data);
						$obj_dbHelper_backup_bank->updateCommand(backup_bank_meta(),$email_setting_data,$where);
					}
				break;

				case "backup_bank_manage_backups_delete_module":
					if(wp_verify_nonce(isset($_REQUEST["_wp_nonce"]) ? esc_attr($_REQUEST["_wp_nonce"]) : "", "backup_bank_manage_backups_delete"))
					{
						$where = array();
						$where_meta = array();
						$where["id"] = isset($_REQUEST["id"]) ? intval($_REQUEST["id"]) : "";
						$bb_backup_data = $wpdb->get_var
						(
							$wpdb->prepare
							(
								"SELECT meta_value FROM ".backup_bank_meta().
								" WHERE meta_id = %d",
								$where["id"]
							)
						);
						$bb_backup_data_array = unserialize($bb_backup_data);
						$where_meta["meta_id"] = isset($_REQUEST["id"]) ? intval($_REQUEST["id"]) : "";
						$obj_dbHelper_backup_bank->deleteCommand(backup_bank(),$where);
						$obj_dbHelper_backup_bank->deleteCommand(backup_bank_meta(),$where_meta);

						if($bb_backup_data_array["execution"] == "scheduled")
						{
							$cron_name = "backup_scheduler_".$where["id"];
							unschedule_events_backup_bank($cron_name);
						}
						$backup_archive = unserialize($bb_backup_data_array["archive"]);
						$logfile_archive = unserialize($bb_backup_data_array["log_filename"]);
						if(isset($backup_archive) && count($backup_archive))
						{
							foreach ($backup_archive as $value)
							{
								if($bb_backup_data_array["status"] != "file_exists")
								{
									@unlink(untrailingslashit($bb_backup_data_array["folder_location"])."/".$value);
								}
							}
						}
						if(isset($backup_archive) && count($backup_archive))
						{
							foreach ($logfile_archive as $value)
							{
								if($bb_backup_data_array["status"] != "file_exists")
								{
									@unlink(untrailingslashit($bb_backup_data_array["folder_location"])."/".$value);
									@unlink(untrailingslashit($bb_backup_data_array["folder_location"])."/".str_replace(".txt",".json",$value));
								}
							}
						}
						if(isset($bb_backup_data_array["restore_log_filename"]))
						{
							$restore_logfile = unserialize($bb_backup_data_array["restore_log_filename"]);
							if($bb_backup_data_array["status"] != "file_exists")
							{
								if(isset($restore_logfile) && count($restore_logfile) > 0)
								{
									foreach ($restore_logfile as $value)
									{
										@unlink($value);
										@unlink(str_replace(".txt",".json",$value));
									}
								}
							}
						}
					}
				break;

				case "backup_bank_manage_backups_bulk_delete_module":
					if(wp_verify_nonce(isset($_REQUEST["_wp_nonce"]) ? esc_attr($_REQUEST["_wp_nonce"]) : "", "backup_bank_manage_backups_bulk_delete"))
					{
						$encrypted_records = isset($_REQUEST["encrypted_records"]) ? json_decode(stripslashes($_REQUEST["encrypted_records"])) : "";
						$backup_ids = implode(",",$encrypted_records);

						$bb_backup_data = $wpdb->get_results
						(
							"SELECT * FROM ".backup_bank_meta().
							" WHERE meta_id in ($backup_ids)"
						);

						$bb_backup_data_array = get_backup_bank_unserialize_data($bb_backup_data);
						if(isset($restore_logfile) && count($restore_logfile) > 0)
						{
							foreach($bb_backup_data_array as $value)
							{
								if($value["execution"] == "scheduled")
								{
									$cron_name = "backup_scheduler_".$value["meta_id"];
									unschedule_events_backup_bank($cron_name);
								}
								$backup_archive = unserialize($value["archive"]);
								$logfile_archive = unserialize($value["log_filename"]);
								if(count($backup_archive) > 0)
								{
									foreach ($backup_archive as $data)
									{
										if($value["status"] != "file_exists")
										{
											@unlink(untrailingslashit($value["folder_location"])."/".$data);
										}
									}
								}
								if(count($logfile_archive) > 0)
								{
									foreach ($logfile_archive as $data)
									{
										if($value["status"] != "file_exists")
										{
											@unlink(untrailingslashit($value["folder_location"])."/".$data);
											@unlink(untrailingslashit($value["folder_location"])."/".str_replace(".txt",".json",$data));
										}
									}
								}
								if(isset($value["restore_log_filename"]))
								{
									$restore_logfile = unserialize($value["restore_log_filename"]);
									if($value["status"] != "file_exists")
									{
										if(count($restore_logfile) > 0)
										{
											foreach ($restore_logfile as $data)
											{
												@unlink($data);
												@unlink(str_replace(".txt",".json",$data));
											}
										}
									}
								}
							}
						}

						$obj_dbHelper_backup_bank->bulk_deleteCommand(backup_bank(),"id",$backup_ids);
						$obj_dbHelper_backup_bank->bulk_deleteCommand(backup_bank_meta(),"meta_id",$backup_ids);
					}
				break;

				case "backup_bank_ftp_settings_module":
					if(wp_verify_nonce(isset($_REQUEST["_wp_nonce"]) ? esc_attr($_REQUEST["_wp_nonce"]) : "", "backup_bank_ftp_settings"))
					{
						parse_str(base64_decode(isset($_REQUEST["data"]) ? $_REQUEST["data"] : ""),$ftp_settings_data_array);

						$bb_ftp_settings_id = $wpdb->get_var
						(
							$wpdb->prepare
							(
								"SELECT id FROM ".backup_bank().
								" WHERE type = %s",
								"ftp_settings"
							)
						);

						$ftp_settings_data = array();
						$ftp_settings_data["backup_to_ftp"] = esc_attr($ftp_settings_data_array["ux_ddl_ftp_settings_enable_disable"]);
						$ftp_settings_data["protocol"] = esc_attr($ftp_settings_data_array["ux_ddl_ftp_protocol"]);
						$ftp_settings_data["host"] = esc_html($ftp_settings_data_array["ux_txt_ftp_settings_host"]);
						$ftp_settings_data["login_type"] = esc_attr($ftp_settings_data_array["ux_ddl_login_type"]);
						$ftp_settings_data["ftp_username"] = esc_html($ftp_settings_data_array["ux_txt_ftp_settings_username"]);
						$ftp_settings_data["ftp_password"] = esc_html($ftp_settings_data_array["ux_txt_ftp_settings_password"]);

						$ftp_settings_data["port"] = esc_html($ftp_settings_data_array["ux_txt_ftp_settings_port"]);
						$ftp_settings_data["remote_path"] = esc_html($ftp_settings_data_array["ux_txt_ftp_settings_remote_path"]);
						$ftp_settings_data["ftp_mode"] = esc_attr($ftp_settings_data_array["ux_ddl_ftp_mode"]);

						if($ftp_settings_data["backup_to_ftp"] == "enable")
						{
							$obj_ftp_connect = new ftp_connection_backup_bank();
							$ftp_connection = $obj_ftp_connect->ftp_connect($ftp_settings_data["host"],$ftp_settings_data["protocol"],$ftp_settings_data["port"]);
							$ftp_login = false;
							if($ftp_connection != false)
							{
								$ftp_login = $obj_ftp_connect->login_ftp($ftp_connection,$ftp_settings_data["login_type"],$ftp_settings_data["ftp_username"],$ftp_settings_data_array["ux_txt_ftp_settings_password"]);
							}
						}
						else
						{
							$ftp_login = true;
						}

						if($ftp_login != false)
						{
							$update_ftp_settings = array();
							$where = array();
							$where["meta_id"] = $bb_ftp_settings_id;
							$where["meta_key"] = "ftp_settings";
							$update_ftp_settings["meta_value"] = serialize($ftp_settings_data);
							$obj_dbHelper_backup_bank->updateCommand(backup_bank_meta(),$update_ftp_settings,$where);
						}
					}
				break;

				case "check_ftp_dropbox_connection":
					if(wp_verify_nonce(isset($_REQUEST["_wp_nonce"]) ? esc_attr($_REQUEST["_wp_nonce"]) : "", "backup_bank_check_ftp_dropbox_connection"))
					{
						$backup_destination = isset($_REQUEST["backup_destination"]) ? base64_decode($_REQUEST["backup_destination"]) : "";
						$backup_type = isset($_REQUEST["type"]) ? esc_attr($_REQUEST["type"]) : "";

						if($backup_type != "schedule")
						{
							$archive_name = isset($_REQUEST["archive_name"]) ? base64_decode($_REQUEST["archive_name"]) : "";
							$location = base64_decode(isset($_REQUEST["content_location"]) ? $_REQUEST["content_location"] : "").base64_decode(isset($_REQUEST["folder_location"]) ? $_REQUEST["folder_location"] : "");
							!is_dir($location) ? wp_mkdir_p($location) : "";

							$file_name = trailingslashit($location).$archive_name.".json";

							$path = trailingslashit(base64_decode(isset($_REQUEST["folder_location"]) ? $_REQUEST["folder_location"] : ""));
							$file_url_path = content_url().$path.$archive_name.".json";

							$result = 1;
						}
						switch($backup_destination)
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
								$upload_ftp = "";
								$ftp_settings_data_array = unserialize($ftp_settings_data);
								$obj_ftp_connect = new ftp_connection_backup_bank();
								$ftp_connection = $obj_ftp_connect->ftp_connect($ftp_settings_data_array["host"],$ftp_settings_data_array["protocol"],$ftp_settings_data_array["port"]);

								if($ftp_connection != false)
								{
									$ftp_login = $obj_ftp_connect->login_ftp($ftp_connection,$ftp_settings_data_array["login_type"],$ftp_settings_data_array["ftp_username"],$ftp_settings_data_array["ftp_password"]);
									if($ftp_login == false)
									{
										die();
									}
									$ftp_result = $obj_ftp_connect->ftp_mkdir_recusive($ftp_connection,trailingslashit($ftp_settings_data_array["remote_path"]).BACKUP_BANK_FOLDER_DROPBOX);

									if($ftp_result !== false)
									{
										$ftp_connection->pasv($ftp_settings_data_array["ftp_mode"]);
										$test_file = BACKUP_BANK_DIR_PATH."lib/ftp-client/backup-bank-ftp-test.txt";
										$backup_name = basename($test_file);
										if(!@$ftp_connection->put($backup_name,$test_file,FTP_BINARY))
										{
											$upload_ftp = "550";
										}
									}
									else
									{
										$upload_ftp = "550";
									}
								}
								else
								{
									die();
								}
								if($upload_ftp != "")
								{
									echo $upload_ftp;
									die();
								}
							break;
						}

						if($backup_type != "schedule")
						{
							file_put_contents($file_name, "");
							$message = "{"."\r\n";
							$message .= '"log": '.'"Starting Backup"'.','."\r\n";
							$message .= '"perc": '.$result.','."\r\n";
							$message .= '"status": '.'"Starting"'.','."\r\n";
							$message .= '"cloud": '.'1'."\r\n";
							$message .= "}";
							file_put_contents($file_name, $message);
							echo untrailingslashit($file_url_path);
						}
					}
				break;

				case "check_ftp_dropbox_connection_rerun":
					if(wp_verify_nonce(isset($_REQUEST["_wp_nonce"]) ? esc_attr($_REQUEST["_wp_nonce"]) : "", "backup_bank_check_ftp_dropbox_connection_rerun"))
					{
						$backup_destination = isset($_REQUEST["backup_destination"]) ? base64_decode($_REQUEST["backup_destination"]) : "";

						$archive_name = isset($_REQUEST["archive_name"]) ? base64_decode($_REQUEST["archive_name"]) : "";
						$location = base64_decode($_REQUEST["location"]);
						!is_dir($location) ? wp_mkdir_p($location) : "";

						$file_name = trailingslashit($location).$archive_name.".json";
						$file_url_path = str_replace(str_replace("\\","/",WP_CONTENT_DIR),content_url(),$file_name);

						$result = 1;
						switch($backup_destination)
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
								$upload_ftp = "";
								$ftp_settings_data_array = unserialize($ftp_settings_data);
								if($ftp_settings_data_array["backup_to_ftp"] == "disable")
								{
									echo "553";
									die();
								}
								$obj_ftp_connect = new ftp_connection_backup_bank();
								$ftp_connection = $obj_ftp_connect->ftp_connect($ftp_settings_data_array["host"],$ftp_settings_data_array["protocol"],$ftp_settings_data_array["port"]);

								if($ftp_connection != false)
								{
									$ftp_login = $obj_ftp_connect->login_ftp($ftp_connection,$ftp_settings_data_array["login_type"],$ftp_settings_data_array["ftp_username"],$ftp_settings_data_array["ftp_password"]);
									if($ftp_login == false)
									{
										die();
									}
									$ftp_result = $obj_ftp_connect->ftp_mkdir_recusive($ftp_connection,$ftp_settings_data_array["remote_path"]);

									if($ftp_result !== false)
									{
										$ftp_connection->pasv($ftp_settings_data_array["ftp_mode"]);
										$test_file = BACKUP_BANK_DIR_PATH."lib/ftp-client/backup-bank-ftp-test.txt";
										$backup_name = basename($test_file);
										if(!@$ftp_connection->put($backup_name,$test_file,FTP_BINARY))
										{
											$upload_ftp = "550";
										}
									}
									else
									{
										$upload_ftp = "550";
									}
								}
								else
								{
									die();
								}
								if($upload_ftp != "")
								{
									echo $upload_ftp;
									die();
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
								if($email_settings_array["backup_to_email"] == "disable")
								{
									echo "555";
									die();
								}
							break;
						}
						$message = "{"."\r\n";
						$message .= '"log": '.'"Re-running Backup"'.','."\r\n";
						$message .= '"perc": '.$result."\r\n";
						$message .= '"cloud": '.'1'."\r\n";
						$message .= "}";
						file_put_contents($file_name, $message);
						echo $file_url_path;
					}
				break;

				case "backup_bank_rerun_backups":
					if(wp_verify_nonce(isset($_REQUEST["_wp_nonce"]) ? esc_attr($_REQUEST["_wp_nonce"]) : "", "backup_bank_manage_rerun_backups"))
					{
						$backup_id = isset($_REQUEST["id"]) ? intval($_REQUEST["id"]) : "";

						$backups_data = $wpdb->get_var
						(
							$wpdb->prepare
							(
								"SELECT meta_value FROM ".backup_bank_meta()."
								WHERE meta_id=%d",
								$backup_id
							)
						);
						$backups_data_array = unserialize($backups_data);

						@unlink(untrailingslashit($backups_data_array["folder_location"])."/".implode("",unserialize($backups_data_array["archive"])));
						@unlink(untrailingslashit($backups_data_array["folder_location"])."/".implode("",unserialize($backups_data_array["log_filename"])));

						$backups_data_array["timezone_difference"] = isset($_REQUEST["timezone_difference"]) ? $_REQUEST["timezone_difference"] * 60 : "";
						if(isset($backups_data_array["old_backup"]))
						{
							unset($backups_data_array["old_backup"]);
							unset($backups_data_array["old_backup_logfile"]);
						}

						$obj_backup_data_backup_bank = new backup_data_backup_bank();
						$obj_backup_data_backup_bank->close_browser_connection();
						do_action("start_backup",$backups_data_array);
					}
				break;
				}
			die();
		}
	}
}
?>
