<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 23/06/15
 * Time: 11.52
 */
require_once "../../../lib/start.php";
require_once "../lib/MiddleSchoolFinalExamPDF.php";

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

$sel_esiti = "SELECT rb_ex_esami_licenza.* FROM rb_ex_esami_licenza WHERE classe = ".$_SESSION['__classe__']->get_ID();
$res_esiti = $db->executeQuery($sel_esiti);
if ($res_esiti->num_rows > 0) {
	while ($row = $res_esiti->fetch_assoc()) {
		$students[$row['alunno']]['finale']['esito'] = $row['esito'];
		$students[$row['alunno']]['finale']['id_esito'] = $row['id'];
		$students[$row['alunno']]['finale']['voto'] = $row['voto'];
	}
}

$sel_val = "SELECT * FROM rb_ex_esiti_esame ORDER BY id";
$res_val = $db->executeQuery($sel_val);
$esiti_possibili = array();
while ($row = $res_val->fetch_assoc()) {
	$esiti_possibili[$row['id']] = $row;
}

$presidente = $db->executeCount("SELECT presidente FROM rb_ex_dati_amministrativi_esame WHERE anno = {$year}");

$file = "esiti_esame".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione().".pdf";
$pdf = new MiddleSchoolFinalExamPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor("IStituto comprensivo Nivola");
$pdf->SetTitle('Tabellone esiti esame conclusivo');
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
$pdf->AddPage("P", "A4");
$pdf->createTable($students, $_SESSION['__classe__'], $presidente, $esiti_possibili);
$pdf->Output($file, 'D');
