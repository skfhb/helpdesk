<?php
//------------------------------------------------------------------------------//
//	Projet 		: Task Manager								  					//
//	Fichier 	: gettypcs.php 							  	  					//
//  Description : Page de requ�te d'affichage des types de commentaire AJAX  	//
//	Auteur 		: Herv� Bordeau								  					//
// 	Date 		: 15/02/2013							      					//
//------------------------------------------------------------------------------//
//Derni�re modif le 15/02/2013 par HB
if(session_id() == '')
{
	session_start();
}
	
	//Si on utilise pas la page en include
	if (isset($_POST['option']) && $_POST['option'] != "")
	{
		header('Content-Type: text/html; charset=iso-8859-1');
		//- la d�finition des constantes de l'ensemble de l'typccation
		include("include/cst.php");
		//- la gestion de la couche d'acc�s aux donn�es
		include("include/dal.php");
	}
	
	//Ouverture connexion � la DB
	$c = openConnection();	
	
	//Par d�faut, mode affichage
	if (empty($_POST['option']) || $_POST['option'] == 'get')
	{
		$sql = 'SELECT * FROM TAMGTYPC';
		
		//R�cup liste des types de commentaire
		$typcs = execSQL($c, $sql);
			
		//On d�finit un compteur pour les types de commentaire
		$nbTypcs = 0;
		
		while (odbc_fetch_row($typcs))
		{
			//Affichage d'un type de commentaire
			echo '<div class="typcLine" id="'.odbc_result($typcs, 'CODTYPC').'">';
			echo '<input type="button" value="Supprimer" onclick="delTypc('.odbc_result($typcs, 'CODTYPC').')" />';
			echo '<b>'.odbc_result($typcs, 'LBLTYPC').'</b>';
			//Si admin, checkbox non-disabled
			if (isset($_SESSION['isAdm']) && $_SESSION['isAdm'])
			{
				//Si type de commentaire public, alors afficher checkbox coch�e
				if (odbc_result($typcs, 'PUBTYPC') == 1)
				{
					echo '<input type="checkbox" value="Public" onclick="changePubTypc('.odbc_result($typcs, 'CODTYPC').');" />Public';
				}
				//Sinon l'afficher d�coch�e
				else
				{
					echo '<input type="checkbox" value="Public" onclick="changePubTypc('.odbc_result($typcs, 'CODTYPC').');" checked />Priv�';
				}
			}
			else	//Sinon, checkbox disabled
			{
				//Si type de commentaire public, alors afficher checkbox coch�e
				if (odbc_result($typcs, 'PUBTYPC') == 1)
				{
					echo '<input type="checkbox" value="Public"  disabled="disabled" />Public';
				}
				//Sinon l'afficher d�coch�e
				else
				{
					echo '<input type="checkbox" value="Public"  disabled="disabled" checked />Priv�';
				}
			}
			echo '</div>';
			//Incr�mentation du compteur
			$nbTypcs++;
		}
		//Si aucun type de commentaire trouv�, afficher message d'erreur
		if ($nbTypcs == 0)
		{
			echo '<b>D�sol�, aucun type de commentaire n\'a �t� trouv�.</b>';
		}
	
	}
	//Si mode insert
	elseif (isset($_POST['option']) && $_POST['option'] == 'insert')
	{
		//R�cup ID du dernier type de commentaire
		$getMaxID = execSQL($c, 'SELECT MAX(CODTYPC) FROM TAMGTYPC');
		while (odbc_fetch_row($getMaxID))
		{
			$maxID = odbc_result($getMaxID, 1)+1;
		}
		//Insert en DB
		$stmt = odbc_prepare($c, 'INSERT INTO TAMGTYPC (CODTYPC, LBLTYPC, PUBTYPC) VALUES (?, ?, ?)');
		$res = odbc_execute($stmt, array($maxID, utf8_decode($_POST['name']), $_POST['public']));
	}
	//Si mode delete
	elseif (isset($_POST['option']) && $_POST['option'] == 'delete')
	{		
		//R�cup liste des commentaires du type de commentaire � supprimer
		$sql = 'SELECT * FROM TAMGCOMT WHERE CODTYPC = '.$_POST['id'];
		$comtLinkedToDeletedTypc = execSQL($c, $sql);
		
		//Compte le nombre de lignes
		$nbComt = getNumRows($comtLinkedToDeletedTypc);
		$nbComtComt = $nbComt;
	
		//R�cup liste des commentaires du type de commentaire � supprimer
		$sql = 'SELECT * FROM TAMGFILE WHERE CODTYPC = '.$_POST['id'];
		$filesLinkedToDeletedTypc = execSQL($c, $sql);
		
		//Compte le nombre de lignes
		$nbComt += getNumRows($filesLinkedToDeletedTypc);
		$nbComtFile = $nbComt - $nbComtComt;
	
		//Si aucune t�che
		if ($nbComt == 0)
		{
			//Effectue le delete
			$stmt = odbc_prepare($c, 'DELETE FROM TAMGTYPC WHERE CODTYPC = ?');
			$res = odbc_execute($stmt, array($_POST['id']));
		}
		//Sinon
		else
		{
			//Avertir l'utilisateur et affiche les n� de t�ches li�es
			echo 'Impossible de supprimer ce type de commentaire, des commentaires y sont li�s : ';
			if ($nbComtComt != 0 && $nbComtFile == 0)
			{
				echo 'T�che '.odbc_result($comtLinkedToDeletedTypc, 'CODTASK');
				while (odbc_fetch_row($comtLinkedToDeletedTypc))
				{
					echo ', ';
					echo 'T�che '.odbc_result($comtLinkedToDeletedTypc, 'CODTASK');
				}
			}
			elseif ($nbComtComt == 0 && $nbComtFile != 0)
			{
				echo 'T�che '.odbc_result($filesLinkedToDeletedTypc, 'CODTASK');
				while (odbc_fetch_row($filesLinkedToDeletedTypc))
				{
					echo ', ';
					echo 'T�che '.odbc_result($filesLinkedToDeletedTypc, 'CODTASK');
				}
			}
			else
			{
				echo 'T�che '.odbc_result($comtLinkedToDeletedTypc, 'CODTASK');
				while (odbc_fetch_row($comtLinkedToDeletedTypc))
				{
					echo ', ';
					echo 'T�che '.odbc_result($comtLinkedToDeletedTypc, 'CODTASK');
				}
				while (odbc_fetch_row($filesLinkedToDeletedTypc))
				{
					echo ', ';
					echo 'T�che '.odbc_result($filesLinkedToDeletedTypc, 'CODTASK');
				}
			}
			
		}
	}
	elseif (isset($_POST['option']) && $_POST['option'] == 'changepubtypc')
	{		
		//R�cup statut public du typc concern�
		$sql = 'SELECT PUBTYPC FROM TAMGTYPC WHERE CODTYPC = '.$_POST['id'];
		$statutPubTypc = execSQL($c, $sql);
		while (odbc_fetch_row($statutPubTypc))
		{
			if (odbc_result($statutPubTypc, 'PUBTYPC') == 1)
			{
				$stmt = odbc_prepare($c, 'UPDATE TAMGTYPC SET PUBTYPC = 0 WHERE CODTYPC = ?');
				$res = odbc_execute($stmt, array($_POST['id']));
			}
			else
			{
				$stmt = odbc_prepare($c, 'UPDATE TAMGTYPC SET PUBTYPC = 1 WHERE CODTYPC = ?');
				$res = odbc_execute($stmt, array($_POST['id']));
			}
		}
	}
	//Fermeture connexion
	closeConnection($c);
?>