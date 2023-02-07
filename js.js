let input=document.querySelectorAll("#input");
let max=input.length-1;
load.style.display="none";
body.style.display='none';
recherche.style.display='none';
plu_div.style.display='none';
let paroisse="";
plus.onclick=pl;
plu_div.onclick=pl;
function pl(){
	if(plu.style.left=='-250px') {
		plus.src='../emplois de temps/moins.png';
		plu.style.left='0px';
		plu_div.style.display=""
	}
	else{
		plus.src='../emplois de temps/plus.png';
		plu.style.left='-250px';
		plu_div.style.display="none"
	}
}
let tab=document.querySelectorAll(".table");
function imprimer(a=tab3){
	let w=window.innerWidth;
	tab.forEach(e=>{
		e.style.width="95%"
		e.style.textAlign='center';
	});
	// alert("cool");
	html2pdf(a,{filename:'Sat_CE_'+no(paroisse)+'.pdf'});
	let p="12px";
	if(w<=600) p="8px";
	if(w<=500) p="5px";
	setTimeout(()=>{tab.forEach(e=>e.style.width="800px")} ,2000);
}
// expression valide
function valide(){
	let t=true;
	for(let i=0;i<8;i++){
		if(input[i].value==""){
			t=false;break
		}
	}
	return t;
}
// genere ce que je dois envoyer
let nom=["date","lecon","ef_g","ef_f","ef_m","of_g","of_f","of_m","m_p","ob"];
function send(){
	let s="";
	for(let i=0;i<input.length;i++){
		s+=nom[i]+"="+encodeURIComponent(acent(input[i].value));
		if(i<input.length-1) s+="&";
	}
	return s;
}
// les acents 
function acent(a){
	a=a.toString();
	a=a.split("'").join("@");
	a=a.split("-").join("$");
	a=a.split("=").join("~");
	return a;
}
//efface
function efface(){
	input.forEach(e=>e.value="");
}
let xml=new XMLHttpRequest();
function envoi(a){
	active=false;
	if(valide()){
		xml.onreadystatechange=()=>{
			if(xml.readyStates===XMLHttpRequest.DONE){
				if(xml.status==200){
					tab3.innerHTML=xml.responseText;
					appel();
					// active()
				}
				else{
					alert("error");
				}
			}
		}
		xml.open("POST","traitement.php",true);
		xml.setRequestHeader("content-type",'application/x-www-form-urlencoded');
		xml.send(`${a}`);
		load.style.display="";
		setTimeout(appel,3000);
	}
	else{
		smooth();
	}
}
tab3.innerHTML="";
// appel au server
let active_dis=true;
function appel(){
	tab3.innerHTML="";
	xml.onreadystatechange=()=>{
		if(xml.readyState===XMLHttpRequest.DONE){
			if(xml.status==200){
				tab3.innerHTML=xml.responseText;
				load.style.display="none";
				tab=document.querySelectorAll(".table");
				info=document.querySelectorAll("#info");
				active=true;
			}
			else{
				load.style.display="none";
				alert("erreur");
			}
		}
	}
	xml.open("POST","action.php",true);
	xml.setRequestHeader("content-type",'application/x-www-form-urlencoded');
	xml.send(`action=appel`);
	tab=document.querySelectorAll("table");
	// alert(xml.responseText);
}
// action en base de donner
function action(i,j){
	let ac=prompt("Choisisez une action pour la journe du "+j+" \n1 pour supprimer en base de donné\n2 pour copier dans les champs à remplir");
	let post="";
	switch(ac){
		case "1" : post="supprimer";break; 
		case "2" : post="copier";break;
		default: alert("nous comprenons pas l'action "+ac);
	}
	if(post!=""){
		xml.open("POST","action.php",true);
		xml.setRequestHeader("content-type",'application/x-www-form-urlencoded');
		xml.send(`action=${post}&id=${i}`);
		load.style.display=""
		xml.onreadystatechange=()=>{
			if(xml.readyState===XMLHttpRequest.DONE){
				if(xml.status==200){
					if(post=="copier"){
						copier(xml.responseText);
						smooth();
						load.style.display="none";
					}
						if(post=="supprimer") appel();
				}
				else{
					load.style.display="none";
					alert("erreur");
				}
			}
		}
	}
}
function smooth(){
	window.scrollTo(0,0,'smooth');
}
// fonction pour copier
function copier(a){
	a=a.split("@");
	for(let i=0;i<a.length-1;i++){
		input[i].value=a[i];
	}
}

