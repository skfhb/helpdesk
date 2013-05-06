<?php
//---------------------------------------------------------------//
//	Projet 		: Task Manager									 //
//	Fichier 	: login.php 								 	 //
//  Description : Page de gestion des connexions utilisateurs    //
//	Auteur 		: Hervé Bordeau									 //
// 	Date 		: 08/03/2013							     	 //
//---------------------------------------------------------------//
//Dernière modif le 08/03/2013 par HB
	
	session_start();
	header('Content-Type: text/html; charset=iso-8859-1');

	//- la définition des constantes de l'ensemble de l'typccation
	include("include/cst.php");
	//- la gestion de la couche d'accès aux données
	include("include/dal.php");
	//- la gestion de la couche AJAX
	include("include/ajax.php");
	
	//Ouverture connexion à la DB
	$c = openConnection();	
	
	if ((isset($_POST['login'])) && ($_POST['login'] != ''))
	{
		if (empty($_POST['pwd']))
		{
			$pwd = '';
		}
		else
		{
			$pwd = $_POST['pwd'];
		}
		if ($_POST['login'] == 'endsession')
		{
			unset($_SESSION['login']);
			unset($_SESSION['isAdm']);
			returnOk();
		}
		else
		{
			$sql = 'SELECT CODUSER, PWDUSER, ADMUSER FROM TAMGUSER WHERE NAMUSER = \''.strtoupper(trim($_POST['login'])).'\'';
			$users = execSQL($c, $sql);
			if (getNumRows($users) != 1)
			{
				returnFail();
			}
			else
			{
				if (trim(odbc_result($users, 'PWDUSER')) == trim(md5($pwd)))
				{
					$_SESSION['login'] = $_POST['login'];
					if (odbc_result($users, 'ADMUSER') == '1')
					{
						$_SESSION['isAdm'] = true;
					}
					else
					{
						$_SESSION['isAdm'] = false;
					}
					returnAccept();
				}
				else
				{
					returnFail();
				}
			}
		}
	}
	
	function returnAccept()
	{
		echo '<div id="connected">';
		echo 'Bienvenue <b>'.strtoupper(trim($_SESSION['login'])).'</b>';
		echo '<br /><br /><br />';
		echo '<font class="btn"><u>Mon profil</u></font>';
		echo '<br /><br /><br />';
		echo '<font class="btn" onclick="disconnect();"><u>Se déconnecter</u></font>';
		echo '</div>';
	}
	
	function returnFail()
	{
		echo '<font style="color:red;">Identifiants incorrects</font><br />';
		echo 'Identifiant : ';
		echo '<input type="text" id="loginConnect" onkeypress="if (event.keyCode == 13) connect();" />';
		echo '<br />';
		echo 'Mot de passe : ';
		echo '<input type="password" id="pwdConnect" onkeypress="if (event.keyCode == 13) connect();" />';
		echo '<br /><br />';
		echo '<img src="resources/style/connect-btn.png" alt="Se connecter" class="btn" onclick="connect();" />';
	}
	
	function returnOk()
	{
		echo 'Identifiant : ';
		echo '<input type="text" id="loginConnect" onkeypress="if (event.keyCode == 13) connect();" />';
		echo '<br />';
		echo 'Mot de passe : ';
		echo '<input type="password" id="pwdConnect" onkeypress="if (event.keyCode == 13) connect();" />';
		echo '<br /><br />';
		echo '<img src="resources/style/connect-btn.png" alt="Se connecter" class="btn" onclick="connect();" />';
	}
?>