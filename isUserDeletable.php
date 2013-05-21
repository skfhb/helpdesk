<?php
//---------------------------------------------------------------//
//	Projet 		: Task Manager									 //
//	Fichier 	: isUserDeletable.php						 	 //
//  Description : Vérifie qu'un user peut être supprimé		     //
//	Auteur 		: Hervé Bordeau									 //
// 	Date 		: 16/05/2013							     	 //
//---------------------------------------------------------------//
//Dernière modif le 16/05/2013 par HB

	header('Content-Type: text/html; charset=iso-8859-1');
	//- la définition des constantes de l'ensemble de l'application
	include("include/cst.php");
	//- la gestion de la couche d'accès aux données
	include("include/dal.php");
	
	//Ouverture connexion à la DB
	$c = openConnection();
	$founduser = false;
	$sqlusr = 'SELECT * FROM TAMGTASK WHERE CODUSER = \''.$_POST['id'].'\'';
	$users = execSQL($c, $sqlusr);

	if (getNumRows($users) > 0)
	{
		$founduser = true;
	}
	else
	{
		$sqlusr = 'SELECT * FROM TAMGDEST WHERE CODUSER = \''.$_POST['id'].'\'';
		$users = execSQL($c, $sqlusr);

		if (getNumRows($users) > 0)
		{
			$founduser = true;
		}
		else
		{
			$sqlusr = 'SELECT * FROM TAMGMODF WHERE CODUSER = \''.$_POST['id'].'\'';
			$users = execSQL($c, $sqlusr);

			if (getNumRows($users) > 0)
			{
				$founduser = true;
			}
			else
			{
				$sqlusr = 'SELECT * FROM TAMGAFFC WHERE CODUSER = \''.$_POST['id'].'\'';
				$users = execSQL($c, $sqlusr);

				if (getNumRows($users) > 0)
				{
					$founduser = true;
				}
				else
				{
					$sqlusr = 'SELECT * FROM TAMGCOMT WHERE CODUSER = \''.$_POST['id'].'\'';
					$users = execSQL($c, $sqlusr);

					if (getNumRows($users) > 0)
					{
						$founduser = true;
					}
					else
					{
						$sqlusr = 'SELECT * FROM TAMGFILE WHERE CODUSER = \''.$_POST['id'].'\'';
						$users = execSQL($c, $sqlusr);

						if (getNumRows($users) > 0)
						{
							$founduser = true;
						}
					}
				}
			}
		}
	}
	if (!$founduser)
	{
		echo 'true'.$_POST['id'];
	}
	else
	{
		echo 'false';
	}
?>