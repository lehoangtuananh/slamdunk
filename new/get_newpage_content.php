<?php
	header('Content-Type: application/json; charset=utf-8');
	require "news_and_parse_func.php";
	mb_internal_encoding('UTF-8');
	
	$link_id = $_GET["id"];
	
	$result = NAPF_getNewContent($link_id);
		
	$respone = array("result_code" => 1,"text"=> $result['text'],"next_title_id" => $result['next_title_id'],"title"=>$result['title'],);
	echo json_encode($respone);
		
?>