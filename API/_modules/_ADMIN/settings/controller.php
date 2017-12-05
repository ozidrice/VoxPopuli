<?php 
class settingsController extends Controller{
	
	function __construct(){
		parent::__construct(new Model(), new settingsView());
	}
	
	function run_action($action){
		if($action==""){
			if(sizeof($_POST) == 0){
				$this->page_settings();
			}else{
				$this->update_param_site();
			}
			return true;
		}
		return false;
	}
	
	private function page_settings(){
		require_once("_model/usermodel.php");
		$this->view->data["liste_user_type"] = (new userModel)->get_user_type();
		$this->view->set_titre("Paramètres");
		$this->view->set_template("settings");
		$this->view->data["fil_ariane"] = array("/admin/"=>"home","#"=>"Paramètres");
	}
	
	private function update_param_site(){
		if($this->model->set_param_site($this->get_listdata_param())){
			$this->view->set_success("La modification a bien été prise en compte.");
			$this->page_settings();
		}else{
			$this->view->set_error("La modification a échouée, veillez réessayer.");
			$this->view->affiche_page_settings($_POST);
		}
	}
	
	private function get_listdata_param(){
		$listdata = array();
		foreach($_POST as $param => $value){
			$listdata[$param] = $value;
		}
		return $listdata;
	}
}
?>