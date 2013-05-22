<?php
//---------------------------------------------------------------//
//	Projet 		: Task Manager									 //
//	Fichier 	: listtask.php 								 	 //
//  Description : Page de gestion des tâches				     //
//	Auteur 		: Hervé Bordeau									 //
// 	Date 		: 08/03/2013							     	 //
//---------------------------------------------------------------//
//Dernière modif le 08/03/2013 par HB
	
	//- la définition des constantes de l'ensemble de l'application
	require_once("include/cst.php");
	//- la gestion de la couche d'accès aux données
	require_once("include/dal.php");
	//- les fonctions outil
	include("include/tools.php");
	//- la classe de gestion des commentaires
	require "include/classComment.php";
	
	//Ouverture connexion à la DB
	$c = openConnection();
	
	//if (isset($_SESSION['login']))
	//{
	if (isset($_SESSION['qry']) && $_SESSION['qry'] != '')
	{
		$sqltask = $_SESSION['qry'];
	}
	else
	{
		$sqltask = 'SELECT CODTASK, LBLTASK, URGTASK, CODTYPT, DATASKT FROM TAMGTASK WHERE ACTTASK != 0';
	}
		$tasks = execSQL($c, $sqltask);
		$parite = 'impair';
		echo '<div class="headertaskhead"><div class="headerelement" style="width:32px;">&nbsp;</div><div class="headerelement" style="width:100px;"><b>Code tâche</b></div><div class="headerelement" style="width:50px;"><b>Urgent</b></div><div class="headerelement" style="width:150px;"><b>Type</b></div><div class="headerelement" style="width:75px;"><b>Statut</b></div><div class="headerelement" style="text-align:left;width:600px;"><b>Intitulé tâche</b></div></div>';
		echo '<div class="sortablecontainer">';
		while (odbc_fetch_row($tasks))
		{
			$sqlstat = 'SELECT CODSTS, LBLSTS FROM TAMGSTAT WHERE CODSTS IN (SELECT CODSTS FROM TAMGHSTS WHERE CODTASK = '.odbc_result($tasks, 'CODTASK').' ORDER BY TSTPSTS DESC FETCH FIRST 1 ROW ONLY)';
			$statuts = execSQL($c, $sqlstat);
			$sqltyp = 'SELECT LBLTYPT FROM TAMGTYPT WHERE CODTYPT = '.odbc_result($tasks, 'CODTYPT');
			$types = execSQL($c, $sqltyp);
			echo '<div class="headertask'.$parite.'" id="'.odbc_result($tasks, 'CODTASK').'div">';
			echo '<div class="headerelementmove" id="'.odbc_result($tasks, 'CODTASK').'" unselectable="on" style="width:32px;">&nbsp;</div>';
			echo '<div class="headerelement" style="width:100px;cursor:pointer;" onclick="loadPage(\'dettask.php?id='.odbc_result($tasks, 'CODTASK').'\')" onmouseover="this.style.textDecoration=\'underline\';this.style.color=\'#0000AA\';" onmouseout="this.style.textDecoration=\'none\';this.style.color=\'#000000\';">'.odbc_result($tasks, 'CODTASK').'</div>';
			if (odbc_result($tasks, 'URGTASK') == '1')
			{
				echo '<div class="headerelement" style="width:50px;"><img src="'._IMG_STYLE.'icone-urgent.png" alt="Urgent" width="'._IMG_STAT_WIDTH.'" height="'._IMG_STAT_HEIGHT.'" /></div>';
			}
			else
			{
				echo '<div class="headerelement" style="width:50px;">&nbsp;</div>';
			}
			echo '<div class="headerelement" style="width:150px;">'.odbc_result($types, 'LBLTYPT').'</div>';
			echo '<div class="headerelement" style="width:75px;"><img src="'._IMG_STAT.odbc_result($statuts, 'CODSTS').'.png" alt="" title="'.trim(odbc_result($statuts, 'LBLSTS')).'" width="'._IMG_STAT_WIDTH.'" height="'._IMG_STAT_HEIGHT.'" /></div>';
			if (strlen(trim(odbc_result($tasks, 'LBLTASK'))) > 75)
			{
				echo '<div class="headerelement" title="'.trim(odbc_result($tasks, 'LBLTASK')).'" onmouseover="displayToolTip();" style="text-align:left;width:600px;">'.substr(odbc_result($tasks, 'LBLTASK'), 0, 73).'...</div>';
			}
			else
			{
				echo '<div class="headerelement" style="text-align:left;width:600px;">'.odbc_result($tasks, 'LBLTASK').'</div>';
			}
			echo '</div>';
			if ($parite == 'impair')
			{
				$parite = 'pair';
			}
			else
			{
				$parite = 'impair';
			}
		}
		echo '</div>';
		echo '<br />';
		echo '<div class="emptyFooter" style="height:32px;"></div>';
	//}
	
?>