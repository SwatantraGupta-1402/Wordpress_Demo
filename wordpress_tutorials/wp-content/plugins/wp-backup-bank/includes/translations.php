<?php
/**
* This file is used for translation strings.
*
* @author  Tech Banker
* @package wp-backup-bank/includes
* @version 3.0.1
*/

if(!defined("ABSPATH")) exit; //exit if accessed directly

// Footer
$bb_success = __("Success!",wp_backup_bank);
$bb_feature_request = __("Your request email has been sent Successfully",wp_backup_bank);
$bb_backup_generated_successfully = __("The Backup has been Successfully Generated",wp_backup_bank);
$bb_update_email_template = __("Email Templates have been saved Successfully",wp_backup_bank);
$bb_choose_tables = __("Please choose at least one Table for Backup!",wp_backup_bank);
$bb_update_alert_setup = __("Alert Setup Settings have been saved Successfully",wp_backup_bank);
$bb_update_other_settings = __("Other Settings have been saved Successfully",wp_backup_bank);
$bb_confirm_single_delete = __("Are you sure you want to delete Backup?",wp_backup_bank);
$bb_confirm_bulk_delete = __("Are you sure you want to delete Backups?",wp_backup_bank);
$bb_delete_backups =__("The Backup has been deleted Successfully",wp_backup_bank);
$bb_bulk_delete_backups =__("Selected Backups have been deleted Successfully",wp_backup_bank);
$bb_choose_record_to_delete = __("Please choose at least 1 record to delete!",wp_backup_bank);
$bb_choose_action = __("Please choose an Action from Dropdown!",wp_backup_bank);
$bb_update_ftp_settings =  __("FTP Settings have been saved Successfully",wp_backup_bank);
$bb_update_email_settings = __("Email Settings have been saved Successfully",wp_backup_bank);
$bb_ftp_conn = __("Incorrect Server Address!",wp_backup_bank);
$bb_could_not_connect = __("Error Connecting FTP with details mentioned below. Please rectify and try again!",wp_backup_bank);
$bb_invalid_dropbox_api_or_Secret_key = __("Error Validating DropBox Api Key or App Secret. Please rectify and try again!", wp_backup_bank);
$bb_error_file_upload = __("Error uploading file. Please rectify and try again!",wp_backup_bank);
$bb_cancel_backup_to_dropbox = __("Error! Access to your Folder has been Cancelled",wp_backup_bank);
$bb_backup_terminated = __("Unfortunately, your Backup has been Terminated!",wp_backup_bank);
$bb_dropbox_upload = __("Error establishing a DropBox connection or Access Token got Expired!", wp_backup_bank);
$bb_ftp_connect = __("Incorrect Server Address!",wp_backup_bank);
$bb_choose_backup_to_download = __("Please choose a specific Backup File to download!", wp_backup_bank);
$bb_choose_log_file_to_download = __("Please choose a specific Log File to download!", wp_backup_bank);
$bb_choose_backup = __("Please choose a Backup File",wp_backup_bank);
$bb_choose_log_file = __("Please choose a Log File",wp_backup_bank);
$bb_choose_backup_to_download_tooltip = __("In this field, you would need to choose a Backup File to download",wp_backup_bank);
$bb_choose_log_file_to_download_tooltip = __("In this field, you would need to choose a Log File to download",wp_backup_bank);
$bb_backup_email = __("Email couldn't be sent as Backup Size exceeds 20MB!",wp_backup_bank);
$bb_confirm_rerun = __("Are you sure you would like to re-run this Backup?",wp_backup_bank);
$bb_ftp_not_configured_message = __("FTP Settings are not configured!",wp_backup_bank);
$bb_email_not_configured_message = __("Email Settings are not configured!",wp_backup_bank);
$bb_message_premium_edition = __("This feature is available only in Premium Editions! <br> Kindly Purchase to unlock it!", wp_backup_bank);

//Disclaimer
$bb_important_disclaimer = __("Important Disclaimer!",wp_backup_bank);
$bb_backup_bank_premium_disclaimer = __(" to see Premium Edition Features in detail",wp_backup_bank);
$bb_backup_bank_click_here = __("here",wp_backup_bank);
$bb_click = __("* Click ",wp_backup_bank);
$bb_backup_bank_amazon_s3_settings = __("* Saving / Updating Amazon S3 Settings",wp_backup_bank);
$bb_backup_bank_scheduler_disclaimer = __("* Schedule Backup",wp_backup_bank);
$bb_backup_bank_db_extensions_disclaimer = __("* DB Compression type .sql.bz2",wp_backup_bank);
$bb_backup_bank_file_extensions_disclaimer = __("* File Compression type .tar.bz2",wp_backup_bank);
$bb_backup_bank_complete_backup_disclaimer = __("* Complete Backup",wp_backup_bank);
$bb_backup_bank_dropbox_ftp_email_backup_disclaimer = __("* Remote Storage to Amazon S3, Dropbox, Email, FTP, Google Drive, OneDrive, Rackspace and Microsoft Azure",wp_backup_bank);
$bb_backup_bank_google_drive_settings = __("* Saving / Updating Google Drive Settings",wp_backup_bank);
$bb_backup_bank_onedrive_settings = __("* Saving / Updating OneDrive Settings",wp_backup_bank);
$bb_backup_bank_dropbox_settings = __("* Saving / Updating Dropbox Settings",wp_backup_bank);
$bb_backup_bank_rackspace_settings = __("* Saving / Updating Rackspace Settings",wp_backup_bank);
$bb_backup_bank_azure_settings = __("* Saving / Updating Microsoft Azure Settings",wp_backup_bank);
$bb_backups_limit_exceed = __("* Only 5 Backups are allowed in this Edition",wp_backup_bank);
$bb_backup_bank_editing_email_templates = __("* Editing Email Templates",wp_backup_bank);
$bb_backup_bank_roles_capabilities = __("* Saving Roles & Capabilities",wp_backup_bank);
$bb_backup_bank_demos_disclaimer = __("* For Backup Bank Demos, click ",wp_backup_bank);
$bb_backup_bank_purge_backup = __("* Purge Backups",wp_backup_bank);
$bb_backup_bank_restore_backup = __("* Restore Backup",wp_backup_bank);
$bb_backup_bank_user_guide_disclaimer = __("* For Backup Bank User Guide for this page, click ",wp_backup_bank);
$bb_premium_edition = __("(Only Available in Premium Editions!)",wp_backup_bank);

