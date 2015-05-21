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
	$response_xml .= '</bus_stops>';
	header("Content-type: text/xml; charset=utf-8");
	echo $response_xml;
}

function get_bus_stop_info($conn, $stop_id)
{
	$response_xml = '<?xml version="1.0" encoding="UTF-8"?>';
	$response_xml .= "\n<bus_stop_info>\n";
	// bus stop info
	$sql = "select * 
			from stops a
			where a.stop_id = '".$stop_id."'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		$response_xml .= "<stop>\n";
		$response_xml .= "\t<stop_id>".$row["stop_id"]."</stop_id>\n";
		$response_xml .= "\t<stop_code>".$row["stop_code"]."</stop_code>\n";
		$response_xml .= "\t<stop_name>".$row["stop_name"]."</stop_name>\n";
		$response_xml .= "\t<stop_lat>".$row["stop_lat"]."</stop_lat>\n";
		$response_xml .= "\t<stop_lon>".$row["stop_lon"]."</stop_lon>\n";
		$response_xml .= "</stop>\n";
	}
	
	
	// get incoming bus
	$current_time_str = date('H:i:s');
	$current_time = intval(substr($current_time_str, 0, 2))*3600 + intval(substr($current_time_str, 3, 2))*60 + intval(substr($current_time_str, 5, 2));
	$sql = "select c.route_id, c.route_short_name, b.trip_headsign, a.stop_id, min(a.arrival_time) arrival_time
			from stop_times a
			inner join trips b on a.trip_id = b.trip_id
			inner join routes c on b.route_id = c.route_id
			inner join calendar_dates d on b.service_id = d.service_id and d.date = '".date('Ymd')."'
			where a.stop_id = '".$stop_id."' 
			and (SUBSTRING(a.arrival_time,1,2)*3600 + SUBSTRING(a.arrival_time,4,2)*60 + SUBSTRING(a.arrival_time,7,2)) > ".$current_time."
			group by c.route_id, c.route_short_name, b.trip_headsign, a.stop_id";

	$result = $conn->query($sql);
	if ($result->num_rows > 0)
	{
		// output data of each row
		$response_xml .= "<incoming_buses>\n";
		while($row = $result->fetch_assoc())
		{
			$response_xml .= "\t<bus>\n";
			$response_xml .= "\t\t<route_id>".$row["route_id"]."</route_id>\n";
			$response_xml .= "\t\t<route_short_name>".$row["route_short_name"]."</route_short_name>\n";
			$response_xml .= "\t\t<trip_headsign>".$row["trip_headsign"]."</trip_headsign>\n";
			$response_xml .= "\t\t<stop_id>".$row["stop_id"]."</stop_id>\n";
			$response_xml .= "\t\t<arrival_time>".$row["arrival_time"]."</arrival_time>\n";
			$response_xml .= "\t</bus>\n";
		}
		$response_xml .= "</incoming_buses>\n";
	}
	$response_xml .= "</bus_stop_info>";
	header("Content-type: text/xml; charset=utf-8");
	echo $response_xml;
}

