<?php
//------------------------------------------------------------//
//	Projet 		: Task Manager								  //
//	Fichier 	: menu.php 							  		  //
//  Description : G�re l'affichage du menu					  //
//	Auteur 		: Herv� Bordeau								  //
// 	Date 		: 12/02/2013							      //
//------------------------------------------------------------//
//Derni�re modif le 14/02/2013 par HB

?>
<img src="resources/style/menu-top.png" alt="Deco_top" />
<br />
<ul id="menuItems">
<li class="btn" onclick="loadPage('listtask.php')">G�rer les t�ches</li>
<br />
<li class="btn" onclick="loadPage('createtask.php');setTimeout(function() { setDatePicker(); }, 400);">Cr�er une t�che</li>
<br />
<li class="btn" onclick="loadPage('dettask.php')">Dettask</li>
<br />
<li class="btn" onclick="loadPage('statuts.php')">Statuts</li>
<br />
<li class="btn" onclick="loadPage('patch.php')">Patchs</li>
<br />
<li class="btn" onclick="loadPage('appli.php')">Applications</li>
<br />
<li class="btn" onclick="loadPage('prio.php')">Degr�s de priorit�</li>
<br />
<li class="btn" onclick="loadPage('typt.php')">Types de t�che</li>
<br />
<li class="btn" onclick="loadPage('typc.php')">Types de commentaire</li>
<br />
</ul>
<br />
<img src="resources/style/menu-bottom.png" alt="Deco_bottom" />