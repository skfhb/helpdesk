<?php
//------------------------------------------------------------//
//	Projet 		: Task Manager								  //
//	Fichier 	: menu.php 							  		  //
//  Description : Gère l'affichage du menu					  //
//	Auteur 		: Hervé Bordeau								  //
// 	Date 		: 12/02/2013							      //
//------------------------------------------------------------//
//Dernière modif le 14/02/2013 par HB

if(session_id() == '')
{
	session_start();
}
?>
<img src="resources/style/menu-top.png" alt="Deco_top" />
<br />
<ul id="menuItems">
<li class="btn" onclick="loadPage('parlisttask.php')">G&eacute;rer les t&acirc;ches</li>
<br />
<?php
	if (isset($_SESSION['login']))
	{
		echo '<li class="btn" onclick="loadPage(\'createtask.php\');setTimeout(function() { setDatePicker(); }, 400);">Cr&eacute;er une t&acirc;che</li>';
		echo '<br />';
	}
?>
<?php
	if (isset($_SESSION['isAdm']) && $_SESSION['isAdm'])
	{
		echo '<li class="btn" onclick="loadPage(\'usergst.php\')">Gestion des utilisateurs</li>';
		echo '<br />';
		echo '<li class="btn" onclick="loadPage(\'statuts.php\')">Statuts</li>';
		echo '<br />';
		echo '<li class="btn" onclick="loadPage(\'patch.php\')">Patchs</li>';
		echo '<br />';
		echo '<li class="btn" onclick="loadPage(\'appli.php\')">Applications</li>';
		echo '<br />';
		echo '<li class="btn" onclick="loadPage(\'prio.php\')">Degr&eacute;s de priorit&eacute;</li>';
		echo '<br />';
		echo '<li class="btn" onclick="loadPage(\'typt.php\')">Types de t&acirc;che</li>';
		echo '<br />';
		echo '<li class="btn" onclick="loadPage(\'typc.php\')">Types de commentaire</li>';
		echo '<br />';
	}
?>
</ul>
<br />
<img src="resources/style/menu-bottom.png" alt="Deco_bottom" />