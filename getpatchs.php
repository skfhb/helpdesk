<?php
//------------------------------------------------------------//
//	Projet 		: Task Manager								  //
//	Fichier 	: getpatchs.php 							  //
//  Description : Page de requ�te d'affichage des patchs AJAX //
//	Auteur 		: Herv� Bordeau								  //
// 	Date 		: 12/02/2013							      //
//------------------------------------------------------------//
//Derni�re modif le 12/02/2013 par HB
	
	header('Content-Type: text/html; charset=iso-8859-1');
	
	//Si on utilise pas la page en include
	if (isset($_POST['appli']) && $_POST['appli'] != "")
	{
		//- la d�finition des constantes de l'ensemble de l'application
		include("include/cst.php");
		//- la gestion de la couche d'acc�s aux donn�es
		include("include/dal.php");
		//- la gestion de la couche AJAX
		include("include/ajax.php");
	}
	
	//Ouverture connexion � la DB
	$c = openConnection();	
	
	//Par d�faut, mode affichage
	if (empty($_POST['option']))
	{
		$sql = 'SELECT * FROM TAMGPATC';
	
		//Si appli s�lectionn�e, charger les patchs de cette appli
		if (isset($_POST['appli']) && $_POST['appli'] != "")
		{
			$sql .= ' WHERE CODAPP = '.$_POST['appli'];
		}
		//Si appli non s�lectionn�e, charger les patchs d'une appli par d�faut (cst.php)
		else
		{
			$sql .= ' WHERE CODAPP = '._DEFAULT_PATCHS_TOLOAD;
		}
		
		//R�cup liste des patchs
		$patchs = execSQL($c, $sql);
			
		//On d�finit un compteur pour les patchs
		$nbPatchs = 0;
		
		while (odbc_fetch_row($patchs))
		{
			//Affichage du patch
			echo '<div class="patchLine" id="'.odbc_result($patchs, 'CODPATC').'">';
			echo '<input type="button" value="Supprimer" onclick="delPatch('.odbc_result($patchs, 'CODPATC').')" />';
			echo '<b>'.odbc_result($patchs, 'NAMPATC').'</b>';
			echo '</div>';
			//Incr�mentation du compteur
			$nbPatchs++;
		}
		//Si aucun patch trouv�, afficher message d'erreur
		if ($nbPatchs == 0)
		{
			echo '<b>D�sol�, aucun patch n\'a �t� trouv�.</b>';
		}
	
	}
	//Si mode insert
	elseif (isset($_POST['option']) && $_POST['option'] == 'insert')
	{
		//R�cup ID du dernier patch
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
		//R�cup liste des t�ches li�es au patch � supprimer
		$sql = 'SELECT * FROM TAMGPATA WHERE CODPATC = '.$_POST['id'];
		
		
		$tasksLinkedToDeletedPatch = execSQL($c, $sql);
			
		//Compte le nombre de lignes
		$nbTasks = getNumRows($tasksLinkedToDeletedPatch);
	
		//Si aucune t�che
		if ($nbTasks == 0)
		{
			//Effectue le delete
			$stmt = odbc_prepare($c, 'DELETE FROM TAMGPATC WHERE CODPATC = ?');
			$res = odbc_execute($stmt, array($_POST['id']));
		}
		//Sinon
		else
		{
			//Avertir l'utilisateur et affiche les n� de t�ches li�es
			echo 'Impossible de supprimer ce patch, des t�ches y sont li�es : ';
			echo '<br />';
			while (odbc_fetch_row($tasksLinkedToDeletedPatch))
			{
				echo 'T�che '.odbc_result($tasksLinkedToDeletedPatch, 'CODTASK');
				echo '<br />';
			}
		}
	}
	
	//Fermeture connexion
	closeConnection($c);
?>