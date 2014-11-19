<?php
	header('Content-Type: application/json; charset=utf-8');
	require "sdmaep_func.php";
	mb_internal_encoding('UTF-8');
	
	$index = SF_getCategory('21464744');
	
	$respone['result_code'] = 1;	
	$respone['index'] = array("title_list"=> $index['title_list'],"id_list" => $index['id_list'],"type_list" => $index['type_list'],"total_page"=>$index['total_page'],);
	foreach($index['id_list'] as $id){
		
		$result = SF_getArticle($id);
		$respone[$id] = array("title"=> $result['title'],"text"=> $result['text'],"title_list"=> $result['title_list'],"id_list" => $result['id_list'],"type_list" => $result['type_list'],"des_list" => $result['des_list'],"total_page"=>$result['total_page'],);	
		
		/*
		foreach($result['id_list'] as $id2){
			SF_getArticle($id2);
		}
		*/
	}
	
	echo json_encode($respone);
?>