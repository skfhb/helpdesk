<?php
//---------------------------------------------------------------//
//	Projet 		: Task Manager									 //
//	Fichier 	: createtaskhp 								 	 //
//  Description : Page de cr�ation de t�ches				     //
//	Auteur 		: Herv� Bordeau									 //
// 	Date 		: 11/04/2013							     	 //
//---------------------------------------------------------------//
//Derni�re modif le 11/04/2013 par HB

	header('Content-Type: text/html; charset=iso-8859-1');
	//- la d�finition des constantes de l'ensemble de l'application
	include("include/cst.php");
	//- la gestion de la couche d'acc�s aux donn�es
	include("include/dal.php");
	//- les fonctions outil
	include("include/tools.php");
	
	//Ouverture connexion � la DB
	$c = openConnection();
?>
	<div id="headerTask">
	<!-- Gestion design header en CSS -->
		<div id="idtask">
			Cr�ation d'une t�che
		</div>
	</div>
	<div id="contentTask">
		<form id="newTaskForm" enctype="multipart/form-data" action="taskWrite.php" method="post" target="taskWrite">
			<table>
				<tr>
					<td>
						Nom : <input type="text" id="taskname" name="taskname" onchange="this.style.backgroundColor = '#FFFFFF';"/>
					</td>
					<td style="text-align:right;">
						Statut : 
						<?php
						//R�cup liste des statuts
						$stats = execSQL($c, 'SELECT * FROM TAMGSTAT');
						echo '<img src="'._IMG_STAT.'1.png" id="imgstat" width="16" height="16" />';
						echo '<select id="selectStat" name="selectstat" style="width:150px;" onchange="document.getElementById(\'imgstat\').src=\''._IMG_STAT.'\' + document.getElementById(\'selectStat\').options[document.getElementById(\'selectStat\').selectedIndex].value + \'.png\'">';
						//Rempli le select
						while (odbc_fetch_row($stats))
						{
							echo '<option value="'.odbc_result($stats, 'CODSTS').'">'.trim(odbc_result($stats, 'LBLSTS')).'</option>';
						}
						echo '</select>';
						?>
					</td>
				</tr>
			</table>
			<br />
			<br />
			<table>
				<tr>
					<td>
						Type : 
						<?php
						//R�cup liste des types de t�che
						$typts = execSQL($c, 'SELECT * FROM TAMGTYPT');
						
						echo '<select id="selectTypt" name="selecttypt">';
						//Rempli le select
						while (odbc_fetch_row($typts))
						{
							echo '<option value="'.odbc_result($typts, 'CODTYPT').'">'.odbc_result($typts, 'LBLTYPT').'</option>';
						}
						echo '</select>';
						?>
					</td>
					<td style="text-align:right;">
						Application : 
						<?php
						//R�cup liste des applis
						$apps = execSQL($c, 'SELECT * FROM TAMGAPPL');
						
						echo '<select id="selectApp" name="selectapp" style="width:150px;">';
						echo '<option value="none"></option>';
						//Rempli le select
						while (odbc_fetch_row($apps))
						{
							echo '<option value="'.odbc_result($apps, 'CODAPP').'">'.odbc_result($apps, 'NAMAPP').'</option>';
						}
						echo '</select>';
						?>
					</td>
				</tr>
			</table>
			<br />
			<br />
			<input type="checkbox" name="taskurg" id="taskurg" /> T�che urgente
			<br />
			<br />
			�ch�ance au : <input type="text" name="dateecheance" id="datepicker" />
			<br />
			<br />
			Destinataire(s) : 
			<br />
			<br />
			<table style="border:0px;">
				<tr>
					<td>
						<?php
						//R�cup liste des types de t�che
						$users = execSQL($c, 'SELECT * FROM TAMGUSER ORDER BY NAMUSER');
						
						//Sur changement de valeur, charge les patchs li�s � l'appli choisie
						echo '<select id="selectDest" style="width:100px;">';
						//Rempli le select
						while (odbc_fetch_row($users))
						{
							echo '<option value="'.trim(odbc_result($users, 'CODUSER')).'">'.trim(odbc_result($users, 'NAMUSER')).'</option>';
						}
						echo '</select>';
						?>
					</td>
					<td>
						<input type="button" value="=>" onclick="addDest();" />
						<br />
						<input type="button" value="<=" onclick="removeDest();" />
					</td>
					<td id="finalDest">
					</td>
				</tr>
			</table>
			<br />
			<br />
			<input type="submit" value="Cr�er la t�che" style="width:100%;" onclick="validNewTask();"/>
		</form>
		<iframe style="height:100px;border:0px;" name="taskWrite" seamless></iframe>
	</div>