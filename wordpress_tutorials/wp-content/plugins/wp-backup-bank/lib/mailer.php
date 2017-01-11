<?php
/*
Class Name: dbMailer_backup_bank
Parameters: No
Description: This Class is used for send Emails.
Created On: 07-04-2016 3:38
Created By: Tech Banker Team
*/
if(!defined("ABSPATH")) exit; // Exit if accessed directly
if(!class_exists("dbMailer_backup_bank"))
{
	class dbMailer_backup_bank
	{
		/*
		Function Name: email_when_backup_generated_successfully
		Parameters: yes($backup_generated_data_array,$backup_bank_data)
		Description: This function is used for sending Emails when backup is successfully generated.
		Created On: 07-04-2016 04:01
		Created By: Tech Banker Team
		*/

		function email_when_backup_generated_successfully($backup_generated_data_array,$backup_bank_data)
		{
			if(file_exists(BACKUP_BANK_DIR_PATH."includes/translations.php"))
			{
				include BACKUP_BANK_DIR_PATH."includes/translations.php";
			}
			$datetime = date("d M Y H:i A");
			global $current_user;
			$headers = "";
			$headers .= "Content-Type: text/html; charset= utf-8". "\r\n";
			if($backup_generated_data_array["email_cc"] != "")
			{
				$headers .= "Cc: " .$backup_generated_data_array["email_cc"]. "\r\n";
			}
			if($backup_generated_data_array["email_bcc"] != "")
			{
				$headers .= "Bcc: " .$backup_generated_data_array["email_bcc"]."\r\n";
			}
			switch($backup_bank_data["backup_type"])
			{
				case "only_themes":
					$type = $bb_themes;
					$exclude = $backup_bank_data["exclude_list"] != "" ? $backup_bank_data["exclude_list"] : $bb_na;
					$db_compression = $bb_na;
					$database_table = $bb_na;
					$file_compression = $backup_bank_data["file_compression_type"];
				break;

				case "only_plugins":
					$type = $bb_plugins;
					$exclude = $backup_bank_data["exclude_list"] != "" ? $backup_bank_data["exclude_list"] : $bb_na;
					$db_compression = $bb_na;
					$database_table = $bb_na;
					$file_compression = $backup_bank_data["file_compression_type"];
				break;

				case "only_wp_content_folder":
					$type = $bb_contents;
					$exclude = $backup_bank_data["exclude_list"] != "" ? $backup_bank_data["exclude_list"] : $bb_na;
					$db_compression = $bb_na;
					$database_table = $bb_na;
					$file_compression = $backup_bank_data["file_compression_type"];
				break;

				case "complete_backup":
					$type = $bb_complete_backup;
					$exclude = $backup_bank_data["exclude_list"] != "" ? $backup_bank_data["exclude_list"] : $bb_na;
					$db_compression = $backup_bank_data["db_compression_type"];
					$db_tables =	$backup_bank_data["backup_tables"];

					$data_table_array = explode(",",$db_tables);
					$database_table = "<ul>";
					if(isset($data_table_array) && count($data_table_array) > 0)
					{
						foreach($data_table_array as $row)
						{
							$database_table .= "<li style=\"margin-left: 0px;\">".$row."</li>";
						}
					}
					$database_table .= "</ul>";
					$file_compression = $backup_bank_data["file_compression_type"];
				break;

				case "only_filesystem":
					$type = $bb_filesystem;
					$exclude = $backup_bank_data["exclude_list"] != "" ? $backup_bank_data["exclude_list"] : $bb_na;
					$db_compression = $bb_na;
					$database_table = $bb_na;
					$file_compression = $backup_bank_data["file_compression_type"];
				break;

				case "only_plugins_and_themes":
					$type = $bb_plugins_themes;
					$exclude = $backup_bank_data["exclude_list"] != "" ? $backup_bank_data["exclude_list"] : $bb_na;
					$db_compression = $bb_na;
					$database_table = $bb_na;
					$file_compression = $backup_bank_data["file_compression_type"];
				break;

				case "only_database":
					$type = $bb_database;
					$exclude = $bb_na;
					$db_compression = $backup_bank_data["db_compression_type"];
					$db_tables =	$backup_bank_data["backup_tables"];

					$data_table_array = explode(",",$db_tables);
					$database_table = "<ul>";
					if(isset($data_table_array) && count($data_table_array) > 0)
					{
						foreach($data_table_array as $row)
						{
							$database_table .= "<li style=\"margin-left: 0px;\">".$row."</li>";
						}
					}
					$database_table .= "</ul>";
					$file_compression = $bb_na;
				break;
			}

			switch($backup_bank_data["backup_destination"])
			{
				case "email":
					$backup_dest = $bb_email;
				break;

				case "ftp":
					$backup_dest = $bb_ftp;
				break;

				default:
					$backup_dest = $bb_local_folder;
			}

			$subject = $backup_generated_data_array["email_subject"];
			$replace_subject = str_replace("[backup_type]",$type,$subject);
			$message = "<div style=\"font-family: Calibri;\">";
			$message .= $backup_generated_data_array["email_message"];
			$message .= "</div>";

			if($backup_bank_data["execution"] == "scheduled")
			{
				$archive_array = unserialize($backup_bank_data["archive"]);
				$archive_name = $archive_array[count($archive_array)-2];
			}
			else
			{
				$archive_name = implode("",unserialize($backup_bank_data["archive"]));
			}

			$replace_type_message = str_replace("[backup_type]",$type,$message);
			$replace_type_site_url = str_replace("[site_url]",site_url(),$replace_type_message);
			$replace_type_archive = str_replace("[archive_name]","<a href=".$backup_bank_data["backup_urlpath"].$archive_name.">".$archive_name."</a>",$replace_type_site_url);
			$replace_type_backup_name = str_replace("[backup_name]",$backup_bank_data["backup_name"],$replace_type_archive);
			$replace_type_exclude_list = str_replace("[exclude_list]",$exclude,$replace_type_backup_name);
			$replace_type_file_compression = str_replace("[file_compression_type]",$file_compression,$replace_type_exclude_list);
			$replace_type_db_compression = str_replace("[db_compression_type]",$db_compression,$replace_type_file_compression);
			$replace_type_table = str_replace("[backup_tables]",$database_table,$replace_type_db_compression);
			$replace_type_location = str_replace("[folder_location]",$backup_bank_data["folder_location"],$replace_type_table);
			$replace_type_start_time = str_replace("[start_time]",$datetime,$replace_type_location);
			$user = $backup_bank_data["execution"] == "scheduled" ? $bb_scheduler : $current_user->display_name;
			$replace_type_username = str_replace("[username]",$user,$replace_type_start_time);
			$replace_type_backup_destination = str_replace("[backup_destination]",$backup_dest,$replace_type_username);

			wp_mail($backup_generated_data_array["email_send_to"],$replace_subject,$replace_type_backup_destination,$headers);
		}

		/*
		Function Name: email_when_backup_failed
		Parameters: yes($email_backup_data_array,$backup_bank_data)
		Description: This function is used for sending Emails when backup is failed.
		Created On: 07-04-2016 04:15
		Created By: Tech Banker Team
		*/

		function email_when_backup_failed($email_backup_data_array,$backup_bank_data)
		{
			if(file_exists(BACKUP_BANK_DIR_PATH."includes/translations.php"))
			{
				include BACKUP_BANK_DIR_PATH."includes/translations.php";
			}
			$start_time = date("d M Y H:i A");
			global $current_user;

			$headers = "";
			$headers .= "Content-Type: text/html; charset= utf-8". "\r\n";
			if($email_backup_data_array["email_cc"] != "")
			{
				$headers .= "Cc: " .$email_backup_data_array["email_cc"]. "\r\n";
			}
			if($email_backup_data_array["email_bcc"] != "")
			{
				$headers .= "Bcc: " .$email_backup_data_array["email_bcc"]."\r\n";
			}
			switch($backup_bank_data["backup_type"])
			{
				case "only_themes":
					$type = $bb_themes;
					$exclude = $backup_bank_data["exclude_list"] != "" ? $backup_bank_data["exclude_list"] : $bb_na;
					$db_compression = $bb_na;
					$database_table = $bb_na;
					$file_compression = $backup_bank_data["file_compression_type"];
				break;

				case "only_plugins":
					$type = $bb_plugins;
					$exclude = $backup_bank_data["exclude_list"] != "" ? $backup_bank_data["exclude_list"] : $bb_na;
					$db_compression = $bb_na;
					$database_table = $bb_na;
					$file_compression = $backup_bank_data["file_compression_type"];
				break;

				case "only_wp_content_folder":
					$type = $bb_contents;
					$exclude = $backup_bank_data["exclude_list"] != "" ? $backup_bank_data["exclude_list"] : $bb_na;
					$db_compression = $bb_na;
					$database_table = $bb_na;
					$file_compression = $backup_bank_data["file_compression_type"];
				break;

				case "complete_backup":
					$type = $bb_complete_backup;
					$exclude = $backup_bank_data["exclude_list"] != "" ? $backup_bank_data["exclude_list"] : $bb_na;
					$db_compression = $backup_bank_data["db_compression_type"];
					$db_tables =	$backup_bank_data["backup_tables"];

					$data_table_array = explode(",",$db_tables);
					$database_table = "<ul>";
					if(isset($data_table_array) && count($data_table_array) > 0)
					{
						foreach($data_table_array as $row)
						{
							$database_table .= "<li style=\"margin-left: 0px;\">".$row."</li>";
						}
					}
					$database_table .= "</ul>";
					$file_compression = $backup_bank_data["file_compression_type"];
				break;

				case "only_filesystem":
					$type = $bb_filesystem;
					$exclude = $backup_bank_data["exclude_list"] != "" ? $backup_bank_data["exclude_list"] : $bb_na;
					$db_compression = $bb_na;
					$database_table = $bb_na;
					$file_compression = $backup_bank_data["file_compression_type"];
				break;

				case "only_plugins_and_themes":
					$type = $bb_plugins_themes;
					$exclude = $backup_bank_data["exclude_list"] != "" ? $backup_bank_data["exclude_list"] : $bb_na;
					$db_compression = $bb_na;
					$database_table = $bb_na;
					$file_compression = $backup_bank_data["file_compression_type"];
				break;

				case "only_database":
					$type = $bb_database;
					$exclude = $bb_na;
					$db_compression = $backup_bank_data["db_compression_type"];
					$db_tables =	$backup_bank_data["backup_tables"];

					$data_table_array = explode(",",$db_tables);
					$database_table = "<ul>";
					if(isset($data_table_array) && count($data_table_array) > 0)
					{
						foreach($data_table_array as $row)
						{
							$database_table .= "<li style=\"margin-left: 0px;\">".$row."</li>";
						}
					}
					$database_table .= "</ul>";
					$file_compression = $bb_na;
				break;
			}

			switch($backup_bank_data["backup_destination"])
			{
				case "ftp":
					$backup_destination = $bb_ftp;
				break;

				case "email":
					$backup_destination = $bb_email;
				break;

				default:
					$backup_destination = $bb_local_folder;
			}

			$subject = $email_backup_data_array["email_subject"];
			$replace_subject = str_replace("[backup_type]",$type,$subject);
			$message = "<div style=\"font-family: Calibri;\">";
			$message .= $email_backup_data_array["email_message"];
			$message .= "</div>";
			$backup_site_url = str_replace("[site_url]",site_url(),$message);

			$backup_archive_name = str_replace("[archive_name]",$bb_na,$backup_site_url);
			$backup_name = str_replace("[backup_name]",$backup_bank_data["backup_name"],$backup_archive_name);
			$backup_exclude_list = str_replace("[exclude_list]",$exclude,$backup_name);
			$backup_file_compression = str_replace("[file_compression_type]",$file_compression,$backup_exclude_list);
			$backup_db_compression = str_replace("[db_compression_type]",$db_compression,$backup_file_compression);
			$backup_table = str_replace("[backup_tables]",$database_table,$backup_db_compression);
			$backup_location = str_replace("[folder_location]",$backup_bank_data["folder_location"],$backup_table);
			$user = $backup_bank_data["execution"] == "scheduled" ? $bb_scheduler : $current_user->display_name;
			$backup_current_user = str_replace("[username]",$user,$backup_location);
			$start_date_time = str_replace("[start_time]",$start_time,$backup_current_user);
			$destination = str_replace("[backup_destination]",$backup_destination,$start_date_time);
			$replace_backup_type = str_replace("[backup_type]",$type,$destination);

			wp_mail($email_backup_data_array["email_send_to"],$replace_subject,$replace_backup_type,$headers);
		}

		/*
		Function Name: sending_backup_to_email
		Parameters: yes($email_settings_array,$backup_bank_data)
		Description: This function is used for sending backup to Email.
		Created On: 07-04-2016 04:01
		Created By: Tech Banker Team
		*/

		function sending_backup_to_email($email_settings_array,$backup_bank_data)
		{
			if(file_exists(BACKUP_BANK_DIR_PATH."includes/translations.php"))
			{
				include BACKUP_BANK_DIR_PATH."includes/translations.php";
			}
			$start_time = date("d M Y H:i A");
			global $current_user;
			ini_set("memory_limit","-1");
			set_time_limit(0);
			$headers = "";
			$headers .= "Content-Type: text/html; charset= utf-8". "\r\n";
			if($email_settings_array["cc_email"] != "")
			{
				$headers .= "Cc: " .$email_settings_array["cc_email"]. "\r\n";
			}
			if($email_settings_array["bcc_email"] != "")
			{
				$headers .= "Bcc: " .$email_settings_array["bcc_email"]."\r\n";
			}

			switch($backup_bank_data["backup_type"])
			{
				case "only_themes":
					$type = $bb_themes;
					$exclude = $backup_bank_data["exclude_list"] != "" ? $backup_bank_data["exclude_list"] : $bb_na;
					$database_table = $bb_na;
					$compression = $backup_bank_data["file_compression_type"];
				break;

				case "only_plugins":
					$type = $bb_plugins;
					$exclude = $backup_bank_data["exclude_list"] != "" ? $backup_bank_data["exclude_list"] : $bb_na;
					$db_tables = $bb_na;
					$database_table = $bb_na;
					$compression = $backup_bank_data["file_compression_type"];
				break;

				case "only_wp_content_folder":
					$type = $bb_contents;
					$exclude = $backup_bank_data["exclude_list"] != "" ? $backup_bank_data["exclude_list"] : $bb_na;
					$database_table = $bb_na;
					$compression = $backup_bank_data["file_compression_type"];
				break;

				case "complete_backup":
					$type = $bb_complete_backup;
					$exclude = $backup_bank_data["exclude_list"] != "" ? $backup_bank_data["exclude_list"] : $bb_na;
					$db_tables =	$backup_bank_data["backup_tables"];

					$data_table_array = explode(",",$db_tables);
					$database_table = "<ul>";
					if(isset($data_table_array) && count($data_table_array) > 0)
					{
						foreach($data_table_array as $row)
						{
							$database_table .= "<li style=\"margin-left: 0px;\">".$row."</li>";
						}
					}
					$database_table .= "</ul>";
					$compression = $backup_bank_data["file_compression_type"];
				break;

				case "only_filesystem":
					$type = $bb_filesystem;
					$exclude = $backup_bank_data["exclude_list"] != "" ? $backup_bank_data["exclude_list"] : $bb_na;
					$database_table = $bb_na;
					$compression = $backup_bank_data["file_compression_type"];
				break;

				case "only_plugins_and_themes":
					$type = $bb_plugins_themes;
					$exclude = $backup_bank_data["exclude_list"] != "" ? $backup_bank_data["exclude_list"] : $bb_na;
					$database_table = $bb_na;
					$compression = $backup_bank_data["file_compression_type"];
				break;

				case "only_database":
					$type = $bb_database;
					$exclude = $bb_na;
					$db_compression = $backup_bank_data["db_compression_type"];
					$db_tables =	$backup_bank_data["backup_tables"];

					$data_table_array = explode(",",$db_tables);
					$database_table = "<ul>";
					if(isset($data_table_array) && count($data_table_array) > 0)
					{
						foreach($data_table_array as $row)
						{
							$database_table .= "<li style=\"margin-left: 0px;\">".$row."</li>";
						}
					}
					$database_table .= "</ul>";
					$compression = $backup_bank_data["db_compression_type"];
				break;
			}

			$subject = $email_settings_array["email_subject"];
			$replace_subject = str_replace("[backup_type]",$type,$subject);
			$message = "<div style=\"font-family: Calibri;\">";
			$message .= $email_settings_array["email_message"];
			$message .= "</div>";

			if($backup_bank_data["execution"] == "scheduled")
			{
				$archive_array = unserialize($backup_bank_data["archive"]);
				$archive_name = $archive_array[count($archive_array)-2];
				$logfile_array = unserialize($backup_bank_data["log_filename"]);
				$logfile_name = $logfile_array[count($logfile_array)-2];
			}
			else
			{
				$archive_name = implode("",unserialize($backup_bank_data["archive"]));
				$logfile_name = implode("",unserialize($backup_bank_data["log_filename"]));
			}
			$replace_type_message = str_replace("[backup_type]",$type,$message);
			$replace_type_site_url = str_replace("[site_url]",site_url(),$replace_type_message);
			$replace_type_archive = str_replace("[archive_name]","<a href=".$backup_bank_data["backup_urlpath"].$archive_name.">".$archive_name."</a>",$replace_type_site_url);
			$replace_type_backup_name = str_replace("[backup_name]",$backup_bank_data["backup_name"],$replace_type_archive);
			$replace_type_exclude_list = str_replace("[exclude_list]",$exclude,$replace_type_backup_name);
			$replace_type_db_compression = str_replace("[compression_type]",$compression,$replace_type_exclude_list);
			$replace_type_table = str_replace("[backup_tables]",$database_table,$replace_type_db_compression);
			$user = $backup_bank_data["execution"] == "scheduled" ? $bb_scheduler : $current_user->display_name;
			$backup_current_user = str_replace("[username]",$user,$replace_type_table);
			$start_date_time = str_replace("[start_time]",$start_time,$backup_current_user);

			if(filesize(untrailingslashit($backup_bank_data["folder_location"])."/".$archive_name) <= 20971520)
			{
				$attachment = array(untrailingslashit($backup_bank_data["folder_location"])."/".$archive_name,untrailingslashit($backup_bank_data["folder_location"])."/".$logfile_name);
				wp_mail($email_settings_array["email_address"],$replace_subject,$start_date_time,$headers,$attachment);
			}
		}
	}
}
?>
