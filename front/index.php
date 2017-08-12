<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 12/07/17
 * Time: 12.12
 */
require_once "../../../lib/start.php";

ini_set('display_errors', 1);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_mod_home__'] = "../";
$_SESSION['__area__'] = "teachers";

/*
 * commissione d'esame
 */
$comm = $db->executeCount("SELECT id_commissione FROM rb_ex_commissioni_esame WHERE anno = {$_SESSION['__current_year__']->get_ID()} AND sezione = '{$_SESSION['__classe__']->get_sezione()}'");
$_SESSION['comm'] = $comm;

/*
 * turni di assistenza
 */
$sel_t = "SELECT prova FROM rb_ex_turni_assistenza WHERE docente = {$_SESSION['__user__']->getUid()}";
$res_t = $db->executeQuery($sel_t);
$turni = [];
while ($r = $res_t->fetch_assoc()) {
	$turni[] = $r['prova'];
}

/*
 * calendario degli impegni
 */
$calendar = [];
$sel = "SELECT id_prova, prova, DATE(data) AS data FROM rb_ex_prove_scritte WHERE anno = {$_SESSION['__current_year__']->get_ID()} 
		UNION SELECT 0 AS id_prova, 'Orali' AS prova, DATE(data) AS data FROM rb_ex_orali WHERE anno = {$_SESSION['__current_year__']->get_ID()} AND id_commissione = {$comm}
		ORDER BY data";
$res = $db->executeQuery($sel);
while ($row = $res->fetch_assoc()) {
	$calendar[$row['data']] = ['id_prova' => $row['id_prova'], 'data' => $row['data'], 'prova' => $row['prova'], 'assistenza' => 0];
	if (in_array($row['id_prova'], $turni)) {
		$calendar[$row['data']]['assistenza'] = 1;
	}
	if ($row['id_prova'] == 0) {
		$calendar[$row['data']]['assistenza'] = 1;
	}
}

/*
 * scritti da valutare
 */
