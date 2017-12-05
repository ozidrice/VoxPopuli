<?php 
class SITEMAPController extends Controller{
	private $listURL = array();

	function __construct(){
		parent::__construct(new Model(), new View());
	}
	
	function run_action($action){
		$this->sitemap();
		return true;
	}
	
	private function sitemap(){
		$this->ajouterElement("/", 1, "monthly");
		$this->ajouterElement("/photos/", 1, "monthly");
		$this->ajouterElement("/videos/", 1, "monthly");
		
		require_once("_model/usermodel.php");
		$userModel = new userModel();
		$liste_user = $userModel->get_liste_user();
		foreach ($liste_user as $user) {
			$this->ajouterElement("/user/".$user->get_slug(), 0.8, "weekly");
		};

		$this->ajouterElement("/compte/", 0.6, "monthly");
		$this->ajouterElement("/connexion/", 0.6, "monthly");
		$this->ajouterElement("/inscription/", 0.6, "monthly");
		
		header('Content-type: text/xml');
		$this->view->data["liste_url"] = $this->listURL;
		$this->view->set_template("sitemap"); 
	}
	private function ajouterElement($loc,$priority="",$changefreq=""){
		$this->listURL[] = array('loc' => "https://".$_SERVER["HTTP_HOST"].$loc, 'priority' => $priority, 'changefreq' => $changefreq);
	}

	private function getCategorieFille($list_categorie,$linkparent=""){
		foreach ($list_categorie as $catMere) {
			$this->ajouterElement("/galerie/".$linkparent.$catMere->slug."/", 0.8, "weekly");
			$this->getCategorieFille($catMere->get_listCategoriesFille(), $linkparent.$catMere->slug."/");
		}
	}
}
?>