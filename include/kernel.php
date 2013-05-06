<?php
//-----------------------------------------------------------//
//	Projet 		: Task Manager								 //
//	Fichier 	: kernel.php 								 //
//  Description : G�re les fonctions cl�s de l'application   //
//	Auteur 		: Herv� Bordeau								 //
// 	Date 		: 08/02/2013							     //
//-----------------------------------------------------------//
//Derni�re modif le 08/02/2013 par HB
	
?>
<script>
	//Fonction permettant d'afficher une page par AJAX
	function loadPage(url, onLoadedPage)
	{
		ajax(url, "", displayLoadedPage);
		setTimeout("init()", 1000);
	}
	//D�pendance loadPage
	function displayLoadedPage(content)
	{
		document.getElementById("pageBody").innerHTML = content;
	}

</script>