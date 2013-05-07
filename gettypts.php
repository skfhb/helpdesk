<?php
//----------------------------------------------------------------------//
//	Projet 		: Task Manager								  			//
//	Fichier 	: gettypts.php 							  	  			//
//  Description : Page de requ�te d'affichage des types de t�che AJAX  	//
//	Auteur 		: Herv� Bordeau								  			//
// 	Date 		: 15/02/2013							      			//
//----------------------------------------------------------------------//
//Derni�re modif le 15/02/2013 par HB
	
	
	//Si on utilise pas la page en include
	if (isset($_POST['option']) && $_POST['option'] != "")
	{
		header('Content-Type: text/html; charset=iso-8859-1');
		//- la d�finition des constantes de l'ensemble de l'typtcation
		include("include/cst.php");
		//- la gestion de la couche d'acc�s aux donn�es
		include("include/dal.php");
	}
	
	//Ouverture connexion � la DB
	$c = openConnection();	
	
	//Par d�faut, mode affichage
	if (empty($_POST['option']) || $_POST['option'] == 'get')
	{
		$sql = 'SELECT * FROM TAMGTYPT';
		
		//R�cup liste des types de t�che
		$typts = execSQL($c, $sql);
			
		//On d�finit un compteur pour les types de t�che
		$nbTypts = 0;
		
		while (odbc_fetch_row($typts))
		{
			//Affichage d'un type de t�che
			echo '<div class="typtLine" id="'.odbc_result($typts, 'CODTYPT').'">';
			echo '<input type="button" value="Supprimer" onclick="delTypt('.odbc_result($typts, 'CODTYPT').')" />';
			echo '<b>'.odbc_result($typts, 'LBLTYPT').'</b>';
			echo '</div>';
			//Incr�mentation du compteur
			$nbTypts++;
		}
		//Si aucun type de t�che trouv�, afficher message d'erreur
		if ($nbTypts == 0)
		{
			echo '<b>D�sol�, aucun type de t�che n\'a �t� trouv�.</b>';
		}
	
	}
	//Si mode insert
	elseif (isset($_POST['option']) && $_POST['option'] == 'insert')
	{
		//R�cup ID du dernier type de t�che
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
		//R�cup liste des t�ches du type de t�che � supprimer
		$sql = 'SELECT * FROM TAMGTASK WHERE CODTYPT = '.$_POST['id'];
		
		
		$tasksLinkedToDeletedTypt = execSQL($c, $sql);
			
		//Compte le nombre de lignes
		$nbTasks = getNumRows($tasksLinkedToDeletedTypt);
	
		//Si aucune t�che
		if ($nbTasks == 0)
		{
			//Effectue le delete
			$stmt = odbc_prepare($c, 'DELETE FROM TAMGTYPT WHERE CODTYPT = ?');
			$res = odbc_execute($stmt, array($_POST['id']));
		}
		//Sinon
		else
		{
			//Avertir l'utilisateur et affiche les n� de t�ches li�es
			echo 'Impossible de supprimer ce type de t�che, des t�ches y sont li�es : ';
			echo 'T�che '.odbc_result($tasksLinkedToDeletedTypt, 'CODTASK');
			while (odbc_fetch_row($tasksLinkedToDeletedTypt))
			{
				echo ', ';
				echo 'T�che '.odbc_result($tasksLinkedToDeletedTypt, 'CODTASK');
			}
		}
	}
	
	//Fermeture connexion
	closeConnection($c);
?>