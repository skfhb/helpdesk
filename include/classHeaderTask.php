<?php
//---------------------------------------------------------------//
//	Projet 		: Task Manager									 //
//	Fichier 	: classHeaderTask.php						 	 //
//  Description : Classe de gestion des entêtes de tâche 		 //
//	Auteur 		: Hervé Bordeau									 //
// 	Date 		: 07/03/2013							     	 //
//---------------------------------------------------------------//
//Dernière modif le 08/03/2013 par HB

class headerTask
{
	private $_codtask;
	private $_urgent;
	private $_statut;
	private $_typtask;
	private $_lbltask;
	private $_dataskt;
	private $_lastmodf;
	private $_rowtask;
	
	public function getCodtask()
	{
		return $this->_codtask;
	}
	
	public function getUrgent()
	{
		return $this->_urgent;
	}
	
	public function getStatut()
	{
		return $this->_statut;
	}
	
	public function getTyptask()
	{
		return $this->_typtask;
	}
	
	public function getLbltask()
	{
		return $this->_lbltask;
	}
	
	public function getDataskt()
	{
		return $this->_dataskt;
	}
	
	public function getLastmodf()
	{
		return $this->_lastmodf;
	}
	
	public function getRowtask()
	{
		return $this->_rowtask;
	}
}
?>
	