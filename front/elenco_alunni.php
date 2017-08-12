<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 13/07/17
 * Time: 17.08
 */
require_once "../../../lib/start.php";
ini_set('display_errors', 1);
check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_mod_home__'] = "../";
$_SESSION['__area__'] = "teachers";

$year = $_SESSION['__current_year__']->get_ID();
$sel_pubb = "SELECT id_pagella FROM rb_pubblicazione_pagelle WHERE anno = {$year} AND quadrimestre = 2";
$res_pubb = $db->executeQuery($sel_pubb);
$row = $res_pubb->fetch_assoc();
$id_pubb = $row['id_pagella'];

$cls = $db->executeCount("SELECT classe FROM rb_ex_commissioni_esame WHERE id_commissione = {$_SESSION['comm']}");
foreach ($_SESSION['students'] as $k => $item) {
	$grades = [];
	$grades[] = $item['voti']['ammissione'];
	foreach ($item['voti']['scritti'] as $t) {
		if ($t != '') {
			$grades[] = $t;
		}
	}
	if ($item['voti']['orale'] != '') {
		$grades[] = $item['voti']['orale'];
	}
	$sum = array_sum($grades);
	$avg = round(($sum / count($grades)), 2);
	$_SESSION['students'][$k]['voti']['avg'] = $avg;
}
$studenti = $_SESSION['students'];

$drawer_label = "Esame: riepilogo classe ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();
$navigation_label = "Scuola secondaria ";

include "elenco_alunni.html.php";