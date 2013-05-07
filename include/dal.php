<?php

	//----------------------------------------------//
	//	Projet 	    : Task Manager					//
	//	Fichier     : dal.php 						//
	//  Description : Couche d'accès aux données	//
	//	Auteur 	    : Hervé Bordeau					//
	// 	Date 	    : 07/02/2013					//
	//----------------------------------------------//
	//Dernière modif le 08/02/2013 par HB
	
	//Liste des fonctions
		// string   setConnectionString()
		// string   decipherPass($cipher)
		// resource openConnection()
		// resource execSQL($connection, $sql)			
		// void		closeConnection($connection);
		// int      getNumRows($resource);
	//Fin de liste des fonctions
	
	
	//Prépare la chaîne de connexion pour l'accès à la DB
	function setConnectionString()
	{
		return "DRIVER="._DB_DRIVER.";SYSTEM="._DB_SYSTEM.";DBQ="._DB_LIB;
	}
	
	//Décrypte le mot de passe passé en paramètre
	function decipherPass($cipher)    
	{
        return (trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, _CODIFICATION, $cipher , MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
    }
	
	//Ouvre une connexion à la DB
	function openConnection()
	{
		return odbc_connect(setConnectionString(), _DB_USER, decipherPass(_DB_PASS_CRYPT)); 
	}
	
	//Exécute une requête SQL
	function execSQL($connection, $sql)
	{
		if($connection)
		{
			return odbc_exec($connection, $sql);
		}
	}
	
	//Ferme une connexion à la DB
	function closeConnection($connection)
	{
		odbc_close($connection);
	}
	
	//Compte le nombre de lignes de résultat d'un SELECT
	function getNumRows($result)
	{
		$numRows = 0;
		while (odbc_fetch_row($result))
		{
			$numRows++;
		}
		return $numRows;
	}
?>