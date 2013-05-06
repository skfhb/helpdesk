<?php
//------------------------------------------------------------//
//	Projet 		: Task Manager								  //
//	Fichier 	: tools.php	 								  //
//  Description : Boîte à outils							  //
//	Auteur 		: Hervé Bordeau								  //
// 	Date 		: 04/03/2013							      //
//------------------------------------------------------------//
//Dernière modif le 04/03/2013 par HB

//Prend en paramètre une date au format AAAA-MM-JJ et la renvoie au format JJ/MM/AAAA
function formatDate($date)
{
	$toReturn = substr($date, 8, 2);
	$toReturn .= '/';
	$toReturn .= substr($date, 5, 2);
	$toReturn .= '/';
	$toReturn .= substr($date, 0, 4);
	
	return $toReturn;
}

//Prend en paramètre un timestamp au format AAAA-MM-JJ-HH.MM.SS.CCCCCC et renvoie l'heure au format HH:MM:SS
function formatTime($datetime)
{
	$toReturn = substr($datetime, 11, 2);
	$toReturn .= ':';
	$toReturn .= substr($datetime, 14, 2);
	$toReturn .= ':';
	$toReturn .= substr($datetime, 17, 2);
	
	return $toReturn;
}

//Prend en paramètre une date et une heure au format AAAA-MM-JJ-HH.MM.SS.CCCCCC et la renvoie au format JJ/MM/AAAA HH:MM:SS
function formatDateTime($datetime)
{
	$toReturn = formatDate($datetime);
	$toReturn .= ' ';
	$toReturn .= formatTime($datetime);
	
	return $toReturn;
}

//Compare deux commentaires d'après leurs infos en base, selon la tâche qu'ils concernent, l'utilisateur, le type de commentaire et le timestamp à la minute
function commentEquals($a, $b)
{
	$isEquals = true;
	if (odbc_result($a, 'CODTASK') != odbc_result($b, 'CODTASK'))
	{
		$isEquals = false;
	}
	elseif (odbc_result($a, 'CODUSER') != odbc_result($b, 'CODUSER'))
	{
		$iEquals = false;
	}
	elseif (odbc_result($a, 'CODTYPC') != odbc_result($b, 'CODTYPC'))
	{
		$isEquals = false;
	}
	elseif ((strtotime(formatDateTime(odbc_result($a, 'TSTPCOM'))) - strtotime(formatDateTime(odbc_result($b, 'TSTPCOM')))) > 60)
	{
		$isEquals = false;
	}
	return $isEquals;
}
?>
<script>

	function insertSort(divArray)
	{
		for (var i = 1 ; i < divArray.length ; i++)
		{
			var x = divArray[i];
			var j = i;
			while (j > 0 && divArray[j-1].offsetTop > x.offsetTop)
			{
				divArray[j] = divArray[j-1];
				j = j-1;
			}
			divArray[j] = x;
		}
		return divArray;
	}

	function displayToolTip()
	{
		$( document ).tooltip();
	}
	
	function trim(myString)
	{
		return myString.replace(/^\s+/g,'').replace(/\s+$/g,'')
	} 
	
	function setDatePicker() {
		$(function() {
		
				$.datepicker.regional['fr'] = {clearText: 'Effacer', clearStatus: '',
					closeText: 'Fermer', closeStatus: 'Fermer sans modifier',
					prevText: '<Préc', prevStatus: 'Voir le mois précédent',
					nextText: 'Suiv>', nextStatus: 'Voir le mois suivant',
					currentText: 'Courant', currentStatus: 'Voir le mois courant',
					monthNames: ['Janvier','Février','Mars','Avril','Mai','Juin',
					'Juillet','Août','Septembre','Octobre','Novembre','Décembre'],
					monthNamesShort: ['Jan','Fév','Mar','Avr','Mai','Jun',
					'Jul','Aoû','Sep','Oct','Nov','Déc'],
					monthStatus: 'Voir un autre mois', yearStatus: 'Voir un autre année',
					weekHeader: 'Sm', weekStatus: '',
					dayNames: ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
					dayNamesShort: ['Dim','Lun','Mar','Mer','Jeu','Ven','Sam'],
					dayNamesMin: ['Di','Lu','Ma','Me','Je','Ve','Sa'],
					dayStatus: 'Utiliser DD comme premier jour de la semaine', dateStatus: 'Choisir le DD, MM d',
					dateFormat: 'dd/mm/yy', firstDay: 0, 
					initStatus: 'Choisir la date', isRTL: false};
			$.datepicker.setDefaults($.datepicker.regional['fr']);

			$( "#datepicker" ).datepicker({
			  showOn: "both",
			  buttonImage: "<?php echo _IMG_STYLE; ?>calendar.gif",
			  buttonImageOnly: true
			});
			$( "#datepicker" ).datepicker( $.datepicker.regional[ "fr" ] );
		  });
    };
</script>