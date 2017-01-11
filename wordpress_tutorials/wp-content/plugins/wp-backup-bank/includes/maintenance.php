<?php
if(!defined("ABSPATH")) exit; //exit if accessed directly
global $wpdb;
$enable_maintenance_mode = $wpdb->get_var
(
	$wpdb->prepare
	(
		"SELECT meta_value FROM ".backup_bank_restore().
		" WHERE meta_key = %s",
		"maintenance_mode_settings"
	)
);
$enable_maintenance_mode_data = unserialize($enable_maintenance_mode);
?>
<h1> <?php echo isset($enable_maintenance_mode_data["message_when_restore"]) ? $enable_maintenance_mode_data["message_when_restore"] : "Site in Maintenance Mode"; ?> </h1>
<?php
exit();
?>
