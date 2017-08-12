<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 29/07/17
 * Time: 16.20
 */
require_once "../../../lib/start.php";

ini_set('display_errors', 1);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_mod_home__'] = "../";
$_SESSION['__area__'] = "teachers";

$year = $_SESSION['__current_year__']->get_ID();

$res_cons = $db->executeQuery("SELECT rb_ex_consigli_orientativi_alunni.consiglio AS id, id_alunno, rb_ex_consigli_orientativi.giudizio AS consiglio
									FROM rb_ex_consigli_orientativi_alunni, rb_ex_consigli_orientativi 
									WHERE anno = $year 
									AND classe = {$_SESSION['__classe__']->get_ID()} 
									AND rb_ex_consigli_orientativi_alunni.consiglio = rb_ex_consigli_orientativi.id_giudizio");
$cons = [];
while ($row = $res_cons->fetch_assoc()) {
	$cons[$row['id_alunno']] = ["id" => $row['id'], "consiglio" => $row['consiglio']];
}

$r_cons = $db->executeQuery("SELECT * FROM rb_ex_consigli_orientativi ORDER BY id_giudizio");

$students = $_SESSION['students'];

$navigation_label = "Scuola secondaria ";
$drawer_label = "Guidizio orientativo classe ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();

include "consiglio_orientativo.html.php";