<?php
//---------------------------------------------------------------//
//	Projet 		: Task Manager									 //
//	Fichier 	: calcExistsTask.php							 //
//  Description : D�pendance AJAX : v�rif existence t�che parente//
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

if (isset($_POST['tasknb']) && $_POST['tasknb'] != '')
{
	$task = execSQL($c, 'SELECT * FROM TAMGTASK WHERE CODTASK = '.$_POST['tasknb'].' AND ACTTASK = 1');
	$nbTask = getNumRows($task);
	if ($nbTask == 1)
	{
		echo 'OK';
	}
	else
	{
		echo 'NULL';
	}
}
else
{
	echo 'NULL';
}
?>