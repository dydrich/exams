<?php

/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 4/23/17
 * Time: 7:34 PM
 */
class WrittenTest
{
	protected $testID;
	protected $description;
	protected $subjects;
	protected $datetime;
	protected $year;
	protected $duration;
	protected $datasource;
	protected $workshift;

	/**
	 * WrittenTest constructor.
	 * @param $description
	 * @param $subjects
	 * @param $datetime
	 * @param $year
	 * @param $duration
	 * @param $datasource
	 */
	public function __construct($id, $description, $subjects, $datetime, $year, RBTime $duration, MySQLDataLoader $datasource, $workshift = null) {
		$this->testID = $id;
		$this->description = $description;
		if (is_array($subjects)) {
			$this->subjects = $subjects;
		}
		else {
			$this->subjects = explode("#", $subjects);
		}
		$this->datetime = $datetime;
		$this->year = $year;
		$this->duration = $duration;
		$this->datasource = $datasource;

		if ($subjects == "0") {
			$this->subjects = [3, 16];
		}
		if ($workshift != null) {
			$this->workshift = $workshift;
		}
		else {
			$this->loadWorkshift();
		}
	}

	/**
	 * @return mixed
	 */
	public function getTestID() {
		return $this->testID;
	}

	/**
	 * @param mixed $testID
	 */
	public function setTestID($testID) {
		$this->testID = $testID;
	}

	/**
	 * @return mixed
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param mixed $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @return mixed
	 */
	public function getSubjects() {
		return $this->subjects;
	}

	/**
	 * @param mixed $subjects
	 */
	public function setSubjects($subjects) {
		$this->subjects = $subjects;
	}

	/**
	 * @return mixed
	 */
	public function getDatetime() {
		return $this->datetime;
	}

	/**
	 * @param mixed $datetime
	 */
	public function setDatetime($datetime) {
		$this->datetime = $datetime;
	}

	/**
	 * @return mixed
	 */
	public function getYear() {
		return $this->year;
	}

	/**
	 * @param mixed $year
	 */
	public function setYear($year) {
		$this->year = $year;
	}

	/**
	 * @return mixed
	 */
	public function getDuration() {
		return $this->duration;
	}

	/**
	 * @param mixed $duration
	 */
	public function setDuration($duration) {
		$this->duration = $duration;
	}

	/**
	 * @return mixed
	 */
	public function getDatasource() {
		return $this->datasource;
	}

	/**
	 * @param mixed $datasource
	 */
	public function setDatasource($datasource) {
		$this->datasource = $datasource;
	}

	/**
	 * @return null
	 */
	public function getWorkshift() {
		return $this->workshift;
	}

	/**
	 * @param null $workshift
	 */
	public function setWorkshift($workshift) {
		$this->workshift = $workshift;
	}

	public function loadWorkshift() {
		$workshift = [];
		$sel_comm = "SELECT * FROM rb_ex_commissioni_esame WHERE anno = {$_SESSION['__current_year__']->get_ID()}";
		$res_comm = $this->datasource->executeQuery($sel_comm);
		foreach ($res_comm as $comm) {
			if (!isset($workshift[$comm['id_commissione']])) {
				$workshift[$comm['id_commissione']] = ['id' => $comm['id_commissione'], 'number' => $comm['numero'], 'class' => $comm['sezione'], 'teachers' => []];
			}
		}
		/* dsa */
		$workshift[0] = ['id' => 0, 'number' => 0, 'class' => '', 'teachers' => []];

		$sel_turni = "SELECT rb_ex_turni_assistenza.*, cognome, nome
			  FROM rb_ex_turni_assistenza, rb_utenti 
			  WHERE rb_ex_turni_assistenza.prova = ".$this->testID." 
			  AND docente = uid
			  ORDER BY cognome, nome";
		$res = $this->datasource->executeQuery($sel_turni);
		if ($res) {
			foreach ($res as $re) {
				$workshift[$re['commissione']]['teachers'][$re['docente']] = ['uid' => $re['docente'], 'teacher' => $re['cognome']." ".$re['nome']];
			}
		}
		$this->workshift = $workshift;
	}

	public function addWorkshift($teacher, $comm, $name) {
		$this->workshift[$comm]['teachers'][$teacher] = ['uid' => $teacher, 'teacher' => $name];
		$this->datasource->executeUpdate("INSERT INTO rb_ex_turni_assistenza (prova, commissione, docente) VALUES ({$this->testID}, {$comm}, {$teacher})");
	}

	public static function getInstance($id, MySQLDataLoader $db) {
		$data = $db->executeQuery("SELECT * FROM rb_ex_prove_scritte WHERE id_prova = {$id}");
		$rbt = new RBTime(0, 0, 0);
		$rbt->setTime($data[0]['durata']*60);
		$ret = new WrittenTest($id, $data[0]['prova'], $data[0]['materie'], $data[0]['data'], $_SESSION['__current_year__']->get_ID(), $rbt, $db);
		return $ret;
	}

	public function insert() {
		$sbjs = implode("#", $this->subjects);
		$duration = $this->duration->getTime() / 60;
		$sql = "INSERT INTO rb_ex_prove_scritte (anno, prova, materie, data, durata) VALUES ({$this->year}, '{$this->description}', '{$sbjs}', '{$this->datetime}', $duration)";
		$this->testID = $this->datasource->executeUpdate($sql);
		return $this->testID;
	}

	public function update() {
		$sbjs = implode("#", $this->subjects);
		$duration = $this->duration->getTime() / 60;
		$sql = "REPLACE INTO rb_ex_prove_scritte (id_prova, anno, prova, materie, data, durata) VALUES ({$this->testID}, {$this->year}, '{$this->description}', '{$sbjs}', '{$this->datetime}', $duration)";
		$this->datasource->executeUpdate($sql);
	}
}