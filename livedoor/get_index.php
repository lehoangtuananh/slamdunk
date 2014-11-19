<?php
	header('Content-Type: application/json; charset=utf-8');
	require "livedoor_func.php";
	mb_internal_encoding('UTF-8');
	
	$result = LF_getIndex();
		
	$respone = array("result_code" => 1,"title_list"=> $result['title_list'],"id_list" => $result['id_list'],"total_page"=>$result['total_page'],);
	echo json_encode($respone);
?>