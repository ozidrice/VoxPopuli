<?php 
require_once("_modules/contenucontroller.php");
class articlesController extends contenuController{
	
	function __construct(){
		parent::__construct();
	}
	function run_action($action){
		if($action==""){
			$this->page_articles();
			return true;
		}elseif($action == "ajout"){
			$this->view->add_js("/ckeditor/ckeditor.js");
			$this->view->data["fil_ariane"] = array("/admin/"=>"home","/admin/articles/"=>"Articles","#"=>"Créer un article");
			$this->view->set_template("articles_form");
			$this->view->data["list_etat"] = $this->model->get_contenu_etat($_POST["etat"]);

			if(sizeof($_POST) != 0){
				$contenu = new Contenu();
				$contenu->set_titre($_POST["titre"]);
				$contenu->set_description($_POST["description"]);
				$contenu->set_metakeywords($_POST["metakeywords"]);
				$contenu->set_contenu($_POST["contenu"]);

				$etat = $this->model->get_contenu_etat($_POST["etat"]);
				$contenu->set_etat($etat);
				$contenu->set_id_user($_SESSION["user"]->get_iduser());
				$contenu->set_typeContenu(3);
				if($this->model->add_contenu($contenu)){
					$this->view->set_success("L'article a bien été créé'");
					$this->page_articles();
				}else{
					$this->view->set_error("Une erreur est survenue");
				}
			}
			return true;

		}elseif($action == "edit"){
			$this->view->add_js("/ckeditor/ckeditor.js");
			$this->view->data["fil_ariane"] = array("/admin/"=>"home","/admin/articles/"=>"Articles","#"=>"Modifier un article");
			$this->view->set_template("articles_form");
			
			$recherche = array();
			$recherche["idContenu"] = $_GET[3];
			$recherche["typeContenu"] = $this->typeContenu;
			if($this->view->data["article"] = $this->model->get_contenu($recherche)[0]){
				if(sizeof($_POST) != 0){
					$this->view->data["article"]->set_titre($_POST["titre"]);
					$this->view->data["article"]->set_description($_POST["description"]);
					$this->view->data["article"]->set_metakeywords($_POST["metakeywords"]);
					$this->view->data["article"]->set_contenu($_POST["contenu"]);

					$etat = $this->model->get_contenu_etat($_POST["etat"]);
					$this->view->data["article"]->set_etat($etat);
					if($this->model->add_contenu($this->view->data["article"])){
						$this->view->set_success("Les modifications ont bien été prises en compte");
						$this->page_articles();
					}
					return true;
				}else{
					$this->view->data["list_etat"] = $this->model->get_contenu_etat($_POST["etat"]);
					$this->view->set_titre("Editer un article");
					return true;
				}
			}
		}
		return false;
	}

	function page_articles(){
		$this->view->data["fil_ariane"] = array("/admin/"=>"home","#"=>"Articles");
		$this->view->set_template("articles");
		$this->view->set_titre("Articles");

		$recherche = array();
		$recherche["typeContenu"] = 3;
		$recherche["not_deleted"] = true;
		$recherche["order_by"] = "contenu.date_ins DESC";
		$recherche["etat"] = $_GET["etat"];
		$this->view->data["list_article"] = $this->model->get_contenu($recherche); 
	}
	

}
?>