// Menus
$wp_backup_bank = __("WP Backup Bank",wp_backup_bank);
$bb_manage_backups = __("Manage Backups",wp_backup_bank);
$bb_schedule_backup = __("Schedule Backup",wp_backup_bank);
$bb_general_settings = __("General Settings",wp_backup_bank);
$bb_alert_setup = __("Alert Setup",wp_backup_bank);
$bb_other_settings = __("Other Settings",wp_backup_bank);
$bb_dropbox_settings = __("Dropbox Settings", wp_backup_bank);
$bb_email_settings = __("Email Settings", wp_backup_bank);
$bb_ftp_settings = __("FTP Settings", wp_backup_bank);
$bb_email_templates = __("Email Templates",wp_backup_bank);
$bb_roles_and_capabilities = __("Roles & Capabilities",wp_backup_bank);
$bb_feature_requests = __("Feature Requests",wp_backup_bank);
$bb_system_information = __("System Information",wp_backup_bank);
$bb_licensing = __("Licensing",wp_backup_bank);
$bb_backups = __("Backups / Restore",wp_backup_bank);
$bb_onedrive_settings = __("OneDrive Settings", wp_backup_bank);
$bb_google_drive = __("Google Drive Settings",wp_backup_bank);
$bb_amazons3_settings = __("Amazon S3 Settings", wp_backup_bank);
$bb_rackspace_settings = __("Rackspace Settings", wp_backup_bank);
$bb_ms_azure_settings = __("Microsoft Azure Settings", wp_backup_bank);


