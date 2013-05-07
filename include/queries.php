<?php
//------------------------------------------------------------//
//	Projet 		: Task Manager								  //
//	Fichier 	: queries.php	 							  //
//  Description : Effectue les requ�tes SQL					  //
//	Auteur 		: Herv� Bordeau								  //
// 	Date 		: 12/02/2013							      //
//------------------------------------------------------------//
//Derni�re modif le 12/02/2013 par HB

?>
<script>

	//----------------GLOBAL-------------------
	//Connecte un utilisateur
	function connect()
	{
		var login = document.getElementById('loginConnect').value;
		var pwd = document.getElementById('pwdConnect').value;
		ajax('login.php', '&login='+login+'&pwd='+pwd, setConnected);
	}
	//D�connecte un utilisateur
	function disconnect()
	{
		ajax('login.php', '&login=endsession&pwd=null', setConnected);
	}
	//Fonction pour AJAX, modifie l'affichage du bloc de connexion
	function setConnected(content)
	{
		document.getElementById('connectBox').innerHTML = content;
	}
	//G�re la touche entr�e sur le formulaire de connexion
	function validateIfNeeded(keycode, fct)
	{
		if (keyCode == 13)
		{
			fct();
		}
	}

	//----------------PATCHS-------------------
	//Refresh affichage des patchs d'apr�s DB
	function loadPatchs()
	{
		var selectedAppli = document.getElementById('selectAppli').options[document.getElementById('selectAppli').selectedIndex].value;
		ajax('getpatchs.php', '&appli='+selectedAppli, displayLoadedPatchs);
	}
	//D�pendance loadPatchs()
	function displayLoadedPatchs(content)
	{
		document.getElementById("contentPatchs").innerHTML = content;
	}
	//Ajoute box de saisie de nom de patch
	function newPatch()
	{
		document.getElementById('newpatch').innerHTML = '<input type="text" id="newpatchname" /><input type="button" id="newpatchvalid" value="OK" onclick="saveNewPatch()" />';
	}
	//Envoie les donn�es d'insert d'un nouveau patch
	function saveNewPatch()
	{
		var newPatchName = document.getElementById('newpatchname').value;
		var selectedAppli = document.getElementById('selectAppli').options[document.getElementById('selectAppli').selectedIndex].value;
		if (newPatchName != "")
		{
			ajax('getpatchs.php', '&option=insert&appli='+selectedAppli+'&name='+newPatchName, loadPatchs);
		}
	}
	//Envoie les donn�es de delete d'un patch
	function delPatch(i)
	{
		if (confirm("�tes-vous s�r de vouloir supprimer le patch ?"))
		{
			var selectedAppli = document.getElementById('selectAppli').options[document.getElementById('selectAppli').selectedIndex].value;
			ajax('getpatchs.php', '&option=delete&appli='+selectedAppli+'&id='+i, manageDelPatchs);
		}
	}
	//Traite le retour de la suppression
	function manageDelPatchs(parm)
	{	
		if (parm == '')
		{
			loadPatchs();
		}
		else
		{
			alert(parm);
		}
	}
	
	//----------------APPLIS-------------------
	//Refresh affichage des applis d'apr�s DB
	function loadApplis()
	{
		ajax('getapplis.php', '&option=get', displayLoadedApplis);
	}
	//D�pendance loadApplis()
	function displayLoadedApplis(content)
	{
		document.getElementById("contentApplis").innerHTML = content;
	}
	//Ajoute box de saisie de nom d'appli
	function newAppli()
	{
		document.getElementById('newappli').innerHTML = '<input type="text" id="newappliname" /><input type="button" id="newapplivalid" value="OK" onclick="saveNewAppli()" />';
	}
	//Envoie les donn�es d'insert d'une nouvelle appli
	function saveNewAppli()
	{
		var newAppliName = document.getElementById('newappliname').value;
		if (newAppliName != "")
		{
			ajax('getapplis.php', '&option=insert&name='+newAppliName, loadApplis);
		}
	}
	//Envoie les donn�es de delete d'une appli
	function delAppli(i)
	{
		if (confirm("�tes-vous s�r de vouloir supprimer cette application ?"))
		{
			ajax('getapplis.php', '&option=delete&id='+i, manageDelApplis);
		}
	}
	//Traite le retour de la suppression
	function manageDelApplis(parm)
	{	
		if (parm == '')
		{
			loadApplis();
		}
		else
		{
			alert(parm);
		}
	}
	
	//----------------STATUTS-------------------	
	//Refresh affichage des statuts d'apr�s DB
	function loadStatuts()
	{
		ajax('getstatuts.php', '&option=get', displayLoadedStatuts);
	}
	//D�pendance loadStatuts()
	function displayLoadedStatuts(content)
	{
		document.getElementById("contentStatuts").innerHTML = content;
	}
	//Ajoute box de saisie de nom de statut
	function newStatut()
	{
		document.getElementById('newstatut').innerHTML = '<iframe style="height:100px;border:0px;" name="fileUpload" seamless></iframe><form id="newStatutForm" enctype="multipart/form-data" action="fileUpload.php" method="post" target="fileUpload"><input type="text" name="newstatutname" id="newstatutname" /><input type="file" name="newstatutimg" id="newstatutimg" /><input type="submit" id="newstatutvalid" value="OK" onclick="saveNewStatut()" /></form><font style="color:#FF0000;">Veillez � ce que le chemin de l\'image ne contienne pas d\'espace</font>';
	}
	//Envoie les donn�es d'insert d'un nouveau patch
	function saveNewStatut()
	{
		setTimeout(function () { document.getElementById('newstatut').innerHTML = "";loadStatuts(); }, 300);
	}
	//Envoie les donn�es de delete d'un patch
	function delStatut(i)
	{
		if (confirm("�tes-vous s�r de vouloir supprimer le statut ?"))
		{
			ajax('getstatuts.php', '&option=delete&id='+i, manageDelStatuts);
		}
	}
	//Traite le retour de la suppression
	function manageDelStatuts(parm)
	{	
		if (parm == '')
		{
			loadStatuts();
		}
		else
		{
			alert(parm);
		}
	}
	//----------------PRIOS-------------------
	//Refresh affichage des prios d'apr�s DB
	function loadPrios()
	{
		ajax('getprios.php', '&option=get', displayLoadedPrios);
	}
	//D�pendance loadPrios()
	function displayLoadedPrios(content)
	{
		document.getElementById("contentPrios").innerHTML = content;
	}
	//Ajoute box de saisie de nom de prio
	function newPrio()
	{
		document.getElementById('newprio').innerHTML = '<input type="text" id="newprioname" /><input type="button" id="newpriovalid" value="OK" onclick="saveNewPrio()" />';
	}
	//Envoie les donn�es d'insert d'une nouvelle prio
	function saveNewPrio()
	{
		var newPrioName = document.getElementById('newprioname').value;
		if (newPrioName != "")
		{
			ajax('getprios.php', '&option=insert&name='+newPrioName, loadPrios);
		}
	}
	//Envoie les donn�es de delete d'une prio
	function delPrio(i)
	{
		if (confirm("�tes-vous s�r de vouloir supprimer ce degr� de priorit� ?"))
		{
			ajax('getprios.php', '&option=delete&id='+i, manageDelPrio);
		}
	}
	//Traite le retour de la suppression
	function manageDelPrio(parm)
	{	
		if (parm == '')
		{
			loadPrios();
		}
		else
		{
			alert(parm);
		}
	}
	//----------------TYPES DE T�CHES-------------------
	//Refresh affichage des types de t�che d'apr�s DB
	function loadTypts()
	{
		ajax('gettypts.php', '&option=get', displayLoadedTypts);
	}
	//D�pendance loadTypts()
	function displayLoadedTypts(content)
	{
		document.getElementById("contentTypts").innerHTML = content;
	}
	//Ajoute box de saisie de nom de type de t�che
	function newTypt()
	{
		document.getElementById('newtypt').innerHTML = '<input type="text" id="newtyptname" /><input type="button" id="newtyptvalid" value="OK" onclick="saveNewTypt()" />';
	}
	//Envoie les donn�es d'insert d'un nouveau type de t�che
	function saveNewTypt()
	{
		var newTyptName = document.getElementById('newtyptname').value;
		if (newTyptName != "")
		{
			ajax('gettypts.php', '&option=insert&name='+newTyptName, loadTypts);
		}
	}
	//Envoie les donn�es de delete d'un type de t�che
	function delTypt(i)
	{
		if (confirm("�tes-vous s�r de vouloir supprimer ce type de t�che ?"))
		{
			ajax('gettypts.php', '&option=delete&id='+i, manageDelTypt);
		}
	}
	//Traite le retour de la suppression
	function manageDelTypt(parm)
	{	
		if (parm == '')
		{
			loadTypts();
		}
		else
		{
			alert(parm);
		}
	}
	
	//----------------TYPES DE COMMENTAIRE-------------------
	//Refresh affichage des types de commentaire d'apr�s DB
	function loadTypcs()
	{
		ajax('gettypcs.php', '&option=get', displayLoadedTypcs);
	}
	//D�pendance loadTypcs()
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
			document.getElementById('ispublic').innerHTML = 'Priv�';
		}
		else
		{
			document.getElementById('ispublic').innerHTML = 'Public';
		}
	}
	//Envoie les donn�es d'insert d'un nouveau type de commentaire
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
	//Envoie les donn�es de delete d'un type de commentaire
	function delTypc(i)
	{
		if (confirm("�tes-vous s�r de vouloir supprimer ce type de commentaire ?"))
		{
			ajax('gettypcs.php', '&option=delete&id='+i, manageDelTypc);
		}
	}
	//Traite le retour de la suppression
	function manageDelTypc(parm)
	{	
		if (parm == '')
		{
			loadTypcs();
		}
		else
		{
			alert(parm);
		}
	}
	//----------------T�L�CHARGEMENTS-------------------
	//Lance l'ouverture de la pi�ce jointe dans un nouvel onglet (t�l�charge si pas lisible par navigateur)
	function download(url)
	{
		window.open(url); 
		return false;
	}
	
	//---------------CR�ATION T�CHE---------------------
	//G�re l'ajout d'un destinataire � la t�che cr��e
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
	//G�re la suppression d'un destinataire � la t�che cr��e
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
	//Remplit le champ de type hidden pour traitement des destinataires en DB
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
	//G�re l'affichage de s�lection d'un utilisateur dans la liste des destinataires
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
	//Enregistre la t�che en DB
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
	//Apr�s cr�ation, redirige vers la derni�re t�che cr��e
	function displayCreatedTask(id)
	{
		loadPage('dettask.php?id='+id)
	}
	
	//---------------DETAIL T�CHE---------------------
	//G�re la suppression d'une t�che
	function deleteTask(i)
	{
		if (confirm("Vous �tes sur le point de d�sactiver une t�che d�finitivement. �tes-vous s�r de vouloir continuer ?"))
		{
			ajax('deletetask.php', 'task='+i, redirectAfterDelete);
		}
	}
	
	//Fonction pour AJAX redirigeant sur listtask apr�s del d'une t�che
	function redirectAfterDelete(parm)
	{
		//Param�tre re�u toujours vide
		loadPage('listtask.php')
	}
</script>