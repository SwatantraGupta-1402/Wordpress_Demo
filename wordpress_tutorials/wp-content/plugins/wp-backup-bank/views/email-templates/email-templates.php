<?php
/**
* This Template is used for saving email templates.
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
	else if(email_templates_backup_bank == "1")
	{
		$backup_bank_change_template = wp_create_nonce("backup_bank_change_template");
		$backup_bank_update_email_template = wp_create_nonce("backup_bank_update_email_template");
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
							<span>
								<?php echo $bb_email_templates; ?>
							</span>
						</li>
					</ul>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="portlet box vivid-green">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-custom-layers"></i>
									<?php echo $bb_email_templates; ?>
								</div>
							</div>
							<div class="portlet-body form">
								<form id="ux_frm_email_template">
									<div class="form-body">
										<div class="note note-danger">
											<h4 class="block">
												<?php echo $bb_premium_edition_features_disclaimer; ?>
											</h4>
											<ul>
												<li><?php echo $bb_backup_bank_editing_email_templates; ?></li>
											</ul>
											<ul>
												<li><?php echo $bb_backup_bank_demos_disclaimer; ?><a href="<?php echo "http://beta.tech-banker.com/products/wp-backup-bank/demos/";?>" target="_blank" class='custom_links'><?php echo $bb_backup_bank_click_here; ?></a>.</li>
												<li><?php echo $bb_backup_bank_user_guide_disclaimer; ?><a href="<?php echo "http://beta.tech-banker.com/products/wp-backup-bank/user-guide/email-templates/";?>" target="_blank" class='custom_links'><?php echo $bb_backup_bank_click_here; ?></a>.</li>
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
												<?php echo $bb_choose_email_template;?> :
												<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_choose_email_template_tooltip;?>" data-placement="right"></i>
											</label>
											<select name="ux_ddl_email_template" id="ux_ddl_email_template" class="form-control" onchange="template_change_data_backup_bank();">
												<option value="template_for_backup_successful_generated"><?php echo $bb_email_template_for_generated_backup; ?></option>
												<option value="template_for_scheduled_backup"><?php echo $bb_email_template_for_backup_schedule; ?></option>
												<option value="template_for_restore_successfully"><?php echo $bb_email_template_for_restore_successfully; ?></option>
												<option value="template_for_backup_failure"><?php echo $bb_email_template_for_backup_failure; ?></option>
												<option value="template_for_restore_failure"><?php echo $bb_email_template_for_restore_failure; ?></option>
											</select>
										</div>
										<div class="form-group">
											<label class="control-label">
												<?php echo $bb_email_template_send_to;?> :
												<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_email_template_send_to_tooltip;?>" data-placement="right"></i>
												<span class="required" aria-required="true">*</span>
											</label>
											<input type="text" class="form-control" name="ux_txt_email_send_to" id="ux_txt_email_send_to" value="" placeholder="<?php echo $bb_email_template_send_to_placeholder;?>">
										</div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">
														<?php echo $bb_cc_email;?> :
														<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_email_cc_tooltip;?>" data-placement="right"></i>
													</label>
													<input type="text" class="form-control" name="ux_txt_email_template_cc" id="ux_txt_email_template_cc" value="" placeholder="<?php echo $bb_cc_placeholder;?>">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">
														<?php echo $bb_bcc_email;?> :
														<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_email_bcc_tooltip;?>" data-placement="right"></i>
													</label>
													<input type="text" class="form-control" name="ux_txt_email_template_bcc" id="ux_txt_email_template_bcc" value="" placeholder="<?php echo $bb_bcc_placeholder;?>">
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label">
												<?php echo $bb_subject;?> :
												<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_email_template_subject_tooltip;?>" data-placement="right"></i>
												<span class="required" aria-required="true">*</span>
											</label>
											<input type="text" class="form-control" name="ux_txt_email_subject" id="ux_txt_email_subject" value="" placeholder="<?php echo $bb_email_template_subject_placeholder;?>">
										</div>
										<div class="form-group">
											<label class="control-label">
												<?php echo $bb_email_message;?> :
												<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_email_message_tooltip;?>" data-placement="right"></i>
												<span class="required" aria-required="true">*</span>
											</label>
											<?php
												$bb_email_template_distribution = "";
												wp_editor( $bb_email_template_distribution, $id ="ux_heading_content", array("media_buttons" => false, "textarea_rows" => 8, "tabindex" => 4 ) );
											?>
											<textarea id="ux_txt_email_template_message" name="ux_txt_email_template_message" style="display:none"><?php echo $bb_email_template_distribution;?></textarea>
										</div>
										<div class="line-separator"></div>
										<div class="form-actions">
											<div class="pull-right">
												<input type="hidden" id="ux_email_template_meta_id" value=""/>
												<input type="submit" class="btn vivid-green" name="ux_btn_save_email_template" id="ux_btn_save_email_template" value="<?php echo $bb_save_changes;?>">
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
							<span>
								<?php echo $bb_email_templates; ?>
							</span>
						</li>
					</ul>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="portlet box vivid-green">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-custom-layers"></i>
									<?php echo $bb_email_templates; ?>
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
