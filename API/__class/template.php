<?php 
Template::$lienTemplate = "front";
 class Template{
	public static $lienTemplate;
	public $css;
	public $js;
	
	function __construct(array $css,array $js){
		
		
		$lienDefaultCSS = "/".self::$lienTemplate."/css/";
		$lienDefaultJS = "/".self::$lienTemplate."/js/";
		$this->css = array(
			$lienDefaultCSS."bootstrap.min.css",
			$lienDefaultCSS."font-awesome.min.css",
			"/style.css"
		);		
		$this->js = array(
			$lienDefaultJS."jquery-3.1.1.min.js",
			$lienDefaultJS."bootstrap.js",
			$lienDefaultJS."smoothscroll.js" 
		);
		$this->css = array_merge($this->css,$css);
		$this->js = array_merge($this->js,$js);
	}
	
	function get_css(){
		$s="";
		foreach($this->css as $fichier){
			$s.='<link rel="stylesheet" type="text/css" href="'.$fichier.'"/>
			';
		}
		return $s;
	}
	
	function get_js(){
		$s="";
		foreach($this->js as $fichier){
			$s.='<script src="'.$fichier.'"></script>
			';
		}
		return $s;
	}
	
	function get_lienTemplate(){
		return self::$lienTemplate ;
	}
}
?>