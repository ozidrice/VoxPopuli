<?php 
class mailController extends Controller{
	
	function __construct(){
		parent::__construct(new mailModel(), new View());
	}
	
	function run_action($action){
		$this->model->sendMailToListeMailing("TEST");
		if($action==""){
			$this->page_mail();
			return true;
		}elseif($action == "ajoutmail"){
			if(sizeof($_POST) == 0){
				$this->page_ajout();
			}else{
				if($this->model->add_mail($_POST)){
					$this->view->set_success("Le mail a bien été ajouté");
					$this->page_mail();
				}else{
					$this->view->set_error("Une erreur est survenue");	
					$this->page_ajout($_POST);
				}
			}
			return true;
		}elseif($action == "editmail"){
			if(sizeof($_POST) == 0){
				$this->page_edit();
			}else{
					$_POST["nommail"] = urldecode($_GET["3"]);
				if($this->model->add_mail($_POST)){
					$this->view->set_success("Le mail a bien été modifié");
					$this->page_edit();
				}else{
					$this->view->set_error("Une erreur est survenue");	
					$this->view->edit_mail($_POST);
				}
			}
			return true;
		}elseif($action == "mailing_download"){
			header('Content-type: text/csv');
			header('Content-Disposition: attachment; filename="mailing_list.csv"');
			header('Pragma: no-cache');
			header('Expires: 0');
			 
			$file = fopen('php://output', 'w');
			 
			$data = array();
			$data[] = array('Mail');
			foreach ($this->model->get_mailing_list() as $mail){
				$data[] = array($mail->get_to());
			}
			 
			foreach ($data as $row){
			    fputcsv($file, $row);
			}
			exit();
		}
		return false;
	}

	private function page_ajout($mail = null){
		$this->view->set_titre("Ajouter un mail");
		$this->view->set_template("mail_form");
		$this->view->data["fil_ariane"] = array("/admin/"=>"home","/admin/mail/"=>"Mails","#"=>"Ajouter un mail");
		$this->view->data["mail"] = $mail;
		$this->view->data["allow_edit_nommail"] = true;
		$this->view->add_js("/ckeditor/ckeditor.js");
	}

	private function page_edit(){
		if($mail = $this->model->get_mail(urldecode($_GET["3"]))){
			$this->view->set_titre("Modifier un mail");
			$this->view->set_template("mail_form");
			$this->view->data["fil_ariane"] = array("/admin/"=>"home","/admin/mail/"=>"Mails","#"=>"Modifier un mail");
			$this->view->data["mail"] = $mail;
			$this->view->data["allow_edit_nommail"]=false;
			$this->view->add_js("/ckeditor/ckeditor.js");
		}
	}
	
	private function page_mail(){
		$this->view->set_template("mail");
		$this->view->set_titre("Mails");
		$this->view->data["liste_mail"] = $this->model->get_list_mail();
	}

	
}
?>