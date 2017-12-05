<?php

/**
* 
*/
class Question
{
	public $idQuestion;
	public $intitule;
	
	function __construct($data)
	{
		$this->idQuestion = $data["idQuestion"];
		$this->intitule = $data["intitule"];	
	}
}

?>