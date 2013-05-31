<?php
//---------------------------------------------------------------//
//	Projet 		: Task Manager									 //
//	Fichier 	: filterpatc3.php							 	 //
//  Description : Gère les filtres/recherches de patchs		     //
//	Auteur 		: Hervé Bordeau									 //
// 	Date 		: 30/05/2013							     	 //
//---------------------------------------------------------------//
//Dernière modif le 31/05/2013 par HB

header('Content-Type: text/html; charset=iso-8859-1');	
//- la définition des constantes de l'ensemble de l'application
require_once("include/cst.php");
//- la gestion de la couche d'accès aux données
require_once("include/dal.php");

//Ouverture connexion à la DB
$c = openConnection();

	if (isset($_POST['codapp']) && $_POST['codapp'] != '' && $_POST['codapp'] != 'all')
	{
		$patchs = execSQL($c, 'SELECT * FROM TAMGPATC WHERE CODAPP = '.$_POST['codapp'].' AND CODPATC NOT IN (SELECT CODPATC FROM TAMGPATA WHERE CODTASK = '.$_GET['id'].')');
	}
	elseif (isset($_GET['codapp']) && $_GET['codapp'] != '' && $_GET['codapp'] != 'all')
	{
		$patchs = execSQL($c, 'SELECT * FROM TAMGPATC WHERE CODAPP = '.$_GET['codapp'].' AND CODPATC NOT IN (SELECT CODPATC FROM TAMGPATA WHERE CODTASK = '.$_GET['id'].')');
	}
	else
	{
		$patchs = execSQL($c, 'SELECT * FROM TAMGPATC WHERE CODPATC NOT IN (SELECT CODPATC FROM TAMGPATA WHERE CODTASK = '.$_GET['id'].')');
	}
	$nbPatchs = getNumRows($patchs);
	odbc_fetch_row($patchs, 0);

		echo '<img src="resources/style/cross.png" alt="X" style="cursor:pointer;" width="16" height="16" onclick="rmvNewPatc();" />';
		echo '&nbsp;';
		echo '<img src="resources/style/plus.png" alt="+" style="cursor:pointer;" width="16" height="16" onclick="addNewPatc();" />';
		echo '&nbsp;';
		echo '<select id="patcfilter">';
		//Rempli le select
		while (odbc_fetch_row($patchs))
		{
			if (isset($_SESSION['F_Patc']) && $_SESSION['F_Patc'] == odbc_result($patchs, 'CODPATC'))
			{
				echo '<option value="'.odbc_result($patchs, 'CODPATC').'" selected>'.odbc_result($patchs, 'NAMPATC').'</option>';
			}
			else
			{
				echo '<option value="'.odbc_result($patchs, 'CODPATC').'">'.odbc_result($patchs, 'NAMPATC').'</option>';
			}
		}
		echo '</select>';
?>