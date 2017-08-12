<?php

/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 15/07/17
 * Time: 11.51
 */

require_once "WrittenTest.php";

class ExamTest extends WrittenTest
{
	protected $commission;
	protected $class;
	protected $students;

	public function __construct($id, $year, MySQLDataLoader $loader, $commission, $class) {
		$this->testID = $id;
		$this->datasource = $loader;
		$this->year = $year;
		$this->commission = $commission;
		$this->class = $class;
		$this->loadStudentsData();
	}

	protected function loadStudentsData() {
		$sel_pubb = "SELECT id_pagella FROM rb_pubblicazione_pagelle WHERE anno = {$this->year} AND quadrimestre = 2";
		$id_pubb = $this->datasource->executeCount($sel_pubb);
		$studenti = [];
		$res = $this->datasource->executeQuery("SELECT rb_alunni.id_alunno AS id_alunno, cognome, nome 
							  FROM rb_alunni, rb_pagelle, rb_esiti 
							  WHERE rb_alunni.id_alunno = rb_pagelle.id_alunno 
							  AND id_pubblicazione = {$id_pubb} 
							  AND rb_alunni.id_classe = {$this->class} 
							  AND rb_esiti.id_esito = rb_pagelle.esito
							  AND positivo = 1 
							  ORDER BY cognome, nome");
		foreach ($res as $row) {
			$studenti[$row['id_alunno']] = $row;
			$studenti[$row['id_alunno']]['voto'] = '';
			$studenti[$row['id_alunno']]['scelta'] = '';
			$studenti[$row['id_alunno']]['giudizio'] = '';
			$data = $this->datasource->executeQuery("SELECT voto, `traccia-quesiti`, giudizio FROM rb_ex_voti_esame WHERE alunno = {$row['id_alunno']} AND id_prova = {$this->testID}");
			if ($data[0]['voto'] != '') {
				$studenti[$row['id_alunno']]['voto'] = $data['0']['voto'];
			}
			if ($data[0]['traccia-quesiti'] != '') {
				$studenti[$row['id_alunno']]['scelta'] = $data['0']['traccia-quesiti'];
			}
			if ($data[0]['giudizio'] != '') {
				$studenti[$row['id_alunno']]['giudizio'] = $data['0']['giudizio'];
			}
		}
		$this->students = $studenti;
	}

	public function registerGrade($student, $grade) {
		$this->students[$student]['voto'] = $grade;
		$id = $this->datasource->executeCount("SELECT id FROM rb_ex_voti_esame WHERE id_prova = {$this->testID} AND alunno = $student");
		if($id) {
			$sql = "UPDATE rb_ex_voti_esame SET voto = $grade WHERE id_prova = {$this->testID} AND alunno = $student";
		}
		else {
			$sql = "INSERT INTO rb_ex_voti_esame (anno, classe, alunno, voto, id_prova) VALUES ({$this->year}, {$this->class}, $student, $grade, {$this->testID})";
		}
		$this->datasource->executeUpdate($sql);
	}

	public function registerChoice($student, $choice) {
		$this->students[$student]['scelta'] = $choice;
		$sql = "UPDATE rb_ex_voti_esame SET `traccia-quesiti` = $choice WHERE id_prova = {$this->testID} AND alunno = $student";
		$this->datasource->executeUpdate($sql);
	}

	public function getStudent($student) {
		return $this->students[$student];
	}

	/**
	 * @return mixed
	 */
	public function getCommission() {
		return $this->commission;
	}

	/**
	 * @param mixed $commission
	 */
	public function setCommission($commission) {
		$this->commission = $commission;
	}

	/**
	 * @return mixed
	 */
	public function getClass() {
		return $this->class;
	}

	/**
	 * @param mixed $class
	 */
	public function setClass($class) {
		$this->class = $class;
	}

	/**
	 * @return mixed
	 */
	public function getStudents() {
		return $this->students;
	}

	/**
	 * @param mixed $students
	 */
	public function setStudents($students) {
		$this->students = $students;
	}

	public function updateAllJudgmentsFromModel() {
		/*
		recupero giudizi
		*/
		$res = $this->datasource->executeQuery("SELECT * FROM rb_ex_giudizi_fissi WHERE materia = {$this->subjects} AND anno = {$this->year} ORDER BY voto DESC");
		$judgs = [];
		foreach ($res as $re) {
			$judgs[$re['voto']] = $re['testo'];
		}
		foreach ($this->students as $k => $student) {
			$this->students[$k]['giudizio'] = $judgs[$student['voto']];
			$this->datasource->executeUpdate("UPDATE rb_ex_voti_esame SET giudizio = '{$judgs[$student['voto']]}' WHERE id_prova = {$this->testID} AND alunno = $k");
		}
	}
}