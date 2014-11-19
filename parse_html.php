<?php
	require "lib/htmlUtil.php";
	mb_internal_encoding('UTF-8');
	
	$link_id = $_GET["id"];
	
	//echo strlen(CF_title());
	//$paths = array('//*[@id="entry-' . $link_id . '"]/div[1]', '//*[@id="entry-'.$link_id.'"]/h2/a');
		
	//$nodes = getNodesFromURLByXpaths("http://blog.livedoor.jp/jungle123/archives/" . $link_id . '.html', $paths);
	
	//echo $nodes[0]->nodeValue;
	//echo $nodes[1]->nodeValue;
	
		$paths = array('//*[@id="entry-' . $link_id . '"]/div[1]', '//*[@id="entry-'.$link_id.'"]/h2/a', '//*[@id="entry-' . $link_id . '"]/div[1]/a');
		
		$nodes = getNodesFromURLByXpaths("http://blog.livedoor.jp/jungle123/archives/" . $link_id . '.html', $paths);
		
		$node = $nodes[0];
		
		$text = "";
		$next_title_id = "";
		
		foreach ($node->getElementsByTagName("a") as $el) {
			$next_title_id = getIdFromURL($el->getAttribute('href'));
			break;
		}
		
		if($next_title_id == ''){
			$next_title_id = $nodes[2]->nodeValue;
		}
		
		$text = $node->nodeValue;
		
		$next_char_pos = strrpos($text, "く")-3;
		$char_pos = strrpos($text, "window");
		
		if (($char_pos-$next_char_pos) <30) $char_pos = $next_char_pos;
		$text = substr($text, 0 , $char_pos);
		
		$text = HU_clipContent($text);
		$text = str_replace("\n","<br>\n",$text);
		$text = str_replace("\t\t\t\t\t","",$text);
				
		$node =$nodes[1];
		$title = $node->nodeValue;
		
		
		$description = substr($text, 0, strpos($text, "<br>", 200));
		
		echo HU_decode_SpecChar($text);
?>