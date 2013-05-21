<?php
session_start();
//---------------------------------------------------------------//
//	Projet 		: Task Manager									 //
//	Fichier 	: profil.php								 	 //
//  Description : Page de gestion de profil					     //
//	Auteur 		: Hervé Bordeau									 //
// 	Date 		: 11/04/2013							     	 //
//---------------------------------------------------------------//
//Dernière modif le 11/04/2013 par HB

	header('Content-Type: text/html; charset=iso-8859-1');
	//- la définition des constantes de l'ensemble de l'application
	include("include/cst.php");
	//- la gestion de la couche d'accès aux données
	include("include/dal.php");
	//- les fonctions outil
	include("include/tools.php");
	
	//Ouverture connexion à la DB
	$c = openConnection();
?>
	<div id="headerTask">
	<!-- Gestion design header en CSS -->
		<div id="idtask">
			Gestion de profil
		</div>
	</div>
	<div id="contentTask">
		<?php
			echo 'Nom d\'utilisateur : <b>'.$_SESSION['login'].'</b>';
		?>
		<br />
		<br />
		<u><b>Changer de mot de passe</b></u>
		<table>
			<tr>
				<td style="width:300px;">
					Mot de passe actuel : 
				</td>
				<td>
					<input type="password" name="oldpwd" id="oldpwd" />
				</td>
			</tr>
			<tr>
				<td style="width:300px;">
					Nouveau mot de passe : 
				</td>
				<td>
					<input type="password" name="newpwd" id="newpwd" />
				</td>
			</tr>
			<tr>
				<td style="width:300px;">
					Confirmez le nouveau mot de passe : 
				</td>
				<td>
					<input type="password" name="cnfpwd" id="cnfpwd" />
				</td>
			</tr>
		</table>
		<input type="button" name="changePwd" value="Changer de mot de passe" onclick="changePassword();" />
		<div id="resultChgPwd">
		</div>
	</div>