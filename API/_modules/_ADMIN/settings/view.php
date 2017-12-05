<?php 
class settingsView extends View{
	function get_name(){
		return get_class();
	}
	function affiche_page_settings($data){
		?>

		<div id="settings">
			<form action="" method="post" enctype="multipart/form-data">
				<section class="panel">
                    <div class="panel-body" style="padding-top: 10px">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#general">Informations générales</a></li>
                            <li><a data-toggle="tab" href="#reseauxsociaux">Réseaux sociaux </a></li>
                            <li><a data-toggle="tab" href="#inscription">Inscriptions</a></li>
                        </ul>
                    </div>
                 </section>
                <div class="tab-content">
					<div class="tab-pane fade active in" id="general">
						<div class="col-md-6">
							<div class="panel panel-default">
								<div class="panel-heading">
									Informations du site
								</div>
								<div class="panel-body">
									<div class="form-group">
										<label>Nom du site</label>
										<input class="form-control" type="text" name="nomSite" placeholder="Titre" value="<?php echo $data["nomSite"] ?>" required/>
									</div>
									
									<div class="form-group">
										<label>Logo</label>
										<input class="form-control" type="file" name="logoSite"/>
										<img class="appercuLogoSite" src="<?php echo $this->get_repositoryLink().$this->paramSite["logo"] ?>">
									</div>
									
									<div class="form-group">
										<label>Footer</label>
										<input class="form-control" type="text" name="footer" placeholder="Footer" value="<?php echo $data["footer"] ?>" />
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="panel panel-default">
								<div class="panel-heading">
									Maintenance
								</div>
								<div class="panel-body">
									<div class="form-group">
									  <label>Mode maintenance</label>
									  <div class="col-md-12">
									  	<label class="col-md-2 col-md-offset-2"><input type="radio" name="maintenance" value="1" <?php if($data["maintenance"] == 1) echo "checked"; ?>>on</label>
									  	<label class="col-md-2 col-md-offset-2"><input type="radio" name="maintenance" value="0" <?php if($data["maintenance"] == 0) echo "checked"; ?>>off</label>
									  </div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="panel panel-default">
								<div class="panel-heading">
									Mails
								</div>
								<div class="panel-body">
									<div class="form-group">
									  <label>Activer les mails</label>
									  <div class="col-md-12">
									  	<label class="col-md-2 col-md-offset-2"><input type="radio" name="mail_active" value="1" <?php if($data["mail_active"] == 1) echo "checked"; ?>>on</label>
									  	<label class="col-md-2 col-md-offset-2"><input type="radio" name="mail_active" value="0" <?php if($data["mail_active"] == 0) echo "checked"; ?>>off</label>
									  </div>
									</div>
									<div class="form-group">
										<label>Mail administrateur</label>
										<input class="form-control" type="text" name="mail_admin" placeholder="Titre" value="<?php echo $data["mail_admin"] ?>" required/>
									</div>
									<div class="form-group">
										<label>Mail affiché</label>
										<input class="form-control" type="text" name="mail_submit" placeholder="Titre" value="<?php echo $data["mail_submit"] ?>" required/>
									</div>
									<div class="form-group">
										<label>Nom expéditeur</label>
										<input class="form-control" type="text" name="mail_nom_expediteur" placeholder="Titre" value="<?php echo $data["mail_nom_expediteur"] ?>" required/>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="reseauxsociaux">
						<div class="col-md-6">
							<div class="panel panel-default">
								<div class="panel-heading">
									Réseaux sociaux
								</div> 
								<div class="panel-body">
									<div class="form-group">
										<label>Twitter</label>
										<div>
											<div class="col-md-3"><span>www.twitter.com/</span></div>
											<div class="col-md-9">
												<input class="form-control" type="text" name="twitter" placeholder="Twitter" value="<?php echo $data["twitter"] ?>"/>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label>Facebook</label>
										<div>
											<div class="col-md-3"><span>www.facebook.com/</span></div>
											<div class="col-md-9">
												<input class="form-control" type="text" name="facebook" placeholder="Facebook" value="<?php echo $data["facebook"] ?>"/>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label>Instagram</label>
										<div>
											<div class="col-md-3"><span>www.instagram.com/</span></div>
											<div class="col-md-9">
												<input class="form-control" type="text" name="instagram" placeholder="Instagram" value="<?php echo $data["instagram"] ?>"/>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="inscription">
						<div class="col-md-6">
							<div class="panel panel-default">
								<div class="panel-heading">
									Inscriptions
								</div> 
								<div class="panel-body">
									<div class="form-group">
									  <label>Activer les inscriptions</label>
									  <div class="col-md-12">
									  	<label class="col-md-2 col-md-offset-2"><input type="radio" name="inscription_active" value="1" <?php if($data["inscription_active"] == 1) echo "checked"; ?>>on</label>
									  	<label class="col-md-2 col-md-offset-2"><input type="radio" name="inscription_active" value="0" <?php if($data["inscription_active"] == 0) echo "checked"; ?>>off</label>
									  </div>
									</div>
									<div class="form-group">
										<label>Type de compte par défaut</label>
									    <div class="col-md-12">
										    <select name="inscription_default_userType" class="form-control">
											     <?php foreach ($data["liste_user_type"] as $usertype){
											     	$selected = "";
											     	if( $usertype->get_idtype() == $data["inscription_default_userType"]){
											     		$selected = "selected";
											     	}
											     	echo "<option value=".$usertype->get_idtype()." ".$selected.">".$usertype->get_intitule()."</option>";
											     }
											     ?>
										    </select>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>	
				<div class="col-md-12">
					<div class="form-group">
						<button type="submit" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Modifier</button>
					</div>
				</div>
			</form>
		</div>
		<?php
	}
	
	
	
}
?>