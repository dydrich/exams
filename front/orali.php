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

$students = $_SESSION['students'];

$drawer_label = "Esame: riepilogo orali classe ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();
$navigation_label = "Scuola secondaria ";

include "orali.html.php";