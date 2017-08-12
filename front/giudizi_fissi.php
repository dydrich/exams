<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 16/07/17
 * Time: 10.28
 */
require_once "../../../lib/start.php";

ini_set('display_errors', 1);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_mod_home__'] = "../";
$_SESSION['__area__'] = "teachers";

$navigation_label = "Scuola secondaria ";
$drawer_label = "Creazione giudizi fissi: ".$_SESSION['teacher_subjects'][$_REQUEST['sub']]['prova'];

$sub = $_REQUEST['sub'];
$year = $_SESSION['__current_year__']->get_ID();

$grades = [];
for ($i = 10; $i > 3; $i--) {
	$grades[$i] = '';
}
$res = $db->executeQuery("SELECT * FROM rb_ex_giudizi_fissi WHERE anno = $year AND materia = $sub ORDER BY voto DESC");
while ($row = $res->fetch_assoc()) {
	$grades[$row['voto']] = $row['testo'];
}

include "giudizi_fissi.html.php";