function get_bus_route_info($conn, $bus_route_id)
{
	header("Content-type: text/xml; charset=utf-8");
	$response_xml = '<?xml version="1.0" encoding="UTF-8"?>';
	$response_xml .= "\n<bus_route_info>\n";
	
	//route name
	$sql = "SELECT * from routes 
			where route_id = ".$bus_route_id;
	$result = $conn->query($sql);
	if ($result->num_rows > 0)
	{
		// output data of each row
		$response_xml .= "\t<bus_information>\n";
		$row = $result->fetch_assoc();
		$response_xml .= "\t\t<route_id>".$row["route_id"]."</route_id>\n";
		$response_xml .= "\t\t<route_short_name>".$row["route_short_name"]."</route_short_name>\n";
		$response_xml .= "\t\t<route_long_name>".$row["route_long_name"]."</route_long_name>\n";
		$response_xml .= "\t</bus_information>\n";
	}
	
	//route direction
	$sql = "SELECT distinct trip_headsign, direction_id, shape_id
			from trips
			where route_id = ".$bus_route_id." 
			ORDER BY `route_id` ASC";
	$result = $conn->query($sql);
	if ($result->num_rows > 0)
	{
		// output data of each row
		$response_xml .= "\t<bus_directions>\n";
		while($row = $result->fetch_assoc())
		{
			$response_xml .= "\t\t<direction>\n";
			$response_xml .= "\t\t\t<direction_id>".$row["direction_id"]."</direction_id>\n";
			$response_xml .= "\t\t\t<trip_headsign>".$row["trip_headsign"]."</trip_headsign>\n";
			$response_xml .= "\t\t\t<shape_id>".$row["shape_id"]."</shape_id>\n";
			$response_xml .= "\t\t</direction>\n";
		}
		$response_xml .= "\t</bus_directions>\n";
	}
	
	//ongoing bus
	//get running bus
	$running_trips = "";
	$sql = "select a.trip_id, b.route_id, b.direction_id, min(a.arrival_time), max(a.arrival_time)
			from stop_times a
			inner join trips b on a.trip_id = b.trip_id
			inner join calendar_dates c on b.service_id = c.service_id and c.date = '".date('Ymd')."'
			where b.route_id = ".$bus_route_id."
			group by a.trip_id, b.route_id, b.direction_id
			having min(a.arrival_time) <= '".date('H:i:s')."' and max(a.arrival_time) >= '".date('H:i:s')."'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0)
	{
		while($row = $result->fetch_assoc())
		{
			$running_trips .= "'".$row["trip_id"]."',";
		}
		$running_trips .= "''";
	}
	//get nearest bus stops
	$sql = "select c.stop_id, c.stop_name, c.stop_lat, c.stop_lon, a.arrival_time
			from (	SELECT a.trip_id, min(arrival_time) arrival_time
					FROM `stop_times` a
					where a.trip_id in (".$running_trips.")
					and a.arrival_time > '".date('H:i:s')."'
					group by a.trip_id) a
			inner join stop_times b on a.trip_id = b.trip_id and a.arrival_time = b.arrival_time
			inner join stops c on b.stop_id = c.stop_id";
	$result = $conn->query($sql);
	
	$response_xml .= "\t<running_buses>\n";
	if ($result->num_rows > 0)
	{
		while($row = $result->fetch_assoc())
		{
			$response_xml .= "\t\t<running_bus>\n";
			$response_xml .= "\t\t\t<stop_id>".$row["stop_id"]."</stop_id>\n";
			$response_xml .= "\t\t\t<stop_name>".$row["stop_name"]."</stop_name>\n";
			$response_xml .= "\t\t\t<stop_lat>".$row["stop_lat"]."</stop_lat>\n";
			$response_xml .= "\t\t\t<stop_lon>".$row["stop_lon"]."</stop_lon>\n";
			$response_xml .= "\t\t\t<arrival_time>".$row["arrival_time"]."</arrival_time>\n";
			$response_xml .= "\t\t</running_bus>\n";
		}
	}
	$response_xml .= "\t</running_buses>\n";
	
	$response_xml .= "\n</bus_route_info>";
	echo $response_xml;
}

function get_all_bus_route($conn)
{
	$sql = "select * from routes";
	$response_xml = '<?xml version="1.0" encoding="UTF-8"?>';
	$response_xml .= "\n<bus_routes>\n";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc())
		{
			$response_xml .= "\t<route>\n";
			$response_xml .= "\t\t<route_id>".$row["route_id"]."</route_id>\n";
			$response_xml .= "\t\t<route_short_name>".$row["route_short_name"]."</route_short_name>\n";
			$response_xml .= "\t\t<route_long_name>".$row["route_long_name"]."</route_long_name>\n";
			$response_xml .= "\t</route>\n";
		}
	}
	$response_xml .= '</bus_routes>';
	header("Content-type: text/xml; charset=utf-8");
	echo $response_xml;
}

