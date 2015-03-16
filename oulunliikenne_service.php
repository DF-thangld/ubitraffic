<?php
	$service = $_GET['service'];
	$url = '';
	if ($service=='weather')
		$url = "http://www.oulunliikenne.fi/rss/weather_station/weather.xml";
	else if ($service=='camera')
		$url = "http://www.oulunliikenne.fi/rss/weather_camera/camera.xml";
	else if ($service=='lam')
		$url = "http://www.oulunliikenne.fi/rss/lam/lam.xml";
	else if ($service=='parking')
		$url = "http://www.oulunliikenne.fi/rss/parking/parking.xml";
	else if ($service=='weather_places')
		$url = "http://www.infotripla.fi/oulunliikenne/rajapinnat/weather.csv";
	else if ($service=='camera_places')
		$url = "http://www.infotripla.fi/oulunliikenne/rajapinnat/camera.csv";
	else if ($service=='lam_places')
		$url = "http://www.infotripla.fi/oulunliikenne/rajapinnat/lam.csv";
	$content = file_get_contents($url);
	echo $content;
?>