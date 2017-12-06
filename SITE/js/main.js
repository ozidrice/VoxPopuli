var API_link = "https://voxpopuliapi.ozidrice.com/";
var publictoken = "ozqidjodqjdqs";


var user = create_user("pseudorandom2");
//POUR RECUP LES VALEURS = user.then(val => console.log(val));
var list_participants = list_user();
var current_game = get_current_game().then(val=>console.log(val));


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