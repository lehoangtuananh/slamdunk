<?php
	header('Content-Type: application/json; charset=utf-8');
	require "sdmaep_func.php";
	mb_internal_encoding('UTF-8');
	
	$link_id = $_GET["id"];
	$result = SF_getCategory($link_id);
		
	$respone = array("result_code" => 1,"title_list"=> $result['title_list'],"id_list" => $result['id_list'],"type_list" => $result['type_list'],"total_page"=>$result['total_page'],);
	echo json_encode($respone);
?>