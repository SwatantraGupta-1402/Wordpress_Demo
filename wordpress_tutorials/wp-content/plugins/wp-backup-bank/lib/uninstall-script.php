<?php
/**
* This file contains uninstallation code.
*
* @author	Tech Banker
* @package wp-backup-bank/lib
* @version 3.0.1
*/
if(!defined("ABSPATH")) exit; //exit if accessed directly
if(!is_user_logged_in())
{
	return;
}
else
{
	if(!current_user_can("manage_options"))
	{
		return;
	}
	else
	{
		if(wp_next_scheduled("automatic_updates_backup_bank"))
		{
			wp_clear_scheduled_hook("automatic_updates_backup_bank");
	 	}
		if (count($wpdb->get_var("SHOW TABLES LIKE '" . backup_bank_meta() . "'")) != 0)
		{
			$bb_other_settings_updated_data = $wpdb->get_var
			(
				$wpdb->prepare
				(
					"SELECT meta_value FROM ".backup_bank_meta().
					" WHERE meta_key=%s",
					"other_settings"
				)
			);
			$bb_other_settings_array = unserialize($bb_other_settings_updated_data);
			if(isset($bb_other_settings_array["remove_tables_at_uninstall"]))
			{
				if($bb_other_settings_array["remove_tables_at_uninstall"] == "enable")
				{
					if (count($wpdb->get_var("SHOW TABLES LIKE '" . backup_bank() . "'")) != 0)
					{
						$wpdb->query("DROP TABLE " .backup_bank());
					}
					$wpdb->query("DROP TABLE " . backup_bank_meta());
					if (count($wpdb->get_var("SHOW TABLES LIKE '" . backup_bank_restore() . "'")) != 0)
					{
						$wpdb->query("DROP TABLE " . backup_bank_restore());
					}
					delete_option("backup-bank-version-number");
				}
			}
		}
	}
}
?>
