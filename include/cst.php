<?php

	//-----------------------------------------------------------//
	//	Projet 		: Task Manager								 //
	//	Fichier 	: cst.php 									 //
	//  Description : D�finition des constantes de l'application //
	//	Auteur 		: Herv� Bordeau								 //
	// 	Date 		: 08/02/2013							     //
	//-----------------------------------------------------------//
	//Derni�re modif le 08/02/2013 par HB
	
	
	// Constantes relatives � la couche d'acc�s aux donn�es
		//Nom du driver ODBC pour acc�s � un AS/400
		define("_DB_DRIVER", "iSeries Access ODBC Driver");
		//Nom de l'AS/400
		define("_DB_SYSTEM", "GALATEA");
		//Nom de la lib
		define("_DB_LIB", "DEVTAMG");
		//Nom de l'utilisateur qui se connecte
		define("_DB_USER", "HB");
		//Mot de passe de l'utilisateur (crypt�)
		define("_DB_PASS_CRYPT", "�+qz�7������&��,�6�w��~��m��ZW");  
		//Cl� de cryptage
		define("_CODIFICATION", "TAMGCODIF49"); 

	// Constantes relatives aux fonctions de communication AJAX
		//Type du header de la trame � envoyer
		define("_AJAX_HEADER_TYPE",  "Content-type");
		//Contenu du header de la trame � envoyer
		define("_AJAX_HEADER_CONTENT", "application/x-www-form-urlencoded");
		//M�thode d'envoi
		define("_AJAX_METHOD", "POST");
		//Code r�ponse du serveur si dispo
		define("_AJAX_RESPONSE_SRV", 4);
		//Code r�ponse HTTP si dispo
		define("_AJAX_RESPONSE_HTTP", 200);
		//Mode de communication : true - asynchrone / false - synchrone
		define("_AJAX_MODE", "true");
		
	// Constantes relatives � l'affichage
		//Nom de la fen�tre
		define("_WINDOW_TITLE", "Task Manager");
		//Chemin des images de style
		define("_IMG_STYLE", "resources/style/");
		//Chemin des images de statut
		define("_IMG_STAT", "resources/statuts/");
		//Hauteur des images de statut
		define("_IMG_STAT_HEIGHT", "16");
		//Largeur des images de statut
		define("_IMG_STAT_WIDTH", "16");
		//Texte lien t�che-appli
		define("_TXT_TASK_APPLI", "Relatif � ");
		//Texte lien t�che-patch
		define("_TXT_TASK_PATCH", "Patch ");
		//Texte derni�re modif
		define("_TXT_LASTMOD", "Derni�re modification le ");
		//Texte prio
		define("_TXT_PRIO", "T�che � ");
		//Texte urgence
		define("_TXT_URGENT", "Urgent");
		
	// Constantes fonctionnelles de l'application
		//Application par d�faut d'affichage des patchs
		define("_DEFAULT_PATCHS_TOLOAD", 1);
?>