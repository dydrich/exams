<?php

/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 15/07/17
 * Time: 16.36
 */
require_once "ExamTest.php";

class NationalTest extends ExamTest
{
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
			$studenti[$row['id_alunno']]['ita'] = 0;
			$studenti[$row['id_alunno']]['mat'] = 0;
			$data = $this->datasource->executeQuery("SELECT voto, giudizio FROM rb_ex_voti_esame WHERE alunno = {$row['id_alunno']} AND id_prova = {$this->testID}");
			if ($data[0]['voto'] != '') {
				$studenti[$row['id_alunno']]['voto'] = $data['0']['voto'];
			}
			if ($data[0]['giudizio'] != '') {
				list ($ita, $mat) = explode("#", $data[0]['giudizio']);
				$studenti[$row['id_alunno']]['ita'] = $ita;
				$studenti[$row['id_alunno']]['mat'] = $mat;
			}
		}
		$this->students = $studenti;
	}

	public function registerScore($student, $ita, $mat) {
		$this->students[$student]['ita'] = $ita;
		$this->students[$student]['mat'] = $mat;
		$value = $ita."#".$mat;
		$sql = "UPDATE rb_ex_voti_esame SET `giudizio` = '$value' WHERE id_prova = {$this->testID} AND alunno = $student";
		$this->datasource->executeUpdate($sql);
	}

}