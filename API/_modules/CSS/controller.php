<?php 
class CSSController extends Controller{
	private $listURL = array();

	function __construct(){
		parent::__construct(new Model(), new CSSView());
	}
	
	function run_action($action){
		if($action == "style"){
			$this->view->style();
			return true;
		}elseif($action == "galerie_responsive"){
			$this->view->galerie_responsive();
			return true;
		}elseif($action == "galerie"){
			$this->view->galerie();
			return true;
		}elseif($action == "front_responsive"){
			$this->view->front_responsive();
			return true;
		}elseif($action == "front"){
			$this->view->front();
			return true;
		}elseif($action == "connexion"){
			$this->view->connexion();
			return true;
		}elseif($action == "back"){
			$this->view->back();
			return true;
		}elseif($action == "back_responsive"){
			$this->view->back_responsive();
			return true;
		}

	}
	
	private function sitemap(){
		$this->ajouterElement("/", 1, "monthly");
		/* CV */
		$this->ajouterElement("/cv/", 0.8, "monthly");
		/* GALERIE */
		$this->ajouterElement("/galerie/", 0.9, "monthly");
		
		include_once("_model/galeriemodel.php");
		$modelgalerie = new galerieModel();
		
		$listCatMere = $modelgalerie->get_list_categorie();
		$this->getCategorieFille($listCatMere);
		
		$listImage = $modelgalerie->get_list_img();
		foreach ($listImage as $photo) {
			$this->ajouterElement("/galerie/image/?idImage=".$photo["idImage"], 0.7, "weekly");
		}

		$this->view->afficheSitemap($this->listURL);
	}
	private function ajouterElement($loc,$priority="",$changefreq=""){
		$this->listURL[] = array('loc' => "https://".$_SERVER["HTTP_HOST"].$loc, 'priority' => $priority, 'changefreq' => $changefreq);
	}

	private function getCategorieFille($list_categorie){
		foreach ($list_categorie as $catMere) {
			$this->ajouterElement("/galerie/".$catMere->slug."/", 0.8, "weekly");
			$this->getCategorieFille($catMere->get_listCategoriesFille());
		}
	}
}
?>