// Common Variables
$bb_save_changes = __("Save Changes",wp_backup_bank);
$bb_enable = __("Enable",wp_backup_bank);
$bb_disable = __("Disable",wp_backup_bank);
$bb_user_access_message = __("You don't have Sufficient Access to this Page. Kindly contact the Administrator for more Privileges",wp_backup_bank);
$bb_backup_name = __("Backup Name",wp_backup_bank);
$bb_backup_type = __("Backup Type", wp_backup_bank);
$bb_api_key = __("App Key",wp_backup_bank);
$bb_subject = __("Subject",wp_backup_bank);
$bb_backup_name_tooltip = __("In this field, you would need to provide a title to your backup",wp_backup_bank);
$bb_backup_name_placeholder = __("Please provide Backup Title ",wp_backup_bank);
$bb_backup_type_tooltip = __("In this field, you would need to choose the type of backup that you would like to create",wp_backup_bank);
$bb_complete_backup = __("Complete Backup",wp_backup_bank);
$bb_only_database = __("Only Database",wp_backup_bank);
$bb_only_filesystem = __("Only Filesystem",wp_backup_bank);
$bb_only_plugins_and_themes = __("Only Plugins and Themes",wp_backup_bank);
$bb_only_themes = __("Only Themes",wp_backup_bank);
$bb_only_plugins = __("Only Plugins",wp_backup_bank);
$bb_wp_content_folder = __("Only WP Content Folder",wp_backup_bank);
$bb_exclude_list = __("Exclude List",wp_backup_bank);
$bb_exclude_list_tooltip = __("In this field, you would need to provide extensions separating by commas to exclude those files from list",wp_backup_bank);
$bb_exclude_list_placeholder = __("Please provide file extensions",wp_backup_bank);
$bb_file_compression = __("File Compression Type",wp_backup_bank);
$bb_file_compression_tooltip = __(" In this field, you would need to choose compression type for your backup file from dropdown",wp_backup_bank);
$bb_db_compression = __("DB Compression Type",wp_backup_bank);
$bb_db_compression_tooltip = __("In this field, you would need to choose compression type for your database backup file from dropdown",wp_backup_bank);
$bb_backup_destination = __("Backup Destination",wp_backup_bank);
$bb_backup_destination_tooltip = __("In this field, you would need to choose storage space for your backup from dropdown. Before this, you would need to configure settings of remote storage",wp_backup_bank);
$bb_local_folder = __("Local Folder",wp_backup_bank);
$bb_dropbox = __("Dropbox",wp_backup_bank);
$bb_onedrive = __("OneDrive",wp_backup_bank);
$bb_email = __("Email",wp_backup_bank);
$bb_ftp = __("FTP",wp_backup_bank);
$bb_dropbox_not_configured = __("Dropbox (Only Available in Premium Editions!)",wp_backup_bank);
$bb_email_not_configured = __("Email (Not configured!)",wp_backup_bank);
$bb_ftp_not_configured = __("FTP (Not configured!)",wp_backup_bank);
$bb_table_names = __("Please choose Tables for Backup",wp_backup_bank);
$bb_backup_tables = __("Backup Tables",wp_backup_bank);
$bb_backup_tables_tooltip = __("In this field, you would need to choose tables for which you would like to take a backup",wp_backup_bank);
$bb_archive_name = __("Archive Name",wp_backup_bank);
$bb_archive_name_tooltip = __("In this field, you would need to provide the name and format in which you would like to save your backup file",wp_backup_bank);
$bb_archive_name_placeholder = __("Please provide your Archive Name",wp_backup_bank);
$bb_preview = __("Preview",wp_backup_bank);
$bb_start_backup = __("Start Backup",wp_backup_bank);
$bb_folder_location = __("Folder Location",wp_backup_bank);
$bb_folder_location_tooltip = __("In this field, you would need to provide a Local Folder path to store your Backup",wp_backup_bank);
$bb_folder_location_placeholder = __("Please provide a Local Folder Path",wp_backup_bank);
$bb_cc_email = __("CC",wp_backup_bank);
$bb_bcc_email = __("BCC",wp_backup_bank);
$bb_cc_placeholder = __("Please provide CC Email Address", wp_backup_bank);
$bb_bcc_placeholder = __("Please provide BCC Email Address", wp_backup_bank);
$bb_email_address_tooltip = __("In this field, you would need to provide a valid Email Address where you would like to send backup with backup details", wp_backup_bank);
$bb_extention_not_found = __(" ( Extension not supported )", wp_backup_bank);
$bb_email_message = __("Message",wp_backup_bank);
$bb_email_message_tooltip = __("In this field, you would need to provide message which has to be sent to user when the backup is successfully scheduled", wp_backup_bank);
$bb_email_cc_tooltip = __(" In this field, you would need to provide valid CC Email Address", wp_backup_bank);
$bb_email_bcc_tooltip = __("In this field, you would need to provide valid BCC Email Address", wp_backup_bank);
$bb_database = __("Database Backup",wp_backup_bank);
$bb_plugins = __("Plugins Backup",wp_backup_bank);
$bb_plugins_themes = __("Plugins and Themes Backup",wp_backup_bank);
$bb_themes = __("Themes Backup",wp_backup_bank);
$bb_contents = __("Contents Backup",wp_backup_bank);
$bb_filesystem = __("Filesystem Backup",wp_backup_bank);
$bb_never = __("Never",wp_backup_bank);
$bb_na = __("N/A",wp_backup_bank);
$bb_onedrive_not_configured = __("OneDrive (Only Available in Premium Editions!)",wp_backup_bank);
$bb_google_drive_not_configured = __("Google Drive (Only Available in Premium Editions!)",wp_backup_bank);
$bb_google_drive_settings = __("Google Drive",wp_backup_bank);
$bb_redirect_url = __("Redirect URI",wp_backup_bank);
$bb_redirect_url_tooltip = __("Please copy this URI and paste into Redirect URI field when creating your app",wp_backup_bank);
$bb_amazons3_not_configured = __("Amazon S3 (Only Available in Premium Editions!)",wp_backup_bank);
$bb_amazons3 = __("Amazon S3",wp_backup_bank);
$bb_rackspace_not_configured = __("Rackspace (Only Available in Premium Editions!)",wp_backup_bank);
$bb_rackspace = __("Rackspace",wp_backup_bank);
$bb_azure_not_configured = __("Microsoft Azure (Only Available in Premium Editions!)",wp_backup_bank);
$bb_ms_azure = __("Microsoft Azure",wp_backup_bank);

