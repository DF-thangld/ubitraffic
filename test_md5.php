<?php
	/*$filename = "http://transitdata.fi/oulu/google_transit.zip";
	$md5file = md5_file($filename);
	echo $md5file;
	echo '<br/>';
	$filename = "http://www.oulunliikekeskus.fi/docs/2011/templates/stat/companies_xml.php";
	$md5file = md5_file($filename);
	echo $md5file;*/
	/*echo getcwd();*/
	$array = array(	'05:15:00',
					'05:45:00',
					'06:15:00',
					'06:45:00',
					'07:05:00',
					'07:25:00',
					'07:45:00',
					'08:05:00',
					'08:25:00',
					'08:45:00',
					'09:05:00',
					'09:25:00',
					'09:45:00',
					'10:05:00',
					'10:25:00',
					'10:45:00',
					'11:05:00',
					'11:25:00',
					'11:45:00',
					'12:05:00',
					'12:25:00',
					'12:45:00',
					'13:05:00',
					'13:25:00',
					'13:45:00',
					'14:05:00',
					'14:25:00',
					'14:45:00',
					'15:05:00',
					'15:25:00',
					'15:45:00',
					'16:05:00',
					'16:25:00',
					'16:45:00',
					'17:05:00',
					'17:25:00',
					'17:45:00',
					'18:15:00',
					'18:45:00',
					'19:15:00',
					'19:45:00',
					'20:15:00',
					'20:45:00',
					'21:45:00',
					'22:45:00');
	//20:24:53
	$current_time = '20:24:53';
	$distance = 9999999999999999;
	$current_value = intval(substr($current_time, 0, 2))*3600 + intval(substr($current_time, 3, 2))*60 + intval(substr($current_time, 5, 2));
	$nearest_time = '';
	foreach ($array as &$bus_time)
	{
		$array_value = intval(substr($bus_time, 0, 2))*3600 + intval(substr($bus_time, 3, 2))*60 + intval(substr($bus_time, 5, 2));
		if (abs($array_value - $current_value) < $distance)
		{
			$distance = abs($array_value - $current_value);
			$nearest_time = $bus_time;
		}
	}
	echo $nearest_time;
?>


