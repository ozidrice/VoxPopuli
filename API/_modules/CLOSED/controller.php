<?php 
class CLOSEDController extends Controller{
	
	function __construct(){
		parent::__construct(new Model(), new View());
	}
	
	function run_action($action){
		if($action==""){
			$this->page_CLOSED();
			return true;
		}
		return false;
	}
	
	private function page_CLOSED(){
		$this->view->set_template("general_en_travaux");	
	}
}
?>