<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 02/06/17
 * Time: 10.12
 * orali
 */
require_once "../../../lib/start.php";
require_once "../../../lib/RBTime.php";

ini_set("display_errors", "1");

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_mod_home__'] = "../";
$_SESSION['__area__'] = "manager";

$navigation_label = "Scuola secondaria ";
$drawer_label = "Gestione orali";

$anno = $_SESSION['__current_year__']->get_ID();

$sel_comm = "SELECT * FROM rb_ex_commissioni_esame WHERE anno = $anno";
$res_comm = $db->executeQuery($sel_comm);
$commissioni = [];
while ($row = $res_comm->fetch_assoc()) {
	$commissioni[$row['id_commissione']] = ['numero' => $row['numero'], 'classe' => $row['classe'], 'sezione' => $row['sezione'], 'data' => '', 'studenti' => []];
	$dt = $db->executeCount("SELECT data FROM rb_ex_orali WHERE id_commissione = {$row['id_commissione']}");
	if ($dt) {
		$commissioni[$row['id_commissione']]['data'] = $dt;
	}

	$res_st = $db->executeQuery("SELECT id_alunno, cognome, nome FROM rb_alunni WHERE id_classe = {$row['classe']} ORDER BY cognome, nome");
	while ($r = $res_st->fetch_assoc()) {
		$commissioni[$row['id_commissione']]['studenti'][$r['id_alunno']] = $r['cognome']." ".$r['nome'];
	}
}

include "orali.html.php";