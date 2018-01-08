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
		user.then((logged_user)=>{
			list_user().then((users)=>{
				users.forEach((user)=>{
					if(user["idJoueur"] == logged_user["idJoueur"]){
						if(user["vie"] > 0){
							answer.classList.add("selected");
							vote(user["idJoueur"],answer.id.substr(7))
						}
						else {
							console.log("Plus de vie");
						}
					}
				});
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

game_infos.addEventListener("click",function(){
	game_rules_container.classList.remove("hidden");
	game_rules_container.classList.remove("animation_close_rules");
	game_rules_container.classList.add("animation_open_rules");
	setTimeout(function(){game_rules.classList.remove("hidden");}, 2000);
});

game_rules_container.addEventListener("click",function(){
	game_rules.classList.add("hidden");
	game_rules_container.classList.remove("animation_open_rules");
	game_rules_container.classList.add("animation_close_rules");
});