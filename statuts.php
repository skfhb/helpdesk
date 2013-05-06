<?php
//-----------------------------------------------------------//
//	Projet 		: Task Manager								 //
//	Fichier 	: statuts.php 								 //
//  Description : Page de gestion des statuts				 //
//	Auteur 		: Herv� Bordeau								 //
// 	Date 		: 13/02/2013							     //
//-----------------------------------------------------------//
//Derni�re modif le 13/02/2013 par HB
	
	header('Content-Type: text/html; charset=iso-8859-1');
	//- la d�finition des constantes de l'ensemble de l'application
	include("include/cst.php");
	//- la gestion de la couche d'acc�s aux donn�es
	include("include/dal.php");
	
	//Charge les statuts
	echo '<div id="contentStatuts">';
	include('getstatuts.php');
	echo '</div>';
	
	echo '<br /><br /><div id="newstatut"></div>';
	echo '<input type="button" value="Ajouter un nouveau statut" onclick="newStatut()" />';

?>