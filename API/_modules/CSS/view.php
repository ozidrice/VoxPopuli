<?php 
class CSSView extends View{

 	public $black = "#343434";
 	public $white = "#fff";
 	public $red = "#d83013";
 	public $green = "#5FCF80";

 	public $back_color_menu_galerie;
 	public $back_color_galerie = "#f5f5f5";
	public $text_color_galerie;

	function __construct(){
		parent::__construct();
		header('content-type: text/css');
  		ob_start('ob_gzhandler');
    	header('Cache-Control: max-age=604800, must-revalidate');


    	$this->back_color_menu_galerie = $this->black;
    	$this->text_color_galerie =  $this->black;

		
	}
	
	function style(){
		include $this->templateFolder."/css/style.css"; 
	}

	function galerie_responsive(){
		include $this->templateFolder."/css/galerie_responsive.css"; 
	}

	function galerie(){
		include $this->templateFolder."/css/galerie.css"; 
	}

	function front_responsive(){
		include $this->templateFolder."/css/front_responsive.css"; 
	}

	function front(){
		include $this->templateFolder."/css/front.css"; 
	}

	function connexion(){
		include $this->templateFolder."/css/connexion.css"; 
	}

	function back(){
		include $this->templateAdminFolder."/css/back.css"; 
	}

	function back_responsive(){
		include $this->templateAdminFolder."/css/back_responsive.css"; 
	}
	
	function get_name(){
		return get_class();
	}
	

	
}
?>