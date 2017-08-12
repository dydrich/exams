<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 16/07/17
 * Time: 11.48
 */
require_once "../../../lib/start.php";

ini_set("display_errors", "1");

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_mod_home__'] = "../";
$_SESSION['__area__'] = "teachers";

$field = $_REQUEST['id'];
$value = $_REQUEST['value'];
$year = $_SESSION['__current_year__']->get_ID();

$upd = "REPLACE INTO rb_ex_giudizi_fissi (testo, voto, anno, materia) VALUES ('$value', $field, $year, {$_SESSION['sub']})";
$update_var = $db->executeUpdate($upd);

header("Content-type: text/plain");
print $value;
exit;