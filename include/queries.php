<?php
//------------------------------------------------------------//
//	Projet 		: Task Manager								  //
//	Fichier 	: queries.php	 							  //
//  Description : Effectue les requêtes SQL					  //
//	Auteur 		: Hervé Bordeau								  //
// 	Date 		: 12/02/2013							      //
//------------------------------------------------------------//
//Dernière modif le 12/02/2013 par HB

header('Content-Type: text/html; charset=iso-8859-1');
?>
<script>

	//----------------GLOBAL-------------------
	function connect()
	{
		var login = document.getElementById('loginConnect').value;
		var pwd = document.getElementById('pwdConnect').value;
		ajax('login.php', '&login='+login+'&pwd='+pwd, setConnected);
	}
	
	function disconnect()
	{
		ajax('login.php', '&login=endsession&pwd=null', setConnected);
	}
	
	function setConnected(content)
	{
		document.getElementById('connectBox').innerHTML = content;
	}
	
	function validateIfNeeded(keycode, fct)
	{
		if (keyCode == 13)
		{
			fct();
		}
	}

	//----------------PATCHS-------------------


	//Refresh affichage des patchs d'après DB
	function loadPatchs()
	{
		var selectedAppli = document.getElementById('selectAppli').options[document.getElementById('selectAppli').selectedIndex].value;
		ajax('getpatchs.php', '&appli='+selectedAppli, displayLoadedPatchs);
	}
	//Dépendance loadPatchs()
	function displayLoadedPatchs(content)
	{
		document.getElementById("contentPatchs").innerHTML = content;
	}
	//Ajoute box de saisie de nom de patch
	function newPatch()
	{
		document.getElementById('newpatch').innerHTML = '<input type="text" id="newpatchname" /><input type="button" id="newpatchvalid" value="OK" onclick="saveNewPatch()" />';
	}
	//Envoie les données d'insert d'un nouveau patch
	function saveNewPatch()
	{
		var newPatchName = document.getElementById('newpatchname').value;
		var selectedAppli = document.getElementById('selectAppli').options[document.getElementById('selectAppli').selectedIndex].value;
		if (newPatchName != "")
		{
			ajax('getpatchs.php', '&option=insert&appli='+selectedAppli+'&name='+newPatchName, loadPatchs);
		}
	}
	//Envoie les données de delete d'un patch
	function delPatch(i)
	{
		if (confirm("Êtes-vous sûr de vouloir supprimer le patch ?"))
		{
			var selectedAppli = document.getElementById('selectAppli').options[document.getElementById('selectAppli').selectedIndex].value;
			ajax('getpatchs.php', '&option=delete&appli='+selectedAppli+'&id='+i, loadPatchs);
		}
	}
	
	
	//----------------APPLIS-------------------


	//Refresh affichage des applis d'après DB
	function loadApplis()
	{
		ajax('getapplis.php', '&option=get', displayLoadedApplis);
	}
	//Dépendance loadApplis()
	function displayLoadedApplis(content)
	{
		document.getElementById("contentApplis").innerHTML = content;
	}
	//Ajoute box de saisie de nom d'appli
	function newAppli()
	{
		document.getElementById('newappli').innerHTML = '<input type="text" id="newappliname" /><input type="button" id="newapplivalid" value="OK" onclick="saveNewAppli()" />';
	}
	//Envoie les données d'insert d'une nouvelle appli
	function saveNewAppli()
	{
		var newAppliName = document.getElementById('newappliname').value;
		if (newAppliName != "")
		{
			ajax('getapplis.php', '&option=insert&name='+newAppliName, loadApplis);
		}
	}
	//Envoie les données de delete d'une appli
	function delAppli(i)
	{
		if (confirm("Êtes-vous sûr de vouloir supprimer cette application ?"))
		{
			ajax('getapplis.php', '&option=delete&id='+i, loadApplis);
		}
	}
	
	
	//----------------STATUTS-------------------
	
	
	//Refresh affichage des statuts d'après DB
	function loadStatuts()
	{
		ajax('getstatuts.php', '&option=get', displayLoadedStatuts);
	}
	//Dépendance loadStatuts()
	function displayLoadedStatuts(content)
	{
		document.getElementById("contentStatuts").innerHTML = content;
	}
	//Ajoute box de saisie de nom de statut
	function newStatut()
	{
		document.getElementById('newstatut').innerHTML = '<iframe style="height:100px;border:0px;" name="fileUpload" seamless></iframe><form id="newStatutForm" enctype="multipart/form-data" action="fileUpload.php" method="post" target="fileUpload"><input type="text" name="newstatutname" id="newstatutname" /><input type="file" name="newstatutimg" id="newstatutimg" /><input type="submit" id="newstatutvalid" value="OK" onclick="saveNewStatut()" /></form><font style="color:#FF0000;">Veillez à ce que le chemin de l\'image ne contienne pas d\'espace</font>';
	}
	//Envoie les données d'insert d'un nouveau patch
	function saveNewStatut()
	{
		setTimeout(function () { document.getElementById('newstatut').innerHTML = "";loadStatuts(); }, 300);
	}
	//Envoie les données de delete d'un patch
	function delStatut(i)
	{
		if (confirm("Êtes-vous sûr de vouloir supprimer le statut ?"))
		{
			ajax('getstatuts.php', '&option=delete&id='+i, loadStatuts);
		}
	}
	
	//----------------PRIOS-------------------


	//Refresh affichage des prios d'après DB
	function loadPrios()
	{
		ajax('getprios.php', '&option=get', displayLoadedPrios);
	}
	//Dépendance loadPrios()
	function displayLoadedPrios(content)
	{
		document.getElementById("contentPrios").innerHTML = content;
	}
	//Ajoute box de saisie de nom de prio
	function newPrio()
	{
		document.getElementById('newprio').innerHTML = '<input type="text" id="newprioname" /><input type="button" id="newpriovalid" value="OK" onclick="saveNewPrio()" />';
	}
	//Envoie les données d'insert d'une nouvelle prio
	function saveNewPrio()
	{
		var newPrioName = document.getElementById('newprioname').value;
		if (newPrioName != "")
		{
			ajax('getprios.php', '&option=insert&name='+newPrioName, loadPrios);
		}
	}
	//Envoie les données de delete d'une prio
	function delPrio(i)
	{
		if (confirm("Êtes-vous sûr de vouloir supprimer ce degré de priorité ?"))
		{
			ajax('getprios.php', '&option=delete&id='+i, loadPrios);
		}
	}
	
	//----------------TYPES DE TÂCHES-------------------
	
	//Refresh affichage des types de tâche d'après DB
	function loadTypts()
	{
		ajax('gettypts.php', '&option=get', displayLoadedTypts);
	}
	//Dépendance loadTypts()
	function displayLoadedTypts(content)
	{
		document.getElementById("contentTypts").innerHTML = content;
	}
	//Ajoute box de saisie de nom de type de tâche
	function newTypt()
	{
		document.getElementById('newtypt').innerHTML = '<input type="text" id="newtyptname" /><input type="button" id="newtyptvalid" value="OK" onclick="saveNewTypt()" />';
	}
	//Envoie les données d'insert d'un nouveau type de tâche
	function saveNewTypt()
	{
		var newTyptName = document.getElementById('newtyptname').value;
		if (newTyptName != "")
		{
			ajax('gettypts.php', '&option=insert&name='+newTyptName, loadTypts);
		}
	}
	//Envoie les données de delete d'un type de tâche
	function delTypt(i)
	{
		if (confirm("Êtes-vous sûr de vouloir supprimer ce type de tâche ?"))
		{
			ajax('gettypts.php', '&option=delete&id='+i, loadTypts);
		}
	}
	
	//----------------TYPES DE COMMENTAIRE-------------------
	
	//Refresh affichage des types de commentaire d'après DB
	function loadTypcs()
	{
		ajax('gettypcs.php', '&option=get', displayLoadedTypcs);
	}
	//Dépendance loadTypcs()
	function displayLoadedTypcs(content)
	{
		document.getElementById("contentTypcs").innerHTML = content;
	}
	//Ajoute box de saisie de nom de type de commentaire
	function newTypc()
	{
		document.getElementById('newtypc').innerHTML = '<input type="text" id="newtypcname" /><input type="checkbox" id="newtypcpublic" onclick="changePublic()" /><input type="button" id="newtypcvalid" value="OK" onclick="saveNewTypc()" style="float:right;" /><font id="ispublic" style="float:right;width:60px;padding-top:2px;">Public</font>';
	}
	function changePublic()
	{
		if (document.getElementById('newtypcpublic').checked)
		{
			document.getElementById('ispublic').innerHTML = 'Privé';
		}
		else
		{
			document.getElementById('ispublic').innerHTML = 'Public';
		}
	}
	//Envoie les données d'insert d'un nouveau type de commentaire
	function saveNewTypc()
	{
		var newTypcName = document.getElementById('newtypcname').value;
		var isPublic = 1;
		if (document.getElementById('newtypcpublic').checked)
		{
			isPublic = 0;
		}
		if (newTypcName != "")
		{
			ajax('gettypcs.php', '&option=insert&name='+newTypcName+'&public='+String(isPublic), loadTypcs);
		}
	}
	//Envoie les données de delete d'un type de commentaire
	function delTypc(i)
	{
		if (confirm("Êtes-vous sûr de vouloir supprimer ce type de commentaire ?"))
		{
			ajax('gettypcs.php', '&option=delete&id='+i, loadTypcs);
		}
	}
	
	//----------------TÉLÉCHARGEMENTS-------------------
	function download(url)
	{
		window.open(url); 
		return false;
	}
	
	//---------------CRÉATION TÂCHE---------------------
	function addDest()
	{
		var toAdd = document.getElementById('selectDest').options[document.getElementById('selectDest').selectedIndex].value;
		var name = document.getElementById('selectDest').options[document.getElementById('selectDest').selectedIndex].innerText;
		if (document.getElementById('finalDest').innerText == '')
		{
			document.getElementById('finalDest').innerHTML = '<input type="hidden" name="'+ trim(toAdd) +'" class="hiddenuser" value="'+trim(toAdd)+'" /><div class="userDest" onclick="focusUserDest(this);">' + trim(name) + '</div>';
		}
		else
		{
			document.getElementById('finalDest').innerHTML += '<input type="hidden" name="'+ trim(toAdd) +'" class="hiddenuser" value="'+trim(toAdd)+'" /><div class="userDest" onclick="focusUserDest(this);">' + trim(name) + '</div>';
		}
		var dests = document.getElementById('selectDest').options;
		for (var i = 0 ; i < dests.length ; i++)
		{
			if (dests[i].value == toAdd)
			{
				document.getElementById('selectDest').removeChild(dests[i]);
			}
		}
		document.getElementById('finalDest').style.backgroundColor = '#FFFFFF';
		updateDestUsers();
	}
	
	function removeDest()
	{
		var toRemove = document.getElementsByClassName('selectedUserDest');
		var opt=document.createElement("option");
		var text=document.createTextNode(toRemove[0].innerText);
		var inserted = false;
		opt.setAttribute("value", toRemove[0].innerText);
		opt.appendChild(text);
		
		var dests = document.getElementById('selectDest').options;
		for (var i = 0 ; i < dests.length ; i++)
		{
			if ((toRemove[0].innerText < dests[i].value) && !(inserted))
			{
				document.getElementById('selectDest').insertBefore(opt, dests[i]);
				inserted = true;
			}
		}
		document.getElementById('finalDest').removeChild(toRemove[0]);
		updateDestUsers();
	}
	
	function updateDestUsers()
	{
		var dests = document.getElementsByClassName('hiddenuser');
		var str = "";
		for (var i = 0 ; i < dests.length ; i++)
		{
			str = str + dests[i].value + ';';
		}
		str = str.substring(0, str.length-1);
		document.getElementById('usersDestStringList').value = str;
	}
	
	function focusUserDest(u)
	{
		u.className = 'selectedUserDest';
		var users = document.getElementsByClassName('selectedUserDest');
		for (var i = 0 ; i < users.length ; i++)
		{
			if (users[i] != u)
			{
				users[i].className = 'userDest';
			}
		}
	}
	
	function validNewTask()
	{
		var isTaskOk = true;
		if (trim(document.getElementById('taskname').value) == '')
		{
			document.getElementById('taskname').style.backgroundColor = '#FF5353';
			isTaskOk = false;
		}
		var usersDest = document.getElementsByClassName('userDest');
		var userSelected = document.getElementsByClassName('selectedUserDest');
		if (!usersDest[0] && !userSelected[0])
		{
			document.getElementById('finalDest').style.backgroundColor = '#FF5353';
			isTaskOk = false;
		}
		
		if (isTaskOk)
		{
			setTimeout(function() { 
				ajax('getMaxTaskId.php', 'a', displayCreatedTask);
			}, 3000);
		}
	}
	
	function displayCreatedTask(id)
	{
		loadPage('dettask.php?id='+id)
	}
</script>