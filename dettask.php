<?php
//---------------------------------------------------------------//
//	Projet 		: Task Manager									 //
//	Fichier 	: dettask.php 								 	 //
//  Description : Page de gestion du d�tail d'une t�che 		 //
//	Auteur 		: Herv� Bordeau									 //
// 	Date 		: 15/02/2013							     	 //
//---------------------------------------------------------------//
//Derni�re modif le 08/03/2013 par HB
	
	session_start();
	header('Content-Type: text/html; charset=iso-8859-1');
	//- la d�finition des constantes de l'ensemble de l'application
	include("include/cst.php");
	//- la gestion de la couche d'acc�s aux donn�es
	include("include/dal.php");
	//- les fonctions outil
	include("include/tools.php");
	//- la classe de gestion des commentaires
	require "include/classComment.php";
	//- la gestion du "clickout"
	include("include/clickout.php");
	
	//Ouverture connexion � la DB
	$c = openConnection();	
	
	//Si aucune t�che de s�lectionn�e, on sort imm�diatement
	if (isset($_GET['id']) && $_GET['id'] != "")
	{
		//R�cup�ration des infos de la t�che pass�e en argument
		$sql = 'SELECT * FROM TAMGTASK WHERE CODTASK = '.$_GET['id'];
		$tasks = execSQL($c, $sql);
		
		//Tant qu'on trouve des lignes (il n'y en aura, normalment, qu'une)
		while (odbc_fetch_row($tasks))
		{
			//R�cup�ration de toutes les infos concernant la t�che
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
			//On fait le lien avec les types de t�che, pour obtenir une cha�ne et non un simple ID
			$sql = 'SELECT * FROM TAMGTYPT WHERE CODTYPT = '.$task_Codtypt;
			$typts = execSQL($c, $sql);
			while (odbc_fetch_row($typts))
			{
				$lblTypt = odbc_result($typts, 'LBLTYPT');
			}
			//On fait le lien avec les statuts pour obtenir une cha�ne et non un simple ID, apr�s avoir r�cup�r� le statut actuel
			$sql = 'SELECT * FROM TAMGSTAT WHERE CODSTS IN (SELECT CODSTS FROM TAMGHSTS WHERE CODTASK = '.$task_ID.' ORDER BY TSTPSTS DESC)';
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
			$sql = 'SELECT NAMUSER FROM TAMGUSER WHERE CODUSER IN (SELECT CODUSER FROM TAMGDEST WHERE CODTASK = '.$task_ID.')';
			$dests = execSQL($c, $sql);
			$nbDest = 0;
			while (odbc_fetch_row($dests))
			{
				$user_dest[$nbDest] = odbc_result($dests, 'NAMUSER');
				$nbDest++;
			}
			//On fait le lien avec les utilisateurs pour obtenir les noms des personnes charg�es d'effectuer la t�che
			$sql = 'SELECT NAMUSER FROM TAMGUSER WHERE CODUSER IN (SELECT CODUSER FROM TAMGAFFC WHERE CODTASK = '.$task_ID.')';
			$affcs = execSQL($c, $sql);
			$nbAffc = 0;
			while (odbc_fetch_row($affcs))
			{
				$user_affc[$nbAffc] = odbc_result($affcs, 'NAMUSER');
				$nbAffc++;
			}
			//On fait le lien avec les utilisateurs et les modifs pour obtenir le nom du dernier utilisateur ayant modifi� la t�che, ainsi que la date
			$sql = 'SELECT NAMUSER, TSTPMOD FROM TAMGUSER, TAMGMODF WHERE CODTASK = '.$task_ID.' AND TAMGUSER.CODUSER = TAMGMODF.CODUSER ORDER BY TSTPMOD DESC';
			$lastmodf = execSQL($c, $sql);
			while (odbc_fetch_row($lastmodf))
			{
				$user_lastmodf = odbc_result($lastmodf, 'NAMUSER');
				//Pour l'affichage, on s�pare la date de l'heure
				$time_lastmodf = formatDate(odbc_result($lastmodf, 'TSTPMOD')).'</b> � <b>'.formatTime(odbc_result($lastmodf, 'TSTPMOD'));
			}
			//On regarde quelles applications sont concern�es par la t�che
			$sql = 'SELECT NAMAPP FROM TAMGAPPL WHERE CODAPP IN (SELECT CODAPP FROM TAMGAPTA WHERE CODTASK = '.$task_ID.')';
			$apps = execSQL($c, $sql);
			$nbApp = 0;
			//Pour chaque appli
			while (odbc_fetch_row($apps))
			{
				//On r�cup�re le nom de l'appli
				$app[$nbApp]['name'] = odbc_result($apps, 'NAMAPP');
				//On initialise la variable comptant les patchs de cette appli concern�s
				$app[$nbApp]['nbPatch'] = 0;
				//On r�cup�re les patchs en question
				$sql = 'SELECT NAMPATC FROM TAMGPATC WHERE CODAPP IN (SELECT CODAPP FROM TAMGAPPL WHERE NAMAPP = \''.$app[$nbApp]['name'].'\') AND CODPATC IN (SELECT CODPATC FROM TAMGPATA WHERE CODTASK = '.$task_ID.')';
				$patchs = execSQL($c, $sql);
				//Pour chaque patch
				while (odbc_fetch_row($patchs))
				{
					//On stocke le nom du patch dans un tableau h�ritant de l'appli
					$app[$nbApp][$app[$nbApp]['nbPatch']] = odbc_result($patchs, 'NAMPATC');
					//On ajoute 1 au nombre de patch recens�s
					$app[$nbApp]['nbPatch']++;
				}	
				//On ajoute 1 au nombre d'applis recens�es
				$nbApp++;
			}
			//On fait le lien avec les valeurs de priorit�
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
		<div id="parenttask">
			<?php 
				if (isset($task_Partask) && $task_Partask != "")
				{
					echo 'Li�e � la t�che '.$task_Partask;
				}
			?>
		</div>
		<div id="idtask">
			<?php echo 'T�che n�'.$task_ID; ?>
		</div>
		<?php 
			if (isset($_SESSION['isAdm']) && $_POST['isAdm'])
			{
				echo '<div id="typtask">';
			}
			else
			{
				echo '<div id="typtask">';
			}
			echo $lblTypt; 
		?>
		</div>
	</div>
	<div id="contentTask">
		<?php
			echo '<b>'.$task_Lbl.'</b><br /><br />';
			echo '<img src="'._IMG_STAT.$activeStatutId.'.png" alt="" width="'._IMG_STAT_WIDTH.'" height="'._IMG_STAT_HEIGHT.'" /><b>   '.$activeStatutLbl.'</b><br /><br />';
			if ($task_Pub == '1')
			{
				echo '<input type="checkbox" value="Public" disabled="disabled" /> Sp�cifique informatique<br /><br />';
			}
			else
			{
				echo '<input type="checkbox" value="Public" disabled="disabled" checked /><b> Sp�cifique informatique</b><br /><br />';
			}
			echo 'Demand� par <b>'.$user_asker.'</b> le <b>'.formatDate($task_Ask).'</b><br /><br />';
			echo '<table><tr><td style="text-align:left;">Pour :</td><td style="text-align:left;">Pris en charge par :</td></tr>';
			while ($nbAffc > 0 || $nbDest > 0)
			{
				echo '<tr><td>';
				if ($nbDest > 0)
				{
					echo '<b><li>'.$user_dest[$nbDest-1].'</li></b>';
					$nbDest--;
				}
				echo '</td><td>';
				if ($nbAffc > 0)
				{
					echo '<b><li>'.$user_affc[$nbAffc-1].'</li></b>';
					$nbAffc--;
				}
				echo '</td></tr>';
			}
			echo '<tr><td>';
			if (isset($_SESSION['isAdm']) && $_SESSION['isAdm'])
			{
				//R�cup liste des users
				$users = execSQL($c, 'SELECT * FROM DEVTAMG.TAMGUSER ORDER BY NAMUSER');
					
				echo '<select id="selectDest" style="width:100px;">';
				//Remplis le select
				while (odbc_fetch_row($users))
				{
					echo '<option value="'.trim(odbc_result($users, 'CODUSER')).'">'.trim(odbc_result($users, 'NAMUSER')).'</option>';
				}
				echo '</select>';
			}
			echo '</td><td>';
			if (isset($_SESSION['isAdm']) && $_SESSION['isAdm'])
			{
				//R�cup liste des users
				$users = execSQL($c, 'SELECT * FROM DEVTAMG.TAMGUSER WHERE ADMUSER=1 ORDER BY NAMUSER');
					
				echo '<select id="selectAffc" style="width:100px;">';
				//Remplis le select
				while (odbc_fetch_row($users))
				{
					echo '<option value="'.trim(odbc_result($users, 'CODUSER')).'">'.trim(odbc_result($users, 'NAMUSER')).'</option>';
				}
				echo '</select>';
			}
			echo '</td></tr>';
			while ($nbApp > 0)
			{
				$toWrite = '<tr><td style="text-align:left;">'._TXT_TASK_APPLI.'<b>'.$app[$nbApp-1]['name'].'</b></td>';
				while ($app[$nbApp-1]['nbPatch'] > 1)
				{
					$toWrite .= '<td style="text-align:left;">'._TXT_TASK_PATCH.'<b>'.$app[$nbApp-1][$app[$nbApp-1]['nbPatch']-1].'</b></td></tr><tr><td></td>';
					$app[$nbApp-1]['nbPatch']--;
				}
				if ($app[$nbApp-1]['nbPatch'] == 1)
				{
					$toWrite .= '<td style="text-align:left;">'._TXT_TASK_PATCH.'<b>'.$app[$nbApp-1][$app[$nbApp-1]['nbPatch']-1].'</b></td></tr>';
				}
				echo $toWrite;
				$nbApp--;
			}
			echo '</table><br />';
			echo _TXT_LASTMOD.'<b>'.$time_lastmodf.'</b> par <b>'.$user_lastmodf.'</b><br /><br />';
			echo _TXT_PRIO.'<b>'.$lblPrio.'</b><br /><br />';
			if ($task_Urg == '1')
			{
				echo '<img src="'._IMG_STYLE.'icone-urgent.png" alt="Urgent" width="'._IMG_STAT_WIDTH.'" height="'._IMG_STAT_HEIGHT.'" /> <font style="color:red;"><b>'._TXT_URGENT.'</b></font>';
				echo '<br />';
			}
			if ($task_Due <> '')
			{
				echo '&Eacute;ch&eacute;ance au <b>'.formatDate($task_Due).'</b>';
			}
			echo '<br /><br />';			
		?>
	</div>
	<?php
		if (isset($_SESSION['isAdm']) && $_SESSION['isAdm'])
		{
			echo '<div id="deletetask" onclick="deleteTask('.$task_ID.')">';
			echo 'D�sactiver cette t�che';
			echo '</div>';
		}
	?>
	<div id="commenttask">
		<?php			
			//R�cup de la liste des commentaires textes
			$sqlcomt = 'SELECT CODTASK, CODUSER, CODTYPC, TSTPCOM, NUMLGNC, TXTCOM FROM TAMGCOMT WHERE CODTASK = '.$task_ID.' AND NUMLGNC = 1 ORDER BY TSTPCOM';
			//R�cup de la liste des commentaires fichiers joints
			$sqlfile = 'SELECT CODTASK, CODUSER, CODTYPC, TSTPCOM, NUMLGNE, FILECOM FROM TAMGFILE WHERE CODTASK = '.$task_ID.' AND NUMLGNE = 1 ORDER BY TSTPCOM';
			//Initialisation index du tableau des commentaires
			$i = 0;
			
			$comts = execSQL($c, $sqlcomt);
			$files = execSQL($c, $sqlfile);
			
			//R�cup du nombre de commentaires texte
			$countComts = getNumRows($comts);
			//R�cup du nombre de commentaires fichiers
			$countFiles = getNumRows($files);
			
			//Remise en place du curseur sur premiers enregs
			odbc_fetch_row($comts, 1);
			odbc_fetch_row($files, 1);
			
			//Num�ro du record texte lu
			$indexComts = 0;
			//Num�ro du record fichier lu
			$indexFiles = 0;
			//Aucun commentaire affich� � ce stade
			$alreadyDisplayed = false;
			
			if (($countComts != 0) || ($countFiles != 0))
			{
				//Tant qu'on arrive pas en fin de fichier de l'une des deux tables
				while (($countComts != $indexComts) && ($countFiles != $indexFiles))
				{
					//On r�cup�re les commentaires en tant qu'objets pour chacun des enregs
					$tempComt = new comment(odbc_result($comts, 'CODTASK'), odbc_result($comts, 'CODUSER'), odbc_result($comts, 'CODTYPC'), odbc_result($comts, 'TSTPCOM'));
					$tempFile = new comment(odbc_result($files, 'CODTASK'), odbc_result($files, 'CODUSER'), odbc_result($files, 'CODTYPC'), odbc_result($files, 'TSTPCOM'));
					//Si le commentaire "texte" est plus vieux que le commentaire "fichier"
					if (strtotime($tempComt->getTstp()) < strtotime($tempFile->getTstp()))
					{
						//Et si des commentaires ont d�j� �t� affich�s
						if (isset($comments))
						{
							//On v�rifie que celui qu'on veut �crire ne l'est pas d�j�
							for ($j = 0 ; $j < count($comments) ; $j++)
							{
								if (!$alreadyDisplayed)
								{	
									$alreadyDisplayed = $comments[$j]->equals($tempComt);
								}
							}
						}
						//Si ce n'est pas le cas ou qu'aucun commentaire n'a �t� �crit, on l'ajoute dans la liste des commentaires � afficher, et on passe au commentaire "texte" suivant
						if (!$alreadyDisplayed)
						{
							$comments[$i] = $tempComt;
							$alreadyDisplayed = false;
							$i++;
							odbc_fetch_row($comts);
							$indexComts++;
						}
					}
					//Si le commentaire "fichier" est plus vieux que le commentaire "texte", ou � m�me date
					else
					{
						//Et si des commentaires ont d�j� �t� affich�s
						if (isset($comments))
						{
							//On v�rifie que celui qu'on veut �crire ne l'est pas d�j�
							for ($j = 0 ; $j < count($comments) ; $j++)
							{
								if (!$alreadyDisplayed)
								{	
									$alreadyDisplayed = $comments[$j]->equals($tempFile);
								}
							}
						}
						//Si ce n'est pas le cas ou qu'aucun commentaire n'a �t� �crit, on l'ajoute dans la liste des commentaires � afficher, et on passe au commentaire "fichier" suivant
						if (!$alreadyDisplayed)
						{
							$comments[$i] = $tempFile;
							$alreadyDisplayed = false;
							$i++;
							odbc_fetch_row($files);
							$indexFiles++;
						}
					}
				}
				//� la fin, s'il reste des commentaires � afficher en "texte"
				while ($countComts != $indexComts)
				{
					//On les cr�� en tant qu'objet
					$tempComt = new comment(odbc_result($comts, 'CODTASK'), odbc_result($comts, 'CODUSER'), odbc_result($comts, 'CODTYPC'), odbc_result($comts, 'TSTPCOM'));
					//On teste qu'il n'ait pas d�j� �t� affich�, si affichage il y a eu
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
				//De m�me, s'il reste des commentaires � afficher en "fichier"
				while ($countFiles != $indexFiles)
				{
					//On les cr�� en tant qu'objet
					$tempFile = new comment(odbc_result($files, 'CODTASK'), odbc_result($files, 'CODUSER'), odbc_result($files, 'CODTYPC'), odbc_result($files, 'TSTPCOM'));
					//On teste qu'il n'ait pas �t� d�j� affich�, si affichage il y a eu
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
				
				//Finalement, on affiche tous les commentaires dans l'ordre � partir du tableau qu'on a rempli
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
		?>
	</div>
</div>