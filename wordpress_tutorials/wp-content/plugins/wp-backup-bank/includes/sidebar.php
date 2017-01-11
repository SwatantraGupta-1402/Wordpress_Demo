<?php
/**
* This file is used for displaying sidebar menus.
*
* @author  Tech Banker
* @package wp-backup-bank/includes
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
	else
	{
		?>
		<div class="page-sidebar-wrapper-tech-banker">
			<div class="page-sidebar-tech-banker navbar-collapse collapse">
				<div class="sidebar-menu-tech-banker">
					<ul class="page-sidebar-menu-tech-banker" data-slide-speed="200">
						<div class="sidebar-search-wrapper" style="padding:20px;text-align:center">
							<a class="plugin-logo" href="<?php echo tech_banker_beta_url; ?>" target="_blank">
								<img src="<?php echo plugins_url("assets/global/img/backup-bank-logo.png",dirname(__FILE__));?>">
							</a>
						</div>
						<li id="ux_bb_li_backups">
							<a href="javascript:;">
								<i class="icon-custom-folder-alt"></i>
								<span class="title">
									<?php echo $bb_backups; ?>
								</span>
							</a>
							<ul class="sub-menu">
								<li id="ux_bb_li_manage_backups">
									<a href="admin.php?page=bb_manage_backups">
										<i class="icon-custom-folder-alt"></i>
										<span class="title">
											<?php echo $bb_manage_backups; ?>
										</span>
									</a>
								</li>
								<li id="ux_bb_li_generate_manual_backup">
									<a href="admin.php?page=bb_start_backup">
										<i class="icon-custom-note"></i>
										<span class="title">
											<?php echo $bb_start_backup; ?>
										</span>
									</a>
								</li>
								<li id="ux_bb_li_schedule_backup">
									<a href="admin.php?page=bb_schedule_backup">
										<i class="icon-custom-hourglass"></i>
										<span class="title">
											<?php echo $bb_schedule_backup; ?>
										</span>
									</a>
								</li>
							</ul>
						</li>
						<li id="ux_bb_li_general_settings">
							<a href="javascript:;">
								<i class="icon-custom-paper-clip"></i>
								<span class="title">
									<?php echo $bb_general_settings; ?>
								</span>
							</a>
							<ul class="sub-menu">
								<li id="ux_bb_li_alert_setup_backup">
									<a href="admin.php?page=bb_alert_setup">
										<i class="icon-custom-bell"></i>
										<span class="title">
											<?php echo $bb_alert_setup; ?>
										</span>
									</a>
								</li>
								<li id="ux_bb_li_amazons3_settings">
									<a href="admin.php?page=bb_amazons3_settings">
										<i class="icon-custom-action-undo"></i>
										<span class="title">
											<?php echo $bb_amazons3_settings; ?>
										</span>
									</a>
								</li>
								<li id="ux_bb_li_dropbox_settings">
									<a href="admin.php?page=bb_dropbox_settings">
										<i class="icon-custom-social-dropbox"></i>
										<span class="title">
											<?php echo $bb_dropbox_settings; ?>
										</span>
									</a>
								</li>
								<li id="ux_bb_li_email_settings">
									<a href="admin.php?page=bb_email_settings">
										<i class="icon-custom-envelope"></i>
										<span class="title">
											<?php echo $bb_email_settings; ?>
										</span>
									</a>
								</li>
								<li id="ux_bb_li_ftp_settings">
									<a href="admin.php?page=bb_ftp_settings">
										<i class="icon-custom-share-alt"></i>
										<span class="title">
											<?php echo $bb_ftp_settings; ?>
										</span>
									</a>
								</li>
								<li id="ux_bb_li_google_drive_backup">
									<a href="admin.php?page=bb_google_drive">
										<i class="icon-custom-social-dribbble"></i>
										<span class="title">
											<?php echo $bb_google_drive; ?>
										</span>
									</a>
								</li>
								<li id="ux_bb_li_onedrive_settings">
									<a href="admin.php?page=bb_onedrive_settings">
										<i class="icon-custom-cloud-upload"></i>
										<span class="title">
											<?php echo $bb_onedrive_settings; ?>
										</span>
									</a>
								</li>
								<li id="ux_bb_li_rackspace_settings">
									<a href="admin.php?page=bb_rackspace_settings">
										<i class="icon-custom-rocket"></i>
										<span class="title">
											<?php echo $bb_rackspace_settings; ?>
										</span>
									</a>
								</li>
								<li id="ux_bb_li_ms_azure_settings">
									<a href="admin.php?page=bb_ms_azure_settings">
										<i class="icon-custom-energy"></i>
										<span class="title">
											<?php echo $bb_ms_azure_settings; ?>
										</span>
									</a>
								</li>
								<li id="ux_bb_li_other_settings_backup">
									<a href="admin.php?page=bb_other_settings">
										<i class="icon-custom-settings"></i>
										<span class="title">
											<?php echo $bb_other_settings; ?>
										</span>
									</a>
								</li>
							</ul>
						</li>
						<li id="ux_bb_li_email_template">
							<a href="admin.php?page=bb_email_templates">
								<i class="icon-custom-layers"></i>
								<span class="title">
									<?php echo $bb_email_templates; ?>
								</span>
							</a>
						</li>
						<li id="ux_bb_li_roles_capabilities">
							<a href="admin.php?page=bb_roles_and_capabilities">
								<i class="icon-custom-user"></i>
								<span class="title">
									<?php echo $bb_roles_and_capabilities; ?>
								</span>
							</a>
						</li>
						<li id="ux_bb_li_feature_requests">
							<a href="admin.php?page=bb_feature_requests">
								<i class="icon-custom-star"></i>
								<span class="title">
									<?php echo $bb_feature_requests; ?>
								</span>
							</a>
						</li>
						<li id="ux_bb_li_system_information">
							<a href="admin.php?page=bb_system_information">
								<i class="icon-custom-screen-desktop"></i>
								<span class="title">
									<?php echo $bb_system_information; ?>
								</span>
							</a>
						</li>
						<li id="ux_bb_li_premium_editions">
							<a href="admin.php?page=bb_premium_editions">
								<i class="icon-custom-briefcase"></i>
								<span class="title">
									<?php echo $bb_premium_editions; ?>
								</span>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	<?php
	}
}
?>
