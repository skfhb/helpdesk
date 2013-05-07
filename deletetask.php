<?php
//--------------------------------------------------------------------------//
//	Projet 		: Task Manager								  				//
//	Fichier 	: deletetask.php 							  				//
//  Description : Page utilisée pour désactiver une tâche 					//
//	Auteur 		: Hervé Bordeau								  				//
// 	Date 		: 07/05/2013							      				//
//--------------------------------------------------------------------------//
//Dernière modif le 07/05/2013 par HB

	//- la définition des constantes de l'ensemble de l'application
	include("include/cst.php");
	//- la gestion de la couche d'accès aux données
	include("include/dal.php");
	
	//Ouverture connexion à la DB
	$c = openConnection();
		
	//Désactive tâche
	if (isset($_POST['task']) && $_POST['task'] != '')
	{
		$stmt = odbc_prepare($c, 'UPDATE DEVTAMG.TAMGTASK SET ACTTASK = 0 WHERE CODTASK = ?');
		$res = odbc_execute($stmt, array($_POST['task']));
	}
	
	//Fermeture connexion
	closeConnection($c);
?>