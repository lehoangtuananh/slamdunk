<?php
	function CF_getLastPageID(){
		
		$dom = new DOMDocument('1.0', 'utf-8');
		$dom->load("../xml/config.xml");
		
		$config = $dom->documentElement;
		
		return $config->getElementsByTagName('lastpageID')->item(0)->nodeValue;
	}
	
	function CF_title(){
		
		$dom = new DOMDocument('1.0', 'utf-8');
		$dom->load("../xml/config.xml");
		
		$config = $dom->documentElement;
		
		return $config->getElementsByTagName('title')->item(0)->nodeValue;
	}
?>