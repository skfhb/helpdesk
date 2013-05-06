<?php
//------------------------------------------------------------//
//	Projet 		: Task Manager								  //
//	Fichier 	: menu.php 							  		  //
//  Description : Gère l'affichage du menu					  //
//	Auteur 		: Hervé Bordeau								  //
// 	Date 		: 12/02/2013							      //
//------------------------------------------------------------//
//Dernière modif le 14/02/2013 par HB

?>
<img src="resources/style/menu-top.png" alt="Deco_top" />
<br />
<ul id="menuItems">
<li class="btn" onclick="loadPage('listtask.php')">Gérer les tâches</li>
<br />
<li class="btn" onclick="loadPage('createtask.php');setTimeout(function() { setDatePicker(); }, 400);">Créer une tâche</li>
<br />
<li class="btn" onclick="loadPage('dettask.php')">Dettask</li>
<br />
<li class="btn" onclick="loadPage('statuts.php')">Statuts</li>
<br />
<li class="btn" onclick="loadPage('patch.php')">Patchs</li>
<br />
<li class="btn" onclick="loadPage('appli.php')">Applications</li>
<br />
<li class="btn" onclick="loadPage('prio.php')">Degrés de priorité</li>
<br />
<li class="btn" onclick="loadPage('typt.php')">Types de tâche</li>
<br />
<li class="btn" onclick="loadPage('typc.php')">Types de commentaire</li>
<br />
</ul>
<br />
<img src="resources/style/menu-bottom.png" alt="Deco_bottom" />