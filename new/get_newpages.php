<?php
	header('Content-Type: application/json; charset=utf-8');
	require "news_and_parse_func.php";
    require "simple_html_dom.php";
	mb_internal_encoding('UTF-8');

    $result = NAPF_getNewPages();

	$respone = array("result_code" => 1,"title_list"=> $result['title_list'],"id_list" => $result['id_list'],"des_list" => $result['des_list'],"time_list" => $result['time_list'],"total_page"=>$result['total_page'],);
	echo json_encode($respone);

?>
