<?php
//---------------------------------------------------------------//
//	Projet 		: Task Manager									 //
//	Fichier 	: qrymgr.php								 	 //
//  Description : Gère les queries pour listtask			     //
//	Auteur 		: Hervé Bordeau									 //
// 	Date 		: 21/05/2013							     	 //
//---------------------------------------------------------------//
//Dernière modif le 21/05/2013 par HB

session_start();

if (isset($_POST['App']) && ($_POST['App'] != 'all'))
{
	$_SESSION['F_App'] = $_POST['App'];
}
else
{
	unset($_SESSION['F_App']);
}
if (isset($_POST['Patc']) && ($_POST['Patc'] != 'all'))
{
	$_SESSION['F_Patc'] = $_POST['Patc'];
}
else
{
	unset($_SESSION['F_Patc']);
}
if (isset($_POST['Typt']) && ($_POST['Typt'] != 'all'))
{
	$_SESSION['F_Typt'] = $_POST['Typt'];
}
else
{
	unset($_SESSION['F_Typt']);
}
if (isset($_POST['Prio']) && ($_POST['Prio'] != 'all'))
{
	$_SESSION['F_Prio'] = $_POST['Prio'];
}
else
{
	unset($_SESSION['F_Prio']);
}
if (isset($_POST['Stat']) && ($_POST['Stat'] != 'all'))
{
	$_SESSION['F_Stat'] = $_POST['Stat'];
}
else
{
	unset($_SESSION['F_Stat']);
}
if (isset($_POST['Urg']) && ($_POST['Urg'] != 'false'))
{
	$_SESSION['F_Urg'] = 1;
}
else
{
	unset($_SESSION['F_Urg']);
}
if (isset($_POST['MAsk']) && ($_POST['MAsk'] != 'false'))
{
	$_SESSION['F_MAsk'] = 1;
}
else
{
	unset($_SESSION['F_MAsk']);
}
if (isset($_POST['MCon']) && ($_POST['MCon'] != 'false'))
{
	$_SESSION['F_MCon'] = 1;
}
else
{
	unset($_SESSION['F_MCon']);
}
if (isset($_POST['MAffc']) && ($_POST['MAffc'] != 'false'))
{
	$_SESSION['F_MAffc'] = 1;
}
else
{
	unset($_SESSION['F_MAffc']);
}
if (isset($_POST['CODTSK']) && ($_POST['CODTSK'] != ''))
{
	$_SESSION['F_CODTSK'] = $_POST['CODTSK'];
}
else
{
	unset($_SESSION['F_CODTSK']);
}
if (isset($_POST['LBLTSK']) && ($_POST['LBLTSK'] != ''))
{
	$_SESSION['F_LBLTSK'] = $_POST['LBLTSK'];
}
else
{
	unset($_SESSION['F_LBLTSK']);
}
$qry = '';
$qry .= 'SELECT * FROM DEVTAMG.TAMGTASK WHERE ACTTASK = 1 ';
if (empty($_SESSION['isAdm']) || !$_SESSION['isAdm'])
{
	$qry .= 'AND PUBTASK = 1 ';
}

if (isset($_SESSION['F_App']))
{
	$qry .= 'AND CODTASK IN (SELECT CODTASK FROM DEVTAMG.TAMGAPTA WHERE CODAPP = '.$_SESSION['F_App'].') ';
}
if (isset($_SESSION['F_Patc']))
{
	$qry .= 'AND CODTASK IN (SELECT CODTASK FROM DEVTAMG.TAMGPATA WHERE CODPATC = '.$_SESSION['F_Patc'].') ';
}
if (isset($_SESSION['F_Urg']))
{
	$qry .= 'AND URGTASK = '.$_SESSION['F_Urg'].' ';
}
if (isset($_SESSION['F_Typt']))
{
	$qry .= 'AND CODTYPT = '.$_SESSION['F_Typt'].' ';
}
if (isset($_SESSION['F_Prio']))
{
	$qry .= 'AND CODPRIO = '.$_SESSION['F_Prio'].' ';
}
if (isset($_SESSION['F_Stat']))
{
	$qry .= 'AND CODTASK IN (';
	$qry .= 'SELECT CODTASK FROM DEVTAMG.TAMGHSTS';
	$qry .= ' WHERE (CODTASK, TSTPSTS) IN';
	$qry .= ' (SELECT CODTASK, MAX(TSTPSTS) FROM DEVTAMG.TAMGHSTS';
	$qry .= ' GROUP BY CODTASK)';
	$qry .= ' AND CODSTS = '.$_SESSION['F_Stat'].')';
}
if (isset($_SESSION['F_MAsk']) || isset($_SESSION['F_MCon']) || isset($_SESSION['F_MAffc']))
{
	$qry .= ' AND (';
}
if (isset($_SESSION['F_MAsk']))
{
	$qry .= 'CODUSER = \''.$_SESSION['coduser'].'\'';
	if (isset($_SESSION['F_MCon']) || isset($_SESSION['F_MAffc']))
	{
		$qry .= ' OR ';
	}
}
if (isset($_SESSION['F_MCon']))
{
	$qry .= 'CODTASK IN (SELECT CODTASK FROM DEVTAMG.TAMGDEST WHERE CODUSER = \''.$_SESSION['coduser'].'\') ';
	if (isset($_SESSION['F_MAffc']))
	{
		$qry .= ' OR ';
	}
}
if (isset($_SESSION['F_MAffc']))
{
	$qry .= 'CODTASK IN (SELECT CODTASK FROM DEVTAMG.TAMGAFFC WHERE CODUSER = \''.$_SESSION['coduser'].'\') ';
}
if (isset($_SESSION['F_MAsk']) || isset($_SESSION['F_MCon']) || isset($_SESSION['F_MAffc']))
{
	$qry .= ')';
}
if (isset($_SESSION['F_CODTSK']))
{
	$qry .= ' AND CODTASK = '.$_SESSION['F_CODTSK'];
}
if (isset($_SESSION['F_LBLTSK']))
{
	$qry .= ' AND LBLTASK LIKE \'%'.$_SESSION['F_LBLTSK'].'%\'';
}
echo $qry;
$_SESSION['qry'] = $qry;

?>