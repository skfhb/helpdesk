<?php
//-----------------------------------------------------------//
//	Projet 		: Task Manager								 //
//	Fichier 	: ajax.php									 //
//  Description : G�re les transactions AJAX			     //
//	Auteur 		: Herv� Bordeau								 //
// 	Date 		: 08/02/2013							     //
//-----------------------------------------------------------//
//Derni�re modif le 08/02/2013 par HB
		
?>
<script>
//g�re les objets AJAX
function getXhr()
{
	if(window.XMLHttpRequest) // Firefox et autres
		xhr = new XMLHttpRequest(); 
	else if(window.ActiveXObject)
	{ // Internet Explorer 
		try 
		{
			xhr = new ActiveXObject("Msxml2.XMLHTTP");
		} 
		catch (e)
		{
			xhr = new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	else 
	{ // XMLHttpRequest non support� par le navigateur 
		alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest..."); 
		xhr = false; 
	} 
}
//Effectue une requ�te en AJAX
function ajax(url, datas, onComplete)
{
	getXhr();
	xhr.onreadystatechange = function()
		{
			if(xhr.readyState == <?php echo _AJAX_RESPONSE_SRV; ?> && xhr.status == <?php echo _AJAX_RESPONSE_HTTP; ?>)
			{		
				onComplete(xhr.responseText);
			}
		}
	xhr.open("<?php echo _AJAX_METHOD; ?>", url, <?php echo _AJAX_MODE; ?>);
	xhr.setRequestHeader("<?php echo _AJAX_HEADER_TYPE; ?>", "<?php echo _AJAX_HEADER_CONTENT; ?>");
	xhr.send(datas);
}
</script>
