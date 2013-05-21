<?php
//---------------------------------------------------------------//
//	Projet 		: Task Manager									 //
//	Fichier 	: usergst.php 								 	 //
//  Description : Page de gestion des utilisateurs			     //
//	Auteur 		: Hervé Bordeau									 //
// 	Date 		: 16/05/2013							     	 //
//---------------------------------------------------------------//
//Dernière modif le 16/05/2013 par HB
	
	header('Content-Type: text/html; charset=iso-8859-1');
	//- la définition des constantes de l'ensemble de l'application
	include("include/cst.php");
	//- la gestion de la couche d'accès aux données
	include("include/dal.php");
	//- les fonctions outil
	include("include/tools.php");
	//- la classe de gestion des commentaires
	require "include/classComment.php";
	
	//Ouverture connexion à la DB
	$c = openConnection();
	
	$sqlusr = 'SELECT * FROM TAMGUSER ORDER BY NAMUSER';
	$users = execSQL($c, $sqlusr);
	$parite = false;
	echo '<table width="700" cellspacing="0" cellpadding="10" style="text-align:center;">';
	echo '<td>';
	echo 'ID <input type="text" id="newUserID" />';
	echo '</td><td>';
	echo 'Nom <input type="text" id="newUserName" />';
	echo '</td><td>';
	echo '<img src="resources/style/plus.png" alt="Ajouter " width="24" height="24" style="cursor:pointer;" onclick="addUsr()" />';
	echo '</td>';
	echo '</table>';
	echo '<table width="700" id="usertable" cellspacing="0" cellpadding="10" style="text-align:center;">';
	echo '<tr>';
	echo '<td>';
	echo '<b>Statut</b>';
	echo '</td>';
	echo '<td>';
	echo '<b>Nom</b>';
	echo '</td>';
	echo '<td>';
	echo '<b>Adresse</b>';
	echo '</td>';
	echo '<td>';
	echo '<b>Actions</b>';
	echo '</td>';
	echo '<td>';
	echo '<b>Supprimer</b>';
	echo '</td>';
	echo '</tr>';
	while (odbc_fetch_row($users))
	{
		if ($parite)
		{
			echo '<tr width="600" style="background-color : #FFFFFF;">';
			$parite = false;
		}
		else
		{
			echo '<tr width="600" style="background-color : #AFCBE7;">';
			$parite = true;
		}
		echo '<td>';
		if (odbc_result($users, 'ADMUSER') == 1)
		{
			echo '<img src="resources/style/admin.png" alt="Administrateur " width="24" height="24" />';
		}
		else
		{
			echo '<img src="resources/style/user.png" alt="Utilisateur " width="24" height="24" />';
		}
		echo '</td><td>';
		if (odbc_result($users, 'ADMUSER') == 1)
		{
			echo '<font style="color:#FF0000;text-decoration:underline;font-weight:bold;">'.odbc_result($users, 'NAMUSER').'</font>';
		}
		else
		{
			echo odbc_result($users, 'NAMUSER');
		}
		echo '</td><td>';
		echo '<input type="text" id="'.trim(odbc_result($users, 'CODUSER')).'adr" value="'.trim(odbc_result($users, 'ADRUSER')).'" /><input type="button" value="OK" onclick="changeAddr(\''.trim(odbc_result($users, 'CODUSER')).'\');" />';
		echo '</td><td>';
		echo '<input type="button" id="'.trim(odbc_result($users, 'CODUSER')).'pwd" value="Réinitialiser le mot de passe" style="width:200px;" onclick="reinitPwd(\''.trim(odbc_result($users, 'CODUSER')).'\');" />';
		echo '<br />';
		if (odbc_result($users, 'ADMUSER') == 1)
		{
			echo '<input type="button" id="'.trim(odbc_result($users, 'CODUSER')).'adm" value="Retirer les droits" style="width:200px;" onclick="grantOrRevoke(\''.trim(odbc_result($users, 'CODUSER')).'\', 0);" />';
		}
		else
		{
			echo '<input type="button" id="'.trim(odbc_result($users, 'CODUSER')).'adm" value="Nommer administrateur" style="width:200px;" onclick="grantOrRevoke(\''.trim(odbc_result($users, 'CODUSER')).'\', 1);" />';
		}
		echo '</td><td>';
		echo '<img src="resources/style/cross.png" alt="Supprimer " width="24" height="24" style="cursor:pointer;" onclick="delUsr(\''.trim(odbc_result($users, 'CODUSER')).'\')" />';
		echo '</td>';
	}
	echo '<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
	echo '</table>';
?>