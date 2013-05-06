<?php
//-----------------------------------------------------------//
//	Projet 		: Task Manager								 //
//	Fichier 	: typt.php 								 	 //
//  Description : Page de gestion des types de tâche		 //
//	Auteur 		: Hervé Bordeau								 //
// 	Date 		: 15/02/2013							     //
//-----------------------------------------------------------//
//Dernière modif le 15/02/2013 par HB
	
	header('Content-Type: text/html; charset=iso-8859-1');
	//- la définition des constantes de l'ensemble de l'application
	include("include/cst.php");
	//- la gestion de la couche d'accès aux données
	include("include/dal.php");
	
	//Charge les types de tâche
	echo '<div id="contentTypts">';
	include('gettypts.php');
	echo '</div>';
	
	echo '<br /><br /><div id="newtypt"></div>';
	echo '<input type="button" value="Ajouter un nouveau type de tâche" onclick="newTypt()" />';
?>