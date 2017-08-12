<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 08/08/17
 * Time: 18.15
 */
require_once "../../../lib/start.php";
require_once "../lib/MiddleSchoolFinalExamLog.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_mod_home__'] = "../";
$_SESSION['__area__'] = "teachers";

$year = $_SESSION['__current_year__']->get_ID();

$students = $_SESSION['students'];

if((!$_SESSION['__user__']->isCoordinator($_SESSION['__classe__']->get_ID())) && ($_SESSION['__user__']->getUsername() != "rbachis") ){
	$_SESSION['__referer__'] = $_SERVER['HTTP_REFERER'];
	header("Location: no_permission.php");
}

$presidente = $db->executeCount("SELECT presidente FROM rb_ex_dati_amministrativi_esame WHERE anno = {$year}");
$comm = $db->executeCount("SELECT numero FROM rb_ex_commissioni_esame WHERE anno = $year AND sezione = '".$_SESSION['__classe__']->get_sezione()."'");

$docenti = [];
$sel_teachers = "SELECT nome, cognome, uid, sostituto FROM rb_utenti, rb_ex_docenti_commissione_esame WHERE docente = uid AND commissione = {$comm}";
$res_teachers = $db->executeQuery($sel_teachers);
while ($row = $res_teachers->fetch_assoc()) {
	$docenti[$row['uid']] = $row;
	$docenti[$row['uid']]['sub'] = [];
	if ($row['sostituto'] != "") {
		$res_sub = $db->executeQuery("SELECT nome, cognome FROM rb_utenti WHERE uid = ".$row['sostituto']);
		$sub = $res_sub->fetch_assoc();
		$docenti[$row['uid']]['sub'] = $sub;
	}
}

$file = "schede_esame".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione().".pdf";

$pdf = new MiddleSchoolFinalExamLog(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor("IStituto comprensivo Nivola");
$pdf->SetTitle('Sche personali esame conclusivo');
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setLanguageArray($l);
$pdf->SetDisplayMode('fullpage', 'SinglePage', 'UseNone');
$pdf->SetFont('helvetica', '', 12);
$pdf->createTable($students, $_SESSION['__classe__'], $presidente, $comm, $docenti);
$pdf->Output($file, 'D');
