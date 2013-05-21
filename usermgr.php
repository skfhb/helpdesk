<?php
//---------------------------------------------------------------//
//	Projet 		: Task Manager									 //
//	Fichier 	: usermgr.php 								 	 //
//  Description : Page de gestion des utilisateurs			     //
//	Auteur 		: Herv� Bordeau									 //
// 	Date 		: 16/05/2013							     	 //
//---------------------------------------------------------------//
//Derni�re modif le 16/05/2013 par HB
	
	header('Content-Type: text/html; charset=iso-8859-1');
	//- la d�finition des constantes de l'ensemble de l'application
	include("include/cst.php");
	//- la gestion de la couche d'acc�s aux donn�es
	include("include/dal.php");
	
	//Ouverture connexion � la DB
	$c = openConnection();
	
	if (isset($_POST['option']) && $_POST['option'] != '')
	{
		if ($_POST['option'] == 'setadr')
		{
			//Update en DB
			$stmt = odbc_prepare($c, 'UPDATE DEVTAMG.TAMGUSER SET ADRUSER = \''.$_POST['attr'].'\' WHERE CODUSER = \''.$_POST['id'].'\'');
			$res = odbc_execute($stmt, array(null));
			echo 'adrok';
		}
		elseif ($_POST['option'] == 'setpwd')
		{
			//Update en DB
			$stmt = odbc_prepare($c, 'UPDATE DEVTAMG.TAMGUSER SET PWDUSER = \''."d41d8cd98f00b204e9800998ecf8427e".'\' WHERE CODUSER = \''.$_POST['id'].'\'');
			$res = odbc_execute($stmt, array(null));
			echo 'pwdok';
		}
		elseif ($_POST['option'] == 'setadm')
		{
			//Update en DB
			$stmt = odbc_prepare($c, 'UPDATE DEVTAMG.TAMGUSER SET ADMUSER = '.$_POST['attr'].' WHERE CODUSER = \''.$_POST['id'].'\'');
			$res = odbc_execute($stmt, array(null));
			echo 'admok';
		}
		elseif ($_POST['option'] == 'delusr')
		{
			//Delete en DB
			$stmt = odbc_prepare($c, 'DELETE FROM DEVTAMG.TAMGUSER WHERE CODUSER = \''.$_POST['id'].'\'');
			$res = odbc_execute($stmt, array(null));
			echo 'delok';
		}
		elseif ($_POST['option'] == 'addusr')
		{
			//Insert en DB
			$stmt = odbc_prepare($c, 'INSERT INTO DEVTAMG.TAMGUSER (CODUSER, NAMUSER, PWDUSER, ADMUSER, ADRUSER) VALUES (?, ?, ?, ?, ?)');
			$res = odbc_execute($stmt, array($_POST['id'], $_POST['name'], 'd41d8cd98f00b204e9800998ecf8427e', 0, ''));
			echo 'addok';
		}
	}
?>