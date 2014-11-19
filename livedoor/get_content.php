<?php
	header('Content-Type: application/json; charset=utf-8');
	require "livedoor_func.php";
	mb_internal_encoding('UTF-8');
	
	$link_id = $_GET["id"];

    LF_writeToFile();

//	$result = LF_getContent($link_id);
//
//	//$respone = array("result_code" => 1,"text"=> $result['text'],"next_title_id" => $result['next_title_id'],"title"=>$result['title'],);
//	echo json_encode($result);
		
?>