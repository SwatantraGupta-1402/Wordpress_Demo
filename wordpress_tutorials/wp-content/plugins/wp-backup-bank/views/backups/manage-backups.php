<?php
/**
* This Template is used for displaying generated backups and restore them.
*
* @author  Tech Banker
* @package wp-backup-bank/views/backups
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
	else if(manage_backups_backup_bank == "1")
	{
		$backup_bank_manage_backups_delete = wp_create_nonce("backup_bank_manage_backups_delete");
		$backup_bank_manage_backups_bulk_delete = wp_create_nonce("backup_bank_manage_backups_bulk_delete");
		$backup_bank_manage_backups = wp_create_nonce("backup_bank_manage_backups");
		$backup_bank_manage_rerun_backups = wp_create_nonce("backup_bank_manage_rerun_backups");
		$backup_bank_purge = wp_create_nonce("backup_bank_purge");
		$backup_bank_restore_message = wp_create_nonce("backup_bank_restore_message");
		$backup_bank_check_ftp_dropbox_connection = wp_create_nonce("backup_bank_check_ftp_dropbox_connection_rerun");
		?>
		<div class="page-content-wrapper">
			<div class="page-content">
				<div class="page-bar">
					<ul class="page-breadcrumb">
						<li>
							<i class="icon-custom-home"></i>
							<a href="admin.php?page=bb_manage_backups">
								<?php echo $wp_backup_bank; ?>
							</a>
							<span>></span>
						</li>
						<li>
							<a href="admin.php?page=bb_manage_backups">
								<?php echo $bb_backups; ?>
							</a>
							<span>></span>
						</li>
						<li>
							<span>
								<?php echo $bb_manage_backups; ?>
							</span>
						</li>
					</ul>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="portlet box vivid-green">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-custom-folder-alt"></i>
									<?php echo $bb_manage_backups; ?>
								</div>
							</div>
							<div class="portlet-body form">
								<form id="ux_frm_manage_backups">
									<div class="form-body">
										<div class="note note-danger">
											<h4 class="block">
												<?php echo $bb_premium_edition_features_disclaimer; ?>
											</h4>
											<ul>
												<div class="row">
													<div class="col-md-4">
														<li><?php echo $bb_backup_bank_purge_backup; ?></li>
													</div>
													<div class="col-md-4">
														<li><?php echo $bb_backup_bank_restore_backup; ?></li>
													</div>
												</div>
											</ul>
											<ul>
												<li><?php echo $bb_backup_bank_demos_disclaimer; ?><a href="<?php echo "http://beta.tech-banker.com/products/wp-backup-bank/demos/";?>" target="_blank" class='custom_links'><?php echo $bb_backup_bank_click_here; ?></a>. </li>
												<li><?php echo $bb_backup_bank_user_guide_disclaimer; ?><a href="<?php echo "http://beta.tech-banker.com/products/wp-backup-bank/user-guide/backups-restore/manage-backups/";?>" target="_blank" class='custom_links'><?php echo $bb_backup_bank_click_here; ?></a>.</li>
											</ul>
											<?php
											if((isset($extension_not_found) && count($extension_not_found) > 0 )|| $total_backups >= 5)
											{
												?>
												<h4 class="block">
													<?php echo $bb_important_disclaimer; ?>
												</h4>
												<?php
											}
											if(isset($extension_not_found) && count($extension_not_found) > 0)
											{
												?>
												<ul>
													<li><?php echo $bb_contact_to_host; ?></li>
													<?php
													foreach($extension_not_found as $extension)
													{
														?>
														<li>* <?php echo $extension; ?></li>
														<?php
													}
													?>
												</ul>
												<?php
												}
												if($total_backups >= 5)
												{
													?>
													<ul>
														<li><?php echo $bb_backups_limit_exceed; ?></li>
													</ul>
													<?php
												}
											?>
										</div>
										<div class="table-top-margin">
											<select name="ux_ddl_manage_backups" id="ux_ddl_manage_backups" class="custom-bulk-width">
												<option value=""><?php echo $bb_manage_backups_bulk_action; ?></option>
												<option value="delete"><?php echo $bb_manage_backups_delete; ?></option>
											</select>
											<input type="button" class="btn vivid-green" name="ux_btn_apply" id="ux_btn_apply" value="<?php echo $bb_manage_backups_apply;?>" onclick="bulk_delete_backup_logs()">
											<a href="admin.php?page=bb_start_backup" class="btn vivid-green" name="ux_btn_manual_backup" id="ux_btn_manual_backup" <?php echo $total_backups >= 5 ? "disabled=disabled" : ""; ?>> <?php echo $bb_start_backup;?></a>
											<a href="admin.php?page=bb_schedule_backup" class="btn vivid-green" name="ux_btn_schedule_backup" id="ux_btn_schedule_backup"> <?php echo $bb_manage_backups_schedule_backup_btn;?></a>
											<input type="button" class="btn btn-danger" name="ux_btn_purge_backups" id="ux_btn_purge_backups" value="<?php echo $bb_manage_backups_purge_backups; ?>" onclick="purge_backup_bank();">
										</div>
										<div class="line-separator"></div>
										<table class="table table-striped table-bordered table-hover table-margin-top" id="ux_tbl_manage_backups">
											<thead>
												<tr>
													<th style="text-align: center;" class="chk-action">
														<input type="checkbox" name="ux_chk_all_manage_backups" id="ux_chk_all_manage_backups">
													</th>
													<th style="width:33%">
														<label>
															<?php echo $bb_backup_Details;?>
														</label>
													</th>
													<th style="width:23%">
														<label>
															<?php echo $bb_last_execution;?>
														</label>
													</th>
													<th style="width:24%">
														<label>
															<?php echo $bb_next_execution;?>
														</label>
													</th>
														<th style="width:20%">
														<label>
															<?php echo $bb_manage_backups_last_status;?>
														</label>
													</th>
												</tr>
											</thead>
											<tbody>
												<?php
												if(isset($bb_backups_unserialized_data) && count($bb_backups_unserialized_data) > 0)
												{
													foreach($bb_backups_unserialized_data as $value)
													{
														$backup_archive_time = "";
														$backup_log_time = "";
														if($value["execution"] == "scheduled")
														{
															$archives_array = unserialize($value["archive"]);
															$pop_archive_array = array_pop($archives_array);
															$archive_array = array_reverse($archives_array);
															$logs_array = unserialize($value["log_filename"]);
															$pop_log_array = array_pop($logs_array);
															$log_array = array_reverse($logs_array);
															$count_array = count($archives_array);
															if(isset($value["execution_time"]))
															{
																$backup_time_array = unserialize($value["execution_time"]);
																$backup_time = array_reverse($backup_time_array);
																$backup_archive_time = array_combine($archive_array,$backup_time);
																$backup_log_time = array_combine($log_array,$backup_time);
															}
														}
														else
														{
															$log_array = unserialize($value["log_filename"]);
															$archive_array = unserialize($value["archive"]);
															$count_array = count($archive_array);
															if(isset($value["execution_time"]))
															{
																$backup_time = unserialize($value["execution_time"]);
																$backup_archive_time = array_combine($archive_array,$backup_time);
																$backup_log_time = array_combine($log_array,$backup_time);
															}
														}
														$count_log_array = count($log_array);
														$restore_log_time = "";
														if(isset($value["restore_log_urlpath"]))
														{
															$restore_logs_array = unserialize($value["restore_log_urlpath"]);
															$restore_log_array = array_reverse($restore_logs_array);
															$restore_time_array = unserialize($value["restore_execution_time"]);
															$restore_time = array_reverse($restore_time_array);
															$restore_log_time = array_combine($restore_log_array,$restore_time);
														}
														$upload_status = 0;
														if($value["status"] == "uploading_to_ftp" || $value["status"] == "uploading_to_email")
														{
															$upload_status = 1;
														}
														?>
														<tr>
															<td style="text-align: center;">
																<input type="checkbox" name="ux_chk_manage_backups_<?php echo $value["meta_id"]; ?>" id="ux_chk_manage_backups_<?php echo $value["meta_id"]; ?>" onclick="check_all_manage_backups(<?php echo $value["meta_id"]; ?>)" value="<?php echo $value["meta_id"]; ?>">
															</td>
															<td>
																<label class="control-label">
																	<strong>
																		<?php echo $bb_backup_name;?> :
																	</strong>
																	<?php echo esc_html($value["backup_name"]); ?>
																</label><br>
																<label class="control-label">
																	<strong>
																		<?php echo $bb_backup_type;?> :
																	</strong>
																	<?php
																	switch($value["backup_type"])
																	{
																		case "complete_backup":
																			echo $bb_complete_backup;
																		break;

																		case "only_database":
																			echo $bb_database;
																		break;

																		case "only_filesystem":
																			echo $bb_filesystem;
																		break;

																		case "only_plugins_and_themes":
																			echo $bb_plugins_themes;
																		break;

																		case "only_themes":
																			echo $bb_themes;
																		break;

																		case "only_plugins":
																			echo $bb_plugins;
																		break;

																		case "only_wp_content_folder":
																			echo $bb_contents;
																		break;
																	}
																	?>
																</label><br>
																<label class="control-label">
																	<strong>
																		<?php echo $bb_backup_destination;?> :
																	</strong>
																	<?php
																	switch($value["backup_destination"])
																	{
																		case "local_folder":
																			echo $bb_local_folder;
																		break;

																		case "email":
																			echo $bb_email;
																		break;

																		case "ftp":
																			echo $bb_ftp;
																		break;
																	}
																	?>
																</label><br>
																<label class="control-label">
																	<strong>
																		<?php echo $bb_compression_type;?> :
																	</strong>
																	<?php
																		if($value["backup_type"] == "only_database")
																		{
																			echo esc_html($value["db_compression_type"]);
																		}
																		else
																		{
																			echo esc_html($value["file_compression_type"]);
																		}
																	?>
																</label><br>
																<label class="control-label">
																	<strong>
																		<?php echo $bb_manage_backups_execution;?> :
																	</strong>
																	<?php
																	switch($value["execution"])
																	{
																		case "manual":
																			echo $bb_manage_backups_execution_manual;
																		break;

																		case "scheduled":
																			echo $bb_manage_backups_execution_scheduled;
																		break;
																	}
																	?>
																</label><br>
																<label class="control-label">
																	<strong>
																		<?php echo $bb_backup_executed_in;?> :
																	</strong>
																	<?php
																	if($value["status"] == "completed_successfully" || $value["status"] == "email_not_sent" ||
																	($value["execution"] == "scheduled" && $count_array >= 1) || $upload_status > 0)
																	{
																		echo date("H:i:s",esc_html($value["executed_in"]));
																	}
																	else
																	{
																		echo $bb_na;
																	}
																	?>
																</label><br>
																<label class="control-label">
																	<strong>
																		<?php echo $bb_backup_total_size;?> :
																	</strong>
																	<?php
																	if($value["status"] == "completed_successfully" || $value["status"] == "email_not_sent" ||
																  ($value["execution"] == "scheduled" && $count_array >= 1) || $upload_status > 0)
																	{
																		echo esc_html($value["total_size"]);
																	}
																	else
																	{
																		echo $bb_na;
																	}
																	?>
																</label><br>
																<label class="custom-alternative">
																	<?php
																	if($value["execution"] == "manual" && $value["status"] != "running")
																	{
																		$file_name = implode("",unserialize($value["archive_name"]));
																		?>
																		<a href="javascript:void(0);">
																			<i class="icon-custom-reload tooltips" data-original-title="<?php echo $bb_manage_rerun_backup ;?>" onclick="rerun_backup_bank(<?php echo $value["meta_id"]; ?>,'<?php echo $value["backup_destination"]; ?>','<?php echo $file_name; ?>','<?php echo $value["folder_location"]; ?>')" data-placement="top"></i>
																		</a> |
																	<?php
																	}
																	if($value["status"] == "completed_successfully" || $value["status"] == "email_not_sent" || isset($value["old_backup"])
																	|| ($value["execution"] == "scheduled" && $count_array >= 1) || $upload_status > 0)
																	{
																		?>
																		<a href="javascript:void(0);"  data-popup-open="ux_open_popup" onclick="show_download_backup_bank(<?php echo $value["meta_id"]?>);">
																			<i class="icon-custom-arrow-down tooltips" data-original-title="<?php echo $bb_manage_download_backup ;?>" data-placement="top"></i>
																		</a> |
																		<?php
																		if(!isset($value["old_backup"]))
																		{
																			?>
																			<a href="javascript:void(0);" onclick="show_restore_backup_bank();">
																				<i class="icon-custom-share-alt tooltips" data-original-title="<?php echo $bb_manage_backups_tooltip ;?>" data-placement="top"></i>
																			</a> |
																		<?php
																		}
																	}
																	if((($value["status"] != "not_yet_executed" && $value["status"] != "running" || isset($value["old_backup"]) || ($value["execution"] == "scheduled" && $count_array >= 1) )) && $count_log_array > 0)
																	{
																		?>
																		<a href="javascript:void(0);" data-popup-open="ux_open_popup" onclick="show_download_log_backup_bank(<?php echo $value["meta_id"]?>);">
																			<i class="icon-custom-login tooltips" data-original-title="<?php echo $bb_manage_download_log_file ;?>" data-placement="top"></i>
																		</a> |
																		<?php
																	}
																	?>
																	<a href="javascript:void(0);">
																		<i class="icon-custom-trash tooltips" data-original-title="<?php echo $bb_manage_backups_delete ;?>" onclick="delete_backup_logs(<?php echo $value["meta_id"] ?>)" data-placement="top"></i>
																	</a>
																</label><br>
																<select name="ux_ddl_download_type_<?php echo $value["meta_id"];?>" id="ux_ddl_download_type_<?php echo $value["meta_id"];?>" class="form-control" style="display:none;">
																	<option value=""><?php echo $bb_choose_backup; ?></option>
																	<?php
																	if($backup_archive_time != "")
																	{
																		foreach($backup_archive_time as $key => $data)
																		{
																			?>
																			<option value="<?php echo trailingslashit($value["backup_urlpath"]).$key; ?>"><?php echo $bb_manage_backup_on.date("d M Y h:i A",$data)." (".$key.")"; ?></option>
																			<?php
																		}
																	}
																	?>
																</select>
																<select name="ux_ddl_download_log_<?php echo $value["meta_id"];?>" id="ux_ddl_download_log_<?php echo $value["meta_id"];?>" class="form-control" style="display:none;">
																	<option value=""><?php echo $bb_choose_log_file; ?></option>
																	<?php
																		if(is_array($restore_log_time))
																		{
																			foreach($restore_log_time as $key => $data)
																			{
																				?>
																				<option value="<?php echo $key; ?>"><?php echo $bb_manage_backup_restore_on.date("d M Y h:i A",$data)." (".basename($key).")"; ?></option>
																				<?php
																			}
																		}
																		if(isset($value["old_backup_logfile"]))
																		{
																			if(isset($backup_time) && count($backup_time) > 0)
																			{
																				foreach($backup_time as $time)
																				{
																					?>
																					<option value="<?php echo $value["old_backup_logfile"]; ?>"><?php echo $bb_manage_backup_on.date("d M Y h:i A",$time)." (".basename($value["old_backup_logfile"]).")"; ?></option>
																					<?php
																				}
																			}
																		}
																		else
																		{
																			if($backup_log_time != "")
																			{
																				foreach($backup_log_time as $key => $data)
																				{
																					?>
																					<option value="<?php echo trailingslashit($value["backup_urlpath"]).$key; ?>"><?php echo $bb_manage_backup_on.date("d M Y h:i A",$data)." (".$key.")"; ?></option>
																					<?php
																				}
																			}
																		}
																	?>
																</select>
															</td>
															<td>
																<label class="control-label">
																	<?php
																	switch($value["status"])
																	{
																		case "not_yet_executed":
																			echo $bb_manage_backups_status_not_yet;
																		break;

																		default:
																		echo date("d M Y h:i A",esc_html($value["executed_time"]));
																	}
																	?>
																</label><br>
															</td>
															<td>
																<?php
																	if($value["execution"] == "manual")
																	{
																		echo $bb_na;
																	}
																	else
																	{
																		$schedule_name = "backup_scheduler_".$value["meta_id"];
																		$next_execution = get_backup_bank_schedule_time($schedule_name);
																		$diff =  timezone_difference_backup_bank($value["time_zone"]);
																		switch ($next_execution)
																		{
																			case "":
																				echo $bb_na;
																			break;

																			default:
																				echo date("d M, Y h:i A",$next_execution - $diff)."<br/>"." In About ".human_time_diff($next_execution);
																			break;
																		}
																	}
																?>
															</td>
															<td>
																<?php
																switch($value["status"])
																{
																	case "not_yet_executed":
																		echo $bb_manage_backups_status_not_yet;
																	break;

																	case "file_exists":
																	case "terminated":
																		echo $bb_manage_backups_terminated;
																	break;

																	case "email_not_sent":
																	case "completed_successfully":
																		echo $bb_manage_backups_completed_successfully;
																	break;

																	case "completed":
																	case "running":
																		echo $bb_manage_backups_status_running;
																	break;

																	case "uploading_to_email":
																		echo $bb_manage_backups_uploading_to_email;
																	break;

																	case "uploading_to_ftp":
																		echo $bb_manage_backups_uploading_to_ftp;
																	break;
																}
																?>
															</td>
														</tr>
														<?php
													}
												}
												?>
											</tbody>
										</table>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="popup" data-popup="ux_open_popup">
			<div class="popup-inner">
				<div class="portlet box vivid-green">
					<div class="portlet-title">
						<div class="caption" id="ux_div_action">
							<?php echo $bb_manage_backups_download_backup ; ?>
						</div>
					</div>
					<div class="modal-body">
						<form id="ux_frm_download_backups">
							<div class="form-group">
								<label class="control-label">
									<span id="ux_span_download">
										<?php echo $bb_manage_select_backup;?>
									</span> :
									<i class="icon-custom-question tooltips" id="ux_pop_up_tooltip" data-original-title="<?php echo $bb_choose_backup_to_download_tooltip;?>" data-placement="right"></i>
									<span class="required" aria-required="true">*</span>
								</label>
								<select name="ux_ddl_download_type" id="ux_ddl_download_type" class="form-control">
									<option value=""><?php echo $bb_choose_backup; ?></option>
								</select>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<input type="button" class="btn vivid-green" name="ux_btn_backup" id="ux_btn_backup" onclick="download_backup_bank();" value="<?php echo $bb_manage_download_backup;?>">
						<input type="button" data-popup-close="ux_open_popup" class="btn vivid-green" name="ux_btn_close" id="ux_btn_close" value="<?php echo $bb_manage_backups_close;?>">
					</div>
				</div>
			</div>
		</div>

		<div id="ux_div_portlet_progress" style="display:none;">
			<div id="ux_div_progressbar">
				<div class="progress-bar-position ">
					<div class="portlet-progress-bar">
						<span id="progress_bar_heading">
							Restore Backup
						</span>
						<?php
						if(!is_rtl())
						{
							?>
							<span style="float:right;">
								<span id="ux_hrs" class="tech-banker-counter">00</span> <span id="ux_collon" class="tech-banker-counter">:</span>
								<span id="ux_mins">00</span> :
								<span id="ux_secs">00</span>
							</span>
							<?php
						}
						else
						{
							?>
							<span style="float:left;">
								<span id="ux_secs">00</span> :
								<span id="ux_mins">00</span> <span id="ux_collon" class="tech-banker-counter">:</span>
								<span id="ux_hrs" class="tech-banker-counter">00</span>
							</span>
							<?php
						}
						?>
					</div>
					<div id="progress" class="progress-bar-width">
						<div id="progress_status" style="width:1%;max-width: 100%;color:#fff;background-color:#a4cd39;text-align: center;">
							1%
						</div>
					</div>
					<div id="uploading_progress" class="tech-banker-counter">
						<div id="upload_progress" class="progress-bar-width">
							<div id="uploaded_status" style="width:1%;max-width: 100%;color:#fff;background-color:#a4cd39;text-align: center;">
								1%
							</div>
						</div>
					</div>
					<div id="information" class="progress-info">
						Restoring Backup
					</div>
					<div class="portlet-progress-message">
						<p>
							<span id="cancel_message">* Please do not <u>Cancel</u> or <u>Refresh</u> the Page until the Restore process is Completed.</span><br/>
							* Kindly be Patient!
						</p>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
	else
	{
		?>
		<div class="page-content-wrapper">
			<div class="page-content">
				<div class="page-bar">
					<ul class="page-breadcrumb">
						<li>
							<i class="icon-custom-home"></i>
							<a href="admin.php?page=bb_manage_backups">
								<?php echo $wp_backup_bank; ?>
							</a>
							<span>></span>
						</li>
						<li>
							<a href="admin.php?page=bb_manage_backups">
								<?php echo $bb_backups; ?>
							</a>
							<span>></span>
						</li>
						<li>
							<span>
								<?php echo $bb_manage_backups; ?>
							</span>
						</li>
					</ul>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="portlet box vivid-green">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-custom-folder-alt"></i>
									<?php echo $bb_manage_backups; ?>
								</div>
							</div>
							<div class="portlet-body form">
								<div class="form-body">
									<strong><?php echo $bb_user_access_message; ?></strong>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
?>
