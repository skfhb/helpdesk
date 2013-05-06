<?php
//------------------------------------------------------------//
//	Projet 		: Task Manager								  //
//	Fichier 	: getpatchs.php 							  //
//  Description : Page de requête d'affichage des patchs AJAX //
//	Auteur 		: Hervé Bordeau								  //
// 	Date 		: 12/02/2013							      //
//------------------------------------------------------------//
//Dernière modif le 12/02/2013 par HB
	
	header('Content-Type: text/html; charset=iso-8859-1');
	
	//Si on utilise pas la page en include
	if (isset($_POST['appli']) && $_POST['appli'] != "")
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
	if (empty($_POST['option']))
	{
		$sql = 'SELECT * FROM TAMGPATC';
	
		//Si appli sélectionnée, charger les patchs de cette appli
		if (isset($_POST['appli']) && $_POST['appli'] != "")
		{
			$sql .= ' WHERE CODAPP = '.$_POST['appli'];
		}
		//Si appli non sélectionnée, charger les patchs d'une appli par défaut (cst.php)
		else
		{
			$sql .= ' WHERE CODAPP = '._DEFAULT_PATCHS_TOLOAD;
		}
		
		//Récup liste des patchs
		$patchs = execSQL($c, $sql);
			
		//On définit un compteur pour les patchs
		$nbPatchs = 0;
		
		while (odbc_fetch_row($patchs))
		{
			//Affichage du patch
			echo '<div class="patchLine" id="'.odbc_result($patchs, 'CODPATC').'">';
			echo '<input type="button" value="Supprimer" onclick="delPatch('.odbc_result($patchs, 'CODPATC').')" />';
			echo '<b>'.odbc_result($patchs, 'NAMPATC').'</b>';
			echo '</div>';
			//Incrémentation du compteur
			$nbPatchs++;
		}
		//Si aucun patch trouvé, afficher message d'erreur
		if ($nbPatchs == 0)
		{
			echo '<b>Désolé, aucun patch n\'a été trouvé.</b>';
		}
	
	}
	//Si mode insert
	elseif (isset($_POST['option']) && $_POST['option'] == 'insert')
	{
		//Récup ID du dernier patch
		$getMaxID = execSQL($c, 'SELECT MAX(CODPATC) FROM TAMGPATC');
		while (odbc_fetch_row($getMaxID))
		{
			$maxID = odbc_result($getMaxID, 1)+1;
		}
		//Insert en DB
		$stmt = odbc_prepare($c, 'INSERT INTO TAMGPATC (CODPATC, NAMPATC, CODAPP) VALUES (?, ?, ?)');
		$res = odbc_execute($stmt, array($maxID, utf8_decode($_POST['name']), $_POST['appli']));
	}
	//Si mode delete
	elseif (isset($_POST['option']) && $_POST['option'] == 'delete')
	{		
		//Récup liste des tâches liées au patch à supprimer
		$sql = 'SELECT * FROM TAMGPATA WHERE CODPATC = '.$_POST['id'];
		
		
		$tasksLinkedToDeletedPatch = execSQL($c, $sql);
			
		//Compte le nombre de lignes
		$nbTasks = getNumRows($tasksLinkedToDeletedPatch);
	
		//Si aucune tâche
		if ($nbTasks == 0)
		{
			//Effectue le delete
			$stmt = odbc_prepare($c, 'DELETE FROM TAMGPATC WHERE CODPATC = ?');
			$res = odbc_execute($stmt, array($_POST['id']));
		}
		//Sinon
		else
		{
			//Avertir l'utilisateur et affiche les n° de tâches liées
			echo 'Impossible de supprimer ce patch, des tâches y sont liées : ';
			echo '<br />';
			while (odbc_fetch_row($tasksLinkedToDeletedPatch))
			{
				echo 'Tâche '.odbc_result($tasksLinkedToDeletedPatch, 'CODTASK');
				echo '<br />';
			}
		}
	}
	
	//Fermeture connexion
	closeConnection($c);
?>