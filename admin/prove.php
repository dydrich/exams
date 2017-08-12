<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 4/23/17
 * Time: 7:23 PM
 */
require_once "../../../lib/start.php";
require_once "../../../lib/RBTime.php";
require_once "../lib/WrittenTest.php";

ini_set("display_errors", "1");

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_mod_home__'] = "../";
$_SESSION['__area__'] = "manager";

$navigation_label = "Scuola secondaria ";
$drawer_label = "Gestione prove";

$anno = $_SESSION['__current_year__']->get_ID();

$sel_prove = "SELECT * FROM rb_ex_prove_scritte WHERE anno = $anno ORDER BY data ASC";
$res_prove = $db->executeQuery($sel_prove);

$sel_comm = "SELECT * FROM rb_ex_commissioni_esame WHERE anno = $anno";
$res_comm = $db->executeQuery($sel_comm);

include "prove.html.php";