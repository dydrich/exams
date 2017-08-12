<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 23/06/15
 * Time: 9.34
 * esiti esame di licenza media
 */
ini_set("display_errors", DISPLAY_ERRORS);
require_once "../../../lib/start.php";

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_mod_home__'] = "../";
$_SESSION['__area__'] = "teachers";

$year = $_SESSION['__current_year__']->get_ID();

$students = $_SESSION['students'];

if((!$_SESSION['__user__']->isCoordinator($_SESSION['__classe__']->get_ID())) && ($_SESSION['__user__']->getUsername() != "rbachis") && $_SESSION['__user__']->getSchoolOrder() != 2 ){
	$_SESSION['__referer__'] = $_SERVER['HTTP_REFERER'];
	header("Location: no_permission.php");
}


$sel_esiti = "SELECT rb_ex_esami_licenza.* FROM rb_ex_esami_licenza WHERE classe = ".$_SESSION['__classe__']->get_ID();
$res_esiti = $db->executeQuery($sel_esiti);
if ($res_esiti->num_rows > 0) {
	while ($row = $res_esiti->fetch_assoc()) {
		$students[$row['alunno']]['finale']['esito'] = $row['esito'];
		$students[$row['alunno']]['finale']['id_esito'] = $row['id'];
		$students[$row['alunno']]['finale']['voto'] = $row['voto'];
	}
}

$sel_val = "SELECT * FROM rb_ex_esiti_esame ORDER BY id";
$res_val = $db->executeQuery($sel_val);
$esiti_possibili = array();
while ($row = $res_val->fetch_assoc()) {
	$esiti_possibili[$row['id']] = $row;
}


$navigation_label = "Registro personale ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();
$drawer_label = "Esiti esame conclusivo";

include "esiti_esame.html.php";
