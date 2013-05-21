<?php
session_start();
//---------------------------------------------------------------//
//	Projet 		: Task Manager									 //
//	Fichier 	: chgpwd.php								 	 //
//  Description : Changement de mot de passe				     //
//	Auteur 		: Herv� Bordeau									 //
// 	Date 		: 11/04/2013							     	 //
//---------------------------------------------------------------//
//Derni�re modif le 11/04/2013 par HB

header('Content-Type: text/html; charset=iso-8859-1');
//- la d�finition des constantes de l'ensemble de l'typtcation
include("include/cst.php");
//- la gestion de la couche d'acc�s aux donn�es
include("include/dal.php");

if (isset($_SESSION['login']) && isset($_POST['oldpwd']) && isset($_POST['newpwd']))
{
	//Ouverture connexion � la DB
	$c = openConnection();	
	
	$sql = 'SELECT * FROM TAMGUSER WHERE NAMUSER = \''.$_SESSION['login'].'\'';
		
	//R�cup user
	$users = execSQL($c, $sql);
					
	$found = false;
					
	while (odbc_fetch_row($users))
	{
		if (trim(odbc_result($users, 'PWDUSER')) == md5($_POST['oldpwd']))
		{
			//Insert en DB
			$stmt = odbc_prepare($c, 'UPDATE DEVTAMG.TAMGUSER SET PWDUSER = \''.md5($_POST['newpwd']).'\' WHERE NAMUSER LIKE \''.strtoupper($_SESSION['login']).'%\'');
			$res = odbc_execute($stmt, array(null));
			echo 'Mot de passe modifi�';
		}
		else
		{
			echo '<font style="color:#FF0000;">Ancien mot de passe erron�</font>';
		}
		$found = true;
	}
	
	if (!$found)
	{
		echo 'Utilisateur non-trouv�';
	}
}
else
{
	echo 'Une erreur s\'est produite';
}
?>