<?php
/**
* This Template is used for managing email settings.
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
	else if(general_settings_backup_bank == "1")
	{
		$backup_bank_alert_setup = wp_create_nonce("backup_bank_alert_setup");
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
								<?php echo $bb_alert_setup; ?>
							</span>
						</li>
					</ul>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="portlet box vivid-green">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-custom-bell"></i>
									<?php echo $bb_alert_setup; ?>
								</div>
							</div>
							<div class="portlet-body form">
								<form id="ux_frm_alert_setup">
									<div class="form-body">
										<div class="note note-danger">
											<h4 class="block">
												<?php echo $bb_important_disclaimer; ?>
											</h4>
											<ul>
												<li><?php echo $bb_backup_bank_demos_disclaimer; ?><a href="<?php echo "http://beta.tech-banker.com/products/wp-backup-bank/demos/";?>" target="_blank" class='custom_links'><?php echo $bb_backup_bank_click_here; ?></a>.</li>
												<li><?php echo $bb_backup_bank_user_guide_disclaimer; ?><a href="<?php echo "http://beta.tech-banker.com/products/wp-backup-bank/user-guide/general-settings/alert-setup/";?>" target="_blank" class='custom_links'><?php echo $bb_backup_bank_click_here; ?></a>.</li>
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
												<?php echo $bb_email_for_generated_backup;?> :
												<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_alert_setup_email_template_for_generated_backup_tooltip;?>" data-placement="right"></i>
												<span class="required" aria-required="true">*</span>
											</label>
											<select name="ux_ddl_backup_generated_successfull" id="ux_ddl_backup_generated_successfull" class="form-control" >
												<option value="enable"><?php echo $bb_enable; ?></option>
												<option value="disable"><?php echo $bb_disable; ?></option>
											</select>
										</div>
										<div class="form-group">
											<label class="control-label">
												<?php echo $bb_email_for_backup_schedule;?> :
												<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_alert_setup_email_template_for_backup_schedule_tooltip;?>" data-placement="right"></i>
												<span class="required" aria-required="true">*</span>
											</label>
											<select name="ux_ddl_backup_scheduled_successfull" id="ux_ddl_backup_scheduled_successfull" class="form-control" >
												<option value="enable"><?php echo $bb_enable; ?></option>
												<option value="disable"><?php echo $bb_disable; ?></option>
											</select>
										</div>
										<div class="form-group">
											<label class="control-label">
												<?php echo $bb_email_for_restore_successfully;?> :
												<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_alert_setup_email_template_for_restore_successfully_tooltip;?>" data-placement="right"></i>
												<span class="required" aria-required="true">*</span>
											</label>
											<select name="ux_ddl_restore_completed_successfull" id="ux_ddl_restore_completed_successfull" class="form-control" >
												<option value="enable"><?php echo $bb_enable; ?></option>
												<option value="disable"><?php echo $bb_disable; ?></option>
											</select>
										</div>
										<div class="form-group">
											<label class="control-label">
												<?php echo $bb_email_for_backup_failure;?> :
												<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_alert_setup_email_template_for_backup_failure_tooltip;?>" data-placement="right"></i>
												<span class="required" aria-required="true">*</span>
											</label>
											<select name="ux_ddl_backup_failed" id="ux_ddl_backup_failed" class="form-control" >
												<option value="enable"><?php echo $bb_enable; ?></option>
												<option value="disable"><?php echo $bb_disable; ?></option>
											</select>
										</div>
										<div class="form-group">
											<label class="control-label">
												<?php echo $bb_email_for_restore_failure;?> :
												<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_alert_setup_email_template_for_restore_failure_tooltip;?>" data-placement="right"></i>
												<span class="required" aria-required="true">*</span>
											</label>
											<select name="ux_ddl_restore_failed" id="ux_ddl_restore_failed" class="form-control" >
												<option value="enable"><?php echo $bb_enable; ?></option>
												<option value="disable"><?php echo $bb_disable; ?></option>
											</select>
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
								<?php echo $bb_alert_setup; ?>
							</span>
						</li>
					</ul>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="portlet box vivid-green">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-custom-bell"></i>
									<?php echo $bb_alert_setup; ?>
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
