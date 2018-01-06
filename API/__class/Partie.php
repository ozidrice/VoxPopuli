<?php



/**

* 

*/

class Partie

{

	public $idPartie;
	public $tourCourant;
	public $etat;

	public $date;



	function __construct($data)

	{

		$this->idPartie = $data["idPartie"];
		$this->tourCourant = $data["tourCourant"];
		$this->etat = $data["etat"];

		$this->date = $data["date"];

	}

}



?>