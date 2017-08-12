<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 4/28/17
 * Time: 6:27 PM
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

$anno = $_SESSION['__current_year__']->get_ID();

$wt = WrittenTest::getInstance($_REQUEST['idp'], new MySQLDataLoader($db));
$wt->loadWorkshift();
$_SESSION['test'] = $wt;
$workshift = $wt->getWorkshift();

setlocale(LC_TIME, "it_IT.utf8");
$day = strftime("%A %d %B", strtotime(substr($wt->getDatetime(), 0, 10)));
$drawer_label = "Turni di assistenza di ".$day;

include "turni_scritti.html.php";