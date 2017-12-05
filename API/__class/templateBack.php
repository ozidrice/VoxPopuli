<?php 
require_once("template.php");
class TemplateBack extends Template {
	
	function __construct(){
		$lienDossierCSS = "/".self::$lienTemplate."/admin/css/";
		$lienDossierJS = "/".self::$lienTemplate."/js/";
		
		$css = array(
			"/back.css",
			"/back_responsive.css",
		);
		
		$js = array(
			$lienDossierJS."back.js"
		);
		
		parent::__construct($css,$js);
	}


	function get_lienTemplate(){
		return self::$lienTemplate."/admin/";
	}
}
?>