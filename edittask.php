<?php
//---------------------------------------------------------------//
//	Projet 		: Task Manager									 //
//	Fichier 	: edittask.php 								 	 //
//  Description : Page de modif d'une tâche 			 		 //
//	Auteur 		: Hervé Bordeau									 //
// 	Date 		: 30/05/2013							     	 //
//---------------------------------------------------------------//
//Dernière modif le 30/05/2013 par HB
	
	try
	{
		//Si warning, le gérer par la fonction "warning_handler"
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
		//Rien à faire, la session a juste déjà été lancée
	}
	//Manage le warning du header déjà envoyé
	function warning_handler($errno, $errstr) 
	{ 
			//Rien à faire, le header est juste déjà passé
	}
	
	//- la définition des constantes de l'ensemble de l'application
	include("include/cst.php");
	//- la gestion de la couche d'accès aux données
	include("include/dal.php");
	//- les fonctions outil
	include("include/tools.php");
	//- la classe de gestion des commentaires
	require "include/classComment.php";
	//- la gestion du "clickout"
	include("include/clickout.php");
	
	//Ouverture connexion à la DB
	$c = openConnection();	
	
	//Si aucune tâche de sélectionnée, on sort immédiatement
	if (isset($_GET['id']) && $_GET['id'] != "")
	{
		//Récupération des infos de la tâche passée en argument
		$sql = 'SELECT * FROM TAMGTASK WHERE CODTASK = '.$_GET['id'];
		$tasks = execSQL($c, $sql);
		
		//Tant qu'on trouve des lignes (il n'y en aura, normalment, qu'une)
		while (odbc_fetch_row($tasks))
		{
			//Récupération de toutes les infos concernant la tâche
			$task_ID = odbc_result($tasks, 'CODTASK');
			$task_Lbl = odbc_result($tasks, 'LBLTASK');
			$task_Due = odbc_result($tasks, 'DUETASK');
			$task_Urg = odbc_result($tasks, 'URGTASK');
			$task_Pub = odbc_result($tasks, 'PUBTASK');
			$task_Ask = odbc_result($tasks, 'DATASKT');
			$task_Codtypt = odbc_result($tasks, 'CODTYPT');
			$task_Codprio = odbc_result($tasks, 'CODPRIO');
			$task_Codasker = odbc_result($tasks, 'CODUSER');
			$task_Partask = odbc_result($tasks, 'PARTASK');
			//On fait le lien avec les types de tâche, pour obtenir une chaîne et non un simple ID
			$sql = 'SELECT * FROM TAMGTYPT WHERE CODTYPT = '.$task_Codtypt;
			$typts = execSQL($c, $sql);
			while (odbc_fetch_row($typts))
			{
				$lblTypt = odbc_result($typts, 'LBLTYPT');
			}
			//On fait le lien avec les statuts pour obtenir une chaîne et non un simple ID, après avoir récupéré le statut actuel
			$sql = 'SELECT * FROM TAMGSTAT WHERE CODSTS IN (SELECT CODSTS FROM TAMGHSTS WHERE CODTASK = '.$task_ID.' ORDER BY TSTPSTS DESC FETCH FIRST 1 ROW ONLY)';
			$statut = execSQL($c, $sql);
			while (odbc_fetch_row($statut))
			{
				$activeStatutId = odbc_result($statut, 'CODSTS');
				$activeStatutLbl = odbc_result($statut, 'LBLSTS');
			}
			//On fait le lien avec les utilisateurs pour obtenir le nom du demandeur
			$sql = 'SELECT NAMUSER FROM TAMGUSER WHERE CODUSER =  \''.$task_Codasker.'\'';
			$asker = execSQL($c, $sql);
			while (odbc_fetch_row($asker))
			{
				$user_asker = odbc_result($asker, 'NAMUSER');
			}
			//On fait le lien avec les utilisateurs pour obtenir les noms des destinataires
			$sql = 'SELECT CODUSER, NAMUSER FROM TAMGUSER WHERE CODUSER IN (SELECT CODUSER FROM TAMGDEST WHERE CODTASK = '.$task_ID.')';
			$dests = execSQL($c, $sql);
			$nbDest = 0;
			while (odbc_fetch_row($dests))
			{
				$user_dest[$nbDest] = odbc_result($dests, 'NAMUSER');
				$user_dest_id[$nbDest] = odbc_result($dests, 'CODUSER');
				$nbDest++;
			}
			//On fait le lien avec les utilisateurs pour obtenir les noms des personnes chargées d'effectuer la tâche
			$sql = 'SELECT CODUSER, NAMUSER FROM TAMGUSER WHERE CODUSER IN (SELECT CODUSER FROM TAMGAFFC WHERE CODTASK = '.$task_ID.')';
			$affcs = execSQL($c, $sql);
			$nbAffc = 0;
			while (odbc_fetch_row($affcs))
			{
				$user_affc[$nbAffc] = odbc_result($affcs, 'NAMUSER');
				$user_affc_id[$nbAffc] = odbc_result($affcs, 'CODUSER');
				$nbAffc++;
			}
			//On fait le lien avec les utilisateurs et les modifs pour obtenir le nom du dernier utilisateur ayant modifié la tâche, ainsi que la date
			$sql = 'SELECT NAMUSER, TSTPMOD FROM TAMGUSER, TAMGMODF WHERE CODTASK = '.$task_ID.' AND TAMGUSER.CODUSER = TAMGMODF.CODUSER ORDER BY TSTPMOD DESC';
			$lastmodf = execSQL($c, $sql);
			while (odbc_fetch_row($lastmodf))
			{
				$user_lastmodf = odbc_result($lastmodf, 'NAMUSER');
				//Pour l'affichage, on sépare la date de l'heure
				$time_lastmodf = formatDate(odbc_result($lastmodf, 'TSTPMOD')).'</b> à <b>'.formatTime(odbc_result($lastmodf, 'TSTPMOD'));
			}
			//On regarde quelles applications sont concernées par la tâche
			$sql = 'SELECT CODAPP, NAMAPP FROM TAMGAPPL WHERE CODAPP IN (SELECT CODAPP FROM TAMGAPTA WHERE CODTASK = '.$task_ID.')';
			$apps = execSQL($c, $sql);
			$nbApp = 0;
			//Pour chaque appli
			while (odbc_fetch_row($apps))
			{
				//On récupère le nom et l'id de l'appli
				$app[$nbApp]['name'] = odbc_result($apps, 'NAMAPP');
				$app[$nbApp]['id'] = odbc_result($apps, 'CODAPP');
				//On initialise la variable comptant les patchs de cette appli concernés
				$app[$nbApp]['nbPatch'] = 0;
				//On récupère les patchs en question
				$sql = 'SELECT CODPATC, NAMPATC FROM TAMGPATC WHERE CODAPP IN (SELECT CODAPP FROM TAMGAPPL WHERE NAMAPP = \''.$app[$nbApp]['name'].'\') AND CODPATC IN (SELECT CODPATC FROM TAMGPATA WHERE CODTASK = '.$task_ID.')';
				$patchs = execSQL($c, $sql);
				//Pour chaque patch
				while (odbc_fetch_row($patchs))
				{
					//On stocke le nom du patch dans un tableau héritant de l'appli
					$app[$nbApp][$app[$nbApp]['nbPatch']] = odbc_result($patchs, 'NAMPATC');
					$patc[$app[$nbApp]['nbPatch']] = odbc_result($patchs, 'CODPATC');
					//On ajoute 1 au nombre de patch recensés
					$app[$nbApp]['nbPatch']++;
				}	
				//On ajoute 1 au nombre d'applis recensées
				$nbApp++;
			}
			//On fait le lien avec les valeurs de priorité
			$sql = 'SELECT VALPRIO FROM TAMGPRIO WHERE CODPRIO = '.$task_Codprio;
			$prios = execSQL($c, $sql);
			while (odbc_fetch_row($prios))
			{
				$lblPrio = odbc_result($prios, 'VALPRIO');
			}
		}
	}
