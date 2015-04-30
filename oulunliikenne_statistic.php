<?php
	include("config.php");
	function get_faces_count($instance_id='')
	{
		$url = "http://stats.ubioulu.fi/hotspots/faces_realtime.php";
		if ($instance_id!='')
			$url .= '?instance_id='.$instance_id;
		
		$content = file_get_contents($url);
		
		$content = json_decode($content, true);
		
		$faces_count = $content['result'];
		if ($faces_count == '')
			$faces_count = 0;
		
		return $faces_count;
	}
	
	function log_stat_data($conn, $instance_id, $action, $data_1='', $data_2='', $data_3='')
	{
		$sql = "insert into statistic_data (time, action_code, data_1, data_2, data_3, faces_count, instance_id) 
				values (".time().", '".$action."', '".$data_1."', '".$data_2."', '".$data_3."', ".get_faces_count($instance_id).", '".$instance_id."')";
		
		/*$stmt->bindParam(':time', time());
		$stmt->bindParam(':action_code', $action);
		$stmt->bindParam(':data_1', $data_1);
		$stmt->bindParam(':data_2', $data_2);
		$stmt->bindParam(':data_3', $data_3);
		$stmt->bindParam(':faces_count', $get_faces_count($instance_id));
		$stmt->bindParam(':instance_id', $instance_id);*/
		$result = $conn->query($sql);
		/*echo $sql;*/
	}
	
	$instance_id = $_GET['instance_id'];
	$action = $_GET['action'];
	
	$instance_id = '';
	$action = '';
	$data_1 = '';
	$data_2 = '';
	$data_3 = '';
	if (isset($_GET['data_1']))
		$data_1 = $_GET['data_1'];
	if (isset($_GET['data_2']))
		$data_2 = $_GET['data_2'];
	if (isset($_GET['data_3']))
		$data_3 = $_GET['data_3'];
	if (isset($_GET['instance_id']))
		$instance_id = $_GET['instance_id'];
	if (isset($_GET['action']))
		$action = $_GET['action'];
	if ($instance_id != '' && $action != '')
		log_stat_data($conn, $instance_id, $action, $data_1, $data_2, $data_3);
	mysqli_close($conn);
?>