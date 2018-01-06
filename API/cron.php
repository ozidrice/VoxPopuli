<?php
require_once("_modules/controller.php");
require_once("_modules/model.php");
require_once("_modules/view.php");
Class Cron{
	private static $partieCourante;
	static function launch(){
		ini_set('memory_limit', '256M');
		set_time_limit(0);

		self::set_time_left(-1);
		$model = new Model();
		$i = 0;
		while($i<1){
			if($model->get_param_site()["VP_kill"] == 1){
				echo "KILLED BY BDD";
				exit();
			}
			//PARTIE DURANT LAQUELLE LES JOUEURS JOIN LA GAME
			$model->creerNouvellePartie();
			self::$partieCourante = $model->get_partie_courante();
			$model->set_partie_etat(self::$partieCourante->idPartie,1);
			
			
			while(sizeof($model->get_liste_joueur_partie(self::$partieCourante->idPartie)) < 2){
				if($model->get_param_site()["VP_kill"] == 1){
					echo "KILLED BY BDD";
					exit();
				}	
				self::set_time_left($model->get_partie_etat(1)->temps);
				while(self::get_time_left() > 0){
					sleep(1);
					$current_time = self::get_time_left();
					self::set_time_left($current_time-1);
				}

			}
			$liste_question = $model->get_question();
			$liste_reponse = $model->get_reponse();
			//PARTIE DURANT LAQUELLE LES JOUEURS REPONDENT A LA QUESTION

			while($model->more_thant_one_player_alive(self::$partieCourante->idPartie)){
				$model->next_tour(self::$partieCourante->idPartie);
				self::$partieCourante = $model->get_partie_courante();		
				
				$question = $liste_question[rand(1,sizeof($liste_question))-1];
				$id1 = rand(1,sizeof($liste_reponse))-1;
				$reponse1 = $liste_reponse[$id1];
				do{
					$id2 = rand(1,sizeof($liste_reponse))-1;
				}while($id1 == $id2);
				$reponse2 = $liste_reponse[$id2];
				
				do{
					$id3 = rand(1,sizeof($liste_reponse))-1;
				}while($id3 == $id1 || $id3 == $id2);
				$reponse3 = $liste_reponse[$id3];

				if($model->get_param_site()["VP_kill"] == 1){
					echo "KILLED BY BDD";
					exit();
				}
				
				$model->set_nouvelle_question(self::$partieCourante->idPartie, self::$partieCourante->tourCourant,$question->idQuestion, $reponse1->idReponse, $reponse2->idReponse, $reponse3->idReponse);
				

				$model->set_partie_etat(self::$partieCourante->idPartie,2);
				self::set_time_left($model->get_partie_etat(2)->temps);
				while(self::get_time_left()>0){
					sleep(1);
					$current_time = self::get_time_left();
					self::set_time_left($current_time-1);
				}
				$liste_reponse_gagnantes = $model->get_reponses_gagnante(self::$partieCourante->idPartie,$question->idQuestion,self::$partieCourante->tourCourant);
				$liste_vie = $model->getVieJoueurs(self::$partieCourante->idPartie);
				foreach ($liste_vie as $vie) {
					$vote = $model->get_vote(self::$partieCourante->idPartie,$question->idQuestion,$vie->idJoueur,self::$partieCourante->tourCourant);
					if($vote->idReponse == "" || !array_key_exists($vote->idReponse,$liste_reponse_gagnantes)){
						$model->retirerVie($vie,1);
					}
				}
				$model->set_partie_etat(self::$partieCourante->idPartie,3);
				self::set_time_left($model->get_partie_etat(3)->temps);
				while(self::get_time_left()>0){
					sleep(1);
					$current_time = self::get_time_left();
					self::set_time_left($current_time-1);
				}
				
			}
			$model->set_partie_etat(self::$partieCourante->idPartie,4);
			$i++;
		}
	}
	static function get_time_left(){
		return json_decode(file_get_contents('cache'));
	}
	private static function set_time_left($variable){
		file_put_contents('cache', json_encode($variable));
	}
}
?>