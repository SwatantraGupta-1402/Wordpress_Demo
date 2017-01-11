<?php
/**
* This Template is used for displaying email settings.
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
		$backup_bank_email_settings = wp_create_nonce("backup_bank_email_settings");
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
								<?php echo $bb_email_settings; ?>
							</span>
						</li>
					</ul>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="portlet box vivid-green">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-custom-envelope"></i>
									<?php echo $bb_email_settings; ?>
								</div>
							</div>
							<div class="portlet-body form">
								<form id="ux_frm_email_settings">
									<div class="form-body">
										<div class="note note-danger">
											<h4 class="block">
												<?php echo $bb_important_disclaimer; ?>
											</h4>
											<ul>
												<li><?php echo $bb_backup_bank_demos_disclaimer; ?><a href="<?php echo "http://beta.tech-banker.com/products/wp-backup-bank/demos/";?>" target="_blank" class='custom_links'><?php echo $bb_backup_bank_click_here; ?></a>.</li>
												<li><?php echo $bb_backup_bank_user_guide_disclaimer; ?><a href="<?php echo "http://beta.tech-banker.com/products/wp-backup-bank/user-guide/general-settings/email-settings/";?>" target="_blank" class='custom_links'><?php echo $bb_backup_bank_click_here; ?></a>.</li>
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
												<?php echo $bb_email_backup_to;?> :
												<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_email_tooltip;?>" data-placement="right"></i>
												<span class="required" aria-required="true">*</span>
											</label>
											<select name="ux_ddl_email_settings_enable_disable" id="ux_ddl_email_settings_enable_disable" class="form-control" onchange="email_backup_bank();">
												<option value="enable"><?php echo $bb_enable; ?></option>
												<option value="disable"><?php echo $bb_disable; ?></option>
											</select>
										</div>
										<div id="ux_div_email">
											<div class="form-group">
												<label class="control-label">
													<?php echo $bb_email_settings_email_address;?> :
													<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_email_address_tooltip;?>" data-placement="right"></i>
													<span class="required" aria-required="true">*</span>
												</label>
												<input name="ux_txt_email_address" id="ux_txt_email_address" type="text" class="form-control" placeholder="<?php echo $bb_email_address_placeholder;?>" value="<?php echo isset($email_setting_data_array["email_address"]) ? esc_html($email_setting_data_array["email_address"]) : ""?>">
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label">
															<?php echo $bb_cc_email;?> :
															<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_email_cc_tooltip;?>" data-placement="right"></i>
														</label>
														<input type="text" class="form-control" name="ux_txt_email_cc" id="ux_txt_email_cc" value="<?php echo isset($email_setting_data_array["cc_email"]) ? esc_html($email_setting_data_array["cc_email"]) : ""?>" placeholder="<?php echo $bb_cc_placeholder;?>">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label">
															<?php echo $bb_bcc_email;?> :
															<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_email_bcc_tooltip;?>" data-placement="right"></i>
														</label>
														<input type="text" class="form-control" name="ux_txt_email_bcc" id="ux_txt_email_bcc" value="<?php echo isset($email_setting_data_array["bcc_email"]) ? esc_html($email_setting_data_array["bcc_email"]) : ""?>" placeholder="<?php echo $bb_bcc_placeholder;?>">
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label">
													<?php echo $bb_subject;?> :
													<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_email_settings_subject_tooltip;?>" data-placement="right"></i>
													<span class="required" aria-required="true">*</span>
												</label>
												<input type="text" class="form-control" name="ux_txt_email_subject" id="ux_txt_email_subject" value="<?php echo isset($email_setting_data_array["email_subject"]) ? esc_html($email_setting_data_array["email_subject"]) : ""?>" placeholder="<?php echo $bb_email_settings_subject_placeholder;?>">
											</div>
											<div class="form-group">
												<label class="control-label">
													<?php echo $bb_email_message;?> :
													<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_email_message_tooltip;?>" data-placement="right"></i>
													<span class="required" aria-required="true">*</span>
												</label>
												<?php
													$bb_email_settings_distribution = isset($email_setting_data_array["email_message"]) ? $email_setting_data_array["email_message"] : "";
													wp_editor( $bb_email_settings_distribution, $id ="ux_heading_content", array("media_buttons" => false, "textarea_rows" => 8, "tabindex" => 4 ) );
												?>
												<textarea id="ux_txt_email_settings_message" name="ux_txt_email_settings_message" style="display:none"></textarea>
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
								<?php echo $bb_email_settings; ?>
							</span>
						</li>
					</ul>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="portlet box vivid-green">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-custom-envelope"></i>
									<?php echo $bb_email_settings; ?>
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