?>
<div id="task">
	<div id="headerTask">
	<!-- Gestion design header en CSS -->
		<div id="idtask">
			<?php echo 'Tâche n°'.$task_ID; ?>
		</div>
		<?php 
			echo '<div id="typtask">';
			echo '<form id="newTaskForm" name="modTask" enctype="multipart/form-data" action="taskEdit.php" method="post" target="taskEdit" onsubmit="return validEditTask();">';
			//Récup liste des types de tâche
			$typts = execSQL($c, 'SELECT * FROM TAMGTYPT');
			
			echo '<select id="selectTypt" name="selecttypt">';
			//Rempli le select
			while (odbc_fetch_row($typts))
			{
				if (odbc_result($typts, 'CODTYPT') == $task_Codtypt)
				{
					echo '<option value="'.odbc_result($typts, 'CODTYPT').'" selected>'.odbc_result($typts, 'LBLTYPT').'</option>';
				}
				else
				{
					echo '<option value="'.odbc_result($typts, 'CODTYPT').'">'.odbc_result($typts, 'LBLTYPT').'</option>';
				}
			}
			echo '</select>';
			echo '</div>';
		?>
	</div>
	<?php
		if (isset($_SESSION['isAdm']) && $_SESSION['isAdm'])
		{
			echo '<div id="validmodtask" onclick="updateNewDestUsers();updateNewAffcUsers();updateNewAffcPatcs();validEditTask();setTimeout(function() { loadPage(\'parlisttask.php\'); }, 2000);">';
			echo 'Enregistrer les modifications';
			echo '</div>';
		}
	?>
	<div id="contentTask">
		<div id="parenttask">
			<?php 
			if (isset($task_Partask) && $task_Partask != '')
			{
				echo 'Liée à la tâche <input type="text" name="taskpart" id="taskpart" value="'.$task_Partask.'" onkeypress="verifTaskExists();" onkeyup="verifTaskExists();" /><img src="resources/statuts/all.png" id="isTaskPartOk" width="16" height="16" /><br /><br />';
			}
			else
			{
				echo 'Liée à la tâche <input type="text" name="taskpart" id="taskpart" value="" onkeypress="verifTaskExists();" onkeyup="verifTaskExists();" /><img src="resources/statuts/all.png" id="isTaskPartOk" width="16" height="16" /><br /><br />';
			}
			?>
		</div>
		<?php
			echo '<input type="text" name="newLbl" id="newLbl" style="width:100%;" value="'.trim($task_Lbl).'" /><br /><br />';
			//Récup liste des statuts
			$stats = execSQL($c, 'SELECT * FROM TAMGSTAT');
			echo '<img src="'._IMG_STAT.$activeStatutId.'.png" id="imgstat" alt="" width="'._IMG_STAT_WIDTH.'" height="'._IMG_STAT_HEIGHT.'" />';
			echo '<select id="selectStat" name="selectstat" style="width:150px;" onchange="document.getElementById(\'imgstat\').src=\''._IMG_STAT.'\' + document.getElementById(\'selectStat\').options[document.getElementById(\'selectStat\').selectedIndex].value + \'.png\'">';
			//Rempli le select
			while (odbc_fetch_row($stats))
			{
				if (odbc_result($stats, 'CODSTS') == $activeStatutId)
				{
					echo '<option value="'.odbc_result($stats, 'CODSTS').'" selected>'.trim(odbc_result($stats, 'LBLSTS')).'</option>';
				}
				else
				{
					echo '<option value="'.odbc_result($stats, 'CODSTS').'">'.trim(odbc_result($stats, 'LBLSTS')).'</option>';
				}
			}
			echo '</select><br /><br />';
			
			if ($task_Pub == '1')
			{
				echo '<input type="checkbox" name="newPub" id="newPub" value="Public" /> Spécifique informatique<br /><br />';
			}
			else
			{
				echo '<input type="checkbox" value="Public" name="newPub" id="newPub" checked /><b> Spécifique informatique</b><br /><br />';
			}
			echo 'Demandé par <b>'.$user_asker.'</b> le <b>'.formatDate($task_Ask).'</b><br /><br />';
			echo '<table><tr><td style="text-align:left;">Pour :</td><td style="text-align:left;">Pris en charge par :</td></tr>';
			echo '<tr><td><div id="finalDest">';
			while ($nbDest > 0)
			{
				echo '<div class="userDest" onclick="focusUserDest(this);"><input type="hidden" class="hiddenuser" value="'.$user_dest_id[$nbDest-1].'" />'.$user_dest[$nbDest-1].'</div>';
				$nbDest--;
			}
			echo '</div>';
			echo '<img src="resources/style/cross.png" alt="X" style="cursor:pointer;" width="16" height="16" onclick="rmvNewDest();" />';
			echo '&nbsp;';
			echo '<img src="resources/style/plus.png" alt="+" style="cursor:pointer;" width="16" height="16" onclick="addNewDest();" />';
			echo '&nbsp;';
			echo '<select id="selectnewdest" id="selectnewdest" style="width:180px;">';
			//Récup liste des applis
			$usrs = execSQL($c, 'SELECT * FROM TAMGUSER ORDER BY NAMUSER');
						
			//Rempli le select
			while (odbc_fetch_row($usrs))
			{
				foreach ($user_dest as $usr)
				{
					if (odbc_result($usrs, 'NAMUSER') == $usr)
					{
						$founduser = true;
					}
				}
				if (!$founduser)
				{
					echo '<option value="'.odbc_result($usrs, 'CODUSER').'">'.odbc_result($usrs, 'NAMUSER').'</option>';
				}
				$founduser = false;
			}
			echo '</select>';
			echo '</td><td>';
			echo '<div id="finalAffc">';
			while ($nbAffc > 0)
			{
				echo '<div class="userAffc" onclick="focusUserAffc(this);"><input type="hidden" class="hiddenuseraffc" value="'.$user_affc_id[$nbAffc-1].'" />'.$user_affc[$nbAffc-1].'</div>';
				$nbAffc--;
			}
			echo '</div>';
			echo '<img src="resources/style/cross.png" alt="X" style="cursor:pointer;" width="16" height="16" onclick="rmvNewAffc();" />';
			echo '&nbsp;';
			echo '<img src="resources/style/plus.png" alt="+" style="cursor:pointer;" width="16" height="16" onclick="addNewAffc();" />';
			echo '&nbsp;';
			echo '<select id="selectnewaffc" id="selectnewaffc" style="width:180px;">';
			//Récup liste des applis
			$usrs = execSQL($c, 'SELECT * FROM TAMGUSER WHERE ADMUSER = 1 ORDER BY NAMUSER');
						
			//Rempli le select
			while (odbc_fetch_row($usrs))
			{
				foreach ($user_affc as $usr)
				{
					if (odbc_result($usrs, 'NAMUSER') == $usr)
					{
						$founduser = true;
					}
				}
				if (!$founduser)
				{
					echo '<option value="'.odbc_result($usrs, 'CODUSER').'">'.odbc_result($usrs, 'NAMUSER').'</option>';
				}
				$founduser = false;
			}
			echo '</select>';
			echo '</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td></tr>';
			echo '</td></tr><tr><td>&nbsp;</td><td>Patch : </td></tr>';
			echo '<tr><td>';
			echo _TXT_TASK_APPLI.'<select id="appfilter" name="appfilter" style="width:150px;" onclick="getActIndex();" onfocus="getActIndex();" onchange="alertBeforeEmptyPatcLst();">';
			echo '<option value="none"></option>';
			//Récup liste des applis
			$apps = execSQL($c, 'SELECT * FROM TAMGAPPL');
						
			//Rempli le select
			while (odbc_fetch_row($apps))
			{
				if (isset($app[0]['id']) && odbc_result($apps, 'CODAPP') == $app[0]['id'])
				{
					echo '<option value="'.odbc_result($apps, 'CODAPP').'" selected>'.odbc_result($apps, 'NAMAPP').'</option>';
				}
				else
				{
					echo '<option value="'.odbc_result($apps, 'CODAPP').'">'.odbc_result($apps, 'NAMAPP').'</option>';
				}
			}
			echo '</select>';
			echo '</td><td>';
			echo '<div id="finalPatc">';
			while ($app[0]['nbPatch'] > 0)
			{
				echo '<div class="patcAffc" onclick="focusPatcAffc(this);"><input type="hidden" class="hiddenpatc" value="'.$patc[$app[0]['nbPatch']-1].'" />'.$app[0][$app[0]['nbPatch']-1].'</div>';
				$app[0]['nbPatch']--;
			}
			echo '</div>';
			echo '<div id="filterpatc">';
			$_GET['codapp'] = $app[0]['id'];
			include('filterpatc3.php');
			echo '</div>';
			echo '</td></tr>';
			echo '</table><br />';
			echo '<br /><br />';
			echo _TXT_LASTMOD.'<b>'.$time_lastmodf.'</b> par <b>'.$user_lastmodf.'</b><br /><br />';
			echo _TXT_PRIO.'<select id="newprio" name="newprio" style="width:150px;">';
			//Récup liste des applis
			$prios = execSQL($c, 'SELECT * FROM TAMGPRIO');
						
			//Rempli le select
			while (odbc_fetch_row($prios))
			{
				if (isset($task_Codprio) && odbc_result($prios, 'CODPRIO') == $task_Codprio)
				{
					echo '<option value="'.odbc_result($prios, 'CODPRIO').'" selected>'.odbc_result($prios, 'VALPRIO').'</option>';
				}
				else
				{
					echo '<option value="'.odbc_result($prios, 'CODPRIO').'">'.odbc_result($prios, 'VALPRIO').'</option>';
				}
			}
			echo '</select><br /><br />';
			if ($task_Urg == '1')
			{
				echo '<input type="checkbox" name="newurg" id="newurg" checked /><font style="font-weight:bold;color:#FF0000;"> Urgent</font>';
				echo '<br />';
			}
			else
			{
				echo '<input type="checkbox" name="newurg" id="newurg" /><font style="font-weight:bold;color:#FF0000;"> Urgent</font>';
				echo '<br />';
			}
			if ($task_Due <> '')
			{
				echo '&Eacute;ch&eacute;ance au <input type="text" name="dateecheance" id="datepicker" value="'.formatDate($task_Due).'" />';
			}
			else
			{
				echo '&Eacute;ch&eacute;ance au <input type="text" name="dateecheance" id="datepicker" value="" />';
			}
			echo '<br /><br />';	
			echo '<input type="hidden" name="taskid" id="taskid" value="'.$task_ID.'" />';
			echo '<input type="hidden" name="newlistaffc" id="newlistaffc" value="" />';
			echo '<input type="hidden" name="newlistdest" id="newlistdest" value="" />';
			echo '<input type="hidden" name="newlistpatc" id="newlistpatc" value="" />';
			echo '<input type="hidden" name="actindex" id="actindex" value="" />';
		?>
		</form>
		<iframe style="height:1px;border:0px;" name="taskEdit" seamless></iframe>
	</div>
	<?php
		if (isset($_SESSION['isAdm']) && $_SESSION['isAdm'])
		{
			echo '<div id="deletetask" onclick="deleteTask('.$task_ID.')">';
			echo 'Désactiver cette tâche';
			echo '</div>';
		}
	?>
	<div id="commenttask">
		<?php			
			//Récup de la liste des commentaires textes
			$sqlcomt = 'SELECT CODTASK, CODUSER, CODTYPC, TSTPCOM, NUMLGNC, TXTCOM FROM TAMGCOMT WHERE CODTASK = '.$task_ID.' AND NUMLGNC = 1 ORDER BY TSTPCOM';
			//Récup de la liste des commentaires fichiers joints
			$sqlfile = 'SELECT CODTASK, CODUSER, CODTYPC, TSTPCOM, NUMLGNE, FILECOM FROM TAMGFILE WHERE CODTASK = '.$task_ID.' AND NUMLGNE = 1 ORDER BY TSTPCOM';
			//Initialisation index du tableau des commentaires
			$i = 0;
			$comts = execSQL($c, $sqlcomt);
			$files = execSQL($c, $sqlfile);
			//Récup du nombre de commentaires texte
			$countComts = getNumRows($comts);
			//Récup du nombre de commentaires fichiers
			$countFiles = getNumRows($files);
			//Remise en place du curseur sur premiers enregs
			odbc_fetch_row($comts, 1);
			odbc_fetch_row($files, 1);
			//Numéro du record texte lu
			$indexComts = 0;
			//Numéro du record fichier lu
			$indexFiles = 0;
			//Aucun commentaire affiché à ce stade
			$alreadyDisplayed = false;
			
			if (($countComts != 0) || ($countFiles != 0))
			{
				//Tant qu'on arrive pas en fin de fichier de l'une des deux tables
				while (($countComts != $indexComts) && ($countFiles != $indexFiles))
				{
					if ((strtotime(odbc_result($comts, 'TSTPCOM')) - strtotime(odbc_result($files, 'TSTPCOM'))) <= 60)
					{
						if (strtotime(odbc_result($comts, 'TSTPCOM')) < strtotime(odbc_result($files, 'TSTPCOM')))
						{
							$tstptoqry = odbc_result($files, 'TSTPCOM');
						}
						else
						{
							$tstptoqry = odbc_result($comts, 'TSTPCOM');
						}
					}
					//On récupère les commentaires en tant qu'objets pour chacun des enregs
					$tempComt = new comment(odbc_result($comts, 'CODTASK'), odbc_result($comts, 'CODUSER'), odbc_result($comts, 'CODTYPC'), $tstptoqry);
					$tempFile = new comment(odbc_result($files, 'CODTASK'), odbc_result($files, 'CODUSER'), odbc_result($files, 'CODTYPC'), $tstptoqry);
					//Si le commentaire "texte" est plus vieux que le commentaire "fichier"
					if (strtotime($tempComt->getTstp()) < strtotime($tempFile->getTstp()))
					{	
						//Et si des commentaires ont déjà été affichés
						if (isset($comments))
						{
							//On vérifie que celui qu'on veut écrire ne l'est pas déjà
							for ($j = 0 ; $j < count($comments) ; $j++)
							{
								if (!$alreadyDisplayed)
								{	
									$alreadyDisplayed = $comments[$j]->equals($tempComt);
								}
							}
						}
						//Si ce n'est pas le cas ou qu'aucun commentaire n'a été écrit, on l'ajoute dans la liste des commentaires à afficher, et on passe au commentaire "texte" suivant
						if (!$alreadyDisplayed)
						{
							$comments[$i] = $tempComt;
							$alreadyDisplayed = false;
							$i++;
							odbc_fetch_row($comts);
							$indexComts++;
						}
						else
						{
							odbc_fetch_row($comts);
							$alreadyDisplayed = false;
							$indexComts++;
						}
					}
					//Si le commentaire "fichier" est plus vieux que le commentaire "texte", ou à même date
					else
					{
						//Et si des commentaires ont déjà été affichés
						if (isset($comments))
						{
							//On vérifie que celui qu'on veut écrire ne l'est pas déjà
							for ($j = 0 ; $j < count($comments) ; $j++)
							{
								if (!$alreadyDisplayed)
								{	
									$alreadyDisplayed = $comments[$j]->equals($tempFile);
								}
							}
						}
						//Si ce n'est pas le cas ou qu'aucun commentaire n'a été écrit, on l'ajoute dans la liste des commentaires à afficher, et on passe au commentaire "fichier" suivant
						if (!$alreadyDisplayed)
						{
							$comments[$i] = $tempFile;
							$alreadyDisplayed = false;
							$i++;
							odbc_fetch_row($files);
							$indexFiles++;
						}
						else
						{
							odbc_fetch_row($files);
							$alreadyDisplayed = false;
							$indexFiles++;
						}
					}
				}

				//À la fin, s'il reste des commentaires à afficher en "texte"
				while ($countComts != $indexComts)
				{
					//On les créé en tant qu'objet
					$tempComt = new comment(odbc_result($comts, 'CODTASK'), odbc_result($comts, 'CODUSER'), odbc_result($comts, 'CODTYPC'), odbc_result($comts, 'TSTPCOM'));
					//On teste qu'il n'ait pas déjà été affiché, si affichage il y a eu
					if (isset($comments))
					{
						for ($j = 0 ; $j < count($comments) ; $j++)
						{
							if (!$alreadyDisplayed)
							{	
								$alreadyDisplayed = $comments[$j]->equals($tempComt);
							}
						}
					}
					//Si ce n'est pas le cas, on l'affiche
					if (!$alreadyDisplayed)
					{
						$comments[$i] = $tempComt;
						$i++;
					}
					//Dans tous les cas, on passe au suivant
					$alreadyDisplayed = false;
					odbc_fetch_row($comts);
					$indexComts++;
				}
				//De même, s'il reste des commentaires à afficher en "fichier"
				while ($countFiles != $indexFiles)
				{
					//On les créé en tant qu'objet
					$tempFile = new comment(odbc_result($files, 'CODTASK'), odbc_result($files, 'CODUSER'), odbc_result($files, 'CODTYPC'), odbc_result($files, 'TSTPCOM'));
					//On teste qu'il n'ait pas été déjà affiché, si affichage il y a eu
					if (isset($comments))
					{
						for ($j = 0 ; $j < count($comments) ; $j++)
						{
							if (!$alreadyDisplayed)
							{	
								$alreadyDisplayed = $comments[$j]->equals($tempFile);
							}
						}
					}
					//Si ce n'est pas le cas, on l'affiche
					if (!$alreadyDisplayed)
					{
						$comments[$i] = $tempFile;
						$i++;
					}
					//Dans tous les cas, on passe au suivant
					$alreadyDisplayed = false;
					odbc_fetch_row($files);
					$indexFiles++;
				}
				//Finalement, on affiche tous les commentaires dans l'ordre à partir du tableau qu'on a rempli
				for ($i = 0 ; $i < count($comments) ; $i++)
				{
					$comt = $comments[$i];
					$comt->display();
					if ($i+1 != count($comments))
					{
						echo '<br />';
					}
				}
			}
			else
			{
				echo '<font style="color:#FFFFFF;">Aucun commentaire</font>';
			}
			if (isset($_SESSION['login']))
			{
				include('createcmt.php');
			}
		?>
	</div>
</div>