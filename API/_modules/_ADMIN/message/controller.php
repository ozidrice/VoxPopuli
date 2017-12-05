<?php 
class messageController extends Controller{
	
	function __construct(){
		parent::__construct(new messageModel(), new View());
	}
	
	function run_action($action){
		if($action == ""){
			$this->view->set_titre("Contact");
			$this->view->set_template("message");
			$this->view->data["liste_etat"] = $this->model->get_message_etat();
			$recherche = array();
			$recherche["order_by"] = "date_ins DESC";
			$this->view->data["liste_messages"] = $this->model->get_message($recherche);
			return true;
		}
		return false;
	}
}
?>