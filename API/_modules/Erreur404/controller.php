<?php 
class Erreur404Controller extends Controller{
	
	function __construct(){
		parent::__construct(new Model(), new View());
	}
	
	function run_action($action){
		header("HTTP/1.0 404 Not Found");
		$this->page_404();
		return true;
	}
	
	private function page_404(){
		$this->view->set_titre("Erreur 404");
		$this->view->set_template("general_404");
	}
}
?>