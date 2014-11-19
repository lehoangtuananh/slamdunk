<?php

	function getContentInHTMLTag ($html) {
	
		$IsCharOfTag = false;
				
	}
	
	function getLinkInAchorTag ($html) {
	
		$token = strtok($html, "href=\"");
		$token = strtok("\"");
		$token = strtok("\"");
		return $token;
	
	}	
	
	function getIdFromURL ($url) {
		$start = strrpos($url, "/") +1;
		$length = strrpos($url, ".") - $start;
		return substr($url, $start, $length);
	}
	
	function getIdFromSDMaepURL ($url) {
		$start = strrpos($url, "/") +1;
		$length = strrpos($url, "-") - $start;		
		if($length < 0) $length = strrpos($url, ".") - $start;
		return substr($url, $start, $length);
	}
	
	function getIdFromRakutenURL ($url) {
		$url = substr($url, 0, strlen($url) -1);
		$start = strrpos($url, "/") +1;
		return substr($url, $start, strlen($url));
	}
	
	function getTypeFromURL($url){
		//type = 1: category
		//type = 2: article
		$last_splash = strrpos($url, "/");		
		$url = substr($url, 0, $last_splash);		
		$url = substr($url, strrpos($url, "/") +1);
		
		if($url == "category") return "1";
		
		if($url == "article") return "2";
		
		return "0";
	}
	
	function HU_readPage($link){
		$file = fopen($link,'r') or exit("Unable to open page!");
		$content = "";
		
		while(!feof($file))
		{
			$content.=fgets($file);
		}
		fclose($file);
		
		return $content;
	}
	
	function HU_getNode($content, $path){
		$d = new domdocument();
		libxml_use_internal_errors(true);
		$d->loadHTML($content);
		
		$xpath = new domxpath($d);
		
		foreach($xpath->query($path) as $node){
			return $node;
		}
	}
	
	function getNodeFromURLByXpath ($link, $path){
	
		$content = HU_readPage($link);
		
		$node = HU_getNode($content, $path);
		$text = $node->nodeValue;
		
		if (strpos($link,'slamdunkmaep') !== false && strpos($text,'CDATA') == false) {
				$content = str_replace("\x87\x70", "cm", $content);
				$content = str_replace("\x87\x73", "kg", $content);
				$content = str_replace("\x87\x5B", "VIII", $content);
				$content = str_replace("\xFB\xFC\x8D\xBB", "ab\x8D\xBB", $content);
				$node = HU_getNode($content, $path);
			}
		
		return $node;
	}
	
	function getNodesFromURLByXpaths ($link, $paths){
		$file = fopen($link,'r') or exit("Unable to open page!");
		$content = "";
		
		while(!feof($file))
		{
			$content.=fgets($file);
		}
		fclose($file);
		
		
		//todo: xoa sau debug
		//echo $content;
		
		
		$d = new domdocument('1.0', 'UTF-8');
		libxml_use_internal_errors(true);
		$d->loadHTML($content);
		
		$xpath = new domxpath($d);
		$result = array();
		foreach($paths as $i => $path){
			
			foreach($xpath->query($path) as $node){
				$result[$i] = $node;
			}
		}
		
		
		if(count($result) < count($paths)){
			$link_id = getIdFromURL ($link);
			$start_pos = strpos($content, '<h2 class="entry-title">');
			$end_pos = strpos($content, '<div id="ad2"></div>');
			if($end_pos > $start_pos){
				$content = substr($content, $start_pos, $end_pos - $start_pos);
			}
			
			$content.='</div>';
			$content = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
						<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja"> 

						<head>
						<meta http-equiv="Content-Type" content="text/html; charset=euc-jp" /></head><body>'.$content.'</body></html>';
						
			//echo bin2hex($content);
			
			$content = HU_encode_SpecChar($content);
		
			$paths = array('/html/body/div', '/html/body/h2/a', '/html/body/div/a');
			
			libxml_use_internal_errors(false);
			$d->loadHTML($content);
		
			$xpath = new domxpath($d);
			$result = array();
			foreach($paths as $i => $path){
				
				foreach($xpath->query($path) as $node){
					$result[$i] = $node;
				}
			}
			//$result[0]->nodeValue = $content;
		}
		
		return $result;
	}
	
	function HU_getLivedoorContent($link){
	
		//echo file_get_contents($link);
		
		$file = fopen($link,'r') or exit("Unable to open page!");
		$content = "";
		
		while(!feof($file))
		{
			$content.=fgets($file);
		}
		fclose($file);
		
		$start_pos = strpos($content, '<h2 class="entry-title">');
		$end_pos = strpos($content, '<div id="ad2"></div>');
		if($end_pos > $start_pos){
			$content = substr($content, $start_pos, $end_pos - $start_pos);
		}
		
		$content.='</div>';
		$content = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
					<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja"> 

					<head>
					<meta http-equiv="Content-Type" content="text/html; charset=euc-jp" /></head><body>'.$content.'</body></html>';
					
		echo $content;
		
		$content = HU_encode_SpecChar($content);
		
		$d = new domdocument('1.0', 'UTF-8');
		$d->resolveExternals = false;
		$d->substituteEntities = false;
		libxml_use_internal_errors(false);
		$d->loadHTML($content);
		
		$text = '';
		$des = '';
		
		foreach ($d->getElementsByTagName('div') as $content_node){
			
			foreach ($content_node->childNodes as $child){
				if ($child->nodeType == 1 && $child->nodeName == 'a'){	
					
					if ($child->nodeValue == '') continue;
					
					echo 'next = '.$child->nodeValue;
					echo 'next_id = '.getIdFromURL($child->getAttribute('href'));
					
				}
				elseif ($child->nodeType == 3){
					$text.= $child->nodeValue."<br>";
					$des.= $child->nodeValue."<br>";
				}
			}	
		}
		
		echo $text;
		
	}
		
	function HU_clipContent($content){
		$count = 0;
		$content = str_replace("\n\n", "\n", $content, $count);
		if($count > 0 ){
			return HU_clipContent($content);
		} else{
			return $content;
		}
	}
	
	function HU_encode_SpecChar($content){
		$content = str_replace("\xAD\xE2\xA3\xB1", "No1", $content);
		$content = str_replace("\xAD\xE2\x31\xA5", "\x31\xA5", $content);
		
		//chapter 50735071
		$content = str_replace("\xAD\xE2\xA3\xB2", "\xA3\xB2", $content);
		$content = str_replace("\xAD\xD1", "", $content);
		
		$content = str_replace("\xAD\xD4", "", $content);
		
		$content = str_replace("\xAD\xE2", "", $content);
		//$content = str_replace("\xAD\xB6", "", $content);
		//chapter 51105775
		$content = str_replace("\xAD\xA4", "", $content);
		$content = str_replace("\xAD\xA5", "", $content);
		$content = str_replace("\xAD\xAB", "", $content);
		$content = str_replace("\xAD\xA7", "", $content);
		$content = str_replace("\xAD\xA6", "", $content);
		$content = str_replace("\xAD\xAA", "", $content);
		
		//c 51686785
		$content = str_replace("\xAD\xB6", "", $content);
		
		
		return $content;
		
	}
	
	
	function HU_decode_SpecChar($content){
		$content = str_replace('No1', '№１',$content);
		$content = str_replace('\7incircle', '⑦',$content);
		return $content;
		
	}
?>