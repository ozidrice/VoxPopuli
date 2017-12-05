<?php 
class userController extends Controller{
	
	function __construct(){
		parent::__construct(new userModel(), new View());
	}
	
	function run_action($action){
		if($action==""){
			$this->page_user();
			return true;
		}elseif($action == 'type'){
			if(sizeof($_POST) == 0){
				$this->page_type_user();
			}else{
				$this->modif_type_user();
			}
			return true;
		}else{
			if(sizeof($_POST) == 0){
				$this->page_edit_user();
			}else {
				$this->modif_user();
			}
			return true;
		}
		return false;
	}
	
	private function page_user(){
		$this->view->set_template("user");
		$this->view->set_titre("Utilisateurs");
		$this->view->data["fil_ariane"] = array("/admin/"=>"home","#"=>"Liste des utilisateurs");
		$recherche = array();
		$recherche["search"] = $_GET["search"];
		$this->view->data["liste_user"] = $this->model->get_liste_user($recherche);
		// $this->view->affiche_page_user($data);
	}

	private function page_edit_user(){
		$id_user = $_GET[2];
		$this->view->data["user"] = $this->model->get_user($id_user);
		$this->view->data["liste_user_type"] = $this->model->get_user_type();
		if($this->view->data["user"]->is_null()){
			$this->view->set_error("Utilisateur introuvable");
		}else{
			$this->view->set_template("user_form");
			$this->view->set_titre("Modifier un utilisateur");
			$this->view->data["fil_ariane"] = array("/admin/"=>"home","/admin/user/"=>"Liste des utilisateurs","#"=>"Modifier un utilisateur");
		}
	}

	private function modif_user(){
		$user = new User($_POST);
		$user->set_typeuser($this->model->get_user_type($user->get_typeuser()));
		if($this->model->change_user_type($user) && $this->model->add_user($user) != 0){
			$this->view->set_success("La modification a bien été effectuée");
			$this->page_user();
		}else{
			$this->view->set_error("Une erreur est survenue, veillez reessayer");
			$this->view->data["user"] = $user;
			$this->view->data["liste_user_type"] = $this->model->get_user_type();
			$this->view->set_template("user_form");
			$this->view->set_titre("Modifier un utilisateur");
			$this->view->data["fil_ariane"] = array("/admin/"=>"home","/admin/user/"=>"Liste des utilisateurs","#"=>"Modifier un utilisateur");
		}
	}

	private function page_type_user(){
		$this->view->set_template("user_types");
		$this->view->set_titre("Type d'utilisateur");
		$this->view->data["fil_ariane"] = array("/admin/"=>"home","/admin/user/"=>"Utilisateurs","#"=>"Type d'utilisateur");
		$this->view->data["liste_user_type"] = $this->model->get_user_type();
	}

	private function modif_type_user(){
		$data = array();
		$erreur = false;	
		foreach ($_POST["intitule"] as $id_type_user => $intitule) {
			if($this->model->add_user_type(new UserType(array(
				"idType"=>$id_type_user,
				"intitule"=>$intitule ))) == 0){
				$erreur = true;
			}
		}
		if(!$erreur){
			$this->view->set_success("Les modifications ont bien été prises en compte");
		}else{
			$this->view->set_error("Une erreur est survenue, veillez reessayer");
		}
		$this->page_type_user();


	}
}
?>