// Roles and Capabilities
$bb_roles_capabilities_show_menu = __("Show Backup Bank Menu",wp_backup_bank);
$bb_roles_capabilities_show_menu_tooltip = __("In this field, you would need to choose a specific role which can see Sidebar Menu",wp_backup_bank);
$bb_roles_capabilities_administrator = __("Administrator",wp_backup_bank);
$bb_roles_capabilities_author = __("Author",wp_backup_bank);
$bb_roles_capabilities_editor = __("Editor",wp_backup_bank);
$bb_roles_capabilities_contributor = __("Contributor",wp_backup_bank);
$bb_roles_capabilities_subscriber = __("Subscriber",wp_backup_bank);
$bb_roles_capabilities_topbar_menu = __("Show Backup Bank Top Bar Menu",wp_backup_bank);
$bb_roles_capabilities_topbar_menu_tooltip = __("In this field, you would need to choose a specific option to show Backup Bank Top Bar Menu", wp_backup_bank);
$bb_roles_capabilities_administrator_role =  __("An Administrator Role can do the following ",wp_backup_bank);
$bb_roles_capabilities_administrator_role_tooltip = __("Administrators will have by default full control to manage different options in Backup Bank, so all checkboxes will already be selected for the Administrator Role as mentioned below", wp_backup_bank);
$bb_roles_capabilities_full_control = __("Full Control",wp_backup_bank);
$bb_roles_capabilities_author_role = __("An Author Role can do the following ",wp_backup_bank);
$bb_roles_capabilities_author_role_tooltip = __("You can choose what pages could be accessed by users having an Author Role on your Backup Bank Plugin. This could be achieved with the help of checkboxes mentioned below", wp_backup_bank);
$bb_roles_capabilities_editor_role = __("An Editor Role can do the following ",wp_backup_bank);
$bb_roles_capabilities_editor_role_tooltip = __("You can choose what pages could be accessed by users having Editor Role on your Backup Bank Plugin. This could be achieved with the help of checkboxes mentioned below", wp_backup_bank);
$bb_roles_capabilities_contributor_role = __("A Contributor Role can do the following ",wp_backup_bank);
$bb_roles_capabilities_contributor_role_tooltip = __("You can choose what pages could be accessed by  users having Contributor Role on your Backup Bank Plugin. This could be achieved with the help of checkboxes mentioned below", wp_backup_bank);
$bb_roles_capabilities_subscriber_role = __("A Subscriber Role can do the following",wp_backup_bank);
$bb_roles_capabilities_subscriber_role_tooltip = __("You can choose what pages could be accessed by users having a Subscriber Role on your Backup Bank Plugin. This could be achieved with the help of checkboxes mentioned below", wp_backup_bank);
$bb_roles_capabilities_other_role = __("Other Roles can do the following",wp_backup_bank);
$bb_roles_capabilities_other_role_tooltip = __("Please choose specific page available for Others Role Access", wp_backup_bank);
$bb_roles_and_capabilities_other_roles_capabilities = __("Please tick the appropriate capabilities for security purposes ",wp_backup_bank);
$bb_roles_and_capabilities_other_roles_capabilities_tooltip = __("Only users with these capabilities can access Backup Bank", wp_backup_bank);
$bb_roles_capabilities_other = __("Others",wp_backup_bank);

//Onedrive Settings
$bb_onedrive_get_client_id = __("Get your OneDrive Application Id & Application Secrets", wp_backup_bank);
$bb_onedrive_backup_to = __("Backup to OneDrive", wp_backup_bank);
$bb_onedrive_tooltip = __("If you would like to store your Backup over OneDrive then you would need to choose enable or vice-versa from drop down", wp_backup_bank);
$bb_onedrive_client_id_tooltip = __("In this field, you would need to provide Application Id. It is an alphanumeric string which you will get by signing-in into OneDrive account", wp_backup_bank);
$bb_onedrive_client_id_placeholder = __("Please provide valid Application Id", wp_backup_bank);
$bb_onedrive_client_secret = __("Application Secrets", wp_backup_bank);
$bb_onedrive_client_secret_tooltip = __("In this field, you would need to provide Application Secrets which will you get after configuring your OneDrive account", wp_backup_bank);
$bb_onedrive_client_secret_placeholder = __("Please provide valid Application Secrets", wp_backup_bank);
$bb_client_id = __("Application Id",wp_backup_bank);
$bb_onedrive_skd_application = __("Create new OneDrive Live SDK Application", wp_backup_bank);

// Amazon S3 Settings
$bb_amazons3_backup_to = __("Backup to Amazon S3", wp_backup_bank);
$bb_amazons3_tooltip = __("If you would like to store your Backup over Amazon S3 then you would need to choose enable or vice-versa from drop down", wp_backup_bank);
$bb_amazons3_asccess_key_id = __("Access Key Id",wp_backup_bank);
$bb_amazons3_asccess_key_id_tooltip = __("In this field, you would need to provide Access Key Id. It is an alphanumeric text string which you will get by signing-in into Amazon S3 account", wp_backup_bank);
$bb_amazons3_asccess_key_id_placeholder = __("Please provide valid Access Key Id", wp_backup_bank);
$bb_amazons3_secret_key = __("Secret Access Key", wp_backup_bank);
$bb_amazons3_secret_key_tooltip = __("In this field, you would need to provide Secret Access Key which will you get after configuring your Amazon S3 account", wp_backup_bank);
$bb_amazons3_secret_key_placeholder = __("Please provide valid Secret Access Key", wp_backup_bank);
$bb_amazons3_bucket_name = __("Bucket Name", wp_backup_bank);
$bb_amazons3_bucket_tooltip = __("In this field, you would need to provide Bucket Name which will you get from your Amazon S3 account", wp_backup_bank);
$bb_amazons3_bucket_placeholder = __("Please provide valid Bucket Name", wp_backup_bank);
$bb_amazons3_get_access_key_id_secret_key = __("Get your Amazon Access Key Id & Secret Access Key", wp_backup_bank);

