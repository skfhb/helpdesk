<?php
//------------------------------------------------------------//
//	Projet 		: Task Manager								  //
//	Fichier 	: getapplis.php 							  //
//  Description : Page de requ�te d'affichage des applis AJAX //
//	Auteur 		: Herv� Bordeau								  //
// 	Date 		: 12/02/2013							      //
//------------------------------------------------------------//
//Derni�re modif le 12/02/2013 par HB
	
	//Si on utilise pas la page en include
	if (isset($_POST['option']) && $_POST['option'] != "")
	{
		header('Content-Type: text/html; charset=iso-8859-1');
		//- la d�finition des constantes de l'ensemble de l'application
		include("include/cst.php");
		//- la gestion de la couche d'acc�s aux donn�es
		include("include/dal.php");
	}
	
	//Ouverture connexion � la DB
	$c = openConnection();	
	
	//Par d�faut, mode affichage
	if (empty($_POST['option']) || $_POST['option'] == 'get')
	{
		$sql = 'SELECT * FROM TAMGAPPL';
		
		//R�cup liste des applis
		$applis = execSQL($c, $sql);
			
		//On d�finit un compteur pour les applis
		$nbApplis = 0;
		
		while (odbc_fetch_row($applis))
		{
			//Affichage de l'appli
			echo '<div class="appliLine" id="'.odbc_result($applis, 'CODAPP').'">';
			echo '<input type="button" value="Supprimer" onclick="delAppli('.odbc_result($applis, 'CODAPP').')" />';
			echo '<b>'.odbc_result($applis, 'NAMAPP').'</b>';
			echo '</div>';
			//Incr�mentation du compteur
			$nbApplis++;
		}
		//Si aucune appli trouv�e, afficher message d'erreur
		if ($nbApplis == 0)
		{
			echo '<b>D�sol�, aucune application n\'a �t� trouv�e.</b>';
		}
	
	}
	//Si mode insert
	elseif (isset($_POST['option']) && $_POST['option'] == 'insert')
	{
		//R�cup ID du dernier appli
		$getMaxID = execSQL($c, 'SELECT MAX(CODAPP) FROM TAMGAPPL');
		while (odbc_fetch_row($getMaxID))
		{
			$maxID = odbc_result($getMaxID, 1)+1;
		}
		//Insert en DB
		$stmt = odbc_prepare($c, 'INSERT INTO TAMGAPPL (CODAPP, NAMAPP) VALUES (?, ?)');
		$res = odbc_execute($stmt, array($maxID, utf8_decode($_POST['name'])));
	}
	//Si mode delete
	elseif (isset($_POST['option']) && $_POST['option'] == 'delete')
	{		
		//R�cup liste des t�ches li�es au appli � supprimer
		$sql = 'SELECT * FROM TAMGAPTA WHERE CODAPP = '.$_POST['id'];
		
		$tasksLinkedToDeletedAppli = execSQL($c, $sql);
			
		$sql = 'SELECT * FROM TAMGPATC WHERE CODAPP = '.$_POST['id'];
		
		$patchsLinkedToDeletedAppli = execSQL($c, $sql);
		
		//Compte le nombre de lignes
		$nbTasks = getNumRows($tasksLinkedToDeletedAppli);
		$nbPatchs = getNumRows($patchsLinkedToDeletedAppli);
	
		//Si aucune t�che et aucun patch
		if ($nbTasks == 0 && $nbPatchs == 0)
		{
			//Effectue le delete
			$stmt = odbc_prepare($c, 'DELETE FROM TAMGAPPL WHERE CODAPP = ?');
			$res = odbc_execute($stmt, array($_POST['id']));
		}
		//Sinon
		else
		{
			//Avertir l'utilisateur et affiche les n� de t�ches li�es
			if ($nbTasks != 0)
			{
				echo 'Impossible de supprimer cette application, des t�ches y sont li�es : ';
				echo 'T�che '.odbc_result($tasksLinkedToDeletedAppli, 'CODTASK');
				while (odbc_fetch_row($tasksLinkedToDeletedAppli))
				{
					echo ', ';
					echo 'T�che '.odbc_result($tasksLinkedToDeletedAppli, 'CODTASK');
				}
			}
			echo '                                                                      ';
			if ($nbPatchs != 0)
			{
				echo 'Impossible de supprimer cette application, des patchs y sont li�s : ';
				echo 'Patch '.odbc_result($patchsLinkedToDeletedAppli, 'CODPATC');
				while (odbc_fetch_row($patchsLinkedToDeletedAppli))
				{
					echo ', ';
					echo 'Patch '.odbc_result($patchsLinkedToDeletedAppli, 'CODPATC');
				}
			}
		}
	}
	
	//Fermeture connexion
	closeConnection($c);
?>