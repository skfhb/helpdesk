<?php
//---------------------------------------------------------------//
//	Projet 		: Task Manager									 //
//	Fichier 	: parlisttask.php							 	 //
//  Description : Appelle tablisttask et listtask			     //
//	Auteur 		: Hervé Bordeau									 //
// 	Date 		: 21/05/2013							     	 //
//---------------------------------------------------------------//
//Dernière modif le 21/05/2013 par HB

	//Manage le warning du header déjà envoyé
	if (!function_exists('warning_handler'))
	{
		function warning_handler($errno, $errstr) 
		{ 
				//Rien à faire, le header est juste déjà passé
		}
	}
	try
	{
		//Si warning, le gérer par la fonction "warning_handler"
		set_error_handler("warning_handler", E_WARNING);
		//envoyer le header
		header('Content-Type: text/html; charset=iso-8859-1');
		if(session_id() == '')
		{
			session_start();
		}
	}
	catch (Exception $e)
	{
		//Rien à faire, la session a juste déjà été lancée
	}
	echo '<div id="tabslisttask">';
	include('tabslisttask.php');
	echo '</div>';
	echo '<div id="listtask">';
	include('listtask.php');
	echo '</div>';
?>