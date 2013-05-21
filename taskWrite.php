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
	$pubtask = '1';
	$acttask = '1';
	$dataskt = date('d.m.Y');
	$codtypt = $_POST['selecttypt'];
	$codprio = 2;
	$getUser = execSQL($c, 'SELECT * FROM TAMGUSER WHERE NAMUSER LIKE \''.strtoupper($_SESSION['login']).'%\'');
	$usersDest = $_POST['usersDestStringList'];
	$usersDest = explode(';', $usersDest);
	while (odbc_fetch_row($getUser))
	{
		$coduser = odbc_result($getUser, 'CODUSER');
	}
	$partask = NULL;
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
	//Insert en DB : TAMGDEST
	foreach ($usersDest as $user)
	{
		$stmt = odbc_prepare($c, 'INSERT INTO TAMGDEST (CODTASK, CODUSER) VALUES (?, ?)');
		$res = odbc_execute($stmt, array($maxID, $user));
	}
	
	
	
	//Fermeture connexion
	closeConnection($c);
?>