//Rackspace Settings
$bb_rackspace_backup_to =  __("Backup to Rackspace", wp_backup_bank);
$bb_rackspace_tooltip = __("If you would like to store your Backup over Rackspace then you would need to choose enable or vice-versa from drop down", wp_backup_bank);
$bb_rackspace_username =  __("Username", wp_backup_bank);
$bb_rackspace_username_tooltip =  __(" In this field, you would need to provide Rackspace Username. You will get your username by signing-in into Rackspace account", wp_backup_bank);
$bb_rackspace_username_placeholder =  __("Please provide valid Username", wp_backup_bank);
$bb_rackspace_api_key =  __("Api Key", wp_backup_bank);
$bb_rackspace_api_key_tooltip =  __("In this field, you would need to provide API Key which will you get after configuring your Rackspace account", wp_backup_bank);
$bb_rackspace_api_key_placeholder =  __("Please provide valid Api Key", wp_backup_bank);
$bb_rackspace_container =  __("Container Name", wp_backup_bank);
$bb_rackspace_container_tooltip =  __("In this field, you would need to provide Container Name. you will get it from your Rackspace account", wp_backup_bank);
$bb_rackspace_container_placeholder =  __("Please provide a valid container name", wp_backup_bank);
$bb_rackspace_region =  __("Container Region", wp_backup_bank);
$bb_rackspace_region_tooltip = __("In this field, you would need to choose a region where your Rackspace container is created", wp_backup_bank);
$bb_rackspace_region_dfw =  __("Dallas (DFW)", wp_backup_bank);
$bb_rackspace_region_iad =  __("Northern Virginia (IAD)", wp_backup_bank);
$bb_rackspace_region_ord =  __("Chicago (ORD)", wp_backup_bank);
$bb_rackspace_region_lon =  __("London (LON)", wp_backup_bank);
$bb_rackspace_region_syd =  __("Sydney (SYD)", wp_backup_bank);
$bb_rackspace_region_hkg =  __("Hong Kong (HKG)", wp_backup_bank);
$bb_rackspace_get_credentials = __("Get your Rackspace Username & Api key", wp_backup_bank);

//MS Azure Settings
$bb_ms_azure_backup_to =  __("Backup to Microsoft Azure", wp_backup_bank);
$bb_ms_azure_tooltip = __("If you would like to store your Backup over Microsoft Azure then you would need to choose enable or vice-versa from drop down", wp_backup_bank);
$bb_ms_azure_account_name =  __("Account Name", wp_backup_bank);
$bb_ms_azure_account_name_tooltip =  __("In this field, you would need to provide Microsoft Azure Account Name. You will get your account name by signing-in to Microsoft Azure account", wp_backup_bank);
$bb_ms_azure_account_name_placeholder =  __("Please provide valid Account Name", wp_backup_bank);
$bb_ms_azure_access_key = __("Access Key", wp_backup_bank);
$bb_ms_azure_access_key_tooltip =  __("In this field, you would need to provide Access Key which will you get after configuring your Microsoft Azure account", wp_backup_bank);
$bb_ms_azure_access_key_placeholder =  __("Please provide valid Access Key", wp_backup_bank);
$bb_ms_azure_container =  __("Container Name", wp_backup_bank);
$bb_ms_azure_container_tooltip =  __(" In this field, you would need to provide Container Name. You will get it from your Microsoft Azure account", wp_backup_bank);
$bb_ms_azure_container_placeholder = __("Please provide a valid container name", wp_backup_bank);
$bb_ms_azure_get_client_account_details = __("Get your Azure Account Name & Access Key", wp_backup_bank);

// Feature Request
$bb_feature_requests_thank_you = __("Thank You!",wp_backup_bank);
$bb_feature_requests_suggest_some_features = __("Kindly fill in the below form, if you would like to suggest some features which are not in the Plugin",wp_backup_bank);
$bb_feature_requests_suggestion_complaint = __("If you also have any suggestion/complaint, you can use the same form below",wp_backup_bank);
$bb_feature_requests_write_us_on = __("You can also write us on",wp_backup_bank);
$bb_feature_requests_your_name = __("Your Name",wp_backup_bank);
$bb_feature_requests_your_name_tooltip = __("In this field, you would need to provide your Name which you would like to request to be added to this Plugin",wp_backup_bank);
$bb_feature_requests_your_name_placeholder = __("Please provide your Name",wp_backup_bank);
$bb_feature_requests_your_email = __("Your Email",wp_backup_bank);
$bb_feature_requests_your_email_tooltip = __("In this field, you would need to provide your valid Email Address which you would like to request to be added to this Plugin",wp_backup_bank);
$bb_feature_requests_your_email_placeholder = __("Please provide your Email Address",wp_backup_bank);
$bb_feature_requests_tooltip = __("In this field, you would need to provide a feature which you would like to request to be added to this Plugin",wp_backup_bank);
$bb_feature_requests_placeholder = __("Please provide your Feature Request",wp_backup_bank);
$bb_feature_requests_send_request = __("Send Request",wp_backup_bank);

