<?php
	require "../lib/htmlUtil.php";
	require "../lib/database.php";
	
	mb_internal_encoding('UTF-8');


    $i = 0 ;

    function LF_writeToFile()
    {
        $parse_data = array(
            51828623 => "＃６７８（全国編）……守備の戦略" ,
            51829433 => "＃６７９（全国編）……不気味なゲーム" ,
            51830424 => "＃６８０（全国編）……俺の仕事" ,
            51830520 => "＃６８１（全国編）……獲り返すのがエース" ,
            51831739 => "＃６８２（全国編）……待たせ過ぎながら最高" ,
            51831860 => "＃６８３（全国編）……難しいゲーム" ,
            51832620 => "＃６８４（全国編）……よくない空気" ,
            51832658 => "＃６８５（全国編）……秘密兵器と呼ばれる男" ,
            51833450 => "＃６８６（全国編）……伏兵の活躍" ,
            51835076 => "＃６８７（全国編）……原点回帰" ,
            51835085 => "＃６８８（全国編）……背中を追う" ,
            51835834 => "＃６８９（全国編）……仲良く仲良く" ,
            51836761 => "＃６９０（全国編）……新必殺技" ,
            51838290 => "＃６９１（全国編）……勝負の福田" ,
            51838296 => "＃６９２（全国編）……仕掛ける陵南" ,
            51838328 => "＃６９３（全国編）……4人のビッグマン" ,
            51842305 => "＃６９４（全国編）……仕掛けない湘北" ,
            51843038 => "＃６９５（全国編）……再認識の作戦" ,
            51843743 => "＃６９６（全国編）……流れをモノにしろ" ,
            51844536 => "＃６９７（全国編）……再現" ,
            51844608 => "＃６９８（全国編）……湘北の時間" ,
            51845526 => "＃６９９（全国編）……崩れたアンタッチャブル" ,
            51846167 => "＃７００（全国編）……もう一本、もう二本" ,
            51846223 => "＃７０１（全国編）……火花散るエース" ,
            51846374 => "＃７０２（全国編）……15秒の勝負" ,
            51846762 => "＃７０３（全国編）……冬の王者決定まであと10分" ,
            51848648 => "＃７０４（全国編）……フォワードコンビ" ,
            51849238 => "＃７０５（全国編）……本当の本気"
        );

        foreach($parse_data as $x => $x_value) {
            //$nextkey = array_search($x, $parse_data) + 1;
            //echo "Key=" . $x . ", Value=" . $x_value . "Next=" .get_next_key($x,$parse_data);
            //echo "<br>";
            LF_parseContent_write_file($x,$x_value,get_next_key($x,$parse_data));
        }

    }

    function get_next_key($key,$array)
    {
        //return current(array_slice($array, array_search($key, array_keys($array)) + 1, 1));
        $keys = array_keys($array);
        return $keys[array_search($key,$keys)+1];
    }

    function LF_parseContent_write_file($link_id,$_title,$_next_id) {

        //TODO: xoa sau khi parse
        //echo $link_id."  |  ";

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
        $text = HU_decode_SpecChar($text);

        $text = str_replace("\n","<br>\n",$text);
        $text = str_replace("\t\t\t\t\t","",$text);

        $node =$nodes[1];
        $title = $node->nodeValue;

        $respone = array("result_code" => 1,"text"=> $text,"next_title_id" => $_next_id,"title"=> $_title,);


        //echo json_encode($respone);

        file_put_contents("/Users/tuananh/Desktop/php/".$link_id,json_encode($respone));

    //		DB_saveLivedoorContent($link_id, $next_title_id, $title, $text);
    //
    //		$description = substr($text, 0, strpos($text, "<br>", 200));
    //
    //		DB_updateDescriptionForLivedoorPage($link_id, $description);

    }

	function LF_parseContent($link_id) {
	
		//TODO: xoa sau khi parse
		//echo $link_id."  |  ";
	
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
		$text = HU_decode_SpecChar($text);
		
		$text = str_replace("\n","<br>\n",$text);
		$text = str_replace("\t\t\t\t\t","",$text);
				
		$node =$nodes[1];
		$title = $node->nodeValue;

        $respone = array("result_code" => 1,"text"=> $text,"next_title_id" => "51830424","title"=> "＃６７９（全国編）……不気味なゲーム",);


        return $respone;
		
//		DB_saveLivedoorContent($link_id, $next_title_id, $title, $text);
//
//		$description = substr($text, 0, strpos($text, "<br>", 200));
//
//		DB_updateDescriptionForLivedoorPage($link_id, $description);
				
	}


	
//	function LF_getContent($link_id) {
//
//		$data = DB_getLivedoorContentById($link_id);
//
//		if($data == ""){
//			LF_parseContent($link_id);
//			$data = DB_getLivedoorContentById($link_id);
//		}
//
//        LF_parseContent($link_id);
//
//	}

    function LF_getContent($link_id){
        return LF_parseContent($link_id);
    }
			
	function LF_parseIndex() {
		
		$node = getNodeFromURLByXpath("http://blog.livedoor.jp/jungle123/", '//*[@id="sub"]/div[3]/div[3]/center/table');
		
		$titles = array();
		$ids = array();
		$count = 0;
		
		foreach ($node->getElementsByTagName("*") as $el) {
			if ($el->tagName == "a"){	
				$temp = $el->textContent;
				
					$titles[$count] = $temp;
					$ids[$count] = getIdFromURL($el->getAttribute('href'));
					$count++;
				
			}
		}		
		
		DB_saveLivedoorPagesByParentid("index",$ids,$titles,$titles);
	}
	
	function LF_getIndex() {
		//truong hop du lieu da ton tai trong DB
		$data = DB_getLivedoorPagesByParentid("index");
		
		if($data == ""){
			LF_parseIndex();
			$data = DB_getLivedoorPagesByParentid("index");
		}
		
		$result['title_list'] = $data['titles'];
		$result['id_list'] =  $data['ids'];
		$result['total_page'] = count($data['titles']);
		
		return $result;
	}
	
	function LF_getTitles($link_id) {
		//truong hop du lieu da ton tai trong DB
		$data = DB_getLivedoorPagesByParentid($link_id);

		if($data != ""){
			$result['title_list'] = $data['titles'];
			$result['id_list'] =  $data['ids'];
			$result['des_list'] =  $data['description'];
			$result['total_page'] = count($data['titles']);

			return $result;
		}
		
		//truong hop du lieu chua ton tai can phai parse
		$node = getNodeFromURLByXpath("http://blog.livedoor.jp/jungle123/archives/" . $link_id . '.html', '//*[@id="entry-' . $link_id . '"]/div[1]');
				
		$result = "";
		
		$titles = array();
		$ids = array();
		$count = 0;
		
		foreach ($node->getElementsByTagName("a") as $el) {
			//if ($el->tagName == "a"){	
				$temp = $el->textContent;
				//if(preg_match('/[0-9]/', $temp)){
					$titles[$count] = $temp;
					$ids[$count] = getIdFromURL($el->getAttribute('href'));
					$count++;
				//}
			//}
		}		
		
		$result['title_list'] = $titles;
		$result['id_list'] =  $ids;
		$result['des_list'] = $titles;
		$result['total_page'] = $count;
		
		DB_saveLivedoorPagesByParentid($link_id,$ids,$titles,$titles);
		
		return $result;
	}
	
	function LF_parseLastPages() {
		return LF_parseTitles(CF_getLastPageID());
	}
	
	function LF_getLastPages() {
		return LF_getTitles(CF_getLastPageID());
	}
		
?>