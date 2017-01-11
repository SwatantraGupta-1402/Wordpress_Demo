<?php
/**
* This Template is used for Scheduling backups.
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
	else if(schedule_backup_bank == "1")
	{
		$backup_bank_schedule_backup = wp_create_nonce("backup_bank_schedule_backup");
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
								<?php echo $bb_schedule_backup ; ?>
							</span>
						</li>
					</ul>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="portlet box vivid-green">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-custom-hourglass"></i>
									<?php echo $bb_schedule_backup ; ?>
								</div>
							</div>
							<div class="portlet-body form">
								<form id="ux_frm_schedule_backup">
									<div class="form-body">
										<div class="note note-danger">
											<h4 class="block">
												<?php echo $bb_premium_edition_features_disclaimer; ?>
											</h4>
											<ul>
												<li><?php echo $bb_backup_bank_scheduler_disclaimer; ?></li>
											</ul>
											<ul>
												<li><?php echo $bb_backup_bank_demos_disclaimer; ?><a href="<?php echo "http://beta.tech-banker.com/products/wp-backup-bank/demos/";?>" target="_blank" class='custom_links'><?php echo $bb_backup_bank_click_here; ?></a>.</li>
												<li><?php echo $bb_backup_bank_user_guide_disclaimer; ?><a href="<?php echo "http://beta.tech-banker.com/products/wp-backup-bank/user-guide/backups-restore/schedule-backup/";?>" target="_blank" class='custom_links'><?php echo $bb_backup_bank_click_here; ?></a>.</li>
												<li> <?php echo $bb_click; ?><a href="<?php echo "http://beta.tech-banker.com/products/wp-backup-bank/";?>" target="_blank" class='custom_links'><?php echo $bb_backup_bank_click_here; ?></a> <?php echo $bb_backup_bank_premium_disclaimer; ?>.</li>
											</ul>
											<ul>
												<?php
												if(isset($extension_not_found) && count($extension_not_found) > 0)
												{
												?>
												<h4 class="block">
													<?php echo $bb_important_disclaimer; ?>
												</h4>
													<li><?php echo $bb_contact_to_host; ?></li>
													<?php
													foreach($extension_not_found as $extension)
													{
														?>
															<li>* <?php echo $extension; ?></li>
														<?php
													}
												}
												?>
											</ul>
										</div>
										<div class="form-actions">
											<div class="pull-right">
												<input type="submit" class="btn vivid-green ux_btn_generate_backup" name="ux_btn_generate_backup" id="ux_btn_generate_backup" value="<?php echo $bb_schedule_backup;?>">
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
													<input type="text" name="ux_txt_backup_name" id="ux_txt_backup_name" class="form-control" value="Backup From WP Backup Bank" placeholder="<?php echo $bb_backup_name_placeholder ;?>">
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
														<option value="complete_backup" ><?php echo $bb_complete_backup; ?></option>
														<option value="only_database" selected="selected"  ><?php echo $bb_only_database; ?></option>
														<option value="only_filesystem" ><?php echo $bb_only_filesystem; ?></option>
														<option value="only_plugins_and_themes" ><?php echo $bb_only_plugins_and_themes; ?></option>
														<option value="only_themes" ><?php echo $bb_only_themes; ?></option>
														<option value="only_plugins" ><?php echo $bb_only_plugins; ?></option>
														<option value="only_wp_content_folder" ><?php echo $bb_wp_content_folder; ?></option>
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
												<input type="text" class="form-control" name="ux_txt_exclude_list" id="ux_txt_exclude_list" value=".svn-base, .git, .ds_store" placeholder="<?php echo $bb_exclude_list_placeholder;?>">
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
												<option value=".tar.gz" disabled = "disabled" > .tar.gz</option>
												<option value=".tar.bz2" disabled = "disabled" > .tar.bz2</option>
												<option value=".zip"  disabled = "disabled" >.zip</option>
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
												<option value=".sql.gz" disabled = "disabled" >.sql.gz</option>
												<option value=".sql.bz2" disabled = "disabled" >.sql.bz2</option>
												<option value=".sql.zip" disabled = "disabled" >.sql.zip</option>
											</select>
										</div>
									</div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">
														<?php echo $bb_schedule_backup_start_on;?> :
														<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_schedule_backup_start_on_tooltip;?>" data-placement="right"></i>
														<span class="required" aria-required="true">*</span>
													</label>
													<input name="ux_txt_start_on" id="ux_txt_start_on" type="text" class="form-control" placeholder="<?php echo $bb_schedule_backup_start_on_placeholder;?>" value="<?php echo date("m/d/Y");?>">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">
														<?php echo $bb_schedule_backup_start_time; ?> :
														<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_schedule_backup_start_time_tooltip;?>" data-placement="right"></i>
														<span class="required" aria-required="true">*</span>
													</label>
													<div class="input-icon right">
														<select class="form-control custom-input-medium input-inline" name="ux_ddl_start_hours" id="ux_ddl_start_hours">
															<?php
																for($flag = 0; $flag < 24; $flag++)
																{
																	if($flag < 10)
																	{
																		?>
																		<option value="<?php echo $flag * 60 * 60;?>">0<?php echo $flag;?><?php echo $bb_schedule_backup_hrs; ?></option>
																		<?php
																	}
																	else
																	{
																		?>
																		<option value="<?php echo $flag * 60 * 60;?>"><?php echo $flag;?><?php echo $bb_schedule_backup_hrs; ?></option>
																		<?php
																	}
																}
															?>
														</select>
														<select class="form-control custom-input-medium input-inline" name="ux_ddl_start_minutes" id="ux_ddl_start_minutes">
															<?php
																for($flag = 0; $flag < 60; $flag++)
																{
																	if($flag < 10)
																	{
																		?>
																		<option value="<?php echo $flag * 60;?>">0<?php echo $flag;?><?php echo $bb_schedule_backup_mins; ?></option>
																		<?php
																	}
																	else
																	{
																		?>
																		<option value="<?php echo $flag * 60;?>"><?php echo $flag;?><?php echo $bb_schedule_backup_mins; ?></option>
																		<?php
																	}
																}
															?>
														</select>
													</div>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label">
												<?php echo $bb_schedule_backup_duration;?> :
												<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_schedule_backup_duration_tooltip;?>" data-placement="right"></i>
												<span class="required" aria-required="true">*</span>
											</label>
											<div class="input-icon right">
												<select name="ux_ddl_duration" id="ux_ddl_duration" class="form-control" onchange="change_duration_backup_bank();">
													<option value="Hourly"><?php echo $bb_schedule_backup_hourly;?></option>
													<option value="Daily"><?php echo $bb_schedule_backup_daily;?></option>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label">
												<?php echo $bb_schedule_backup_end_on;?> :
												<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_schedule_backup_end_on_tooltip;?>" data-placement="right"></i>
												<span class="required" aria-required="true">*</span>
											</label>
											<select class="form-control" onchange="change_end_time_backup_bank();" name="ux_ddl_end_time" id="ux_ddl_end_time">
												<option value="on"><?php echo $bb_schedule_backup_end_time_on;?></option>
												<option value="never" selected="selected"><?php echo $bb_never;?></option>
											</select>
										</div>
										<div id="ux_div_end_date">
											<div class="form-group">
												<label class="control-label">
													<?php echo $bb_schedule_backup_end_date;?> :
													<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_schedule_backup_end_date_tooltip;?>" data-placement="right"></i>
													<span class="required" aria-required="true">*</span>
												</label>
												<input name="ux_txt_schedule_end_date" id="ux_txt_schedule_end_date" type="text" class="form-control" placeholder="<?php echo $bb_schedule_backup_end_date_placeholder;?>">
											</div>
										</div>
										<div id="ux_div_repeat_every">
											<div class="form-group">
												<label class="control-label">
													<?php echo $bb_schedule_backup_repeat_every;?> :
													<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_schedule_backup_repeat_every_tooltip;?>" data-placement="right"></i>
													<span class="required" aria-required="true">*</span>
												</label>
												<select class="form-control" name="ux_ddl_repeat_every" id="ux_ddl_repeat_every">
													<?php
														for($flag = 1; $flag < 24; $flag++)
														{
															if($flag < 10)
															{
																if($flag == "4")
																{
																	?>
																	<option selected="selected" value="<?php echo $flag ."Hour";?>">0<?php echo $flag;?><?php echo $bb_schedule_backup_hrs; ?></option>
																	<?php
																}
																else
																{
																	?>
																	<option value="<?php echo $flag."Hour";?>">0<?php echo $flag;?><?php echo $bb_schedule_backup_hrs; ?></option>
																	<?php
																}
															}
															else
															{
																?>
																<option value="<?php echo $flag."Hour";?>"><?php echo $flag;?><?php echo $bb_schedule_backup_hrs; ?></option>
																<?php
															}
														}
													?>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label">
												<?php echo $bb_schedule_backup_time_zone;?> :
												<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_schedule_backup_time_zone_tooltip;?>" data-placement="right"></i>
												<span class="required" aria-required="true">*</span>
											</label>
											<div class="input-icon right">
												<select class="form-control" name="ux_ddl_time_zone" id="ux_ddl_time_zone">
													<option value="Pacific/Midway">(UTC-11:00) Midway Island</option>
													<option value="Pacific/Samoa">(UTC-11:00) Samoa</option>
													<option value="Pacific/Honolulu">(UTC-10:00) Hawaii</option>
													<option value="US/Alaska">(UTC-09:00) Alaska</option>
													<option value="America/Los_Angeles">(UTC-08:00) Pacific Time (US &amp; Canada)</option>
													<option value="America/Tijuana">(UTC-08:00) Tijuana</option>
													<option value="US/Arizona">(UTC-07:00) Arizona</option>
													<option value="America/Chihuahua">(UTC-07:00) Chihuahua, Mexico</option>
													<option value="America/Mazatlan">(UTC-07:00) Mazatlan</option>
													<option value="US/Mountain">(UTC-07:00) Mountain Time (US &amp; Canada)</option>
													<option value="America/Managua">(UTC-06:00) Central America</option>
													<option value="US/Central">(UTC-06:00) Central Time (US &amp; Canada)</option>
													<option value="America/Mexico_City">(UTC-06:00) Guadalajara, Mexico City</option>
													<option value="America/Monterrey">(UTC-06:00) Monterrey</option>
													<option value="Canada/Saskatchewan">(UTC-06:00) Saskatchewan</option>
													<option value="America/Bogota">(UTC-05:00) Bogota</option>
													<option value="US/Eastern">(UTC-05:00) Eastern Time (US &amp; Canada)</option>
													<option value="US/East-Indiana">(UTC-05:00) Indiana (East)</option>
													<option value="America/Lima">(UTC-05:00) Lima</option>
													<option value="America/Bogota">(UTC-05:00) Quito</option>
													<option value="Canada/Atlantic">(UTC-04:00) Atlantic Time (Canada)</option>
													<option value="America/Caracas">(UTC-04:30) Caracas</option>
													<option value="America/La_Paz">(UTC-04:00) La Paz</option>
													<option value="America/Santiago">(UTC-04:00) Santiago</option>
													<option value="Canada/Newfoundland">(UTC-03:30) Newfoundland</option>
													<option value="America/Sao_Paulo">(UTC-03:00) Brasilia</option>
													<option value="America/Argentina/Buenos_Aires">(UTC-03:00) Buenos Aires, Georgetown</option>
													<option value="America/Godthab">(UTC-03:00) Greenland</option>
													<option value="America/Noronha">(UTC-02:00) Mid-Atlantic</option>
													<option value="Atlantic/Azores">(UTC-01:00) Azores</option>
													<option value="Atlantic/Cape_Verde">(UTC-01:00) Cape Verde Is.</option>
													<option value="Africa/Casablanca">(UTC+00:00) Casablanca</option>
													<option value="Europe/London">(UTC+00:00) Edinburgh</option>
													<option value="Etc/Greenwich" selected="selected">(UTC+00:00) Greenwich Mean Time : Dublin</option>
													<option value="Europe/Lisbon">(UTC+00:00) Lisbon</option>
													<option value="Europe/London">(UTC+00:00) London</option>
													<option value="Africa/Monrovia">(UTC+00:00) Monrovia</option>
													<option value="UTC">(UTC+00:00) UTC</option>
													<option value="Europe/Amsterdam">(UTC+01:00) Amsterdam</option>
													<option value="Europe/Belgrade">(UTC+01:00) Belgrade</option>
													<option value="Europe/Berlin">(UTC+01:00) Berlin, Bern</option>
													<option value="Europe/Bratislava">(UTC+01:00) Bratislava</option>
													<option value="Europe/Brussels">(UTC+01:00) Brussels</option>
													<option value="Europe/Budapest">(UTC+01:00) Budapest</option>
													<option value="Europe/Copenhagen">(UTC+01:00) Copenhagen</option>
													<option value="Europe/Ljubljana">(UTC+01:00) Ljubljana</option>
													<option value="Europe/Madrid">(UTC+01:00) Madrid</option>
													<option value="Europe/Paris">(UTC+01:00) Paris</option>
													<option value="Europe/Prague">(UTC+01:00) Prague</option>
													<option value="Europe/Rome">(UTC+01:00) Rome</option>
													<option value="Europe/Sarajevo">(UTC+01:00) Sarajevo</option>
													<option value="Europe/Skopje">(UTC+01:00) Skopje</option>
													<option value="Europe/Stockholm">(UTC+01:00) Stockholm</option>
													<option value="Europe/Vienna">(UTC+01:00) Vienna</option>
													<option value="Europe/Warsaw">(UTC+01:00) Warsaw</option>
													<option value="Africa/Lagos">(UTC+01:00) West Central Africa</option>
													<option value="Europe/Zagreb">(UTC+01:00) Zagreb</option>
													<option value="Europe/Athens">(UTC+02:00) Athens</option>
													<option value="Europe/Bucharest">(UTC+02:00) Bucharest</option>
													<option value="Africa/Cairo">(UTC+02:00) Cairo</option>
													<option value="Africa/Harare">(UTC+02:00) Harare</option>
													<option value="Europe/Helsinki">(UTC+02:00) Helsinki</option>
													<option value="Europe/Istanbul">(UTC+02:00) Istanbul</option>
													<option value="Asia/Jerusalem">(UTC+02:00) Jerusalem</option>
													<option value="Europe/Helsinki">(UTC+02:00) Kyiv</option>
													<option value="Africa/Johannesburg">(UTC+02:00) Pretoria</option>
													<option value="Europe/Riga">(UTC+02:00) Riga</option>
													<option value="Europe/Sofia">(UTC+02:00) Sofia</option>
													<option value="Europe/Tallinn">(UTC+02:00) Tallinn</option>
													<option value="Europe/Vilnius">(UTC+02:00) Vilnius</option>
													<option value="Asia/Baghdad">(UTC+03:00) Baghdad</option>
													<option value="Asia/Kuwait">(UTC+03:00) Kuwait</option>
													<option value="Europe/Minsk">(UTC+03:00) Minsk</option>
													<option value="Africa/Nairobi">(UTC+03:00) Nairobi</option>
													<option value="Asia/Riyadh">(UTC+03:00) Riyadh</option>
													<option value="Europe/Volgograd">(UTC+03:00) Volgograd</option>
													<option value="Asia/Tehran">(UTC+03:30) Tehran</option>
													<option value="Asia/Muscat">(UTC+04:00) Abu Dhabi</option>
													<option value="Asia/Baku">(UTC+04:00) Baku</option>
													<option value="Europe/Moscow">(UTC+04:00) Moscow, St. Petersburg</option>
													<option value="Asia/Muscat">(UTC+04:00) Muscat</option>
													<option value="Asia/Tbilisi">(UTC+04:00) Tbilisi</option>
													<option value="Asia/Yerevan">(UTC+04:00) Yerevan</option>
													<option value="Asia/Kabul">(UTC+04:30) Kabul</option>
													<option value="Asia/Karachi">(UTC+05:00) Islamabad,Karachi</option>
													<option value="Asia/Tashkent">(UTC+05:00) Tashkent</option>
													<option value="Asia/Calcutta">(UTC+05:30) Chennai, Mumbai, New Delhi, Sri Jayawardenepura</option>
													<option value="Asia/Kolkata">(UTC+05:30) Kolkata</option>
													<option value="Asia/Katmandu">(UTC+05:45) Kathmandu</option>
													<option value="Asia/Almaty">(UTC+06:00) Almaty</option>
													<option value="Asia/Dhaka">(UTC+06:00) Astana,Dhaka</option>
													<option value="Asia/Yekaterinburg">(UTC+06:00) Ekaterinburg</option>
													<option value="Asia/Rangoon">(UTC+06:30) Rangoon</option>
													<option value="Asia/Bangkok">(UTC+07:00) Bangkok, Hanoi</option>
													<option value="Asia/Jakarta">(UTC+07:00) Jakarta</option>
													<option value="Asia/Novosibirsk">(UTC+07:00) Novosibirsk</option>
													<option value="Asia/Hong_Kong">(UTC+08:00) Beijing, Hong Kong</option>
													<option value="Asia/Chongqing">(UTC+08:00) Chongqing</option>
													<option value="Asia/Krasnoyarsk">(UTC+08:00) Krasnoyarsk</option>
													<option value="Asia/Kuala_Lumpur">(UTC+08:00) Kuala Lumpur</option>
													<option value="Australia/Perth">(UTC+08:00) Perth</option>
													<option value="Asia/Singapore">(UTC+08:00) Singapore</option>
													<option value="Asia/Taipei">(UTC+08:00) Taipei</option>
													<option value="Asia/Ulan_Bator">(UTC+08:00) Ulaan Bataar</option>
													<option value="Asia/Urumqi">(UTC+08:00) Urumqi</option>
													<option value="Asia/Irkutsk">(UTC+09:00) Irkutsk</option>
													<option value="Asia/Tokyo">(UTC+09:00) Osaka,Sapporo, Tokyo</option>
													<option value="Asia/Seoul">(UTC+09:00) Seoul</option>
													<option value="Australia/Adelaide">(UTC+09:30) Adelaide</option>
													<option value="Australia/Darwin">(UTC+09:30) Darwin</option>
													<option value="Australia/Brisbane">(UTC+10:00) Brisbane</option>
													<option value="Australia/Canberra">(UTC+10:00) Canberra</option>
													<option value="Pacific/Guam">(UTC+10:00) Guam</option>
													<option value="Australia/Hobart">(UTC+10:00) Hobart</option>
													<option value="Australia/Melbourne">(UTC+10:00) Melbourne</option>
													<option value="Pacific/Port_Moresby">(UTC+10:00) Port Moresby</option>
													<option value="Australia/Sydney">(UTC+10:00) Sydney</option>
													<option value="Asia/Yakutsk">(UTC+10:00) Yakutsk</option>
													<option value="Asia/Vladivostok">(UTC+11:00) Vladivostok</option>
													<option value="Pacific/Auckland">(UTC+12:00) Auckland</option>
													<option value="Pacific/Fiji">(UTC+12:00) Fiji</option>
													<option value="Pacific/Kwajalein">(UTC+12:00) International Date Line West</option>
													<option value="Asia/Kamchatka">(UTC+12:00) Kamchatka</option>
													<option value="Asia/Magadan">(UTC+12:00) Magadan</option>
													<option value="Pacific/Marshall">(UTC+12:00) Marshall Is.</option>
													<option value="Asia/Caledonia">(UTC+12:00) New Caledonia, Solomon Is.</option>
													<option value="Pacific/Wellington">(UTC+12:00) Wellington</option>
													<option value="Pacific/Tongatapu">(UTC+13:00) Nuku'alofa</option>
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
												<table class="table table-striped table-bordered table-hover table-margin-top" id="ux_tbl_database_schedule_backup">
													<thead>
														<tr>
															<th style="width: 5%;text-align:center;">
																<input type="checkbox" id="ux_chk_select_all_first" value="0" checked="checked" name="ux_chk_select_all_first" >
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
																			<input type="checkbox" class="all_check_backup_tables" checked="checked" id="ux_chk_add_schedule_backup_db_<?php echo $flag;?>" name="ux_chk_add_new_backup_db[]" value="<?php echo $result[$flag] ;?>">
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
																			<input type="checkbox"  class="all_check_backup_tables" checked="checked" id="ux_chk_add_schedule_backup_db_<?php echo $flag;?>" name="ux_chk_add_new_backup_db[]" value="<?php echo $result[$flag] ;?>">
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
															$flag++;
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
												<input type="text" name="ux_txt_archive_name"  class="form-control" id="ux_txt_archive_name" value="<?php echo "backup_%Y-%m-%d_%H-%i-%s";?>" placeholder="<?php echo $bb_archive_name_placeholder;?>">
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
													<option value="amazons3" disabled = "disabled" ><?php echo $bb_amazons3; ?></option>
													<option value="dropbox" disabled = "disabled" ><?php echo $bb_dropbox; ?></option>
													<option value="email" disabled = "disabled" ><?php echo $bb_email;  ?></option>
													<option value="ftp" disabled = "disabled" ><?php echo $bb_ftp; ?></option>
													<option value="google_drive" disabled = "disabled" ><?php echo $bb_google_drive_settings ; ?></option>
													<option value="onedrive" disabled = "disabled" ><?php echo $bb_onedrive; ?></option>
													<option value="rackspace" disabled = "disabled" ><?php echo $bb_rackspace; ?></option>
													<option value="azure" disabled = "disabled" ><?php echo $bb_ms_azure; ?></option>
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
												<input type="submit" class="btn vivid-green ux_btn_generate_backup" name="ux_btn_generate_backup" id="ux_btn_generate_backup" value="<?php echo $bb_schedule_backup;?>">
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
								<?php echo $bb_schedule_backup; ?>
							</span>
						</li>
					</ul>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="portlet box vivid-green">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-custom-hourglass"></i>
									<?php echo $bb_schedule_backup; ?>
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