$exam_subjects = [];
foreach ($_SESSION['tests'] as $test) {
	if ($test['prova'] == "Prova INVALSI") {
		continue;
	}
	$exam_subjects[$test['materie']] = ["id" => $test['id_prova'], "prova" => $test['prova']];
}
$teacher_subjects = [];
$cdc_sub = $db->executeQuery("SELECT id_materia FROM rb_cdc 
								  WHERE id_classe = {$_SESSION['__classe__']->get_ID()} 
								  AND id_anno = {$_SESSION['__current_year__']->get_ID()} 
								  AND id_docente = {$_SESSION['__user__']->getUid()}");
while ($r = $cdc_sub->fetch_assoc()) {
	if (isset($exam_subjects[$r['id_materia']])) {
		$teacher_subjects[$r['id_materia']] = $exam_subjects[$r['id_materia']];
	}
}
$_SESSION['teacher_subjects'] = $teacher_subjects;

/*
 * studenti
 */
$anno = $_SESSION['__current_year__']->get_ID();
$sel_pubb = "SELECT id_pagella FROM rb_pubblicazione_pagelle WHERE anno = {$anno} AND quadrimestre = 2";
$res_pubb = $db->executeQuery($sel_pubb);
$row = $res_pubb->fetch_assoc();
$id_pubb = $row['id_pagella'];
$cls = $db->executeCount("SELECT classe FROM rb_ex_commissioni_esame WHERE id_commissione = {$_SESSION['comm']}");
$studenti = [];
$res = $db->executeQuery("SELECT rb_alunni.id_alunno AS id_alunno, cognome, nome, rb_alunni.sesso AS sesso, luogo_nascita, data_nascita 
							  FROM rb_alunni, rb_pagelle, rb_esiti 
							  WHERE rb_alunni.id_alunno = rb_pagelle.id_alunno 
							  AND id_pubblicazione = {$id_pubb} 
							  AND rb_alunni.id_classe = {$cls} 
							  AND rb_esiti.id_esito = rb_pagelle.esito
							  AND positivo = 1 
							  ORDER BY cognome, nome");
while ($row = $res->fetch_assoc()) {
	$sel_avg = "SELECT AVG(CASE WHEN voto > 5 THEN voto ELSE 6 END) 
				FROM rb_scrutini 
				WHERE anno = {$anno} 
				AND quadrimestre = 2 
				AND materia != 26 
				AND alunno = {$row['id_alunno']}";
	$res_amm = $db->executeCount($sel_avg);
	$amm = round($res_amm);
	$studenti[$row['id_alunno']] = $row;
	$studenti[$row['id_alunno']]['classe'] = $cls;
	$studenti[$row['id_alunno']]['voti']['ammissione'] = $amm;
	$grades = [];
	$grades[] = $studenti[$row['id_alunno']]['voti']['ammissione'];
	foreach ($_SESSION['tests'] as $k => $test) {
		$studenti[$row['id_alunno']]['voti']['scritti'][$test['materie']] = '';
		$studenti[$row['id_alunno']]['tracce']['scritti'][$test['materie']] = '';
		$studenti[$row['id_alunno']]['giudizi']['scritti'][$test['materie']] = '';
		$res_gr = $db->executeQuery("SELECT voto, `traccia-quesiti`, giudizio FROM rb_ex_voti_esame WHERE alunno = {$row['id_alunno']} AND id_prova = $k");
		if ($res_gr) {
			$_row = $res_gr->fetch_assoc();
			$studenti[$row['id_alunno']]['voti']['scritti'][$test['materie']] = $_row['voto'];
			$studenti[$row['id_alunno']]['tracce']['scritti'][$test['materie']] = $_row['traccia-quesiti'];
			$studenti[$row['id_alunno']]['giudizi']['scritti'][$test['materie']] = $_row['giudizio'];
			$grades[] = $_row['voto'];
		}
	}
	$sum = array_sum($grades);
	$avg = round(($sum / count($grades)), 2);
	$studenti[$row['id_alunno']]['voti']['orale'] = '';
	$studenti[$row['id_alunno']]['tracce']['orale'] = '';
	$studenti[$row['id_alunno']]['voti']['avg'] = $avg;
	$studenti[$row['id_alunno']]['voti']['lode'] = $db->executeCount("SELECT COALESCE(lode, 0) FROM rb_ex_esami_licenza WHERE alunno = {$row['id_alunno']} AND anno = $anno");

	$_oral_grade = $db->executeQuery("SELECT voto, giudizio FROM rb_ex_voti_esame WHERE anno = $anno AND id_prova = 0 AND alunno = {$row['id_alunno']}");
	if($_oral_grade) {
		$oral_grade = $_oral_grade->fetch_assoc();
		$studenti[$row['id_alunno']]['voti']['orale'] = $oral_grade['voto'];
		$studenti[$row['id_alunno']]['tracce']['orale'] = $oral_grade['giudizio'];
	}

	$cons = $db->executeCount("SELECT giudizio FROM rb_ex_consigli_orientativi, rb_ex_consigli_orientativi_alunni WHERE consiglio = id_giudizio AND id_alunno = {$row['id_alunno']}");
	$studenti[$row['id_alunno']]['consiglio_orientativo'] = $cons;

	$sel_esiti = "SELECT rb_ex_esami_licenza.* FROM rb_ex_esami_licenza WHERE classe = ".$_SESSION['__classe__']->get_ID()." AND alunno = ".$row['id_alunno'];
	$res_esiti = $db->executeQuery($sel_esiti);
	if ($res_esiti->num_rows > 0) {
		while ($r = $res_esiti->fetch_assoc()) {
			if (isset($studenti[$row['id_alunno']])) {
				$studenti[$row['id_alunno']]['finale']['esito'] = $r['esito'];
				$studenti[$row['id_alunno']]['finale']['voto'] = $r['voto'];
			}
		}
	}
}

$_SESSION['students'] = $studenti;

$navigation_label = "Scuola secondaria ";
$drawer_label = "Esami di Stato a. s. ".$_SESSION['__current_year__']->get_descrizione().", classe ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();

include "index.html.php";