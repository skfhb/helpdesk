<?php
//---------------------------------------------------------------//
//	Projet 		: Task Manager									 //
//	Fichier 	: qrymgr.php								 	 //
//  Description : Gère les queries pour listtask			     //
//	Auteur 		: Hervé Bordeau									 //
// 	Date 		: 21/05/2013							     	 //
//---------------------------------------------------------------//
//Dernière modif le 21/05/2013 par HB

session_start();

$_SESSION['qry'] = $_POST['qry'];

?>