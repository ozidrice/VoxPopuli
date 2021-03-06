var fontawesomelife = "fa-heart";
var fontawesomelife_empty = "fa-heart-o";

var user;
var game;
var current_game = get_current_game();

var form_user = document.querySelector("#form"); 
var submit_button = document.querySelector('#submit_button');
var join_button = document.querySelector("#join_button");
var connection = document.querySelector('#connection');
var connection_container = document.querySelector('#connection_container');
var chrono = document.querySelector("#chrono p");
var players_list = document.querySelector("#players");
var player_infos = document.querySelector("#player_infos");
var section_join_game = document.querySelector("#join_game");
var section_winner = document.querySelector("#winner");
var section_game_bar = document.querySelector("#game_bar");
var waiting_players = document.querySelector('#waiting_players');
var list_reponse = document.querySelectorAll(".answer");
var list_result = document.querySelectorAll(".result");
var user_infos = document.querySelector("#user_infos");
var infos_bar = document.querySelector("#infos_bar");
var game_infos = document.querySelector("#game_infos");
var game_rules_container = document.querySelector("#game_rules_container");
var game_rules = document.querySelector("#game_rules");

form_user.addEventListener("submit",()=>{
	connection.style.display = "none";
	connection_container.classList.add("animation");
	var pseudo = form_user.querySelector("input#pseudo").value;
	user = create_user(pseudo);
	player_infos.querySelector("p").innerHTML = pseudo;
});

join_button.addEventListener("click",()=>{
	user.then((userdata)=>join_game(userdata.idJoueur));
	join_button.classList.add("hidden");
	waiting_players.classList.remove("hidden");
	
});

var update_interval = 1000; //ms
var time_since_last_update = 0; //ms
window.setInterval(function(){
  	//Update game only if timer == 0 or to resync 
  	var curr_time = chrono.innerHTML;
  	if(curr_time == 0 || time_since_last_update >= 5000){
  		update_time();
  		game_loop();
  	}
  	set_time(curr_time-1);
	time_since_last_update += update_interval;
}, update_interval);






function game_loop(){
	if(game = get_current_game()){

		game = game.then((game)=>{
			var idEtat = game.etat.idEtat;
			switch(idEtat){
				case "1":
					//Rejoindre la partie
					section_game_bar.classList.add("hidden");
					section_winner.classList.add("hidden");
					section_join_game.classList.remove("hidden");
					clear_pastilles();
					break;
				case "2":
					//Question
					update_question();
					section_join_game.classList.add("hidden");
					section_winner.classList.add("hidden");
					list_result.forEach((result_elem)=>result_elem.classList.add("hidden"));
					section_game_bar.classList.remove("hidden");
					clear_pastilles();
					break;
				case "3":
					//Resultats Question
					update_question();
					set_nb_vote_reponse();
					set_pastille_vote();
					list_reponse.forEach((answer)=>{
						answer.classList.remove("selected");
					});
					break;
				case "4":
					//Fin
					set_winner();
					section_join_game.classList.add("hidden");
					section_game_bar.classList.add("hidden");

					waiting_players.classList.add("hidden");
					join_button.classList.remove("hidden");
					clear_player_list();
					section_winner.classList.remove("hidden");
					break;
			}
			if(idEtat != 4){
				update_users();
			}

	  	});
	}
}


/*SYNCHRO TEMPS SERVEUR ET TEMPS AFFICHE*/
function update_time(){
	get_time_left().then((time)=>{
		set_time(time);
	});
}

/*SET LE TEMPS AFFICHE A TIME*/
function set_time(time){
	if(time >= 0)
		chrono.innerHTML=time;
}

/*UPDATE LA QUESTION ET LES REPONSES COURANTE*/
function update_question(){
	get_current_question().then((question)=>{
		set_question(question["question"]["intitule"]);
		set_reponses(question["list_reponse"]);
	})	
}

/*UPDATE LA LISTE DES JOUEURS DE LA PARTIE COURANTE SUR LA PAGE*/
function update_users(){
	list_user().then((list_user) => {
		list_user.forEach((user) => update_user(user));
	});
}

function clear_player_list(){
	players_list.querySelectorAll(".player:not(.hidden)").forEach((player_elem)=>player_elem.remove());
}

/*CREE UN NOUVEL ELEMENT USER DANS LA PAGE*/
function set_new_user(user){
	var elem_model_user = document.querySelector("#player_exemple");
	var elem_new_user = elem_model_user.cloneNode(true);
	elem_new_user.querySelector("p").innerHTML = user["pseudo"];
	elem_new_user.classList.remove("hidden");
	elem_new_user.id = "player_"+user["idJoueur"];
	players_list.appendChild(elem_new_user);
}

