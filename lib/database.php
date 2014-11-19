<?php
	require "../lib/config.php";
	
	function DB_getAttributeFromTableById($table_name, $col_name, $col_value, $att){
		
		$con = connectDB();
		
		if(!$con){
			return disconnectDBAndReturn($con,"");			
		}
		
		$result = mysqli_query($con,"SELECT * FROM ".$table_name." WHERE ".$col_name."='" . $col_value. "';");
	
		if(!$result) {
			return disconnectDBAndReturn($con,"");
		}

		while($row = mysqli_fetch_array($result))
		{
			$rs = $row[$att];
			return disconnectDBAndReturn($con,$rs);	
		} 
			
	}
	
	function DB_getLivedoorContentById($id){
	
		$con = connectDB();
		
		if(!$con){
			return disconnectDBAndReturn($con,"");			
		}
		
		$result = mysqli_query($con,"SELECT * FROM livedoor_contents WHERE id='" . $id. "';");
	
		if(!$result) {
			return disconnectDBAndReturn($con,"");
		}

		while($row = mysqli_fetch_array($result))
		{
			$rs['text'] = $row['text'];
			$rs['next_title_id'] = $row['next_title_id'];
			$rs['title'] = $row['title'];
			
			return disconnectDBAndReturn($con,$rs);
		} 
		
		
	}
	
	function DB_saveLivedoorContent($link_id, $next_title_id, $title, $text){
	
		$con = connectDB();
		
		mysqli_query($con,"INSERT INTO livedoor_contents (id, next_title_id, title, text) VALUES ('". $link_id ."', '".$next_title_id."', '".$title."', '".$text."')");
		
		disconnectDB($con);
	}
	
	function DB_updateNextidForLivedoorContent($id, $next_id){
	
		$con = connectDB();
		
		mysqli_query($con,"UPDATE livedoor_contents SET next_title_id='".$next_id."' WHERE id='".$id."'");
				
		disconnectDB($con);
	}
	
	function DB_getLivedoorPagesByParentid($parent_id){
		
		$con = connectDB();
		
		if(!$con){
            echo "cannnot connect DB";
			return disconnectDBAndReturn($con,"");			
		}
		
		$result = mysqli_query($con,"SELECT * FROM livedoor_pages WHERE parent_id='" . $parent_id. "';");
	
		if(!$result) {
			return disconnectDBAndReturn($con,"");
		}
		
		$count = 0;
		$titles = array();
		$ids = array();
		$descriptions = array();
		
		while($row = mysqli_fetch_array($result))
		{
			$titles[$count] = $row['title'];
			$ids[$count] =  $row['id'];
			$descriptions[$count] =  $row['description'];
			$count++;
		} 
		
		if($count == 0) return disconnectDBAndReturn($con, "");
		
		$rs['titles'] = $titles;
		$rs['ids'] =  $ids;
		$rs['description'] = $descriptions;
				
		return disconnectDBAndReturn($con,$rs);		
	}
	
	function DB_saveLivedoorPagesByParentid($parent_id, $ids, $titles, $descriptions){
	
		$con = connectDB();
		
		$count = count($ids);
		for($i = 0; $i < $count; $i++){		
			mysqli_query($con,"INSERT INTO livedoor_pages (id, parent_id, title, description) VALUES ('". $ids[$i] ."', '".$parent_id."', '".$titles[$i]."', '".$descriptions[$i]."')");
		}
		
		disconnectDB($con);
	}
	
	function DB_updateDescriptionForLivedoorPage($id, $description){
	
		$con = connectDB();
		
		mysqli_query($con,"UPDATE livedoor_pages SET description='".$description."' WHERE id='".$id."'");
				
		disconnectDB($con);
	}
		
	function DB_getNewPages(){
	
		$con = connectDB();
		
		if(!$con){
			return disconnectDBAndReturn($con,"");			
		}
		
		$result = mysqli_query($con,"SELECT * FROM newpages;");
	
		if(!$result) {
			return disconnectDBAndReturn($con,"");
		}
		$count = 0;
		$titles = array();
		$ids = array();
		$descriptions = array();
		$times = array();
		
		while($row = mysqli_fetch_array($result))
		{
			$titles[$count] = $row['title'];
			$ids[$count] = $row['id'];
			$descriptions[$count] = $row['description'];
			$times[$count] = $row['time'];
				
			$count++;			
		} 
		
		if($count == 0) return disconnectDBAndReturn($con, "");
		
		$rs['titles'] = $titles;
		$rs['ids'] = $ids;
		$rs['description'] = $descriptions;
		$rs['times'] = $times;
		
		return disconnectDBAndReturn($con,$rs);
		
	}
	
	function DB_saveNewPages($ids, $titles, $descriptions, $times, $texts, $next_ids, $page){
	
		$con = connectDB();
		
		$count = count($ids);
		for($i = 0; $i < $count; $i++){		
			mysqli_query($con,"INSERT INTO newpages (id, title, description, time, next_id, text, page) VALUES ('".$ids[$i]."', '".$titles[$i]."', '". $descriptions[$i]."', '".$times[$i]."', '".$next_ids[$i]."', '".$texts[$i]."', '".$page."')");
		}
		
		disconnectDB($con);
		
	}
	
	function DB_getNewPageContent($id){
	
		$con = connectDB();
		
		if(!$con){
			return disconnectDBAndReturn($con,"");			
		}
		
		$result = mysqli_query($con,"SELECT * FROM newpages WHERE id='" . $id. "';");
	
		if(!$result) {
			return disconnectDBAndReturn($con,"");
		}

		while($row = mysqli_fetch_array($result))
		{
			$rs['text'] = $row['text'];
			$rs['title'] = $row['title'];
			$rs['next_title_id'] = $row['next_id'];
			
			return disconnectDBAndReturn($con,$rs);
		} 
		
	}
	
	function DB_getSDMaepArticleById($id){
	
		$con = connectDB();
		
		if(!$con){
			return disconnectDBAndReturn($con,"");			
		}
		
		$result = mysqli_query($con,"SELECT * FROM sdmaep_article WHERE id='" . $id. "';");
	
		if(!$result) {
			return disconnectDBAndReturn($con,"");
		}

		while($row = mysqli_fetch_array($result))
		{
			$rs['text'] = $row['text'];
			$rs['title'] = $row['title'];
			
			return disconnectDBAndReturn($con,$rs);
		} 
		
		
	}
	
	function DB_saveSDMaepArticle($link_id, $title, $text, $description){
		
		$con = connectDB();
		
		mysqli_query($con,"INSERT INTO sdmaep_article (id, title, text, description) VALUES ('". $link_id ."','".$title."', '".$text."', '".$description."')");
		
		mysqli_query($con,"UPDATE sdmaep_category SET description='".$description."' WHERE cat_id='".$link_id."'");
		
		disconnectDB($con);
	}
	
	function DB_saveRakutenCategoriesByParentid($parent_id,$ids,$titles){
	
		$con = connectDB();
		
		$count = count($ids);
		for($i = 0; $i < $count; $i++){		
			$key = $ids[$i].'/'.$parent_id;
			mysqli_query($con,"INSERT INTO rakuten_category (id, cat_id, parent_id, title, description) VALUES ('".$key."', '".$ids[$i]."', '". $parent_id."', '".$titles[$i]."', '".$titles[$i]."')");
		}
		
		disconnectDB($con);
						
	}
	
	function DB_saveSDMaepCategoryByParentid($parent_id,$id,$title,$type,$des){
	
		$con = connectDB();
				
		$key = $id.'/'.$parent_id;
		
		mysqli_query($con,"INSERT INTO sdmaep_category (id, cat_id, parent_id, title, type, description) VALUES ('".$key."', '".$id."', '". $parent_id."', '".$title."', '".$type."', '".$des."')");
		
		
		disconnectDB($con);
						
	}
	
	function DB_getSDMaepCategoriesByParentid($parent_id){
	
		$con = connectDB();
		
		if(!$con){
			return disconnectDBAndReturn($con,"");			
		}
		
		$result = mysqli_query($con,"SELECT * FROM sdmaep_category WHERE parent_id='" . $parent_id. "';");
	
		if(!$result) {
			return disconnectDBAndReturn($con,"");
		}
		
		$count = 0;
		$titles = array();
		$ids = array();
		$types = array();
		$descriptions = array();
		
		while($row = mysqli_fetch_array($result))
		{
			$titles[$count] = $row['title'];
			$ids[$count] =  $row['cat_id'];
			$types[$count] =  $row['type'];
			$descriptions[$count] = $row['description'];
			
			$count++;
		} 
		
		if($count == 0) return disconnectDBAndReturn($con, "");
		
		$rs['titles'] = $titles;
		$rs['ids'] =  $ids;
		$rs['types'] = $types;
		$rs['descriptions'] = $descriptions;
				
		return disconnectDBAndReturn($con,$rs);		
				
	}
	
	function DB_getSDMaepArticle($link_id){
	
		$article = DB_getSDMaepArticleById($link_id);
		
		$categories = DB_getSDMaepCategoriesByParentid($link_id);
		
		if($article == "") return "";
		$data = array();
		if($categories == ""){
			
			$titles = array();
			$ids = array();
			$types = array();
			$descriptions = array();
		
			$data['titles'] = $titles;
			$data['ids'] =  $ids;
			$data['types'] = $types;
			$data['descriptions'] = $descriptions;
		} else {
			$data['titles'] = $categories['titles'];
			$data['ids'] =  $categories['ids'];
			$data['types'] = $categories['types'];
			$data['descriptions'] = $categories['descriptions'];
		}
		
		$data['text'] = $article['text'];
		$data['title'] = $article['title'];
		
		return $data;
		
		
	}
	
	function DB_getAttributeById($link_id, $att){
		
		$con = connectDB();
		
		if(!$con){
			return disconnectDBAndReturn($con,"");			
		}
		
		$result = mysqli_query($con,"SELECT * FROM sdmaep_category WHERE cat_id='" . $link_id. "';");
	
		if(!$result) {
			return disconnectDBAndReturn($con,"");
		}

		while($row = mysqli_fetch_array($result))
		{
			$rs = $row[$att];
			return disconnectDBAndReturn($con,$rs);	
		} 
			
	}
	
	function DB_saveSDMaepCategoriesByParentid($parent_id,$ids,$titles,$types){
	
		$con = connectDB();
		
		$count = count($ids);
		for($i = 0; $i < $count; $i++){		
			$key = $ids[$i].'/'.$parent_id;
			mysqli_query($con,"INSERT INTO sdmaep_category (id, cat_id, parent_id, title, type, description) VALUES ('".$key."', '".$ids[$i]."', '". $parent_id."', '".$titles[$i]."', '".$types[$i]."', '".$titles[$i]."')");
		}
		
		disconnectDB($con);
						
	}
	
	function DB_getRakutenCategoriesByParentid($parent_id){
	
		$con = connectDB();
		
		if(!$con){
			return disconnectDBAndReturn($con,"");			
		}
		
		$result = mysqli_query($con,"SELECT * FROM rakuten_category WHERE parent_id='" . $parent_id. "';");
	
		if(!$result) {
			return disconnectDBAndReturn($con,"");
		}
		
		$count = 0;
		$titles = array();
		$ids = array();
		$des = array();
		
		while($row = mysqli_fetch_array($result))
		{
			$titles[$count] = $row['title'];
			$ids[$count] =  $row['cat_id'];
			$des[$count] =  $row['description'];
			$count++;
		} 
		
		if($count == 0) return disconnectDBAndReturn($con, "");
		
		$rs['titles'] = $titles;
		$rs['ids'] =  $ids;
		$rs['descriptions'] = $des;
				
		return disconnectDBAndReturn($con,$rs);		
				
	}
	
	function DB_saveRakutenDairy($link_id, $title, $text, $description){
		
		$con = connectDB();
		
		mysqli_query($con,"INSERT INTO rakuten_dairy (id, title, text, description) VALUES ('". $link_id ."','".$title."', '".$text."', '".$description."')");
		
		mysqli_query($con,"UPDATE rakuten_category SET description='".$description."' WHERE cat_id='".$link_id."'");
		
		disconnectDB($con);
	}
	
	function DB_getRakutenDairyById($id){
	
		$con = connectDB();
		
		if(!$con){
			return disconnectDBAndReturn($con,"");			
		}
		
		$result = mysqli_query($con,"SELECT * FROM rakuten_dairy WHERE id='" . $id. "';");
	
		if(!$result) {
			return disconnectDBAndReturn($con,"");
		}

		while($row = mysqli_fetch_array($result))
		{
			$rs['text'] = $row['text'];
			$rs['title'] = $row['title'];
			
			return disconnectDBAndReturn($con,$rs);
		} 
		
		
	}
	
	function DB_getRakutenDairy($link_id){
	
		$dairy = DB_getRakutenDairyById($link_id);
		
		$categories = DB_getRakutenCategoriesByParentid($link_id);
		
		if($dairy == "") return "";
		$data = array();
		if($categories == ""){
			
			$titles = array();
			$ids = array();
			$types = array();
		
			$data['titles'] = $titles;
			$data['ids'] =  $ids;
			$data['descriptions'] = $types;
		} else {
			$data['titles'] = $categories['titles'];
			$data['ids'] =  $categories['ids'];
			$data['descriptions'] = $categories['descriptions'];
		}
		
		$data['text'] = $dairy['text'];
		$data['title'] = $dairy['title'];
		
		return $data;
		
		
	}
	
	function DB_getRakutenCategoryAttributeById($link_id, $att){
		
		$con = connectDB();
		
		if(!$con){
			return disconnectDBAndReturn($con,"");			
		}
		
		$result = mysqli_query($con,"SELECT * FROM rakuten_category WHERE cat_id='" . $link_id. "';");
	
		if(!$result) {
			return disconnectDBAndReturn($con,"");
		}

		while($row = mysqli_fetch_array($result))
		{
			$rs = $row[$att];
			return disconnectDBAndReturn($con,$rs);	
		} 
			
	}
	
	function connectDB(){
	
		try{
	
			$con=mysqli_connect("slamdunk.cpbb1tubihfb.ap-northeast-1.rds.amazonaws.com","root","root123456","slam_dunk");

			if (mysqli_connect_errno())
			{
			  return false;
			}
			
			return $con;
		} catch(Exception $e){
			//error_log("DB_connectDB Func: ".$e->getMessage(), 0, "error_log.text");
		}
	}
	
	function disconnectDB($con){
		try{
			mysqli_close($con);
		} catch(Exception $e){
			//error_log("DB_disconnectDB Func: ".$e->getMessage(), 0, "error_log.text");
		}
	}
	
	function disconnectDBAndReturn($con, $result){
		try{
			mysqli_close($con);
			return $result;
		} catch(Exception $e){
			//error_log("DB_disconnectDBAndReturn Func: ".$e->getMessage(), 0, "error_log.text");
		}
	}
?>