<!DOCTYPE html>
<html>
<head>
  <title>Ubi eco-counter test</title>
</head>

<body>

  <?php
	//get contents from oulunliikenne (bike/walk eco-counters)
	echo "Eco-Counters for biking/walking:<br>";
	$oulunliikenne = file_get_contents('http://www.oulunliikenne.fi/public_traffic_api/eco_traffic/eco_counters.php');	
	
	echo "<br><br>Decode the data for eco-counters:<br><br>";
	
	//Get all eco-counters
	//this could be done probably by javascript/jquery because the 3 inner foreach-loops is very heavy
	$decoded = json_decode($oulunliikenne, true);
	foreach($decoded as $item)
	{
		foreach($item as $subitem) {
			//get eco-counters' id
			$counterid = $subitem['id'];
			echo "id: ".$counterid.", ";
			
			//get name of the eco-counter
			echo "name: ".$subitem['name'].", ";
			
			//Geom returns coordinates in latitude and longitude
			echo "geom: ".$subitem['geom'].", ";
			
			//return the direction (keskustaan = to the oulu center / pois keskustaan = away from the oulu center)
			echo "direction_name: ".$subitem['direction_name'].", ";
			
			//type returns the type (biking/walking)
			echo "type: ".$subitem['type'];
			echo "<br>";	
			
			//get weekly data for each eco-counter
			$getcount = file_get_contents('http://www.oulunliikenne.fi/public_traffic_api/eco_traffic/eco_counter_daydata.php?measurementPointId='.$counterid.'&daysFromHistory=7');
			$ddecoded = json_decode($getcount, true);
			
			//result title is the time of the query == current time
			echo "Result title: ".$ddecoded['resultTitle'].", ";
			
			//return this year's max data
			echo "Year's max date: ".$ddecoded['yearMaxDate'].", ";
			echo "Year's max weekday: ".$ddecoded['yearMaxWeekday'].", ";
			echo "Year's max value: ".$ddecoded['yearMaxValue']."<br><br>";
			
			
			echo "Week's amount of data:<br>";
			
			//print the data for 1 week for each eco-counter
			foreach($ddecoded['ecoCounterDayResults'] as $item) {
				echo "Date: ".$item['date'].", ";
				echo "Weekday: ".$item['weekday'].", ";
				echo "Value: ".$item['value']."<br>";
			}
			echo "<br><br>";
		}
	}
		
  ?>

</body>

</html>