<?php
function parse_short_date( $date ){
	$year = substr($date, 0, 4);
	$month = substr($date, 4, 2);
	$day = substr($date, 6, 2);
	$new_date = "";
	if($day != "" && $month != "" && $year != ""){$new_date = "$day/$month/$year";}
	return $new_date;
}

function parse_date( $date ){
	$year = substr($date, 0, 4);
	$month = substr($date, 4, 2);
	$day = substr($date, 6, 2);
	switch($month){
		case "01":
			$monthlong = "January";
			break;
		case "02":
			$monthlong = "February";
			break;
		case "03":
			$monthlong = "March";
			break;
		case "04":
			$monthlong = "April";
			break;
		case "05":
			$monthlong = "May";
			break;
		case "06":
			$monthlong = "June";
			break;
		case "07":
			$monthlong = "July";
			break;
		case "08":
			$monthlong = "August";
			break;
		case "09":
			$monthlong = "September";
			break;
		case "10":
			$monthlong = "October";
			break;
		case "11":
			$monthlong = "November";
			break;
		case "12":
			$monthlong = "December";
			break;
	}
	$new_date = "$monthlong $day, $year";
	return $new_date;
}

function encodeDate( $date ){
	if(substr($date, 1,1) == "/"){
		$day = substr($date,0,1);
		$day = "0$day";
		if(substr($date, 3,1) == "/"){
			$month = substr($date, 2,1);
			$month = "0$month";
			$year = substr($date,4,4);
		}else{
			$month = substr($date, 2,2);
			$year = substr($date,5,4);
		}
	}else{
		$day = substr($date,0,2);
		if(substr($date, 4,1) == "/"){
			$month = substr($date, 3,1);
			$month = "0$month";
			$year = substr($date,5,4);
		}else{
			$month = substr($date, 3,2);
			$year = substr($date,6,4);
		}
	}

	$new_date = strtotime($month."/".$day."/".$year);
	return $new_date;
}

function get_timezone_list($selected_timezone){
	$timezones = array(
		array("Pacific/Midway", "(GMT -11:00) Midway Island, Samoa"),
		array("Pacific/Honolulu", "(GMT -10:00) Hawaii"),
		array("America/Anchorage", "(GMT -9:00) Alaska"),
		array("America/Los_Angeles", "(GMT -8:00) Pacific Time (US &amp; Canada)"),
		array("America/Denver", "(GMT -7:00) Mountain Time (US &amp; Canada)"),
		array("America/Chicago", "(GMT -6:00) Central Time (US &amp; Canada), Mexico City"),
		array("America/New_York", "(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima"),
		array("America/La_Paz", "(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz"),
		array("America/St_Johns", "(GMT -3:30) Newfoundland"),
		array("America/Argentina/Buenos_Aires", "(GMT -3:00) Brazil, Buenos Aires, Georgetown"),
		array("Atlantic/South_Georgia", "(GMT -2:00) Mid-Atlantic"),
		array("Atlantic/Azores", "(GMT -1:00) Azores, Cape Verde Islands"),
		array("Europe/London", "(GMT) Western Europe Time, London, Lisbon, Casablanca"),
		array("Europe/Amsterdam", "(GMT +1:00) Amsterdam, Brussels, Copenhagen, Madrid, Paris"),
		array("Africa/Johannesburg", "(GMT +2:00) Kaliningrad, South Africa"),
		array("Asia/Baghdad", "(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg"),
		array("Asia/Tehran", "(GMT +3:30) Tehran"),
		array("Asia/Tblisi", "(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi"),
		array("Asia/Kabul", "(GMT +4:30) Kabul"),
		array("Asia/Karachi", "(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent"),
		array("Asia/Calcutta", "(GMT +5:30) Bombay, Calcutta, Madras, New Delhi"),
		array("Asia/Katmandu", "(GMT +5:45) Kathmandu"),
		array("Asia/Dhaka", "(GMT +6:00) Almaty, Dhaka, Colombo"),
		array("Asia/Jakatra", "(GMT +7:00) Bangkok, Hanoi, Jakarta"),
		array("Asia/Singapore", "(GMT +8:00) Beijing, Perth, Singapore, Hong Kong"),
		array("Asia/Tokyo", "(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk"),
		array("Australia/Adelaide", "(GMT +9:30) Adelaide, Darwin"),
		array("Pacific/Guam", "(GMT +10:00) Eastern Australia, Guam, Vladivostok"),
		array("Pacific/Kosrae", "(GMT +11:00) Magadan, Solomon Islands, New Caledonia"),
		array("Pacific/Auckland", "(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka"),
		array("Pacific/Chatham", "(GMT +12:45) Chatham Islands"),
		array("Pacific/Tongatapu", "(GMT +13:00) Phoenix Islands, Tonga"),
		array("Pacific/Kiritimati", "(GMT +14:00) Line Islands")
	);
	for($x=0;$x<sizeof($timezones);$x++){
		if($timezones[$x][0] == $selected_timezone || ($selected_timezone == "" && $timezones[$x][0] == $_SESSION['default_timezone'])){
			$selected = " selected=\"selected\"";
		}else{
			$selected = "";
		}
		echo "<option value=\"" . $timezones[$x][0] . "\"" . $selected . ">" . $timezones[$x][1] . "</option>";
	}
}
?>