<?php
//-----------------------------------------------------------//
//	Projet 		: Task Manager								 //
//	Fichier 	: typt.php 								 	 //
//  Description : Page de gestion des types de t�che		 //
//	Auteur 		: Herv� Bordeau								 //
// 	Date 		: 15/02/2013							     //
//-----------------------------------------------------------//
//Derni�re modif le 15/02/2013 par HB
	
	header('Content-Type: text/html; charset=iso-8859-1');
	//- la d�finition des constantes de l'ensemble de l'application
	include("include/cst.php");
	//- la gestion de la couche d'acc�s aux donn�es
	include("include/dal.php");
	
	//Charge les types de t�che
	echo '<div id="contentTypts">';
	include('gettypts.php');
	echo '</div>';
	
	echo '<br /><br /><div id="newtypt"></div>';
	echo '<input type="button" value="Ajouter un nouveau type de t�che" onclick="newTypt()" />';
?>