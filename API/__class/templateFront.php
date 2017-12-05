<?php 
require_once("template.php");
class TemplateFront extends Template {
	
	function __construct(){
		$lienDossierCSS = "/".self::$lienTemplate."/css/";
		$lienDossierJS = "";
		
		
		$css = array(
			"/front.css",
			"/front_responsive.css"
		);
		
		$js = array(
			"/".self::$lienTemplate."/js/front.js"
		);
		
		parent::__construct($css,$js);
	}


		
}
?>