<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 13/07/17
 * Time: 18.21
 */
require_once "../../../lib/start.php";
require_once "../lib/NationalTest.php";

ini_set('display_errors', 1);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_mod_home__'] = "../";
$_SESSION['__area__'] = "teachers";

$year = $_SESSION['__current_year__']->get_ID();

$id_test = $db->executeCount("SELECT id_prova FROM rb_ex_prove_scritte WHERE anno = {$_SESSION['__current_year__']->get_ID()} AND prova = 'Prova INVALSI'");
$_SESSION['test'] = $id_test;

foreach ($_SESSION['students'] as $k => $student) {
	$grade = $db->executeCount("SELECT voto FROM rb_ex_voti_esame WHERE id_prova = {$id_test} AND alunno = {$k}");
	if ($grade) {
		$_SESSION['students'][$k]['voti']['scritti']['3#16'] = $grade;
	}
}
$studenti = $_SESSION['students'];

$exam_test = new NationalTest($id_test, $year, new MySQLDataLoader($db), null, $_SESSION['__classe__']->get_ID());

$navigation_label = "Scuola secondaria ";
$drawer_label = "A. s. ".$_SESSION['__current_year__']->get_descrizione().", classe ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione().": prova nazionale INVALSI";

include "invalsi.html.php";