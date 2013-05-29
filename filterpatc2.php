<?php
//---------------------------------------------------------------//
//	Projet 		: Task Manager									 //
//	Fichier 	: filterpatc2.php							 	 //
//  Description : G�re les filtres/recherches de patchs		     //
//	Auteur 		: Herv� Bordeau									 //
// 	Date 		: 21/05/2013							     	 //
//---------------------------------------------------------------//
//Derni�re modif le 21/05/2013 par HB

header('Content-Type: text/html; charset=iso-8859-1');	
//- la d�finition des constantes de l'ensemble de l'application
require_once("include/cst.php");
//- la gestion de la couche d'acc�s aux donn�es
require_once("include/dal.php");

//Ouverture connexion � la DB
$c = openConnection();

	if (isset($_POST['codapp']) && $_POST['codapp'] != '' && $_POST['codapp'] != 'all')
	{
		$patchs = execSQL($c, 'SELECT * FROM TAMGPATC WHERE CODAPP = '.$_POST['codapp']);
	}
	else
	{
		$patchs = execSQL($c, 'SELECT * FROM TAMGPATC');
	}
	$nbPatchs = getNumRows($patchs);
	odbc_fetch_row($patchs, 0);

		echo 'Patch : <select id="patcfilter">';
		echo '<option value="none" id="filteroptall"></option>';
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