<?php
//------------------------------------------------------------//
//	Projet 		: Task Manager								  //
//	Fichier 	: getstatuts.php 							  //
//  Description : Page de requ�te d'affichage des patchs AJAX //
//	Auteur 		: Herv� Bordeau								  //
// 	Date 		: 13/02/2013							      //
//------------------------------------------------------------//
//Derni�re modif le 13/02/2013 par HB
	
	header('Content-Type: text/html; charset=iso-8859-1');
	
	//Si pas pr�cis� type de requ�te
	if (isset($_POST['option']))
	{
		$option = $_POST['option'];
	}
	//Alors requ�te d'affichage
	else
	{
		$option = 'get';
	}
	
	//Si on utilise pas la page en include
	if (isset($_POST['option']) && $_POST['option'] != "")
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
	if ($option == 'get')
	{
		$sql = 'SELECT * FROM TAMGSTAT';
		
		//R�cup liste des statuts
		$statuts = execSQL($c, $sql);
			
		//On d�finit un compteur pour les statuts
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
			//Incr�mentation du compteur
			$nbStatuts++;
		}
		//Si aucun statut trouv�, afficher message d'erreur
		if ($nbStatuts == 0)
		{
			echo '<b>D�sol�, aucun statut n\'a �t� trouv�.</b>';
		}
	
	}
	//Si mode insert
	elseif ($option == 'insert')
	{
		//R�cup ID du dernier statut
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
		//R�cup liste des t�ches li�es au statut � supprimer
		$sql = 'SELECT * FROM TAMGHSTS WHERE CODSTS = '.$_POST['id'];
		
		
		$tasksLinkedToDeletedStatut = execSQL($c, $sql);
			
		//Compte le nombre de lignes
		$nbTasks = getNumRows($tasksLinkedToDeletedStatut);
	
		//Si aucune t�che
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
			//Avertir l'utilisateur et affiche les n� de t�ches li�es
			echo 'Impossible de supprimer ce statut, des t�ches y sont li�es : ';
			echo '<br />';
			while (odbc_fetch_row($tasksLinkedToDeletedStatut))
			{
				echo 'T�che '.odbc_result($tasksLinkedToDeletedStatut, 'CODTASK');
				echo '<br />';
			}
		}
	}
	
	//Fermeture connexion
	closeConnection($c);
?>