// Manage Backup
$bb_manage_backups_destination = __("Destination", wp_backup_bank);
$bb_manage_backups_execution = __("Execution", wp_backup_bank);
$bb_manage_backups_status = __("Status", wp_backup_bank);
$bb_manage_backups_schedule_backup_btn = __("Schedule Backup", wp_backup_bank);
$bb_manage_backups_action = __("Action", wp_backup_bank);
$bb_manage_backups_execution_manual = __("Manual", wp_backup_bank);
$bb_manage_backups_execution_scheduled = __("Scheduled", wp_backup_bank);
$bb_manage_backups_status_not_yet = __("Not Yet Executed", wp_backup_bank);
$bb_manage_backups_status_running = __("Backup is Running", wp_backup_bank);
$bb_manage_backups_delete = __("Delete",wp_backup_bank);
$bb_manage_backups_download = __("Download",wp_backup_bank);
$bb_manage_backups_tooltip = __("Restore Backup",wp_backup_bank);
$bb_manage_backups_bulk_action = __("Bulk Action",wp_backup_bank);
$bb_manage_backups_apply = __("Apply",wp_backup_bank);
$bb_manage_backups_terminated = __("Backup Terminated",wp_backup_bank);
$bb_manage_backups_completed_successfully = __("Backup Completed Successfully",wp_backup_bank);
$bb_backup_executed_in = __("Executed In",wp_backup_bank);
$bb_backup_total_size = __("Total Size",wp_backup_bank);
$bb_backup_Details = __("Backup Details",wp_backup_bank);
$bb_last_execution = __("Last Execution",wp_backup_bank);
$bb_next_execution = __("Next Execution",wp_backup_bank);
$bb_manage_backups_last_status = __("Last Status", wp_backup_bank);
$bb_manage_backups_log = __("Log",wp_backup_bank);
$bb_manage_backups_download_backup = __("Download Backup",wp_backup_bank);
$bb_manage_backups_close = __("Close",wp_backup_bank);
$bb_manage_download_backup = __("Download Backup",wp_backup_bank);
$bb_manage_download_log_file = __("Download Log File",wp_backup_bank);
$bb_manage_download_restore_backup = __("Restore Backup",wp_backup_bank);
$bb_manage_select_backup = __("Select Backup",wp_backup_bank);
$bb_manage_select_log_backup = __("Select Log File",wp_backup_bank);
$bb_manage_backups_restored_successfully = __("Backup Restored Successfully",wp_backup_bank);
$bb_manage_backups_restore_terminated = __("Backup Restore Terminated",wp_backup_bank);
$bb_manage_backups_purge_backups = __("Purge Backups", wp_backup_bank);
$bb_compression_type = __("Compression Type", wp_backup_bank);
$bb_manage_rerun_backup = __("re-run",wp_backup_bank);
$bb_manage_backup_on = __("Backup Taken On ",wp_backup_bank);
$bb_manage_backup_restore_on = __("Backup Restore On ",wp_backup_bank);
$bb_manage_backups_uploading_to_ftp = __("Uploading Backup to Ftp",wp_backup_bank);
$bb_manage_backups_uploading_to_email = __("Uploading Backup to Email",wp_backup_bank);
$bb_manage_backups_uploading_to_dropbox = __("Uploading Backup to Dropbox",wp_backup_bank);
$bb_manage_backups_uploading_to_onedrive = __("Uploading Backup to OneDrive",wp_backup_bank);
$bb_manage_backups_uploading_to_google_drive = __("Uploading Backup to Google Drive",wp_backup_bank);
$bb_manage_backups_uploading_to_amazons3 = __("Uploading Backup to Amazon s3",wp_backup_bank);
$bb_manage_backups_uploading_to_rackspace = __("Uploading Backup to Rackspace",wp_backup_bank);
$bb_manage_backups_uploading_to_azure = __("Uploading Backup to Microsoft Azure",wp_backup_bank);

// Email Templates
$bb_choose_email_template = __("Choose Email Template", wp_backup_bank);
$bb_choose_email_template_tooltip = __("In this field, you would need to choose a specific template to configure Settings", wp_backup_bank);
$bb_email_template_send_to = __("Send To",wp_backup_bank);
$bb_email_template_send_to_tooltip = __("In this field, you would need to provide a valid email address where you would like to send an email notification when the backup is successfully generated", wp_backup_bank);
$bb_email_template_send_to_placeholder = __("Please provide Email Address", wp_backup_bank);
$bb_email_template_subject_tooltip = __(" In this field, you would need to provide the subject for email notification", wp_backup_bank);
$bb_email_template_subject_placeholder = __("Please provide Subject", wp_backup_bank);
$bb_email_template_for_backup_schedule = __("When Backup is Successfully Scheduled",wp_backup_bank);
$bb_email_template_for_generated_backup = __("When Backup is Successfully Generated",wp_backup_bank);
$bb_email_template_for_backup_failure = __("When Backup is Failed",wp_backup_bank);
$bb_email_template_for_restore_successfully = __("When Restore is Successfully Completed",wp_backup_bank);
$bb_email_template_for_restore_failure = __("When Restore is Failed",wp_backup_bank);

