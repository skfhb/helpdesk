<?php
//--------------------------------------------------------------------------//
//	Projet 		: Task Manager								  				//
//	Fichier 	: clickout.php  							  				//
//  Description : Page JS permettant de simuler les events "clickout"		//
//	Auteur 		: Herv� Bordeau								  				//
// 	Date 		: 07/05/2013							      				//
//--------------------------------------------------------------------------//
//Derni�re modif le 07/05/2013 par HB
?>
<script>
document.getElementById('click').onclick = function(event) {
    event.stopPropagation();
}

document.onclick = function() {
    loseFocusOnEverything();
}

function loseFocusOnEverything()
{
	alert('lose focus');
}
</script>