<?php
//-----------------------------------------------------------//
//	Projet 		: Task Manager								 //
//	Fichier 	: appli.php 								 //
//  Description : Page de gestion des applications			 //
//	Auteur 		: Herv� Bordeau								 //
// 	Date 		: 14/02/2013							     //
//-----------------------------------------------------------//
//Derni�re modif le 14/02/2013 par HB
	
	header('Content-Type: text/html; charset=iso-8859-1');
	//- la d�finition des constantes de l'ensemble de l'application
	include("include/cst.php");
	//- la gestion de la couche d'acc�s aux donn�es
	include("include/dal.php");
	
	//Charge les applis
	echo '<div id="contentApplis">';
	include('getapplis.php');
	echo '</div>';
	
	echo '<br /><br /><div id="newappli"></div>';
	echo '<input type="button" value="Ajouter une nouvelle application" onclick="newAppli()" />';
?>