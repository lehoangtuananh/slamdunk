<?php
	require "../lib/htmlUtil.php";
	require "../lib/database.php";
	
	
	mb_internal_encoding('UTF-8');
	
	function RF_parseIndex() {
		
		$node = getNodeFromURLByXpath("http://plaza.rakuten.co.jp/syokugurume/diary/?ctgy=8", '//*[@id="content-center"]/div/div[4]/div[2]');
		
		$titles = array();
		$ids = array();
		$count = 0;
		
		foreach ($node->getElementsByTagName("*") as $el) {
			if ($el->tagName == "a"){	
				$temp = $el->textContent;
				
					$titles[$count] = $temp;
					$ids[$count] = getIdFromRakutenURL($el->getAttribute('href'));
					$count++;
				
			}
		}		
				
		DB_saveRakutenCategoriesByParentid("index",$ids,$titles);
		
	}
	
	function RF_getIndex() {
		
		$data = DB_getRakutenCategoriesByParentid("index");
		
		if($data == ""){
			RF_parseIndex();
			$data = DB_getRakutenCategoriesByParentid("index");
		}
		
		$result['title_list'] = $data['titles'];
		$result['id_list'] =  $data['ids'];
		$result['des_list'] =  $data['descriptions'];
		$result['total_page'] = count($data['titles']);
		
		return $result;
	}
		
	function RF_parseDairy($link_id, $path){
	
		$titles = array();
		$ids = array();
		$count = 0;
		
		$content_node = getNodeFromURLByXpath("http://plaza.rakuten.co.jp/syokugurume/diary/".$link_id."/", $path); 
		
		$text = '';
		$des = '';
		
		foreach ($content_node->childNodes as $child){
			if ($child->nodeType == 1 && $child->nodeName == 'a'){	
				
				if ($child->nodeValue == '') continue;
				
				$titles[$count] = $child->nodeValue;
				$id = getIdFromRakutenURL($child->getAttribute('href'));
				$ids[$count] = $id;
				$text.= 'id='.$id.'<br>';
				$count++;								
				
			}
			elseif ($child->nodeType == 3){
				$text.= $child->nodeValue."<br>";
				$des.= $child->nodeValue."<br>";
			}
		}				
		//$text = substr($text,0, strrpos("CDATA", $text) - 5);
		$title = DB_getRakutenCategoryAttributeById($link_id, 'title');
		//$text = substr($text,0, strrpos( $text,"CDATA") - 5);
		
		$des_len = strlen($des);
		if ($des_len > 200){
			$description = substr($des, 0, strpos($des, "<br>", 180));
		} else{
			$description = substr($des, 0, $des_len);
		}
		//echo $link_id.'/'.$title.'/'.$text.'/'.$description;		
		DB_saveRakutenDairy($link_id, $title, $text, $description);
		if($count > 0) DB_saveRakutenCategoriesByParentid($link_id,$ids,$titles);
				
	}
	
	function RF_getDiary($link_id, $path){
	
		//truong hop du lieu da ton tai trong DB
		$data = DB_getRakutenDairy($link_id);
		
		if($data == ""){
			RF_parseDairy($link_id, $path);
			$data = DB_getRakutenDairy($link_id);
		}
		
		$result['title_list'] = $data['titles'];
		$result['id_list'] =  $data['ids'];
		$result['des_list'] =  $data['descriptions'];
		$result['text'] = $data['text']; 
		$result['title'] = $data['title'];
		$result['total_page'] = count($data['titles']);
		
		return $result;
	}
	

?>