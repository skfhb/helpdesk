<?php
//---------------------------------------------------------------//
//	Projet 		: Task Manager									 //
//	Fichier 	: typc.php 								 		 //
//  Description : Page de gestion des types de commentaire		 //
//	Auteur 		: Herv� Bordeau									 //
// 	Date 		: 15/02/2013							     	 //
//---------------------------------------------------------------//
//Derni�re modif le 15/02/2013 par HB
	
	header('Content-Type: text/html; charset=iso-8859-1');
	//- la d�finition des constantes de l'ensemble de l'application
	include("include/cst.php");
	//- la gestion de la couche d'acc�s aux donn�es
	include("include/dal.php");
	
	//Charge les types de commentaire
	echo '<div id="contentTypcs">';
	include('gettypcs.php');
	echo '</div>';
	
	echo '<br /><br /><div id="newtypc"></div>';
	echo '<input type="button" value="Ajouter un nouveau type de commentaire" onclick="newTypc()" />';
?>