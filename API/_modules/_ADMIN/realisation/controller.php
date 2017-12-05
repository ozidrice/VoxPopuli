<?php 

require_once("_model/contenumodel.php");
require_once("_modules/_ADMIN/contenu/controller.php");
class realisationController extends contenuController{
	
	function __construct(){
		parent::__construct(new realisationView());
	}
	
	function run_action($action){
		if($action == ""){
			$this->view->affiche_page_real(array());
			return true;
		}elseif($action == "ajout"){
			if(sizeof($_POST) == 0){
				$this->view->affiche_page_ajout(array());
			}else{
				$_POST["typeContenu"] = "realisation";
				$this->ajouterContenu();
			}
			return true;
		}elseif($action == "edit"){
			if(sizeof($_POST) == 0){
				$this->view->affiche_page_edit(array());
			}else{
				$_POST["typeContenu"] = "realisation";
				$this->edit_contenu();
			}
			return true;
		}

		return false;
	}
	
	
}
?>