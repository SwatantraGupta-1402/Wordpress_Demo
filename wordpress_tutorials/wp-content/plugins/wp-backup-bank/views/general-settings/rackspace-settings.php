<?php
/**
* This Template is used for displaying rackspace settings.
*
* @author  Tech Banker
* @package wp-backup-bank/views/general-settings
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
	else if(general_settings_backup_bank == "1")
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
							<a href="admin.php?page=bb_alert_setup">
								<?php echo $bb_general_settings; ?>
							</a>
							<span>></span>
						</li>
						<li>
							<span>
								<?php echo $bb_rackspace_settings; ?>
							</span>
						</li>
					</ul>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="portlet box vivid-green">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-custom-rocket"></i>
									<?php echo $bb_rackspace_settings; ?>
								</div>
							</div>
							<div class="portlet-body form">
								<form id="ux_frm_rackspace">
									<div class="form-body">
										<div class="note note-danger">
											<h4 class="block">
												<?php echo $bb_premium_edition_features_disclaimer; ?>
											</h4>
											<ul>
												<li> <?php echo $bb_backup_bank_rackspace_settings; ?> </li>
											</ul>
											<ul>
												<li><?php echo $bb_backup_bank_demos_disclaimer; ?><a href="<?php echo "http://beta.tech-banker.com/products/wp-backup-bank/demos/";?>" target="_blank" class='custom_links'><?php echo $bb_backup_bank_click_here; ?></a>.</li>
												<li><?php echo $bb_backup_bank_user_guide_disclaimer; ?><a href="<?php echo "http://beta.tech-banker.com/products/wp-backup-bank/user-guide/general-settings/rackspace-settings/";?>" target="_blank" class='custom_links'><?php echo $bb_backup_bank_click_here; ?></a>.</li>
												<li> <?php echo $bb_click; ?><a href="<?php echo "http://beta.tech-banker.com/products/wp-backup-bank/";?>" target="_blank" class='custom_links'><?php echo $bb_backup_bank_click_here; ?></a> <?php echo $bb_backup_bank_premium_disclaimer; ?>.</li>
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
										<div class="form-group">
											<label class="control-label">
												<?php echo $bb_rackspace_backup_to;?> :
												<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_rackspace_tooltip;?>" data-placement="right"></i>
												<span class="required" aria-required="true">*</span>
											</label>
											<select name="ux_ddl_rackspace_enable_disable" id="ux_ddl_rackspace_enable_disable" class="form-control">
												<option value="enable"><?php echo $bb_enable; ?></option>
												<option value="disable"><?php echo $bb_disable; ?></option>
											</select>
										</div>
										<div id="ux_div_rackspace">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label">
															<?php echo $bb_rackspace_username;?> (<a href="https://mycloud.rackspace.com/" id="ux_rackspace_link"  target="_blank"><?php echo $bb_rackspace_get_credentials; ?></a>) :
															<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_rackspace_username_tooltip; ?>" data-placement="right"></i>
															<span class="required" aria-required="true">*</span>
														</label>
														<input name="ux_txt_rackspace_username" id="ux_txt_rackspace_username" type="text" class="form-control" placeholder="<?php echo $bb_rackspace_username_placeholder;?>" value="<?php echo isset($rackspace_settings_data_array["username"]) ? esc_html($rackspace_settings_data_array["username"]) : ""; ?>">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label">
															<?php echo $bb_rackspace_api_key;?> :
															<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_rackspace_api_key_tooltip; ?>" data-placement="right"></i>
															<span class="required" aria-required="true">*</span>
														</label>
														<input name="ux_txt_rackspace_api_key" id="ux_txt_rackspace_api_key" type="text" class="form-control" placeholder="<?php echo $bb_rackspace_api_key_placeholder; ?>" value="<?php echo isset($rackspace_settings_data_array["api_key"]) ? esc_html($rackspace_settings_data_array["api_key"]) : ""; ?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label">
															<?php echo $bb_rackspace_container;?> :
															<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_rackspace_container_tooltip; ?>" data-placement="right"></i>
															<span class="required" aria-required="true">*</span>
														</label>
														<input name="ux_txt_rackspace_container" id="ux_txt_rackspace_container" type="text" class="form-control" placeholder="<?php echo $bb_rackspace_container_placeholder; ?>" value="<?php echo isset($rackspace_settings_data_array["container"]) ? esc_html($rackspace_settings_data_array["container"]) : ""; ?>">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label">
															<?php echo $bb_rackspace_region;?> :
															<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_rackspace_region_tooltip; ?>" data-placement="right"></i>
															<span class="required" aria-required="true">*</span>
														</label>
														<select name="ux_ddl_rackspace_region" id="ux_ddl_rackspace_region" class="form-control">
															<option value="DFW"><?php echo $bb_rackspace_region_dfw; ?></option>
															<option value="ORD"><?php echo $bb_rackspace_region_ord; ?></option>
															<option value="LON"><?php echo $bb_rackspace_region_lon; ?></option>
															<option value="IAD"><?php echo $bb_rackspace_region_iad; ?></option>
															<option value="HKG"><?php echo $bb_rackspace_region_hkg; ?></option>
															<option value="SYD"><?php echo $bb_rackspace_region_syd; ?></option>
														</select>
													</div>
												</div>
											</div>
										</div>
										<div class="line-separator"></div>
										<div class="form-actions">
											<div class="pull-right">
												<input type="submit" class="btn vivid-green" name="ux_btn_save_changes" id="ux_btn_save_changes" value="<?php echo $bb_save_changes;?>">
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
							<a href="admin.php?page=bb_alert_setup">
								<?php echo $bb_general_settings; ?>
							</a>
							<span>></span>
						</li>
						<li>
							<span>
								<?php echo $bb_rackspace_settings; ?>
							</span>
						</li>
					</ul>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="portlet box vivid-green">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-custom-rocket"></i>
									<?php echo $bb_rackspace_settings; ?>
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
