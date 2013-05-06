<?php
//-----------------------------------------------------------//
//	Projet 		: Task Manager								 //
//	Fichier 	: kernel.php 								 //
//  Description : Gère les fonctions clés de l'application   //
//	Auteur 		: Hervé Bordeau								 //
// 	Date 		: 08/02/2013							     //
//-----------------------------------------------------------//
//Dernière modif le 08/02/2013 par HB
	
?>
<script>
	//Fonction permettant d'afficher une page par AJAX
	function loadPage(url, onLoadedPage)
	{
		ajax(url, "", displayLoadedPage);
		setTimeout("init()", 1000);
	}
	//Dépendance loadPage
	function displayLoadedPage(content)
	{
		document.getElementById("pageBody").innerHTML = content;
	}

</script>