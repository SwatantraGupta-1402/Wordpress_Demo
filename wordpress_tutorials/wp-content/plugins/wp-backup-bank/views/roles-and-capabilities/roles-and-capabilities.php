<?php
/**
* This Template is used for managing roles and capabilities.
*
* @author  Tech Banker
* @package wp-backup-bank/views/roles-and-capabilities
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
	else if(roles_and_capabilities_backup_bank == "1")
	{
		$backup_bank_roles_capabilities = wp_create_nonce("backup_bank_roles_capabilities");
		$roles_and_capabilities = explode(",",isset($details_roles_capabilities["roles_and_capabilities"]) ? $details_roles_capabilities["roles_and_capabilities"] : "");
		$author = explode(",",isset($details_roles_capabilities["author_privileges"]) ? $details_roles_capabilities["author_privileges"] : "");
		$editor = explode(",",isset($details_roles_capabilities["editor_privileges"]) ? $details_roles_capabilities["editor_privileges"] : "");
		$contributor = explode(",",isset($details_roles_capabilities["contributor_privileges"]) ? $details_roles_capabilities["contributor_privileges"] : "");
		$subscriber = explode(",",isset($details_roles_capabilities["subscriber_privileges"]) ? $details_roles_capabilities["subscriber_privileges"] : "");
		$others = explode(",",isset($details_roles_capabilities["other_privileges"]) ? $details_roles_capabilities["other_privileges"] : "");
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
								<?php echo $bb_roles_and_capabilities; ?>
							</span>
						</li>
					</ul>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="portlet box vivid-green">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-custom-user"></i>
									<?php echo $bb_roles_and_capabilities; ?>
								</div>
							</div>
							<div class="portlet-body form">
								<form id="ux_frm_roles_and_capabilities">
									<div class="form-body">
										<div class="note note-danger">
											<h4 class="block">
												<?php echo $bb_premium_edition_features_disclaimer; ?>
											</h4>
											<ul>
												<li><?php echo $bb_backup_bank_roles_capabilities;?></li>
											</ul>
											<ul>
												<li><?php echo $bb_backup_bank_demos_disclaimer; ?><a href="<?php echo "http://beta.tech-banker.com/products/wp-backup-bank/demos/";?>" target="_blank" class='custom_links'><?php echo $bb_backup_bank_click_here; ?></a>.</li>
												<li><?php echo $bb_backup_bank_user_guide_disclaimer; ?><a href="<?php echo "http://beta.tech-banker.com/products/wp-backup-bank/user-guide/roles-capabilities/";?>" target="_blank" class='custom_links'><?php echo $bb_backup_bank_click_here; ?></a>.</li>
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
												<?php echo $bb_roles_capabilities_show_menu;?> :
												<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_roles_capabilities_show_menu_tooltip;?>" data-placement="right"></i>
												<span class="required" aria-required="true">*</span>
											</label>
											<table class="table table-striped table-bordered table-margin-top" id="ux_tbl_backup_bank_roles">
												<thead>
													<tr>
														<th>
															<input type="checkbox"  name="ux_chk_administrator" id="ux_chk_administrator" value="1" checked="checked" disabled="disabled" <?php echo $roles_and_capabilities[0] == "1" ? "checked = checked" : ""?>>
															<?php echo $bb_roles_capabilities_administrator;?>
														</th>
														<th>
															<input type="checkbox"  name="ux_chk_author" id="ux_chk_author"  value="1" onclick="show_roles_capabilities_backup_bank(this,'ux_div_author_roles');" <?php echo $roles_and_capabilities[1] == "1" ? "checked = checked" : ""?>>
															<?php echo $bb_roles_capabilities_author;?>
														</th>
														<th>
															<input type="checkbox"  name="ux_chk_editor" id="ux_chk_editor" value="1" onclick="show_roles_capabilities_backup_bank(this,'ux_div_editor_roles');" <?php echo $roles_and_capabilities[2] == "1" ? "checked = checked" : ""?>>
															<?php echo $bb_roles_capabilities_editor;?>
														</th>
														<th>
															<input type="checkbox"  name="ux_chk_contributor" id="ux_chk_contributor"  value="1" onclick="show_roles_capabilities_backup_bank(this,'ux_div_contributor_roles');" <?php echo $roles_and_capabilities[3] == "1" ? "checked = checked" : ""?>>
															<?php echo $bb_roles_capabilities_contributor;?>
														</th>
														<th>
															<input type="checkbox"  name="ux_chk_subscriber" id="ux_chk_subscriber" value="1" onclick="show_roles_capabilities_backup_bank(this,'ux_div_subscriber_roles');" <?php echo $roles_and_capabilities[4] == "1" ? "checked = checked" : ""?>>
															<?php echo $bb_roles_capabilities_subscriber;?>
														</th>
														<th>
															<input type="checkbox" name="ux_chk_other" id="ux_chk_other" value="1" onclick="show_roles_capabilities_backup_bank(this,'ux_div_other_roles');" <?php echo $roles_and_capabilities[5] == "1" ? "checked = checked" : ""?>>
															<?php echo $bb_roles_capabilities_other;?>
														</th>
													</tr>
												</thead>
											</table>
										</div>
										<div class="form-group">
											<label class="control-label">
												<?php echo $bb_roles_capabilities_topbar_menu;?> :
												<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_roles_capabilities_topbar_menu_tooltip;?>" data-placement="right"></i>
												<span class="required" aria-required="true">*</span>
											</label>
											<select name="ux_ddl_backup_bank_menu" id="ux_ddl_backup_bank_menu" class="form-control">
												<option value="enable"><?php echo $bb_enable;?></option>
												<option value="disable"><?php echo $bb_disable;?></option>
											</select>
										</div>
										<div class="line-separator"></div>
										<div class="form-group">
											<div id="ux_div_administrator_roles">
												<label class="control-label">
													<?php echo $bb_roles_capabilities_administrator_role;?> :
													<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_roles_capabilities_administrator_role_tooltip;?>" data-placement="right"></i>
													<span class="required" aria-required="true">*</span>
												</label>
												<div class="table-margin-top">
													<table class="table table-striped table-bordered table-hover" id="ux_tbl_administrator">
														<thead>
															<tr>
																<th style="width: 40% !important;">
																	<input type="checkbox" name="ux_chk_full_control_administrator" id="ux_chk_full_control_administrator" checked="checked" disabled="disabled" value="1">
																	<?php echo $bb_roles_capabilities_full_control;?>
																</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>
																	<input type="checkbox" name="ux_chk_manage_backups_admin" disabled="disabled" checked="checked" id="ux_chk_manage_backups_admin" value="1">
																	<?php echo $bb_manage_backups;?>
																</td>
																<td>
																	<input type="checkbox" name="ux_chk_add_new_backup_admin" disabled="disabled" checked="checked" id="ux_chk_add_new_backup_admin" value="1">
																	<?php echo $bb_start_backup ;?>
																</td>
																<td>
																	<input type="checkbox" name="ux_chk_schedule_backup_admin" disabled="disabled" checked="checked" id="ux_chk_schedule_backup_admin" value="1">
																	<?php echo $bb_schedule_backup ;?>
																</td>
															</tr>
															<tr>
																<td>
																	<input type="checkbox" name="ux_chk_general_settings_admin" disabled="disabled" checked="checked" id="ux_chk_general_settings_admin" value="1">
																	<?php echo $bb_general_settings;?>
																</td>
																<td>
																	<input type="checkbox" name="ux_chk_template_admin" disabled="disabled" checked="checked" id="ux_chk_template_admin" value="1">
																	<?php echo $bb_email_templates;?>
																</td>
																<td>
																	<input type="checkbox" name="ux_chk_roles_admin" disabled="disabled" checked="checked" id="ux_chk_roles_admin" value="1">
																	<?php echo $bb_roles_and_capabilities;?>
																</td>
															</tr>
															<tr>
																<td>
																	<input type="checkbox" name="ux_chk_system_information_admin" disabled="disabled" checked="checked" id="ux_chk_system_information_admin" value="1">
																	<?php echo $bb_system_information;?>
																</td>
																<td>
																</td>
																<td>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
												<div class="line-separator"></div>
											</div>
										</div>
										<div class="form-group">
											<div id="ux_div_author_roles">
												<label class="control-label">
													<?php echo $bb_roles_capabilities_author_role;?> :
													<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_roles_capabilities_author_role_tooltip;?>" data-placement="right"></i>
													<span class="required" aria-required="true">*</span>
												</label>
												<div class="table-margin-top">
													<table class="table table-striped table-bordered table-hover" id="ux_tbl_author">
														<thead>
															<tr>
																<th style="width: 40% !important;">
																	<input type="checkbox" name="ux_chk_full_control_author" id="ux_chk_full_control_author" value="1" onclick="full_control_function_backup_bank(this,'ux_div_author_roles');" <?php echo isset($author) && $author[0] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_roles_capabilities_full_control;?>
																</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>
																	<input type="checkbox" name="ux_chk_manage_backups_author" id="ux_chk_manage_backups_author" value="1" <?php echo isset($author) && $author[1] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_manage_backups;?>
																</td>
																<td>
																	<input type="checkbox" name="ux_chk_add_new_backup_author" id="ux_chk_add_new_backup_author" value="1" <?php echo isset($author) && $author[2] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_start_backup ;?>
																</td>
																<td>
																	<input type="checkbox" name="ux_chk_schedule_backup_author" id="ux_chk_schedule_backup_author" value="1" <?php echo isset($author) && $author[3] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_schedule_backup ;?>
																</td>
															</tr>
															<tr>
																<td>
																	<input type="checkbox" name="ux_chk_general_settings_author" id="ux_chk_general_settings_author" value="1" <?php echo isset($author) && $author[4] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_general_settings;?>
																</td>
																<td>
																	<input type="checkbox" name="ux_chk_templates_author" id="ux_chk_templates_author" value="1" <?php echo isset($author) && $author[5] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_email_templates;?>
																</td>
																<td>
																	<input type="checkbox" name="ux_chk_roles_author" id="ux_chk_roles_author" value="1" <?php echo isset($author) && $author[6] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_roles_and_capabilities;?>
																</td>
															</tr>
															<tr>
																<td>
																	<input type="checkbox" name="ux_chk_system_information_author" id="ux_chk_system_information_author" value="1" <?php echo isset($author) && $author[7] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_system_information;?>
																</td>
																<td>
																</td>
																<td>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
												<div class="line-separator"></div>
											</div>
										</div>
										<div class="form-group">
											<div id="ux_div_editor_roles">
												<label class="control-label">
													<?php echo $bb_roles_capabilities_editor_role;?> :
													<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_roles_capabilities_editor_role_tooltip;?>" data-placement="right"></i>
													<span class="required" aria-required="true">*</span>
												</label>
												<div class="table-margin-top">
													<table class="table table-striped table-bordered table-hover" id="ux_tbl_editor">
														<thead>
															<tr>
																<th style="width: 40% !important;">
																	<input type="checkbox" name="ux_chk_full_control_editor" id="ux_chk_full_control_editor" value="1" onclick="full_control_function_backup_bank(this,'ux_div_editor_roles');" <?php echo isset($editor) && $editor[0] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_roles_capabilities_full_control;?>
																</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>
																	<input type="checkbox" name="ux_chk_manage_backups_editor" id="ux_chk_manage_backups_editor" value="1" <?php echo isset($editor) && $editor[1] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_manage_backups;?>
																</td>
																<td>
																	<input type="checkbox" name="ux_chk_add_new_backup_editor" id="ux_chk_add_new_backup_editor" value="1" <?php echo isset($editor) && $editor[2] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_start_backup ;?>
																</td>
																<td>
																	<input type="checkbox" name="ux_chk_schedule_backup_editor" id="ux_chk_schedule_backup_editor" value="1" <?php echo isset($editor) && $editor[3] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_schedule_backup ;?>
																</td>
															</tr>
															<tr>
																<td>
																	<input type="checkbox" name="ux_chk_general_settings_editor" id="ux_chk_general_settings_editor" value="1" <?php echo isset($editor) && $editor[4] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_general_settings;?>
																</td>
																<td>
																	<input type="checkbox" name="ux_chk_templates_editor" id="ux_chk_templates_editor" value="1" <?php echo isset($editor) && $editor[5] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_email_templates;?>
																</td>
																<td>
																	<input type="checkbox" name="ux_chk_roles_editor" id="ux_chk_roles_editor" value="1" <?php echo isset($editor) && $editor[6] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_roles_and_capabilities;?>
																</td>
															</tr>
															<tr>
																<td>
																	<input type="checkbox" name="ux_chk_system_information_editor" id="ux_chk_system_information_editor" value="1" <?php echo isset($editor) && $editor[7] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_system_information;?>
																</td>
																<td>
																</td>
																<td>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
												<div class="line-separator"></div>
											</div>
										</div>
										<div class="form-group">
											<div id="ux_div_contributor_roles">
												<label class="control-label">
													<?php echo $bb_roles_capabilities_contributor_role;?> :
													<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_roles_capabilities_contributor_role_tooltip;?>" data-placement="right"></i>
													<span class="required" aria-required="true">*</span>
												</label>
												<div class="table-margin-top">
													<table class="table table-striped table-bordered table-hover" id="ux_tbl_contributor">
														<thead>
															<tr>
																<th style="width: 40% !important;">
																	<input type="checkbox" name="ux_chk_full_control_contributor" id="ux_chk_full_control_contributor" value="1" onclick="full_control_function_backup_bank(this,'ux_div_contributor_roles');" <?php echo isset($contributor) && $contributor[0] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_roles_capabilities_full_control;?>
																</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>
																	<input type="checkbox" name="ux_chk_manage_backups_contributor" id="ux_chk_manage_backups_contributor" value="1" <?php echo isset($contributor) && $contributor[1] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_manage_backups;?>
																</td>
																<td>
																	<input type="checkbox" name="ux_chk_add_new_backup_contributor" id="ux_chk_add_new_backup_contributor" value="1" <?php echo isset($contributor) && $contributor[2] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_start_backup ;?>
																</td>
																<td>
																	<input type="checkbox" name="ux_chk_schedule_backup_contributor" id="ux_chk_schedule_backup_contributor" value="1" <?php echo isset($contributor) && $contributor[3] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_schedule_backup ;?>
																</td>
															</tr>
															<tr>
																<td>
																	<input type="checkbox" name="ux_chk_general_settings_contributor" id="ux_chk_general_settings_contributor" value="1" <?php echo isset($contributor) && $contributor[4] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_general_settings;?>
																</td>
																<td>
																	<input type="checkbox" name="ux_chk_templates_contributor" id="ux_chk_templates_contributor" value="1" <?php echo isset($contributor) && $contributor[5] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_email_templates;?>
																</td>
																<td>
																	<input type="checkbox" name="ux_chk_roles_contributor" id="ux_chk_roles_contributor" value="1" <?php echo isset($contributor) && $contributor[6] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_roles_and_capabilities;?>
																</td>
															</tr>
															<tr>
																<td>
																	<input type="checkbox" name="ux_chk_system_information_contributor" id="ux_chk_system_information_contributor" value="1" <?php echo isset($contributor) && $contributor[7] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_system_information;?>
																</td>
																<td>
																</td>
																<td>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
												<div class="line-separator"></div>
											</div>
										</div>
										<div class="form-group">
											<div id="ux_div_subscriber_roles">
												<label class="control-label">
													<?php echo $bb_roles_capabilities_subscriber_role;?> :
													<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_roles_capabilities_subscriber_role_tooltip;?>" data-placement="right"></i>
													<span class="required" aria-required="true">*</span>
												</label>
												<div class="table-margin-top">
													<table class="table table-striped table-bordered table-hover" id="ux_tbl_subscriber">
														<thead>
															<tr>
																<th style="width: 40% !important;">
																	<input type="checkbox" name="ux_chk_full_control_subscriber" id="ux_chk_full_control_subscriber" value="1" onclick="full_control_function_backup_bank(this,'ux_div_subscriber_roles');" <?php echo isset($subscriber) && $subscriber[0] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_roles_capabilities_full_control;?>
																</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>
																	<input type="checkbox" name="ux_chk_manage_backups_subscriber" id="ux_chk_manage_backups_subscriber" value="1" <?php echo isset($subscriber) && $subscriber[1] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_manage_backups;?>
																</td>
																<td>
																	<input type="checkbox" name="ux_chk_add_new_backup_subscriber" id="ux_chk_add_new_backup_subscriber" value="1" <?php echo isset($subscriber) && $subscriber[2] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_start_backup ;?>
																</td>
																<td>
																	<input type="checkbox" name="ux_chk_schedule_backup_subscriber" id="ux_chk_schedule_backup_subscriber" value="1" <?php echo isset($subscriber) && $subscriber[3] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_schedule_backup ;?>
																</td>
															</tr>
															<tr>
																<td>
																	<input type="checkbox" name="ux_chk_general_settings_subscriber" id="ux_chk_general_settings_subscriber" value="1" <?php echo isset($subscriber) && $subscriber[4] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_general_settings;?>
																</td>
																<td>
																	<input type="checkbox" name="ux_chk_templates_subscriber" id="ux_chk_templates_subscriber" value="1" <?php echo isset($subscriber) && $subscriber[5] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_email_templates;?>
																</td>
																<td>
																	<input type="checkbox" name="ux_chk_roles_subscriber" id="ux_chk_roles_subscriber" value="1" <?php echo isset($subscriber) && $subscriber[6] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_roles_and_capabilities;?>
																</td>
															</tr>
															<tr>
																<td>
																	<input type="checkbox" name="ux_chk_system_information_subscriber" id="ux_chk_system_information_subscriber" value="1" <?php echo isset($subscriber) && $subscriber[7] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_system_information;?>
																</td>
																<td>
																</td>
																<td>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
												<div class="line-separator"></div>
											</div>
										</div>
										<div class="form-group">
											<div id="ux_div_other_roles">
												<label class="control-label">
													<?php echo $bb_roles_capabilities_other_role;?> :
													<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_roles_capabilities_other_role_tooltip;?>" data-placement="right"></i>
													<span class="required" aria-required="true">*</span>
												</label>
												<div class="table-margin-top">
													<table class="table table-striped table-bordered table-hover" id="ux_tbl_other">
														<thead>
															<tr>
																<th style="width: 40% !important;">
																	<input type="checkbox" name="ux_chk_full_control_other" id="ux_chk_full_control_other" value="1" onclick="full_control_function_backup_bank(this,'ux_div_other_roles');" <?php echo isset($others) && $others[0] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_roles_capabilities_full_control;?>
																</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>
																	<input type="checkbox" name="ux_chk_manage_backups_other" id="ux_chk_manage_backups_other" value="1" <?php echo isset($others) && $others[1] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_manage_backups;?>
																</td>
																<td>
																	<input type="checkbox" name="ux_chk_add_new_backup_other" id="ux_chk_add_new_backup_other" value="1" <?php echo isset($others) && $others[2] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_start_backup ;?>
																</td>
																<td>
																	<input type="checkbox" name="ux_chk_schedule_backup_other" id="ux_chk_schedule_backup_other" value="1" <?php echo isset($others) && $others[3] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_schedule_backup ;?>
																</td>
															</tr>
															<tr>
																<td>
																	<input type="checkbox" name="ux_chk_general_settings_other" id="ux_chk_general_settings_other" value="1" <?php echo isset($others) && $others[4] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_general_settings;?>
																</td>
																<td>
																	<input type="checkbox" name="ux_chk_templates_other" id="ux_chk_templates_other" value="1" <?php echo isset($others) && $others[5] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_email_templates;?>
																</td>
																<td>
																	<input type="checkbox" name="ux_chk_roles_other" id="ux_chk_roles_other" value="1" <?php echo isset($others) && $others[6] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_roles_and_capabilities;?>
																</td>
															</tr>
															<tr>
																<td>
																	<input type="checkbox" name="ux_chk_system_information_other" id="ux_chk_system_information_other" value="1" <?php echo isset($others) && $others[7] =="1" ? "checked = checked" : ""?>>
																	<?php echo $bb_system_information;?>
																</td>
																<td>
																</td>
																<td>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
												<div class="line-separator"></div>
											</div>
										</div>
										<div class="form-group">
											<div id="ux_div_other_roles_capabilities">
											<label class="control-label">
											<?php echo $bb_roles_and_capabilities_other_roles_capabilities;?> :
											<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_roles_and_capabilities_other_roles_capabilities_tooltip;?>" data-placement="right"></i>
											<span class="required" aria-required="true">*</span>
											</label>
											<div class="table-margin-top">
												<table class="table table-striped table-bordered table-hover" id="ux_tbl_other_roles">
													<thead>
														<tr>
															<th style="width: 40% !important;">
																<input type="checkbox" name="ux_chk_full_control_other_roles" id="ux_chk_full_control_other_roles" value="1" onclick="full_control_function_backup_bank(this,'ux_div_other_roles_capabilities');" <?php echo $details_roles_capabilities["others_full_control_capability"] =="1" ? "checked = checked" : ""?>>
																<?php echo $bb_roles_capabilities_full_control;?>
															</th>
														</tr>
													</thead>
													<tbody>
													<?php
													$flag = 0;
													$user_capabilities = get_others_capabilities_backup_bank();
													if(count($user_capabilities) > 0)
													{
														foreach($user_capabilities as $key => $value)
														{
															$other_roles = in_array($value,$other_roles_array) ? "checked=checked" : "";
															$flag++;
															if($key % 3 == 0)
															{
																?>
																<tr>
																<?php
															}
															?>
															<td>
																<input type="checkbox" name="ux_chk_other_capabilities_<?php echo $value;?>" id="ux_chk_other_capabilities_<?php echo $value;?>" value="<?php echo $value;?>" <?php echo $other_roles; ?>>
																<?php echo $value;?>
															</td>
															<?php
															if(count($user_capabilities) == $flag && $flag % 3 == 1)
															{
																?>
																<td>
																</td>
																<td>
																</td>
																<?php
															}
															?>
															<?php
															if(count($user_capabilities) == $flag && $flag % 3 == 2)
															{
																?>
																<td>
																</td>
																<?php
															}
															?>
															<?php
															if($flag % 3 == 0)
															{
																?>
																</tr>
																<?php
															}
														}
													}
													?>
													</tbody>
												</table>
											</div>
											<div class="line-separator"></div>
											</div>
										</div>
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
							<span>
								<?php echo $bb_roles_and_capabilities; ?>
							</span>
						</li>
					</ul>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="portlet box vivid-green">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-custom-user"></i>
									<?php echo $bb_roles_and_capabilities; ?>
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
