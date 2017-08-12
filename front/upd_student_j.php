<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 30/07/17
 * Time: 16.58
 */
require_once "../../../lib/start.php";

ini_set("display_errors", "1");

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_mod_home__'] = "../";
$_SESSION['__area__'] = "teachers";

$std = $_REQUEST['id'];
$value = $db->real_escape_string($_REQUEST['value']);
$year = $_SESSION['__current_year__']->get_ID();
$test = $_SESSION['test'];

$upd = "UPDATE rb_ex_voti_esame SET giudizio = '{$value}' WHERE anno = $year AND id_prova = $test AND alunno = $std";
$update_var = $db->executeUpdate($upd);

header("Content-type: text/plain");
print $value;
exit;