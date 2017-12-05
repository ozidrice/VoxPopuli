<?php

/**
* 
*/
class Partie
{
	public $idPartie;
	public $etat;
	public $date;

	function __construct($data)
	{
		$this->idPartie = $data["idPartie"];
		$this->etat = $data["etat"];
		$this->date = $data["date"];
	}
}

?>