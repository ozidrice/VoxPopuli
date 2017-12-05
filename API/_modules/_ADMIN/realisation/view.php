<?php 
class realisationView extends View{
	
	function __construct(){
		parent::__construct();
		$this->set_titre("Réalisations");
	}

	function get_name(){
		return get_class();
	}
	
	function affiche_page_real($data){
		$filariane = array("/admin/"=>"home","#"=>"Réalisations");
		$this->fil_ariane($filariane);
		?>
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					Listing
				</div>
				<div class="panel-body">
					<div>
						<a href="ajout" class="btn btn-success">
							<i class="fa fa-plus" aria-hidden="true"></i>
							Ajouter
						</a>
						<?php							
						$this->pagination("/admin/realisation/",$data["pagination"]["nb_page"],$data["pagination"]["currentpage"]);
						?>
						<div id="listImage">
							<?php
							//$this->affiche_lignes_img($data["image"]);
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	function affiche_page_ajout($data){
		$filariane = array("/admin/"=>"home","/admin/realisation"=>"Réalisations","#"=>"Créer");
		$this->fil_ariane($filariane);
		$this->form_contenu($data);
	}

	function form_contenu($data){
		?>
		<form action="" method="POST">
			<input type="hidden" name="idContenu" value="<?php echo $data["idContenu"] ?>"/>
			<div class="col-md-6">
				<div class="form-group">
					<label>Titre</i></label>
					<input class="form-control" type="text" name="titre" placeholder="Titre" value="<?php echo $data["titre"] ?>" required/>
				</div>
				<div class="form-group">
					<label>Description</i></label>
					<input class="form-control" type="text" name="description" placeholder="Description" value="<?php echo $data["description"] ?>"/>
				</div>
				<div class="form-group">
					<label>Illustration</i></label>
					<input class="form-control" type="file" name="illustration" placeholder="Illustration"/>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Meta description</i></label>
					<input class="form-control" type="text" name="metadesc" placeholder="Meta description" value="<?php echo $data["metadesc"] ?>"/>
				</div>
				<div class="form-group">
					<label>Meta keywords</i></label>
					<input class="form-control" type="text" name="metakeywords" placeholder="Meta keywords" value="<?php echo $data["metakeywords"] ?>"/>
				</div>
			</div>
			<div class="col-md-12">
				<textarea  class="form-control" name="contenu" cols="" rows="10"><?php echo $data["contenu"] ?></textarea>
			</div>
			<div class="clear"></div>	
			<div class="form-group">
				<button type="submit" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Ajouter</button>
			</div>
		</form> 
		<?php
	}

}
?>