<?php
//---------------------------------------------------------------//
//	Projet 		: Task Manager									 //
//	Fichier 	: createtaskhp 								 	 //
//  Description : Page de cr�ation de t�ches				     //
//	Auteur 		: Herv� Bordeau									 //
// 	Date 		: 11/04/2013							     	 //
//---------------------------------------------------------------//
//Derni�re modif le 11/04/2013 par HB

	
	try
	{
		//Si warning, le g�rer par la fonction "warning_handler"
		set_error_handler("warning_handler", E_WARNING);
		if(session_id() == '')
		{
			session_start();
		}
		
		//envoyer le header
		header('Content-Type: text/html; charset=iso-8859-1');
	}
	catch (Exception $e)
	{
		//Rien � faire, la session a juste d�j� �t� lanc�e
	}
	//Manage le warning du header d�j� envoy�
	function warning_handler($errno, $errstr) 
	{ 
			//Rien � faire, le header est juste d�j� pass�
	}

	
	//header('Content-Type: text/html; charset=iso-8859-1');
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
		<form id="newTaskForm" enctype="multipart/form-data" action="taskWrite.php" method="post" target="taskWrite" onsubmit="return validNewTask();">
		<?php 
			if (isset($_SESSION['isAdm']) && $_SESSION['isAdm'])
			{
				echo '<img src="resources/style/lnk.png" width="16" height="16" />';
				echo 'Li�e � la t�che n� : ';
				echo '<input type="text" name="taskpart" id="taskpart" onkeyup="verifTaskExists();" onkeypress="verifTaskExists();" />';
				echo '<img src="resources/statuts/all.png" id="isTaskPartOk" width="16" height="16" />';
				echo '<br />';
				echo '<br />';
			}
		?>
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
							if (odbc_result($stats, 'CODSTS') == 1 || (isset($_SESSION['isAdm']) && $_SESSION['isAdm']))
							{
								echo '<option value="'.odbc_result($stats, 'CODSTS').'">'.trim(odbc_result($stats, 'LBLSTS')).'</option>';
							}
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
						
						echo '<select id="appfilter" name="appfilter" style="width:150px;" onchange="chgfilterpatc2();">';
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
			<table style="border:0px;">
				<tr>
					<td>
						<input type="checkbox" name="taskurg" id="taskurg" /> T�che urgente
					</td>
					<td>
						<?php
						if (isset($_SESSION['isAdm']) && $_SESSION['isAdm'])
						{
							echo '<div id="filterpatc">';
							echo 'Patch : <select id="patcfilter">';
							echo '<option value="all" id="filteroptall"></option>';
							echo '</select>';
							echo '</div>';
						}
						else
						{
							echo '&nbsp;';
						}
						?>
					</td>
				</tr>
			</table>
			<br />
			<br />
			<?php
				if (isset($_SESSION['isAdm']) && $_SESSION['isAdm'])
				{
					echo '<table style="border:0px;">';
					echo '<tr>';
					echo '<td>';
					//R�cup liste des priorit�s
					$prios = execSQL($c, 'SELECT * FROM TAMGPRIO');
						
					echo 'Priorit� : <select id="selectprio" name="selectprio" style="width:150px;">';
					//Rempli le select
					while (odbc_fetch_row($prios))
					{
						echo '<option value="'.odbc_result($prios, 'CODPRIO').'">'.odbc_result($prios, 'VALPRIO').'</option>';
					}
					echo '</select>';
					echo '</td>';
					echo '<td>';
					echo '<input type="checkbox" name="taskpub" id="taskpub" /> T�che priv�e';
					echo '</td>';
					echo '</tr>';
					echo '</table>';
					echo '<br /><br />';
				}
			?>
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
						//R�cup liste des users
						$users = execSQL($c, 'SELECT * FROM TAMGUSER ORDER BY NAMUSER');
						
						echo '<select id="selectDest" style="width:100px;">';
						//Remplis le select
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
			<?php
				if (isset($_SESSION['isAdm']) && $_SESSION['isAdm'])
				{
				?>
				Affect� � : 
				<br />
				<br />
					<table style="border:0px;">
						<tr>
							<td>
								<?php
								//R�cup liste des users
								$users = execSQL($c, 'SELECT * FROM TAMGUSER WHERE ADMUSER = 1 ORDER BY NAMUSER');
								
								echo '<select id="selectAffc" style="width:100px;">';
								//Remplis le select
								while (odbc_fetch_row($users))
								{
									echo '<option value="'.trim(odbc_result($users, 'CODUSER')).'">'.trim(odbc_result($users, 'NAMUSER')).'</option>';
								}
								echo '</select>';
								?>
							</td>
							<td>
								<input type="button" value="=>" onclick="addAffc();" />
								<br />
								<input type="button" value="<=" onclick="removeAffc();" />
							</td>
							<td id="finalAffc">
							</td>
						</tr>
					</table>
					<br />
					<br />
				<?php
				}
			?>
			<input type="hidden" id="patcNb" name="patcNb" value="" />
			<input type="hidden" id="usersDestStringList" name="usersDestStringList" value="" />
			<input type="hidden" id="usersAffcStringList" name="usersAffcStringList" value="" />
			<input type="submit" value="Cr�er la t�che" style="width:100%;" onclick="return validNewTask();"/>
		</form>
		<iframe style="height:1px;border:0px;" name="taskWrite" seamless></iframe>
	</div>