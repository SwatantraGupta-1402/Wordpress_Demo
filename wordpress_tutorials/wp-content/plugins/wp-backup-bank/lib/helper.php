<?php
/**
* This file is used for creating dbHelper class.
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
		/*
		Class Name: dbHelper_backup_bank
		Parameters: No
		Description: This Class is used for Insert Update and Delete operations.
		Created On: 05-02-2016 11:38
		Created By: Tech Banker Team
		*/

		if(!class_exists("dbHelper_backup_bank"))
		{
			class dbHelper_backup_bank
			{
				/*
				Function Name: insertCommand
				Parameters: Yes($table_name,$data)
				Description: This Function is used for Insert data in database.
				Created On: 05-02-2016 11:38
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
				Created On: 05-02-2016 11:38
				Created By: Tech Banker Team
				*/

				function updateCommand($table_name,$data,$where)
				{
					global $wpdb;
					$wpdb->update($table_name,$data,$where);
				}

				/*
				Function Name: deleteCommand
				Parameters: Yes($table_name,$where)
				Description: This function is used for delete data.
				Created On: 05-02-2016 11:38
				Created By: Tech Banker Team
				*/

				function deleteCommand($table_name,$where)
				{
					global $wpdb;
					$wpdb->delete($table_name,$where);
				}

				/*
				Function Name: bulk_deleteCommand
				Parameters: Yes($table_name,$data,$where)
				Decription: This function is being used  to delete bulk Data.
				Created On: 05-02-2016 11:38
				Created By: Tech Banker Team
				*/

				function bulk_deleteCommand($table_name,$where,$data)
				{
					global $wpdb;
					$wpdb->query
					(
						"DELETE FROM $table_name WHERE $where IN ($data)"
					);
				}
			}
		}

		/*
		Class Name: ftp_connection_backup_bank
		Parameters: No
		Description: This Class is used for FTP Connection.
		Created On: 01-03-2016 12:13
		Created By: Tech Banker Team
		*/

		if(!class_exists("ftp_connection_backup_bank"))
		{
			class ftp_connection_backup_bank
			{
				/*
				Function Name: ftp_connect
				Parameters: Yes
				Description: This Function is used for ftp Connection.
				Created On: 01-03-2016 12:13
				Created By: Tech Banker Team
				*/

				function ftp_connect($ftp_host,$protocol,$port)
				{
					if(file_exists(BACKUP_BANK_DIR_PATH."lib/ftp-client/ftp-client.php"))
					{
						include_once BACKUP_BANK_DIR_PATH."lib/ftp-client/ftp-client.php";
					}
					switch($protocol)
					{
						case "ftp":
							$is_ssl = false;
						break;

						case "ftps":
							$is_ssl = true;
						break;

						case "sfpt_over_ssh":
							$is_ssl = true;
						break;
					}
					$ftp = new FtpClient();
					$result = $ftp->connect($ftp_host,$is_ssl,$port);
					return $result != false ? $ftp : $result;
				}

				/*
				Function Name: login_ftp
				Parameters: Yes
				Description: This Function is used for ftp login.
				Created On: 01-03-2016 12:24
				Created By: Tech Banker Team
				*/

				function login_ftp($ftp,$login_type,$ftp_username = "",$ftp_password = "")
				{
					switch ($login_type)
					{
						case "username_password":
							$result = @$ftp->login($ftp_username, $ftp_password);
						break;

						case "username_only":
							$result = @$ftp->login($ftp_username);
						break;

						case "anonymous":
							$result = @$ftp->login($ftp_username = "anonymous");
						break;

						case "no_login":
							$result = @$ftp->login();
						break;
					}
					return $result;
				}

				/*
				Function Name: ftp_mkdir_recusive
				Parameters: Yes($con_id,$path)
				Description: This Function is used for creating directory.
				Created On: 01-03-2016 12:24
				Created By: Tech Banker Team
				*/

				function ftp_mkdir_recusive($con_id,$path)
				{
					$parts = explode("/",$path);
					$return = true;
					$full_filepath = "";
					if(isset($parts) &&  count($parts) > 0)
					{
						foreach($parts as $part)
						{
							if(empty($part))
							{
								$full_filepath .= "/";
								continue;
							}
							$full_filepath .= $part."/";

							if(@$con_id->chdir($full_filepath))
							{
								$full_filepath = "";
							}
							else
							{
								if(@$con_id->mkdir($part))
								{
									$con_id->chdir($part);
								}
								else
								{
									$return = false;
								}
							}
						}
					}
					return $return;
				}

				/*
				Function Name: custom_ftp_put
				Parameters: Yes($conn,$local_file_path, $remote_file_path,$file_name)
				Description: This Function is used for Uploading files to FTP.
				Created On: 10-06-2016 11:00
				Created By: Tech Banker Team
				*/

				public function custom_ftp_put($conn,$local_file_path, $remote_file_path,$file_name,$backup_bank_data)
				{
					$upload_path = untrailingslashit($backup_bank_data["folder_location"]);
					$archive_name = implode("",unserialize($backup_bank_data["archive_name"]));
					$log_file_path = $upload_path."/".$archive_name.".txt";
					$start_time = microtime(true);
					$file_size = filesize($local_file_path);
					$file_extention = strstr($remote_file_path,".");
					$existing_size = 0;

					$file_size = max($file_size, 1);

					$fh = fopen($local_file_path, "rb");
					if ($existing_size) fseek($fh, $existing_size);

					$ret = $conn->nb_fput($remote_file_path, $fh, FTP_BINARY, $existing_size);

					$backup_status = "completed";
					$cloud = 2;
					while ($ret == FTP_MOREDATA)
					{
						$new_size = ftell($fh);

						if ($new_size - $existing_size > 524288)
						{
							$existing_size = $new_size;
							$percent = ceil($new_size/$file_size*100);
							$rtime = microtime(true)-$start_time;
							if($file_extention != ".txt")
							{
								$log = "Uploading to <b>FTP</b> (<b>".round(($new_size/1048576),1)."MB</b> out of <b>".round(($file_size/1048576),1)."MB</b>).";
								$message = "{"."\r\n";
								$message .= '"log": '.'"'.$log.'"'.','."\r\n";
								$message .= '"perc": '.$percent.','."\r\n";
								$message .= '"status": "'.$backup_status.'"'.','."\r\n";
								$message .= '"cloud": '.$cloud."\r\n";
								$message .= "}";
								@file_put_contents($file_name, $message);
								@file_put_contents($log_file_path,strip_tags(sprintf("%08.03f",round($rtime, 3))." ".$log."\r\n"),FILE_APPEND);


							}
						}
						$ret = $conn->nb_continue();
					}

					fclose($fh);

					return true;
				}
			}
		}

		/**
		* @author		 DavidAnderson <https://updraftplus.com>
		* @copyright 2011-16 David Anderson, 2010 Paul Kehrer
		* @license	 https://opensource.org/licenses/gpl-license
		* @link			 https://updraftplus.com
		* @since		 available since Release 2.1.0
		*/

		if(!class_exists("Backup_bank_PclZip")) require_once(BACKUP_BANK_DIR_PATH."lib/class-zip.php");
		if(file_exists(BACKUP_BANK_DIR_PATH."lib/pear-archive-tar/tar.php")) require_once(BACKUP_BANK_DIR_PATH."lib/pear-archive-tar/tar.php");
		if(!class_exists("backup_data_backup_bank"))
		{
			class backup_data_backup_bank
			{
				public $logfile_handle = false;
				public $zipfiles_dirbatched;
				private $use_zip_object = "Backup_bank_ZipArchive";
				public $upload_path;
				public $archive_name;
				public $upload_dir_realpath;
				public $backup_bank_data;
				public $backup_type;
				public $file_compression_type;
				public $backup_file_path;
				public $exclude_list;
				public $error = array();
				public $logfile_name;
				public $count_zipfiles_batched;
				public $how_many_tables;
				public $total_tables;
				public $zipfiles_added;
				public $status;
				public $backup_completed;
				public $db_compression_type;
				public $backup_destination;
				public $backup_file;
				public $database_file_name;
				public $kbsize;
				public $timetaken;
				public $zip_microtime_start;
				public $json_file_name;
				public $files_size_added;
				public $total_files_size;
				public $cloud;
				public $log_timetaken;

				public function __construct($backup_bank_data_array = "")
				{
					if($backup_bank_data_array != "")
					{
						!is_dir($backup_bank_data_array["folder_location"]) ? wp_mkdir_p($backup_bank_data_array["folder_location"]) : "";
						$this->upload_path = untrailingslashit($backup_bank_data_array["folder_location"]);
						$this->archive_name = implode("",unserialize($backup_bank_data_array["archive_name"]));
						$this->backup_completed = "";
						$this->json_file_name = $this->upload_path."/".$this->archive_name.".json";
						$this->open_logfile_backup_bank($this->upload_path."/".$this->archive_name.".txt");
						$this->backup_bank_data = $backup_bank_data_array;
						$this->backup_type = $backup_bank_data_array["backup_type"];
						$this->file_compression_type = $backup_bank_data_array["file_compression_type"];
						$this->db_compression_type = $backup_bank_data_array["db_compression_type"];
						$this->exclude_list = explode(",",str_replace(" ","",$backup_bank_data_array["exclude_list"]));
						$this->backup_destination = $backup_bank_data_array["backup_destination"];
						$this->upload_dir_realpath = realpath(BACKUP_BANK_BACKUPS_DIR);
						$this->cloud = 1;
						if(((($this->db_compression_type ==".sql.zip" || $this->file_compression_type == ".zip") && $this->backup_type == "complete_backup") || ($this->backup_type != "complete_backup" && $this->backup_type != "only_database" && $this->file_compression_type ==".zip") ||
						($this->backup_type == "only_database" && $this->db_compression_type == ".sql.zip")))
						{
							if(!class_exists("ZipArchive") || !class_exists("Backup_bank_ZipArchive") || (!extension_loaded("zip") && !method_exists("ZipArchive", "AddFile")))
							{
								$this->backup_bank_log("Zip Engine: ZipArchive is not Available or is Disabled (Use PclZip if needed).\r\n");
								$this->use_zip_object = "Backup_bank_PclZip";
							}
						}
					}
				}

				/**
				* @author		 DavidAnderson <https://updraftplus.com>
				* @copyright 2011-16 David Anderson, 2010 Paul Kehrer
				* @license	 https://opensource.org/licenses/gpl-license
				* @link			 https://updraftplus.com
				* @since		 available since Release 2.1.0
				*/

				public function close_browser_connection()
				{
					// Close browser connection so that it can resume AJAX polling
					header('Content-Length: 0');
					header('Connection: close');
					header('Content-Encoding: none');
					if (session_id()) session_write_close();
					echo "\r\n\r\n";
					if (ob_get_level()) ob_end_flush();
					flush();
				}

				/**
				* @author		 DavidAnderson <https://updraftplus.com>
				* @copyright 2011-16 David Anderson, 2010 Paul Kehrer
				* @license	 https://opensource.org/licenses/gpl-license
				* @link			 https://updraftplus.com
				* @since		 available since Release 2.1.0
				*/

				public function table_prefix_backup_bank()
				{
					global $wpdb;
					if (is_multisite() && !defined("MULTISITE"))
					{
						$prefix = $wpdb->base_prefix;
					}
					else
					{
						$prefix = $wpdb->prefix;
					}
					return $prefix;
				}

				/**
				* @author		 DavidAnderson <https://updraftplus.com>
				* @copyright 2011-16 David Anderson, 2010 Paul Kehrer
				* @license	 https://opensource.org/licenses/gpl-license
				* @link			 https://updraftplus.com
				* @since		 available since Release 2.1.0
				*/

				public function error_count_backup_bank($level = "error")
				{
					$count = 0;
					if(isset($this->errors) &&  count($this->errors) > 0)
					{
						foreach($this->errors as $err)
						{
							if(("error" == $level && (is_string($err) || is_wp_error($err))) || (is_array($err) && $level == $err["level"]))
							{
								$count++;
							}
						}
					}
					return $count;
				}

				/**
				* @author		 DavidAnderson <https://updraftplus.com>
				* @copyright 2011-16 David Anderson, 2010 Paul Kehrer
				* @license	 https://opensource.org/licenses/gpl-license
				* @link			 https://updraftplus.com
				* @since		 available since Release 2.1.0
				*/

				public function backup_bank_log($line, $level = "notice", $uniq_id = false)
				{
					if("error" == $level || "warning" == $level)
					{
						if("error" == $level && 0 == $this->error_count_backup_bank())
						$this->backup_bank_log("An error condition has been occurred for the first time during this job.\r\n");
						if($uniq_id)
						{
							$this->errors[$uniq_id] = array("level" => $level, "message" => $line);
						}
						else
						{
							$this->errors[] = array("level" => $level, "message" => $line);
						}
						if("error" == $level) 2;
					}
					if($this->logfile_handle)
					{
						$rtime = microtime(true)-$this->opened_log_time;
						fwrite($this->logfile_handle, sprintf("%08.03f", round($rtime, 3))." ".(("notice" != $level) ? "[".ucfirst($level)."] " : "").strip_tags($line));
					}

					switch($this->backup_type)
					{
						case "complete_backup":
							$database_tables_count = $this->how_many_tables == "" ? 24 : $this->how_many_tables;
							$count_table = $this->total_tables == "" ? 1 : $this->total_tables;
							$result = ceil($count_table/$database_tables_count*24);
							if($this->file_compression_type == ".zip")
							{
								$zipfiles_batched_count = $this->total_files_size == "" ? 74 : $this->total_files_size;
								$count_zipfiles_added = $this->files_size_added == "" ? 1 : $this->files_size_added;
								if($this->backup_completed == "")
								{
									$total_result = ceil($count_zipfiles_added/$zipfiles_batched_count*74);
									$result += $total_result;
								}
								else
								{
									$result = $this->backup_completed;
								}
							}
						break;

						case "only_database":
							$databse_tables_count = $this->how_many_tables == "" ? 98 : $this->how_many_tables;
							$count_tables = $this->total_tables == "" ? 1 : $this->total_tables;
							if($this->backup_completed == "")
							{
								$result = ceil($count_tables/$databse_tables_count*98);
							}
							else
							{
								$result = $this->backup_completed;
							}
						break;

						default:
							$zipfiles_batched_count = $this->total_files_size == "" ? 98 : $this->total_files_size;
							$count_zipfiles_added = $this->files_size_added == "" ? 1 : $this->files_size_added;
							if($this->backup_completed == "")
							{
								$result = ceil($count_zipfiles_added/$zipfiles_batched_count*98);
							}
							else
							{
								$result = $this->backup_completed;
							}
					}
					if($line != "")
					{
						if($this->cloud != 1)
						{
							$result = 1;
						}
						$new_line = str_replace("\r\n","",$line);

						@file_put_contents($this->json_file_name, "");
						$message = "{"."\r\n";
						$message .= '"log": '.'"'.$new_line.'"'.','."\r\n";
						$message .= '"perc": '.$result.','."\r\n";
						$message .= '"status": "'.$this->status.'"'.','."\r\n";
						$message .= '"cloud": '.$this->cloud."\r\n";
						$message .= "}";

						@file_put_contents($this->json_file_name, $message);
					}
				}

				public function backup_destination_backup_bank($file)
				{
					switch($this->backup_destination)
					{
						case "email":
							$backup_dest = "Email";
						break;

						case "ftp":
							$backup_dest = "Ftp";
							$this->cloud = 2;
						break;

					}
					$this->backup_bank_log("Starting Sending <b>$file Backup</b> to <b>$backup_dest</b>.\r\n");
				}

				/**
				* @author		 DavidAnderson <https://updraftplus.com>
				* @copyright 2011-16 David Anderson, 2010 Paul Kehrer
				* @license	 https://opensource.org/licenses/gpl-license
				* @link			 https://updraftplus.com
				* @since		 available since Release 2.1.0
				*/

				public function is_writable_backup_bank($dir)
				{
					if(!@is_writable($dir)) return false;
					$rand_file = "$dir/test-".md5(rand().time()).".txt";
					$ret = @file_put_contents($rand_file, "testing...");
					@unlink($rand_file);
					return ($ret > 0);
				}

				/**
				* @author		 DavidAnderson <https://updraftplus.com>
				* @copyright 2011-16 David Anderson, 2010 Paul Kehrer
				* @license	 https://opensource.org/licenses/gpl-license
				* @link			 https://updraftplus.com
				* @since		 available since Release 2.1.0
				*/

				public function open_database_file_backup_bank($file)
				{
					$this->dbhandle = @fopen($file, "w");

					if(false === $this->dbhandle)
					{
						$this->backup_bank_log("ERROR: Backup File <b>$file</b> couldn't be open for writing.\r\n");
					}
					return $this->dbhandle;
				}

				/**
				* @author		 DavidAnderson <https://updraftplus.com>
				* @copyright 2011-16 David Anderson, 2010 Paul Kehrer
				* @license	 https://opensource.org/licenses/gpl-license
				* @link			 https://updraftplus.com
				* @since		 available since Release 2.1.0
				*/

				private function database_sorttables_backup_bank($a_arr, $b_arr)
				{
					$a = $a_arr["name"];
					$a_table_type = $a_arr["type"];
					$b = $b_arr["name"];
					$b_table_type = $b_arr["type"];

					if ("VIEW" == $a_table_type && "VIEW" != $b_table_type) return 1;
					if ("VIEW" == $b_table_type && "VIEW" != $a_table_type) return -1;

					if ($a == $b) return 0;
					$our_table_prefix = $this->table_prefix;
					if ($a == $our_table_prefix."options") return -1;
					if ($b ==  $our_table_prefix."options") return 1;
					if ($a == $our_table_prefix."site") return -1;
					if ($b ==  $our_table_prefix."site") return 1;
					if ($a == $our_table_prefix."blogs") return -1;
					if ($b ==  $our_table_prefix."blogs") return 1;
					if ($a == $our_table_prefix."users") return -1;
					if ($b ==  $our_table_prefix."users") return 1;
					if ($a == $our_table_prefix."usermeta") return -1;
					if ($b ==  $our_table_prefix."usermeta") return 1;

					if (empty($our_table_prefix)) return strcmp($a, $b);

					try
					{
						$core_tables = array_merge($this->wpdb_obj->tables, $this->wpdb_obj->global_tables, $this->wpdb_obj->ms_global_tables);
					}
					catch (Exception $e)
					{
						$this->backup_bank_log($e->getMessage()."\r\n");
					}

					if (empty($core_tables)) $core_tables = array("terms", "term_taxonomy", "termmeta", "term_relationships", "commentmeta", "comments", "links", "postmeta", "posts", "site", "sitemeta", "blogs", "blogversions");

					$na = $this->str_replace_once_backup_bank($our_table_prefix, "", $a);
					$nb = $this->str_replace_once_backup_bank($our_table_prefix, "", $b);
					if (in_array($na, $core_tables) && !in_array($nb, $core_tables)) return -1;
					if (!in_array($na, $core_tables) && in_array($nb, $core_tables)) return 1;
					return strcmp($a, $b);
				}

				/**
				* @author		 DavidAnderson <https://updraftplus.com>
				* @copyright 2011-16 David Anderson, 2010 Paul Kehrer
				* @license	 https://opensource.org/licenses/gpl-license
				* @link			 https://updraftplus.com
				* @since		 available since Release 2.1.0
				*/

				private function database_backup_header_backup_bank()
				{
					global $wp_version;

					$mysql_version = $this->wpdb_obj->db_version();

					$wp_upload_dir = wp_upload_dir();
					$this->backup_bank_store("# --------------------------------------------------------\n");
					$this->backup_bank_store("# Database Backup\n");
					$this->backup_bank_store("# Plugin: Backup Bank Created by Tech Banker\n");
					$this->backup_bank_store("# WordPress Version: $wp_version, running on PHP Version".phpversion()." (".$_SERVER["SERVER_SOFTWARE"]."), MySQL Version $mysql_version\n");
					$this->backup_bank_store("# Backup of: ".untrailingslashit(site_url())."\n");
					$this->backup_bank_store("# Home URL: ".untrailingslashit(home_url())."\n");
					$this->backup_bank_store("# Content URL: ".untrailingslashit(content_url())."\n");
					$this->backup_bank_store("# Uploads URL: ".untrailingslashit($wp_upload_dir["baseurl"])."\n");
					$this->backup_bank_store("# Table prefix: ".$this->table_prefix."\n");
					$this->backup_bank_store("# Site info: multisite = ".(is_multisite() ? "1" : "0")."\n\n");

					$this->backup_bank_store("# Generated On: ".date("l j,F Y H:i T")."\n");
					$this->backup_bank_store("# Hostname: ".$this->dbinfo["host"]."\n");
					$this->backup_bank_store("# Database Name: ".$this->backup_bank_backquote($this->dbinfo["name"])."\n");
					$this->backup_bank_store("# --------------------------------------------------------\n");

					if(defined("DB_CHARSET"))
					{
						$this->backup_bank_store("/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\n");
						$this->backup_bank_store("/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\n");
						$this->backup_bank_store("/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\n");
						$this->backup_bank_store("/*!40101 SET NAMES " . DB_CHARSET . " */;\n");
					}
					$this->backup_bank_store("/*!40101 SET foreign_key_checks = 0 */;\n\n");
				}

				/**
				* @author		 DavidAnderson <https://updraftplus.com>
				* @copyright 2011-16 David Anderson, 2010 Paul Kehrer
				* @license	 https://opensource.org/licenses/gpl-license
				* @link			 https://updraftplus.com
				* @since		 available since Release 2.1.0
				*/

				public function backup_bank_store($query_line)
				{
					if(false == ($ret = @fwrite($this->dbhandle, $query_line)))
					{
						$this->backup_bank_log("Error occurred while writing a line to Backup.\r\n");
					}
					return $ret;
				}

				/**
				* @author		 DavidAnderson <https://updraftplus.com>
				* @copyright 2011-16 David Anderson, 2010 Paul Kehrer
				* @license	 https://opensource.org/licenses/gpl-license
				* @link			 https://updraftplus.com
				* @since		 available since Release 2.1.0
				*/

				public function close()
				{
					return fclose($this->dbhandle);
				}

				/**
				* @author		 DavidAnderson <https://updraftplus.com>
				* @copyright 2011-16 David Anderson, 2010 Paul Kehrer
				* @license	 https://opensource.org/licenses/gpl-license
				* @link			 https://updraftplus.com
				* @since		 available since Release 2.1.0
				*/

				public function backup_bank_backquote($a_name)
				{
					if(!empty($a_name) && $a_name != "*")
					{
						return "`".$a_name."`";
					}
					else
					{
						return $a_name;
					}
				}

				/**
				* @author		 DavidAnderson <https://updraftplus.com>
				* @copyright 2011-16 David Anderson, 2010 Paul Kehrer
				* @license	 https://opensource.org/licenses/gpl-license
				* @link			 https://updraftplus.com
				* @since		 available since Release 2.1.0
				*/

				public function str_replace_once_backup_bank($needle, $replace, $haystack)
				{
					$pos = strpos($haystack, $needle);
					return ($pos !== false) ? substr_replace($haystack,$replace,$pos,strlen($needle)) : $haystack;
				}

				/**
				* @author		 DavidAnderson <https://updraftplus.com>
				* @copyright 2011-16 David Anderson, 2010 Paul Kehrer
				* @license	 https://opensource.org/licenses/gpl-license
				* @link			 https://updraftplus.com
				* @since		 available since Release 2.1.0
				*/

				public function str_lreplace_backup_bank($search, $replace, $subject)
				{
					$pos = strrpos($subject, $search);
					if($pos !== false) $subject = substr_replace($subject, $replace, $pos, strlen($search));
					return $subject;
				}

				/**
				* @author		 DavidAnderson <https://updraftplus.com>
				* @copyright 2011-16 David Anderson, 2010 Paul Kehrer
				* @license	 https://opensource.org/licenses/gpl-license
				* @link			 https://updraftplus.com
				* @since		 available since Release 2.1.0
				*/

				private function table_backup_bank($table, $where = "", $table_type = "BASE TABLE")
				{
					$microtime = microtime(true);

					$dump_as_table = ($this->duplicate_tables_exist == false && stripos($table, $this->table_prefix) === 0 && strpos($table, $this->table_prefix) !== 0) ? $this->table_prefix.substr($table, strlen($this->table_prefix)) : $table;

					$table_structure = $this->wpdb_obj->get_results("DESCRIBE ".$this->backup_bank_backquote($table));
					if(!$table_structure)
					{
						$this->backup_bank_log("Error occurred while getting details of Tables.\r\n");
						return false;
					}

					$this->backup_bank_store("DROP TABLE IF EXISTS ".$this->backup_bank_backquote($dump_as_table).";\n");

					if("VIEW" == $table_type)
					{
						$this->backup_bank_store("DROP VIEW IF EXISTS ".$this->backup_bank_backquote($dump_as_table).";\n");
					}

					$description = ("VIEW" == $table_type) ? "view" : "table";

					$this->backup_bank_store("\n# Table structure for ".$this->backup_bank_backquote($table)."\n\n");

					$create_table = $this->wpdb_obj->get_results("SHOW CREATE TABLE ".$this->backup_bank_backquote($table), ARRAY_N);
					if(false === $create_table)
					{
						$err_msg ="SHOW CREATE TABLE for ".$table." not Supported.";
						$this->backup_bank_log($err_msg."\r\n", "error");
						$this->backup_bank_store("#\n# $err_msg\n#\n");
					}
					$create_line = $this->str_lreplace_backup_bank("TYPE=", "ENGINE=", $create_table[0][1]);

					if(preg_match("/ENGINE=([^\s;]+)/", $create_line, $eng_match))
					{
						$engine = $eng_match[1];
						if("myisam" == strtolower($engine))
						{
							$create_line = preg_replace("/PAGE_CHECKSUM=\d\s?/", "", $create_line, 1);
						}
					}

					if($dump_as_table !== $table)
					$create_line = $this->str_replace_once_backup_bank($table, $dump_as_table, $create_line);

					$this->backup_bank_store($create_line." ;");

					if(false === $table_structure)
					{
						$err_msg = "Error while getting $description structure for ".$table;
						$this->backup_bank_store("#\n# $err_msg\n#\n");
					}

					$this->backup_bank_store("\n\n# Backup Data for $description ".$this->backup_bank_backquote($table). "\n");

					if("VIEW" != $table_type)
					{
						$defs = array();
						$integer_fields = array();
						if(isset($table_structure) &&  count($table_structure) > 0)
						{
							foreach($table_structure as $struct)
							{
								if((0 === strpos($struct->Type, "tinyint")) || (0 === strpos(strtolower($struct->Type), "smallint")) ||
								(0 === strpos(strtolower($struct->Type), "mediumint")) || (0 === strpos(strtolower($struct->Type), "int")) || (0 === strpos(strtolower($struct->Type), "bigint")) )
								{
									$defs[strtolower($struct->Field)] = ( null === $struct->Default ) ? "NULL" : $struct->Default;
									$integer_fields[strtolower($struct->Field)] = "1";
								}
							}
						}
						$increment = 500;
						$row_start = 0;
						$row_inc = $increment;

						$search = array("\x00", "\x0A", "\x0D", "\x1a");
						$replace = array('\0', '\n', '\r', '\Z');

						if($where) $where = "WHERE $where";

						$lock_table = "LOCK TABLES " . $this->backup_bank_backquote($dump_as_table) . " WRITE;";
						$this->backup_bank_store($lock_table);
						do
						{
							@set_time_limit(BACKUP_BANK_SET_TIME_LIMIT);

							$table_data = $this->wpdb_obj->get_results("SELECT * FROM $table $where LIMIT {$row_start}, {$row_inc}", ARRAY_A);
							$entries = "INSERT INTO " . $this->backup_bank_backquote($dump_as_table) . " VALUES ";
							if(isset($table_data) &&  count($table_data) > 0)
							{
								$thisentry = "";
								foreach($table_data as $row)
								{
									$values = array();
									foreach($row as $key => $value)
									{
										if(isset($integer_fields[strtolower($key)]))
										{
											$value = ( null === $value || "" === $value) ? $defs[strtolower($key)] : $value;
											$values[] = ( "" === $value ) ? "''" : $value;
										}
										else
										{
											$values[] = (null === $value) ? "NULL" : "'" . str_replace($search, $replace, str_replace('\'', '\\\'', str_replace('\\', '\\\\', $value))) . "'";
										}
									}
									if($thisentry) $thisentry .= ",\n ";
									$thisentry .= "(".implode(", ", $values).")";
									if(strlen($thisentry) > 524288)
									{
										$this->backup_bank_store(" \n".$entries.$thisentry.";");
										$thisentry = "";
									}
								}
								if($thisentry) $this->backup_bank_store(" \n".$entries.$thisentry.";");
								$row_start += $row_inc;
							}
						}
						while(count($table_data) > 0);
					}

					$this->backup_bank_store("\n");
					$unlock_table = "UNLOCK TABLES; ";
					$this->backup_bank_store($unlock_table."\n");
					$this->backup_bank_store("# End of Backup Data for Table ".$this->backup_bank_backquote($table) . "\n\n");

					$table_file_prefix = $this->archive_name."-table-".$table.".table";
					$table_name = "<b>".$table."</b>";
					$table_size = "<b>".round(filesize($this->upload_path."/".$table_file_prefix.".tmp.sql")/1024,1)."</b>";
					$this->backup_bank_log("Completed Compressing Table $table_name in (<b>".sprintf("%.02f",max(microtime(true)-$this->zip_microtime_start,0.00001))." seconds</b>) with size (<b>$table_size kb</b>).\r\n");
				}

				/**
				* @author		 DavidAnderson <https://updraftplus.com>
				* @copyright 2011-16 David Anderson, 2010 Paul Kehrer
				* @license	 https://opensource.org/licenses/gpl-license
				* @link			 https://updraftplus.com
				* @since		 available since Release 2.1.0
				*/

				public function detect_safe_mode_backup_bank()
				{
					return (@ini_get("safe_mode") && strtolower(@ini_get("safe_mode")) != "off") ? 1 : 0;
				}

				/**
				* @author		 DavidAnderson <https://updraftplus.com>
				* @copyright 2011-16 David Anderson, 2010 Paul Kehrer
				* @license	 https://opensource.org/licenses/gpl-license
				* @link			 https://updraftplus.com
				* @since		 available since Release 2.1.0
				*/

				public function open_logfile_backup_bank($logfile_name)
				{
					$this->logfile_name =  $logfile_name;
					$this->logfile_handle = fopen($this->logfile_name, "a");

					$this->opened_log_time = microtime(true);
					$this->backup_bank_log("Log file opened on ".date("r")." on ".network_site_url()."\r\n");
					global $wpdb,$wp_version;
					$this->zip_microtime_start = microtime(true);

					@ini_set("memory_limit", apply_filters("admin_memory_limit", WP_MAX_MEMORY_LIMIT));
					$mysql_version = $wpdb->db_version();
					@ini_set("error_log", $this->logfile_name);
					$safe_mode = $this->detect_safe_mode_backup_bank();
					@ini_set("log_errors", "1");

					$memory_limit = ini_get("memory_limit");
					$memory_usage = round(@memory_get_usage(false)/1048576, 1);
					$memory_usage2 = round(@memory_get_usage(true)/1048576, 1);

					@set_time_limit(BACKUP_BANK_SET_TIME_LIMIT);
					@ignore_user_abort(true);
					$max_execution_time = (int)@ini_get("max_execution_time");

					$logline = "Backup Bank WordPress Backup plugin : WP: ".$wp_version." PHP: ".phpversion()." (".@php_uname().") MySQL: $mysql_version Server: ".$_SERVER["SERVER_SOFTWARE"]." safe_mode: $safe_mode max_execution_time: $max_execution_time memory_limit: $memory_limit (used: ${memory_usage}M | ${memory_usage2}M) multisite: ".((is_multisite()) ? "Y" : "N");

					$this->backup_bank_log($logline."\r\n");

					$disk_free_space = @disk_free_space($this->upload_path);
					if($disk_free_space == false)
					{
						$this->backup_bank_log("Unknown Free space in your disk containing Backup Bank Directory.\r\n");
					}
					else
					{
						$this->backup_bank_log("Only <b>".round($disk_free_space/1048576,1)." Mb</b> space left in your Disk.\r\n");
						$disk_free_mb = round($disk_free_space/1048576, 1);
						if($disk_free_space < 50*1048576)
						$this->backup_bank_log(sprintf("Only <b>%s Mb</b> space left in your Disk.\r\n", round($disk_free_space/1048576, 1)), "warning", "lowdiskspace".$disk_free_mb);
					}
				}

				/**
				* @author		 DavidAnderson <https://updraftplus.com>
				* @copyright 2011-16 David Anderson, 2010 Paul Kehrer
				* @license	 https://opensource.org/licenses/gpl-license
				* @link			 https://updraftplus.com
				* @since		 available since Release 2.1.0
				*/

				public function compress_database_file_backup_bank($file,$type)
				{
					$this->backup_bank_log("Compressing Database file.\r\n");
					switch($type)
					{
						case ".sql.zip":
							$compress_file_name = str_replace(".sql",".sql.zip",$file);
							$file_name = basename($file);
							$zip = new $this->use_zip_object;
							$create_file = (version_compare(PHP_VERSION, "5.2.12", ">") && defined("ZIPARCHIVE::CREATE")) ? ZIPARCHIVE::CREATE : 1;
							$zip->open($compress_file_name,$create_file);
							$zip->addFile($file, $file_name);
							$zip->close();
							@unlink($file);
						break;

						case ".sql":
							$compress_file_name = $file;
						break;

						case ".sql.gz":
							$compress_file_name = str_replace(".sql",".sql.gz",$file);
							$zip = new Archive_Tar($compress_file_name,".sql.gz",$this->backup_type,$this->database_file_name,$this->backup_destination,$this->backup_file,"manual");
							$file_name = basename($file);
							$zip->addModify($file,$file_name);
							@unlink($file);
						break;

						case ".sql.bz2":
							$compress_file_name = str_replace(".sql",".sql.bz2",$file);
							$zip = new Archive_Tar($compress_file_name,".sql.bz2",$this->backup_type,$this->database_file_name,$this->backup_destination,$this->backup_file,"manual");
							$file_name = basename($file);
							$zip->addModify($file,$file_name);
							@unlink($file);
						break;
					}
					return $compress_file_name;
				}

				/**
				* @author		 DavidAnderson <https://updraftplus.com>
				* @copyright 2011-16 David Anderson, 2010 Paul Kehrer
				* @license	 https://opensource.org/licenses/gpl-license
				* @link			 https://updraftplus.com
				* @since		 available since Release 2.1.0
				*/

				public function database_backup_bank($dbinfo = array())
				{
					global $wpdb;
					$check_file = $this->upload_path."/".$this->archive_name.$this->db_compression_type;
					if(file_exists($check_file))
					{
						$this->status = "file_exists";
						$this->backup_bank_log("File <b>".basename($check_file). "</b> already Exists.\r\n");
						return $this->status;
					}
					$this->wpdb_obj = $wpdb;
					$this->table_prefix = $this->table_prefix_backup_bank();
					$dbinfo["host"] = DB_HOST;
					$dbinfo["name"] = DB_NAME;
					$dbinfo["user"] = DB_USER;
					$dbinfo["pass"] = DB_PASSWORD;
					$this->dbinfo = $dbinfo;
					$errors = 0;

					$this->total_tables = 0;
					$all_tables = $wpdb->get_results("SHOW FULL TABLES", ARRAY_N);
					if(empty($all_tables) && !empty($this->wpdb_obj->last_error))
					{
						$all_tables = $this->wpdb_obj->get_results("SHOW TABLES", ARRAY_N);
						$all_tables = array_map(create_function('$a', 'return array("name" => $a[0], "type" => "BASE TABLE");'), $all_tables);
					}
					else
					{
						$all_tables = array_map(create_function('$a', 'return array("name" => $a[0], "type" => $a[1]);'), $all_tables);
					}
					if(0 == count($all_tables))
					{
						$this->status = "terminated";
						$this->backup_bank_log("Error: Database Tables not found.\r\n");
						return $this->status;
					}

					$this->backup_bank_log("Starting Sorting Tables."."\r\n");
					usort($all_tables, array($this, "database_sorttables_backup_bank"));

					$all_table_names = array_map(create_function('$a', 'return $a["name"];'), $all_tables);

					if(!$this->is_writable_backup_bank($this->upload_path))
					{
						$this->backup_bank_log("Your Database Backup failed as Directory <b>".$this->upload_path."</b> is not writable.\r\n");
					}

					$this->duplicate_tables_exist = false;
					if(isset($all_table_names) &&  count($all_table_names) > 0)
					{
						foreach($all_table_names as $table)
						{
							if(strtolower($table) != $table && in_array(strtolower($table), $all_table_names))
							{
								$this->duplicate_tables_exist = true;
								$this->backup_bank_log("Table names differs only based on case-sensitivity $table / ".strtolower($table)."\r\n");
							}
						}
					}
					$this->how_many_tables = count(explode(",",$this->backup_bank_data["backup_tables"]));

					$stitch_files = array();
					if(isset($all_tables) &&  count($all_tables) > 0)
					{
						foreach($all_tables as $ti)
						{
							$table = $ti["name"];
							if(in_array($table,explode(",",$this->backup_bank_data["backup_tables"])))
							{
								$table_type = $ti["type"];
								$this->total_tables++;

								@set_time_limit(BACKUP_BANK_SET_TIME_LIMIT);
								$table_file_prefix = $this->archive_name."-table-".$table.".table";

								if(file_exists($this->upload_path."/".$table_file_prefix.".sql"))
								{
									$this->backup_bank_log("$table File already Exists.\r\n");
									$stitch_files[] = $table_file_prefix;
								}
								else
								{
									$opened = $this->open_database_file_backup_bank($this->upload_path."/".$table_file_prefix.".tmp.sql");
									if(false === $opened)
									{
										$this->status = "terminated";
										$this->backup_bank_log("File <b>".$this->upload_path."/".$table_file_prefix.".tmp.sql</b> has been Failed to open.\r\n");
										return $this->status;
									}

									$this->backup_bank_store("# Table Name: ".$this->backup_bank_backquote($table). "\n");

									$table_status = $this->wpdb_obj->get_row("SHOW TABLE STATUS WHERE Name='$table'");
									$tablename = "<b>".$table."</b>";

									if(isset($table_status->Rows))
									{
										$this->backup_bank_log("Table found $tablename.\r\n");
										$this->backup_bank_log("Starting Compressing Table $tablename.\r\n");
										$this->backup_bank_log("Total Rows found <b>$table_status->Rows </b>in $tablename.\r\n");
										$this->backup_bank_store("# Total Rows found $table_status->Rows in $table \n");
										if($table_status->Rows > BACKUP_BANK_WARN_DB_ROWS)
										{
											$this->backup_bank_log("Rows in Table $tablename has been increased to its unexpected size.\r\n", "warning", "manyrows_".$table);
										}
									}

									$this->table_backup_bank($table, $where = "", $table_type);
									$this->close();

									rename($this->upload_path."/".$table_file_prefix.".tmp.sql", $this->upload_path."/".$table_file_prefix.".sql");
									$stitch_files[] = $table_file_prefix;
								}
							}
						}
					}
					$time_this_run = time()-$this->opened_log_time;
					if($time_this_run > 2000)
					{
						$this->status = "terminated";
						$this->backup_bank_log("Process had been running for a very long time that leads to failure of Backup.\r\n");
						return $this->status;
					}

					$backup_final_file_name = $this->upload_path."/".$this->archive_name.".sql";

					if(false === $this->open_database_file_backup_bank($backup_final_file_name))
					return false;

					$this->database_backup_header_backup_bank();

					$unlink_files = array();

					$sind = 1;
					if(isset($stitch_files) &&  count($stitch_files) > 0)
					{
						foreach($stitch_files as $table_file)
						{
							$this->backup_bank_log("<b>{$table_file}.sql ($sind/$this->how_many_tables) :</b> Added to Final Database.\r\n");
							if(!$handle = fopen($this->upload_path."/".$table_file.".sql", "r"))
							{
								$this->backup_bank_log("Database File <b>{$table_file}.sql</b> failed to open.\r\n");
								$errors++;
							}
							else
							{
								while($line = fgets($handle, 2048))
								{
									$this->backup_bank_store($line);
								}
								fclose($handle);
								$unlink_files[] = $this->upload_path."/".$table_file.".sql";
							}
							$sind++;
						}
					}
					if(defined("DB_CHARSET"))
					{
						$this->backup_bank_store("/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\n/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\n/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;\n");
					}

					$this->backup_bank_log("Finishing file writing of <b>".$this->archive_name.".sql <b>file (Size:<b>".round(filesize($backup_final_file_name)/1048576,1)." Mb</b>).\r\n");
					if(!$this->close())
					{
						$this->backup_bank_log("Error occurred while closing Database file.\r\n");
						$errors++;
					}

					if(isset($unlink_files) &&  count($unlink_files) > 0)
					{
						foreach($unlink_files as $unlink_file) @unlink($unlink_file);
					}

					if($errors > 0)
					{
						$this->status = "terminated";
						$this->backup_bank_log("Database backup has been Failed.\r\n");
						return $this->status;
					}
					else
					{
						$this->backup_completed = $this->backup_type == "complete_backup" ? "" : 100;
						$backup_file = $this->compress_database_file_backup_bank($backup_final_file_name,$this->backup_bank_data["db_compression_type"]);
						$this->database_filesize = filesize($backup_file);
						$this->backup_bank_log("<b>$this->total_tables</b> Tables are Successfully Backed up.\r\n");
						if($this->backup_type == "only_database")
						{
							$this->timetaken = max(microtime(true)-$this->zip_microtime_start, 0.000001);
							$this->log_timetaken = microtime(true)-$this->zip_microtime_start;
							$this->kbsize = round(filesize($backup_file)/1048576,1);
							if($this->backup_destination == "local_folder")
							{
								$this->status = "completed_successfully";
							}
						}
						$this->backup_bank_log("Completed Backup for Database.\r\n");
						if($this->backup_type != "complete_backup" && $this->backup_destination != "local_folder")
						{
							$this->status = "completed";
							$file = "Database";
							$this->backup_destination_backup_bank($file);
						}

						return $this->backup_type == "complete_backup" ? $backup_file : $this->status;
					}
				}

				/**
				* @author		 DavidAnderson <https://updraftplus.com>
				* @copyright 2011-16 David Anderson, 2010 Paul Kehrer
				* @license	 https://opensource.org/licenses/gpl-license
				* @link			 https://updraftplus.com
				* @since		 available since Release 2.1.0
				*/

				/* System Files Backup */
				public function get_backup_type_files_backup_bank($backup_bank_backup_type)
				{
					switch($backup_bank_backup_type)
					{
						case "only_themes":
							$backup_type_array = array("Themes" => WP_CONTENT_DIR."/themes");
						break;

						case "only_plugins":
							$backup_type_array = array("Plugins" => untrailingslashit(WP_PLUGIN_DIR));
						break;

						case "only_wp_content_folder":
							$backup_type_array = array("Contents" => untrailingslashit(BACKUP_BANK_CONTENT_DIR));
						break;

						case "complete_backup":
							$backup_type_array = array("Complete" => untrailingslashit(ABSPATH));
						break;

						case "only_filesystem":
							$backup_type_array = array("Filesystem" => untrailingslashit(ABSPATH));
						break;

						case "only_plugins_and_themes":
							$backup_type_array = array("Plugins_Themes" => array(untrailingslashit(WP_PLUGIN_DIR),WP_CONTENT_DIR."/themes"));
						break;
					}
					return $backup_type_array;
				}

				/**
				* @author		 DavidAnderson <https://updraftplus.com>
				* @copyright 2011-16 David Anderson, 2010 Paul Kehrer
				* @license	 https://opensource.org/licenses/gpl-license
				* @link			 https://updraftplus.com
				* @since		 available since Release 2.4.1
				*/

				public function recursively_addzip_backup_bank($full_filepath, $use_path_when_storing, $original_fullpath)
				{
					$full_filepath = realpath($full_filepath);
					$original_fullpath = realpath($original_fullpath);

					if(is_dir($full_filepath))
					{
						if($full_filepath == $this->upload_dir_realpath)
						{
							$skipped_dir_name = str_replace(realpath(dirname(ABSPATH))."\\","",realpath(BACKUP_BANK_BACKUPS_DIR));
							$this->backup_bank_log("Directory Path <b>$skipped_dir_name</b> has been Skipped.\r\n");
							return true;
						}

						$this->zipfiles_dirbatched[] = $use_path_when_storing;
						if(!$dir_handle = @opendir($full_filepath))
						{
							$this->backup_bank_log("Directory <b>$full_filepath</b> has been Failed to open.\r\n");
							return false;
						}

						while(false !== ($e = readdir($dir_handle)))
						{
							if("." == $e || ".." == $e)
							continue;
							if(is_file($full_filepath."/".$e))
							{
								$file_extention = strstr(basename($full_filepath."/".$e),".");
								if(in_array($file_extention,$this->exclude_list))
								{
									continue;
								}
								if(is_readable($full_filepath."/".$e))
								{
									$store_path = $use_path_when_storing == "" ? $e : $use_path_when_storing."/".$e;
									$this->zipfiles_batched[$full_filepath."/".$e] = $store_path;
									$this->makezip_recursive_batchedbytes += @filesize($full_filepath."/".$e);
								}
								else
								{
									$this->status = "terminated";
									$this->backup_bank_log("Backup of <b>$full_filepath/$e</b> File has been Failed as <b>$full_filepath/$e</b> File is not readable.\r\n");
									return $this->status;
								}
							}
							elseif(is_dir($full_filepath."/".$e))
							{
								$store_path = $use_path_when_storing == "" ? $e : $use_path_when_storing."/".$e;
								$this->recursively_addzip_backup_bank($full_filepath."/".$e, $store_path, $original_fullpath);
							}
						}
						closedir($dir_handle);
					}
					else
					{
						$this->backup_bank_log("File Path <b>$use_path_when_storing</b> is Unexpected.\r\n");
					}
					return true;
				}

				/**
				* @author		 DavidAnderson <https://updraftplus.com>
				* @copyright 2011-16 David Anderson, 2010 Paul Kehrer
				* @license	 https://opensource.org/licenses/gpl-license
				* @link			 https://updraftplus.com
				* @since		 available since Release 2.4.1
				*/

				function zip_addfiles_backup_bank()
				{
					$this->count_zipfiles_batched = count($this->zipfiles_batched);
					$ret = true;
					$zipfile = $this->zip_basename.$this->file_compression_type.".tmp";
					if(count($this->zipfiles_dirbatched) == 0 && count($this->zipfiles_batched) == 0)
					return true;
					$force_allinone = true;
					if($this->use_zip_object == "Backup_bank_PclZip")
					$force_allinone = false;
					$data_added_since_reopen = 0;
					$zipfiles_added_over_maxbatch = 0;

					$message = "{"."\r\n";
					$message .= '"name": '.'"WP Backup Bank"'."\r\n";
					$message .= "}";
					@file_put_contents($this->upload_path."/".$this->archive_name."-".$this->backup_type.".json",$message);
					$zip = $this->file_compression_backup_bank($zipfile);
					$zip->addFile($this->upload_path."/".$this->archive_name."-".$this->backup_type.".json", $this->archive_name."-".$this->backup_type.".json");
					if(!$force_allinone)
					{
						$zip->addFiles_unset();
					}
					else
					{
						unset($zip);
						$zip = $this->file_compression_backup_bank($zipfile);
					}
					unlink($this->upload_path."/".$this->archive_name."-".$this->backup_type.".json");

					$zip = $this->file_compression_backup_bank($zipfile);
					$system_file_name = $this->upload_path."/".$this->archive_name.$this->file_compression_type;
					$database_file_name = $this->upload_path."/".$this->archive_name.$this->db_compression_type;
					if($this->backup_file_path != "")
					{
						$this->backup_bank_log("Adding Compressed Sql Database File <b>".basename($database_file_name)."</b> to <b>".basename($system_file_name)."</b>.\r\n");
						$zip->addFile($this->backup_file_path,basename($this->backup_file_path));
						if(!$force_allinone)
						{
							$zip->addFiles_unset();
						}
						else
						{
							unset($zip);
							$zip = $this->file_compression_backup_bank($zipfile);
						}
					}
					$this->backup_file_path != "" ?	unlink($this->backup_file_path) : "";

					while($dir = array_pop($this->zipfiles_dirbatched)) $zip->addEmptyDir($dir);
					$zipfiles_added_thisbatch = 0;
					if(isset($this->zipfiles_batched) &&  count($this->zipfiles_batched) > 0)
					{
						foreach($this->zipfiles_batched as $file => $add_as)
						{
							if(!file_exists($file))
							{
								$this->backup_bank_log("Dropping File<b>".$add_as."</b>\r\n");
								continue;
							}
							$fsize = filesize($file);

							if($fsize > BACKUP_BANK_WARN_FILE_SIZE)
							{
								$this->backup_bank_log("File <b>$add_as</b> of size <b>".round($fsize/1048576, 1). "Mb</b> has been Encountered.\r\n", "warning", "vlargefile_".md5($this->filename."#".$add_as));
							}
							@touch($zipfile);
							$zip->addFile($file, $add_as);
							$zipfiles_added_thisbatch++;
							$this->zipfiles_added_thisrun++;
							$data_added_since_reopen += $fsize;
							$zipfiles_added_over_maxbatch  += $fsize;

							$maxzipbatch = 26214400;
							$this->zipfiles_added++;

							if($force_allinone)
							{
								if($zipfiles_added_over_maxbatch > $maxzipbatch)
								{
									@set_time_limit(BACKUP_BANK_SET_TIME_LIMIT);
									$zipfiles_added_thisbatch = 0;
									unset($zip);
									$zipfiles_added_over_maxbatch = 0;
									clearstatcache();
									if(empty($zip))
									{
										$zip = $this->file_compression_backup_bank($zipfile);
									}
								}

								if($this->zipfiles_added == $this->count_zipfiles_batched)
								{
									@set_time_limit(BACKUP_BANK_SET_TIME_LIMIT);
									$zipfiles_added_thisbatch = 0;
									unset($zip);
									$zipfiles_added_over_maxbatch = 0;
									if(empty($zip))
									{
										$zip = $this->file_compression_backup_bank($zipfile);
									}
									clearstatcache();
								}
							}
							else
							{
								if($zipfiles_added_over_maxbatch > $maxzipbatch)
								{
									@set_time_limit(BACKUP_BANK_SET_TIME_LIMIT);
									$zip->addFiles_unset();
									$zipfiles_added_over_maxbatch = 0;
									clearstatcache();
								}
								if($this->zipfiles_added == $this->count_zipfiles_batched)
								{
									@set_time_limit(BACKUP_BANK_SET_TIME_LIMIT);
									$zip->addFiles_unset();
									$zipfiles_added_over_maxbatch = 0;
									clearstatcache();
								}
							}

							$this->files_size_added = round($data_added_since_reopen/1048576, 1);
							$this->total_files_size = round($this->makezip_recursive_batchedbytes/1048576,1);

							if($this->zipfiles_added % 100 == 0 || $this->zipfiles_added == $this->count_zipfiles_batched)
							$this->backup_bank_log("Zip Compression : <b>".$this->zipfiles_added."</b> Files out of <b>".$this->count_zipfiles_batched."</b> Files added on <b>".basename($zipfile)."</b> <br/> Completed (<b>".round($data_added_since_reopen/1048576, 1)."Mb</b> out of <b>".round($this->makezip_recursive_batchedbytes/1048576,1)."Mb</b>).\r\n");
						}
					}
					$this->zipfiles_batched = array();

					$nret = $zip->close();

					unset($zip);
					clearstatcache();
					return ($ret == false) ? false : $nret;
				}

				/**
				* @author		 DavidAnderson <https://updraftplus.com>
				* @copyright 2011-16 David Anderson, 2010 Paul Kehrer
				* @license	 https://opensource.org/licenses/gpl-license
				* @link			 https://updraftplus.com
				* @since		 available since Release 2.4.1
				*/

				function makezipfile_backup_bank($source, $file_path, $backup_filename,$filename)
				{
					$tmp_file = $this->upload_path."/".$file_path.".tmp";
					if(file_exists($tmp_file))
					{
						$this->backup_bank_log("File <b>".basename($tmp_file)."</b> has been removed as Zip file already Exists.\r\n");
						@unlink($tmp_file);
					}
					$this->zipfiles_added = 0;
					$this->zipfiles_added_thisrun = 0;
					$this->zipfiles_dirbatched = array();
					$this->zipfiles_batched = array();
					$this->zip_basename = $this->upload_path."/".$backup_filename;

					$error_occurred = false;
					$this->makezip_recursive_batchedbytes = 0;
					if(!is_array($source))
					$source = array($source);
					if(isset($source) &&  count($source) > 0)
					{
						foreach($source as $element)
						{
							$use_path = $this->backup_type != "only_plugins_and_themes" ? $this->archive_name : $this->archive_name."/".basename($element);
							$add_them = $this->recursively_addzip_backup_bank($element,$use_path, $element);
							if(is_wp_error($add_them) || false === $add_them)
							$error_occurred = true;
						}
					}
					if(count($this->zipfiles_dirbatched) > 0 || count($this->zipfiles_batched) > 0)
					{
						$this->backup_bank_log("<b>".count($this->zipfiles_dirbatched)." </b>Directories,<b>"." ".count($this->zipfiles_batched)." </b>Files of size <b>".round($this->makezip_recursive_batchedbytes/1048576,1)." Mb</b> are the total entities for the Zip file.\r\n");
						$add_them = $this->zip_addfiles_backup_bank();

						if(is_wp_error($add_them))
						{
							foreach($add_them->get_error_messages() as $msg)
							{
								$this->backup_bank_log("zip_addfiles_backup_bank returned an error <b>$msg</b>.\r\n");
							}
							$error_occurred = true;
						}
						elseif(false === $add_them)
						{
							$this->backup_bank_log("zip_addfiles_backup_bank returned false.\r\n");
							$error_occurred = true;
						}
					}
					if($error_occurred == false || $this->zipfiles_added > 0)
					{
						return true;
					}
					else
					{
						$this->backup_bank_log("Error occurred while adding Zipfiles <b>".$this->zipfiles_added."</b> (Method=<b>".$this->use_zip_object."</b>)\r\n");
						return false;
					}
				}

				/**
				* @author		 DavidAnderson <https://updraftplus.com>
				* @copyright 2011-16 David Anderson, 2010 Paul Kehrer
				* @license	 https://opensource.org/licenses/gpl-license
				* @link			 https://updraftplus.com
				* @since		 available since Release 2.4.1
				*/

				function create_zip_backup_bank($dirname, $filename, $backup_filename)
				{
					@set_time_limit(BACKUP_BANK_SET_TIME_LIMIT);

					$this->filename = $filename;
					$this->backup_bank_log("Starting Compression for <b>$filename</b> Backup.\r\n");

					if(is_string($dirname) && !file_exists($dirname))
					{
						$this->backup_bank_log("Backup of <b>$filename</b> File has failed as Directory <b>$dirname</b> does not Exist.\r\n");
						return false;
					}
					$file_path = $backup_filename.$this->file_compression_type;
					$backup_full_path = $this->upload_path."/".$file_path;

					clearstatcache();
					$zipcode = $this->makezipfile_backup_bank($dirname, $file_path,$backup_filename,$filename);
					if($zipcode !== true)
					{
						$this->status = "terminated";
						$this->backup_bank_log("Error occrred while creating <b>$filename</b> zip file.\r\n");
					}
					else
					{
						if(file_exists($backup_full_path.".tmp"))
						{
							if(@filesize($backup_full_path.".tmp") === 0)
							{
								$this->status = "terminated";
								$this->backup_bank_log("Backup of <b>$filename</b> zip has been Failed.\r\n");
								@unlink($backup_full_path.".tmp");
							}
							else
							{
								$this->status = "completed_successfully";
								$this->backup_completed = 100;
								@rename($backup_full_path.".tmp", $backup_full_path);
								$this->kbsize = round(filesize($backup_full_path)/1048576,1);
								$this->log_timetaken = microtime(true)-$this->zip_microtime_start;
								$this->timetaken = max(microtime(true)-$this->zip_microtime_start, 0.000001);
								$zip_creating_rate = round($this->kbsize/$this->timetaken, 1);
								$this->backup_bank_log("Total Size on Disk : <b>".round($this->kbsize,1)." Mb</b> Transferred @ <b>$zip_creating_rate Mb/s</b>.<br/>Completed Backup Successfully.\r\n");
							}
						}
						else
						{
							$this->status = "terminated";
							$this->backup_bank_log("File <b>".basename($backup_full_path).".tmp</b> not Found.\r\n", "warning");
						}
					}
					return $this->status;
				}

				/**
				* @author		 DavidAnderson <https://updraftplus.com>
				* @copyright 2011-16 David Anderson, 2010 Paul Kehrer
				* @license	 https://opensource.org/licenses/gpl-license
				* @link			 https://updraftplus.com
				* @since		 available since Release 2.1.0
				*/

				public function get_directories_backup_bank()
				{
					$check_file = $this->upload_path."/".$this->archive_name.$this->file_compression_type;
					if(file_exists($check_file))
					{
						$this->backup_completed = 1;
						$this->status = "file_exists";
						$this->backup_bank_log("File <b>".basename($check_file). "</b> already Exists.\r\n");
					}
					else
					{
						if($this->backup_type == "complete_backup")
						{
							$this->backup_file_path = $this->database_backup_bank();
							if($this->backup_file_path == "terminated" || $this->backup_file_path == "file_exists")
							{
								return $this->backup_file_path;
							}
						}

						$backup_filename = $this->archive_name;
						$backup_filetypes = $this->get_backup_type_files_backup_bank($this->backup_type);

						if(!$this->is_writable_backup_bank(BACKUP_BANK_BACKUPS_DIR))
						{
							$this->backup_bank_log("Backup Directory (".$this->upload_path.") is not writable.\r\n");
							return array();
						}
						if(isset($backup_filetypes) &&  count($backup_filetypes) > 0)
						{
							foreach($backup_filetypes as $filename => $file_dir)
							{
								$this->backup_file = $filename;
								if($this->file_compression_type == ".zip")
								{
									if(count($file_dir)>0)
									{
										$backup_path = $this->create_zip_backup_bank($file_dir, $filename, $backup_filename);
										if($backup_path == "terminated")
										{
											$this->status = "terminated";
											$this->backup_bank_log("Error occurred while creating <b>$filename</b> zip file.\r\n");
										}
									}
									else
									{
										$this->status = "terminated";
										$this->backup_bank_log("Backup of <b>$filename</b> has Failed.\r\n");
									}
									if($this->backup_destination != "local_folder")
									{
										$this->status = "completed";
										$this->backup_completed = 100;
										$this->backup_destination_backup_bank($this->backup_file);
									}
									// else
									// {
									// 	$this->status = "completed_successfully";
									// 	$this->backup_completed = 100;
									// }
								}
								else
								{
									$this->backup_bank_tar_compression($filename,$file_dir);
								}
							}
						}
						if($this->status == "terminated" || $this->status == "file_exists")
						{
							$database_file = $this->upload_path."/".$this->archive_name.$this->db_compression_type;
							@unlink($database_file);
						}
					}

					return $this->status;
				}

				/**
				* @author		 DavidAnderson <https://updraftplus.com>
				* @copyright 2011-16 David Anderson, 2010 Paul Kehrer
				* @license	 https://opensource.org/licenses/gpl-license
				* @link			 https://updraftplus.com
				* @since		 available since Release 2.4.1
				*/

				public function file_compression_backup_bank($zipfile)
				{
					switch($this->file_compression_type)
					{
						case ".zip":
							$zip = new $this->use_zip_object;
							if(file_exists($zipfile))
							{
								$openfile = $zip->open($zipfile);
								clearstatcache();
							}
							else
							{
								$create_file = (version_compare(PHP_VERSION, "5.2.12", ">") && defined("ZIPARCHIVE::CREATE")) ? ZIPARCHIVE::CREATE : 1;
								$openfile = $zip->open($zipfile, $create_file);
							}
							if($openfile !== true)
							{
								$this->backup_bank_log($zip->last_error."\r\n");
								die();
							}
						break;
					}
					return $zip;
				}

				/**
				* @author		 DavidAnderson <https://updraftplus.com>
				* @copyright 2011-16 David Anderson, 2010 Paul Kehrer
				* @license	 https://opensource.org/licenses/gpl-license
				* @link			 https://updraftplus.com
				* @since		 available since Release 2.1.0
				*/

				public function backup_bank_tar_compression($filename,$file_dir)
				{
					$this->backup_bank_log("Starting Compression for <b>$filename</b> Backup.\r\n");
					$zipfile = $this->upload_path."/".$this->archive_name.$this->file_compression_type;
					$this->database_file_name = $this->upload_path."/".$this->archive_name.$this->db_compression_type;
					$tar = new Archive_Tar($zipfile,$this->file_compression_type,$this->backup_type,$this->database_file_name,$this->backup_destination,$this->backup_file,"manual");
					$this->backup_file_path != "" ? $tar->create($file_dir,$this->exclude_list,$this->backup_file_path) : $tar->create($file_dir,$this->exclude_list,"");
					$this->status = $tar->status;
					$this->kbsize = $tar->kbsize;
					$this->timetaken  = $tar->timetaken;
					$this->log_timetaken = $tar->log_timetaken;
				}
			}
		}

		/**
		* @author		 DavidAnderson <https://updraftplus.com>
		* @copyright 2011-16 David Anderson, 2010 Paul Kehrer
		* @license	 https://opensource.org/licenses/gpl-license
		* @link			 https://updraftplus.com
		* @since		 available since Release 2.1.0
		*/

		class backup_bank_WPDB extends wpdb
		{
			public function backup_bank_getdbh()
			{
				return $this->dbh;
			}
			public function backup_bank_use_mysqli()
			{
				return !empty($this->use_mysqli);
			}
		}
	}
}
?>
