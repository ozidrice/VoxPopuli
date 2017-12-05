<?php

/**
* 
*/
class Vie
{
	public $idPartie;
	public $idJoueur;
	public $vie;	
	function __construct($data)
	{
		$this->idPartie = $data["idPartie"];
		$this->idJoueur = $data["idJoueur"];
		$this->vie = $data["vie"];
	}
}

?>