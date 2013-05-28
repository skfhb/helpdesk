<?php
//---------------------------------------------------------------//
//	Projet 		: Task Manager									 //
//	Fichier 	: tabslisttask.php								 //
//  Description : Page contenant les tabs de listtask		     //
//	Auteur 		: Hervé Bordeau									 //
// 	Date 		: 21/05/2013							     	 //
//---------------------------------------------------------------//
//Dernière modif le 21/05/2013 par HB

	echo '<div class="toollisttask">';
	echo '<div class="activetab" onclick="activateTab(this, \'filter\');">Filtrer</div>';
	echo '<div class="tab" onclick="activateTab(this, \'search\');">Rechercher</div>';
	echo '<div class="tab" onclick="activateTab(this, \'col\');">Choix des colonnes</div>';
	echo '<div class="tab" onclick="activateTab(this, \'raz\');">Remise &agrave; z&eacute;ro</div>';
	echo '</div>';
	echo '<br />';
	echo '<div id="preferenceslisttask">';
	echo '<br />';
	include('optnfilter.php');
	echo '</div>';
	echo '<br />';
	
?>