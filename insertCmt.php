<?php
//---------------------------------------------------------------//
//	Projet 		: Task Manager									 //
//	Fichier 	: insertCmt.php 								 //
//  Description : G�re ajout commentaires en DB	    		     //
//	Auteur 		: Herv� Bordeau									 //
// 	Date 		: 05/06/2013							     	 //
//---------------------------------------------------------------//
//Derni�re modif le 05/06/2013 par HB

	header('Content-Type: text/html; charset=iso-8859-1');
	
	//Si on utilise pas la page en include
		//- la d�finition des constantes de l'ensemble de l'application
		include("include/cst.php");
		//- la gestion de la couche d'acc�s aux donn�es
		include("include/dal.php");
		//- la gestion de la couche AJAX
		include("include/ajax.php");
	
	//Ouverture connexion � la DB
	$c = openConnection();	
	
	$codtask = $_POST['taskid'];
	$coduser = $_POST['userid'];
	$codtypc = $_POST['slcttypt'];
	$numlgnc = 1;
	$numlgne = 1;
	$full_txtcom = $_POST['lblcmt'];	
	
	$full_txtcom = str_replace(CHR(13).CHR(10), '<br />', $full_txtcom); 
	
	//R�cup tous les fichiers du r�pertoire concern�
	$dirname = 'upload/Temp'.$codtask.'/';
	$dir = opendir($dirname); 
	$newdirname = 'upload/'.$codtask.'/';
	if (!file_exists($newdirname))
	{
		mkdir($newdirname);
	}
		
	if (strlen($full_txtcom) > 200)
	{
		while (strlen($full_txtcom) > 200)
		{
			$txtcom = substr($full_txtcom, 0, 200);
			$full_txtcom = substr($full_txtcom, 200);
			//Insert en DB : TAMGCOMT
			$stmt = odbc_prepare($c, 'INSERT INTO DEVTAMG.TAMGCOMT (CODTASK, CODUSER, CODTYPC, TSTPCOM, NUMLGNC, TXTCOM) VALUES (?, ?, ?, CURRENT_TIMESTAMP, ?, ?)');
			$res = odbc_execute($stmt, array($codtask, $coduser, $codtypc, $numlgnc, $txtcom));
			$numlgnc++;
		}
		$stmt = odbc_prepare($c, 'INSERT INTO DEVTAMG.TAMGCOMT (CODTASK, CODUSER, CODTYPC, TSTPCOM, NUMLGNC, TXTCOM) VALUES (?, ?, ?, CURRENT_TIMESTAMP, ?, ?)');
		$res = odbc_execute($stmt, array($codtask, $coduser, $codtypc, $numlgnc, $full_txtcom));
	}
	else
	{
		if (strlen($full_txtcom) != 0)
		{
			//Insert en DB : TAMGCOMT
			$stmt = odbc_prepare($c, 'INSERT INTO DEVTAMG.TAMGCOMT (CODTASK, CODUSER, CODTYPC, TSTPCOM, NUMLGNC, TXTCOM) VALUES (?, ?, ?, CURRENT_TIMESTAMP, ?, ?)');
			$res = odbc_execute($stmt, array($codtask, $coduser, $codtypc, $numlgnc, $full_txtcom));
		}
	}
	
	while($file = readdir($dir)) 
	{
		if($file != '.' && $file != '..' && !is_dir($dirname.$file) && $file != 'Thumbs.db')
		{
			if (copy($dirname.$file, $newdirname.$file))
			{
				unlink($dirname.$file);
				//Ouverture dossier d�finitif
				$newdir = opendir($newdirname); 
				if($file != '.' && $file != '..' && !is_dir($newdirname.$file) && $file != 'Thumbs.db')
				{
					//Insert en DB : TAMGFILE
					$filepath = $newdirname.$file;
					$stmt = odbc_prepare($c, 'INSERT INTO DEVTAMG.TAMGFILE (CODTASK, CODUSER, CODTYPC, TSTPCOM, NUMLGNE, FILECOM) VALUES (?, ?, ?, CURRENT_TIMESTAMP, ?, ?)');
					$res = odbc_execute($stmt, array($codtask, $coduser, $codtypc, $numlgne, $filepath));
					$numlgne++;
				}
				//Fermeture dossier d�finitif
				closedir($newdir);
			}
		}
	}
	
	//Fermeture dossier temp
	closedir($dir);
	//Fermeture connexion
	closeConnection($c);
?>