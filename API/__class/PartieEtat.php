<?php 
/**
* 
*/
class PartieEtat
{
	public $idEtat;
	public $nom;
	public $temps;

	function __construct($data)
	{
		$this->idEtat = $data["idEtat"];
		$this->nom = $data["nom"];
		$this->temps = $data["temps"];
	}
}
?>