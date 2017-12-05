<?php
require_once('/home/ozidricexa/Twig/lib/Twig/Autoloader.php');


abstract class Controller{
	public $model;
	public $view;
	
	function __construct($model, $view){
		$this->model=$model;
		$this->view=$view;
		
	}
	
	abstract function run_action($action);

	function send_request(array $data, $url){
		// use key 'http' even if you send the request to https://...
		$options = array(
		    'http' => array(
		        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		        'method'  => 'POST',
		        'content' => http_build_query($data)
		    )
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		if ($result === FALSE) { echo "ERROR REQUESTING ".$url." in Controller(send_request)"; }
		return $result;
	}

	
}
?>