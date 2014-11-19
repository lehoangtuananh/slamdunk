<?php
	require "../lib/htmlUtil.php";
	require "../lib/database.php";
	
	
	mb_internal_encoding('UTF-8');
	
	function SF_parseIndex() {
		
		$node = getNodeFromURLByXpath("http://slamdunkmaep.seesaa.net/", '//*[@id="content"]/div[6]');
		
		$titles = array();
		$ids = array();
		$types = array();
		$count = 0;
		
		foreach ($node->getElementsByTagName("*") as $el) {
			if ($el->tagName == "a"){	
				$temp = $el->textContent;
				
					$titles[$count] = $temp;
					$ids[$count] = getIdFromSDMaepURL($el->getAttribute('href'));
					$types[$count] = getTypeFromURL($el->getAttribute('href'));
					$count++;
				
			}
		}		
				
		DB_saveSDMaepCategoriesByParentid("index",$ids,$titles,$types);
		
	}
	
	function SF_getIndex() {
		
		$data = DB_getSDMaepCategoriesByParentid("index");
		
		if($data == ""){
			SF_parseIndex();
			$data = DB_getSDMaepCategoriesByParentid("index");
		}
		
		$result['title_list'] = $data['titles'];
		$result['id_list'] =  $data['ids'];
		$result['type_list'] =  $data['types'];
		$result['total_page'] = count($data['titles']);
		
		return $result;
	}
	
	function SF_parseCategory($link_id){
		
		//truong hop du lieu chua ton tai can phai parse
		$has_tab = true;
		$count_tab = 1;
		
		$result = "";
			
		$titles = array();
		$ids = array();
		$types = array();
		$count = 0;
		
		while($has_tab){
		
			$content_node = getNodeFromURLByXpath("http://slamdunkmaep.seesaa.net/category/".$link_id."-".$count_tab.".html", '//*[@id="content"]');
			
			$blogs_in_page_counter = 0;
			
			foreach ($content_node->getElementsByTagName('h3') as $title) {
				if ($title->getAttribute('class') == "title"){	
					
					foreach ($title->getElementsByTagName('a') as $achor) {
					
						$titles[$count] = $achor->nodeValue;
						$ids[$count] = getIdFromSDMaepURL($achor->getAttribute('href'));
						$types[$count] = getTypeFromURL($achor->getAttribute('href'));
						$count++;
						
						$blogs_in_page_counter++;
					}
					//echo $blog->nodeValue;
					
					
				}
			}
			
			if($blogs_in_page_counter == 0) $has_tab = false;			
			$count_tab++;

		}
		
		DB_saveSDMaepCategoriesByParentid($link_id,$ids,$titles,$types);
		
		return $result;
	}
	
	function SF_getCategory($link_id){
		
		$data = DB_getSDMaepCategoriesByParentid($link_id);
		
		if($data == ""){
			SF_parseCategory($link_id);
			$data = DB_getSDMaepCategoriesByParentid($link_id);
		}
		
		$result['title_list'] = $data['titles'];
		$result['id_list'] =  $data['ids'];
		$result['type_list'] =  $data['types'];
		$result['des_list'] =  $data['descriptions'];
		$result['total_page'] = count($data['titles']);
		
		return $result;
		
	}
	
	function SF_parseArticle($link_id){
	
		$titles = array();
		$ids = array();
		$types = array();
		$count = 0;
		
		$content_node = getNodeFromURLByXpath("http://slamdunkmaep.seesaa.net/article/".$link_id.".html", '//*[@id="content"]/div[3]/div/div[1]');
		
		$text = '';
		$des = '';
		
		foreach ($content_node->childNodes as $child){
			if ($child->nodeType == 1 && $child->nodeName == 'a'){	
				
				if ($child->nodeValue == '') continue;
				
				$titles[$count] = $child->nodeValue;
				$id = getIdFromSDMaepURL($child->getAttribute('href'));
				$ids[$count] = $id;
				$types[$count] = getTypeFromURL($child->getAttribute('href'));
				$text.= 'id='.$id.'<br>';
				$count++;								
				
			}
			elseif ($child->nodeType == 3){
				$text.= $child->nodeValue."<br>";
				$des.= $child->nodeValue."<br>";
			}
		}				
		//$text = substr($text,0, strrpos("CDATA", $text) - 5);
		$title = DB_getAttributeById($link_id, 'title');
		
		$text = substr($text,0, strrpos( $text,"CDATA") - 5);
		
		$des_len = strlen($des);
		if ($des_len > 200){
			$description = substr($des, 0, strpos($des, "<br>", 180));
		} else{
			$description = substr($des, 0, $des_len);
		}
				
		DB_saveSDMaepArticle($link_id, $title, $text,$description);
		if($count > 0) DB_saveSDMaepCategoriesByParentid($link_id,$ids,$titles,$types);
				
	}
	
	function SF_getArticle($link_id){
	
		//truong hop du lieu da ton tai trong DB
		$data = DB_getSDMaepArticle($link_id);
		
		if($data == ""){
			SF_parseArticle($link_id);
			$data = DB_getSDMaepArticle($link_id);
		}
		
		$result['title_list'] = $data['titles'];
		$result['id_list'] =  $data['ids'];
		$result['type_list'] =  $data['types'];
		$result['des_list'] =  $data['descriptions'];
		$result['text'] = $data['text']; 
		$result['title'] = $data['title'];
		$result['total_page'] = count($data['titles']);
		
		return $result;
	}
	

?>