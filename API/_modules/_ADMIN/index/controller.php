<?php 
class indexController extends Controller{
	
	function __construct(){
		parent::__construct(new indexModel(), new indexView());
	}
	
	function run_action($action){
		if($action==""){
			$this->page_index();
			return true;
		}
		return false;
	}
	
	private function page_index(){
		$this->view->affiche_page_index();
	}
}
?>