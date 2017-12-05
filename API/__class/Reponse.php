<?php

/**
* 
*/
class Reponse
{
	public $idReponse;
	public $intitule;

	function __construct($data)
	{
		$this->idReponse = $data["idReponse"];	
		$this->intitule = $data["intitule"];	
	}
}

?>