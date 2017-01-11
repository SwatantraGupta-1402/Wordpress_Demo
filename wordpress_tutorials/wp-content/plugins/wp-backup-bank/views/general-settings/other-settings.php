<?php
/**
* This Template is used for managing plugin settings.
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
		$backup_bank_other_settings = wp_create_nonce("backup_bank_other_settings");
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
								<?php echo $bb_other_settings; ?>
							</span>
						</li>
					</ul>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="portlet box vivid-green">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-custom-settings"></i>
									<?php echo $bb_other_settings; ?>
								</div>
							</div>
							<div class="portlet-body form">
								<form id="ux_frm_other_settings">
									<div class="form-body">
										<div class="note note-danger">
											<h4 class="block">
												<?php echo $bb_important_disclaimer; ?>
											</h4>
											<ul>
												<li><?php echo $bb_backup_bank_demos_disclaimer; ?><a href="<?php echo "http://beta.tech-banker.com/products/wp-backup-bank/demos/";?>" target="_blank" class='custom_links'><?php echo $bb_backup_bank_click_here; ?></a>.</li>
												<li><?php echo $bb_backup_bank_user_guide_disclaimer; ?><a href="<?php echo "http://beta.tech-banker.com/products/wp-backup-bank/user-guide/general-settings/other-settings/";?>" target="_blank" class='custom_links'><?php echo $bb_backup_bank_click_here; ?></a>.</li>
												<?php
												if(isset($extension_not_found) && count($extension_not_found) > 0)
												{
												?>
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
												<?php echo $bb_other_setting_automatic_plugin_update;?> :
												<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_other_setting_automatic_plugin_update_tooltip;?>" data-placement="right"></i>
												<span class="required" aria-required="true">*</span>
											</label>
											<select name="ux_ddl_automatic_plugin_updates" id="ux_ddl_automatic_plugin_updates" class="form-control" >
												<option value="enable"><?php echo $bb_enable; ?></option>
												<option value="disable"><?php echo $bb_disable; ?></option>
											</select>
										</div>
										<div class="form-group">
											<label class="control-label">
												<?php echo $bb_other_setting_remove_tables;?> :
												<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_other_setting_remove_tables_tootltip;?>" data-placement="right"></i>
												<span class="required" aria-required="true">*</span>
											</label>
											<select name="ux_ddl_remove_tables" id="ux_ddl_remove_tables" class="form-control" >
												<option value="enable"><?php echo $bb_enable; ?></option>
												<option value="disable"><?php echo $bb_disable; ?></option>
											</select>
										</div>
										<div class="form-group">
											<label class="control-label">
												<?php echo $bb_other_setting_maintenance_mode;?> :
												<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_other_setting_maintenance_mode_tooltip;?>" data-placement="right"></i>
												<span class="required" aria-required="true">*</span>
											</label>
											<select name="ux_ddl_maintenance_mode" id="ux_ddl_maintenance_mode" class="form-control">
												<option value="enable"><?php echo $bb_enable; ?></option>
												<option value="disable"><?php echo $bb_disable; ?></option>
											</select>
										</div>
										<div id="ux_txt_maintenance_mode">
											<div class="form-group">
												<label class="control-label">
													<?php echo $bb_other_setting_maintenance_mode_message;?> :
													<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_other_setting_maintenance_mode_message_tooltip;?>" data-placement="right"></i>
													<span class="required" aria-required="true">*</span>
												</label>
												<textarea name="ux_txt_maintenance_mode_message" id="ux_txt_maintenance_mode_message" class="form-control" placeholder="<?php echo $bb_other_setting_maintenance_mode_message_placeholder;?>"><?php echo isset($bb_other_settings_maintenance_mode_data["message_when_restore"]) ? esc_html($bb_other_settings_maintenance_mode_data["message_when_restore"]) : "" ?> </textarea>
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
								<?php echo $bb_other_settings; ?>
							</span>
						</li>
					</ul>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="portlet box vivid-green">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-custom-settings"></i>
									<?php echo $bb_other_settings; ?>
								</div>
							</div>
							<div class="portlet-body form">
								<div class="form-body">
									<strong><?php echo $bb_user_access_message;?></strong>
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
