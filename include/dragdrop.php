<?php
//------------------------------------------------------------//
//	Projet 		: Task Manager								  //
//	Fichier 	: dragdrop.php	 							  //
//  Description : Gère les fonctions de Drag & Drop			  //
//	Auteur 		: Hervé Bordeau								  //
// 	Date 		: 11/03/2013							      //
//------------------------------------------------------------//
//Dernière modif le 11/03/2013 par HB

?>
<script>
//variables-------------------------------------------------------------
var div = new Object();
var coorT = 0;
var index = 1;
var maxOffsetTop = 9999;

function reattribColors()
{
	var nextOffsetTop = document.getElementsByClassName('headertaskhead').offsetBottom;
	var tasks = new Array();
	var lines = document.getElementsByClassName('headertaskpair');
	for (var i = 0 ; i < lines.length ; i++)
	{
		tasks[i] = lines[i];
	}

	tasklength = tasks.length;
	
	lines = document.getElementsByClassName('headertaskimpair');
	for (var i = 0 ; i < lines.length ; i++)
	{
		tasks[i+tasklength] = lines[i];
	}
	tasks = insertSort(tasks);
	for (var i = 0 ; i < tasks.length ; i++)
	{
		if (i % 2 == 1)
		{
			tasks[i].className = 'headertaskpair';
		}
		else
		{
			tasks[i].className = 'headertaskimpair';
		}
		tasks[i].style.top = nextOffsetTop + 'px';
		nextOffsetTop += tasks[i].height;
	}
	
}


//selection d'une div + coordonnées-------------------------------------
function selectImage(e)
{
	$(function() 
		{
			$('.sortablecontainer').sortable(
				{
					cursor: 'move',     	 // Change le curseur en croix de déplacement
					axis: 'y',           	 // Autorise le drag and drop uniquement sur l'axe vertical
					handle: '.headerelementmove',
					containment: "#pageBody",
					stop: function( event, ui ) 
					{
						reattribColors();
					}
				}
			);
		}
	);
}

//fonction d'initiation des objets---------------------------------------
function init()
{
	var  allGrafs=document.getElementsByTagName("div");
	for(i=0;i<allGrafs.length;i++){
		if (allGrafs[i].className == 'headertaskimpair' || allGrafs[i].className == 'headertaskpair')
		{
			if (allGrafs[i].id != '')
			{
				var button = allGrafs[i].getElementsByTagName('div');
				for (j = 0; j < button.length ; j++)
				{
					if (button[j].className == 'headerelementmove')
					{
						button[j].onmousedown = selectImage;
					}
				}
			}
		}
	}
}

//initiation des evenements de base-----------------------------
window.onload = init;
</script>