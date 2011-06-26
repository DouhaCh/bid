<script type="text/javascript">

$(document).ready(function () {
});

</script>


<div class="span-30 prepend-top">

<form method="post" action="setup.php">
<fieldset class="span-30">
	<legend>General Settings</legend>
	
	<?php
	if (isset($err)) {
		?>
		<div class="error"><?= $err ?></div>
		<?php
	}
	?>
	<div class="queen"><p>Your website's main settings go here. You can change all settings on this page later, however it's important to make sure the <strong>Site URL</strong> and <strong>Site domain name</strong> fields are correct. </p></div>
	<label for="config_site_name">Your Site's Name</label><br />
	<input type="text" id="config_site_name" name="config[site_name]" value="<?= $config['site_name'] ?>"><span class="HelpToolTip"> ? <span class="HelpToolTip_Title" style="display:none;">Site Title</span><span class="HelpToolTip_Contents" style="display:none;">The title of your website goes here, but you can easily change this later.</span></span><br />

	<label for="config_site_url">Site URL</label><br />
	http:// <input type="text" id="config_site_url" name="config[site_url]" value="<?= $config['site_url'] ?>"><br />
	
	<label for="config_site_url">Site domain name (no www. or http://)</label><br />
	http:// <input type="text" id="config_site_domain" name="config[site_domain]" value="<?= $config['site_domain'] ?>"><br />

	<label for="config_site_encoding">Text encoding (choose UTF-8 if unsure)</label><br />
	<select name="config[site_encoding]" id="config_site_encoding" style="width:460px">
	<?php 
	
		$encodings=getEncodings();
		foreach ($encodings as $enc=>$label) {
			echo "<option value=\"$enc\"".($zone==$config['time_zone'] ? ' selected' : '').">$label</option>\n";
		}
	?>
	</select><br />
	
	<label for="config_site_currency">Currency</label><br />
	<select name="config[site_currency]" id="config_site_currency" style="width:460px">
	<?php 
	
		$encodings=getCurrencies();
		foreach ($encodings as $curr=>$label) {
			echo "<option value=\"$curr\"".($zone==$config['site_currency'] ? ' selected' : '').">$label</option>\n";
		}
	?>
	</select><span class="HelpToolTip"> ? <span class="HelpToolTip_Title" style="display:none;">Currency type</span><span class="HelpToolTip_Contents" style="display:none;">Choose which currency you want to use. E.g., 'USD' = United States Dollars / $. If unsure, search on Google for 'php currency'.</span></span><br />
	
	
	<label for="config_time_zone">Time Zone</label><br />
	<select name="config[time_zone]" id="config_time_zone" style="width:460px">
	<?php 
	
		$zones=getTimezones();
		foreach ($zones as $zone=>$label) {
			echo "<option value=\"$zone\"".($zone==$config['time_zone'] ? ' selected' : '').">$label</option>\n";
		}
	?>
	</select><span class="HelpToolTip"> ? <span class="HelpToolTip_Title" style="display:none;">Choose your time zone</span><span class="HelpToolTip_Contents" style="display:none;">Choose the desired time zone and the software will remember this. Note, this can easily be changed later.</span></span><br />
	
	<label for="config_license_number">License Key (copy &amp; paste from <a href="https://members.phppennyauction.com/license/customers/index.php?task=my_packages&tab=licenses" target="_blank">here</a>)</label><br />
	<input type="text" id="config_license_number" name="config[license_number]" value="<?= $config['license_number'] ?>"><span class="HelpToolTip"> ? <span class="HelpToolTip_Title" style="display:none;">Enter your 21-digit License Key</span><span class="HelpToolTip_Contents" style="display:none;">Enter your phpPennyAuction License Key here, which is in the License Center. Starts with 'phpPa-', e.g: <em>phpPA-38XsnCjd3rLYuw89</em>. If unsure, please use the Support Center.</span></span><br />
	
</fieldset>

<fieldset>
	<legend>Administrator Information</legend>
	<label for="config_site_name">Admin Email Address:</label><br />
	<input type="text" id="config_admin_email" name="config[admin_email]" value="<?= $config['admin_email'] ?>"><span class="HelpToolTip"> ? <span class="HelpToolTip_Title" style="display:none;">Admin Email Address</span><span class="HelpToolTip_Contents" style="display:none;">Pick a default admin email address for the website, e.g., <em>admin@yourwebsite.com</em>. This can be changed later. </span></span><br />
	
	<label for="config_site_name">Choose an Admin Username:</label><br />
	<input type="text" id="config_admin_login" name="config[admin_login]" value="<?= $config['admin_login'] ?>"><span class="HelpToolTip"> ? <span class="HelpToolTip_Title" style="display:none;">Choose an Admin Username</span><span class="HelpToolTip_Contents" style="display:none;">Choose an admin username here. This will by default, be your first administrator account on your website.</span></span><br />
	
	<label for="config_site_name">Choose an Admin Password:</label><br />
	<input type="password" id="config_admin_password" name="config[admin_password]" value="<?= $config['admin_password'] ?>"><br />
	
	<label for="config_site_name">Confirm Admin Password:</label><br />
	<input type="password" id="config_admin_password2" name="config[admin_password2]" value="<?= $config['admin_password2'] ?>"><br />
	



</fieldset>
</div>

<div class="notice"><strong>Please make sure that the 'Site URL' and 'Site domain name' fields above are correct</strong> before submitting this form!</div><br />
<strong>
After clicking 'Continue' below, please WAIT for the software to be installed. You may see a series of loading screens whilst installation takes place... but if you don't - please wait it out!</strong><br />

<?php
if (!$stop_error) {
	?>
<div class="span-5 prepend-top last">

<input type="hidden" value="install1" name="step">
<input type="submit" value="Continue &raquo; (click ONCE)" class="fatbutton">
 </form>
	<?php
}
?>
</div>
















<?php
function getEncodings()	{
	return array(
		"utf-8"				=>"utf-8 (recommended)",
		"ISO-8859-1"			=>"ISO-8859-1",
		"ISO-8859-2"			=>"ISO-8859-2",
		"ISO-8859-3"			=>"ISO-8859-3",
		"ISO-8859-4"			=>"ISO-8859-4",
		"ISO-8859-5"			=>"ISO-8859-5",
		"ISO-8859-6"			=>"ISO-8859-6",
		"ISO-8859-7"			=>"ISO-8859-7",
		"ISO-8859-8"			=>"ISO-8859-8",
		"ISO-8859-9"			=>"ISO-8859-9",
		"ISO-8859-10"			=>"ISO-8859-10",
		"ISO-8859-11"			=>"ISO-8859-11",
		"ISO-8859-12"			=>"ISO-8859-12",
		"ISO-8859-13"			=>"ISO-8859-13",
		"ISO-8859-14"			=>"ISO-8859-14",
		"ISO-8859-15"			=>"ISO-8859-15",
		"ISO-8859-16"			=>"ISO-8859-16",
		"windows-1250"			=>"windows-1250",
		"windows-1251"			=>"windows-1251",
		"windows-1252"			=>"windows-1252",
		"windows-1253"			=>"windows-1253",
		"windows-1254"			=>"windows-1254",
		"windows-1255"			=>"windows-1255",
		"windows-1256"			=>"windows-1256",
		"windows-1257"			=>"windows-1257",
		"windows-1258"			=>"windows-1258",
		"GB 2312"			=>"GB 2312",
		"GB 18030"			=>"GB 18030",
		"GBK"				=>"GBK",
		"KS X 1001"			=>"KS X 1001",
		"EUC-KR"			=>"EUC-KR",
		"ISO-2022-KR"			=>"ISO-2022-KR",

		);
}
function getCurrencies()	{
	return array(
		"USD"				=>"USD",
		"GBP"				=>"GBP",
		"EUR"				=>"EUR",
		"CAD"				=>"CAD",
		"AUD"				=>"AUD",
		"JPY"				=>"JPY",
		"BRL"				=>"BRL",
		"ZAR"				=>"ZAR",
		"CHF"				=>"CHF",
		"INR"				=>"INR",
		"MXN"				=>"MXN",
		"SEK"				=>"SEK",
		);
}
function getTimezones()	{
	return array(
		"Pacific/Midway"                 => "(GMT-11:00) Midway Island, Samoa",
		"America/Adak"                   => "(GMT-10:00) Hawaii-Aleutian",
		"Etc/GMT+10"                     => "(GMT-10:00) Hawaii",
		"Pacific/Marquesas"              => "(GMT-09:30) Marquesas Islands",
		"Pacific/Gambier"                => "(GMT-09:00) Gambier Islands",
		"America/Anchorage"              => "(GMT-09:00) Alaska",
		"America/Ensenada"               => "(GMT-08:00) Tijuana, Baja California",
		"Etc/GMT+8"                      => "(GMT-08:00) Pitcairn Islands",
		"America/Los_Angeles"            => "(GMT-08:00) Pacific Time (US &amp; Canada)",
		"America/Denver"                 => "(GMT-07:00) Mountain Time (US &amp; Canada)",
		"America/Chihuahua"              => "(GMT-07:00) Chihuahua, La Paz, Mazatlan",
		"America/Dawson_Creek"           => "(GMT-07:00) Arizona",
		"America/Belize"                 => "(GMT-06:00) Saskatchewan, Central America",
		"America/Cancun"                 => "(GMT-06:00) Guadalajara, Mexico City, Monterrey",
		"Chile/EasterIsland"             => "(GMT-06:00) Easter Island",
		"America/Chicago"                => "(GMT-06:00) Central Time (US &amp; Canada)",
		"America/New_York"               => "(GMT-05:00) Eastern Time (US &amp; Canada)",
		"America/Havana"                 => "(GMT-05:00) Cuba",
		"America/Bogota"                 => "(GMT-05:00) Bogota, Lima, Quito, Rio Branco",
		"America/Caracas"                => "(GMT-04:30) Caracas",
		"America/Santiago"               => "(GMT-04:00) Santiago",
		"America/La_Paz"                 => "(GMT-04:00) La Paz",
		"Atlantic/Stanley"               => "(GMT-04:00) Faukland Islands",
		"America/Campo_Grande"           => "(GMT-04:00) Brazil",
		"America/Goose_Bay"              => "(GMT-04:00) Atlantic Time (Goose Bay)",
		"America/Glace_Bay"              => "(GMT-04:00) Atlantic Time (Canada)",
		"America/St_Johns"               => "(GMT-03:30) Newfoundland",
		"America/Araguaina"              => "(GMT-03:00) UTC-3",
		"America/Montevideo"             => "(GMT-03:00) Montevideo",
		"America/Miquelon"               => "(GMT-03:00) Miquelon, St. Pierre",
		"America/Godthab"                => "(GMT-03:00) Greenland",
		"America/Argentina/Buenos_Aires" => "(GMT-03:00) Buenos Aires",
		"America/Sao_Paulo"              => "(GMT-03:00) Brasilia",
		"America/Noronha"                => "(GMT-02:00) Mid-Atlantic",
		"Atlantic/Cape_Verde"            => "(GMT-01:00) Cape Verde Is",
		"Atlantic/Azores"                => "(GMT-01:00) Azores",
		"Europe/Belfast"                 => "(GMT) Greenwich Mean Time : Belfast",
		"Europe/Dublin"                  => "(GMT) Greenwich Mean Time : Dublin",
		"Europe/Lisbon"                  => "(GMT) Greenwich Mean Time : Lisbon",
		"Europe/London"                  => "(GMT) Greenwich Mean Time : London",
		"Africa/Abidjan"                 => "(GMT) Monrovia, Reykjavik",
		"Europe/Amsterdam"               => "(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna",
		"Europe/Belgrade"                => "(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague",
		"Europe/Brussels"                => "(GMT+01:00) Brussels, Copenhagen, Madrid, Paris",
		"Africa/Algiers"                 => "(GMT+01:00) West Central Africa",
		"Africa/Windhoek"                => "(GMT+01:00) Windhoek",
		"Asia/Beirut"                    => "(GMT+02:00) Beirut",
		"Africa/Cairo"                   => "(GMT+02:00) Cairo",
		"Asia/Gaza"                      => "(GMT+02:00) Gaza",
		"Africa/Blantyre"                => "(GMT+02:00) Harare, Pretoria",
		"Asia/Jerusalem"                 => "(GMT+02:00) Jerusalem",
		"Europe/Minsk"                   => "(GMT+02:00) Minsk",
		"Asia/Damascus"                  => "(GMT+02:00) Syria",
		"Europe/Moscow"                  => "(GMT+03:00) Moscow, St. Petersburg, Volgograd",
		"Africa/Addis_Ababa"             => "(GMT+03:00) Nairobi",
		"Asia/Tehran"                    => "(GMT+03:30) Tehran",
		"Asia/Dubai"                     => "(GMT+04:00) Abu Dhabi, Muscat",
		"Asia/Yerevan"                   => "(GMT+04:00) Yerevan",
		"Asia/Kabul"                     => "(GMT+04:30) Kabul",
		"Asia/Yekaterinburg"             => "(GMT+05:00) Ekaterinburg",
		"Asia/Tashkent"                  => "(GMT+05:00) Tashkent",
		"Asia/Kolkata"                   => "(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi",
		"Asia/Katmandu"                  => "(GMT+05:45) Kathmandu",
		"Asia/Dhaka"                     => "(GMT+06:00) Astana, Dhaka",
		"Asia/Novosibirsk"               => "(GMT+06:00) Novosibirsk",
		"Asia/Rangoon"                   => "(GMT+06:30) Yangon (Rangoon)",
		"Asia/Bangkok"                   => "(GMT+07:00) Bangkok, Hanoi, Jakarta",
		"Asia/Krasnoyarsk"               => "(GMT+07:00) Krasnoyarsk",
		"Asia/Hong_Kong"                 => "(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi",
		"Asia/Irkutsk"                   => "(GMT+08:00) Irkutsk, Ulaan Bataar",
		"Australia/Perth"                => "(GMT+08:00) Perth",
		"Australia/Eucla"                => "(GMT+08:45) Eucla",
		"Asia/Tokyo"                     => "(GMT+09:00) Osaka, Sapporo, Tokyo",
		"Asia/Seoul"                     => "(GMT+09:00) Seoul",
		"Asia/Yakutsk"                   => "(GMT+09:00) Yakutsk",
		"Australia/Adelaide"             => "(GMT+09:30) Adelaide",
		"Australia/Darwin"               => "(GMT+09:30) Darwin",
		"Australia/Brisbane"             => "(GMT+10:00) Brisbane",
		"Australia/Hobart"               => "(GMT+10:00) Hobart",
		"Asia/Vladivostok"               => "(GMT+10:00) Vladivostok",
		"Australia/Lord_Howe"            => "(GMT+10:30) Lord Howe Island",
		"Etc/GMT-11"                     => "(GMT+11:00) Solomon Is, New Caledonia",
		"Asia/Magadan"                   => "(GMT+11:00) Magadan",
		"Pacific/Norfolk"                => "(GMT+11:30) Norfolk Island",
		"Asia/Anadyr"                    => "(GMT+12:00) Anadyr, Kamchatka",
		"Pacific/Auckland"               => "(GMT+12:00) Auckland, Wellington",
		"Etc/GMT-12"                     => "(GMT+12:00) Fiji, Kamchatka, Marshall Is",
		"Pacific/Chatham"                => "(GMT+12:45) Chatham Islands",
		"Pacific/Tongatapu"              => "(GMT+13:00) Nuku'alofa",
		"Pacific/Kiritimati"             => "(GMT+14:00) Kiritimati"
	);
}
?>
