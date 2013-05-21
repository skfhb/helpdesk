<?php
//---------------------------------------------------------------//
//	Projet 		: Task Manager									 //
//	Fichier 	: optnfilter.php							 	 //
//  Description : Gère les filtres/recherches sur listtask	     //
//	Auteur 		: Hervé Bordeau									 //
// 	Date 		: 21/05/2013							     	 //
//---------------------------------------------------------------//
//Dernière modif le 21/05/2013 par HB

header('Content-Type: text/html; charset=iso-8859-1');
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
	
	echo '<select id="appfilter" onchange="chgfilterpatc();">';
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
	echo '</td><td style="width:15%;">';
	echo 'Tâches urgentes : <input type="checkbox" name="urgfilter" id="urgfilter" />';
	echo '</td>';
	echo '</tr>';
	echo '</table>';
}
elseif ($_POST['tab'] == 'search')
{
	echo '<br />';
	echo 'Par numéro de tâche : <input type="text" name="searchbytasknb" id="searchbytasknb" value="" />';
	echo '<br /><br />';
	echo 'Par libellé contenant : <input type="text" name="searchbytasklbl" id="searchbytasklbl" value="" />';
	echo '<br />';
	echo '<i><font style="font-size:0.75em;">Sensible à la casse</font></i>';
	echo '<br /><br />';
	echo '<div id="searchbtn" class="btn" onclick="searchlisttask();">Rechercher</div>';
}
elseif ($_POST['tab'] == 'col')
{
	echo 'Colonnes';
}

?>