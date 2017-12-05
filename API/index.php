<?php
	require_once("_modules/controller.php");
	require_once("_modules/model.php");
	require_once("_modules/view.php");
	require_once("_modules/ROOTING/controller.php");

	require_once("__class/template.php");
	require_once("__class/templateBack.php");
	require_once("__class/templateFront.php");
	
	date_default_timezone_set('Europe/Paris');
	ini_set("error_log", "logs/phplogs.log");

	session_start();
	
	$rooting = new ROOTINGController();
	$controller = $rooting->launch();
	
	$template = new TemplateFront();
	
	$controller->view->data["default_css"] = $template->css;
	$controller->view->data["default_js"] = $template->js;
	$controller->view->data["module_name"] = substr(get_class($controller),0,-10);
	$controller->view->tamponVersContenu();
	// require_once($template->get_lienTemplate()."/template.php");

?>