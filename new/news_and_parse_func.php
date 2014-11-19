<?php
require "../lib/htmlUtil.php";
require "../lib/database.php";

mb_internal_encoding('UTF-8');

function NAPF_parseNewPages(){

    //NAPF_parseLivedoorNewPages();
    //NAPF_parseSdmaepNewPages();
    return NAPF_parserLiverdoorNewPages();
}

function NAPF_parseSdmaepNewPages(){

    $node = getNodeFromURLByXpath("http://slamdunkmaep.seesaa.net/", '//*[@id="content"]/div[4]');

    $titles = array();
    $ids = array();
    $count = 0;

    foreach ($node->getElementsByTagName("*") as $el) {
        if ($el->tagName == "a"){
            $temp = $el->textContent;
            $titles[$count] = $temp;
            $ids[$count] = getIdFromSDMaepURL($el->getAttribute('href'));
            $count++;

        }
    }

    for($i = 0; $i<count($ids); $i++){
        $link_id = $ids[$i];
        $paths = array('//*[@id="content"]/div[3]/div/div[1]', '//*[@id="content"]/div[3]/h2','//*[@id="content"]/div[3]/div/div[2]/a[2]');

        $nodes = getNodesFromURLByXpaths("http://slamdunkmaep.seesaa.net/article/".$link_id.".html", $paths);

        $sdmaep_node = $nodes[0];

        $text = '';
        $des = '';

        $article_titles = array();
        $article_ids = array();
        $article_types = array();
        $article_count = 0;

        foreach ($sdmaep_node->childNodes as $child){
            if ($child->nodeType == 1 && $child->nodeName == 'a'){

                if ($child->nodeValue == '') continue;

                $article_titles[$article_count] = $child->nodeValue;
                $article_id = getIdFromSDMaepURL($child->getAttribute('href'));
                $article_ids[$article_count] = $article_id;
                $article_types[$article_count] = getTypeFromURL($child->getAttribute('href'));
                $text.= 'id='.$article_id.'<br>';
                $article_count++;

            }
            elseif ($child->nodeType == 3){
                $text.= $child->nodeValue."<br>";
                $des.= $child->nodeValue."<br>";
            }
        }

        $title = $titles[$i];
        $text = substr($text,0, strrpos( $text,"CDATA") - 5);

        $des_len = strlen($des);
        if ($des_len > 200){
            $description = substr($des, 0, strpos($des, "<br>", 180));
        } else{
            $description = substr($des, 0, $des_len);
        }

        $time_node =$nodes[1];
        $times[$i] = $time_node->nodeValue;

        //$parent_achor_node = $nodes[2];
        //echo $parent_achor_node->nodeValue;
        //$parent_id = getIdFromSDMaepURL($parent_achor_node->getAttribute('href'));


        //DB_saveSDMaepCategoryByParentid($parent_id,$link_id,$title,"2", $description);

        $descriptions[$i] = $description;
        $next_ids[$i] = "";
        $texts[$i] = $text;

        //save for sdmaep
        //DB_saveSDMaepArticle($link_id, $title, $text);
        //if($article_count > 0) DB_saveSDMaepCategoriesByParentid($link_id,$article_ids,$article_titles,$article_types);

    }

    DB_saveNewPages($ids, $titles, $descriptions, $times, $texts, $next_ids, 'sdmaep');
}

function NAPF_parserLiverdoorNewPages()
{
    get_description_list("");

    $arr_title = array();
    $arr_url = array();
    $arr_des = array();

    $url = "http://blog.livedoor.jp/jungle123/";
    $html = file_get_html($url);
    // Find all <div> which attribute id=foo
    $ret = $html->find('.sidebody');
    foreach ( $ret as $node ){
        foreach ( $node->find('a') as $el ){
            if (strpos($el -> plaintext, 'スラムダンクの続きを勝手に考えてみる') !== false){
                $arr_title[] = $el -> plaintext;
                $arr_url[] = $el -> href;
                $arr_des[] = get_description_list($el -> href);
            }
        }
    }
    $result = array("title_list"=> $arr_title,"id_list" => $arr_url,"des_list" => $arr_des,"time_list" => "","total_page"=>"",);
    return $result;

//        $retNext = $html->find('.hentry');
//        foreach ( $retNext as $node ){
//            // title and url
//            foreach ( $node -> find('div[class=entry-meta]') as $ele){
//                foreach ( $ele -> find('a') as $el)
//                   if (strpos($el -> title, 'スラムダンクの続きを勝手に考えてみる') !== falclea){
//                     echo  "|" . $el -> title . "|" . $el->href . "\n" ;
//                   }
//
//
//            }
//            $ret = $node->find('li[class=published]');
//            foreach ( $ret as $child )
//            {
//                //echo $child -> plaintext;
//            }
//        }

}

function get_description_list($url)
{
    $url = "http://blog.livedoor.jp/jungle123/archives/51836761.html";
    $html = file_get_html($url);
    // Find all <div> which attribute id=foo
    $ret = $html->find('div[class=entry-content]');
    $str = "";

    foreach( $ret as $el ){
        $str = $el;
    }

    return substr($str,30,180);
}

function NAPF_parseLivedoorNewPages(){

    $node = getNodeFromURLByXpath("http://blog.livedoor.jp/jungle123/", '//*[@id="sub"]/div[5]/div[3]');

    $titles = array();
    $ids = array();
    $count = 0;

    foreach ($node->getElementsByTagName("*") as $el) {
        if ($el->tagName == "a"){
            $temp = $el->textContent;
            if(!strncmp(CF_title(), $temp, 54)){
                $titles[$count] = $temp;
                $ids[$count] = getIdFromURL($el->getAttribute('href'));
                $count++;
            }
        }
    }


    for($i = 0; $i<count($ids); $i++){
        $link_id = $ids[$i];
        $paths = array('//*[@id="entry-' . $link_id . '"]/div[1]', '//*[@id="main"]/ul/li/abbr', '//*[@id="entry-' . $link_id . '"]/div[1]/a');

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
        $times[$i] = $node->getAttribute('title');

        $descriptions[$i] = substr($text, 0, strpos($text, "<br>", 200));

        $next_ids[$i] = $next_title_id;
        $texts[$i] = $text;

    }

    DB_saveNewPages($ids, $titles, $descriptions, $times, $texts, $next_ids, 'livedoor');

}

function NAPF_getNewPages(){

    return NAPF_parseNewPages();

//        $data = DB_getNewPages();
//
//		if($data == ""){
//		  NAPF_parseNewPages();
//		  $data = DB_getNewPages();
//		}
//
//
//		$result['title_list'] = $data['titles'];
//		$result['id_list'] =  $data['ids'];
//		$result['total_page'] = count($data['titles']);
//		$result['des_list'] =  $data['description'];
//		$result['time_list'] = $data['times'];
//
//		return $result;

}

function NAPF_getNewContent($link_id){
    return DB_getNewPageContent($link_id);
}

?>