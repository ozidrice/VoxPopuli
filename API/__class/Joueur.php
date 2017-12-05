<?php

/**
* 
*/
class Joueur
{
	public $idJoueur;
	public $pseudo;
	public $vie;
	
	function __construct($data)
	{
		$this->idJoueur = $data["idJoueur"];
		$this->pseudo = $data["pseudo"];
		$this->vie = $data["Vie"];
	}
}

?>