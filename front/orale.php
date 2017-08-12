<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 30/07/17
 * Time: 19.26
 */
require_once "../../../lib/start.php";

ini_set('display_errors', 1);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_mod_home__'] = "../";
$_SESSION['__area__'] = "teachers";

$year = $_SESSION['__current_year__']->get_ID();

$student = $_SESSION['students'][$_REQUEST['aid']];

$students = [];
foreach ($_SESSION['students'] as $k => $st) {
	$students[] = ['id' => $k, 'value' => $st['cognome']." ".$st['nome']];
}

$grade_id = $db->executeCount("SELECT id FROM rb_ex_voti_esame WHERE alunno = {$_REQUEST['aid']} AND anno = {$year} AND id_prova = 0");
$action = 'insert';
if($grade_id) {
	$action = "update";
	$rdata = $db->executeQuery("SELECT voto, giudizio FROM rb_ex_voti_esame WHERE id = $grade_id");
	$data = $rdata->fetch_assoc();
}

$drawer_label = "Esame: dettaglio colloquio di ".$student['cognome']." ".$student['nome'];
$navigation_label = "Scuola secondaria ";

include "orale.html.php";