<?php
//-----------------------------------------------------------//
//	Projet 		: Task Manager								 //
//	Fichier 	: prio.php 								 	 //
//  Description : Page de gestion des priorit�s				 //
//	Auteur 		: Herv� Bordeau								 //
// 	Date 		: 14/02/2013							     //
//-----------------------------------------------------------//
//Derni�re modif le 14/02/2013 par HB
	
	header('Content-Type: text/html; charset=iso-8859-1');
	//- la d�finition des constantes de l'ensemble de l'application
	include("include/cst.php");
	//- la gestion de la couche d'acc�s aux donn�es
	include("include/dal.php");
	
	//Charge les prios
	echo '<div id="contentPrios">';
	include('getprios.php');
	echo '</div>';
	
	echo '<br /><br /><div id="newprio"></div>';
	echo '<input type="button" value="Ajouter un nouveau degr�" onclick="newPrio()" />';
?>