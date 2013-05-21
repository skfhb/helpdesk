<?php
	session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
<?php
	//-----------------------------------------------//
	//	Projet 		: Task Manager					 //
	//	Fichier 	: index.php 					 //
	//  Description : fichier coeur de l'application //
	//	Auteur 		: Hervé Bordeau					 //
	// 	Date 		: 08/02/2013					 //
	//-----------------------------------------------//
	//Dernière modif le 08/02/2013 par HB
	
	//Ajoute les fichiers PHP permettant :
	
	//- la définition des constantes de l'ensemble de l'application
	include("include/cst.php");
	//- la gestion de la couche d'accès aux données
	include("include/dal.php");
	//- la gestion de la couche AJAX
	include("include/ajax.php");
	//- la gestion des fonctions clés de l'application
	include("include/kernel.php");
	//- la couche de requêtes
	include("include/queries.php");
	//- les fonctions outils
	include("include/tools.php");
	//- les fonctions drag & drop
	include("include/dragdrop.php");
	
?>
		<title>
			<?php 
				//Affiche le titre de la fenêtre
				echo _WINDOW_TITLE; 
			?>
		</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<!-- Imports jQuery pour DnD -->
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
		<!-- Fin imports jQuery -->
		<link rel="stylesheet" type="text/css" href="style.css" />
	</head>
	<body>
		<div id="header">
			<!--Gestion du header en CSS-->
		</div>
		<div id="connectBox">
			<?php 
				if (isset($_SESSION['login']))
				{
					echo '<div id="connected">';
					echo 'Bienvenue <b>'.$_SESSION['login'].'</b>';
					echo '<br /><br /><br />';
					echo '<font class="btn" onclick="loadPage(\'profil.php\');"><u>Mon profil</u></font>';
					echo '<br /><br /><br />';
					echo '<font class="btn" onclick="disconnect();"><u>Se déconnecter</u></font>';
					echo '</div>';
				}
				else
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
		</div>
		<div id="menu">
			<?php include('menu.php'); ?>
		</div>
		<div id="pageBody">
			<!-- Gestion du contenu par loadPage(url) -->
			<?php include('first.php'); ?>
		</div>
	</body>
</html>
