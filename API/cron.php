<?php
require_once("_modules/controller.php");
require_once("_modules/model.php");
require_once("_modules/view.php");

$model = new Model();
$i = 0;
while($i<1){
	if($model->get_param_site()["VP_kill"] == 1){
		echo "KILLED BY BDD";
		exit();
	}
	$model->creerNouvellePartie();
	$partieCourante = $model->get_partie_courante();
	

	sleep(15);
	//PARTIE DURANT LAQUELLE LES JOUEURS JOIN LA GAME


	$liste_question = $model->get_question();
	$liste_reponse = $model->get_reponse();
	while($model->more_thant_one_player_alive($partieCourante->idPartie)){		
		$question = $liste_question[rand(1,sizeof($liste_question))-1];
		$reponse1 = $liste_reponse[rand(1,sizeof($liste_reponse))-1];
		$reponse2 = $liste_reponse[rand(1,sizeof($liste_reponse))-1];
		$reponse3 = $liste_reponse[rand(1,sizeof($liste_reponse))-1];

		if($model->get_param_site()["VP_kill"] == 1){
			echo "KILLED BY BDD";
			exit();
		}
		$model->set_nouvelle_question($partieCourante->idPartie, $question->idQuestion, $reponse1->idReponse, $reponse2->idReponse, $reponse3->idReponse);
		sleep(30);
		
		$model->get_nb_vote_question($partieCourante->idPartie,$idQuestion);

		$liste_vie = $model->getVieJoueurs($partieCourante->idPartie);
		foreach ($liste_vie as $vie) {
			$model->retirerVie($vie,1);
		}
		//PARTIE DURANT LAQUELLE LES JOUEURS REPONDENT A LA QUESTION
	}

	$i++;

}


?>