<?php
//--------------------------------------------------------------------------//
//	Projet 		: Task Manager								  				//
//	Fichier 	: taskWrite.php 							  				//
//  Description : Page utilisée en iframe pour upload de tâche via AJAX 	//
//	Auteur 		: Hervé Bordeau								  				//
// 	Date 		: 14/02/2013							      				//
//--------------------------------------------------------------------------//
//Dernière modif le 14/02/2013 par HB
	session_start();
	header('Content-Type: text/html; charset=iso-8859-1');
	
	//Si on utilise pas la page en include
		//- la définition des constantes de l'ensemble de l'application
		include("include/cst.php");
		//- la gestion de la couche d'accès aux données
		include("include/dal.php");
		//- la gestion de la couche AJAX
		include("include/ajax.php");
	
	//Ouverture connexion à la DB
	$c = openConnection();	
	
	//Récup ID de la dernière tâche
	$getMaxID = execSQL($c, 'SELECT MAX(CODTASK) FROM TAMGTASK');
	while (odbc_fetch_row($getMaxID))
	{
		$maxID = odbc_result($getMaxID, 1)+1;
	}
	
	//Définition des champs
	$lbltask = $_POST['taskname'];
	if (isset($_POST['dateecheance']) && $_POST['dateecheance'] != '')
	{
		$duetask = $_POST['dateecheance'];
	}
	else
	{
		$duetask = date('d.m.Y');
	}
	if (isset($_POST['taskurg']))
	{
		$urgtask = '1';
	}
	else
	{
		$urgtask = '0';
	}
	$acttask = '1';
	$dataskt = date('d.m.Y');
	$codtypt = $_POST['selecttypt'];
	$getUser = execSQL($c, 'SELECT * FROM TAMGUSER WHERE NAMUSER LIKE \''.strtoupper($_SESSION['login']).'%\'');
	if (isset($_POST['usersAffcStringList']))
	{
		$usersAffc = $_POST['usersAffcStringList'];
		$usersAffc = explode(';', $usersAffc);
	}
	if (isset($_POST['taskpart']) && $_POST['taskpart'] != '')
	{
		$partask = $_POST['taskpart'];
	}
	else
	{
		$partask = NULL;
	}
	if (isset($_POST['patcNb']))
	{
		$codpatc = $_POST['patcNb'];
	}
	if (isset($_POST['selectprio']))
	{
		$codprio = $_POST['selectprio'];
	}
	else
	{
		$codprio = 2;
	}
	if (isset($_POST['taskpub']))
	{
		$pubtask = '0';
	}
	else
	{
		$pubtask = '1';
	}
	$codapp = $_POST['appfilter'];
	$usersDest = $_POST['usersDestStringList'];
	$usersDest = explode(';', $usersDest);
	while (odbc_fetch_row($getUser))
	{
		$coduser = odbc_result($getUser, 'CODUSER');
	}
	$codsts = $_POST['selectstat'];
	$duetask = str_replace('/', '.', $duetask);
	
	//Insert en DB : TAMGTASK
	$stmt = odbc_prepare($c, 'INSERT INTO TAMGTASK (CODTASK, LBLTASK, DUETASK, URGTASK, PUBTASK, ACTTASK, DATASKT, CODTYPT, CODPRIO, CODUSER, PARTASK) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
	$res = odbc_execute($stmt, array($maxID, $lbltask, $duetask, $urgtask, $pubtask, $acttask, $dataskt, $codtypt, $codprio, $coduser, $partask));
	//Insert en DB : TAMGHSTS
	$stmt = odbc_prepare($c, 'INSERT INTO TAMGHSTS (CODTASK, CODSTS, TSTPSTS) VALUES (?, ?, CURRENT_TIMESTAMP)');
	$res = odbc_execute($stmt, array($maxID, $codsts));
	//Insert en DB : TAMGMODF
	$stmt = odbc_prepare($c, 'INSERT INTO TAMGMODF (CODTASK, CODUSER, TSTPMOD) VALUES (?, ?, CURRENT_TIMESTAMP)');
	$res = odbc_execute($stmt, array($maxID, $coduser));
	//Si appli sélectionnée
	if ($codapp != 'none')
	{
		//Insert en DB : TAMGAPTA
		$stmt = odbc_prepare($c, 'INSERT INTO TAMGAPTA (CODTASK, CODAPP) VALUES (?, ?)');
		$res = odbc_execute($stmt, array($maxID, intval($codapp)));
	}
	//Si patch sélectionné
	if (isset($codpatc) && $codpatc != 'none' && $codpatc != 'all' && $codpatc != '')
	{
		//Insert en DB : TAMGPATA
		$stmt = odbc_prepare($c, 'INSERT INTO TAMGPATA (CODTASK, CODPATC) VALUES (?, ?)');
		$res = odbc_execute($stmt, array($maxID, intval($codpatc)));
	}
	//Insert en DB : TAMGDEST
	foreach ($usersDest as $user)
	{
		$stmt = odbc_prepare($c, 'INSERT INTO TAMGDEST (CODTASK, CODUSER) VALUES (?, ?)');
		$res = odbc_execute($stmt, array($maxID, $user));
	}
	//Insert en DB : TAMGAFFC
	if (isset($usersAffc) && $usersAffc[0] != '')
	{
		foreach ($usersAffc as $useraffc)
		{
			$stmt = odbc_prepare($c, 'INSERT INTO TAMGAFFC (CODTASK, CODUSER) VALUES (?, ?)');
			$res = odbc_execute($stmt, array($maxID, $useraffc));
		}
	}
	
	//Fermeture connexion
	closeConnection($c);
?>