<?php 
class statsController extends Controller{
	
	function __construct(){
		parent::__construct(new statsModel(), new View());
	}
	
	function run_action($action){
		if($action==""){
			$this->view->set_titre("Statistiques");
			$this->page_stats();
			return true;
		}
		return false;
	}
	
	private function page_stats(){
		$data = array(
		"nbPagesVues"=>$this->model->get_nbPagesVues_dernier_mois(),
		"nbVisiteurs"=>$this->model->get_nbVisiteurs_dernier_mois()
		);

		$this->view->data["dataNbPage"] = "";
		for($i=30; $i>=0;$i--){
			$date = strtotime(date("Y-m-d", strtotime("-".$i." day")));
			$jNbPage = 0;
			while(strtotime($data["nbPagesVues"][$jNbPage]["date"]) != $date && $jNbPage <= sizeof($data["nbPagesVues"])){$jNbPage++;}
			
			$nbPagesVues = 0;
			if(isset($data["nbPagesVues"][$jNbPage]["nbPagesVues"]))
				$nbPagesVues = $data["nbPagesVues"][$jNbPage]["nbPagesVues"];
			
			$this->view->data["dataNbPage"] .= "['".date("d/m",$date)."',".$nbPagesVues."]";
			if($i-1 >= 0)
				$this->view->data["dataNbPage"] .= ",
			";
		}

		$this->view->data["dataNbVisiteur"] = "";
		for($i=30; $i>=0;$i--){
			$date = strtotime(date("Y-m-d", strtotime("-".$i." day")));
			$jNbVisiteurs = 0;
			while(strtotime($data["nbVisiteurs"][$jNbVisiteurs]["date"]) != $date && $jNbVisiteurs <= sizeof($data["nbVisiteurs"])){$jNbVisiteurs++;}
			
			$nbVisiteurs = 0;
			if(isset($data["nbVisiteurs"][$jNbVisiteurs]["nbVisiteurs"]))
				$nbVisiteurs = $data["nbVisiteurs"][$jNbVisiteurs]["nbVisiteurs"];
			
			$this->view->data["dataNbVisiteur"] .= "['".date("d/m",$date)."',".$nbVisiteurs."]";
			if($i-1 >= 0)
				$this->view->data["dataNbVisiteur"] .= ",
			";
		}


		require_once("_model/usermodel.php");
		$model_user = new userModel();
		$this->view->data["nb_user"] = $model_user->get_nb_user();
		$this->view->data["nb_user_30_last_days"] = $model_user->get_nb_user("-30days");

		$this->view->add_js("https://www.gstatic.com/charts/loader.js");
		$this->view->set_template("stats");
		// $this->view->affiche_page_stats($data);
	}
}
?>