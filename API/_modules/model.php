<?php
include("__class/Question.php");
include("__class/Joueur.php");
include("__class/Partie.php");
include("__class/PartieEtat.php");
include("__class/Reponse.php");
include("__class/Vie.php");
include("__class/Vote.php");
include("__class/Token.php");
class Model{
	protected $conn;
	public $root;
	function __construct(){
		require("__params/bd.php");
		$this->conn = new PDO("mysql:host=".$server.";dbname=".$bdd, $user, $pass);
		$this->root =  substr(__dir__,0,-8);
	}
	
	function get_param_site(){
		$statement = $this->conn->prepare("SELECT * FROM paramSite");
		$statement->execute();
		
		$params = array();
		while( $result = $statement->fetch(PDO::FETCH_ASSOC)){
			$params[$result["param"]] = $result["value"];
		}
		return $params;
	}

	/*________________________
	*
	*	TOKEN
	* ________________________
	*/
	function get_token($token){
		$statement = $this->conn->prepare("SELECT * FROM VP_token WHERE token = ?");
		$statement->execute(array($token));
		return new Token($statement->fetch(PDO::FETCH_ASSOC));
	}



	/*________________________
	*
	*	PARTIE
	* ________________________
	*/
	function creerNouvellePartie(){
		$statement = $this->conn->prepare("INSERT INTO VP_Partie () values()");
		$statement->execute(); 
	}

	function get_partie_courante(){
		$statement = $this->conn->prepare("SELECT * FROM VP_Partie ORDER BY date DESC LIMIT 1");
		$statement->execute(); 
		$partie = new Partie($statement->fetch(PDO::FETCH_ASSOC));
		$partie->etat = $this->get_partie_etat($partie->etat);
		return $partie;
	}

	function get_partie_etat($idetat){
		$statement = $this->conn->prepare("SELECT * FROM VP_PartieEtat WHERE idEtat = ?");
		$statement->execute(array($idetat)); 
		return new PartieEtat($statement->fetch(PDO::FETCH_ASSOC));
	}

	function set_partie_etat($idPartie,$idEtat){
		$statement = $this->conn->prepare("UPDATE VP_Partie SET etat = :idEtat WHERE 	idPartie = :idPartie");
		return $statement->execute(array(
			":idPartie"=>$idPartie,
			":idEtat"=>$idEtat,
			)); 
	}

