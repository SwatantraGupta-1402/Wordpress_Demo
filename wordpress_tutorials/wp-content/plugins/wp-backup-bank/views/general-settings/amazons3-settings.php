<?php
/**
* This Template is used for displaying amazon s3 settings.
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
		if(function_exists("curl_version"))
		{
			$curl_version  =  curl_version();
		}
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
								<?php echo $bb_amazons3_settings; ?>
							</span>
						</li>
					</ul>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="portlet box vivid-green">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-custom-action-undo"></i>
									<?php echo $bb_amazons3_settings; ?>
								</div>
							</div>
							<div class="portlet-body form">
								<form id="ux_frm_amazons3">
									<div class="form-body">
										<div class="note note-danger">
											<h4 class="block">
												<?php echo $bb_premium_edition_features_disclaimer; ?>
											</h4>
											<ul>
												<li> <?php echo $bb_backup_bank_amazon_s3_settings; ?> </li>
											</ul>
											<ul>
												<li><?php echo $bb_backup_bank_demos_disclaimer; ?><a href="<?php echo "http://beta.tech-banker.com/products/wp-backup-bank/demos/";?>" target="_blank" class='custom_links'><?php echo $bb_backup_bank_click_here; ?></a>.</li>
												<li><?php echo $bb_backup_bank_user_guide_disclaimer; ?><a href="<?php echo "http://beta.tech-banker.com/products/wp-backup-bank/user-guide/general-settings/amazon-s3-settings/";?>" target="_blank" class='custom_links'><?php echo $bb_backup_bank_click_here; ?></a>.</li>
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
												<?php echo $bb_amazons3_backup_to;?> :
												<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_amazons3_tooltip;?>" data-placement="right"></i>
												<span class="required" aria-required="true">*</span>
											</label>
											<select name="ux_ddl_amazons3_enable_disable" id="ux_ddl_amazons3_enable_disable" class="form-control">
												<option value="enable"><?php echo $bb_enable; ?></option>
												<option value="disable"><?php echo $bb_disable; ?></option>
											</select>
										</div>
										<div id="ux_div_amazons3">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label">
															<?php echo $bb_amazons3_asccess_key_id;?> (<a href="https://www.amazon.com/ap/signin?openid.assoc_handle=aws&openid.return_to=https%3A%2F%2Fsignin.aws.amazon.com%2Foauth%3Fresponse_type%3Dcode%26client_id%3Darn%253Aaws%253Aiam%253A%253A015428540659%253Auser%252Fhomepage%26redirect_uri%3Dhttps%253A%252F%252Fconsole.
															aws.amazon.com%252Fconsole%252Fhome%253Fstate%253DhashArgs%252523%2526isauthcode%253Dtrue%26noAuthCookie%3Dtrue&
															openid.mode=checkid_setup&openid.ns=http%3A%2F%2Fspecs.openid.net%2Fauth%2F2.0&
															openid.identity=http%3A%2F%2Fspecs.openid.net%2Fauth%2F2.0%2Fidentifier_select&openid.claimed_id=http%3A%2F%2Fspecs.openid.net%2Fauth%2F2.0%2Fidentifier_select&
															action=&disableCorpSignUp=&clientContext=&marketPlaceId=&poolName=&authCookies=&pageId=aws.ssop&
															siteState=registering%2Cen_US&accountStatusPolicy=P1&sso=&openid.pape.preferred_auth_policies=MultifactorPhysical&openid.pape.max_auth_age=120&
															openid.ns.pape=http%3A%2F%2Fspecs.openid.net%2Fextensions%2Fpape%2F1.0&server=%2Fap%2Fsignin%3Fie%3DUTF8&accountPoolAlias=&forceMobileApp=0&language=en_US&
															forceMobileLayout=0" id="ux_amazons3_apps_link"  target="_blank"><?php echo $bb_amazons3_get_access_key_id_secret_key; ?></a>) :
															<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_amazons3_asccess_key_id_tooltip;?>" data-placement="right"></i>
															<span class="required" aria-required="true">*</span>
														</label>
														<input name="ux_txt_amazons3_access_key_id" id="ux_txt_amazons3_access_key_id" type="text" class="form-control" placeholder="<?php echo $bb_amazons3_asccess_key_id_placeholder;?>" value="<?php echo isset($amazons3_settings_data_array["access_key_id"]) ? esc_html($amazons3_settings_data_array["access_key_id"]) : ""; ?>">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label">
															<?php echo $bb_amazons3_secret_key;?> :
															<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_amazons3_secret_key_tooltip; ?>" data-placement="right"></i>
															<span class="required" aria-required="true">*</span>
														</label>
														<input name="ux_txt_amazons3_secret_key" id="ux_txt_amazons3_secret_key" type="text" class="form-control" placeholder="<?php echo $bb_amazons3_secret_key_placeholder; ?>" value="<?php echo isset($amazons3_settings_data_array["secret_key"]) ? esc_html($amazons3_settings_data_array["secret_key"]) : ""; ?>">
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label">
													<?php echo $bb_amazons3_bucket_name;?> :
													<i class="icon-custom-question tooltips" data-original-title="<?php echo $bb_amazons3_bucket_tooltip; ?>" data-placement="right"></i>
													<span class="required" aria-required="true">*</span>
												</label>
												<input name="ux_txt_amazons3_bucket_name" id="ux_txt_amazons3_bucket_name" type="text" class="form-control" placeholder="<?php echo $bb_amazons3_bucket_placeholder; ?>" value="<?php echo isset($amazons3_settings_data_array["bucket_name"]) ? esc_html($amazons3_settings_data_array["bucket_name"]) : ""; ?>">
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
								<?php echo $bb_amazons3_settings; ?>
							</span>
						</li>
					</ul>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="portlet box vivid-green">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-custom-action-undo"></i>
									<?php echo $bb_amazons3_settings; ?>
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
