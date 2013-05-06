<?php
//-----------------------------------------------------------//
//	Projet 		: Task Manager								 //
//	Fichier 	: prio.php 								 	 //
//  Description : Page de gestion des priorités				 //
//	Auteur 		: Hervé Bordeau								 //
// 	Date 		: 14/02/2013							     //
//-----------------------------------------------------------//
//Dernière modif le 14/02/2013 par HB
	
	header('Content-Type: text/html; charset=iso-8859-1');
	//- la définition des constantes de l'ensemble de l'application
	include("include/cst.php");
	//- la gestion de la couche d'accès aux données
	include("include/dal.php");
	
	//Charge les prios
	echo '<div id="contentPrios">';
	include('getprios.php');
	echo '</div>';
	
	echo '<br /><br /><div id="newprio"></div>';
	echo '<input type="button" value="Ajouter un nouveau degré" onclick="newPrio()" />';
?>