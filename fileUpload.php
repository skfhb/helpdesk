<?php
//--------------------------------------------------------------------------//
//	Projet 		: Task Manager								  				//
//	Fichier 	: fileUpload.php 							  				//
//  Description : Page utilisée en iframe pour upload de fichiers via AJAX 	//
//	Auteur 		: Hervé Bordeau								  				//
// 	Date 		: 14/02/2013							      				//
//--------------------------------------------------------------------------//
//Dernière modif le 14/02/2013 par HB
	
	header('Content-Type: text/html; charset=iso-8859-1');
	
	//Si on utilise pas la page en include
		//- la définition des constantes de l'ensemble de l'application
		include("include/cst.php");
		//- la gestion de la couche d'accès aux données
		include("include/dal.php");
		//- la gestion de la couche AJAX
		include("include/ajax.php");
	
	//Ouverture connexion à la DB
	$c = openConnection();	
	
	//Récup ID du dernier statut
	$getMaxID = execSQL($c, 'SELECT MAX(CODSTS) FROM TAMGSTAT');
	while (odbc_fetch_row($getMaxID))
	{
		$maxID = odbc_result($getMaxID, 1)+1;
	}
	
	//Insert en DB
	$stmt = odbc_prepare($c, 'INSERT INTO TAMGSTAT (CODSTS, LBLSTS) VALUES (?, ?)');
	$res = odbc_execute($stmt, array($maxID, $_POST['newstatutname']));
	
	//En plus, si on a reçu le fichier image
	if(isset($_FILES['newstatutimg']))
	{ 
		
		if ($_FILES['newstatutimg']['type'] == 'image/png')	
		{
			$dossier = 'resources/statuts/';
			$fichier = $maxID.'.png';
			//On le déplace dans resources/statuts avec le nom approprié
			move_uploaded_file($_FILES['newstatutimg']['tmp_name'], $dossier . $fichier);
		}
		else
		{
			?>
			<script>alert('Seuls les fichiers PNG sont acceptés');</script>
			<?php
		}
	}
	
	//Fermeture connexion
	closeConnection($c);
?>