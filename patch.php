<?php
//-----------------------------------------------------------//
//	Projet 		: Task Manager								 //
//	Fichier 	: patch.php 								 //
//  Description : Page de gestion des patchs				 //
//	Auteur 		: Herv� Bordeau								 //
// 	Date 		: 12/02/2013							     //
//-----------------------------------------------------------//
//Derni�re modif le 12/02/2013 par HB
	
	header('Content-Type: text/html; charset=iso-8859-1');
	//- la d�finition des constantes de l'ensemble de l'application
	include("include/cst.php");
	//- la gestion de la couche d'acc�s aux donn�es
	include("include/dal.php");
	
	//Ouverture connexion � la DB
	$c = openConnection();
	
	//R�cup liste des applis
	$applis = execSQL($c, 'SELECT * FROM TAMGAPPL');
	
	echo 'S�lectionner l\'application concern�e : ';
	//Sur changement de valeur, charge les patchs li�s � l'appli choisie
	echo '<select id="selectAppli" onchange="loadPatchs()">';
	//Rempli le select
	while (odbc_fetch_row($applis))
	{
		echo '<option value="'.odbc_result($applis, 'CODAPP').'">'.odbc_result($applis, 'NAMAPP').'</option>';
	}
	echo '</select>';
	echo '<br /><br />';
	//Premier chargement : charge les patchs de l'appli par d�faut
	echo '<div id="contentPatchs">';
	include('getpatchs.php');
	echo '</div>';
	
	echo '<br /><br /><div id="newpatch"></div>';
	echo '<input type="button" value="Ajouter un nouveau patch" onclick="newPatch()" />';
	
	//Ferme connexion � la DB
	closeConnection($c);

?>