<?php
class View{
	public $paramSite;
	public $repositoryLink = "/front";
	public $rootLink;
	protected $apiKey = array(
	"Google map"=>"AIzaSyBrKRrxWd06eC_lj8zEbuxm76w-upgNWng",
	);
	
	protected $liste_css = array();
	protected $liste_js = array("async"=>array(),"force_load"=>array());
	public $titre = "";
	public $contenu = "";
	public $meta;
	protected $success;
	protected $error;
	protected $infoMessage;

	/*TWIG*/
	public $templateFolder = "front/";
	public $templateAdminFolder = "admin/";
	private $template;
	public $data = array();
	private $dev_mod = true; //SI TRUE DESACTIVE LE CACHE ET ACTIVE LA FONCTION DEBUG()
	/*____*/


	function __construct(){
		$this->rootLink = substr(__DIR__, 0, strrpos(__DIR__, '/'));
		$this->contenu = "";
	}

	function tamponVersContenu($is_admin = false){
			if(!$is_admin){
				$loader = new Twig_Loader_Filesystem($this->templateFolder);
			}else{
				$loader = new Twig_Loader_Filesystem($this->templateAdminFolder);
			}
			if($this->dev_mod){
				$twig = new Twig_Environment($loader, array(
					'debug' => true,
				    'cache' => false,
				));
				$twig->addExtension(new Twig_Extension_Debug());
			}else{
				$twig = new Twig_Environment($loader, array(
				    'cache' => '/home/ozidricexa/Twig/cache/',
				));
			}
			$this->data["get"] = $_GET;
			$this->data["post"] = $_POST;
			$this->data["session"] = $_SESSION;
			$this->data["paramSite"] = $this->paramSite;
			$this->data["liste_meta"] = $this->meta;
			$this->data["success"] = $this->success;
			$this->data["error"] = $this->error;
			$this->data["info"] = $this->infoMessage;
			$this->data["liste_css"] = $this->liste_css;
			$this->data["liste_js"] = $this->liste_js;
			$this->data["title"] = $this->titre;
			try{
				echo $twig->render($this->template.".tpl",$this->data);
			}catch(Twig_Error_Loader $e){
				$this->data["error"] = $this->data["error"]." [TWIG ERROR TRIGGERED]:".$e->getMessage();
				echo $twig->render("general_404.tpl",$this->data);
			}
			// print_r("[TWIG ERRROR] Template introuvable");
			// $this->contenu = $this->contenu . ob_get_clean();
		
	}
	
	function add_css($fichier_css){
		$this->liste_css[] = $fichier_css;
	}
	
	function add_js($fichier_js, $async = false){
		if(!$async){
			$this->liste_js["force_load"][] = $fichier_js;
		}else{
			$this->liste_js["async"][] = $fichier_js;
		}
	}
	
	function set_param_site(array $params){
		$this->paramSite = $params;
	}

	function set_template($template){
		$this->template = $template;
	}
	
	function set_titre($titre){
		$this->titre = $titre." - ";
	}
	
	function set_success($message){
		$this->success = $message;
	}
	
	function set_error($message){
		$this->error = $message;
	}
	
	function set_info($message){
		$this->infoMessage = $message;
	}
	
	function get_contenu(){
		return $this->contenu;
	}
	
	function get_success(){
		return $this->success;
	}
	
	function get_error(){
		return $this->error;
	}
	
	function get_info(){
		return $this->infoMessage;
	}
	
	function get_repositoryLink(){
		return $this->repositoryLink;
	}
	
	
}
?>