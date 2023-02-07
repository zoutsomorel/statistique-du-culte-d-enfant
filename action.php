<?php 
	include "function.php";
	if($_POST["action"]=="enregistrer"){
		$stat->enregistre();
	}
	if($_POST["action"]=="authentification"){
		$stat->authentification();
	}
	if(!isset($_SESSION["nom_connect"])){
		die();
	}
	if($_POST["action"]=="supprimer") {
		$stat->delete($_POST["id"],$_SESSION["nom_connect"]);
	}
	if($_POST["action"]=="ap_discution"){
		$stat->discution();
	}
	if($_POST["action"]=="appel") {
		$post=$stat->appel($_SESSION["nom_connect"]);
		if(count($post)>0){
			$stat->tab($post);
		}
	}
	if($_POST["action"]=="copier"){
		$stat->copier($_POST["id"],$_SESSION["nom_connect"]);
	}
	if($_POST["action"]=="en_discution"){
		$stat->post_discution();
	}
 ?>