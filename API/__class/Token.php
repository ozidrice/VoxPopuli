<?php

/**
* 
*/
class Token
{
	public $token;
	public $nom;

	function __construct($data)
	{
		$this->token = $data["token"];	
		$this->nom = $data["nom"];	
	}
}

?>