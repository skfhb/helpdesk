<?php
//---------------------------------------------------------------//
//	Projet 		: Task Manager									 //
//	Fichier 	: parlisttask.php							 	 //
//  Description : Appelle tablisttask et listtask			     //
//	Auteur 		: Hervé Bordeau									 //
// 	Date 		: 21/05/2013							     	 //
//---------------------------------------------------------------//
//Dernière modif le 21/05/2013 par HB

	echo '<div id="tabslisttask">';
	include('tabslisttask.php');
	echo '</div>';
	echo '<div id="listtask">';
	include('listtask.php');
	echo '</div>';
?>