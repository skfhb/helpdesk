<?php
//------------------------------------------------------------//
//	Projet 		: Task Manager								  //
//	Fichier 	: getstatuts.php 							  //
//  Description : Page de requête d'affichage des patchs AJAX //
//	Auteur 		: Hervé Bordeau								  //
// 	Date 		: 13/02/2013							      //
//------------------------------------------------------------//
//Dernière modif le 13/02/2013 par HB
	
	header('Content-Type: text/html; charset=iso-8859-1');
	
	//Si pas précisé type de requête
	if (isset($_POST['option']))
	{
		$option = $_POST['option'];
	}
	//Alors requête d'affichage
	else
	{
		$option = 'get';
	}
	
	//Si on utilise pas la page en include
	if (isset($_POST['option']) && $_POST['option'] != "")
	{
		//- la définition des constantes de l'ensemble de l'application
		include("include/cst.php");
		//- la gestion de la couche d'accès aux données
		include("include/dal.php");
		//- la gestion de la couche AJAX
		include("include/ajax.php");
	}
	
	//Ouverture connexion à la DB
	$c = openConnection();	
	
	//Par défaut, mode affichage
	if ($option == 'get')
	{
		$sql = 'SELECT * FROM TAMGSTAT';
		
		//Récup liste des statuts
		$statuts = execSQL($c, $sql);
			
		//On définit un compteur pour les statuts
		$nbStatuts = 0;
		
		while (odbc_fetch_row($statuts))
		{
			//Affichage du statut
			echo '<div class="statutLine" id="'.odbc_result($statuts, 'CODSTS').'">';
			echo '<input type="button" value="Supprimer" onclick="delStatut('.odbc_result($statuts, 'CODSTS').')" />';
			if (file_exists('resources/statuts/'.odbc_result($statuts, 'CODSTS').'.png'))
			{
				echo '<img src="resources/statuts/'.odbc_result($statuts, 'CODSTS').'.png" alt="" width="16" height="16" />';
			}
			echo '<b>'.odbc_result($statuts, 'LBLSTS').'</b>';
			echo '</div>';
			//Incrémentation du compteur
			$nbStatuts++;
		}
		//Si aucun statut trouvé, afficher message d'erreur
		if ($nbStatuts == 0)
		{
			echo '<b>Désolé, aucun statut n\'a été trouvé.</b>';
		}
	
	}
	//Si mode insert
	elseif ($option == 'insert')
	{
		//Récup ID du dernier statut
		$getMaxID = execSQL($c, 'SELECT MAX(CODSTS) FROM TAMGSTAT');
		while (odbc_fetch_row($getMaxID))
		{
			$maxID = odbc_result($getMaxID, 1)+1;
		}
		//Insert en DB
		$stmt = odbc_prepare($c, 'INSERT INTO TAMGSTAT (CODSTS, LBLSTS) VALUES (?, ?)');
		$res = odbc_execute($stmt, array($maxID, utf8_decode($_POST['name'])));
	}
	//Si mode delete
	elseif ($option == 'delete')
	{		
		//Récup liste des tâches liées au statut à supprimer
		$sql = 'SELECT * FROM TAMGHSTS WHERE CODSTS = '.$_POST['id'];
		
		
		$tasksLinkedToDeletedStatut = execSQL($c, $sql);
			
		//Compte le nombre de lignes
		$nbTasks = getNumRows($tasksLinkedToDeletedStatut);
	
		//Si aucune tâche
		if ($nbTasks == 0)
		{
			//Effectue le delete
			$stmt = odbc_prepare($c, 'DELETE FROM TAMGSTAT WHERE CODSTS = ?');
			$res = odbc_execute($stmt, array($_POST['id']));
			unlink('resources/statuts/'.$_POST['id'].'.png');
		}
		//Sinon
		else
		{
			//Avertir l'utilisateur et affiche les n° de tâches liées
			echo 'Impossible de supprimer ce statut, des tâches y sont liées : ';
			echo '<br />';
			while (odbc_fetch_row($tasksLinkedToDeletedStatut))
			{
				echo 'Tâche '.odbc_result($tasksLinkedToDeletedStatut, 'CODTASK');
				echo '<br />';
			}
		}
	}
	
	//Fermeture connexion
	closeConnection($c);
?>