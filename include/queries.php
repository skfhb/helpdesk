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
		if (login != '')
		{
			ajax('login.php', '&login='+login+'&pwd='+pwd, setConnected);
		}
	}
	//D�connecte un utilisateur
	function disconnect()
	{
		ajax('login.php', '&login=endsession&pwd=null', setConnected);
	}
	//Fonction pour AJAX, modifie l'affichage du bloc de connexion, du menu et de la page
	function setConnected(content)
	{
		document.getElementById('connectBox').innerHTML = content;
		$("#pageBody").load("first.php");
		$("#menu").load("menu.php");
	}
	//G�re la touche entr�e sur le formulaire de connexion
	function validateIfNeeded(keycode, fct)
	{
		if (keyCode == 13)
		{
			fct();
		}
	}
	//----------------T�CHES-------------------
	//G�re l'affichage d'un onglet
	function activateTab(tab, optn)
	{
		var activetab = document.getElementsByClassName('activetab');
		if (activetab[0] != tab)
		{
			activetab[0].className = 'tab';
			tab.className = 'activetab';
		}
		ajax('optnfilter.php', '&tab='+optn, displayTab);
	}
	//Affiche l'interface selon l'onglet choisi
	function displayTab(content)
	{
		document.getElementById('preferenceslisttask').innerHTML = '<br />'+content;
		$("#listtask").load("listtask.php");
	}
	//Effectue une recherche des t�ches
	function searchlisttask()
	{
		var codtsk = document.getElementById('searchbytasknb').value;
		var lbltsk = document.getElementById('searchbytasklbl').value;		
		ajax('qrymgr.php', '&CODTSK='+codtsk+'&LBLTSK='+lbltsk, refreshlisttask);
	}
	//Rafra�chit la liste des t�ches
	function refreshlisttask(parm)
	{
		alert(parm);
		$("#listtask").load("listtask.php");
	}
	//Change la liste des patchs selon appli s�lectionn�e : listtask
	function chgfilterpatc()
	{
		var selectedAppli = document.getElementById('appfilter').options[document.getElementById('appfilter').selectedIndex].value;
		ajax('filterpatc.php', '&codapp='+selectedAppli, refreshDisplayPatcFilter);
	}
	//Change la liste des patchs selon appli s�lectionn�e : createtask
	function chgfilterpatc2()
	{
		var selectedAppli = document.getElementById('appfilter').options[document.getElementById('appfilter').selectedIndex].value;
		ajax('filterpatc2.php', '&codapp='+selectedAppli, refreshDisplayPatcFilter);
	}
	//D�pendance AJAX de la fonction chgfilterpatc - Change dynamiquement la liste des patchs selon appli s�lectionn�e
	function refreshDisplayPatcFilter(content)
	{
		document.getElementById('filterpatc').innerHTML = content;
	}
	//Copie l'ID du patch en input hidden pour le passer en POST (le form n'int�gre pas le g�n�r� dynamiquement)
	function setPatchId()
	{
		document.getElementById('patcNb').value = document.getElementById('patcfilter').options[document.getElementById('patcfilter').selectedIndex].value;
	}
	//Applique les filtres
	function setFilter()
	{
		var App = document.getElementById('appfilter').options[document.getElementById('appfilter').selectedIndex].value;
		var Patc = document.getElementById('patcfilter').options[document.getElementById('patcfilter').selectedIndex].value;
		var Urg = document.getElementById('urgfilter').checked;
		var Typt = document.getElementById('typtfilter').options[document.getElementById('typtfilter').selectedIndex].value;
		var Prio = document.getElementById('priofilter').options[document.getElementById('priofilter').selectedIndex].value;
		var Stat = document.getElementById('stsfilter').options[document.getElementById('stsfilter').selectedIndex].value;
		var MAsk = document.getElementById('myaskfilter').checked;
		var MCon = document.getElementById('formefilter').checked;
		var MAffc = document.getElementById('affcmefilter').checked;
		ajax('qrymgr.php', '&App='+App+'&Patc='+Patc+'&Urg='+Urg+'&Typt='+Typt+'&Prio='+Prio+'&Stat='+Stat+'&MAsk='+MAsk+'&MCon='+MCon+'&MAffc='+MAffc, refreshlisttask);
	}
	//----------------USERS--------------------
	//Change le mot de passe d'un utilisateur
	function changePassword()
	{
		if (document.getElementById('newpwd').value == document.getElementById('cnfpwd').value)
		{
			ajax('chgpwd.php', '&oldpwd='+document.getElementById('oldpwd').value+'&newpwd='+document.getElementById('newpwd').value, displayResultPwd);
		}
		else
		{
			document.getElementById('resultChgPwd').innerHTML = 'Nouveaux mots de passe non-identiques';
		}
	}
	//Affiche le r�sultat d'une requ�te de changement de mot de passe
	function displayResultPwd(content)
	{
		document.getElementById('resultChgPwd').innerHTML = content;
	}
	//Envoie une requ�te de changement d'adresse
	function changeAddr(id)
	{
		ajax('usermgr.php', '&option=setadr&id='+id+'&attr='+document.getElementById(id+'adr').value, displayChangedAddr);
	}
	//G�re l'affichage apr�s changement d'adresse
	function displayChangedAddr(content)
	{
		if (content == 'adrok')
		{
			alert('Changement d\'adresse effectu� avec succ�s');
		}
		else
		{
			alert('Une erreur est survenue');
		}
	}
	//Envoie une requ�te de r�initialisation de mot de passe
	function reinitPwd(id)
	{
		ajax('usermgr.php', '&option=setpwd&id='+id, displayReinitPwd);
	}
	//G�re l'affichage apr�s r�initialisation de mot de passe
	function displayReinitPwd(content)
	{
		if (content == 'pwdok')
		{
			alert('R�initialisation de mot de passe effectu�e avec succ�s');
		}
		else
		{
			alert('Une erreur est survenue');
		}
	}
	//Envoie une requ�te de grant ou de revoke des privil�ges
	function grantOrRevoke(id, adm)
	{
		ajax('usermgr.php', '&option=setadm&id='+id+'&attr='+adm, displayGrantOrRevoke);
	}
	//G�re l'affichage apr�s r�initialisation de mot de passe
	function displayGrantOrRevoke(content)
	{
		if (content == 'admok')
		{
			alert('Modification des privil�ges effectu�e avec succ�s');
			$("#pageBody").load("usergst.php");
		}
		else
		{
			alert('Une erreur est survenue');
		}
	}
	//Envoie une requ�te d'ajout d'utilisateur
	function addUsr()
	{
		if ( (document.getElementById('newUserID').value == '') || (document.getElementById('newUserName').value == '') )
		{
			alert('Merci de remplir l\'ID et le nom souhait� pour ajouter un utilisateur');
		}
		else
		{
			ajax('usermgr.php', '&option=addusr&id='+document.getElementById('newUserID').value+'&name='+document.getElementById('newUserName').value, displayAddUser);
		}
	}
	//G�re l'affichage apr�s ajout d'un utilisateur
	function displayAddUser(content)
	{
		if (content == 'addok')
		{
			alert('Ajout effectu� avec succ�s');
			$("#pageBody").load("usergst.php");
		}
		else
		{
			alert('Une erreur est survenue');
		}
	}	
	//Envoie une requ�te de suppression d'utilisateur
	function delUsr(id)
	{
		if (confirm("�tes-vous s�r de vouloir supprimer cet utilisateur ? Cette action est irr�versible !"))
		{
			ajax('isUserDeletable.php', '&id='+id, manageUsrDeletable);
		}
	}
	//V�rifie que l'utilisateur peut �tre supprim�
	function manageUsrDeletable(content)
	{
		if (content.substring(0, 4) == 'true')
		{
			ajax('usermgr.php', '&option=delusr&id='+content.substring(4), displayDelUsr);
		}
		else
		{
			alert('Impossible de supprimer un utilisateur ayant d�j� effectu� des actions');
		}
	}
	//G�re l'affichage apr�s suppression d'un utilisateur
	function displayDelUsr(content)
	{
		if (content == 'delok')
		{
			alert('Utilisateur supprim� !');
			$("#pageBody").load("usergst.php");
		}
		else
		{
			alert('Une erreur est survenue');
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
	function changePubTypc(id)
	{
		ajax('gettypcs.php', '&option=changepubtypc&id='+id, reloadTypcs);
	}
	function reloadTypcs(content)
	{
		loadTypcs();
	}
	//----------------T�L�CHARGEMENTS-------------------
	//Lance l'ouverture de la pi�ce jointe dans un nouvel onglet (t�l�charge si pas lisible par navigateur)
	function download(url)
	{
		window.open(url); 
		return false;
	}
	
	//---------------CR�ATION T�CHE---------------------
	//V�rifie que la t�che parente saisie existe
	function verifTaskExists()
	{
		if (document.getElementById('taskpart').value != '')
		{
			ajax('calcExistsTask.php', '&tasknb='+document.getElementById('taskpart').value, ajxVerifTaskExists);
		}
		else
		{
			document.getElementById('isTaskPartOk').src = 'resources/statuts/all.png';
		}
	}
	//D�pendance AJAX de verifTaskExists pour rapport user
	function ajxVerifTaskExists(content)
	{
		if (content == 'OK')
		{
			document.getElementById('isTaskPartOk').src = 'resources/statuts/5.png';
		}
		else
		{
			document.getElementById('isTaskPartOk').src = 'resources/statuts/2.png';
		}
	}
	//G�re l'ajout d'une affectation � la t�che cr��e
	function addAffc()
	{
		var toAdd = document.getElementById('selectAffc').options[document.getElementById('selectAffc').selectedIndex].value;
		var name = document.getElementById('selectAffc').options[document.getElementById('selectAffc').selectedIndex].innerText;
		if (document.getElementById('finalAffc').innerText == '')
		{
			document.getElementById('finalAffc').innerHTML = '<input type="hidden" name="'+ trim(toAdd) +'" class="hiddenuseraffc" value="'+trim(toAdd)+'" /><div class="userAffc" onclick="focusUserAffc(this);">' + trim(name) + '</div>';
		}
		else
		{
			document.getElementById('finalAffc').innerHTML += '<input type="hidden" name="'+ trim(toAdd) +'" class="hiddenuseraffc" value="'+trim(toAdd)+'" /><div class="userAffc" onclick="focusUserAffc(this);">' + trim(name) + '</div>';
		}
		var dests = document.getElementById('selectAffc').options;
		for (var i = 0 ; i < dests.length ; i++)
		{
			if (dests[i].value == toAdd)
			{
				document.getElementById('selectAffc').removeChild(dests[i]);
			}
		}
		document.getElementById('finalAffc').style.backgroundColor = '#FFFFFF';
		updateAffcUsers();
	}
	//G�re la suppression d'une affectation � la t�che cr��e
	function removeAffc()
	{
		var toRemove = document.getElementsByClassName('selectedUserAffc');
		var opt=document.createElement("option");
		var text=document.createTextNode(toRemove[0].innerText);
		var inserted = false;
		opt.setAttribute("value", toRemove[0].innerText);
		opt.appendChild(text);
		
		var dests = document.getElementById('selectAffc').options;
		for (var i = 0 ; i < dests.length ; i++)
		{
			if ((toRemove[0].innerText < dests[i].innerText) && !(inserted))
			{
				document.getElementById('selectAffc').insertBefore(opt, dests[i]);
				inserted = true;
			}
		}
		if ((dests.length == 0) || !(inserted))
		{
			document.getElementById('selectAffc').appendChild(opt);
		}
		document.getElementById('finalAffc').removeChild(toRemove[0]);
		updateAffcUsers();
	}
	//Remplit le champ de type hidden pour traitement des affcs en DB
	function updateAffcUsers()
	{
		var dests = document.getElementsByClassName('hiddenuseraffc');
		var str = "";
		for (var i = 0 ; i < dests.length ; i++)
		{
			str = str + dests[i].value + ';';
		}
		str = str.substring(0, str.length-1);
		document.getElementById('usersAffcStringList').value = str;
	}
	//G�re l'affichage de s�lection d'un utilisateur dans la liste des affcs
	function focusUserAffc(u)
	{
		u.className = 'selectedUserAffc';
		var users = document.getElementsByClassName('selectedUserAffc');
		for (var i = 0 ; i < users.length ; i++)
		{
			if (users[i] != u)
			{
				users[i].className = 'userAffc';
			}
		}
	}
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
			if ((toRemove[0].innerText < dests[i].innerText) && !(inserted))
			{
				document.getElementById('selectDest').insertBefore(opt, dests[i]);
				inserted = true;
			}
		}
		if ((dests.length == 0) || !(inserted))
		{
			document.getElementById('selectAffc').appendChild(opt);
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
		else
		{
			return false;
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