/*MET A JOUR UN UTILISATEUR DEJA AFFICHE OU LE CREE*/
function update_user(user){
	var joueurElem = document.querySelector("#player_"+user["idJoueur"]);
	if(joueurElem != null){
		joueurElem.querySelectorAll(".fa").forEach((life_elem,i)=>{
			if(user.vie > i){
				life_elem.classList.remove(fontawesomelife_empty);
				life_elem.classList.add(fontawesomelife);
			}else{
				life_elem.classList.remove(fontawesomelife);
				life_elem.classList.add(fontawesomelife_empty);
			}
		})
	}else{
		set_new_user(user);
	}
}

function set_nb_vote_reponse(){
	var result_from_db = get_nb_votes_reponses();
	list_reponse.forEach((reponse_elem)=>{
		var id_reponse = reponse_elem.id.substr(7);
		result_from_db.then((list_result_db)=>{
			var found = false;
			var i = 0;
			while(i < list_result_db.length && found === false){
				var result_db = list_result_db[i];
				if(result_db["idReponse"] == id_reponse){
					reponse_elem.querySelector(".result").innerHTML = result_db["nbVote"];
					found = true;
				}
				i++;
			}
			if(found === false){
				reponse_elem.querySelector(".result").innerHTML = 0;
			}
		});
	});
	document.querySelectorAll(".result").forEach((result_elem)=>result_elem.classList.remove("hidden"));

}


function set_winner(){
	get_winners().then((user)=>{
		if(user.length == 0){
			section_winner.querySelector("span").innerHTML = "Personne n'";
			section_winner.querySelector("#sing").classList.remove("hidden");
			section_winner.querySelector("#plur").classList.add("hidden");
		}else{
			if(user.length == 1){
				section_winner.querySelector("#noms").innerHTML = user[0]["pseudo"];
				section_winner.querySelector("#sing").classList.remove("hidden");
				section_winner.querySelector("#plur").classList.add("hidden");
			}else{
				section_winner.querySelector("#noms").innerHTML = user[0]["pseudo"]+" & "+user[1]["pseudo"];
				section_winner.querySelector("#sing").classList.add("hidden");
				section_winner.querySelector("#plur").classList.remove("hidden");
			}
		}
	});
}

/*MODIFIE LA QUESTION AFFICHEE*/
function set_question(question){
	document.querySelector("#question p").innerHTML = question;
}

/*MODIFIE LES REPONSES AFFICHEES*/
function set_reponses(str_list){
	var list_elem_reponses = document.querySelectorAll(".answer");
	str_list.forEach((rep,key)=>{ 
		var curr_resp = list_elem_reponses[key];
		curr_resp.querySelector(".text").innerHTML = rep["intitule"];
		curr_resp.id="answer_"+rep["idReponse"];
	}); 
}

function set_pastille_vote(){
	players_list.querySelectorAll(".player:not(.hidden)").forEach((player)=>{
		get_votes_current_question().then((list_vote)=>{
			var i = 0;
			var id_joueur = player.id.substr(7);
			while(i < list_vote.length && list_vote[i]["idJoueur"] !=  id_joueur){
				i++;
			}
			var vote = list_vote[i]; 
			if(i === list_vote.length){
				set_pastille_joueur(id_joueur,"color_grey");
			}
			else{
				set_pastille_joueur(id_joueur,get_pastille_reponse(vote["idReponse"]));
			}
		});
	})

}

function set_pastille_joueur(idJoueur, class_color){
	 document.querySelector("#player_"+idJoueur).querySelector(".answer_color").classList.add(class_color);
}

/*RETOURNE LA CLASS DE LA COULEUR DE PASTILLE*/
function get_pastille_reponse(id_reponse){
	var i = 0;
	var list_class = document.querySelector("#answer_"+id_reponse).querySelector(".btn").classList;
	while(i < list_class.length){
		if(list_class[i].startsWith("color_"))
			return list_class[i]
		i++;
	}
	return "color_grey";
}

function clear_pastilles(){
	document.querySelectorAll(".answer_color").forEach((answer_color)=>{
		answer_color.classList.remove("color_red");
		answer_color.classList.remove("color_yellow");
		answer_color.classList.remove("color_green");
		answer_color.classList.remove("color_grey");
	})
}