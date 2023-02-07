<?php 
	session_start();
 class Stat{
 	public $pdo,$nom=["date","lecon","ef_g","ef_f","ef_m","of_g","of_f","of_m","m_p","ob"];
 	function __construct($pdo){
 		$this->pdo=new PDO($pdo);
 	}
 	// me renvois les colonnes pour la base de donné
 	function colonne($nom,$d=","){
 		$s="";
 		for($i=0;$i<count($nom);$i++){
 			$s.=$nom[$i];
 			if($i<count($nom)-1) $s.=$d;
 		}
 		return $s;
 	}
 	// me renvoi les enregistrements 
 	function enregistrement($nom){
 		$s="";
 		$i=0;
 		$max=count($nom);
 		foreach($nom as $nom){
 			$s.="'".$_POST[$nom]."'";
 			if($i<$max-1) $s.=",";
 			$i++;
 		}

 		return $s;
 	}
 	// requete qui appele tous les element d'un table
 	function appel($a="galilee"){
 		$post="";
 		$query=$this->pdo->query("SELECT * FROM \"$a\"");
 		if($query){
 			$post=$query->fetchAll();
 		}
 		return $post;
 	}
 	function enregistrer($a='galilee'){
 		$s=$this->colonne($this->nom);
 		$val=$this->enregistrement($this->nom);
 		$this->pdo->query("INSERT INTO \"$a\" ($s) VALUES ($val)");
 		// if($query) header("location:index.php");
 		echo $s;
 	}
 	// generer les titres du table
 	function titre(){
 		return '
 			<tr>
				<td>Date</td>
				<td>Titre de la lecons</td>
				<td class="col">
					<table class="ta">
						<tr><td colspan="4" class="o_col">effectifs</td></tr>
						<tr><td class="o_col">EG</td><td class="o_col">Ef</td><td class="o_col">EM</td><td>T</td></tr>
					</table>
				</td>
				<td class="col">
					<table class="ta">
						<tr><td colspan="4">offrandes</td></tr>
						<tr><td class="o_col">OG</td><td class="o_col">Of</td><td class="o_col">OM</td><td class="o_col">T</td></tr>
					</table>
				</td>
				<td class="ratio">
					<table class="ta">
						<tr><td colspan="3">Ratio</td></tr>
						<tr><td class="o_col">Re</td><td class="o_col">ROE</td><td class="o_col">ROM</td></tr>
					</table>
				</td>
				<td>Noms moniteurs presents</td>
				<td>Observation</td>
			</tr>
		';
 	}
 	// genere les lignes du tableau
 	function line_tab($d,$id,$dat){
 		$s="<tr>";
 		for($i=0;$i<count($d);$i++){
 			$p="id='info'";
 			if($i==0) $p.=" onclick='action($id,".$this->acent($dat).")' style='cursor:pointer'";
 			if($i==2||$i==3||$i==4){
 				$v="<tr>";
 				foreach($d[$i] as $e) $v.="<td class='o_col'>".$this->casser($e,$d[1],$d[6])."</td>";
 				$v.="</tr>";
 				$s.="
 					<td $p>
 						<table class='tab'>
 							$v
 						</table>
 					</td>
 				";
 				continue;
 			}
 			$s.="<td $p>".$this->acent(htmlentities($d[$i]))."</td>";
 		}
 		$s.="</tr>";
 		return "$s";
 	}
 	function acent($a){
 		$a=(string) $a;
 		$s="";
 		for($i=0;$i<strlen($a);$i++){
 			if($a[$i]=="@"){
 				$s.="'";
 				continue;
 			}
 			if($a[$i]=="_"){
 				$s.=" ";
 				continue;
 			}
 			if($a[$i]=="~"){
 				$s.="=";
 				continue;
 			}
 			if($a[$i]=="$"){
 				$s.="-";
 				continue;
 			}
 			$s.=$a[$i];
 		}
 		return $s;
 	}
 	// casser les elements
 	function casser($a,$b,$c){
 		$a=(string) $a;
 		$d="";
 		if(strlen($b)>=strlen($c)) $d=$b;
 		if(strlen($b)<strlen($c)) $d=$c;
 		$s="";
 		$t=true;
 		$n="";
 		for($i=0;$i<strlen($a);$i++){
 			if($i%6==0&&$i>0&&$i<strlen($a)-1) $n.="<br/>";
 			$n.=$a[$i];
 		}
 		for($i=0;$i<strlen($d);$i++){
 			if($t==false&&$i%10==0) {
 				$t=true;
 				continue;
 			}
 			if($i%10==0&&$i>0&&$t) {
 				$s.="<br/>";
 				$t=false;
 			}
 		}
 		return $s."".$n."".$s;
 	}
 	// generer le tableau

 	function tab($val){
 		$nom=$this->nom;
 		$ti="<h1 class='table' style='width:800px' align='center'><br/><br/>Tableau Statistique</h1><br/><table align='center' class='table' style='width:800px'>".$this->titre();
 		echo $ti;
 		$p=0;
 		$max=count($val);
 			$ef_g=0;
 			$ef_f=0;
 			$ef_m=0;
 			$of_g=0;
 			$of_f=0;
 			$of_m=0;
 		foreach($val as $val){
 			$ef_g+=(int) $val["ef_g"];
 			$ef_f+=(int) $val["ef_f"];
 			$ef_m+=(int) $val["ef_m"];
 			$of_g+=(int) $val["of_g"];
 			$of_f+=(int) $val["of_f"];
 			$of_m+=(int) $val["of_m"];
 			$no=[];
 			$j=0;
 			$k=0;
 			$t=false;
 			$id=$val["id"];
 			$dat=$val["date"];
 			for($i=0,$total=0;$i<count($nom);$i++){
 				if($i>1&&$i<8){
 					if($k==3){
 						$no[$j][$k]=$total;
 						$k=0;$j++;$total=0;
 					}
 					$total+=(int) $val[$nom[$i]];
 					$no[$j][$k]=$val[$nom[$i]];
 					$k++;
 					$t=true;
 					continue;
 				}
 				if($t){
 					$no[$j][$k]=$total;
 					$t=false;
 					$j++;
 					$total=(int) $val["ef_g"] + (int) $val["ef_f"];
 					$no[$j][0]=(int) (($total/$val["ef_m"])*10)/10;
 					$total_of=(int) $val["of_g"] + (int) $val["of_f"];
 					$no[$j][1]= (int) (($total_of/$total)*10)/10;
 					$no[$j][2]=(int) (($val["of_m"]/$val["ef_m"])*10)/10;
 					$j++;
 				}
 				$no[$j]=$val[$nom[$i]];
 				$j++;
 			}
 			echo $this->line_tab($no,$id,'"'.$dat.'"');
 			$p++;
 			if($p==4&&$p!=0){
 				$p=0;
 				$ef_m=(int) (($ef_m/4)*10)/10;
 				$ef_g=(int) (($ef_g/4)*10)/10;
 				$ef_f=(int) (($ef_f/4)*10)/10;
 				$of_f=(int) ($of_f/4);
 				$of_g=(int) ($of_g/4);
 				$of_m=(int) ($of_m/4);
 				$total_ef=$ef_g+$ef_f;
 				$total_of=$of_g+$of_f;
 				$r_e=(int) (($total_ef/$ef_m)*10)/10;
 				$r_oe=(int) (($total_of/$total_ef)*10)/10;
 				$r_om=(int) (($of_m/$ef_m)*10)/10;
 				echo "
 					<tr>
 						<td colspan='2'>Bilan Mensuel</td>
 						<td class='col'>
 							<table class='tab'>
 								<tr>
 									<td>".$ef_g."</td>
 									<td>".$ef_f."</td>
 									<td>".$ef_m."</td>
 									<td>".($ef_m+$total_ef)."</td>
 								</tr>
 							</table>
 						</td>
 						<td class='col'>
 							<table class='tab'>
 								<tr>
 									<td>".$of_g."</td>
 									<td>".$of_f."</td>
 									<td>".$of_m."</td>
 									<td>".($of_m+$total_of)."</td>
 								</tr>
 							</table>
 						</td>
 						<td class='col'>
 							<table class='tab'>
 								<tr>
 									<td>".$r_e."</td>
 									<td>".$r_oe."</td>
 									<td>".$r_om."</td>
 								</tr>
 							</table>
 						</td>
 					</tr>
 				";
 				echo "</table><div class='html2pdf__page-break'></div>".$ti;
 				$ef_g=0;
 				$ef_g=0;
 				$ef_m=0;
 				$of_g=0;
 				$of_f=0;
 				$of_m=0;
 			}
 		}
 		echo "</table><br/>";
 	}
 	// supprimer un enregistrement
 	function delete($i,$a){
 		$i=(int) $i;
 		$this->pdo->query("DELETE FROM \"$a\" WHERE id=$i");
 	}

 	// copier les informations
 	function copier($i,$a){
 		$i=(int) $i;
 		$post=$this->pdo->query("SELECT * FROM \"$a\" WHERE id=$i")->fetchAll();
 		$s="";
		$nom=["date","lecon","ef_g","ef_f","ef_m","of_g","of_f","of_m","m_p","ob"];
 		for($i=0;$i<count($nom);$i++){
 			$s.=$this->acent($post[0][$nom[$i]])."@";
 		}
		echo $s; 		
 	}
 	// authentification
 	function authentification(){
 		$t="a";
 		$nom=$this->pdo->quote($_POST["nom"]);
 		$code=$this->pdo->quote($_POST["code"]);
 		$post=$this->pdo->query("SELECT * FROM paroisse where nom=$nom and code=$code")->fetchAll();
 		if(count($post)==1){
 			if((int) $post[0]["temps"]>time()){
 				$t="b";
 				$_SESSION["nom_connect"]=$post[0]["nom"].$post[0]["code"];
	 			$_SESSION["nom_paroisse"]=$_POST["nom"];
 			}
 			else $t="c";
 		}
 		echo $t;
 	}
 	// enregistrement

 	function enregistre(){
 		$nom=$this->pdo->quote($_POST["nom"]);
 		$code=$this->pdo->quote($_POST["code"]);
 		$post=$this->pdo->query("SELECT * FROM paroisse WHERE nom=$nom and code=$code")->fetchAll();
 		if(count($post)==0){
 			$query=$this->pdo->prepare("INSERT INTO paroisse (nom,code,temps) VALUES(:nom,:code,:temps)");
 			$time=time()+30*24*60*60*3;
	 		$query->execute([
	 			"nom" => $_POST["nom"],
	 			"code" => $_POST["code"],
	 			"temps" => $time
	 		]);
	 		$_SESSION["nom_connect"]=$_POST["nom"].$_POST["code"];
	 		$_SESSION["nom_paroisse"]=$_POST["nom"];
	 		$a=$_SESSION["nom_connect"];
	 		$this->pdo->query("
	 			CREATE TABLE \"$a\" (
					\"id\"	INTEGER,
					\"date\"	TEXT NOT NULL,
					\"lecon\" TEXT NOt NULL,
					\"ef_g\"	INTEGER NOT NULL,
					\"ef_f\"	INTEGER NOT NULL,
					\"ef_m\"	INTEGER NOT NULL,
					\"of_g\"	INTEGER NOT NULL,
					\"of_f\"	INTEGER NOT NULL,
					\"of_m\"	INTEGER NOT NULL,
					\"m_p\"	TEXT NOT NULL,
					\"ob\"	TEXT NOT NULL,
					PRIMARY KEY(\"id\" AUTOINCREMENT)
				)
	 		");
	 		echo $_SESSION["nom_paroisse"];
	 	}
	 	else{
	 		echo "@";
	 	}
 	}
 	function post_discution(){
 		$query=$this->pdo->prepare("INSERT INTO discution_stat (paroisse,pseudo,date,message) VALUES (:a,:b,:c,:d)");
 		$query->execute([
 			"a" => $_SESSION["nom_paroisse"],
 			"b" => $_POST["pseudo"],
 			"c" => $_POST["date"],
 			"d" => $_POST["message"]
 		]);
 	}
 	function discution(){
 		$s="";
 		$id=(int) $_SESSION["id"];
 		$post=$this->pdo->query("SELECT * FROM discution_stat WHERE id>$id")->fetchAll();
 		if(count($post)>0){
 			$i=0;
 			$max=count($post);
 			foreach($post as $post){
 				$s.="
 					<p>
 					<i>".htmlentities($post["pseudo"])." de ".$this->acent($post["paroisse"])." le ".$post["date"]."</i><br/>
 					".htmlentities($post["message"])."
 					</p>
 				";$i++;
 				if($i==$max) $_SESSION["id"]=$post["id"];
 			}
 		}
 		echo $s;
 	}
 }
 $stat=new Stat("sqlite:stat.db");
 ?>