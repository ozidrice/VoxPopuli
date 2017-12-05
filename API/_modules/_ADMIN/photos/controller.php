<?php 
require_once("_modules/contenucontroller.php");
class photosController extends contenuController{
	public $typeContenu;

	function __construct(){
		parent::__construct();
	}
	
	function run_action($action){
		if($action==""){
			$this->page_photos();
			return true;
		}elseif($action == "edit"){
			$recherche = array();
			$recherche["idContenu"] = $_GET[3];
			$recherche["typeContenu"] = $this->typeContenu;
			if($this->view->data["photo"] = $this->model->get_contenu($recherche)[0]){
				if(sizeof($_POST) != 0){
					$this->view->data["photo"]->set_titre($_POST["titre"]);
					$this->view->data["photo"]->set_description($_POST["description"]);
					$this->view->data["photo"]->set_metakeywords($_POST["metakeywords"]);
					$etat = $this->model->get_contenu_etat($_POST["etat"]);
					$this->view->data["photo"]->set_etat($etat);
					if($this->model->add_contenu($this->view->data["photo"])){
						$this->view->set_success("Les modifications ont bien été prises en compte");
						$this->page_photos();
					}
					return true;
				}else{
					$this->view->data["list_etat"] = $this->model->get_contenu_etat($_POST["etat"]);
					$this->view->set_template("photos_form");
					$this->view->set_titre("Editer une photo");
					$folder = $this->model->get_param_site()["theme"]."/img/photos/".$this->view->data["photo"]->get_id_user()."/original/";
					$file = $folder.$this->view->data["photo"]->get_contenu();
					if($fp = fopen($file,"rb", 0)){
					   	$picture = fread($fp,filesize($file));
					   	fclose($fp);
					   	$this->view->data["img"] = 'data:image/gif;base64,'.chunk_split(base64_encode($picture));
						return true;
					}
				}
			}
		}
		return false;
	}

	function page_photos(){
		$this->view->data["fil_ariane"] = array("/admin/"=>"home","#"=>"Photos");
		$this->view->set_template("photos");
		$this->view->set_titre("Photos");
		$this->view->data["list_etat"] = $this->model->get_contenu_etat($_POST["etat"]);

		$recherche = array();
		$recherche["typeContenu"] = $this->typeContenu;
		$recherche["not_deleted"] = true;
		$recherche["order_by"] = "contenu.date_ins DESC";
		$recherche["etat"] = $_GET["etat"];
		$this->view->data["list_photo"] = $this->model->get_contenu($recherche); 
	}
	

}
?>