<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 15/07/17
 * Time: 19.12
 */
require_once "../../../lib/start.php";

ini_set('display_errors', 1);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_mod_home__'] = "../";
$_SESSION['__area__'] = "teachers";

$_SESSION['sub'] = $_REQUEST['sub'];

$navigation_label = "Scuola secondaria ";
$drawer_label = "Gestione giudizi: ".$_SESSION['teacher_subjects'][$_REQUEST['sub']]['prova'];

include "giudizi.html.php";