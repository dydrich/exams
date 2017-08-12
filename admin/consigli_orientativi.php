<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 28/07/17
 * Time: 19.18
 */
require_once "../../../lib/start.php";

ini_set("display_errors", "1");

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_mod_home__'] = "../";
$_SESSION['__area__'] = "manager";

$navigation_label = "Scuola secondaria ";
$drawer_label = "Esame di Stato: giudizi orientativi";

$anno = $_SESSION['__current_year__']->get_ID();

$res_g = $db->executeQuery("SELECT * FROM rb_ex_consigli_orientativi ORDER BY id_giudizio");

include "consigli_orientativi.html.php";