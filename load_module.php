<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 4/19/17
 * Time: 7:31 PM
 */

/**
 * load the requested module
 */

require_once "../../lib/start.php";
require_once "../../lib/RBUtilities.php";

check_session();

$module_code = $_REQUEST['module'];

$sel_module = "SELECT * FROM rb_modules WHERE code_name = '{$module_code}'";
$res_module = $db->execute($sel_module);
$module = $res_module->fetch_assoc();

$_SESSION['__modules__'][$module_code]['home'] = $module['home'];
$_SESSION['__modules__'][$module_code]['lib_home'] = $module['lib_home'];
$_SESSION['__modules__'][$module_code]['front_page'] = $module['front_page'];
$_SESSION['__modules__'][$module_code]['path_to_root'] = $module['path_to_root'];

$_SESSION['__mod_area__'] = $_REQUEST['area'];

$anno = $_SESSION['__current_year__']->get_ID();

/*
 * carica in sessione le prove
 */
$res_tests = $db->executeQuery("SELECT * FROM rb_ex_prove_scritte WHERE anno = {$anno} ORDER BY data ");
$tests = [];
while ($row = $res_tests->fetch_assoc()) {
	$tests[$row['id_prova']] = $row;
}
$_SESSION['tests'] = $tests;

if (isset($_REQUEST['page']) && $_REQUEST['page'] === 'front'){
		header("Location: front/index.php");
}
else if (isset($_REQUEST['page']) && $_REQUEST['page'] === 'admin'){
	header("Location: admin/index.php");
}
else {
	header("Location: {$module['front_page']}");
}
