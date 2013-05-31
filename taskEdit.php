<?php
//--------------------------------------------------------------------------//
//	Projet 		: Task Manager								  				//
//	Fichier 	: taskEdit.php	 							  				//
//  Description : Page utilisée en iframe pour upload de tâche via AJAX 	//
//	Auteur 		: Hervé Bordeau								  				//
// 	Date 		: 31/05/2013							      				//
//--------------------------------------------------------------------------//
//Dernière modif le 31/05/2013 par HB
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
	
	$taskid = $_POST['taskid'];
	$typt = $_POST['selecttypt'];
	if (isset($_POST['dateecheance']) && $_POST['dateecheance'] != '')
	{
		$duetask = $_POST['dateecheance'];
	}
	else
	{
		$duetask = date('d.m.Y');
	}
	if (isset($_POST['newurg']))
	{
		$urgtask = '1';
	}
	else
	{
		$urgtask = '0';
	}
	$acttask = '1';
	$lbltask = $_POST['newLbl'];
	$getUser = execSQL($c, 'SELECT * FROM TAMGUSER WHERE NAMUSER LIKE \''.strtoupper($_SESSION['login']).'%\'');
	while (odbc_fetch_row($getUser))
	{
		$coduser = odbc_result($getUser, 'CODUSER');
	}
	if (isset($_POST['newlistaffc']))
	{
		$usersAffc = $_POST['newlistaffc'];
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
	if (isset($_POST['newlistpatc']))
	{
		$patcsAffc = $_POST['newlistpatc'];
		$patcsAffc = explode(';', $patcsAffc);
	}
	if (isset($_POST['newprio']))
	{
		$codprio = $_POST['newprio'];
	}
	else
	{
		$codprio = 2;
	}
	if (isset($_POST['newPub']))
	{
		$pubtask = '0';
	}
	else
	{
		$pubtask = '1';
	}
	$codapp = $_POST['appfilter'];
	$usersDest = $_POST['newlistdest'];
	$usersDest = explode(';', $usersDest);
	$codsts = $_POST['selectstat'];
	$duetask = str_replace('/', '.', $duetask);
	
	//Insert en DB : TAMGTASK
	$stmt = odbc_prepare($c, 'UPDATE TAMGTASK SET LBLTASK = ?, DUETASK = ?, URGTASK = ?, PUBTASK = ?, ACTTASK = ?, CODTYPT = ?, CODPRIO = ?, PARTASK = ? WHERE CODTASK = ?');
	$res = odbc_execute($stmt, array($lbltask, $duetask, $urgtask, $pubtask, $acttask, $typt, $codprio, $partask, $taskid));
	//Insert en DB : TAMGHSTS
	$stmt = odbc_prepare($c, 'INSERT INTO TAMGHSTS (CODTASK, CODSTS, TSTPSTS) VALUES (?, ?, CURRENT_TIMESTAMP)');
	$res = odbc_execute($stmt, array($taskid, $codsts));
	//Insert en DB : TAMGMODF
	$stmt = odbc_prepare($c, 'UPDATE TAMGMODF SET TSTPMOD = CURRENT_TIMESTAMP WHERE CODTASK = ? AND CODUSER = ?');
	$res = odbc_execute($stmt, array($taskid, $coduser));
	//Si appli sélectionnée
	if ($codapp != 'none')
	{
		//Insert en DB : TAMGAPTA
		$stmt = odbc_prepare($c, 'UPDATE TAMGAPTA SET CODAPP = ? WHERE CODTASK = ?');
		$res = odbc_execute($stmt, array(intval($codapp), $taskid));
	}
	//Insert en DB : TAMGDEST
	$stmt = odbc_prepare($c, 'DELETE FROM TAMGDEST WHERE CODTASK = ?');
	$res = odbc_execute($stmt, array($taskid));
	foreach ($usersDest as $user)
	{
		$stmt = odbc_prepare($c, 'INSERT INTO TAMGDEST (CODTASK, CODUSER) VALUES (?, ?)');
		$res = odbc_execute($stmt, array($taskid, $user));
	}
	//Insert en DB : TAMGAFFC
	if (isset($usersAffc) && $usersAffc[0] != '')
	{
		$stmt = odbc_prepare($c, 'DELETE FROM TAMGAFFC WHERE CODTASK = ?');
		$res = odbc_execute($stmt, array($taskid));
		foreach ($usersAffc as $useraffc)
		{
			$stmt = odbc_prepare($c, 'INSERT INTO TAMGAFFC (CODTASK, CODUSER) VALUES (?, ?)');
			$res = odbc_execute($stmt, array($taskid, $useraffc));
		}
	}
	//Insert en DB : TAMGAFFC
	if (isset($patcsAffc) && $patcsAffc[0] != '')
	{
		$stmt = odbc_prepare($c, 'DELETE FROM TAMGPATA WHERE CODTASK = ?');
		$res = odbc_execute($stmt, array($taskid));
		foreach ($patcsAffc as $patcaffc)
		{
			$stmt = odbc_prepare($c, 'INSERT INTO TAMGPATA (CODTASK, CODPATC) VALUES (?, ?)');
			$res = odbc_execute($stmt, array($taskid, $patcaffc));
		}
	}
	
	//Fermeture connexion
	closeConnection($c);
?>