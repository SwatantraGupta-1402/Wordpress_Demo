<?php
/**
* This Template is used for displaying onedrive settings.
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
		$onedrive_redirect_url = admin_url("admin-ajax.php");
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
								<?php echo $bb_onedrive_settings; ?>
							</span>
						</li>
					</ul>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="portlet box vivid-green">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-custom-cloud-upload"></i>
									<?php echo $bb_onedrive_settings; ?>
								</div>
							</div>
							<div class="portlet-body form">
								<form id="ux_frm_onedrive">
									<div class="form-body">
										<div class="note note-danger">
											<h4 class="block">
												<?php echo $bb_premium_edition_features_disclaimer; ?>
											</h4>
											<ul>
												<li> <?php echo $bb_backup_bank_onedrive_settings; ?> </li>
											</ul>
											<ul>
												<li><?php echo $bb_backup_bank_demos_disclaimer; ?><a href="<?php echo "http://beta.tech-banker.com/products/wp-backup-bank/demos/";?>" target="_blank" class='custom_links'><?php echo $bb_backup_bank_click_here; ?></a>.</li>
												<li><?php echo $bb_backup_bank_user_guide_disclaimer; ?><a href="<?php echo "http://beta.tech-banker.com/products/wp-backup-bank/user-guide/general-settings/onedrive-settings/";?>" target="_blank" class='custom_links'><?php echo $bb_backup_bank_click_here; ?></a>.</li>
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
										<div class="form-group">
											<label class="control-label">
												<?php echo $bb_onedrive_backup_to;?> (<a href="https://account.live.com/developers/applications/create" id="ux_onedrive_apps_link"  target="_blank"><?php echo $bb_onedrive_skd_application; ?></a>) :
												<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_onedrive_tooltip;?>" data-placement="right"></i>
												<span class="required" aria-required="true">*</span>
											</label>
											<select name="ux_ddl_onedrive_settings_enable_disable" id="ux_ddl_onedrive_settings_enable_disable" class="form-control">
												<option value="enable"><?php echo $bb_enable; ?></option>
												<option value="disable"><?php echo $bb_disable; ?></option>
											</select>
										</div>
										<div id="ux_div_onedrive">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label">
															<?php echo $bb_client_id;?> (<a href="https://account.live.com/developers/applications" id="ux_onedrive_apps_link"  target="_blank"><?php echo $bb_onedrive_get_client_id; ?></a>) :
															<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_onedrive_client_id_tooltip;?>" data-placement="right"></i>
															<span class="required" aria-required="true">*</span>
														</label>
														<input name="ux_txt_onedrive_client_id" id="ux_txt_onedrive_client_id" type="text" class="form-control" placeholder="<?php echo $bb_onedrive_client_id_placeholder;?>" value="<?php echo isset($bb_onedrive_settings_unserialize_data["client_id"]) ? esc_html($bb_onedrive_settings_unserialize_data["client_id"]) : ""; ?>">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label">
															<?php echo $bb_onedrive_client_secret;?> :
															<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_onedrive_client_secret_tooltip; ?>" data-placement="right"></i>
															<span class="required" aria-required="true">*</span>
														</label>
														<input name="ux_txt_onedrive_client_secret" id="ux_txt_onedrive_client_secret" type="text" class="form-control" placeholder="<?php echo $bb_onedrive_client_secret_placeholder; ?>" value="<?php echo isset($bb_onedrive_settings_unserialize_data["client_secret"]) ? esc_html($bb_onedrive_settings_unserialize_data["client_secret"]) : ""; ?>">
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label">
													<?php echo $bb_redirect_url;?> :
													<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_redirect_url_tooltip;?>" data-placement="right"></i>
													<span class="required" aria-required="true">*</span>
												</label>
													<input type="text" name="ux_txt_content_location" id="ux_txt_content_location" readonly="readonly" class="form-control" value="<?php echo $onedrive_redirect_url;?>">
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
								<?php echo $bb_onedrive_settings; ?>
							</span>
						</li>
					</ul>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="portlet box vivid-green">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-custom-cloud-upload"></i>
									<?php echo $bb_onedrive_settings; ?>
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
