<?php
	header('Content-Type: application/json; charset=utf-8');
	require "livedoor_func.php";
	mb_internal_encoding('UTF-8');
	
	$index = LF_getIndex();
	
	$respone['result_code'] = 1;	
	$respone['index'] = array("title_list"=> $index['title_list'],"id_list" => $index['id_list'],"total_page"=>$index['total_page'],);
	
	$last_id = '';
	$check = '';
	
	foreach($index['id_list'] as $id){
		
		$page = LF_getTitles($id);
		$respone[$id] = array("title_list"=> $page['title_list'],"id_list" => $page['id_list'],"des_list" => $page['des_list'],"total_page"=>$page['total_page'],);	
		
		/*/TODO: xoa sau khi parse het
		foreach($page['id_list'] as $id2){
			
			$check = DB_getAttributeFromTableById('livedoor_contents', 'id', $last_id, 'next_title_id');
			
			if(strlen($check) == 8 ){
				echo $id2.' co last id la : '.$last_id;
				
			}else {
				echo $last_id.' LOI '.$check;
				DB_updateNextidForLivedoorContent($last_id, $id2);
			}
			$last_id = $id2;
		}
		*/
		
	}
	
	echo json_encode($respone);
?>