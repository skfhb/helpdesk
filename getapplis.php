<?php
//------------------------------------------------------------//
//	Projet 		: Task Manager								  //
//	Fichier 	: getapplis.php 							  //
//  Description : Page de requête d'affichage des applis AJAX //
//	Auteur 		: Hervé Bordeau								  //
// 	Date 		: 12/02/2013							      //
//------------------------------------------------------------//
//Dernière modif le 12/02/2013 par HB
	
	//Si on utilise pas la page en include
	if (isset($_POST['option']) && $_POST['option'] != "")
	{
		header('Content-Type: text/html; charset=iso-8859-1');
		//- la définition des constantes de l'ensemble de l'application
		include("include/cst.php");
		//- la gestion de la couche d'accès aux données
		include("include/dal.php");
	}
	
	//Ouverture connexion à la DB
	$c = openConnection();	
	
	//Par défaut, mode affichage
	if (empty($_POST['option']) || $_POST['option'] == 'get')
	{
		$sql = 'SELECT * FROM TAMGAPPL';
		
		//Récup liste des applis
		$applis = execSQL($c, $sql);
			
		//On définit un compteur pour les applis
		$nbApplis = 0;
		
		while (odbc_fetch_row($applis))
		{
			//Affichage de l'appli
			echo '<div class="appliLine" id="'.odbc_result($applis, 'CODAPP').'">';
			echo '<input type="button" value="Supprimer" onclick="delAppli('.odbc_result($applis, 'CODAPP').')" />';
			echo '<b>'.odbc_result($applis, 'NAMAPP').'</b>';
			echo '</div>';
			//Incrémentation du compteur
			$nbApplis++;
		}
		//Si aucune appli trouvée, afficher message d'erreur
		if ($nbApplis == 0)
		{
			echo '<b>Désolé, aucune application n\'a été trouvée.</b>';
		}
	
	}
	//Si mode insert
	elseif (isset($_POST['option']) && $_POST['option'] == 'insert')
	{
		//Récup ID du dernier appli
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
		//Récup liste des tâches liées au appli à supprimer
		$sql = 'SELECT * FROM TAMGAPTA WHERE CODAPP = '.$_POST['id'];
		
		$tasksLinkedToDeletedAppli = execSQL($c, $sql);
			
		$sql = 'SELECT * FROM TAMGPATC WHERE CODAPP = '.$_POST['id'];
		
		$patchsLinkedToDeletedAppli = execSQL($c, $sql);
		
		//Compte le nombre de lignes
		$nbTasks = getNumRows($tasksLinkedToDeletedAppli);
		$nbPatchs = getNumRows($patchsLinkedToDeletedAppli);
	
		//Si aucune tâche et aucun patch
		if ($nbTasks == 0 && $nbPatchs == 0)
		{
			//Effectue le delete
			$stmt = odbc_prepare($c, 'DELETE FROM TAMGAPPL WHERE CODAPP = ?');
			$res = odbc_execute($stmt, array($_POST['id']));
		}
		//Sinon
		else
		{
			//Avertir l'utilisateur et affiche les n° de tâches liées
			if ($nbTasks != 0)
			{
				echo 'Impossible de supprimer cette application, des tâches y sont liées : ';
				echo 'Tâche '.odbc_result($tasksLinkedToDeletedAppli, 'CODTASK');
				while (odbc_fetch_row($tasksLinkedToDeletedAppli))
				{
					echo ', ';
					echo 'Tâche '.odbc_result($tasksLinkedToDeletedAppli, 'CODTASK');
				}
			}
			echo '                                                                      ';
			if ($nbPatchs != 0)
			{
				echo 'Impossible de supprimer cette application, des patchs y sont liés : ';
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