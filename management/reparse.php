<?php
	//include "../livedoor/livedoor_func.php";
	include "../rakuten/rakuten_func.php";
	//include "../sdmaep/sdmaep_func.php";
	
	$hash_code = $_GET['hash_code'];
	
	if($hash_code != 'abc123') exit ('access fail');
	
	$type = $_GET['type'];
	$page = $_GET['page'];
	
	if($type == 'all' && $page == 'rakuten') {
	
		$index = RF_getIndex();
	
		$respone['result_code'] = 1;	
		$respone['index'] = array("title_list"=> $index['title_list'],"id_list" => $index['id_list'],"des_list" => $index['des_list'],"total_page"=>$index['total_page'],);
		foreach($index['id_list'] as $id){
			
			$result = RF_getDiary($id, '//*[@id="content-center"]/div[2]/div[4]/div[2]');
			$respone[$id] = array("title"=> $result['title'],"title_list"=> $result['title_list'],"id_list" => $result['id_list'],"des_list" => $result['des_list'],"total_page"=>$result['total_page'],);	
			
			foreach($result['id_list'] as $id2){
			
				RF_getDiary($id2,'//*[@id="content-center"]/div[2]/div[4]/div[3]');
			}
		}
	}
	
?>