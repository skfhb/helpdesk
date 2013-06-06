<?php
//---------------------------------------------------------------//
//	Projet 		: Task Manager									 //
//	Fichier 	: dettask.php 								 	 //
//  Description : Classe de gestion des commentaires	 		 //
//	Auteur 		: Hervé Bordeau									 //
// 	Date 		: 07/03/2013							     	 //
//---------------------------------------------------------------//
//Dernière modif le 08/03/2013 par HB


class comment
{
	private $_task;
	private $_user;
	private $_typc;
	private $_tstp;
	private $_text;
	private $_files;
	
	public function getTask()
	{
		return $this->_task;
	}
	
	public function getUser()
	{
		return $this->_user;
	}
	
	public function getTypc()
	{
		return $this->_typc;
	}
	
	public function getTstp()
	{
		return $this->_tstp;
	}
	
	public function getText()
	{
		return $this->_text;
	}
	
	public function getFiles()
	{
		return $this->_files;
	}
	
	public function __construct($task, $user, $typc, $tstp)
	{
		$this->_task = $task;
		$this->_user = $user;
		$this->_typc = $typc;
		$this->_tstp = $tstp;
		$this->_text = '';
		$co = openConnection();
		$sql = 'SELECT CODTASK, CODUSER, CODTYPC, TSTPCOM, NUMLGNC, TXTCOM FROM TAMGCOMT WHERE CODTASK = '.$this->_task.' AND CODUSER = \''.$this->_user.'\' AND CODTYPC = '.$this->_typc.' AND TSTPCOM >= \''.$tstp.'\' ORDER BY TSTPCOM';
		$comments = execSQL($co, $sql);
		while (odbc_fetch_row($comments))
		{
			if (abs(strtotime(odbc_result($comments, 'TSTPCOM')) - strtotime($this->_tstp)) <= 60)
			{
				$this->_tstp = odbc_result($comments, 'TSTPCOM');
				$this->_text .= odbc_result($comments, 'TXTCOM');
			}
		}
		$sql = 'SELECT CODTASK, CODUSER, CODTYPC, TSTPCOM, NUMLGNE, FILECOM FROM TAMGFILE WHERE CODTASK = '.$this->_task.' AND CODUSER = \''.$this->_user.'\' AND CODTYPC = '.$this->_typc.' AND TSTPCOM >= \''.$tstp.'\' ORDER BY TSTPCOM';
		$files = execSQL($co, $sql);
		$this->_tstp = $tstp;
		while (odbc_fetch_row($files))
		{
			if ((strtotime(odbc_result($files, 'TSTPCOM')) - strtotime($this->_tstp)) <= 60)
			{
				$this->_tstp = odbc_result($files, 'TSTPCOM');
				$this->_files[count($this->_files)] = new attachment(odbc_result($files, 'FILECOM'));
			}
		}
		$this->_tstp = $tstp;
		$sql = 'SELECT NAMUSER FROM TAMGUSER WHERE CODUSER = \''.$this->_user.'\'';
		odbc_fetch_row($users = execSQL($co, $sql));
		$this->_user = odbc_result($users, 'NAMUSER');
		$sql = 'SELECT LBLTYPC FROM TAMGTYPC WHERE CODTYPC = '.$this->_typc;
		odbc_fetch_row($typcs = execSQL($co, $sql));
		$this->_typc = odbc_result($typcs, 'LBLTYPC');
	}
	
	public function display()
	{
		echo '<div class="comment"><b>'.$this->_typc.', commentaire posté le '.formatDateTime($this->_tstp).' par '.$this->_user.'</b><br /><br />';
		if ($this->_text != '')
		{
			echo $this->_text.'<br />';
		}
		if (count($this->_files) > 0)
		{
			echo 'Pièces jointes :<br />';
			$height = 0;
			if ((floor(count($this->_files)/4)) == (count($this->_files)/4))
			{
				$height = (floor(count($this->_files)/4))*60;
			}
			else
			{
				$height = (floor(count($this->_files)/4)+1)*60;
			}
			echo '<div class="PJs" style="height:'.strval($height).'px;">';
			for ($i = 0 ; $i < count($this->_files) ; $i++)
			{
				$this->_files[$i]->display();
			}
			echo '</div>';
		}
		echo '</div>';
	}
	
	public function equals($comment)
	{
		$isEquals = true;
		
		if (($comment->getTask() != $this->getTask()) || ($comment->getUser() != $this->getUser()) || ($comment->getTypc() != $this->getTypc()) || ($comment->getTstp() != $this->getTstp()) || ($comment->getText() != $this->getText()))
		{
			$isEquals = false;
		}
		
		return $isEquals;
	}
}

class attachment
{
	private $_path;
	private $_name;
	private $_ext;
	
	public function getPath()
	{
		return $this->_path;
	}
	
	public function getName()
	{
		return $this->_name;
	}
	
	public function getExt()
	{
		return $this->_ext;
	}
	
	public function __construct($path)
	{
		$name = explode('/', $path);
		$name = trim($name[count($name)-1]);
		$ext = explode('.', $name);
		$ext = trim($ext[count($ext)-1]);
		$this->_path = trim($path);
		if (strlen($name) > 12)
		{
			$this->_name = substr($name, 0, 12).'...';
		}
		else
		{
			$this->_name = $name;
		}
		$this->_ext = $ext;
	}
	
	public function display()
	{
		if (file_exists('resources/files/'.$this->_ext.'.png'))
		{
			echo '<div class="PJ" onclick="download(\''.$this->_path.'\');"><img src="resources/files/'.$this->_ext.'.png" alt="Fichier" width="40" height="40" />'.$this->_name.'</div>';
		}
		else
		{
			echo '<div class="PJ" onclick="download(\''.$this->_path.'\');"><img src="resources/files/unknown.png" alt="Fichier" width="40" height="40" />'.$this->_name.'</div>';
		}
	}
}
?>