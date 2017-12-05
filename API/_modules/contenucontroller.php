<?php 
require_once("_model/contenumodel.php");
abstract class contenuController extends Controller{

	function __construct(){
		parent::__construct(new contenuModel(), new View());
	}
}
?>