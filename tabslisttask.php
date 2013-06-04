<?php
//---------------------------------------------------------------//
//	Projet 		: Task Manager									 //
//	Fichier 	: tabslisttask.php								 //
//  Description : Page contenant les tabs de listtask		     //
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

	echo '<div class="toollisttask">';
	echo '<div class="activetab" onclick="activateTab(this, \'filter\');">Filtrer</div>';
	echo '<div class="tab" onclick="activateTab(this, \'search\');">Rechercher</div>';
	echo '<div class="tab" onclick="activateTab(this, \'col\');">Choix des colonnes</div>';
	echo '<div class="tab" onclick="activateTab(this, \'raz\');">Remise &agrave; z&eacute;ro</div>';
	echo '</div>';
	echo '<br />';
	echo '<div id="preferenceslisttask">';
	echo '<br />';
	include('optnfilter.php');
	echo '</div>';
	echo '<br />';
	
?>