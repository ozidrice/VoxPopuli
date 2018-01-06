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


list_reponse.forEach((answer)=>{
	answer.addEventListener("click",()=>{
		list_reponse.forEach((reponse_elem)=>reponse_elem.classList.remove("selected"));
		answer.classList.add("selected");
		user.then((userdata)=>{
			vote(userdata.idJoueur,answer.id.substr(7)).then((vote)=>{
				if(vote != true){
					console.log("PROBLEM");
				}
			});
		});
	});
});


user_infos.addEventListener("click", function(){
	infos_bar.classList.add("block");
});

infos_bar.addEventListener("click", function(){
	infos_bar.classList.remove("block");
});
