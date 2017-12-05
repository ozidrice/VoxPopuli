<?php 
class apiController extends Controller{

	private $jsonTxt;

	function __construct(){
		parent::__construct(new Model(), new View());
	}
	
	function run_action($action){
		header('Content-Type: application/json');
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
					print_r("[ERROR] The \"pseudo\" param have to be different of null");
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

				/*
				* ETAPE 1
				*/
				case 'join_game':
				if($etatPartieCourant->idEtat === 1){
					$vie = new Vie(array(
						"idPartie"=>$idPartieCourante,
						"idJoueur"=>$_GET["idJoueur"],
						"vie"=>3
						));
					if($this->model->createVie($vie)){
						$this->jsonTxt = "OK";
					}
				}else{
					print_r("[ERROR] Impossible action at step [$etatPartieCourant->nom]"); 
				}
				break;
				
				/*
				* ETAPE 2
				*/
				case 'vote':
				if($etatPartieCourant->idEtat === 2){
					$vote = new Vote(array(
						"idPartie"=>$idPartieCourante,
						"idQuestion"=>$this->model->get_questionCourante($idPartieCourante)["question"]->idQuestion,
						"idReponse"=>$_GET["idReponse"],
						"idJoueur"=>$_GET["idJoueur"],
						));
					if($this->model->set_vote($vote)){
						$this->jsonTxt = "OK";
					}
				}else{
					print_r("[ERROR] Impossible action at step [$etatPartieCourant->nom]"); 
				}
				break;
				case 'get_current_question':
				if($etatPartieCourant->idEtat === 2){
					$this->jsonTxt = json_encode($this->model->get_questionCourante($idPartieCourante));
				}
				break;
				case 'get_who_as_voted':
				if($etatPartieCourant->idEtat === 2){
					$this->jsonTxt = json_encode($this->model->get_who_as_voted($idPartieCourante,$this->model->get_questionCourante($idPartieCourante)["question"]->idQuestion));
				}else{
					print_r("[ERROR] Impossible action at step [$etatPartieCourant->nom]"); 
				}
				break;


				/*
				* ETAPE 3
				*/
				case 'get_votes_current_question':
				if($etatPartieCourant->idEtat === 3){
					$this->jsonTxt = json_encode($this->model->get_vote_question_joueurs($idPartieCourante,$this->model->get_questionCourante($idPartieCourante)["question"]->idQuestion));
				}else{
					print_r("[ERROR] Impossible action at step [$etatPartieCourant->nom]"); 
				}
				break;
				case 'get_nb_votes_reponses':
				if($etatPartieCourant->idEtat === 3){
					$this->jsonTxt = json_encode($this->model->get_nb_vote_question($idPartieCourante,$this->model->get_questionCourante($idPartieCourante)["question"]->idQuestion));
				}else{
					print_r("[ERROR] Impossible action at step [$etatPartieCourant->nom]"); 
				}
				break;

				default:
				print_r("[ERROR] Invalid action");
				break;
			}
		}else{
			print_r("[ERROR] Invalid token");
		}
		echo $this->jsonTxt;
		exit();
	}

}
?>