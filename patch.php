<?php
//-----------------------------------------------------------//
//	Projet 		: Task Manager								 //
//	Fichier 	: patch.php 								 //
//  Description : Page de gestion des patchs				 //
//	Auteur 		: Hervé Bordeau								 //
// 	Date 		: 12/02/2013							     //
//-----------------------------------------------------------//
//Dernière modif le 12/02/2013 par HB
	
	header('Content-Type: text/html; charset=iso-8859-1');
	//- la définition des constantes de l'ensemble de l'application
	include("include/cst.php");
	//- la gestion de la couche d'accès aux données
	include("include/dal.php");
	
	//Ouverture connexion à la DB
	$c = openConnection();
	
	//Récup liste des applis
	$applis = execSQL($c, 'SELECT * FROM TAMGAPPL');
	
	echo 'Sélectionner l\'application concernée : ';
	//Sur changement de valeur, charge les patchs liés à l'appli choisie
	echo '<select id="selectAppli" onchange="loadPatchs()">';
	//Rempli le select
	while (odbc_fetch_row($applis))
	{
		echo '<option value="'.odbc_result($applis, 'CODAPP').'">'.odbc_result($applis, 'NAMAPP').'</option>';
	}
	echo '</select>';
	echo '<br /><br />';
	//Premier chargement : charge les patchs de l'appli par défaut
	echo '<div id="contentPatchs">';
	include('getpatchs.php');
	echo '</div>';
	
	echo '<br /><br /><div id="newpatch"></div>';
	echo '<input type="button" value="Ajouter un nouveau patch" onclick="newPatch()" />';
	
	//Ferme connexion à la DB
	closeConnection($c);

?>