function get_bus_route_detail($conn, $route_id, $direction_id)
{
	
	$response_xml = '<?xml version="1.0" encoding="UTF-8"?>';
	$response_xml .= "\n<route_detail>\n";
	
	//get route information
	$sql = "select * from routes where route_short_name = '".$route_id."'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) 
	{
		$row = $result->fetch_assoc();
		$response_xml .= "\t<route_information>\n";
		$response_xml .= "\t\t<route_id>".$row["route_id"]."</route_id>\n";
		$response_xml .= "\t\t<route_short_name>".$row["route_short_name"]."</route_short_name>\n";
		$response_xml .= "\t\t<route_long_name>".$row["route_long_name"]."</route_long_name>\n";
		$response_xml .= "\t</route_information>\n";
	}
	
	//get current bus stop for each bus of the route
	//get running bus trip_id
	$next_stops = array();
	$response_xml .= "\t<current_bus_position>\n";
	$sql = "SELECT distinct a.trip_id
			FROM `stop_times` a
			inner join trips b on a.trip_id = b.trip_id
			inner join calendar_dates c on b.service_id = c.service_id and c.date = ".date('Ymd')."
			inner join routes d on b.route_id = d.route_id
			where d.route_short_name = '".$route_id."' and b.direction_id = ".$direction_id."
			group by a.trip_id
			having min(a.arrival_time) < '".date('H:i:s')."' and max(a.arrival_time) > '".date('H:i:s')."'";
			//echo $sql;
	$result = $conn->query($sql);
	$running_buses = '';
	if ($result->num_rows > 0) 
	{
		
		while($row = $result->fetch_assoc())
		{
			$running_buses .= "'".$row["trip_id"]."',";
		}
		$running_buses .= "''";
		//get neareast bus stops
		$sql = "select x.trip_id, x.arrival_time, y.stop_id, z.stop_name, z.stop_lat, z.stop_lon
				from (	SELECT a.trip_id, min(a.arrival_time) arrival_time
						FROM `stop_times` a
						inner join trips b on a.trip_id = b.trip_id
						inner join calendar_dates c on b.service_id = c.service_id and c.date = '".date('Ymd')."'
						where a.arrival_time > '".date('H:i:s')."'
						and a.trip_id in (".$running_buses.")
						group by a.trip_id) x
				inner join stop_times y on x.trip_id = y.trip_id and x.arrival_time = y.arrival_time
				inner join stops z on y.stop_id = z.stop_id";
		$result = $conn->query($sql);
		while($row = $result->fetch_assoc())
		{
			$response_xml .= "\t\t<bus>\n";
			
			$response_xml .= "\t\t\t<trip_id>".$row["trip_id"]."</trip_id>\n";
			$response_xml .= "\t\t\t<arrival_time>".$row["arrival_time"]."</arrival_time>\n";
			$response_xml .= "\t\t\t<stop_id>".$row["stop_id"]."</stop_id>\n";
			$response_xml .= "\t\t\t<stop_name>".$row["stop_name"]."</stop_name>\n";
			$response_xml .= "\t\t\t<stop_lat>".$row["stop_lat"]."</stop_lat>\n";
			$response_xml .= "\t\t\t<stop_lon>".$row["stop_lon"]."</stop_lon>\n";
			
			$response_xml .= "\t\t</bus>\n";
			$next_stops[] = $row["stop_id"];
		}
	}
	$response_xml .= "\t</current_bus_position>\n";
	
	//get shape_id and trip_id of next bus
	$sql = "SELECT a.trip_id, a.arrival_time, b.shape_id 
			FROM `stop_times` a
			inner join trips b on a.trip_id = b.trip_id
			inner join routes c on b.route_id = c.route_id
			inner join calendar_dates d on b.service_id = d.service_id and d.date = '".date('Ymd')."'
			where c.route_short_name = '".$route_id."' and b.direction_id=".$direction_id."
			and a.stop_sequence = '1'
			order by a.arrival_time";
	
	$current_time = date('H:i:s');
	$distance = 9999999999999999;
	$current_value = intval(substr($current_time, 0, 2))*3600 + intval(substr($current_time, 3, 2))*60 + intval(substr($current_time, 5, 2));
	$trip_id = '';
	$shape_id = '';
	$result = $conn->query($sql);
	while($row = $result->fetch_assoc())
	{
		$array_value = intval(substr($row["arrival_time"], 0, 2))*3600 + intval(substr($row["arrival_time"], 3, 2))*60 + intval(substr($row["arrival_time"], 5, 2));
		if (abs($array_value - $current_value) < $distance)
		{
			$distance = abs($array_value - $current_value);
			$trip_id = $row["trip_id"];
			$shape_id = $row["shape_id"];
		}
	}
	
	//get route shape
	$sql = "select * 
			from shapes 
			where shape_id = '".$shape_id."'
			order by shape_pt_sequence asc";
	$response_xml .= "\t<route_shape>\n";
	$result = $conn->query($sql);
	while($row = $result->fetch_assoc())
	{
		$response_xml .= "\t\t<shape>\n";
		$response_xml .= "\t\t\t<shape_pt_sequence>".$row["shape_pt_sequence"]."</shape_pt_sequence>\n";
		$response_xml .= "\t\t\t<shape_pt_lat>".$row["shape_pt_lat"]."</shape_pt_lat>\n";
		$response_xml .= "\t\t\t<shape_pt_lon>".$row["shape_pt_lon"]."</shape_pt_lon>\n";
		$response_xml .= "\t\t</shape>\n";
	}
	$response_xml .= "\t</route_shape>\n";
	
	//get route bus stop
	$sql = "SELECT a.stop_id, a.stop_sequence, b.stop_lat, b.stop_lon
			FROM `stop_times` a
			inner join stops b on a.stop_id = b.stop_id
			where a.trip_id='".$trip_id."'
			order by a.stop_sequence asc";
	$response_xml .= "\t<route_stops>\n";
	$result = $conn->query($sql);
	while($row = $result->fetch_assoc())
	{
		$is_next_stop = 0;
		$response_xml .= "\t\t<stop>\n";
		$response_xml .= "\t\t\t<stop_id>".$row["stop_id"]."</stop_id>\n";
		$response_xml .= "\t\t\t<stop_sequence>".$row["stop_sequence"]."</stop_sequence>\n";
		$response_xml .= "\t\t\t<stop_lat>".$row["stop_lat"]."</stop_lat>\n";
		$response_xml .= "\t\t\t<stop_lon>".$row["stop_lon"]."</stop_lon>\n";
		foreach ($next_stops as $i => $stop) {
			if ($next_stops[$i] == $row["stop_id"])
				$is_next_stop = 1;
		}
		$response_xml .= "\t\t\t<is_next_stop>".$is_next_stop."</is_next_stop>\n";
		
		
		$response_xml .= "\t\t</stop>\n";
	}
	$response_xml .= "\t</route_stops>\n";
	
	
	
	$response_xml .= '</route_detail>';
	header("Content-type: text/xml; charset=utf-8");
	echo $response_xml;
}

