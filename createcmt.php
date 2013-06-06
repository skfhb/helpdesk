<?php
//---------------------------------------------------------------//
//	Projet 		: Task Manager									 //
//	Fichier 	: createcmt.php 								 //
//  Description : Permet l'ajout de commentaires    		     //
//	Auteur 		: Hervé Bordeau									 //
// 	Date 		: 04/06/2013							     	 //
//---------------------------------------------------------------//
//Dernière modif le 05/06/2013 par HB

	//Manage le warning du header déjà envoyé
	if (!function_exists('warning_handler'))
	{
		function warning_handler($errno, $errstr) 
		{ 
				//Rien à faire, le header est juste déjà passé
		}
	}
	try
	{
		//Si warning, le gérer par la fonction "warning_handler"
		set_error_handler("warning_handler", E_WARNING);
		//envoyer le header
		header('Content-Type: text/html; charset=iso-8859-1');
		if(session_id() == '')
		{
			session_start();
		}
	}
	catch (Exception $e)
	{
		//Rien à faire, la session a juste déjà été lancée
	}

//- la définition des constantes de l'ensemble de l'application
require_once("include/cst.php");
//- la gestion de la couche d'accès aux données
require_once("include/dal.php");

echo '<div class="addCmt">';
echo '<form id="insertCmtForm" action="insertCmt.php" method="post" target="insertCmt">';
echo '<input type="hidden" name="taskid" value="'.$_GET['id'].'" />';
echo '<input type="hidden" name="userid" value="'.$_SESSION['coduser'].'" />';
echo '<table>';
echo '<tr style="width:100px;height:200px;">';
echo '<td>';
echo '<textarea name="lblcmt" style="width:300px;height:200px;"></textarea>';
echo '</td>';
echo '<td style="padding-left:10px;">';
echo '<select name="slcttypt" style="width:150px;">';
//Récup liste des types de commentaire
if (isset($_SESSION['isAdm']) && $_SESSION['isAdm'])
{
	$typcs = execSQL($c, 'SELECT * FROM TAMGTYPC');
}
else
{
	$typcs = execSQL($c, 'SELECT * FROM TAMGTYPC WHERE PUBTYPC = 1');
}
						
//Rempli le select
while (odbc_fetch_row($typcs))
{
	echo '<option value="'.odbc_result($typcs, 'CODTYPC').'">'.odbc_result($typcs, 'LBLTYPC').'</option>';
}
echo '</select>';
echo '<br /><br />';
echo '<div id="cmtPJ">';
echo 'Pas de pièce jointe';
echo '</div>';
echo '<br /><br />';
echo '<iframe style="width:1px;height:1px;border:0px;" name="fileUpload" seamless></iframe><label class="designFF"><form></form><form id="pjForm" enctype="multipart/form-data" action="fileUpload.php" method="post" target="fileUpload"><input type="hidden" name="idtask" value="'.$_GET['id'].'" /><input type="file" id="inputPJ" name="inputPJ" width="140" style="width:140px;" onchange="newPJ();" /></form></label>';
echo '<br />';
echo '<center>';
echo '<img src="resources/style/plus.png" alt="Ajouter" width="32" height="32" style="cursor:pointer;" onclick="validCmt();" />';
echo '</center>';
echo '</td>';
echo '</tr>';
echo '</table>';
echo '</form>';
echo '<iframe style="height:1px;border:0px;" name="insertCmt" seamless></iframe>';
echo '</div>';

?>