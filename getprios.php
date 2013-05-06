<?php
//------------------------------------------------------------//
//	Projet 		: Task Manager								  //
//	Fichier 	: getprios.php 							  	  //
//  Description : Page de requ�te d'affichage des prios AJAX  //
//	Auteur 		: Herv� Bordeau								  //
// 	Date 		: 12/02/2013							      //
//------------------------------------------------------------//
//Derni�re modif le 12/02/2013 par HB
	
	header('Content-Type: text/html; charset=iso-8859-1');
	
	//Si on utilise pas la page en include
	if (isset($_POST['option']) && $_POST['option'] != "")
	{
		//- la d�finition des constantes de l'ensemble de l'priocation
		include("include/cst.php");
		//- la gestion de la couche d'acc�s aux donn�es
		include("include/dal.php");
		//- la gestion de la couche AJAX
		include("include/ajax.php");
	}
	
	//Ouverture connexion � la DB
	$c = openConnection();	
	
	//Par d�faut, mode affichage
	if (empty($_POST['option']) || $_POST['option'] == 'get')
	{
		$sql = 'SELECT * FROM TAMGPRIO';
		
		//R�cup liste des prios
		$prios = execSQL($c, $sql);
			
		//On d�finit un compteur pour les prios
		$nbPrios = 0;
		
		while (odbc_fetch_row($prios))
		{
			//Affichage d'un degr� de prio
			echo '<div class="prioLine" id="'.odbc_result($prios, 'CODPRIO').'">';
			echo '<input type="button" value="Supprimer" onclick="delPrio('.odbc_result($prios, 'CODPRIO').')" />';
			echo '<b>'.odbc_result($prios, 'VALPRIO').'</b>';
			echo '</div>';
			//Incr�mentation du compteur
			$nbPrios++;
		}
		//Si aucune prio trouv�e, afficher message d'erreur
		if ($nbPrios == 0)
		{
			echo '<b>D�sol�, aucun degr� de priorit� n\'a �t� trouv�.</b>';
		}
	
	}
	//Si mode insert
	elseif (isset($_POST['option']) && $_POST['option'] == 'insert')
	{
		//R�cup ID du dernier prio
		$getMaxID = execSQL($c, 'SELECT MAX(CODPRIO) FROM TAMGPRIO');
		while (odbc_fetch_row($getMaxID))
		{
			$maxID = odbc_result($getMaxID, 1)+1;
		}
		//Insert en DB
		$stmt = odbc_prepare($c, 'INSERT INTO TAMGPRIO (CODPRIO, VALPRIO) VALUES (?, ?)');
		$res = odbc_execute($stmt, array($maxID, utf8_decode($_POST['name'])));
	}
	//Si mode delete
	elseif (isset($_POST['option']) && $_POST['option'] == 'delete')
	{		
		//R�cup liste des t�ches li�es au prio � supprimer
		$sql = 'SELECT * FROM TAMGTASK WHERE CODPRIO = '.$_POST['id'];
		
		
		$tasksLinkedToDeletedPrio = execSQL($c, $sql);
			
		//Compte le nombre de lignes
		$nbTasks = getNumRows($tasksLinkedToDeletedPrio);
	
		//Si aucune t�che
		if ($nbTasks == 0)
		{
			//Effectue le delete
			$stmt = odbc_prepare($c, 'DELETE FROM TAMGPRIO WHERE CODPRIO = ?');
			$res = odbc_execute($stmt, array($_POST['id']));
		}
		//Sinon
		else
		{
			//Avertir l'utilisateur et affiche les n� de t�ches li�es
			echo 'Impossible de supprimer ce prio, des t�ches y sont li�es : ';
			echo '<br />';
			while (odbc_fetch_row($tasksLinkedToDeletedPrio))
			{
				echo 'T�che '.odbc_result($tasksLinkedToDeletedPrio, 'CODTASK');
				echo '<br />';
			}
		}
	}
	
	//Fermeture connexion
	closeConnection($c);
?>