function get_bus_shape($conn, $shape_id)
{
	$sql = "select * from shapes where shape_id = ".$shape_id." order by shape_pt_sequence asc";
	$response_xml = '<?xml version="1.0" encoding="UTF-8"?>';
	$response_xml .= "\n<bus_shapes>\n";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc())
		{
			$response_xml .= "\t<shape>\n";
			$response_xml .= "\t\t<shape_id>".$row["shape_id"]."</shape_id>\n";
			$response_xml .= "\t\t<shape_pt_lat>".$row["shape_pt_lat"]."</shape_pt_lat>\n";
			$response_xml .= "\t\t<shape_pt_lon>".$row["shape_pt_lon"]."</shape_pt_lon>\n";
			$response_xml .= "\t\t<shape_pt_sequence>".$row["shape_pt_sequence"]."</shape_pt_sequence>\n";
			$response_xml .= "\t</shape>\n";
		}
	}
	$response_xml .= '</bus_shapes>';
	header("Content-type: text/xml; charset=utf-8");
	echo $response_xml;
}

function get_bus_directions($conn, $route_id)
{
	$response_xml = '<?xml version="1.0" encoding="UTF-8"?>';
	$response_xml .= "\n<bus_directions>\n";
	$sql = "SELECT route_id, route_short_name, route_long_name, direction_id, trip_headsign
			FROM `route_stops` 
			WHERE route_short_name = '".$route_id."'
			group by route_id, route_short_name, route_long_name, direction_id, trip_headsign";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc())
		{
			$response_xml .= "\t<direction>\n";
			$response_xml .= "\t\t<route_id>".$row["route_id"]."</route_id>\n";
			$response_xml .= "\t\t<route_short_name>".$row["route_short_name"]."</route_short_name>\n";
			$response_xml .= "\t\t<route_long_name>".$row["route_long_name"]."</route_long_name>\n";
			$response_xml .= "\t\t<direction_id>".$row["direction_id"]."</direction_id>\n";
			$response_xml .= "\t\t<trip_headsign>".$row["trip_headsign"]."</trip_headsign>\n";
			$response_xml .= "\t</direction>\n";
		}
	}
	$response_xml .= "</bus_directions>\n";
	header("Content-type: text/xml; charset=utf-8");
	echo $response_xml;
}

function get_bus_lines($conn)
{	
	$response_xml = '<?xml version="1.0" encoding="UTF-8"?>';
	$response_xml .= "\n<bus_lines>\n";
	$sql = "SELECT route_short_name, route_long_name FROM routes ORDER BY route_short_name + 0 ASC";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		// output data of each row		
		while($row = $result->fetch_assoc())
		{
			$response_xml .= "\t<line>\n";
			$response_xml .= "\t\t<route_short_name>".$row['route_short_name']."</route_short_name>\n";	
			$response_xml .= "\t\t<route_long_name>".$row['route_long_name']."</route_long_name>\n";			
			$response_xml .= "\t</line>\n";
		}
	}
	$response_xml .= "</bus_lines>\n";
	header("Content-type: text/xml; charset=utf-8");
	echo $response_xml;	
}

$service = $_GET['service'];

if ($service == 'all_bus_stops')
	get_all_bus_stops($conn);
else if ($service == 'bus_stop')
{
	$stop_id = $_GET['stop_id'];
	get_bus_stop_info($conn, $stop_id);
}
else if ($service == 'all_bus_routes')
{
	get_all_bus_route($conn);
}
else if ($service == 'route')
{
	$route_id = $_GET['route_id'];
	$direction_id = $_GET['direction_id'];
	get_bus_route_detail($conn, $route_id,$direction_id);
}
else if ($service == 'bus_shape')
{
	$shape_id = $_GET['shape_id'];
	get_bus_shape($conn, $shape_id);
}
else if ($service == 'bus_directions')
{
	$route_id = $_GET['route_id'];
	get_bus_directions($conn, $route_id);
}
else if ($service == 'bus_lines')
{	
	get_bus_lines($conn);
}

?>