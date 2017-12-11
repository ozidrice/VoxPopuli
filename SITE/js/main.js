var API_link = "https://voxpopuliapi.ozidrice.com/";
var publictoken = "ozqidjodqjdqs";


var user = create_user("pseudorandom2");
//POUR RECUP LES VALEURS = user.then(val => console.log(val));
var current_game = get_current_game();




update_question();
update_users();



function update_question(){
	get_current_question().then((question)=>{
		set_question(question["question"]["intitule"]);
		set_reponses(question["list_reponse"]);
	})	
}

function update_users(){
	list_user().then((list_user) => {
		list_user.forEach((user) => set_new_user(user));
	});
}

function set_new_user(user){
	var elem_model_user = document.querySelector("#player_exemple");
	var elem_new_user = elem_model_user.cloneNode(true);
	console.log(elem_new_user);
	elem_new_user.querySelector("p").innerHTML = user["pseudo"];
	elem_new_user.classList.remove("hidden");
	document.querySelector("#players").appendChild(elem_new_user);
	}

function set_question(question){
	document.querySelector("#question p").innerHTML = question;
}

function set_reponses(str_list){
	var list_elem_reponses = document.querySelectorAll(".answer");
	str_list.forEach((rep,key)=>{ 
		list_elem_reponses[key].innerHTML = rep["intitule"];
	}); 
}

/*
* FUNCTIONS GETTING FROM API
*/


var submit_button = document.querySelector('#submit_button');
var connection = document.querySelector('#connection');
var connection_container = document.querySelector('#connection_container');
console.log(submit_button);

submit_button.addEventListener('click', function(){
	connection.style.display = "none";
	connection_container.classList.add("animation");
});






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

/*SI ETAT == 1*/
function join_game(idJoueur){
	var url = API_link + "join_game";
	var varnames = ["token","idJoueur"];
	var data = [publictoken,idJoueur];
	url = createlink(url,data,varnames);

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