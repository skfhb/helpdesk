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
	
	echo '<select id="appfilter" onchange="chgfilterpatc();setTimeout(setFilter, 1000);">';
	echo '<option value="all">Toutes</option>';
	//Rempli le select
	while (odbc_fetch_row($applis))
	{
		if (isset($_SESSION['F_App']) && $_SESSION['F_App'] == odbc_result($applis, 'CODAPP'))
		{
			echo '<option value="'.odbc_result($applis, 'CODAPP').'" selected>'.odbc_result($applis, 'NAMAPP').'</option>';
		}
		else
		{
			echo '<option value="'.odbc_result($applis, 'CODAPP').'">'.odbc_result($applis, 'NAMAPP').'</option>';
		}
	}
	echo '</select>';
	echo '</td><td style="width:33%;">';
	//Récup liste des patchs
	echo '<div id="filterpatc">';
	include('filterpatc.php');
	echo '</div>';
	echo '</td><td style="width:20%;">';
	if (isset($_SESSION['F_Urg']) && $_SESSION['F_Urg'] == 1)
	{
		echo 'Tâches urgentes : <input type="checkbox" name="urgfilter" id="urgfilter" onclick="setFilter();" checked />';
	}
	else
	{
		echo 'Tâches urgentes : <input type="checkbox" name="urgfilter" id="urgfilter" onclick="setFilter();" />';
	}
	echo '</td>';
	echo '</tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr><tr>';
	echo '<td style="width:33%;">';
	echo 'Type : <select id="typtfilter" onchange="setFilter();">';
	echo '<option value="all">Tous</option>';
	while (odbc_fetch_row($typts))
	{
		if (isset($_SESSION['F_Typt']) && $_SESSION['F_Typt'] == odbc_result($typts, 'CODTYPT'))
		{
			echo '<option value="'.odbc_result($typts, 'CODTYPT').'" selected>'.odbc_result($typts, 'LBLTYPT').'</option>';
		}
		else
		{
			echo '<option value="'.odbc_result($typts, 'CODTYPT').'">'.odbc_result($typts, 'LBLTYPT').'</option>';
		}
	}
	echo '</select>';
	echo '</td><td style="width:33%;">';
	echo 'Priorit&eacute; : <select id="priofilter" onchange="setFilter();">';
	echo '<option value="all">Toutes</option>';
	while (odbc_fetch_row($prios))
	{
		if (isset($_SESSION['F_Prio']) && $_SESSION['F_Prio'] == odbc_result($prios, 'CODPRIO'))
		{
			echo '<option value="'.odbc_result($prios, 'CODPRIO').'" selected>'.odbc_result($prios, 'VALPRIO').'</option>';
		}
		else
		{
			echo '<option value="'.odbc_result($prios, 'CODPRIO').'">'.odbc_result($prios, 'VALPRIO').'</option>';
		}
	}
	echo '</select>';
	echo '</td><td style="width:20%;">';
	echo 'Statut : <select id="stsfilter" onchange="setFilter();document.getElementById(\'imgstat\').src=\''._IMG_STAT.'\' + document.getElementById(\'stsfilter\').options[document.getElementById(\'stsfilter\').selectedIndex].value + \'.png\'">';
	echo '<option value="all">Tous</option>';
	while (odbc_fetch_row($stats))
	{
		if (isset($_SESSION['F_Stat']) && $_SESSION['F_Stat'] == odbc_result($stats, 'CODSTS'))
		{
			echo '<option value="'.odbc_result($stats, 'CODSTS').'" selected>'.odbc_result($stats, 'LBLSTS').'</option>';
		}
		else
		{
			echo '<option value="'.odbc_result($stats, 'CODSTS').'">'.odbc_result($stats, 'LBLSTS').'</option>';
		}
	}
	echo '</select>';
	echo '<img src="'._IMG_STAT.'all.png" id="imgstat" width="16" height="16" />';
	echo '</td>';
	echo '</tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr><tr>';
	echo '<td style="width:33%;">';
	echo 'Mes demandes : ';
	if (isset($_SESSION['F_MAsk']) && $_SESSION['F_MAsk'] == 1)
	{
		echo '<input type="checkbox" name="myaskfilter" id="myaskfilter" onclick="setFilter();" checked />';
	}
	else
	{
		echo '<input type="checkbox" name="myaskfilter" id="myaskfilter" onclick="setFilter();" />';
	}
	echo '</td><td style="width:33%;">';
	echo 'Me concernant : ';
	if (isset($_SESSION['F_MCon']) && $_SESSION['F_MCon'] == 1)
	{
		echo '<input type="checkbox" name="formefilter" id="formefilter" onclick="setFilter();" checked />';
	}
	else
	{
		echo '<input type="checkbox" name="formefilter" id="formefilter" onclick="setFilter();" />';
	}
	echo '</td><td style="width:20%;">';
	echo 'Qui me sont affectées : ';
	if (isset($_SESSION['isAdm']) && $_SESSION['isAdm'])
	{
		if (isset($_SESSION['F_MAffc']) && $_SESSION['F_MAffc'] == 1)
		{
			echo '<input type="checkbox" name="affcmefilter" id="affcmefilter" onclick="setFilter();" checked />';
		}
		else
		{
			echo '<input type="checkbox" name="affcmefilter" id="affcmefilter" onclick="setFilter();" />';
		}
	}
	else
	{
		echo '<input type="checkbox" name="affcmefilter" id="affcmefilter" disabled="disabled" />';
	}
	echo '</td>';
	echo '</tr>';
	echo '</table>';
}
elseif ($_POST['tab'] == 'search')
{
	echo '<br />';
	echo 'Par num&eacute;ro de t&acirc;che : ';
	if (isset($_SESSION['F_CODTSK']))
	{
		echo '<input type="text" name="searchbytasknb" id="searchbytasknb" value="'.$_SESSION['F_CODTSK'].'" onkeypress="if (event.keyCode == 13) searchlisttask();" />';
	}
	else
	{
		echo '<input type="text" name="searchbytasknb" id="searchbytasknb" value="" onkeypress="if (event.keyCode == 13) searchlisttask();" />';
	}
	echo '<br /><br />';
	echo 'Par libell&eacute; contenant : ';
	if (isset($_SESSION['F_LBLTSK']))
	{
		echo '<input type="text" name="searchbytasklbl" id="searchbytasklbl" value="'.$_SESSION['F_LBLTSK'].'" onkeypress="if (event.keyCode == 13) searchlisttask();" />';
	}
	else
	{
		echo '<input type="text" name="searchbytasklbl" id="searchbytasklbl" value="" onkeypress="if (event.keyCode == 13) searchlisttask();" />';
	}
	echo '<br />';
	echo '<i><font style="font-size:0.75em;">Sensible &agrave; la casse</font></i>';
	echo '<br /><br />';
	echo '<div id="searchbtn" class="btn" onclick="searchlisttask();">Rechercher</div>';
}
elseif ($_POST['tab'] == 'col')
{
	echo 'Colonnes';
}
elseif ($_POST['tab'] == 'raz')
{
	echo '<br />';
	if (isset($_SESSION['qry']))
	{
		unset($_SESSION['qry']);
	}
	if (isset($_SESSION['F_App']))
	{
		unset($_SESSION['F_App']);
	}
	if (isset($_SESSION['F_Patc']))
	{
		unset($_SESSION['F_Patc']);
	}
	if (isset($_SESSION['F_Urg']))
	{
		unset($_SESSION['F_Urg']);
	}
	if (isset($_SESSION['F_Typt']))
	{
		unset($_SESSION['F_Typt']);
	}
	if (isset($_SESSION['F_Prio']))
	{
		unset($_SESSION['F_Prio']);
	}
	if (isset($_SESSION['F_Stat']))
	{
		unset($_SESSION['F_Stat']);
	}
	if (isset($_SESSION['F_MAsk']))
	{
		unset($_SESSION['F_MAsk']);
	}
	if (isset($_SESSION['F_MCon']))
	{
		unset($_SESSION['F_MCon']);
	}
	if (isset($_SESSION['F_MAffc']))
	{
		unset($_SESSION['F_MAffc']);
	}
	if (isset($_SESSION['F_CODTSK']))
	{
		unset($_SESSION['F_CODTSK']);
	}
	if (isset($_SESSION['F_LBLTSK']))
	{
		unset($_SESSION['F_LBLTSK']);
	}
	echo 'Remise &agrave; z&eacute;ro effectu&eacute;e';
}

?>