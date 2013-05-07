<?php
//----------------------------------------------------------------------//
//	Projet 		: Task Manager								  			//
//	Fichier 	: gettypts.php 							  	  			//
//  Description : Page de requête d'affichage des types de tâche AJAX  	//
//	Auteur 		: Hervé Bordeau								  			//
// 	Date 		: 15/02/2013							      			//
//----------------------------------------------------------------------//
//Dernière modif le 15/02/2013 par HB
	
	
	//Si on utilise pas la page en include
	if (isset($_POST['option']) && $_POST['option'] != "")
	{
		header('Content-Type: text/html; charset=iso-8859-1');
		//- la définition des constantes de l'ensemble de l'typtcation
		include("include/cst.php");
		//- la gestion de la couche d'accès aux données
		include("include/dal.php");
	}
	
	//Ouverture connexion à la DB
	$c = openConnection();	
	
	//Par défaut, mode affichage
	if (empty($_POST['option']) || $_POST['option'] == 'get')
	{
		$sql = 'SELECT * FROM TAMGTYPT';
		
		//Récup liste des types de tâche
		$typts = execSQL($c, $sql);
			
		//On définit un compteur pour les types de tâche
		$nbTypts = 0;
		
		while (odbc_fetch_row($typts))
		{
			//Affichage d'un type de tâche
			echo '<div class="typtLine" id="'.odbc_result($typts, 'CODTYPT').'">';
			echo '<input type="button" value="Supprimer" onclick="delTypt('.odbc_result($typts, 'CODTYPT').')" />';
			echo '<b>'.odbc_result($typts, 'LBLTYPT').'</b>';
			echo '</div>';
			//Incrémentation du compteur
			$nbTypts++;
		}
		//Si aucun type de tâche trouvé, afficher message d'erreur
		if ($nbTypts == 0)
		{
			echo '<b>Désolé, aucun type de tâche n\'a été trouvé.</b>';
		}
	
	}
	//Si mode insert
	elseif (isset($_POST['option']) && $_POST['option'] == 'insert')
	{
		//Récup ID du dernier type de tâche
		$getMaxID = execSQL($c, 'SELECT MAX(CODTYPT) FROM TAMGTYPT');
		while (odbc_fetch_row($getMaxID))
		{
			$maxID = odbc_result($getMaxID, 1)+1;
		}
		//Insert en DB
		$stmt = odbc_prepare($c, 'INSERT INTO TAMGTYPT (CODTYPT, LBLTYPT) VALUES (?, ?)');
		$res = odbc_execute($stmt, array($maxID, utf8_decode($_POST['name'])));
	}
	//Si mode delete
	elseif (isset($_POST['option']) && $_POST['option'] == 'delete')
	{		
		//Récup liste des tâches du type de tâche à supprimer
		$sql = 'SELECT * FROM TAMGTASK WHERE CODTYPT = '.$_POST['id'];
		
		
		$tasksLinkedToDeletedTypt = execSQL($c, $sql);
			
		//Compte le nombre de lignes
		$nbTasks = getNumRows($tasksLinkedToDeletedTypt);
	
		//Si aucune tâche
		if ($nbTasks == 0)
		{
			//Effectue le delete
			$stmt = odbc_prepare($c, 'DELETE FROM TAMGTYPT WHERE CODTYPT = ?');
			$res = odbc_execute($stmt, array($_POST['id']));
		}
		//Sinon
		else
		{
			//Avertir l'utilisateur et affiche les n° de tâches liées
			echo 'Impossible de supprimer ce type de tâche, des tâches y sont liées : ';
			echo 'Tâche '.odbc_result($tasksLinkedToDeletedTypt, 'CODTASK');
			while (odbc_fetch_row($tasksLinkedToDeletedTypt))
			{
				echo ', ';
				echo 'Tâche '.odbc_result($tasksLinkedToDeletedTypt, 'CODTASK');
			}
		}
	}
	
	//Fermeture connexion
	closeConnection($c);
?>