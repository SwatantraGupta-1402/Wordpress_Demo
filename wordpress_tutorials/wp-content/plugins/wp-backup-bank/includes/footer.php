<?php

/**
* This file contains javascript code.
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
		</div>
		<script type="text/javascript">
			jQuery(".tooltips").tooltip_tip({placement: "right"});
			if (typeof(load_sidebar_content_backup_bank) != "function")
			{
				function load_sidebar_content_backup_bank()
				{
					var menus_height = jQuery(".page-sidebar-menu-tech-banker").height();
					var content_height = jQuery(".page-content").height() + 30;
					if(parseInt(menus_height) > parseInt(content_height))
					{
						jQuery(".page-content").attr("style","min-height:"+menus_height+"px")
					}
					else
					{
						jQuery(".page-sidebar-menu-tech-banker").attr("style","min-height:"+content_height +"px")
					}
				}
			}
			jQuery(".page-sidebar-tech-banker").on("click", "li > a", function (e)
			{
				var hasSubMenu = jQuery(this).next().hasClass("sub-menu");
				var parent = jQuery(this).parent().parent();
				var sidebar_menu = jQuery(".page-sidebar-menu-tech-banker");
				var sub = jQuery(this).next();
				var slideSpeed = parseInt(sidebar_menu.data("slide-speed"));
				parent.children("li.open").children(".sub-menu:not(.always-open)").slideUp(slideSpeed);
				parent.children("li.open").removeClass("open");
				var sidebar_close = parent.children("li.open").removeClass("open");
				if(sidebar_close)
				{
					setInterval(load_sidebar_content_backup_bank ,100);
				}
				if (sub.is(":visible"))
				{
					jQuery(this).parent().removeClass("open");
					sub.slideUp(slideSpeed);
				}
				else if (hasSubMenu)
				{
					jQuery(this).parent().addClass("open");
					sub.slideDown(slideSpeed);
				}
			});
			var sidebar_load_interval = setInterval(load_sidebar_content_backup_bank ,1000);
			setTimeout(function()
			{
				clearInterval(sidebar_load_interval);
			}, 5000);
			if(typeof(overlay_loading_backup_bank) != "function")
			{
				function overlay_loading_backup_bank(control_id)
				{
					var overlay_opacity = jQuery("<div class=\"opacity_overlay\"></div>");
					jQuery("body").append(overlay_opacity);
					var overlay = jQuery("<div class=\"loader_opacity\"><div class=\"processing_overlay\"></div></div>");
					jQuery("body").append(overlay);
					if(control_id != undefined)
					{
						switch(control_id)
						{
							case "feature_request":
								var message = <?php echo json_encode($bb_feature_request);?>;
								var success = <?php echo json_encode($bb_success);?>;
							break;
							case "alert_setup":
								var message = <?php echo json_encode($bb_update_alert_setup);?>;
								var success = <?php echo json_encode($bb_success);?>;
							break;
							case "other_settings":
								var message = <?php echo json_encode($bb_update_other_settings);?>;
								var success = <?php echo json_encode($bb_success);?>;
							break;
							case "update_email_settings":
								var message = <?php echo json_encode($bb_update_email_settings);?>;
								var success = <?php echo json_encode($bb_success);?>;
							break;
							case "delete_backup":
								var message = <?php echo json_encode($bb_delete_backups);?>;
								var success = <?php echo json_encode($bb_success);?>;
							break;
							case "bulk_delete_backup":
								var message = <?php echo json_encode($bb_bulk_delete_backups);?>;
								var success = <?php echo json_encode($bb_success);?>;
							break;
							case "update_ftp_settings":
								var message = <?php echo json_encode($bb_update_ftp_settings);?>;
								var success = <?php echo json_encode($bb_success);?>;
							break;
						}
						var issuccessmessage = jQuery("#toast-container").exists();
						if(issuccessmessage != true)
						{
							var shortCutFunction = jQuery("#manage_messages input:checked").val();
							var $toast = toastr[shortCutFunction](message, success);
						}
					}
				}
			}
			if (typeof(remove_overlay_backup_bank) != "function")
			{
				function remove_overlay_backup_bank()
				{
					jQuery(".loader_opacity").remove();
					jQuery(".opacity_overlay").remove();
				}
			}
			if(typeof(validate_digits_backup_bank) != "function")
			{
				function validate_digits_backup_bank(event)
				{
					if(event.which!=8 && event.which!=0 && (event.which<48 || event.which>57))
					{
						event.preventDefault();
					}
				}
			}
			if(typeof(open_popup_backup_bank) != "function")
			{
				function open_popup_backup_bank()
				{
					jQuery("[data-popup-open]").on("click", function(e)
					{
						var targeted_popup_class = jQuery(this).attr("data-popup-open");
						jQuery('[data-popup="' + targeted_popup_class + '"]').fadeIn(350);
						e.preventDefault();
					});
					// Close popup
					jQuery("[data-popup-close]").on("click", function(e)
					{
						var targeted_popup_class = jQuery(this).attr("data-popup-close");
						jQuery('[data-popup="' + targeted_popup_class + '"]').fadeOut(350);

						e.preventDefault();
					});
					jQuery(document).keydown(function(e)
					{
						// ESCAPE key pressed
						if (e.keyCode == 27)
						{
							var targeted_popup_class = jQuery("[data-popup-close]").attr("data-popup-close");
							jQuery('[data-popup="' + targeted_popup_class + '"]').fadeOut(350);
						}
					});
				}
			}
			if(typeof(date) != "function")
			{
				function date(format, timestamp)
				{
					var that = this,jsdate,f,formatChr = /\\?([a-z])/gi,formatChrCb,
					_pad = function (n, c)
					{
						n = n.toString();
						return n.length < c ? _pad('0' + n, c, '0') : n;
					},
					txt_words = ["Sun", "Mon", "Tues", "Wednes", "Thurs", "Fri", "Satur", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
					formatChrCb = function (t, s)
					{
						return f[t] ? f[t]() : s;
					};
					f =
					{
						d: function ()
						{
							return _pad(f.j(), 2);
						},
						D: function ()
						{
							return f.l().slice(0, 3);
						},
						j: function ()
						{
							return jsdate.getDate();
						},
						l: function ()
						{
							return txt_words[f.w()] + 'day';
						},
						N: function ()
						{
							return f.w() || 7;
						},
						S: function()
						{
							var j = f.j(),
							i = j%10;
							if (i <= 3 && parseInt((j%100)/10) == 1) i = 0;
							return ['st', 'nd', 'rd'][i - 1] || 'th';
						},
						w: function ()
						{
							return jsdate.getDay();
						},
						z: function ()
						{
							var a = new Date(f.Y(), f.n() - 1, f.j()),
							b = new Date(f.Y(), 0, 1);
							return Math.round((a - b) / 864e5);
						},
						W: function ()
						{
							var a = new Date(f.Y(), f.n() - 1, f.j() - f.N() + 3),
							b = new Date(a.getFullYear(), 0, 4);
							return _pad(1 + Math.round((a - b) / 864e5 / 7), 2);
						},
						F: function ()
						{
							return txt_words[6 + f.n()];
						},
						m: function ()
						{
							return _pad(f.n(), 2);
						},
						M: function ()
						{
							return f.F().slice(0, 3);
						},
						n: function ()
						{
							return jsdate.getMonth() + 1;
						},
						t: function ()
						{
							return (new Date(f.Y(), f.n(), 0)).getDate();
						},
						L: function ()
						{
							var j = f.Y();
							return j % 4 === 0 & j % 100 !== 0 | j % 400 === 0;
						},
						o: function ()
						{
							var n = f.n(),
							W = f.W(),
							Y = f.Y();
							return Y + (n === 12 && W < 9 ? 1 : n === 1 && W > 9 ? -1 : 0);
						},
						Y: function ()
						{
							return jsdate.getFullYear();
						},
						y: function ()
						{
							return f.Y().toString().slice(-2);
						},
						a: function ()
						{
							return jsdate.getHours() > 11 ? "pm" : "am";
						},
						A: function ()
						{
							return f.a().toUpperCase();
						},
						B: function ()
						{
							var H = jsdate.getUTCHours() * 36e2,
							i = jsdate.getUTCMinutes() * 60,
							s = jsdate.getUTCSeconds();
							return _pad(Math.floor((H + i + s + 36e2) / 86.4) % 1e3, 3);
						},
						g: function ()
						{
							return f.G() % 12 || 12;
						},
						G: function ()
						{
							return jsdate.getHours();
						},
						h: function ()
						{
							return _pad(f.g(), 2);
						},
						H: function ()
						{
							return _pad(f.G(), 2);
						},
						i: function ()
						{
							return _pad(jsdate.getMinutes(), 2);
						},
						s: function ()
						{
							return _pad(jsdate.getSeconds(), 2);
						},
						u: function ()
						{
							return _pad(jsdate.getMilliseconds() * 1000, 6);
						},
						I: function ()
						{
							var a = new Date(f.Y(), 0),
							c = Date.UTC(f.Y(), 0),
							b = new Date(f.Y(), 6),
							d = Date.UTC(f.Y(), 6);
							return ((a - c) !== (b - d)) ? 1 : 0;
						},
						O: function ()
						{
							var tzo = jsdate.getTimezoneOffset(),
							a = Math.abs(tzo);
							return (tzo > 0 ? "-" : "+") + _pad(Math.floor(a / 60) * 100 + a % 60, 4);
						},
						T: function ()
						{
							return 'UTC';
						},
						Z: function ()
						{
							return -jsdate.getTimezoneOffset() * 60;
						},
						U: function ()
						{
							return jsdate / 1000 | 0;
						}
					};
					this.date = function (format, timestamp)
					{
						that = this;
						jsdate = (timestamp === undefined ? new Date() :
							(timestamp instanceof Date) ? new Date(timestamp) :
								new Date(timestamp * 1000)
							);
						return format.replace(formatChr, formatChrCb);
					};
					return this.date(format, timestamp);
				}
			}
			if(typeof(prevent_paste_backup_bank) != "function")
			{
				function prevent_paste_backup_bank(control_id)
				{
					jQuery("#"+control_id).live("paste",function(e)
					{
						e.preventDefault();
					});
				}
			}
			if(typeof(check_value_backup_bank) != "function")
			{
				function check_value_backup_bank(id)
				{
					jQuery(id).val() == "" ? jQuery(id).val(0) : jQuery(id).val();
				}
			}
			if(typeof(premium_edition_notification_backup_bank) != "function")
			{
				function premium_edition_notification_backup_bank()
				{
					var premium_edition = <?php echo json_encode($bb_message_premium_edition); ?>;
					var shortCutFunction = jQuery("#toastTypeGroup_error input:checked").val();
					var $toast = toastr[shortCutFunction](premium_edition);
				}
			}
			if(typeof(base64_encode)!= "function")
			{
				function base64_encode(data)
				{
					var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
					var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
					ac = 0,
					enc = '',
					tmp_arr = [];
					if (!data)
					{
						return data;
					}
					do
					{
						o1 = data.charCodeAt(i++);
						o2 = data.charCodeAt(i++);
						o3 = data.charCodeAt(i++);
						bits = o1 << 16 | o2 << 8 | o3;
						h1 = bits >> 18 & 0x3f;
						h2 = bits >> 12 & 0x3f;
						h3 = bits >> 6 & 0x3f;
						h4 = bits & 0x3f;
						tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
					} while (i < data.length);
					enc = tmp_arr.join('');
					var r = data.length % 3;
					return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);
				}
			}
			jQuery("#ux_txt_archive_name").keyup(function()
			{
				if(jQuery("#ux_ddl_backup_type").val() == "only_database")
				{
					var type_of_compression = jQuery("#ux_ddl_db_compression_type").val();
				}
				else
				{
					var type_of_compression = jQuery("#ux_ddl_file_compression_type").val();
				}
				var filename = jQuery(this).val();
				filename = filename.replace( "%d", date( "d" ) );
				filename = filename.replace( "%j", date( "j" ) );
				filename = filename.replace( "%m", date( "m" ) );
				filename = filename.replace( "%n", date( "n" ) );
				filename = filename.replace( "%Y", date( "Y" ) );
				filename = filename.replace( "%y", date( "y" ) );
				filename = filename.replace( "%a", date( "a" ) );
				filename = filename.replace( "%A", date( "A" ) );
				filename = filename.replace( "%B", date( "B" ) );
				filename = filename.replace( "%g", date( "g" ) );
				filename = filename.replace( "%G", date( "G" ) );
				filename = filename.replace( "%h", date( "h" ) );
				filename = filename.replace( "%H", date( "H" ) );
				filename = filename.replace( "%i", date( "i" ) );
				filename = filename.replace( "%s", date( "s" ) );
				filename = filename.replace( "%W", date( "W" ) );
				filename = filename.replace( "%l", date( "l" ) );
				filename = filename.replace( "%D", date( "D" ) );
				filename = filename.replace( "%N", date( "N" ) );
				filename = filename.replace( "%S", date( "S" ) );
				filename = filename.replace( "%w", date( "w" ) );
				filename = filename.replace( "%z", date( "z" ) );
				filename = filename.replace( "%F", date( "F" ) );
				filename = filename.replace( "%M", date( "M" ) );
				filename = filename.replace( "%t", date( "t" ) );
				filename = filename.replace( "%L", date( "L" ) );
				filename = filename.replace( "%o", date( "o" ) );
				filename = filename.replace( "%u", date( "u" ) );
				filename = filename.replace( "%I", date( "I" ) );
				filename = filename.replace( "%O", date( "O" ) );
				filename = filename.replace( "%T", date( "T" ) );
				filename = filename.replace( "%Z", date( "Z" ) );
				filename = filename.replace( "%U", date( "U" ) );
				jQuery("#archivename").html(backbank_htmlspecialchars(filename,type_of_compression));
				jQuery("#archive_name_hidden").html(backupbank_removespecialchars(filename));
			});
			backbank_htmlspecialchars = function(string,type_of_compression)
			{
				var concate = string + type_of_compression;
				return jQuery("<span>").text(concate).html();
			};
			backupbank_removespecialchars = function(string)
			{
				return jQuery("<span>").text(string).html();
			};
			if(typeof(file_compression_backup_bank) !="function")
			{
				function file_compression_backup_bank()
				{
					var string = jQuery("#archive_name_hidden").text();
					var compression_type = jQuery("#ux_ddl_file_compression_type").val();
					switch(compression_type)
					{
						case ".zip":
							type_of_compression = ".zip";
						break;
						case ".tar":
							type_of_compression = ".tar";
						break;
						case ".tar.gz":
							type_of_compression = ".tar.gz";
						break;
						case ".tar.bz2":
							type_of_compression = ".tar.bz2";
						break;
					}
					jQuery("#archivename").html(backbank_htmlspecialchars(string,type_of_compression));
					load_sidebar_content_backup_bank();
				}
			}
			if(typeof(db_compression_backup_bank) != "function")
			{
				function db_compression_backup_bank()
				{
					var string = jQuery("#archive_name_hidden").text();
					var compression_type = jQuery("#ux_ddl_db_compression_type").val();
					var database = jQuery("#ux_ddl_backup_type").val();
					if(database == "only_database")
					{
						switch(compression_type)
						{
							case ".sql":
								type_of_compression = ".sql";
							break;
							case ".sql.zip":
								type_of_compression = ".sql.zip";
							break;
							case ".sql.gz":
								type_of_compression = ".sql.gz";
							break;
							case ".sql.bz2":
								type_of_compression = ".sql.bz2";
							break;
						}
					}
					jQuery("#archivename").html(backbank_htmlspecialchars(string,type_of_compression));
					load_sidebar_content_backup_bank();
				}
			}
			if(typeof(backup_type_backup_bank) != "function")
			{
				function backup_type_backup_bank()
				{
					var string = jQuery("#archive_name_hidden").text();
					var type = jQuery("#ux_ddl_backup_type").val();
					var folder_location  = jQuery("#ux_txt_backup_type").val();
					switch (type)
					{
						case "complete_backup":
							jQuery("#ux_div_exclude_list").css("display","block");
							jQuery("#ux_div_file_compression_type").css("display","block");
							jQuery("#ux_div_db_compression_type").css("display","block");
							jQuery("#ux_div_backup_tables").css("display","block");
							jQuery("#ux_txt_folder_location").val(folder_location +"/complete/");
							file_compression_backup_bank();
						break;
						case "only_database":
							jQuery("#ux_div_exclude_list").css("display","none");
							jQuery("#ux_div_file_compression_type").css("display","none");
							jQuery("#ux_div_db_compression_type").css("display","block");
							jQuery("#ux_div_backup_tables").css("display","block");
							jQuery("#ux_txt_folder_location").val(folder_location +"/database/");
							db_compression_backup_bank();
						break;
						case "only_filesystem":
							jQuery("#ux_div_exclude_list").css("display","block");
							jQuery("#ux_div_file_compression_type").css("display","block");
							jQuery("#ux_div_db_compression_type").css("display","none");
							jQuery("#ux_div_backup_tables").css("display","none");
							jQuery("#ux_txt_folder_location").val(folder_location +"/file-system/");
							file_compression_backup_bank();
						break;
						case "only_plugins_and_themes":
							jQuery("#ux_div_exclude_list").css("display","block");
							jQuery("#ux_div_file_compression_type").css("display","block");
							jQuery("#ux_div_db_compression_type").css("display","none");
							jQuery("#ux_div_backup_tables").css("display","none");
							jQuery("#ux_txt_folder_location").val(folder_location +"/plugin-themes/");
							file_compression_backup_bank();
						break;
						case "only_themes":
							jQuery("#ux_div_exclude_list").css("display","block");
							jQuery("#ux_div_file_compression_type").css("display","block");
							jQuery("#ux_div_db_compression_type").css("display","none");
							jQuery("#ux_div_backup_tables").css("display","none");
							jQuery("#ux_txt_folder_location").val(folder_location +"/themes/");
							file_compression_backup_bank();
						break;
						case "only_plugins":
							jQuery("#ux_div_exclude_list").css("display","block");
							jQuery("#ux_div_file_compression_type").css("display","block");
							jQuery("#ux_div_db_compression_type").css("display","none");
							jQuery("#ux_div_backup_tables").css("display","none");
							jQuery("#ux_txt_folder_location").val(folder_location +"/plugins/");
							file_compression_backup_bank();
						break;
						case "only_wp_content_folder":
							jQuery("#ux_div_exclude_list").css("display","block");
							jQuery("#ux_div_file_compression_type").css("display","block");
							jQuery("#ux_div_db_compression_type").css("display","none");
							jQuery("#ux_div_backup_tables").css("display","none");
							jQuery("#ux_txt_folder_location").val(folder_location +"/content/");
							file_compression_backup_bank();
						break;
					}
					load_sidebar_content_backup_bank();
				}
			}
			if(typeof(backup_destination_backup_bank) != "function")
			{
				function backup_destination_backup_bank()
				{
					var destination_type = jQuery("#ux_ddl_backup_destination_type").val();
					switch(destination_type)
					{
						case "local_folder":
							jQuery("#ux_div_backup_destination_local_folder").css("display","block");
							backup_type_backup_bank();
						break;
						case "email":
							jQuery("#ux_div_backup_destination_local_folder").css("display","none");
						break;
						case "ftp":
							jQuery("#ux_div_backup_destination_local_folder").css("display","none");
						break;
					}
					load_sidebar_content_backup_bank();
				}
			}
			if(typeof(remove_progress_bar_backup_bank) != "function")
			{
				function remove_progress_bar_backup_bank()
				{
					jQuery(".opacity_overlay").remove();
					jQuery(".progress-bar-position").remove();
				}
			}
			var count = 0;
			var progress_bar = "";
			if(typeof(progress_bar_message_backup_bank) != "function")
			{
				function progress_bar_message_backup_bank(fileurl)
				{
					if(progress_bar == "")
					{
						d = new Date();
						var random_number = Math.floor((Math.random() * 100000000) + 1);
						random_number = d.getTime() + random_number;
						var url = fileurl +"?_="+ random_number;
						jQuery.getJSON(url, function(data)
						{
							try
							{
								jQuery("#information").html(data.log);
								if(data.cloud == 1 || data.cloud == "undefined" || data.cloud == null)
								{
									jQuery("#progress_status").css("width",data.perc+"%");
									jQuery("#progress_status").text(data.perc+"%");
								}
								else
								{
									jQuery("#progress_status").css("width","100%");
									jQuery("#progress_status").text("100%");
									jQuery("#uploading_progress").removeClass("tech-banker-counter");
									jQuery("#uploaded_status").css("width",data.perc+"%");
									jQuery("#uploaded_status").text(data.perc+"%");
								}
								if(data.status == "terminated" || data.status == "file_exists")
								{
									setTimeout(function()
									{
										clearInterval(counter);
										setTimeout(function()
										{
											var issuccessmessage = jQuery("#toast-container").exists();
											if(issuccessmessage != true)
											{
												var shortCutFunction = jQuery("#toastTypeGroup_error input:checked").val();
												var $toast = toastr[shortCutFunction](<?php echo json_encode($bb_backup_terminated); ?>);
											}
											progress_bar = "stop";
											remove_progress_bar_backup_bank();
											window.location.href = "admin.php?page=bb_manage_backups";
										}, 3000);
									}, 5000);
								}
								else if(data.status == "completed_successfully")
								{
									setTimeout(function()
									{
										clearInterval(counter);
										setTimeout(function()
										{
											var issuccessmessage = jQuery("#toast-container").exists();
											if(issuccessmessage != true)
											{
												var shortCutFunction = jQuery("#manage_messages input:checked").val();
												var $toast = toastr[shortCutFunction](<?php echo json_encode($bb_backup_generated_successfully);?>, <?php echo json_encode($bb_success);?>);
											}
											progress_bar = "stop";
											remove_progress_bar_backup_bank();
											window.location.href = "admin.php?page=bb_manage_backups";
										}, 3000);
									}, 5000);
								}
								else if(data.status == "email_not_sent")
								{
									setTimeout(function()
									{
										clearInterval(counter);
										setTimeout(function()
										{
											var issuccessmessage = jQuery("#toast-container").exists();
											if(issuccessmessage != true)
											{
												var shortCutFunction = jQuery("#toastTypeGroup_error input:checked").val();
												var $toast = toastr[shortCutFunction](<?php echo json_encode($bb_backup_email); ?>);
											}
											progress_bar = "stop";
											remove_progress_bar_backup_bank();
											window.location.href = "admin.php?page=bb_manage_backups";
										}, 3000);
									}, 5000);
								}
							}
							catch (e)
							{
							}
						});
					}
				}
			}
			if(typeof(progress_bar_counter_backup_bank) != "function")
			{
				function progress_bar_counter_backup_bank(file_path)
				{
					count = count + 1;
					var secs = count%60;
					var mins = Math.floor(count/60)%60;
					var hrs = Math.floor(count/3600)%60;
					jQuery("#ux_secs").html(secs<10 ? "0"+secs : secs);
					jQuery("#ux_mins").html(mins<10 ? "0"+mins : mins);
					if(hrs != 0)
					{
						jQuery("#ux_hrs").removeClass("tech-banker-counter");
						jQuery("#ux_collon").removeClass("tech-banker-counter");
						jQuery("#ux_hrs").html(hrs<10 ? "0"+hrs : hrs);
					}
					else
					{
						jQuery("#ux_hrs").addClass("tech-banker-counter");
						jQuery("#ux_collon").addClass("tech-banker-counter");
					}
					progress_bar_message_backup_bank(file_path);
				}
			}
			if(typeof(resize_progress_bar_backup_bank) != "function")
			{
				function resize_progress_bar_backup_bank()
				{
					var width = jQuery(window).width();
					var heigth = jQuery(window).height();
					var myWindow = jQuery(".progress-bar-position");
					myWindow.show("fast");
					myWindow.offset(
					{
						left: (width - myWindow .width()) / 2 + jQuery(window).scrollLeft(),
						top: (heigth - myWindow .height()) / 2 + jQuery(window).scrollTop()
					});
				}
			}
			var counter = "";
			if(typeof(progress_bar_backup_bank) != "function")
			{
				function progress_bar_backup_bank(file_path)
				{
					var overlay_opacity = jQuery("<div class=\"opacity_overlay\"></div>");
					jQuery("body").append(overlay_opacity);
					var progress_bar = jQuery("#ux_div_portlet_progress").clone().html();
					jQuery("body").append(progress_bar);
					jQuery("#ux_div_portlet_progress").remove();
					resize_progress_bar_backup_bank();
					counter = setInterval(progress_bar_counter_backup_bank,1000,file_path);
				}
			}

			if(typeof(show_pop_up_backup_bank) != "function")
			{
				function show_pop_up_backup_bank()
				{
					open_popup_backup_bank();
				}
			}
			if(typeof(isUrlValid_backup_bank)!= "function")
			{
				function isUrlValid_backup_bank(url)
				{
					var value = jQuery("#"+url.id).val();
					var condition =  /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(value);
					return condition;
				}
			}
			var url = "<?php echo tech_banker_url."/feedbacks.php";?>";
			var domain_url = "<?php echo site_url(); ?>";
			<?php
			if(isset($_GET["page"]))
			{
				switch(esc_attr($_GET["page"]))
				{
					case "bb_start_backup":
						?>
						jQuery("#ux_bb_li_backups").addClass("active");
						jQuery("#ux_bb_li_generate_manual_backup").addClass("active");
						<?php
						if(manual_backup_bank == "1")
						{
							if($total_backups >= 5)
							{
								?>
								window.location.href = "admin.php?page=bb_manage_backups";
								<?php
							}
							?>
							jQuery(document).ready(function()
							{
								jQuery("#ux_txt_archive_name").keyup();
								backup_type_backup_bank();
								backup_destination_backup_bank();
								open_popup_backup_bank();
								load_sidebar_content_backup_bank();
								jQuery(document).keydown(function(e)
								{
									if(e.keyCode == 116)
									{
										jQuery(".ux_btn_generate_backup").removeAttr("disabled");
									}
								});
							});
							jQuery(window).resize(resize_progress_bar_backup_bank);
							jQuery("#ux_chk_select_all_first").click(function()
							{
								var check = jQuery(this);
								jQuery("#ux_frm_add_new_backup input[type=checkbox]").each(function()
								{
									jQuery(this).prop("checked", check.is(":checked"));
								})
							});
							jQuery(".all_check_backup_tables").click(function()
							{
								if (jQuery(".all_check_backup_tables:checked").length == jQuery(".all_check_backup_tables").length)
								{
									jQuery("#ux_chk_select_all_first").prop("checked", true);
								}
								else
								{
									jQuery("#ux_chk_select_all_first").prop("checked", false);
								}
							});
							if(typeof(check_tables_length_backup_bank) != "function")
							{
								function check_tables_length_backup_bank()
								{
									var backup_type = jQuery("#ux_ddl_backup_type").val();
									if(backup_type == "complete_backup" || backup_type == "only_database")
									{
										return jQuery(".all_check_backup_tables:checked").length == "0" ? false : true;
									}
									else
									{
										return true;
									}
								}
							}
							jQuery("#ux_frm_add_new_backup").validate
							({
								rules:
								{
									ux_txt_backup_name:
									{
										required: true
									},
									ux_txt_archive_name:
									{
										required: true
									},
									ux_txt_folder_location:
									{
										required: true
									}
								},
								errorPlacement: function (error, element)
								{
									var icon = jQuery(element).parent(".input-icon").children("i");
									icon.removeClass("fa-check").addClass("fa-warning");
									icon.attr("data-original-title", error.text()).tooltip_tip({"container": "body"});
								},
								highlight: function (element)
								{
									jQuery(element).closest(".form-group").removeClass("has-success").addClass("has-error");
								},
								success: function (label, element)
								{
									var icon = jQuery(element).parent(".input-icon").children("i");
									jQuery(element).closest(".form-group").removeClass("has-error").addClass("has-success");
									icon.removeClass("fa-warning").addClass("fa-check");
								},
								submitHandler:function(form)
								{
									var check_table_length = check_tables_length_backup_bank();
									if(check_table_length != false)
									{
										var backup_destination = jQuery("#ux_ddl_backup_destination_type").val();
										var archive_name = jQuery("#archive_name_hidden").text();
										var content_location = jQuery("#ux_txt_content_location").val();
										var folder_location = jQuery("#ux_txt_folder_location").val();
										jQuery(".ux_btn_generate_backup").attr("disabled","disabled");
										jQuery.post(ajaxurl,
										{
											type: "manual",
											content_location: base64_encode(content_location),
											folder_location: base64_encode(folder_location),
											archive_name: base64_encode(archive_name),
											backup_destination: base64_encode(backup_destination),
											param: "check_ftp_dropbox_connection",
											action: "backup_bank_action",
											_wp_nonce: "<?php echo $backup_bank_check_ftp_dropbox_connection; ?>"
										},
										function(data)
										{
											if(jQuery.trim(data) == "550")
											{
												var shortCutFunction = jQuery("#toastTypeGroup_error input:checked").val();
												var $toast = toastr[shortCutFunction](<?php echo json_encode($bb_error_file_upload); ?>);
												jQuery(".ux_btn_generate_backup").removeAttr("disabled");
											}
											else if(jQuery.trim(data) == "1")
											{
												var shortCutFunction = jQuery("#toastTypeGroup_error input:checked").val();
												var $toast = toastr[shortCutFunction](<?php echo json_encode($bb_ftp_connect); ?>);
												jQuery(".ux_btn_generate_backup").removeAttr("disabled");
											}
											else if(jQuery.trim(data) == "2")
											{
												var shortCutFunction = jQuery("#toastTypeGroup_error input:checked").val();
												var $toast = toastr[shortCutFunction](<?php echo json_encode($bb_could_not_connect); ?>);
												jQuery(".ux_btn_generate_backup").removeAttr("disabled");
											}
											else
											{
												var array_tables = [];
												var archive_name = jQuery("#archive_name_hidden").text();
												var archive = jQuery("#archivename").text();
												jQuery("#ux_tbl_database_tables input[type=checkbox][id*=ux_chk_add_new_backup_db_]").each(function()
												{
													if(jQuery(this).attr("checked"))
													{
														array_tables.push(jQuery(this).val());
													}
												});
												var date = new Date();
												var timezone_difference = date.getTimezoneOffset();
												progress_bar_backup_bank(data);
												progress_bar_message_backup_bank(data);
												jQuery.post(ajaxurl,
												{
													timezone_difference: timezone_difference,
													archive: archive,
													archive_name: archive_name,
													encrypted_tables: JSON.stringify(array_tables),
													data: base64_encode(jQuery("#ux_frm_add_new_backup").serialize()),
													param: "backup_bank_manual_backup_module",
													action: "backup_bank_action",
													_wp_nonce: "<?php echo $backup_bank_manual_backup; ?>"
												},
												function(data)
												{
												});
											}
										});
									}
									else
									{
										var shortCutFunction = jQuery("#toastTypeGroup_error input:checked").val();
										var $toast = toastr[shortCutFunction](<?php echo json_encode($bb_choose_tables); ?>);
									}
								}
							});
							<?php
						}
					break;
					case "bb_schedule_backup":
						?>
						jQuery("#ux_bb_li_backups").addClass("active");
						jQuery("#ux_bb_li_schedule_backup").addClass("active");
						<?php
						if(schedule_backup_bank == "1")
						{
							?>
							if(typeof(change_duration_backup_bank) != "function")
							{
								function change_duration_backup_bank()
								{
									var duration = jQuery("#ux_ddl_duration").val();
									switch(duration)
									{
										case "Hourly" :
											jQuery("#ux_div_repeat_every").css("display","block");
										break;
										case "Daily" :
											jQuery("#ux_div_repeat_every").css("display","none");
										break;
									}
									load_sidebar_content_backup_bank();
								}
							}
							if(typeof(change_end_time_backup_bank) != "function")
							{
								function change_end_time_backup_bank()
								{
									var duration = jQuery("#ux_ddl_end_time").val();
									switch(duration)
									{
										case "on" :
											jQuery("#ux_div_end_date").css("display","block");
										break;
										case "never" :
											jQuery("#ux_div_end_date").css("display","none");
										break;
									}
									load_sidebar_content_backup_bank();
								}
							}
							jQuery(document).ready(function()
							{
								jQuery("#ux_txt_archive_name").keyup();
								change_duration_backup_bank();
								change_end_time_backup_bank();
								backup_type_backup_bank();
								backup_destination_backup_bank();
								jQuery("#ux_txt_start_on").datepicker
								({
									dateFormat: "mm/dd/yy",
									numberOfMonths: 1,
									changeMonth: true,
									changeYear: true,
									yearRange: "1970:2039",
									onSelect: function(selected)
									{
										jQuery("#ux_txt_schedule_end_date").datepicker("option","minDate", selected)
									}
								});
								jQuery("#ux_txt_schedule_end_date").datepicker
								({
									numberOfMonths: 1,
									changeMonth: true,
									changeYear: true,
									yearRange: "1970:2039",
									onSelect: function(selected)
									{
										jQuery("#ux_txt_start_on").datepicker("option","maxDate", selected)
									}
								});
								open_popup_backup_bank();
								load_sidebar_content_backup_bank();
							});
							jQuery("#ux_chk_select_all_first").click(function()
							{
								var check = jQuery(this);
								jQuery("#ux_tbl_database_schedule_backup input[type=checkbox]").each(function()
								{
									jQuery(this).prop("checked", check.is(":checked"));
								})
							});
							jQuery(".all_check_backup_tables").click(function()
							{
								if(jQuery(".all_check_backup_tables:checked").length == jQuery(".all_check_backup_tables").length)
								{
									jQuery("#ux_chk_select_all_first").prop("checked", true);
								}
								else
								{
									jQuery("#ux_chk_select_all_first").prop("checked", false);
								}
							});
							if(typeof(check_tables_length_backup_bank) != "function")
							{
								function check_tables_length_backup_bank()
								{
									var backup_type = jQuery("#ux_ddl_backup_type").val();
									if(backup_type == "only_database")
									{
										return jQuery(".all_check_backup_tables:checked").length == "0" ? false : true;
									}
									else
									{
										return true;
									}
								}
							}
							jQuery("#ux_frm_schedule_backup").validate
							({
								rules:
								{
									ux_txt_start_on:
									{
										required: true
									},
									ux_txt_schedule_end_date:
									{
										required: true
									},
									ux_txt_backup_name:
									{
										required: true
									},
									ux_txt_archive_name:
									{
										required: true
									},
									ux_txt_folder_location:
									{
										required: true
									}
								},
								errorPlacement: function (error, element)
								{
									var icon = jQuery(element).parent(".input-icon").children("i");
									icon.removeClass("fa-check").addClass("fa-warning");
									icon.attr("data-original-title", error.text()).tooltip_tip({"container": "body"});
								},
								highlight: function (element)
								{
									jQuery(element).closest(".form-group").removeClass("has-success").addClass("has-error");
								},
								success: function (label, element)
								{
									var icon = jQuery(element).parent(".input-icon").children("i");
									jQuery(element).closest(".form-group").removeClass("has-error").addClass("has-success");
									icon.removeClass("fa-warning").addClass("fa-check");
								},
								submitHandler: function()
								{
									var check_table_length = check_tables_length_backup_bank();
									if(check_table_length != false)
									{
										premium_edition_notification_backup_bank();
									}
									else
									{
										var shortCutFunction = jQuery("#toastTypeGroup_error input:checked").val();
										var $toast = toastr[shortCutFunction](<?php echo json_encode($bb_choose_tables); ?>);
									}
								}
							});
							load_sidebar_content_backup_bank();
							<?php
						}
					break;
					case "bb_manage_backups";
						?>
						jQuery("#ux_bb_li_backups").addClass("active");
						jQuery("#ux_bb_li_manage_backups").addClass("active");
						<?php
						if(manage_backups_backup_bank == "1")
						{
							?>
							if(typeof(restore_progress_bar_backup_bank) != "function")
							{
								function restore_progress_bar_backup_bank(type,file_path)
								{
									if(type == "rerun")
									{
										jQuery("#progress_bar_heading").text("Re-running Backup");
										jQuery("#information").text("Re-running Backup");
										jQuery("#cancel_message").html("* Please do not <u>Cancel</u> or <u>Refresh</u> the Page until the Re-run process is Completed.");
									}
									else
									{
										jQuery("#progress_bar_heading").text("Restore Backup");
										jQuery("#information").text("Restoring Backup");
										jQuery("#cancel_message").html("* Please do not <u>Cancel</u> or <u>Refresh</u> the Page until the Restore process is Completed.");
									}
									var overlay_opacity = jQuery("<div class=\"opacity_overlay\"></div>");
									jQuery("body").append(overlay_opacity);
									var progress_bar = jQuery("#ux_div_portlet_progress").clone().html();
									jQuery("body").append(progress_bar);
									jQuery("#ux_div_portlet_progress").remove();
									resize_progress_bar_backup_bank();
									counter = setInterval(progress_bar_counter_backup_bank,1000,file_path);
								}
							}
							jQuery(document).ready(function()
							{
								open_popup_backup_bank();
							});
							jQuery(window).resize(resize_progress_bar_backup_bank);
							var oTable = jQuery("#ux_tbl_manage_backups").dataTable
							({
								"pagingType": "full_numbers",
								"language":
								{
									"emptyTable": "No data available in table",
									"info": "Showing _START_ to _END_ of _TOTAL_ entries",
									"infoEmpty": "No entries found",
									"infoFiltered": "(filtered1 from _MAX_ total entries)",
									"lengthMenu": "Show _MENU_ entries",
									"search": "Search:",
									"zeroRecords": "No matching records found"
								},
								"bSort": true,
								"pageLength": 10
							});
							var sidebar_load_interval = setInterval(load_sidebar_content_backup_bank ,1000);
							setTimeout(function()
							{
								clearInterval(sidebar_load_interval);
							}, 5000);
							if(typeof(rerun_backup_bank) != "function")
							{
								function rerun_backup_bank(id,backup_destination,archive_name,location)
								{
									var confirm_rerun = confirm(<?php echo json_encode($bb_confirm_rerun); ?>);
									if(confirm_rerun == true)
									{
										jQuery.post(ajaxurl,
										{
											location: base64_encode(location),
											archive_name: base64_encode(archive_name),
											backup_destination: base64_encode(backup_destination),
											param: "check_ftp_dropbox_connection_rerun",
											action: "backup_bank_action",
											_wp_nonce: "<?php echo $backup_bank_check_ftp_dropbox_connection; ?>"
										},
										function(data)
										{
											if(jQuery.trim(data) == "550")
											{
												var shortCutFunction = jQuery("#toastTypeGroup_error input:checked").val();
												var $toast = toastr[shortCutFunction](<?php echo json_encode($bb_error_file_upload); ?>);
											}
											else if(jQuery.trim(data) == "1")
											{
												var shortCutFunction = jQuery("#toastTypeGroup_error input:checked").val();
												var $toast = toastr[shortCutFunction](<?php echo json_encode($bb_ftp_connect); ?>);
											}
											else if(jQuery.trim(data) == "2")
											{
												var shortCutFunction = jQuery("#toastTypeGroup_error input:checked").val();
												var $toast = toastr[shortCutFunction](<?php echo json_encode($bb_could_not_connect); ?>);
											}
											else if(jQuery.trim(data) == "553")
											{
												var shortCutFunction = jQuery("#toastTypeGroup_error input:checked").val();
												var $toast = toastr[shortCutFunction](<?php echo json_encode($bb_ftp_not_configured_message); ?>);
											}
											else if(jQuery.trim(data) == "555")
											{
												var shortCutFunction = jQuery("#toastTypeGroup_error input:checked").val();
												var $toast = toastr[shortCutFunction](<?php echo json_encode($bb_email_not_configured_message); ?>);
											}
											else
											{
												var date = new Date();
												var timezone_difference = date.getTimezoneOffset();
												restore_progress_bar_backup_bank("rerun",data);
												progress_bar_message_backup_bank(data);
												jQuery.post(ajaxurl,
												{
													id: id,
													timezone_difference: timezone_difference,
													param: "backup_bank_rerun_backups",
													action: "backup_bank_action",
													_wp_nonce: "<?php echo $backup_bank_manage_rerun_backups; ?>"
												},
												function(data)
												{
												});
											}
										});
									}
								}
							}
							if(typeof(remove_restore_progress_bar_backup_bank) != "function")
							{
								function remove_restore_progress_bar_backup_bank()
								{
									jQuery(".opacity_overlay").remove();
									jQuery(".progress-bar-position").remove();
								}
							}
							if(typeof(download_backup_bank) != "function")
							{
								function download_backup_bank()
								{
									var download_link = jQuery("#ux_ddl_download_type").val();
									if(download_link != "")
									{
										window.open(download_link, "_blank");
									}
									else
									{
										var shortCutFunction = jQuery("#toastTypeGroup_error input:checked").val();
										var $toast = toastr[shortCutFunction](<?php echo json_encode($bb_choose_backup_to_download); ?>);
									}
								}
							}
							if(typeof(show_download_backup_bank) != "function")
							{
								function show_download_backup_bank(id)
								{
									jQuery("#ux_div_action").html(<?php echo json_encode($bb_manage_download_backup); ?>);
									jQuery("#ux_btn_backup").val(<?php echo json_encode($bb_manage_download_backup); ?>);
									jQuery("#ux_btn_backup").attr("onclick","download_backup_bank()");
									jQuery("#ux_pop_up_tooltip").attr("data-original-title",<?php echo json_encode($bb_choose_backup_to_download_tooltip); ?>);
									jQuery("#ux_span_download").html("<?php echo $bb_manage_select_backup; ?>");
									jQuery("#ux_ddl_download_type").html(jQuery("#ux_ddl_download_type_"+id).html());
									open_popup_backup_bank();
								}
							}
							if(typeof(show_restore_backup_bank) != "function")
							{
								function show_restore_backup_bank()
								{
									open_popup_backup_bank();
									premium_edition_notification_backup_bank();
								}
							}
							if(typeof(download_log_backup_bank) != "function")
							{
								function download_log_backup_bank()
								{
									var download_link = jQuery("#ux_ddl_download_type").val();
									if(download_link != "")
									{
										window.open(download_link, "_blank");
									}
									else
									{
										var shortCutFunction = jQuery("#toastTypeGroup_error input:checked").val();
										var $toast = toastr[shortCutFunction](<?php echo json_encode($bb_choose_log_file_to_download); ?>);
									}
								}
							}
							if(typeof(show_download_log_backup_bank) != "function")
							{
								function show_download_log_backup_bank(id)
								{
									jQuery("#ux_div_action").html(<?php echo json_encode($bb_manage_download_log_file); ?>);
									jQuery("#ux_btn_backup").val(<?php echo json_encode($bb_manage_download_log_file); ?>);
									jQuery("#ux_btn_backup").attr("onclick","download_log_backup_bank()");
									jQuery("#ux_pop_up_tooltip").attr("data-original-title",<?php echo json_encode($bb_choose_log_file_to_download_tooltip); ?>);
									jQuery("#ux_span_download").html(<?php echo json_encode($bb_manage_select_log_backup); ?>);
									jQuery("#ux_ddl_download_type").html(jQuery("#ux_ddl_download_log_"+id).html());
									open_popup_backup_bank();
								}
							}
							jQuery("#ux_chk_all_manage_backups").click(function()
							{
								jQuery("input[type=checkbox]", oTable.fnGetFilteredNodes()).attr("checked",this.checked);
							});
							if(typeof(check_all_manage_backups) != "function")
							{
								function check_all_manage_backups(id)
								{
									if(jQuery("input:checked", oTable.fnGetFilteredNodes()).length == jQuery("input[type=checkbox]", oTable.fnGetFilteredNodes()).length)
									{
										jQuery("#ux_chk_all_manage_backups").attr("checked","checked");
									}
									else
									{
										jQuery("#ux_chk_all_manage_backups").removeAttr("checked");
									}
								}
							}
							if(typeof(delete_backup_logs) != "function")
							{
								function delete_backup_logs(id)
								{
									var confirm_delete = confirm(<?php echo json_encode($bb_confirm_single_delete); ?>);
									if(confirm_delete == true)
									{
										overlay_loading_backup_bank("delete_backup");
										jQuery.post(ajaxurl,
										{
											id: id,
											param: "backup_bank_manage_backups_delete_module",
											action: "backup_bank_action",
											_wp_nonce: "<?php echo $backup_bank_manage_backups_delete; ?>"
										},
										function(data)
										{
											setTimeout(function()
											{
												remove_overlay_backup_bank();
												window.location.href = "admin.php?page=bb_manage_backups";
											}, 3000);
										});
									}
								}
							}
							if(typeof(purge_backup_bank) != "function")
							{
								function purge_backup_bank()
								{
									premium_edition_notification_backup_bank();
								}
							}
							if(typeof(bulk_delete_backup_logs) != "function")
							{
								function bulk_delete_backup_logs()
								{
									var view_records_bulk_action = jQuery("#ux_ddl_manage_backups").val();
									if(view_records_bulk_action == "")
									{
										var shortCutFunction = jQuery("#toastTypeGroup_error input:checked").val();
										var $toast = toastr[shortCutFunction](<?php echo json_encode($bb_choose_action); ?>);
									}
									else
									{
										var checkbox_array = [];
										jQuery("input[type=checkbox][name*=ux_chk_manage_backups_]", oTable.fnGetFilteredNodes()).each(function ()
										{
											if(jQuery(this).val() != "")
											{
												var Checked = jQuery(this).attr("checked");
												if (Checked == "checked")
												{
													var record_id = jQuery(this).val();
													checkbox_array.push(record_id);
												}
											}
										});
										if(checkbox_array.length < 1 )
										{
											var shortCutFunction = jQuery("#toastTypeGroup_error input:checked").val();
											var $toast = toastr[shortCutFunction](<?php echo json_encode($bb_choose_record_to_delete); ?>);
										}
										else
										{
											var confirm_delete = confirm(<?php echo json_encode($bb_confirm_bulk_delete); ?>);
											if(confirm_delete == true)
											{
												overlay_loading_backup_bank("bulk_delete_backup");
												jQuery.post(ajaxurl,
												{
													encrypted_records: JSON.stringify(checkbox_array),
													param: "backup_bank_manage_backups_bulk_delete_module",
													action: "backup_bank_action",
													_wp_nonce: "<?php echo $backup_bank_manage_backups_bulk_delete; ?>"
												},
												function(data)
												{
													setTimeout(function()
													{
														remove_overlay_backup_bank();
														window.location.href = "admin.php?page=bb_manage_backups";
													}, 3000);
												});
											}
										}
									}
								}
							}
							<?php
						}
					break;
					case "bb_alert_setup":
						?>
						jQuery("#ux_bb_li_general_settings").addClass("active");
						jQuery("#ux_bb_li_alert_setup_backup").addClass("active");
						<?php
						if(general_settings_backup_bank == "1")
						{
							?>
							jQuery(document).ready(function()
							{
								jQuery("#ux_ddl_backup_scheduled_successfull").val("<?php echo isset($bb_alert_setup_array["email_when_backup_scheduled_successfully"]) ? $bb_alert_setup_array["email_when_backup_scheduled_successfully"] : "enable" ; ?>");
								jQuery("#ux_ddl_backup_generated_successfull").val("<?php echo isset($bb_alert_setup_array["email_when_backup_generated_successfully"]) ? $bb_alert_setup_array["email_when_backup_generated_successfully"] : "enable"; ?>");
								jQuery("#ux_ddl_backup_failed").val("<?php echo isset($bb_alert_setup_array["email_when_backup_failed"]) ? $bb_alert_setup_array["email_when_backup_failed"] : "enable"; ?>");
								jQuery("#ux_ddl_restore_completed_successfull").val("<?php echo isset($bb_alert_setup_array["email_when_restore_completed_successfully"]) ? $bb_alert_setup_array["email_when_restore_completed_successfully"] : "enable" ; ?>");
								jQuery("#ux_ddl_restore_failed").val("<?php echo isset($bb_alert_setup_array["email_when_restore_failed"]) ? $bb_alert_setup_array["email_when_restore_failed"] : "enable" ; ?>");
								open_popup_backup_bank();
								load_sidebar_content_backup_bank();
							});
							jQuery("#ux_frm_alert_setup").validate(
							{
								submitHandler:function(form)
								{
									overlay_loading_backup_bank("alert_setup");
									jQuery.post(ajaxurl,
									{
										data: jQuery("#ux_frm_alert_setup").serialize(),
										param: "backup_bank_alert_setup_module",
										action: "backup_bank_action",
										_wp_nonce: "<?php echo $backup_bank_alert_setup;?>"
									},
									function(data)
									{
										setTimeout(function()
										{
											remove_overlay_backup_bank();
											window.location.href = "admin.php?page=bb_alert_setup";
										}, 3000);
									});
								}
							});
							<?php
						}
					break;
					case "bb_other_settings":
						?>
						jQuery("#ux_bb_li_general_settings").addClass("active");
						jQuery("#ux_bb_li_other_settings_backup").addClass("active");
						<?php
						if(general_settings_backup_bank == "1")
						{
							?>
							jQuery(document).ready(function()
							{
								jQuery("#ux_ddl_automatic_plugin_updates").val("<?php echo isset($bb_other_settings_array["automatic_plugin_updates"]) ? $bb_other_settings_array["automatic_plugin_updates"] : "enable" ?>");
								jQuery("#ux_ddl_remove_tables").val("<?php echo isset($bb_other_settings_array["remove_tables_at_uninstall"]) ? $bb_other_settings_array["remove_tables_at_uninstall"] : "enable" ?>");
								jQuery("#ux_ddl_maintenance_mode").val("<?php echo isset($bb_other_settings_maintenance_mode_data["restoring"]) ? $bb_other_settings_maintenance_mode_data["restoring"] : "disable" ?>");
								open_popup_backup_bank();
								load_sidebar_content_backup_bank();
							});
							jQuery("#ux_frm_other_settings").validate(
							{
								rules:
								{
									ux_txt_maintenance_mode_message:
									{
										required: true
									}
								},
								errorPlacement: function (error, element)
								{
									var icon = jQuery(element).parent(".input-icon").children("i");
									icon.removeClass("fa-check").addClass("fa-warning");
									icon.attr("data-original-title", error.text()).tooltip_tip({"container": "body"});
								},
								highlight: function (element)
								{
									jQuery(element).closest(".form-group").removeClass("has-success").addClass("has-error");
								},
								success: function (label, element)
								{
									var icon = jQuery(element).parent(".input-icon").children("i");
									jQuery(element).closest(".form-group").removeClass("has-error").addClass("has-success");
									icon.removeClass("fa-warning").addClass("fa-check");
								},
								submitHandler:function(form)
								{
									overlay_loading_backup_bank("other_settings");
									jQuery.post(ajaxurl,
									{
										data: base64_encode(jQuery("#ux_frm_other_settings").serialize()),
										param: "backup_bank_other_settings_module",
										action: "backup_bank_action",
										_wp_nonce: "<?php echo $backup_bank_other_settings; ?>"
									},
									function(data)
									{
										setTimeout(function()
										{
											remove_overlay_backup_bank();
											window.location.href = "admin.php?page=bb_other_settings";
										}, 3000);
									});
								}
							});
							<?php
						}
					break;
					case "bb_dropbox_settings":
						?>
						jQuery("#ux_bb_li_general_settings").addClass("active");
						jQuery("#ux_bb_li_dropbox_settings").addClass("active");
						<?php
						if(general_settings_backup_bank == "1")
						{
							?>
							jQuery(document).ready(function()
							{
								jQuery("#ux_ddl_dropbox_settings_enable_disable").val("<?php echo isset($bb_dropbox_settings_unserialize_data["backup_to_dropbox"]) ? $bb_dropbox_settings_unserialize_data["backup_to_dropbox"] : "enable"; ?>");
								open_popup_backup_bank();
								load_sidebar_content_backup_bank();
							});
							jQuery("#ux_frm_dropbox").validate
							({
								rules:
								{
									ux_txt_dropbox_api_key:
									{
										required: true
									},
									ux_txt_dropbox_secret_key:
									{
										required: true
									}
								},
								errorPlacement: function (error, element)
								{
									var icon = jQuery(element).parent(".input-icon").children("i");
									icon.removeClass("fa-check").addClass("fa-warning");
									icon.attr("data-original-title", error.text()).tooltip_tip({"container": "body"});
								},
								highlight: function (element)
								{
									jQuery(element).closest(".form-group").removeClass("has-success").addClass("has-error");
								},
								success: function (label, element)
								{
									var icon = jQuery(element).parent(".input-icon").children("i");
									jQuery(element).closest(".form-group").removeClass("has-error").addClass("has-success");
									icon.removeClass("fa-warning").addClass("fa-check");
								},
								submitHandler: function(form)
								{
									premium_edition_notification_backup_bank();
								}
							});
							<?php
						}
					break;
					case "bb_email_settings":
						?>
						jQuery("#ux_bb_li_general_settings").addClass("active");
						jQuery("#ux_bb_li_email_settings").addClass("active");
						<?php
						if(general_settings_backup_bank == "1")
						{
							?>
							if(typeof(email_backup_bank) != "function")
							{
								function email_backup_bank()
								{
									var email_enable_disable = jQuery("#ux_ddl_email_settings_enable_disable").val();
									switch(email_enable_disable)
									{
										case "enable":
											jQuery("#ux_div_email").css("display","block");
										break;
										case "disable":
											jQuery("#ux_div_email").css("display","none");
										break;
									}
								}
								load_sidebar_content_backup_bank();
							}
							jQuery(document).ready(function()
							{
								jQuery("#ux_ddl_email_settings_enable_disable").val("<?php echo isset($email_setting_data_array["backup_to_email"]) ? $email_setting_data_array["backup_to_email"] : "disable"?>");
								email_backup_bank();
								open_popup_backup_bank();
								load_sidebar_content_backup_bank();
							});
							var bb_email_setting = jQuery("#ux_frm_email_settings");
							bb_email_setting.validate
							({
								rules:
								{
									ux_txt_email_address:
									{
										required:true,
										email:true
									},
									ux_txt_email_subject:
									{
										required:true
									}
								},
								errorPlacement: function (error, element)
								{
									var icon = jQuery(element).parent(".input-icon").children("i");
									icon.removeClass("fa-check").addClass("fa-warning");
									icon.attr("data-original-title", error.text()).tooltip_tip({"container": "body"});
								},
								highlight: function (element)
								{
									jQuery(element).closest(".form-group").removeClass("has-success").addClass("has-error");
								},
								success: function (label, element)
								{
									var icon = jQuery(element).parent(".input-icon").children("i");
									jQuery(element).closest(".form-group").removeClass("has-error").addClass("has-success");
									icon.removeClass("fa-warning").addClass("fa-check");
								},
								submitHandler: function(form)
								{
									if (jQuery("#wp-ux_heading_content-wrap").hasClass("tmce-active"))
									{
										jQuery("#ux_txt_email_settings_message").val(tinyMCE.get("ux_heading_content").getContent());
									}
									else
									{
										jQuery("#ux_txt_email_settings_message").val(jQuery("#ux_heading_content").val());
									}
									overlay_loading_backup_bank("update_email_settings");
									jQuery.post(ajaxurl,
									{
										data: base64_encode(jQuery("#ux_frm_email_settings").serialize()),
										param: "backup_bank_email_settings_module",
										action: "backup_bank_action",
										_wp_nonce: "<?php echo $backup_bank_email_settings;?>"
									},
									function(data)
									{
										setTimeout(function()
										{
											remove_overlay_backup_bank();
											window.location.href = "admin.php?page=bb_email_settings";
										}, 3000);
									});
								}
							});
							var sidebar_load_interval = setInterval(load_sidebar_content_backup_bank ,1000);
							setTimeout(function()
							{
								clearInterval(sidebar_load_interval);
							}, 5000);
							<?php
						}
					break;
					case "bb_ftp_settings":
						?>
						jQuery("#ux_bb_li_general_settings").addClass("active");
						jQuery("#ux_bb_li_ftp_settings").addClass("active");
						<?php
						if(general_settings_backup_bank == "1")
						{
							?>
							if(typeof(ftp_login_type_backup_bank) != "function")
							{
								function ftp_login_type_backup_bank()
								{
									var ftp_login = jQuery("#ux_ddl_login_type").val();
									switch(ftp_login)
									{
										case "username_password":
											jQuery("#ux_div_ftp_username").css("display","block");
											jQuery("#ux_div_ftp_password").css("display","block");
										break;
										case "username_only":
											jQuery("#ux_div_ftp_username").css("display","block");
											jQuery("#ux_div_ftp_password").css("display","none");
										break;
										case "anonymous":
											jQuery("#ux_div_ftp_username").css("display","none");
											jQuery("#ux_div_ftp_password").css("display","none");
										break;
										case "no_login":
											jQuery("#ux_div_ftp_username").css("display","none");
											jQuery("#ux_div_ftp_password").css("display","none");
										break;
									}
									load_sidebar_content_backup_bank();
								}
							}
							if(typeof(ftp_backup_bank) != "function")
							{
								function ftp_backup_bank()
								{
									var ftp_enable_disable = jQuery("#ux_ddl_ftp_settings_enable_disable").val();
									switch(ftp_enable_disable)
									{
										case "enable":
											jQuery("#ux_div_ftp").css("display","block");
										break;
										case "disable":
											jQuery("#ux_div_ftp").css("display","none");
										break;
									}
									load_sidebar_content_backup_bank();
								}
							}
							jQuery(document).ready(function()
							{
								jQuery("#ux_ddl_ftp_settings_enable_disable").val("<?php echo isset($ftp_settings_data_array["backup_to_ftp"]) ? $ftp_settings_data_array["backup_to_ftp"] : "disable" ?>");
								jQuery("#ux_ddl_ftp_protocol").val("<?php echo isset($ftp_settings_data_array["protocol"]) ? $ftp_settings_data_array["protocol"] : "" ?>");
								jQuery("#ux_ddl_login_type").val("<?php echo isset($ftp_settings_data_array["login_type"]) ? $ftp_settings_data_array["login_type"] : "" ?>");
								jQuery("#ux_ddl_ftp_mode").val("<?php echo isset($ftp_settings_data_array["ftp_mode"]) ? $ftp_settings_data_array["ftp_mode"] : "false" ?>");
								ftp_backup_bank();
								ftp_login_type_backup_bank();
								open_popup_backup_bank();
								load_sidebar_content_backup_bank();
							});
							jQuery("#ux_frm_ftp_settings").validate
							({
								rules:
								{
									ux_txt_ftp_settings_host:
									{
										required: true
									},
									ux_txt_ftp_settings_username:
									{
										required: true
									},
									ux_txt_ftp_settings_password:
									{
										required: true
									},
									ux_txt_ftp_settings_remote_path:
									{
										required: true
									},
									ux_txt_ftp_settings_port:
									{
										required: true,
										digits: true
									}
								},
								errorPlacement: function (error, element)
								{
									var icon = jQuery(element).parent(".input-icon").children("i");
									icon.removeClass("fa-check").addClass("fa-warning");
									icon.attr("data-original-title", error.text()).tooltip_tip({"container": "body"});
								},
								highlight: function (element)
								{
									jQuery(element).closest(".form-group").removeClass("has-success").addClass("has-error");
								},
								success: function (label, element)
								{
									var icon = jQuery(element).parent(".input-icon").children("i");
									jQuery(element).closest(".form-group").removeClass("has-error").addClass("has-success");
									icon.removeClass("fa-warning").addClass("fa-check");
								},
								submitHandler: function (form)
								{
									jQuery.post(ajaxurl,
									{
										data: base64_encode(jQuery("#ux_frm_ftp_settings").serialize()),
										param: "backup_bank_ftp_settings_module",
										action: "backup_bank_action",
										_wp_nonce: "<?php echo $backup_bank_ftp_settings;?>"
									},
									function(data)
									{
										if(jQuery.trim(data) == "1")
										{
											var shortCutFunction = jQuery("#toastTypeGroup_error input:checked").val();
											var $toast = toastr[shortCutFunction](<?php echo json_encode($bb_ftp_conn); ?>);
										}
										else if(jQuery.trim(data) == "2")
										{
											var shortCutFunction = jQuery("#toastTypeGroup_error input:checked").val();
											var $toast = toastr[shortCutFunction](<?php echo json_encode($bb_could_not_connect); ?>);
										}
										else
										{
											overlay_loading_backup_bank("update_ftp_settings");
											setTimeout(function()
											{
												remove_overlay_backup_bank();
												window.location.href = "admin.php?page=bb_ftp_settings";
											}, 3000);
										}
									});
								}
							});
							<?php
						}
					break;
					case "bb_amazons3_settings":
						?>
						jQuery("#ux_bb_li_general_settings").addClass("active");
						jQuery("#ux_bb_li_amazons3_settings").addClass("active");
						<?php
						if(general_settings_backup_bank == "1")
						{
							?>
							jQuery(document).ready(function()
							{
								open_popup_backup_bank();
								load_sidebar_content_backup_bank();
							});
							jQuery("#ux_frm_amazons3").validate
							({
								rules:
								{
									ux_txt_amazons3_access_key_id:
									{
										required: true
									},
									ux_txt_amazons3_secret_key:
									{
										required: true
									},
									ux_txt_amazons3_bucket_name:
									{
										required: true
									}
								},
								errorPlacement: function (error, element)
								{
									var icon = jQuery(element).parent(".input-icon").children("i");
									icon.removeClass("fa-check").addClass("fa-warning");
									icon.attr("data-original-title", error.text()).tooltip_tip({"container": "body"});
								},
								highlight: function (element)
								{
									jQuery(element).closest(".form-group").removeClass("has-success").addClass("has-error");
								},
								success: function (label, element)
								{
									var icon = jQuery(element).parent(".input-icon").children("i");
									jQuery(element).closest(".form-group").removeClass("has-error").addClass("has-success");
									icon.removeClass("fa-warning").addClass("fa-check");
								},
								submitHandler: function(form)
								{
									premium_edition_notification_backup_bank();
								}
							});
							<?php
						}
					break;
					case "bb_google_drive":
						?>
						jQuery("#ux_bb_li_general_settings").addClass("active");
						jQuery("#ux_bb_li_google_drive_backup").addClass("active");
						<?php
						if(general_settings_backup_bank == "1")
						{
							?>
							jQuery(document).ready(function()
							{
								open_popup_backup_bank();
							});
							jQuery("#ux_frm_google_drive").validate
							({
								rules:
								{
									ux_txt_google_drive_client_id:
									{
										required: true
									},
									ux_txt_google_drive_secret_key:
									{
										required: true
									}
								},
								errorPlacement: function (error, element)
								{
									var icon = jQuery(element).parent(".input-icon").children("i");
									icon.removeClass("fa-check").addClass("fa-warning");
									icon.attr("data-original-title", error.text()).tooltip_tip({"container": "body"});
								},
								highlight: function (element)
								{
									jQuery(element).closest(".form-group").removeClass("has-success").addClass("has-error");
								},
								success: function (label, element)
								{
									var icon = jQuery(element).parent(".input-icon").children("i");
									jQuery(element).closest(".form-group").removeClass("has-error").addClass("has-success");
									icon.removeClass("fa-warning").addClass("fa-check");
								},
								submitHandler: function(form)
								{
									premium_edition_notification_backup_bank();
								}
							});
							var sidebar_load_interval = setInterval(load_sidebar_content_backup_bank ,1000);
							setTimeout(function()
							{
								clearInterval(sidebar_load_interval);
							}, 5000);
							<?php
						}
					break;
					case "bb_onedrive_settings":
						?>
						jQuery("#ux_bb_li_general_settings").addClass("active");
						jQuery("#ux_bb_li_onedrive_settings").addClass("active");
						<?php
						if(general_settings_backup_bank == "1")
						{
							?>
							jQuery(document).ready(function()
							{
								open_popup_backup_bank();
							});
							jQuery("#ux_frm_onedrive").validate
							({
								rules:
								{
									ux_txt_onedrive_client_id:
									{
										required: true
									},
									ux_txt_onedrive_client_secret:
									{
										required: true
									}
								},
								errorPlacement: function (error, element)
								{
									var icon = jQuery(element).parent(".input-icon").children("i");
									icon.removeClass("fa-check").addClass("fa-warning");
									icon.attr("data-original-title", error.text()).tooltip_tip({"container": "body"});
								},
								highlight: function (element)
								{
									jQuery(element).closest(".form-group").removeClass("has-success").addClass("has-error");
								},
								success: function (label, element)
								{
									var icon = jQuery(element).parent(".input-icon").children("i");
									jQuery(element).closest(".form-group").removeClass("has-error").addClass("has-success");
									icon.removeClass("fa-warning").addClass("fa-check");
								},
								submitHandler: function(form)
								{
									premium_edition_notification_backup_bank();
								}
							});
						<?php
						}
					break;
					case "bb_rackspace_settings":
					?>
					jQuery("#ux_bb_li_general_settings").addClass("active");
					jQuery("#ux_bb_li_rackspace_settings").addClass("active");
					<?php
					if(general_settings_backup_bank == "1")
					{
						?>
						jQuery(document).ready(function()
						{
							jQuery("#ux_ddl_rackspace_enable_disable").val("<?php echo isset($rackspace_settings_data_array["backup_to_rackspace"]) ? $rackspace_settings_data_array["backup_to_rackspace"] : "enable"; ?>");
							load_sidebar_content_backup_bank();
						});
						jQuery("#ux_frm_rackspace").validate
						({
							rules:
							{
								ux_txt_rackspace_username:
								{
									required: true
								},
								ux_txt_rackspace_api_key:
								{
									required: true
								},
								ux_txt_rackspace_container:
								{
									required: true
								}
							},
							errorPlacement: function (error, element)
							{
								var icon = jQuery(element).parent(".input-icon").children("i");
								icon.removeClass("fa-check").addClass("fa-warning");
								icon.attr("data-original-title", error.text()).tooltip_tip({"container": "body"});
							},
							highlight: function (element)
							{
								jQuery(element).closest(".form-group").removeClass("has-success").addClass("has-error");
							},
							success: function (label, element)
							{
								var icon = jQuery(element).parent(".input-icon").children("i");
								jQuery(element).closest(".form-group").removeClass("has-error").addClass("has-success");
								icon.removeClass("fa-warning").addClass("fa-check");
							},
							submitHandler: function(form)
							{
								premium_edition_notification_backup_bank();
							}
						});
						<?php
					}
					break;
					case "bb_ms_azure_settings":
					?>
					jQuery("#ux_bb_li_general_settings").addClass("active");
					jQuery("#ux_bb_li_ms_azure_settings").addClass("active");
					<?php
					if(general_settings_backup_bank == "1")
					{
						?>
						jQuery(document).ready(function()
						{
							jQuery("#ux_ddl_ms_azure_enable_disable").val("<?php echo isset($ms_azure_data_array["backup_to_ms_azure"]) ? $ms_azure_data_array["backup_to_ms_azure"] : "enable"; ?>");
							load_sidebar_content_backup_bank();
						});
						jQuery("#ux_frm_ms_azure").validate
						({
							rules:
							{
								ux_txt_ms_azure_account_name:
								{
									required: true
								},
								ux_txt_ms_azure_access_key:
								{
									required: true
								},
								ux_txt_ms_azure_container:
								{
									required: true,
									minlength: 3
								}
							},
							errorPlacement: function (error, element)
							{
								var icon = jQuery(element).parent(".input-icon").children("i");
								icon.removeClass("fa-check").addClass("fa-warning");
								icon.attr("data-original-title", error.text()).tooltip_tip({"container": "body"});
							},
							highlight: function (element)
							{
								jQuery(element).closest(".form-group").removeClass("has-success").addClass("has-error");
							},
							success: function (label, element)
							{
								var icon = jQuery(element).parent(".input-icon").children("i");
								jQuery(element).closest(".form-group").removeClass("has-error").addClass("has-success");
								icon.removeClass("fa-warning").addClass("fa-check");
							},
							submitHandler: function(form)
							{
								premium_edition_notification_backup_bank();
							}
						});
						<?php
					}
					break;
					case "bb_email_templates":
						?>
						jQuery("#ux_bb_li_email_template").addClass("active");
						<?php
						if(email_templates_backup_bank == "1")
						{
							?>
							if(typeof(template_change_data_backup_bank) != "function")
							{
								function template_change_data_backup_bank()
								{
									var template_type = jQuery("#ux_ddl_email_template").val();
									jQuery.post(ajaxurl,
									{
										data: template_type,
										param: "backup_bank_change_email_template_module",
										action: "backup_bank_action",
										_wp_nonce: "<?php echo $backup_bank_change_template; ?>"
									},
									function(data)
									{
										jQuery("#ux_email_template_meta_id").val(jQuery.parseJSON(data)[0]["meta_id"]);
										jQuery("#ux_txt_email_send_to").val(jQuery.parseJSON(data)[0]["email_send_to"]);
										jQuery("#ux_txt_email_template_cc").val(jQuery.parseJSON(data)[0]["email_cc"]);
										jQuery("#ux_txt_email_template_bcc").val(jQuery.parseJSON(data)[0]["email_bcc"]);
										jQuery("#ux_txt_email_subject").val(jQuery.parseJSON(data)[0]["email_subject"]);
										if (jQuery("#wp-ux_heading_content-wrap").hasClass("tmce-active"))
										{
											tinyMCE.get("ux_heading_content").setContent(jQuery.parseJSON(data)[0]["email_message"]);
										}
										else
										{
											jQuery("#ux_heading_content").val(jQuery.parseJSON(data)[0]["email_message"]);
										}
									});
								}
							}
							jQuery(document).ready(function()
							{
								template_change_data_backup_bank();
								open_popup_backup_bank();
								load_sidebar_content_backup_bank();
							});
							var bb_email_templates = jQuery("#ux_frm_email_template");
							bb_email_templates.validate
							({
								rules:
								{
									ux_txt_email_send_to:
									{
										required:true,
										email:true
									},
									ux_txt_email_subject:
									{
										required:true
									},
								},
								errorPlacement: function (error, element)
								{
									var icon = jQuery(element).parent(".input-icon").children("i");
									icon.removeClass("fa-check").addClass("fa-warning");
									icon.attr("data-original-title", error.text()).tooltip_tip({"container": "body"});
								},
								highlight: function (element)
								{
									jQuery(element).closest(".form-group").removeClass("has-success").addClass("has-error");
								},
								success: function (label, element)
								{
									var icon = jQuery(element).parent(".input-icon").children("i");
									jQuery(element).closest(".form-group").removeClass("has-error").addClass("has-success");
									icon.removeClass("fa-warning").addClass("fa-check");
								},
								submitHandler: function(form)
								{
									premium_edition_notification_backup_bank();
								}
							});
							<?php
						}
					break;
					case "bb_roles_and_capabilities" :
						?>
						jQuery("#ux_bb_li_roles_capabilities").addClass("active");
						<?php
						if(roles_and_capabilities_backup_bank == "1")
						{
							?>
							if(typeof(show_roles_capabilities_backup_bank)!= "function")
							{
								function show_roles_capabilities_backup_bank(id,div_id)
								{
									if(jQuery(id).prop("checked"))
									{
										jQuery("#"+div_id).css("display","block");
									}
									else
									{
										jQuery("#"+div_id).css("display","none");
									}
								}
							}
							if(typeof(full_control_function_backup_bank)!= "function")
							{
								function full_control_function_backup_bank(id,div_id)
								{
									var checkbox_id  = jQuery(id).prop("checked");
									jQuery("#"+div_id+ " input[type=checkbox]").each(function()
									{
										if(checkbox_id)
										{
											jQuery(this).attr("checked","checked");
											if(jQuery(id).attr("id") != jQuery(this).attr("id"))
											{
												jQuery(this).attr("disabled","disabled");
											}
										}
										else
										{
											if(jQuery(id).attr("id") != jQuery(this).attr("id"))
											{
												jQuery(this).removeAttr("disabled");
												jQuery("#ux_chk_other_capabilities_manage_options").attr("disabled","disabled");
												jQuery("#ux_chk_other_capabilities_read").attr("checked","checked").attr("disabled","disabled");
											}
										}
									});
								}
							}
							jQuery(document).ready(function()
							{
								jQuery("#ux_ddl_backup_bank_menu").val("<?php echo isset($details_roles_capabilities["show_backup_bank_top_bar_menu"]) ? $details_roles_capabilities["show_backup_bank_top_bar_menu"] : "enable";?>");
								full_control_function_backup_bank(jQuery("#ux_chk_full_control_administrator"),"ux_div_administrator_roles");
								show_roles_capabilities_backup_bank("#ux_chk_author","ux_div_author_roles");
								full_control_function_backup_bank(jQuery("#ux_chk_full_control_author"),"ux_div_author_roles");
								show_roles_capabilities_backup_bank("#ux_chk_editor","ux_div_editor_roles");
								full_control_function_backup_bank(jQuery("#ux_chk_full_control_editor"),"ux_div_editor_roles");
								show_roles_capabilities_backup_bank("#ux_chk_contributor","ux_div_contributor_roles");
								full_control_function_backup_bank(jQuery("#ux_chk_full_control_contributor"),"ux_div_contributor_roles");
								show_roles_capabilities_backup_bank("#ux_chk_subscriber","ux_div_subscriber_roles");
								full_control_function_backup_bank(jQuery("#ux_chk_full_control_subscriber"),"ux_div_subscriber_roles");
								show_roles_capabilities_backup_bank("#ux_chk_other","ux_div_other_roles");
								full_control_function_backup_bank("#ux_chk_full_control_other","ux_div_other_roles");
								full_control_function_backup_bank("#ux_chk_full_control_other_roles","ux_div_other_roles_capabilities");
								open_popup_backup_bank();
								load_sidebar_content_backup_bank();
							});
							jQuery("#ux_frm_roles_and_capabilities").validate
							({
								submitHandler:function(form)
								{
									premium_edition_notification_backup_bank();
								}
							});
							var sidebar_load_interval = setInterval(load_sidebar_content_backup_bank ,1000);
							setTimeout(function()
							{
								clearInterval(sidebar_load_interval);
							}, 5000);
							<?php
						}
					break;
					case "bb_feature_requests" :
						?>
						jQuery("#ux_bb_li_feature_requests").addClass("active");
						jQuery(document).ready(function()
						{
							open_popup_backup_bank();
						});
						var features_array = [];
						var url = "<?php echo tech_banker_url."/feedbacks.php";?>";
						var domain_url = "<?php echo site_url(); ?>";
						var bb_frm_feature_request = jQuery("#ux_frm_feature_requests");
						bb_frm_feature_request.validate
						({
							rules:
							{
								ux_txt_your_name:
								{
									required:true
								},
								ux_txt_email_address:
								{
									required:true,
									email:true
								},
								ux_txtarea_feature_request:
								{
									required:true
								}
							},
							errorPlacement: function (error, element)
							{
								var icon = jQuery(element).parent(".input-icon").children("i");
								icon.removeClass("fa-check").addClass("fa-warning");
								icon.attr("data-original-title", error.text()).tooltip_tip({"container": "body"});
							},
							highlight: function (element)
							{
								jQuery(element).closest(".form-group").removeClass("has-success").addClass("has-error");
							},
							success: function (label, element)
							{
								var icon = jQuery(element).parent(".input-icon").children("i");
								jQuery(element).closest(".form-group").removeClass("has-error").addClass("has-success");
								icon.removeClass("fa-warning").addClass("fa-check");
							},
							submitHandler:function(form)
							{
								features_array.push(jQuery("#ux_txt_your_name").val(), jQuery("#ux_txt_email_address").val(), domain_url, jQuery("#ux_txtarea_feature_request").val());
								overlay_loading_backup_bank("feature_request");
								jQuery.post(url,
								{
									data :JSON.stringify(features_array),
									param: "backup_bank_feature_requests"
								},
								function()
								{
									setTimeout(function()
									{
										remove_overlay_backup_bank();
										window.location.href = "admin.php?page=bb_feature_requests";
									}, 3000);
								});
							}
						});
						<?php
					break;
					case "bb_system_information" :
						?>
						jQuery("#ux_bb_li_system_information").addClass("active");
						<?php
						if(system_information_backup_bank == "1")
						{
							?>
							jQuery(document).ready(function()
							{
								open_popup_backup_bank();
							});
							jQuery.getSystemReport = function (strDefault, stringCount, string, location)
							{
								var o = strDefault.toString();
								if (!string)
								{
									string = "0";
								}
								while (o.length < stringCount)
								{
									if (location == "undefined")
									{
										o = string + o;
									}
									else
									{
										o = o + string;
									}
								}
								return o;
							};
							jQuery(".system-report").click(function ()
							{
								var report = "";
								jQuery(".custom-form-body").each(function ()
								{
									jQuery("h3.form-section", jQuery(this)).each(function ()
									{
										report = report + "\n### " + jQuery.trim(jQuery(this).text()) + " ###\n\n";
									});
									jQuery("tbody > tr", jQuery(this)).each(function ()
									{
										var the_name = jQuery.getSystemReport(jQuery.trim(jQuery(this).find("strong").text()), 25, " ");
										var the_value = jQuery.trim(jQuery(this).find("span").text());
										var value_array = the_value.split(", ");
										if (value_array.length > 1)
										{
											var temp_line = "";
											jQuery.each(value_array, function (key, line)
											{
												var tab = ( key == 0 ) ? 0 : 25;
												temp_line = temp_line + jQuery.getSystemReport("", tab, " ", "f") + line + "\n";
											});
											the_value = temp_line;
										}
										report = report + "" + the_name + the_value + "\n";
									});
								});
								try
								{
									jQuery("#ux_system_information").slideDown();
									jQuery("#ux_system_information textarea").val(report).focus().select();
									return false;
								}
								catch (e)
								{
								}
								return false;
							});
							jQuery("#ux_btn_system_information").click(function ()
							{
								if(jQuery("#ux_btn_system_information").text() == "Close System Information!")
								{
									jQuery("#ux_system_information").slideUp();
									jQuery("#ux_btn_system_information").html("Get System Information!");
								}
								else
								{
									jQuery("#ux_btn_system_information").html("Close System Information!");
									jQuery("#ux_btn_system_information").removeClass("system-information");
									jQuery("#ux_btn_system_information").addClass("close-information");
								}
							});
							var sidebar_load_interval = setInterval(load_sidebar_content_backup_bank ,1000);
							setTimeout(function()
							{
								clearInterval(sidebar_load_interval);
							}, 5000);
							load_sidebar_content_backup_bank();
							<?php
						}
					break;
					case "bb_premium_editions" :
						?>
						jQuery("#ux_bb_li_premium_editions").addClass("active");
						load_sidebar_content_backup_bank();
						<?php
					break;
				}
			}
			?>
		</script>
	<?php
	}
}
?>
