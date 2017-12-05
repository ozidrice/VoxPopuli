<?php 
class pagesController extends Controller{
	
	function __construct(){
		parent::__construct(new pagesModel(), new View());
	}
	
	function run_action($action){
		if($action==""){
			$this->page_pages();
			return true;
		}elseif($action == "ajoutpage"){
			if(sizeof($_POST) == 0){
				$this->page_add_page(new PageDynamique());
			}else{
				$page = new PageDynamique($_POST);
				if($this->model->add_page($page)){
					$this->view->set_success("La page a bien été ajoutée");
					$this->page_pages();
				}else{
					$this->view->set_error("Une erreur est survenue");
					$this->page_add_page($page);
				}
			}
			return true;
		}elseif($action == "editpage"){
			if($page = $this->model->get_page(urldecode($_GET["3"]))){
				if($page == NULL)
					return false;
				if(sizeof($_POST) == 0){
					$this->page_edit_page($page);
				}else{
					$page = $this->model->get_page(urldecode($_GET["3"]));
					$page->set_titre($_POST["titre"]);
					$page->set_meta($_POST["meta"]);
					$page->set_contenu($_POST["contenu"]);
					if($this->model->add_page($page)){
						$this->view->set_success("La page a bien été modifié");
					}else{
						$this->view->set_error("Une erreur est survenue");
					}
					$page = $this->model->get_page(urldecode($_GET["3"]));
					$this->page_edit_page($page);
				}
				return true;
			}
		}
		return false;
	}

	private function page_add_page($page){
		$this->view->set_titre("Ajouter une page");
		$this->view->set_template("pages_form");
		$this->view->data["fil_ariane"] = array("/admin/"=>"home","/admin/pages/"=>"Pages","#"=>"Ajouter une page");
		$this->view->data["page"] = $page;
		$this->view->data["allow_edit_slug"]=true;
		$this->view->add_js("/ckeditor/ckeditor.js");
	}

	private function page_edit_page($page){
		$this->view->set_titre("Modifier une page");
		$this->view->set_template("pages_form");
		$this->view->data["fil_ariane"] = array("/admin/"=>"home","/admin/pages/"=>"Pages","#"=>"Modifier une page");
		$this->view->data["page"] = $page;
		$this->view->data["allow_edit_slug"]=false;
		$this->view->add_js("/ckeditor/ckeditor.js");

	}

	
	private function page_pages(){
		$this->view->data["liste_page"] = $this->model->get_liste_page();
		$this->view->set_template("pages");
		$this->view->set_titre("Pages");
		$this->view->data["fil_ariane"] = array("/admin/"=>"home","#"=>"Pages");

		// $this->view->affiche_page_pages($data);
	}
}
?>