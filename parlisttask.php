<?php
//---------------------------------------------------------------//
//	Projet 		: Task Manager									 //
//	Fichier 	: parlisttask.php							 	 //
//  Description : Appelle tablisttask et listtask			     //
//	Auteur 		: Herv� Bordeau									 //
// 	Date 		: 21/05/2013							     	 //
//---------------------------------------------------------------//
//Derni�re modif le 21/05/2013 par HB

	//Manage le warning du header d�j� envoy�
	if (!function_exists('warning_handler'))
	{
		function warning_handler($errno, $errstr) 
		{ 
				//Rien � faire, le header est juste d�j� pass�
		}
	}
	try
	{
		//Si warning, le g�rer par la fonction "warning_handler"
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
		//Rien � faire, la session a juste d�j� �t� lanc�e
	}
	echo '<div id="tabslisttask">';
	include('tabslisttask.php');
	echo '</div>';
	echo '<div id="listtask">';
	include('listtask.php');
	echo '</div>';
?>