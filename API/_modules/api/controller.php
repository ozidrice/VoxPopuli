<?php 
require_once("cron.php");
class apiController extends Controller{
	private $jsonTxt;
	private $jsonErr;
	static $cron;
	function __construct(){
		parent::__construct(new Model(), new View());
	}
	
	function run_action($action){
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *'); 
		$token = $this->model->get_token($_GET["token"]);
		$partieCourante = $this->model->get_partie_courante();
		$idPartieCourante = $partieCourante->idPartie;	
		$etatPartieCourant = $partieCourante->etat;
		if($token->nom != ""){
			switch ($action) {
				case 'create_user':
				if($_GET["pseudo"] != ""){
					$idJoueur = $this->model->ajout_joueur($_GET["pseudo"]);
					$this->jsonTxt = json_encode($this->model->get_joueur($idJoueur));
				}else{
					$this->jsonErr = json_encode("[ERROR] The \"pseudo\" param have to be different of null");
				}
				break;
				case 'get_current_game':
				$this->jsonTxt = json_encode($this->model->get_partie_courante());
				break;	
				case 'get_status_current_game':
				$this->jsonTxt = json_encode($this->model->get_partie_courante()->etat);
				break;	
				case 'list_user':
				$this->jsonTxt = json_encode($this->model->get_liste_joueur_partie($idPartieCourante));
				break;
				case 'get_time_left':
					$this->jsonTxt = json_encode(Cron::get_time_left());
					break;
				case 'cron':
					Cron::launch();
					break;
				/*
				* ETAPE 1
				*/
				case 'join_game':
				if($etatPartieCourant->idEtat == 1){
					$vie = new Vie(array(
						"idPartie"=>$idPartieCourante,
						"idJoueur"=>$_GET["idJoueur"],
						"vie"=>3
						));
					if($this->model->createVie($vie)){
						$this->jsonTxt = "OK";
					}
				}else{
					$this->jsonErr = json_encode("[ERROR] Impossible action at step [$etatPartieCourant->nom]"); 
				}
				break;
				
				/*
				* ETAPE 2
				*/
				case 'vote':
				if($etatPartieCourant->idEtat == 2){
					$vote = new Vote(array(
						"idPartie"=>$idPartieCourante,
						"tour"=>$partieCourante->tourCourant,
						"idQuestion"=>$this->model->get_questionCourante($idPartieCourante)["question"]->idQuestion,
						"idReponse"=>$_GET["idReponse"],
						"idJoueur"=>$_GET["idJoueur"],
						));
					if($this->model->set_vote($vote)){
						$this->jsonTxt = "OK";
					}
				}else{
					$this->jsonErr = json_encode("[ERROR] Impossible action at step [$etatPartieCourant->nom]"); 
				}
				break;
				case 'get_current_question':
				if($etatPartieCourant->idEtat == 2 || $etatPartieCourant->idEtat == 3){
					$this->jsonTxt = json_encode($this->model->get_questionCourante($idPartieCourante));
				}else{
					$this->jsonErr = json_encode("[ERROR] Impossible action at step [$etatPartieCourant->nom]"); 
				}
				break;
				case 'get_who_as_voted':
				if($etatPartieCourant->idEtat ==2){
					$this->jsonTxt = json_encode($this->model->get_who_as_voted($idPartieCourante,$this->model->get_questionCourante($idPartieCourante)["question"]->idQuestion,$partieCourante->tourCourant));
				}else{
					$this->jsonErr = json_encode("[ERROR] Impossible action at step [$etatPartieCourant->nom]"); 
				}
				break;
				/*
				* ETAPE 3
				*/
				case 'get_votes_current_question':
				if($etatPartieCourant->idEtat ==3){
					$this->jsonTxt = json_encode($this->model->get_vote_question_joueurs($idPartieCourante,$this->model->get_questionCourante($idPartieCourante)["question"]->idQuestion,$partieCourante->tourCourant));
				}else{
					$this->jsonErr = json_encode("[ERROR] Impossible action at step [$etatPartieCourant->nom]"); 
				}
				break;
				/*NB DE VOTES POUR CHAQUE REPONSE*/
				case 'get_nb_votes_reponses':
				if($etatPartieCourant->idEtat ==3){
					$this->jsonTxt = json_encode($this->model->get_nb_vote_question($idPartieCourante,$this->model->get_questionCourante($idPartieCourante)["question"]->idQuestion,$partieCourante->tourCourant));
				}else{
					$this->jsonErr = json_encode("[ERROR] Impossible action at step [$etatPartieCourant->nom]"); 
				}
				break;
				case 'get_reponses_gagnante':
					if($etatPartieCourant->idEtat ==3){
						$this->jsonTxt = json_encode($this->model->get_reponses_gagnante($idPartieCourante,$this->model->get_questionCourante($idPartieCourante)["question"]->idQuestion,$partieCourante->tourCourant));
					}else{
						$this->jsonErr = json_encode("[ERROR] Impossible action at step [$etatPartieCourant->nom]"); 
					}
					break;

				/*
				* ETAPE 4
				*/
				case 'get_winners':
				if($etatPartieCourant->idEtat == 4){
					$this->jsonTxt = json_encode($this->model->getJoueurVivants($idPartieCourante));
				}else{
					$this->jsonErr = json_encode("[ERROR] Impossible action at step [$etatPartieCourant->nom]"); 
				}
				break;
				default:
				$this->jsonErr = json_encode("[ERROR] Invalid action");
				break;
			}
		}else{
			$this->jsonErr = json_encode("[ERROR] Invalid token");
		}
		if($this->jsonErr == ""){
			echo $this->jsonTxt;
		}else{
			echo $this->jsonErr;
		}
		exit();
	}
}
?>