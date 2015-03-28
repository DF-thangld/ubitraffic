<?php 
require_once("config.php");
function get_all_bus_stops($conn)
{
	
	$sql = "select * from stops";
	$response_xml = '<?xml version="1.0" encoding="UTF-8"?>';
	$response_xml .= "\n<bus_stops>\n";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc())
		{
			$response_xml .= "\t<stop>\n";
			$response_xml .= "\t\t<stop_id>".$row["stop_id"]."</stop_id>\n";
			$response_xml .= "\t\t<stop_code>".$row["stop_code"]."</stop_code>\n";
			$response_xml .= "\t\t<stop_name>".$row["stop_name"]."</stop_name>\n";
			$response_xml .= "\t\t<stop_lat>".$row["stop_lat"]."</stop_lat>\n";
			$response_xml .= "\t\t<stop_lon>".$row["stop_lon"]."</stop_lon>\n";
			$response_xml .= "\t</stop>\n";
		}
	}
	$conn->close();
	$response_xml .= '</bus_stops>';
	echo $response_xml;
}

function get_bus_stop_info($conn, $stop_id)
{
	$sql = "select * from stops";
}

$service = $_GET['service'];

if ($service == 'all_bus_stops')
	get_all_bus_stops($conn);
else if ($service == 'bus_stop')
{
	$stop_id = $_GET['stop_id'];
	get_all_bus_stops($conn, $stop_id);
}


?>