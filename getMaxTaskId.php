<?php
//--------------------------------------------------------------------------//
//	Projet 		: Task Manager								  				//
//	Fichier 	: getMaxTaskId.php 							  				//
//  Description : Page utilis�e pour r�cup dernier ID de tamgtask			//
//	Auteur 		: Herv� Bordeau								  				//
// 	Date 		: 06/05/2013							      				//
//--------------------------------------------------------------------------//
//Derni�re modif le 06/05/2013 par HB


		//- la d�finition des constantes de l'ensemble de l'application
		include("include/cst.php");
		//- la gestion de la couche d'acc�s aux donn�es
		include("include/dal.php");
	
	//Ouverture connexion � la DB
	$c = openConnection();
		
	//R�cup maxID
	$getMaxID = execSQL($c, 'SELECT MAX(CODTASK) FROM TAMGTASK');
	while (odbc_fetch_row($getMaxID))
	{
		$maxID = odbc_result($getMaxID, 1);
	}
	
	echo $maxID;
	
	//Fermeture connexion
	closeConnection($c);
?>