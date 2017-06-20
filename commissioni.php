<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 2/28/17
 * Time: 4:16 PM
 */
require_once "../../lib/start.php";
require_once "../../lib/ArrayMultiSort.php";

ini_set("display_errors", "1");

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";
$_SESSION['__area__'] = "manager";

$navigation_label = "Scuola secondaria ";
$drawer_label = "Esame di Stato: commissione e sottocommissioni";

$anno = $_SESSION['__current_year__']->get_ID();

/*
 * controllo se le commissioni sono state inserite (al primo accesso vanno inserite)
 */
$sel_commissioni = "SELECT * FROM rb_ex_commissioni_esame WHERE anno = $anno";
$res_commissioni = $db->executeQuery($sel_commissioni);
$create = false;
if($res_commissioni->num_rows < 1) {
	$create = true;
}
else {
	/*
	 * recupero i dati delle commissioni
	 */
	$commissione = [];
	$cls = [];

	$sel_comm = "SELECT * FROM rb_ex_commissioni_esame WHERE anno = $anno ORDER BY numero";
	$res_comm = $db->executeQuery($sel_comm);
	while ($comm = $res_comm->fetch_assoc()) {
		$cls[$comm['id_commissione']] = $comm;
		$cls[$comm['id_commissione']]['cdc'] = [];

		/*
		 * recupero docenti
		 * il comm_count conta le commissioni del docente: se il valore e' zero
		 * ed esiste una indicazione di sostituto, il nome va sostituito con il supplente
		 */
		$sel_teachers = "SELECT nome, cognome, uid, sostituto FROM rb_utenti, rb_ex_docenti_commissione_esame WHERE docente = uid AND commissione = {$comm['id_commissione']}";
		$res_teachers = $db->executeQuery($sel_teachers);
		while ($row = $res_teachers->fetch_assoc()) {
			$cls[$comm['id_commissione']]['cdc'][$row['uid']] = $row;
			if (!isset($commissione[$row['uid']])) {
				$commissione[$row['uid']] = $row;
				$commissione[$row['uid']]['comm_count'] = 1;
			}
			else {
				$commissione[$row['uid']]['comm_count'] += 1;
			}
			$commissione[$row['uid']]['sub'] = [];
			$cls[$comm['id_commissione']]['cdc'][$row['uid']]['sub'] = [];
			if ($row['sostituto'] != "") {
				$res_sub = $db->executeQuery("SELECT nome, cognome, uid FROM rb_utenti WHERE uid = ".$row['sostituto']);
				$sub = $res_sub->fetch_assoc();
				$commissione[$row['uid']]['sub'] = $sub;
				$cls[$comm['id_commissione']]['cdc'][$row['uid']]['sub'] = $sub;
				$commissione[$row['uid']]['comm_count'] -= 1;
				if (!isset($commissione[$row['sostituto']])) {
					$commissione[$row['sostituto']] = $sub;
					$commissione[$row['sostituto']]['comm_count'] = 1;
					$commissione[$row['sostituto']]['sub'] = [];
				}
				else {
					$commissione[$row['sostituto']]['comm_count'] += 1;
				}
			}
		}
	}
	$ams = new ArrayMultiSort($commissione);
	$ams->setSortFields(array('cognome'));
	$ams->sort();
	$commissione = $ams->getData();
}

include "commissioni.html.php";