// Schedule Backup
$bb_schedule_backup_start_on = __("Start On",wp_backup_bank);
$bb_schedule_backup_start_on_tooltip = __("In this field, you would need to choose start date from Date Picker for scheduler to run", wp_backup_bank);
$bb_schedule_backup_start_on_placeholder = __("Please provide Start Date",wp_backup_bank);
$bb_schedule_backup_start_time = __("Start Time",wp_backup_bank);
$bb_schedule_backup_start_time_tooltip = __("In this field, you would need to choose a start time for scheduler to run at",wp_backup_bank);
$bb_schedule_backup_hrs = __(" hrs",wp_backup_bank);
$bb_schedule_backup_mins = __(" mins",wp_backup_bank);
$bb_schedule_backup_duration = __("Duration",wp_backup_bank);
$bb_schedule_backup_duration_tooltip = __("In this field, you would need to choose Time duration for schedule to run. It could be Hourly or Daily",wp_backup_bank);
$bb_schedule_backup_hourly = __("Hourly",wp_backup_bank);
$bb_schedule_backup_daily = __("Daily",wp_backup_bank);
$bb_schedule_backup_end_on = __("End On",wp_backup_bank);
$bb_schedule_backup_end_on_tooltip = __("If you would like to end schedule on a specific date, then you would need to choose on vice-versa",wp_backup_bank);
$bb_schedule_backup_end_time_on = __("On",wp_backup_bank);
$bb_schedule_backup_end_date = __("End Date",wp_backup_bank);
$bb_schedule_backup_end_date_tooltip = __("In this field, you would need to choose end date for Scheduler",wp_backup_bank);
$bb_schedule_backup_end_date_placeholder = __("Please provide End Date",wp_backup_bank);
$bb_schedule_backup_repeat_every = __("Repeat Every",wp_backup_bank);
$bb_schedule_backup_repeat_every_tooltip = __("In this field, you would need to provide repetition for the scheduler. The scheduler would run on selected values from drop-down.",wp_backup_bank);
$bb_schedule_backup_time_zone = __("Time Zone",wp_backup_bank);
$bb_schedule_backup_time_zone_tooltip = __("In this field, you would need to choose Time Zone so that the scheduler runs on time zone accordingly",wp_backup_bank);

// Alert Setup
$bb_alert_setup_email_template_for_backup_schedule_tooltip = __("In this field, you would need to choose Enable to automatically get notified by an email when the backup is successfully scheduled",wp_backup_bank);
$bb_alert_setup_email_template_for_generated_backup_tooltip = __("In this field, you would need to choose Enable to automatically get notified by an email when the backup is successfully generated",wp_backup_bank);
$bb_alert_setup_email_template_for_backup_failure_tooltip = __("In this field, you would need to choose Enable to automatically get notified by an email when backup is failing to complete, generate or schedule",wp_backup_bank);
$bb_alert_setup_email_template_for_restore_successfully_tooltip = __("In this field, you would need to choose Enable to automatically get notified by an email when restore is successfully completed",wp_backup_bank);
$bb_alert_setup_email_template_for_restore_failure_tooltip = __("In this field, you would need to choose Enable to automatically get notified by an email when restore is failing to complete, generate or schedule",wp_backup_bank);
$bb_email_for_backup_schedule = __("Email when a backup is Successfully Scheduled",wp_backup_bank);
$bb_email_for_generated_backup = __("Email when a backup is Successfully Generated",wp_backup_bank);
$bb_email_for_backup_failure = __("Email when a backup is Failed",wp_backup_bank);
$bb_email_for_restore_successfully = __("Email when Restore is Successfully Completed",wp_backup_bank);
$bb_email_for_restore_failure = __("Email when Restore is Failed",wp_backup_bank);

// Other Settings
$bb_other_setting_automatic_plugin_update = __("Automatic Plugin Updates", wp_backup_bank);
$bb_other_setting_automatic_plugin_update_tooltip = __("If you would like that plugin would be automatically updated whenever there is a new version available, then choose enable or vise-versa from drop-down", wp_backup_bank);
$bb_other_setting_maintenance_mode  = __("Maintenance Mode", wp_backup_bank);
$bb_other_setting_maintenance_mode_tooltip = __("If you would like to show maintenance message, then you would need to choose enable or vice-versa from drop down. Maintenance Mode automatically gets enabled or disabled while the database is restored",wp_backup_bank);
$bb_other_setting_maintenance_mode_message  = __("Maintenance Mode Message", wp_backup_bank);
$bb_other_setting_maintenance_mode_message_tooltip = __("In this field you will provide message that would be shown when Maintenance Mode is enabled.",wp_backup_bank);
$bb_other_setting_maintenance_mode_message_placeholder = __("Please provide Message",wp_backup_bank);
$bb_other_setting_remove_tables = __("Remove Tables At Uninstall", wp_backup_bank);
$bb_other_setting_remove_tables_tootltip = __("If you would like to remove tables at deletion of plugin then you would need to choose enable or vice-versa from drop down", wp_backup_bank);

// Dropbox Settings
$bb_dropbox_backup_to = __("Backup to Dropbox", wp_backup_bank);
$bb_dropbox_api_key_tooltip = __("In this field, you would need to provide App Key, which you will get after configuring your dropbox account", wp_backup_bank);
$bb_dropbox_api_key_placeholder = __("Please provide valid App key", wp_backup_bank);
$bb_dropbox_secret_key = __("App Secret", wp_backup_bank);
$bb_dropbox_secret_key_tooltip = __("In this field, you would need to provide App Secret here, which will you get after configuring your Dropbox account", wp_backup_bank);
$bb_dropbox_secret_key_placeholder = __("Please provide valid App Secret", wp_backup_bank);
$bb_dropbox_tooltip = __("If you would like to store your Backup over Dropbox then you would need to choose enable or vice-versa from drop down", wp_backup_bank);
$bb_dropbox_get_api_secret_key = __("Get your Dropbox App key & App Secret", wp_backup_bank);

// Email Settings
$bb_email_backup_to = __("Backup to Email", wp_backup_bank);
$bb_email_tooltip = __("If you would like to store your Backup on Email then you would need to choose enable or vice-versa from drop down", wp_backup_bank);
$bb_email_settings_email_address = __("Email Address",wp_backup_bank);
$bb_email_address_placeholder = __("Please provide Email Address", wp_backup_bank);
$bb_email_settings_subject_tooltip = __("In this field, you would need to provide the subject for email notification ", wp_backup_bank);
$bb_email_settings_subject_placeholder = __("Please provide Subject", wp_backup_bank);