	function get_liste_joueur_partie($idPartie){
		$statement = $this->conn->prepare("SELECT idJoueur,Vie,pseudo FROM VP_Vies INNER JOIN VP_Joueur USING(idJoueur) WHERE idPartie = ?");
		$statement->execute(array($idPartie));
		$liste_joueurs = array();
		foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $value) {
			$liste_joueurs[] = new Joueur($value);
		}
		return $liste_joueurs;
	}

	function more_thant_two_player_alive($idPartie){
		$statement = $this->conn->prepare("SELECT count(*) as nb_alive FROM VP_Vies WHERE idPartie = ? AND vie > 0");
		$statement->execute(array($idPartie));
		return $statement->fetch(PDO::FETCH_ASSOC)["nb_alive"] > 2;
	}


	function next_tour($idPartie){
		$statement = $this->conn->prepare("UPDATE VP_Partie SET tourCourant = tourCourant+1 WHERE idPartie = ?");
		return $statement->execute(array($idPartie)); 
	}



	/*________________________
	*
	*	QUESTION
	* ________________________
	*/
	function get_question($idQuestion = ""){
		if($idQuestion == ""){
			$statement = $this->conn->prepare("SELECT * FROM VP_Question");
			$statement->execute();
			$list_question = array(); 
			foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $value) {
				$list_question[] = new Question($value);
			}
			return $list_question;
		}

		$statement = $this->conn->prepare("SELECT * FROM VP_Question where idQuestion = ?");
		$statement->execute(array($idQuestion));
		return new Question($statement->fetch(PDO::FETCH_ASSOC));

	}

	function set_nouvelle_question($idPartie, $tour, $idQuestion, $idReponse_1, $idReponse_2, $idReponse_3){
		$statement = $this->conn->prepare("INSERT INTO VP_QuestionPartie(idPartie,tour,idQuestion,idReponse_1,idReponse_2,idReponse_3) VALUES (:idPartie, :tour,:idQuestion,:idReponse_1,:idReponse_2,:idReponse_3)");
		return $statement->execute(array(
			":idPartie"=>$idPartie,
			":idQuestion"=>$idQuestion,
			":tour"=>$tour,
			":idReponse_1"=>$idReponse_1,
			":idReponse_2"=>$idReponse_2,
			":idReponse_3"=>$idReponse_3,
			));
	}

	function get_questionCourante($idPartie){
		$statement = $this->conn->prepare("SELECT * FROM VP_QuestionPartie WHERE idPartie = ? ORDER BY tour DESC LIMIT 1");
		$statement->execute(array($idPartie));
		$data = $statement->fetch(PDO::FETCH_ASSOC);
		
		$question = $this->get_question($data["idQuestion"]);
		$list_reponse = array($this->get_reponse($data["idReponse_1"]),$this->get_reponse($data["idReponse_2"]),$this->get_reponse($data["idReponse_3"]));
		return array("question"=>$question,"list_reponse"=>$list_reponse);
	}

	/*________________________
	*
	*	REPONSE
	* ________________________
	*/
	function get_reponse($idReponse = ""){
		if($idReponse == ""){
			$statement = $this->conn->prepare("SELECT * FROM VP_Reponse");
			$statement->execute();
			$list_reponse = array(); 
			foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $value) {
				$list_reponse[] = new Reponse($value);
			}
			return $list_reponse;
		}

		$statement = $this->conn->prepare("SELECT * FROM VP_Reponse WHERE idReponse = ?");
		$statement->execute(array($idReponse));
		return new Reponse($statement->fetch(PDO::FETCH_ASSOC));
	}



	/*________________________
	*
	*	VIE
	* ________________________
	*/
	function getVieJoueurs($idPartie){
		$statement = $this->conn->prepare("SELECT * FROM VP_Vies WHERE idPartie = ?");
		$statement->execute(array($idPartie));
		$liste_vie = array();

		foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $value) {
			$liste_vie[] = new Vie($value);
		}
		return $liste_vie;
	}

	function createVie($vie){
		$statement = $this->conn->prepare("INSERT INTO VP_Vies(idPartie,idJoueur,vie) VALUES (:idPartie,:idJoueur,:vie)");
		return $statement->execute(array(
			":idPartie"=>$vie->idPartie,
			":idJoueur"=>$vie->idJoueur,
			":vie"=>$vie->vie,
			));
	}

	function retirerVie($vie, $value){
		if($value < 0)
			return false;
		$statement = $this->conn->prepare("UPDATE VP_Vies SET vie = vie - :val WHERE idPartie = :idPartie AND idJoueur = :idJoueur");
		return $statement->execute(array(
			":idPartie"=>$vie->idPartie,
			":idJoueur"=>$vie->idJoueur,
			":val"=>$value,
			));
	}

	function getJoueurVivants($idPartie){
		$statement = $this->conn->prepare("SELECT idJoueur,pseudo,Vie FROM VP_Vies INNER JOIN VP_Joueur USING (idJoueur) WHERE idPartie = ? AND Vie > 0");
		$statement->execute(array($idPartie));
		$liste_vie = array();

		foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $value) {
			$liste_vie[] = new Joueur($value);
		}
		return $liste_vie;
	}


	/*________________________
	*
	*	VOTE
	* ________________________
	*/
	function set_vote($vote){
		$statement = $this->conn->prepare("INSERT INTO VP_Votes (idPartie,tour,idQuestion,idReponse,idJoueur) values(:idPartie,:tour,:idQuestion,:idReponse,:idJoueur) 
			ON DUPLICATE KEY UPDATE 
			idReponse = :idReponse");
		return $statement->execute(array(
			":idPartie"=>$vote->idPartie,
			":tour"=>$vote->tour,
			":idQuestion"=>$vote->idQuestion,
			":idReponse"=>$vote->idReponse,
			":idJoueur"=>$vote->idJoueur,
			));
		
	}

	function get_liste_votes($idPartie,$idQuestion,$tour){
		$statement = $this->conn->prepare("SELECT * FROM VP_Votes WHERE idPartie = :idPartie AND idQuestion = :idQuestion AND $tour = :tour");
		$statement->execute(array(
			":idPartie"=>$idPartie,
			":idQuestion"=>$idQuestion,
			":tour"=>$tour,
			));
		return new Vote($statement->fetchAll(PDO::FETCH_ASSOC));
	}

	function get_vote($idPartie,$idQuestion,$idJoueur,$tour){
		$statement = $this->conn->prepare("SELECT * FROM VP_Votes WHERE idPartie = :idPartie AND idQuestion = :idQuestion AND idJoueur = :idJoueur AND tour = :tour");
		$statement->execute(array(
			":idPartie"=>$idPartie,
			":idQuestion"=>$idQuestion,
			":idJoueur"=>$idJoueur,
			":tour"=>$tour,
			));
		return new Vote($statement->fetch(PDO::FETCH_ASSOC));
	}

	function get_nb_vote_question($idPartie, $idQuestion, $tour){
		$statement = $this->conn->prepare("SELECT idReponse, count(*) as nbVote FROM VP_Votes WHERE idQuestion = :idQuestion AND idPartie = :idPartie AND tour = :tour GROUP BY idReponse");
		$statement->execute(array(":idQuestion"=>$idQuestion,":idPartie"=>$idPartie,":tour"=>$tour));

		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	function get_reponses_gagnante($idPartie, $idQuestion,$tour){
		$liste_vote_reponse = $this->get_nb_vote_question($idPartie, $idQuestion,$tour);
		$taille = sizeof($liste_vote_reponse);
		if($taille == 0)
			return array();
		if($taille == 1)
			return array($liste_vote_reponse[0]["idReponse"]=>$liste_vote_reponse[0]);

		$curr_max_nb_reponse = $liste_vote_reponse[0]["nbVote"];
		$array = array();
		$array[$liste_vote_reponse[0]["idReponse"]] = $liste_vote_reponse[0];
		
		for ($i=1; $i<sizeof($liste_vote_reponse); $i++) { 
			if($liste_vote_reponse[$i]["nbVote"] > $curr_max_nb_reponse){
				$array = array();
				$array[$liste_vote_reponse[$i]["idReponse"]] = $liste_vote_reponse[$i];
				$curr_max_nb_reponse = $liste_vote_reponse[$i]["nbVote"];
			}elseif($liste_vote_reponse[$i]["nbVote"] == $curr_max_nb_reponse){
				$array[$liste_vote_reponse[$i]["idReponse"]] = $liste_vote_reponse[$i];
			}
		}
		return $array;
	}

	function get_who_as_voted($idPartie, $idQuestion,$tour){
		$statement = $this->conn->prepare("SELECT idJoueur FROM VP_Votes WHERE idQuestion = :idQuestion AND idPartie = :idPartie AND tour = :tour GROUP BY idReponse");
		$statement->execute(array(":idQuestion"=>$idQuestion,":idPartie"=>$idPartie,":tour"=>$tour));
		return $statement->fetch(PDO::FETCH_ASSOC);
	}

	function get_vote_question_joueurs($idPartie, $idQuestion,$tour){
		$statement = $this->conn->prepare("SELECT * FROM VP_Votes WHERE idQuestion = :idQuestion AND idPartie = :idPartie AND tour = :tour");
		$statement->execute(array(":idQuestion"=>$idQuestion,":idPartie"=>$idPartie,":tour"=>$tour));
		$liste_vote = array();
		foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $value) {
			$liste_vote[] = new Vote($value);
		}
		return $liste_vote;
	}

	

	/*________________________
	*
	*	JOUEURS
	* ________________________
	*/
	function get_joueur($idJoueur = ""){
		if($idJoueur == ""){
			$statement = $this->conn->prepare("SELECT * FROM VP_Joueur");
			$statement->execute();
			$liste_joueurs = array();
			foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $value) {
				$liste_joueurs[] = new Joueur($value);
			}
			return $liste_joueurs;
		}
		$statement = $this->conn->prepare("SELECT * FROM VP_Joueur WHERE idJoueur = ?");
		$statement->execute(array($idJoueur));
		return new Joueur($statement->fetch(PDO::FETCH_ASSOC));
	}

	function ajout_joueur($pseudo){
		$statement = $this->conn->prepare("SELECT * FROM VP_Joueur WHERE pseudo = ?");
		$statement->execute(array($pseudo));
		if($user = $statement->fetch(PDO::FETCH_ASSOC)){
			return $user["idJoueur"];
		}
		
		$statement = $this->conn->prepare("INSERT INTO VP_Joueur(pseudo) VALUES (?) ");
		$statement->execute(array($pseudo));
		return $this->conn->lastInsertId();
	}











	
	
	function upload_file($file_id, $folder="", $types="", $chmod = 0704) {
		if(!$_FILES[$file_id]['name']) return array('','No file specified');
		$file_title = $_FILES[$file_id]['name'];
		//Get file extension
		$ext_arr = explode(".",basename($file_title));
		$ext = strtolower($ext_arr[count($ext_arr)-1]); //Get the last extension
		//Not really uniqe - but for all practical reasons, it is
		$uniqer = substr(md5(uniqid(rand(),1)),0,5);
		$file_name = $uniqer . '_' . $file_title;//Get Unique Name
		$all_types = explode(",",strtolower($types));
		if($types) {
			if(in_array($ext,$all_types));
			else {
				$result = "'".$_FILES[$file_id]['name']."' is not a valid file."; //Show error if any.
				return array('',$result);
			}
		}
		//Where the file must be uploaded to
		if($folder) $folder .= '/';//Add a '/' at the end of the folder
		$uploadfile = $folder . $file_name;
		$result = '';
		//Move the file from the stored location to the new location
		mkdir($folder,$chmod,true);
		if (!move_uploaded_file($_FILES[$file_id]['tmp_name'], $uploadfile)) {
			$result = "Cannot upload the file '".$_FILES[$file_id]['name']."'"; //Show error if any.
			if(!file_exists($folder)) {
				$result .= " : Folder don't exist.";
			} elseif(!is_writable($folder)) {
				$result .= " : Folder not writable.";
			} elseif(!is_writable($uploadfile)) {
				$result .= " : File not writable.";
			}
			$file_name = '';
			
		} else {
			if(!$_FILES[$file_id]['size']) { //Check if the file is made
				@unlink($uploadfile);//Delete the Empty file
				$file_name = '';
				$result = "Empty file found - please use a valid file."; //Show the error message
			} else {
				chmod($uploadfile,$chmod);//Make it universally writable.
			}
		}
		return array($file_name,$result);
	}
	
	function resize_image($file, $w, $h, $crop=FALSE) {
		list($width, $height) = getimagesize($file);
		$r = $width / $height;
		if ($crop) {
			if ($width > $height) {
				$width = ceil($width-($width*abs($r-$w/$h)));
			} else {
				$height = ceil($height-($height*abs($r-$w/$h)));
			}
			$newwidth = $w;
			$newheight = $h;
		} else {
			if ($w/$h > $r) {
				$newwidth = $h*$r;
				$newheight = $h;
			} else {
				$newheight = $w/$r;
				$newwidth = $w;
			}
		}
		$filetype = strtolower(pathinfo($file,PATHINFO_EXTENSION));
		if($filetype == "jpg" || $filetype == "jpeg"){
			$src = imagecreatefromjpeg($file);
		}elseif($filetype == "png"){
			$src = imagecreatefrompng($file);
			imageAlphaBlending($src, true);
			imageSaveAlpha($src, true);
		}
		$newwidth = intval($newwidth);
		$newheight = intval($newheight);
		$dst = imagecreatetruecolor($newwidth, $newheight);
		if($filetype == 'png'){
			ini_set('gd.jpeg_ignore_warning', 1);
			$background = imagecolorallocate($dst , 0, 0, 0);
			imagecolortransparent($dst, $background);

		}
		imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		return $dst;
	}
}
?>