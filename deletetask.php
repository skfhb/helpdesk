<?php
//--------------------------------------------------------------------------//
//	Projet 		: Task Manager								  				//
//	Fichier 	: deletetask.php 							  				//
//  Description : Page utilis�e pour d�sactiver une t�che 					//
//	Auteur 		: Herv� Bordeau								  				//
// 	Date 		: 07/05/2013							      				//
//--------------------------------------------------------------------------//
//Derni�re modif le 07/05/2013 par HB

	//- la d�finition des constantes de l'ensemble de l'application
	include("include/cst.php");
	//- la gestion de la couche d'acc�s aux donn�es
	include("include/dal.php");
	
	//Ouverture connexion � la DB
	$c = openConnection();
		
	//D�sactive t�che
	if (isset($_POST['task']) && $_POST['task'] != '')
	{
		$stmt = odbc_prepare($c, 'UPDATE DEVTAMG.TAMGTASK SET ACTTASK = 0 WHERE CODTASK = ?');
		$res = odbc_execute($stmt, array($_POST['task']));
	}
	
	//Fermeture connexion
	closeConnection($c);
?>