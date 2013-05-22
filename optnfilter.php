<?php
//---------------------------------------------------------------//
//	Projet 		: Task Manager									 //
//	Fichier 	: optnfilter.php							 	 //
//  Description : Gère les filtres/recherches sur listtask	     //
//	Auteur 		: Hervé Bordeau									 //
// 	Date 		: 21/05/2013							     	 //
//---------------------------------------------------------------//
//Dernière modif le 21/05/2013 par HB

	try
	{
		if(session_id() == '')
		{
			session_start();
		}

	}
	catch (Exception $e)
	{
		//Rien à faire, la session et le header ont juste déjà été lancés
	}

//- la définition des constantes de l'ensemble de l'application
require_once("include/cst.php");
//- la gestion de la couche d'accès aux données
require_once("include/dal.php");

//Ouverture connexion à la DB
$c = openConnection();

if (empty($_POST['tab']) || ($_POST['tab'] == 'filter'))
{
	echo '<br />';
	echo '<table>';
	echo '<tr style="width:99%;">';
	echo '<td style="width:33%;">';
	echo 'Application : ';
	
	//Récup liste des applis
	$applis = execSQL($c, 'SELECT * FROM TAMGAPPL');
	$typts = execSQL($c, 'SELECT * FROM TAMGTYPT');
	$prios = execSQL($c, 'SELECT * FROM TAMGPRIO');
	$stats = execSQL($c, 'SELECT * FROM TAMGSTAT');
	
	echo '<select id="appfilter" onchange="setFilter();">';
	echo '<option value="all">Toutes</option>';
	//Rempli le select
	while (odbc_fetch_row($applis))
	{
		echo '<option value="'.odbc_result($applis, 'CODAPP').'">'.odbc_result($applis, 'NAMAPP').'</option>';
	}
	echo '</select>';
	echo '</td><td style="width:33%;">';
	//Récup liste des patchs
	echo '<div id="filterpatc">';
	include('filterpatc.php');
	echo '</div>';
	echo '</td><td style="width:20%;">';
	echo 'Tâches urgentes : <input type="checkbox" name="urgfilter" id="urgfilter" onclick="setFilter();" />';
	echo '</td>';
	echo '</tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr><tr>';
	echo '<td style="width:33%;">';
	echo 'Type : <select id="typtfilter" onchange="setFilter();">';
	echo '<option value="all">Tous</option>';
	while (odbc_fetch_row($typts))
	{
		echo '<option value="'.odbc_result($typts, 'CODTYPT').'">'.odbc_result($typts, 'LBLTYPT').'</option>';
	}
	echo '</select>';
	echo '</td><td style="width:33%;">';
	echo 'Priorit&eacute; : <select id="priofilter" onchange="setFilter();">';
	echo '<option value="all">Toutes</option>';
	while (odbc_fetch_row($prios))
	{
		echo '<option value="'.odbc_result($prios, 'CODPRIO').'">'.odbc_result($prios, 'VALPRIO').'</option>';
	}
	echo '</select>';
	echo '</td><td style="width:20%;">';
	echo 'Statut : <select id="stsfilter" onchange="setFilter();document.getElementById(\'imgstat\').src=\''._IMG_STAT.'\' + document.getElementById(\'stsfilter\').options[document.getElementById(\'stsfilter\').selectedIndex].value + \'.png\'">';
	echo '<option value="all">Tous</option>';
	while (odbc_fetch_row($stats))
	{
		echo '<option value="'.odbc_result($stats, 'CODSTS').'">'.odbc_result($stats, 'LBLSTS').'</option>';
	}
	echo '</select>';
	echo '<img src="'._IMG_STAT.'all.png" id="imgstat" width="16" height="16" />';
	echo '</td>';
	echo '</tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr><tr>';
	echo '<td style="width:33%;">';
	echo 'Mes demandes : <input type="checkbox" name="myaskfilter" id="myaskfilter" onclick="setFilter();" />';
	echo '</td><td style="width:33%;">';
	echo 'Me concernant : <input type="checkbox" name="formefilter" id="formefilter" onclick="setFilter();" />';
	echo '</td><td style="width:20%;">';
	if (isset($_SESSION['isAdm']) && $_SESSION['isAdm'])
	{
		echo 'Qui me sont affectées : <input type="checkbox" name="affcmefilter" id="affcmefilter" onclick="setFilter();" />';
	}
	else
	{
		echo '&nbsp;';
	}
	echo '</td>';
	echo '</tr>';
	echo '</table>';
}
elseif ($_POST['tab'] == 'search')
{
	echo '<br />';
	echo 'Par num&eacute;ro de t&acirc;che : <input type="text" name="searchbytasknb" id="searchbytasknb" value="" />';
	echo '<br /><br />';
	echo 'Par libell&eacute; contenant : <input type="text" name="searchbytasklbl" id="searchbytasklbl" value="" />';
	echo '<br />';
	echo '<i><font style="font-size:0.75em;">Sensible &agrave; la casse</font></i>';
	echo '<br /><br />';
	echo '<div id="searchbtn" class="btn" onclick="searchlisttask();">Rechercher</div>';
}
elseif ($_POST['tab'] == 'col')
{
	echo 'Colonnes';
}

?>