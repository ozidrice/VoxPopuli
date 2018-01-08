// var API_link = "http://localhost/";
var API_link = "https://voxpopuliapi.ozidrice.com/";
var publictoken = "ozqidjodqjdqs";



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

function vote(idJoueur,idvote){
	var url = API_link + "vote";
	var varnames = ["token","idReponse","idJoueur"];
	var data = [publictoken,idvote,idJoueur];
	url = createlink(url,data,varnames);
	console.log(url);
	return fetch_link(url);
}



/*SI ETAT == 3*/
/*DETAIL DES VOTES*/
function get_votes_current_question(){
	var url = API_link + "get_votes_current_question";
	var varnames = ["token"];
	var data = [publictoken];
	url = createlink(url,data,varnames);

	return fetch_link(url);
}
/*NB DE VOTE POUR CHAQUE REPONSE*/
function get_nb_votes_reponses(){
	var url = API_link + "get_nb_votes_reponses";
	var varnames = ["token"];
	var data = [publictoken];
	url = createlink(url,data,varnames);

	return fetch_link(url);
}

function get_reponses_gagnante(){
	var url = API_link + "get_reponses_gagnante";
	var varnames = ["token"];
	var data = [publictoken];
	url = createlink(url,data,varnames);

	return fetch_link(url);
}


/*SI ETAT == 4*/
function get_winners(){
	var url = API_link + "get_winners";
	var varnames = ["token"];
	var data = [publictoken];
	url = createlink(url,data,varnames);

	return fetch_link(url);
}






function fetch_link(link){
	var err = false;
	var fetch_resp = fetch(link)
			.then((response) => response.json())
		.catch(error => err=true);
	if(err===true)
		return fetch_link(link);
	return fetch_resp;	
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
