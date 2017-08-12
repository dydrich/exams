<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 4/23/17
 * Time: 7:02 PM
 */
require_once "../../../lib/start.php";
require_once "../../../lib/ArrayMultiSort.php";

ini_set("display_errors", "1");

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_mod_home__'] = "../";
$_SESSION['__area__'] = "manager";

$navigation_label = "Scuola secondaria ";
$drawer_label = "Gestione prove e turni";

$anno = $_SESSION['__current_year__']->get_ID();

include "gestione_calendario.html.php";