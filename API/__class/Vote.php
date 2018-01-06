<?php



/**

* 

*/

class Vote

{

	public $idPartie;

	public $tour;
	public $idQuestion;

	public $idReponse;

	public $idJoueur;

	function __construct($data)

	{

		$this->idPartie = $data["idPartie"];
		$this->tour = $data["tour"];

		$this->idQuestion = $data["idQuestion"];

		$this->idReponse = $data["idReponse"];

		$this->idJoueur = $data["idJoueur"];

	}

}



?>