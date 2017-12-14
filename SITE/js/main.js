var API_link = "https://voxpopuliapi.ozidrice.com/";
var publictoken = "ozqidjodqjdqs";

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
var section_game_bar = document.querySelector("#game_bar");
var waiting_players = document.querySelector('#waiting_players');
var waiting_start = document.querySelector('waiting_start');


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

document.querySelectorAll(".answer").forEach((answer)=>{
	answer.addEventListener("click",()=>{
		answer.classList.add("selected");
		user.then((userdata)=>vote(userdata.idJoueur,answer.id.substr(7)));
	});
});


window.setInterval(function(){
  	update_time();
  	update_users();
  	game_loop();
}, 7000);




function game_loop(){
	game = get_current_game().then((game)=>{
		var idEtat = game.etat.idEtat;
		switch(idEtat){
			case "1":
				//Rejoindre la partie
				section_join_game.classList.remove("hidden");
				section_game_bar.classList.add("hidden");
				break;
			case "2":
				//En partie
				section_game_bar.classList.remove("hidden");
				section_join_game.classList.add("hidden");
				update_question();
				break;
			case "3":
				//Reponses

				break;
			case "4":
				//ENDED
				console.log("ENDED");
			default:

<<<<<<< HEAD
var join_button = document.querySelector('#join_button');
var waiting_players = document.querySelector('#waiting_players');
var waiting_start = document.querySelector('waiting_start');

join_button.addEventListener("click",function(){
	join_button.classList.add("hidden");
	waiting_players.classList.remove("hidden");
});
=======
>>>>>>> 5c3397ec0ce04f7d56468eda2794eea3d19ba266
				break;

		}
		console.log(idEtat);
  	});
}


/*SYNCHRO TEMPS SERVEUR ET TEMPS AFFICHE*/
function update_time(){
	get_time_left().then((time)=>{
		chrono.innerHTML=time;
	});
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

/*MODIFIE LA QUESTION AFFICHEE*/
function set_question(question){
	document.querySelector("#question p").innerHTML = question;
}

/*MODIFIE LES REPONSES AFFICHEES*/
function set_reponses(str_list){
	var list_elem_reponses = document.querySelectorAll(".answer");
	str_list.forEach((rep,key)=>{ 
		var curr_resp = list_elem_reponses[key];
		curr_resp.querySelector("p").innerHTML = rep["intitule"];
		curr_resp.id="answer_"+rep["idReponse"];
	}); 
}




/*
* FUNCTIONS GETTING FROM API
*/
function create_user(pseudo){
	var url = API_link + "create_user";
	var varnames = ["token","pseudo"];
	var data = [publictoken,pseudo];
	url = createlink(url,data,varnames);

	return fetch_link(url);
}

function list_user(){
	var url = API_link + "list_user";
	var varnames = ["token"];
	var data = [publictoken];
	url = createlink(url,data,varnames);

	return fetch_link(url);
}

function get_current_game(){
	var url = API_link + "get_current_game";
	var varnames = ["token"];
	var data = [publictoken];
	url = createlink(url,data,varnames);

	return fetch_link(url);
}

function get_time_left(){
	var url = API_link + "get_time_left";
	var varnames = ["token"];
	var data = [publictoken];
	url = createlink(url,data,varnames);
	console.log(url);
	return fetch_link(url);
}

/*SI ETAT == 1*/
function join_game(idJoueur){
	var url = API_link + "join_game";
	var varnames = ["token","idJoueur"];
	var data = [publictoken,idJoueur];
	url = createlink(url,data,varnames);

		console.log(url);
	return fetch_link(url);
}

/*SI ETAT == 2*/
function get_current_question(idJoueur){
	var url = API_link + "get_current_question";
	var varnames = ["token"];
	var data = [publictoken];
	url = createlink(url,data,varnames);

	return fetch_link(url);
}

function vote(idJoueur,idvote){
	var url = API_link + "vote";
	var varnames = ["token","idReponse","idJoueur"];
	var data = [publictoken,idvote,idJoueur];
	url = createlink(url,data,varnames);

	return fetch_link(url);
}


function fetch_link(link){
	return fetch(link)
	.then((response) => response.json())
	.then((responseData) => {
		return responseData;
	})
	.catch(error => console.warn(error));
}

function createlink(baselink, datas, varnames){
	if(!Array.isArray(datas))
		return false

	baselink+="?";
	datas.forEach(function(data,key){
		baselink+=varnames[key]+"="+data+"&";
	});
	return baselink;
}