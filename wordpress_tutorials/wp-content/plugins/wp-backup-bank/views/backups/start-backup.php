<?php
/**
* This Template is used for creating new backups.
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
	else if(manual_backup_bank == "1")
	{
		$backup_bank_manual_backup = wp_create_nonce("backup_bank_manual_backup");
		$backup_bank_check_ftp_dropbox_connection = wp_create_nonce("backup_bank_check_ftp_dropbox_connection");
		$local_folder_destination =  str_replace(BACKUP_BANK_CONTENT_DIR,"",BACKUP_BANK_BACKUPS_DATE_DIR);
		$content_folder_destination = str_replace("\\","/",BACKUP_BANK_CONTENT_DIR);
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
								<?php echo $bb_start_backup ; ?>
							</span>
						</li>
					</ul>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="portlet box vivid-green">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-custom-note"></i>
									<?php echo $bb_start_backup ; ?>
								</div>
							</div>
							<div class="portlet-body form">
								<form id="ux_frm_add_new_backup">
									<div class="form-body">
										<div class="note note-danger">
											<h4 class="block">
												<?php echo $bb_premium_edition_features_disclaimer; ?>
											</h4>
											<ul>
												<div class="row">
													<div class="col-md-3">
														<li><?php echo $bb_backup_bank_complete_backup_disclaimer; ?></li>
													</div>
													<div class="col-md-4">
														<li><?php echo $bb_backup_bank_db_extensions_disclaimer; ?></li>
													</div>
													<div class="col-md-5">
														<li><?php echo $bb_backup_bank_file_extensions_disclaimer; ?></li>
													</div>
												</div>
												<li><?php echo $bb_backup_bank_dropbox_ftp_email_backup_disclaimer; ?></li>
											</ul>
											<ul>
												<li><?php echo $bb_backup_bank_demos_disclaimer; ?><a href="<?php echo "http://beta.tech-banker.com/products/wp-backup-bank/demos/";?>" target="_blank" class='custom_links'><?php echo $bb_backup_bank_click_here; ?></a>. </li>
												<li><?php echo $bb_backup_bank_user_guide_disclaimer; ?><a href="<?php echo "http://beta.tech-banker.com/products/wp-backup-bank/user-guide/backups-restore/start-backup/";?>" target="_blank" class='custom_links'><?php echo $bb_backup_bank_click_here; ?></a>. </li>
												<li> <?php echo $bb_click; ?><a href="<?php echo "http://beta.tech-banker.com/products/wp-backup-bank/";?>" target="_blank" class='custom_links'><?php echo $bb_backup_bank_click_here; ?></a> <?php echo $bb_backup_bank_premium_disclaimer; ?>.</li>
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
											</ul>
										</div>
										<div class="form-actions">
											<div class="pull-right">
												<input type="submit" class="btn vivid-green ux_btn_generate_backup" name="ux_btn_generate_backup" id="ux_btn_generate_backup" value="<?php echo $bb_start_backup;?>" <?php echo $total_backups >= 5 ? "disabled=disabled" : ""; ?>>
											</div>
										</div>
										<div class="line-separator"></div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">
														<?php echo $bb_backup_name;?> :
														<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_backup_name_tooltip;?>" data-placement="right"></i>
														<span class="required" aria-required="true">*</span>
													</label>
													<input type="text" name="ux_txt_backup_name" id="ux_txt_backup_name" class="form-control"  value="Backup From WP Backup Bank" placeholder="<?php echo $bb_backup_name_placeholder ;?>">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">
														<?php echo $bb_backup_type;?> :
														<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_backup_type_tooltip;?>" data-placement="right"></i>
														<span class="required" aria-required="true">*</span>
													</label>
													<input type="hidden" name="ux_txt_backup_type" id="ux_txt_backup_type" class="form-control" value="<?php echo $local_folder_destination;?>">
													<select name="ux_ddl_backup_type" id="ux_ddl_backup_type" class="form-control" onchange="backup_type_backup_bank();">
														<option value="complete_backup" disabled="disabled"  style="color:#FF0000"><?php echo $bb_complete_backup . " " . $bb_premium_edition; ?></option>
														<option value="only_database"><?php echo $bb_only_database; ?></option>
														<option value="only_filesystem"><?php echo $bb_only_filesystem; ?></option>
														<option value="only_plugins_and_themes"><?php echo $bb_only_plugins_and_themes; ?></option>
														<option value="only_themes"><?php echo $bb_only_themes; ?></option>
														<option value="only_plugins"><?php echo $bb_only_plugins; ?></option>
														<option value="only_wp_content_folder"><?php echo $bb_wp_content_folder; ?></option>
													</select>
												</div>
											</div>
										</div>
										<div id="ux_div_exclude_list">
											<div class="form-group">
													<label class="control-label">
														<?php echo $bb_exclude_list;?> :
														<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_exclude_list_tooltip;?>" data-placement="right"></i>
													</label>
													<input type="text" class="form-control" name="ux_txt_return_email" id="ux_txt_return_email" value=".svn-base, .git, .ds_store" placeholder="<?php echo $bb_exclude_list_placeholder;?>">
											</div>
										</div>
										<div id="ux_div_file_compression_type">
										<div class="form-group">
											<label class="control-label">
												<?php echo $bb_file_compression;?> :
												<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_file_compression_tooltip;?>" data-placement="right"></i>
												<span class="required" aria-required="true">*</span>
											</label>
											<select name="ux_ddl_file_compression_type" id="ux_ddl_file_compression_type" class="form-control" onchange="file_compression_backup_bank();">
												<option value=".tar">.tar</option>
												<option value=".tar.gz" <?php echo !extension_loaded("zlib") ? "disabled = disabled style=color:#FF0000" : "" ; ?> > <?php echo extension_loaded("zlib") ? ".tar.gz" :  ".tar.gz".$bb_extention_not_found; ?></option>
												<option value=".tar.bz2" disabled = "disabled" style="color:#FF0000"> .tar.bz2<?php echo  " " . $bb_premium_edition ?></option>
												<option value=".zip">.zip</option>
											</select>
										</div>
									</div>
									<div id="ux_div_db_compression_type">
										<div class="form-group">
											<label class="control-label">
												<?php echo $bb_db_compression;?> :
												<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_db_compression_tooltip;?>" data-placement="right"></i>
												<span class="required" aria-required="true">*</span>
											</label>
											<select name="ux_ddl_db_compression_type" id="ux_ddl_db_compression_type" class="form-control" onchange="db_compression_backup_bank();">
												<option value=".sql">.sql</option>
												<option value=".sql.gz" <?php echo !extension_loaded("zlib") ? "disabled = disabled style=color:#FF0000" : ""; ?> > <?php echo extension_loaded("zlib") ? ".sql.gz" : ".sql.gz".$bb_extention_not_found; ?></option>
												<option value=".sql.bz2" disabled = "disabled" style="color:#FF0000" >.sql.bz2<?php echo  " " . $bb_premium_edition ?></option>
												<option value=".sql.zip">.sql.zip</option>
											</select>
										</div>
									</div>
										<div id="ux_div_backup_tables">
											<div class="form-group">
												<label class="control-label">
													<?php echo $bb_backup_tables;?> :
													<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_backup_tables_tooltip;?>" data-placement="right"></i>
													<span class="required" aria-required="true">*</span>
												</label>
												<table class="table table-striped table-bordered table-hover table-margin-top" id="ux_tbl_database_tables">
													<thead>
														<tr>
															<th style="width: 5%;text-align:center;">
																<input type="checkbox" id="ux_chk_select_all_first" value="0" checked="checked" name="ux_chk_select_all_first">
															</th>
															<th>
																<?php echo $bb_table_names;?>
															</th>
														</tr>
													</thead>
													<tbody>
														<?php
														if(is_array($result))
														{
															for($flag = 0; $flag < count($result); $flag++)
															{
																if($flag % 2 == 0)
																{
																	?>
																	<tr>
																		<td style="text-align:center;">
																			<input type="checkbox" class="all_check_backup_tables" id="ux_chk_add_new_backup_db_<?php echo $flag;?>" name="ux_chk_add_new_backup_db[]" checked="checked" value="<?php echo $result[$flag] ;?>">
																		</td>
																		<td class="custom-manual-td">
																			<label style="font-size:13px;"><?php echo $result[$flag] ;?></label>
																		</td>
																	<?php
																}
																else
																{
																	?>
																		<td style="text-align:center;">
																			<input type="checkbox"  class="all_check_backup_tables" checked="checked" id="ux_chk_add_new_backup_db_<?php echo $flag;?>" name="ux_chk_add_new_backup_db[]" value="<?php echo $result[$flag] ;?>">
																		</td>
																		<td class="custom-manual-td">
																			<label style="font-size:13px;"><?php echo $result[$flag];?></label>
																		</td>
																	</tr>
																	<?php
																}
																if($flag == count($result) - 1 && $flag % 2 == 0)
																{
																	?>
																	<td style="width: 5%;text-align:center;">
																	</td>
																	<td class="custom-manual-td">
																		<label></label>
																	</td>
																	<?php
																}
															}
														}
		 												?>
													</tbody>
												</table>
											</div>
										</div>
										<div id="ux_div_archive_name">
											<div class="form-group">
												<label class="control-label">
													<?php echo $bb_archive_name;?> :
													<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_archive_name_tooltip;?>" data-placement="right"></i>
													<span class="required" aria-required="true">*</span>
												</label>
												<input type="text" name="ux_txt_archive_name"  class="form-control" id="ux_txt_archive_name" placeholder="<?php echo $bb_archive_name_placeholder ;?>" value="<?php echo "backup_%Y-%m-%d_%H-%i-%s";?>">
											</div>
											<div class="form-group" style="margin-top:5px;">
												<label style="vertical-align: top;">
													<?php echo $bb_preview ;?> :
												</label>
												<span class="archive-span">
													<span id="archivename"></span>
													<span id="archive_ext" style="margin-left: -3px;"></span>
													<span id="archive_name_hidden" hidden></span>
												</span>
											</div>
										</div>
										<div id="ux_div_backup_destination">
											<div class="form-group">
												<label class="control-label">
													<?php echo $bb_backup_destination;?> :
													<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_backup_destination_tooltip;?>" data-placement="right"></i>
													<span class="required" aria-required="true">*</span>
												</label>
												<select name="ux_ddl_backup_destination_type" id="ux_ddl_backup_destination_type" class="form-control" onchange="backup_destination_backup_bank();">
													<option value="local_folder"><?php echo $bb_local_folder; ?></option>
													<option value="amazons3" disabled = "disabled" style="color:#FF0000"><?php echo $bb_amazons3_not_configured; ?></option>
													<option value="dropbox" disabled = "disabled" style="color:#FF0000"><?php echo $bb_dropbox_not_configured; ?></option>
													<option value="email" <?php echo $settings_data_array["backup_to_email"] != "enable" ? "disabled = disabled style=color:#FF0000" : "" ?>><?php echo $settings_data_array["backup_to_email"] != "enable" ? $bb_email_not_configured : $bb_email; ?></option>
													<option value="ftp" <?php echo $settings_data_array["backup_to_ftp"] != "enable" ? "disabled = disabled style=color:#FF0000;" : "" ?>><?php echo $settings_data_array["backup_to_ftp"] != "enable" ? $bb_ftp_not_configured : $bb_ftp; ?></option>
													<option value="google_drive" disabled = "disabled" style="color:#FF0000"><?php echo $bb_google_drive_not_configured ; ?></option>
													<option value="onedrive" disabled = "disabled" style="color:#FF0000"><?php echo $bb_onedrive_not_configured; ?></option>
													<option value="rackspace" disabled = "disabled" style="color:#FF0000"><?php echo $bb_rackspace_not_configured; ?></option>
													<option value="azure" disabled = "disabled" style="color:#FF0000"><?php echo $bb_azure_not_configured; ?></option>

												</select>
											</div>
										</div>
										<div id="ux_div_backup_destination_local_folder">
											<div class="form-group">
												<label class="control-label">
													<?php echo $bb_folder_location;?> :
													<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_folder_location_tooltip;?>" data-placement="right"></i>
													<span class="required" aria-required="true">*</span>
												</label>
												<div class="row">
													<div class="col-md-6">
														<input type="text" name="ux_txt_content_location" id="ux_txt_content_location" readonly="readonly" class="form-control" value="<?php echo $content_folder_destination;?>">
													</div>
													<div class="col-md-6">
														<input type="text" name="ux_txt_folder_location" class="form-control" id="ux_txt_folder_location" placeholder="<?php echo $bb_folder_location_placeholder;?>">
													</div>
												</div>
											</div>
										</div>
										<div class="line-separator"></div>
										<div class="form-actions">
											<div class="pull-right">
												<input type="submit" class="btn vivid-green ux_btn_generate_backup" name="ux_btn_generate_backup" id="ux_btn_generate_backup" value="<?php echo $bb_start_backup;?>" <?php echo $total_backups >= 5 ? "disabled=disabled" : ""; ?> >
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="ux_div_portlet_progress" style="display:none;">
			<div id="ux_div_progressbar">
				<div class="progress-bar-position">
					<div class="portlet-progress-bar">
						A Backup is on the way!
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
						Starting Backup
					</div>
					<div class="portlet-progress-message">
						<p>
							* Please do not <u>Cancel</u> or <u>Refresh</u> the Page to avoid Termination of the Backup.<br/>
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
								<?php echo $bb_start_backup; ?>
							</span>
						</li>
					</ul>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="portlet box vivid-green">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-custom-note"></i>
									<?php echo $bb_start_backup; ?>
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
