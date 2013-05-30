<?php

	//-----------------------------------------------------------//
	//	Projet 		: Task Manager								 //
	//	Fichier 	: cst.php 									 //
	//  Description : Définition des constantes de l'application //
	//	Auteur 		: Hervé Bordeau								 //
	// 	Date 		: 08/02/2013							     //
	//-----------------------------------------------------------//
	//Dernière modif le 08/02/2013 par HB
	
	
	// Constantes relatives à la couche d'accès aux données
		//Nom du driver ODBC pour accès à un AS/400
		define("_DB_DRIVER", "iSeries Access ODBC Driver");
		//Nom de l'AS/400
		define("_DB_SYSTEM", "GALATEA");
		//Nom de la lib
		define("_DB_LIB", "DEVTAMG");
		//Nom de l'utilisateur qui se connecte
		define("_DB_USER", "HB");
		//Mot de passe de l'utilisateur (crypté)
		define("_DB_PASS_CRYPT", "š+qzÅ7˜¡ôßüì&þ™,Ô6˜w‘°~µ‚mùÙZW");  
		//Clé de cryptage
		define("_CODIFICATION", "TAMGCODIF49"); 

	// Constantes relatives aux fonctions de communication AJAX
		//Type du header de la trame à envoyer
		define("_AJAX_HEADER_TYPE",  "Content-type");
		//Contenu du header de la trame à envoyer
		define("_AJAX_HEADER_CONTENT", "application/x-www-form-urlencoded");
		//Méthode d'envoi
		define("_AJAX_METHOD", "POST");
		//Code réponse du serveur si dispo
		define("_AJAX_RESPONSE_SRV", 4);
		//Code réponse HTTP si dispo
		define("_AJAX_RESPONSE_HTTP", 200);
		//Mode de communication : true - asynchrone / false - synchrone
		define("_AJAX_MODE", "true");
		
	// Constantes relatives à l'affichage
		//Nom de la fenêtre
		define("_WINDOW_TITLE", "Task Manager");
		//Chemin des images de style
		define("_IMG_STYLE", "resources/style/");
		//Chemin des images de statut
		define("_IMG_STAT", "resources/statuts/");
		//Hauteur des images de statut
		define("_IMG_STAT_HEIGHT", "16");
		//Largeur des images de statut
		define("_IMG_STAT_WIDTH", "16");
		//Texte lien tâche-appli
		define("_TXT_TASK_APPLI", "Relatif à ");
		//Texte lien tâche-patch
		define("_TXT_TASK_PATCH", "Patch ");
		//Texte dernière modif
		define("_TXT_LASTMOD", "Dernière modification le ");
		//Texte prio
		define("_TXT_PRIO", "Tâche à ");
		//Texte urgence
		define("_TXT_URGENT", "Urgent");
		
	// Constantes fonctionnelles de l'application
		//Application par défaut d'affichage des patchs
		define("_DEFAULT_PATCHS_TOLOAD", 1);
?>