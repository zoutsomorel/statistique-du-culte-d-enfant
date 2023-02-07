<?php 
	include "function.php";
	$post=$stat->appel("galilee");
	if(count($post)>0){
		$stat->tab($post);
	}

 ?>