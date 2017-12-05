<?php 
class ROOTINGController extends Controller{

	private $adminMod = false;
	private $devMod = false;
	private $dossierAdmin = "_ADMIN/";
	private $url_admin = "admin";
	private $ajax_request = false;
	private $exit_after_running = false;

	function __construct(){
		parent::__construct(new Model(), new View());

		if(!isset($_GET["dev"])){ 
			$this->devMod = true;
			ini_set("display_errors",0);
			error_reporting(0);
		}
	}
	
	function run_action($action){
		
	}

	function launch(){
		$this->rewritte_GET();
		

		$module="api";
		$action = $_GET[0];
	
		$controller = $this->loadModule($module);


		if(!$controller->run_action($action)){
			$controller = $this->get404();
			$controller->run_action("");
		}
		return $controller;
	}

	function rewritte_GET(){
		$param = explode("/",$_GET["param"]);
		foreach ($param as $get) {
			$_GET[] = $get;
		}
	}

	function loadModule($module){
		$dossierAdmin = "";
		if($this->adminMod){ $dossierAdmin = $this->dossierAdmin; }
		
		try{
			if(!include_once("_modules/".$dossierAdmin.$module."/controller.php")){
				throw new Exception('index : controller introuvable ('.$dossierAdmin.$module.').');
			}
			include_once("_modules/".$dossierAdmin.$module."/view.php");
			include_once("_model/".$module."model.php");
			$module = $module."Controller";
			return new $module();
		}catch(Exception $e){
			error_log($e->getMessage());
			return $this->get404();
		}
	}

	function get404(){
		include_once("_modules/Erreur404/controller.php");
		include_once("_modules/Erreur404/model.php");
		include_once("_modules/Erreur404/view.php");
		$module = "Erreur404Controller";
		$controller = new $module();
		return $controller;

	}
	
	
}
?>