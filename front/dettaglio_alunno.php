<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 26/07/17
 * Time: 18.41
 */
require_once "../../../lib/start.php";
require_once "../lib/ExamTest.php";
require_once "../lib/NationalTest.php";

ini_set('display_errors', 1);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_mod_home__'] = "../";
$_SESSION['__area__'] = "teachers";

$year = $_SESSION['__current_year__']->get_ID();
$student = $_SESSION['students'][$_REQUEST['aid']];

$drawer_label = "Dettaglio dell'alunno";
$navigation_label = "Scuola secondaria ";

$sel_al = "SELECT * FROM rb_alunni LEFT JOIN rb_indirizzi_alunni ON rb_alunni.id_alunno = rb_indirizzi_alunni.id_alunno WHERE rb_alunni.id_alunno = ".$_REQUEST['aid'];
$res_al = $db->executeQuery($sel_al);
$al = $res_al->fetch_assoc();

$res_cons = $db->executeQuery("SELECT rb_ex_consigli_orientativi_alunni.consiglio AS id, rb_ex_consigli_orientativi.giudizio AS consiglio
									FROM rb_ex_consigli_orientativi_alunni, rb_ex_consigli_orientativi 
									WHERE anno = $year 
									AND id_alunno = {$_REQUEST['aid']} 
									AND rb_ex_consigli_orientativi_alunni.consiglio = rb_ex_consigli_orientativi.id_giudizio");
$cons = $res_cons->fetch_assoc();

include "dettaglio_alunno.html.php";