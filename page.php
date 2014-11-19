<?php
	//header('Content-Type: application/json; charset=utf-8');
	require "data_respone_func.php";
	mb_internal_encoding('UTF-8');
	
	//error_reporting(0);
	
	$link_id = $_GET["id"];
	
	$result = DRF_getContent($link_id);
		
	$respone = array("result_code" => 1,"text"=> $result['text'],"next_title_id" => $result['next_title_id'],"title"=>$result['title'],);
	
	echo '<a href="http://222.255.166.38:8080/api/page.php?id='.$result['next_title_id'].'">NEXT</a></br>';
	echo json_encode($respone);
		
?>