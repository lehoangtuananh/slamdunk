<?php
	header('Content-Type: application/json; charset=utf-8');
	require "rakuten_func.php";
	mb_internal_encoding('UTF-8');
	
	$link_id = $_GET["id"];
	$result = RF_getDiary($link_id,'//*[@id="content-center"]/div[2]/div[4]/div[3]');
		
	$respone = array("result_code" => 1,"title"=> $result['title'],"text"=> $result['text'],"title_list"=> $result['title_list'],"id_list" => $result['id_list'],"des_list" => $result['des_list'],"total_page"=>$result['total_page'],);
	echo json_encode($respone);
?>