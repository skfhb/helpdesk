<?php
//------------------------------------------------------------//
//	Projet 		: Task Manager								  //
//	Fichier 	: queries.php	 							  //
//  Description : Effectue les requêtes SQL					  //
//	Auteur 		: Hervé Bordeau								  //
// 	Date 		: 12/02/2013							      //
//------------------------------------------------------------//
//Dernière modif le 12/02/2013 par HB

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
	//Déconnecte un utilisateur
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
	//Gère la touche entrée sur le formulaire de connexion
	function validateIfNeeded(keycode, fct)
	{
		if (keyCode == 13)
		{
			fct();
		}
	}
	//----------------TÂCHES-------------------
	//Gère l'affichage d'un onglet
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
	}
	//Effectue une recherche des tâches
	function searchlisttask()
	{
		var codtask = document.getElementById('searchbytasknb').value;
		var lbltask = document.getElementById('searchbytasklbl').value;
		var query = '';
		
		if ((codtask != '') && (lbltask != ''))
		{
			query = 'SELECT * FROM DEVTAMG.TAMGTASK WHERE CODTASK = '+codtask+' OR LBLTASK LIKE \'%'+lbltask+'%\'';
		}
		else
		{
			if (codtask != '')
			{
				query = 'SELECT * FROM DEVTAMG.TAMGTASK WHERE CODTASK = '+codtask;
			}
			else
			{
				query = 'SELECT * FROM DEVTAMG.TAMGTASK WHERE LBLTASK LIKE \'%'+lbltask+'%\'';
			}
		}
		query += ' AND ACTTASK = 1'
		ajax('qrymgr.php', '&qry='+query, refreshlisttask);
	}
	//Rafraîchit la liste des tâches
	function refreshlisttask(parm)
	{
		$("#listtask").load("listtask.php");
	}
	//Change la liste des patchs selon appli sélectionnée
	function chgfilterpatc()
	{
		var selectedAppli = document.getElementById('appfilter').options[document.getElementById('appfilter').selectedIndex].value;
		ajax('filterpatc.php', '&codapp='+selectedAppli, refreshDisplayPatcFilter);
	}
	function refreshDisplayPatcFilter(content)
	{
		document.getElementById('filterpatc').innerHTML = content;
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
	//Affiche le résultat d'une requête de changement de mot de passe
	function displayResultPwd(content)
	{
		document.getElementById('resultChgPwd').innerHTML = content;
	}
	//Envoie une requête de changement d'adresse
	function changeAddr(id)
	{
		ajax('usermgr.php', '&option=setadr&id='+id+'&attr='+document.getElementById(id+'adr').value, displayChangedAddr);
	}
	//Gère l'affichage après changement d'adresse
	function displayChangedAddr(content)
	{
		if (content == 'adrok')
		{
			alert('Changement d\'adresse effectué avec succès');
		}
		else
		{
			alert('Une erreur est survenue');
		}
	}
	//Envoie une requête de réinitialisation de mot de passe
	function reinitPwd(id)
	{
		ajax('usermgr.php', '&option=setpwd&id='+id, displayReinitPwd);
	}
	//Gère l'affichage après réinitialisation de mot de passe
	function displayReinitPwd(content)
	{
		if (content == 'pwdok')
		{
			alert('Réinitialisation de mot de passe effectuée avec succès');
		}
		else
		{
			alert('Une erreur est survenue');
		}
	}
	//Envoie une requête de grant ou de revoke des privilèges
	function grantOrRevoke(id, adm)
	{
		ajax('usermgr.php', '&option=setadm&id='+id+'&attr='+adm, displayGrantOrRevoke);
	}
	//Gère l'affichage après réinitialisation de mot de passe
	function displayGrantOrRevoke(content)
	{
		if (content == 'admok')
		{
			alert('Modification des privilèges effectuée avec succès');
			$("#pageBody").load("usergst.php");
		}
		else
		{
			alert('Une erreur est survenue');
		}
	}
	//Envoie une requête d'ajout d'utilisateur
	function addUsr()
	{
		if ( (document.getElementById('newUserID').value == '') || (document.getElementById('newUserName').value == '') )
		{
			alert('Merci de remplir l\'ID et le nom souhaité pour ajouter un utilisateur');
		}
		else
		{
			ajax('usermgr.php', '&option=addusr&id='+document.getElementById('newUserID').value+'&name='+document.getElementById('newUserName').value, displayAddUser);
		}
	}
	//Gère l'affichage après ajout d'un utilisateur
	function displayAddUser(content)
	{
		if (content == 'addok')
		{
			alert('Ajout effectué avec succès');
			$("#pageBody").load("usergst.php");
		}
		else
		{
			alert('Une erreur est survenue');
		}
	}	
	//Envoie une requête de suppression d'utilisateur
	function delUsr(id)
	{
		if (confirm("Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible !"))
		{
			ajax('isUserDeletable.php', '&id='+id, manageUsrDeletable);
		}
	}
	//Vérifie que l'utilisateur peut être supprimé
	function manageUsrDeletable(content)
	{
		if (content.substring(0, 4) == 'true')
		{
			ajax('usermgr.php', '&option=delusr&id='+content.substring(4), displayDelUsr);
		}
		else
		{
			alert('Impossible de supprimer un utilisateur ayant déjà effectué des actions');
		}
	}
	//Gère l'affichage après suppression d'un utilisateur
	function displayDelUsr(content)
	{
		if (content == 'delok')
		{
			alert('Utilisateur supprimé !');
			$("#pageBody").load("usergst.php");
		}
		else
		{
			alert('Une erreur est survenue');
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
	//----------------TÉLÉCHARGEMENTS-------------------
	//Lance l'ouverture de la pièce jointe dans un nouvel onglet (télécharge si pas lisible par navigateur)
	function download(url)
	{
		window.open(url); 
		return false;
	}
	
	//---------------CRÉATION TÂCHE---------------------
	//Gère l'ajout d'un destinataire à la tâche créée
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
	//Gère la suppression d'un destinataire à la tâche créée
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
	//Gère l'affichage de sélection d'un utilisateur dans la liste des destinataires
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
	//Enregistre la tâche en DB
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
	//Après création, redirige vers la dernière tâche créée
	function displayCreatedTask(id)
	{
		loadPage('dettask.php?id='+id)
	}
	
	//---------------DETAIL TÂCHE---------------------
	//Gère la suppression d'une tâche
	function deleteTask(i)
	{
		if (confirm("Vous êtes sur le point de désactiver une tâche définitivement. Êtes-vous sûr de vouloir continuer ?"))
		{
			ajax('deletetask.php', 'task='+i, redirectAfterDelete);
		}
	}
	
	//Fonction pour AJAX redirigeant sur listtask après del d'une tâche
	function redirectAfterDelete(parm)
	{
		//Paramètre reçu toujours vide
		loadPage('listtask.php')
	}
</script>