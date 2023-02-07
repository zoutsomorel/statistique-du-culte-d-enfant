<meta charset="utf-8"/>
<?php 
	if(!isset($_POST["method"])){
		header("location:index.html");
		die();
	}
	function appel($nom,$code,$methode){
		echo $nom." ".$code;
		$pdo=new PDO("sqlite:stat.db");
		if($methode=="ajouter"){
			$post=$pdo->query("SELECT ajouter FROM paroisse WHERE nom=\"$nom\" and code=\"$code\"")->fetchAll();
			$ajout=(int) $post[0]["ajouter"]+1;
			$time=time()+60*60*24*20;
			$pdo->query("UPDATE paroisse SET temps=$time,ajouter=$ajout WHERE nom=\"$nom\" and code=\"$code\"");
		}
		else{
			$pdo->query("UPDATE paroisse SET temps=0 WHERE nom=\"$nom\" and code=\"$code\"");
		}
	}
	appel($_POST["nom"],$_POST["code"],$_POST["method"]);


 ?>