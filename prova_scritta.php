<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 4/23/17
 * Time: 7:36 PM
 */
require_once "../../lib/start.php";
require_once "lib/WrittenTest.php";

ini_set("display_errors", "1");

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";
$_SESSION['__area__'] = "manager";

$navigation_label = "Scuola secondaria ";
$drawer_label = "Nuova prova scritta";

$anno = $_SESSION['__current_year__']->get_ID();

$sel_subjs = "SELECT * FROM rb_materie WHERE tipologia_scuola = 1 AND has_sons = 0 AND id_materia IN (3, 7, 10, 11, 16) ORDER BY materia";
$res_subjs = $db->executeQuery($sel_subjs);

if ($_REQUEST['idp'] == 0) {
	$action = 'insert';
}
else {
	$action = "update";
	$wt = WrittenTest::getInstance($_REQUEST['idp'], new MySQLDataLoader($db));
}

include "prova_scritta.html.php";