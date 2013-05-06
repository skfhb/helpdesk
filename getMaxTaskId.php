<?php
//--------------------------------------------------------------------------//
//	Projet 		: Task Manager								  				//
//	Fichier 	: getMaxTaskId.php 							  				//
//  Description : Page utilisée pour récup dernier ID de tamgtask			//
//	Auteur 		: Hervé Bordeau								  				//
// 	Date 		: 06/05/2013							      				//
//--------------------------------------------------------------------------//
//Dernière modif le 06/05/2013 par HB


		//- la définition des constantes de l'ensemble de l'application
		include("include/cst.php");
		//- la gestion de la couche d'accès aux données
		include("include/dal.php");
	
	//Ouverture connexion à la DB
	$c = openConnection();
		
	//Récup maxID
	$getMaxID = execSQL($c, 'SELECT MAX(CODTASK) FROM TAMGTASK');
	while (odbc_fetch_row($getMaxID))
	{
		$maxID = odbc_result($getMaxID, 1);
	}
	
	echo $maxID;
	
	//Fermeture connexion
	closeConnection($c);
?>