// FTP Settings
$bb_ftp_settings_backup_to = __("Backup to FTP", wp_backup_bank);
$bb_ftp_settings_tooltip = __("If you would like to store your Backup on FTP then you would need to choose enable or vice-versa from drop down", wp_backup_bank);
$bb_ftp_settings_host = __("Host",wp_backup_bank);
$bb_ftp_settings_host_tooltip = __("In this field, you would need to provide a valid FTP host",wp_backup_bank);
$bb_ftp_settings_host_placeholder = __("Please provide your FTP Host",wp_backup_bank);
$bb_ftp_settings_ftp_username = __("Username",wp_backup_bank);
$bb_ftp_settings_ftp_username_tooltip = __("In this field, you would need to provide valid FTP Username",wp_backup_bank);
$bb_ftp_settings_ftp_username_placeholder = __("Please provide your FTP Username",wp_backup_bank);
$bb_ftp_settings_password = __("Password",wp_backup_bank);
$bb_ftp_settings_password_tooltip = __("In this field, you would need to provide valid FTP Password",wp_backup_bank);
$bb_ftp_settings_password_placeholder = __("Please provide your FTP Password",wp_backup_bank);
$bb_ftp_settings_remote_path = __("Remote Path",wp_backup_bank);
$bb_ftp_settings_remote_path_tooltip = __(" this field, you would need to provide Remote Path, where you would like to store your backup on the server",wp_backup_bank);
$bb_ftp_settings_remote_path_placeholder = __("Please provide your Remote Path",wp_backup_bank);
$bb_ftp_settings_protocol = __("Protocol",wp_backup_bank);
$bb_ftp_settings_protocol_tooltip = __("In this field, you would need to a Protocol according to your server. You would need to choose FTP, FTPS or SFTP over SSH",wp_backup_bank);
$bb_ftps_settings = __("FTPS",wp_backup_bank);
$bb_ftp_settings_sftp_over_ssh = __("SFTP over SSH",wp_backup_bank);
$bb_ftp_settings_login_type = __("Login Type",wp_backup_bank);
$bb_ftp_settings_login_type_tooltip = __("In this field, you would need to choose login type.",wp_backup_bank);
$bb_ftp_settings_username_password = __("Username & Password",wp_backup_bank);
$bb_ftp_settings_username_only = __("Username Only",wp_backup_bank);
$bb_ftp_settings_anonymous = __("Anonymous",wp_backup_bank);
$bb_ftp_settings_anonymous_no_login = __("No Login",wp_backup_bank);
$bb_ftp_settings_ftp_port = __("Port",wp_backup_bank);
$bb_ftp_settings_ftp_port_tooltip = __("In this field, you would need to provide Port Number related to your Protocol you are going to use",wp_backup_bank);
$bb_ftp_settings_ftp_port_placeholder = __("Please provide Port Number",wp_backup_bank);
$bb_ftp_settings_ftp_mode = __("FTP Mode",wp_backup_bank);
$bb_ftp_settings_ftp_mode_tooltip = __("In this field, you would need to choose the mode of FTP",wp_backup_bank);
$bb_ftp_settings_active_mode = __("Active",wp_backup_bank);
$bb_ftp_settings_passive_mode = __("Passive",wp_backup_bank);

// Mailer
$bb_scheduler = __("Scheduler",wp_backup_bank);
$bb_mailer_hours = __("Hours",wp_backup_bank);
$bb_mailer_hour = __("Hour",wp_backup_bank);

//Google Drive
$bb_google_drive_backup_to = __("Backup to Google Drive",wp_backup_bank);
$bb_google_drive_tooltip = __(" If you would like to store your Backup over Google Drive then you would need to choose enable or vice-versa from drop down ",wp_backup_bank);
$bb_google_drive_client_id = __("Client ID",wp_backup_bank);
$bb_google_drive_client_id_tooltip = __("In this field, you would need to provide Client Id. It is an alphanumeric string which you will get by signing-in to Google Drive account",wp_backup_bank);
$bb_google_drive_client_id_placeholder = __("Please provide Client Id",wp_backup_bank);
$bb_google_drive_secret_key = __("Client Secret",wp_backup_bank);
$bb_google_drive_secret_key_tooltip = __("In this field, you would need to provide Client Secret here, which will you get after configuring your Google Drive account. Client Secret is like a secret key which is needed to configure your account",wp_backup_bank);
$bb_google_drive_secret_key_placeholder = __("Please provide Client Secret",wp_backup_bank);
$bb_google_drive_get_client_secret_key = __("Get your Google Drive Client Id & Client Secret",wp_backup_bank);

//Disclaimer
$bb_important_disclaimer = __("Important Disclaimer!",wp_backup_bank);
$bb_premium_edition_features_disclaimer = __("Premium Edition Features :",wp_backup_bank);
$bb_ftp_requirements = __("* FTP Require PHP Version 5.3.0 or Greater",wp_backup_bank);
$bb_premium_editions = __("Premium Editions", wp_backup_bank);

?>
