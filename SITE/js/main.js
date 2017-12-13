var API_link = "https://voxpopuliapi.ozidrice.com/";
var publictoken = "ozqidjodqjdqs";

var form_user = document.querySelector("#form"); 
var user;
var game;

//POUR RECUP LES VALEURS = user.then(val => console.log(val));
var current_game = get_current_game();

var submit_button = document.querySelector('#submit_button');
var connection = document.querySelector('#connection');
var connection_container = document.querySelector('#connection_container');
var chrono = document.querySelector("#chrono p");
var players_list = document.querySelector("#players");

form_user.addEventListener("submit",()=>{
	connection.style.display = "none";
	connection_container.classList.add("animation");
	var pseudo = form_user.querySelector("input#pseudo").value;
	user = create_user(pseudo);
	user.then((userdata)=>join_game(userdata.idJoueur));
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
			case 1:
				//Rejoindre la partie

				break;
			case 2:
				//En partie
				update_question();
				break;
			case 3:
				//Resultats

				break;
			default:

				break;

		}
		console.log(idEtat);
  	});
}


/*SYNCHRO TEMPS SERVEUR ET TEMPS AFFICHE*/
function update_time(){
	get_time_left().then((time)=>chrono.innerHTML=time);
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
	players_list.querySelectorAll(".player:not(.hidden)").forEach((elem)=>elem.remove());
	list_user().then((list_user) => {
		list_user.forEach((user) => set_new_user(user));
	});
}

/*CREE UN NOUVEL ELEMENT USER DANS LA PAGE*/
function set_new_user(user){
	var elem_model_user = document.querySelector("#player_exemple");
	var elem_new_user = elem_model_user.cloneNode(true);
	elem_new_user.querySelector("p").innerHTML = user["pseudo"];
	elem_new_user.classList.remove("hidden");
	players_list.appendChild(elem_new_user);
	}

/*MODIFIE LA QUESTION AFFICHEE*/
function set_question(question){
	document.querySelector("#question p").innerHTML = question;
}

/*MODIFIE LES REPONSES AFFICHEES*/
function set_reponses(str_list){
	var list_elem_reponses = document.querySelectorAll(".answer");
	str_list.forEach((rep,key)=>{ 
		list_elem_reponses[key].innerHTML = rep["intitule"];
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