<?php 
require_once("config.php");

function get_places($conn, $category_id)
{
	
	$response_xml = '<?xml version="1.0" encoding="UTF-8"?>';
	$response_xml .= "\n<places>\n";
	// bus stop info
	$sql = "select * 
			from places a
			where a.parent_cat_id = ".$category_id;
	$result = $conn->query($sql);
	if ($result->num_rows > 0) 
	{
		$row = $result->fetch_assoc();
		while($row = $result->fetch_assoc())
		{
			$response_xml .= "<place>\n";
			$response_xml .= "\t<company_id>".$row["company_id"]."</company_id>\n";
			$response_xml .= "\t<company_name>".utf8_encode($row["name"])."</company_name>\n";
			$response_xml .= "\t<business_id>".$row["business_id"]."</business_id>\n";
			$response_xml .= "\t<description_1>".utf8_encode($row["description_1"])."</description_1>\n";
			$response_xml .= "\t<description_2>".utf8_encode($row["description_2"])."</description_2>\n";
			$response_xml .= "\t<url>".$row["url"]."</url>\n";
			$response_xml .= "\t<address>".utf8_encode($row["address"])."</address>\n";
			$response_xml .= "\t<phone>".$row["phone"]."</phone>\n";
			$response_xml .= "\t<email>".utf8_encode($row["email"])."</email>\n";
			$response_xml .= "\t<office_hours_1>".utf8_encode($row["office_hours_1"])."</office_hours_1>\n";
			$response_xml .= "\t<office_hours_2>".utf8_encode($row["office_hours_2"])."</office_hours_2>\n";
			$response_xml .= "\t<image>".$row["image"]."</image>\n";
			$response_xml .= "\t<lat>".$row["lat"]."</lat>\n";
			$response_xml .= "\t<lon>".$row["lon"]."</lon>\n";
			$response_xml .= "</place>\n";
		}
		
	}
	$response_xml .= "</places>";
	header("Content-type: text/xml; charset=utf-8");
	echo $response_xml;
}

function get_uncoorded_places($conn)
{
	
	$response_xml = '<?xml version="1.0" encoding="UTF-8"?>';
	$response_xml .= "\n<places>\n";
	// bus stop info
	$sql = "select * 
			from places a
			where a.lat='' and `got_coord`!=1 limit 6";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) 
	{
		$row = $result->fetch_assoc();
		while($row = $result->fetch_assoc())
		{
			$response_xml .= "<place>\n";
			$response_xml .= "\t<company_id>".$row["company_id"]."</company_id>\n";
			$response_xml .= "\t<company_name>".utf8_encode($row["name"])."</company_name>\n";
			$response_xml .= "\t<business_id>".$row["business_id"]."</business_id>\n";
			$response_xml .= "\t<description_1>".utf8_encode($row["description_1"])."</description_1>\n";
			$response_xml .= "\t<description_2>".utf8_encode($row["description_2"])."</description_2>\n";
			$response_xml .= "\t<url>".$row["url"]."</url>\n";
			$response_xml .= "\t<address>".utf8_encode($row["address"])."</address>\n";
			$response_xml .= "\t<phone>".$row["phone"]."</phone>\n";
			$response_xml .= "\t<email>".utf8_encode($row["email"])."</email>\n";
			$response_xml .= "\t<office_hours_1>".utf8_encode($row["office_hours_1"])."</office_hours_1>\n";
			$response_xml .= "\t<office_hours_2>".utf8_encode($row["office_hours_2"])."</office_hours_2>\n";
			$response_xml .= "\t<image>".$row["image"]."</image>\n";
			$response_xml .= "\t<lat>".$row["lat"]."</lat>\n";
			$response_xml .= "\t<lon>".$row["lon"]."</lon>\n";
			$response_xml .= "</place>\n";
		}
		
	}
	$response_xml .= "</places>";
	header("Content-type: text/xml; charset=utf-8");
	echo $response_xml;
}

function update_coord($conn)
{
	$lat = $_GET['lat'];
	$lon = $_GET['lon'];
	$company_id = $_GET['company_id'];
	$response_xml = '<?xml version="1.0" encoding="UTF-8"?>';
	$response_xml .= "\n<places>\n";
	// bus stop info
	$sql = 'update places
			set lat = '. $lat .', lon = '. $lon .', got_coord=1
			where company_id='.$company_id;
	$result = $conn->query($sql);
	$response_xml .= "<done>Finish</done>";
	$response_xml .= "</places>";
	header("Content-type: text/xml; charset=utf-8");
	echo $response_xml;
}

$action = '';
if (isset($_GET['action']))
{
	$action = $_GET['action'];
}

if ($action == '')
{
	$category_id = $_GET['category_id'];
	get_places($conn, $category_id);
}
else if ($action == 'geocode')
	get_uncoorded_places($conn);
else if ($action == 'coord')
	update_coord($conn);
?>