// envoi des informations
form.onsubmit=(e)=>{
	e.preventDefault();
	envoi(send());
}
// authentification
authen.onsubmit=(e)=>{
	e.preventDefault();
	authentification(string(au[0].value),string(au[1].value));
}
// inscription
inscrip.onsubmit=(e)=>{
	e.preventDefault();
	enregistrement(string(ins[0].value),string(ins[1].value));
}
// sans_carctere
let caract=[" ","+","=","-","*"];
function string(a){
	a=a.toString();
	let t=false;
	let max=a.length-1;
	s="";
	for(let i=max;i>=0;i--){
		if(a[max]==" "){
			max--;
			continue;
		}
		s=a[i].toString()+s;
	}
	a=s;
	for(let i=0;i<caract.length;i++){
		if(a.indexOf(caract[i])!=-1){
			a=a.split(caract[i]).join("_");
		}
	}
	return a.toLowerCase();
}
// verifier l'authentification
function authentification(a,b){
	error.style.display='none';
	load.style.display="";
	xml.onreadystatechange=()=>{
		if(xml.readyState===XMLHttpRequest.DONE){
			if(xml.status==200){
				let a=xml.responseText;
				switch(a){
					case 'a': 
						error.style.display='';
						in_error.innerHTML="Ce compte N'existe pas";
					break;
					case 'b' : 
						paroisse=au[0].value;
						body.style.display="";
						// appel();
						localStorage.setItem("code",au[0].value+"@"+au[1].value);
						inf.innerHTML="info du culte de "+paroisse;
						if(active_dis){
							id();
							appel_dis();
							appel_mes();
							active_dis=false;
						}
						appel();

						dis();
					break;
					case 'c': 
						error.style.display='';
						in_error.innerHTML="Compte Bloquer Contacter par Whatsapp Le +237 698508833 Pour le debloquage";
					break;
				}
				load.style.display="none";
			}
			else{
				alert("erreur");
			}
		}
	}
	xml.open("POST","action.php",true);
	xml.setRequestHeader("content-type",'application/x-www-form-urlencoded');
	xml.send(`action=authentification&nom=${a}&code=${b}`);
}
// retourne le vrai non en base de donné
function no(a){
	return a.split("_").join(" ").toLowerCase();
}
// enregistrement
function enregistrement(a,b){
	error.style.display='none';
	load.style.display="";
	xml.onreadystatechange=()=>{
		if(xml.readyState===XMLHttpRequest.DONE){
			if(xml.status==200){
				if(xml.responseText=="@"){
					error.style.display='';
					in_error.innerHTML="Compte déja existant";
				}
				else{
					body.style.display="";
					inf.innerHTML="Info du culte de "+no(xml.responseText);
					localStorage.setItem("code",ins[0].value+"@"+ins[1].value);
					if(active_dis){
						id();
						appel_dis()
						appel_mes();
					}
					appel();
					dis();
					
				}
				load.style.display="none";
			}
			else{
				alert("erreur");
			}
		}
	}
	xml.open("POST","action.php",true);
	xml.setRequestHeader("content-type",'application/x-www-form-urlencoded');
	xml.send(`action=enregistrer&nom=${a}&code=${b}`);
}
// disparaitre les auth et ins

function dis(){
	inscription.style.display="none";
	authenti.style.display="none";
	au[0].value="";
	au[1].value="";
	ins[0].value="";
	ins[1].value="";
}
// recherche par dates
let info="";
function search(a){
	let s="";
	for(let i=0;i<info.length;i+=7){
		if(info[i].innerHTML==a.value){
			s+="<tr>";
			for(let j=i;j<i+7;j++){
				let p="";
				if(j==i+2||j==i+3||j==i+4) p="class='o_col'";
				s+="<td "+p+">"+info[j].innerHTML+"</td>";
			}
			s+="</tr>";
		}
	}
	if(s!="") s="<table class='table' style='width:800px'>"+titre+s+"</table>";
	else s="aucun resultat";
	resultat.innerHTML=s;
}
let titre=`
	<tr>
		<td>Date</td>
		<td>Titre de la lecons</td>
		<td class="col">
			<table class="tab">
				<tr><td colspan="4" class="o_col">effectifs</td></tr>
				<tr><td class="o_col">EG</td><td class="o_col">Ef</td><td class="o_col">EM</td><td>T</td></tr>
			</table>
		</td>
		<td class="col">
			<table class="tab">
				<tr><td colspan="4">offrandes</td></tr>
				<tr><td class="o_col">OG</td><td class="o_col">Of</td><td class="o_col">OM</td><td class="o_col">T</td></tr>
			</table>
		</td>
		<td class="col">
			<table class="tab">
				<tr><td colspan="4">Ratio</td></tr>
				<tr><td class="o_col">Re</td><td class="o_col">ROE</td><td class="o_col">ROM</td></tr>
			</table>
		</td>
		<td>Noms moniteurs presents</td>
		<td>Observation</td>
	</tr>
`;
// partie discution 

function envoi_dis(){
	let dat=new Date();
	dat=dat.getDate()+"/"+(dat.getMonth()+1)+"/"+dat.getFullYear()+" à "+dat.getHours()+":"+dat.getMinutes()+":"+dat.getSeconds();
	load.style.display="";
	xml.onreadystatechange=()=>{
		if(xml.readyState===XMLHttpRequest.DONE){
			if(xml.status==200){
				appel_dis();
			}
			else {
				load.style.display="none";
				alert("erreur");
			}
		}
	}
	xml.open("POST","action.php",true);
	xml.setRequestHeader("content-type",'application/x-www-form-urlencoded');
	xml.send(`action=en_discution&pseudo=${encodeURIComponent(commen[0].value)}&message=${encodeURIComponent(commen[1].value)}&date=${dat}`);

}
commentaire.onsubmit=(e)=>{
	e.preventDefault();
	envoi_dis();
}
function appel_dis(){
	xml.onreadystatechange=()=>{
		if(xml.readyState===XMLHttpRequest.DONE){
			if(xml.status==200){
				load.style.display="none";
				aff_commentaire.innerHTML+=xml.responseText;
			}
			else{
				alert("erreur");
			}
		}
	}
	xml.open("POST","action.php",false);
	xml.setRequestHeader("content-type",'application/x-www-form-urlencoded');
	xml.send("action=ap_discution");
}
function id(){
	xml.open("POST","id.php",false);
	xml.setRequestHeader("content-type",'application/x-www-form-urlencoded');
	xml.send();
}
let temps=0;
let active=true;
function appel_mes(){
	temps++;
	if(temps%600==0) {
		if(active) appel_dis();
	}
	requestAnimationFrame(appel_mes);
}
// localStorage.removeItem("code")
if(localStorage.getItem("code")!=null){
	let a=localStorage.getItem("code").split("@");
	// alert(a);
	au[0].value=a[0];
	au[1].value=a[1];
	authentification(string(a[0]),string(a[1]));
}