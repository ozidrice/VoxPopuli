<?php 
require_once("_model/commandemodel.php");
class commandeController extends Controller{
	
	function __construct(){
		parent::__construct(new commandeModel(), new View());
	}
	
	function run_action($action){
		if($action==""){	
			$this->view->set_template("commande");
			$this->view->set_titre("Commandes");

			$recherche = array();
			$recherche["order_by"] = "date DESC";
			$this->view->data["liste_commande"] = $this->model->get_commande($recherche);
			$this->view->data["nb_commande"] = $this->model->get_commande($recherche,true);
			return true;
		}
		return false;
	}
	
}
?>