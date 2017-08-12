<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 08/08/17
 * Time: 18.26
 */

require_once "../../../lib/SchoolPDF.php";

class MiddleSchoolFinalExamLog extends SchoolPDF {

	public function createTable($data, Classe $cls, $president, $comm, $docenti){
		$classe = $cls->get_anno().$cls->get_sezione();
		$page = 1;
		$year = $_SESSION['__current_year__']->to_string();
		foreach ($data as $k => $student) {
			$art = "il candidato";
			$fin = "o";
			if ($student['sesso'] == 'F') {
				$art = "la candidata";
				$fin = "a";
			}
			$this->AddPage("P", "A4");
			$this->setPage($page, true);
			$this->Image($_SESSION['__path_to_root__'].'images/ministero.jpg', 90, 8, 15, 15, 'JPG', '', '', false, '');
			$this->SetFont('times', 'B', '15');
			$this->Cell(0, 14, "Ministero dell'Istruzione, dell'Università e della Ricerca", 0, 1, 'C', 0, '', 0);
			$this->SetFont('helvetica', 'B', '13');
			$this->Cell(0, 9, "ISTITUTO COMPRENSIVO \"C. NIVOLA\"", 0, 1, 'C', 0, '', 0);
			$this->SetFont('', '', '11');
			$this->Cell(0, 5, "Via Pacinotti snc - (loc. Serra Perdosa), Iglesias (CI) ", 0, 1, 'C', 0, '', 0);
			$this->SetFont('', 'B', '12');
			$this->Cell(0, 9, "Scuola statale - secondaria di primo grado", 0, 1, 'C', 0, '', 0);
			$this->Cell(0, 10, "", 0, 1, 'L', 0);
			$this->SetFont('', '', '10');
			$this->Cell(0, 4, $year, 0, 1, 'C', 0, '', 0);
			$this->Cell(0, 2, 'Sessione unica', 0, 1, 'C', 0, '', 0);
			$this->Ln();
			$this->Cell(0, 10, 'Classe Terza, sezione '.$cls->get_sezione()." - Sottocommisione n. ".$comm, 0, 1, 'C', 0, '', 0);
			$this->Ln();
			$this->Ln();
			$this->SetFont('helvetica', 'B', '18');
			$this->Cell(0, 0, 'ESAME DI STATO', 0, 1, 'C', 0, '', 0);
			$this->SetFont('helvetica', 'B', '16');
			$this->Cell(0, 0, 'conclusivo del primo ciclo di istruzione', 0, 1, 'C', 0, '', 0);
			$this->Ln();
			$this->Ln();
			$this->Ln();
			$this->SetFont('helvetica', 'B', '13');
			if ($student['sesso'] == 'M') {
				$this->Cell(0, 10, 'Scheda personale del candidato', 0, 1, 'C', 0, '', 0);
			}
			else {
				$this->Cell(0, 10, 'Scheda personale della candidata', 0, 1, 'C', 0, '', 0);
			}
			$this->SetFont('helvetica', '', '13');
			$this->Cell(0, 0, $student['cognome']." ".$student['nome'], 0, 1, 'C', 0, '', 0);
			$this->SetFont('helvetica', '', '10');
			$this->Cell(0, 15, "Nat{$fin} a ".$student['luogo_nascita']." il ".format_date($student['data_nascita'], SQL_DATE_STYLE, IT_DATE_STYLE, "/"), 0, 1, 'C', 0, '', 0);
			$this->Ln();
			$this->Ln();
			$this->SetFont('helvetica', '', '11');
			$this->Cell(140, 15, ucfirst($art)." è stat{$fin} ammess{$fin} all'esame con il seguente giudizio di ammissione: ", 0, 0, 'L', 0, '', 0);
			$this->SetFont('helvetica', 'B', '12');
			$this->Cell(0, 15, $student['voti']['ammissione'], 0, 0, 'L', 0, '', 0);
			$this->SetFont('helvetica', '', '11');
			$this->Ln();
			$this->MultiCell(0, 15, "Il Consiglio di classe ha formulato il seguente consiglio orientativo:  ", 0, 'L', false, 0, 15);
			$this->SetFont('helvetica', 'B', '11');
			$this->MultiCell(0, 15, $student['consiglio_orientativo'], 0, 'L', false, 0, 130);
			/*
			 * seconda page
			 */
			$this->AddPage("P", "A4");
			$page++;
			$this->setPage($page, true);
			$this->SetFont('times', 'B', '15');
			$this->Cell(0, 14, "PROVE D'ESAME", 0, 1, 'C', 0, '', 0);
			$tests = $_SESSION['tests'];
			foreach ($tests as $t => $test) {
				if ($test['prova'] != 'Prova INVALSI') {
					$this->SetFont('helvetica', 'B', '12');
					$this->Cell(0, 5, $test['prova'], 0, 1, 'L', 0, '', 0);
					$this->SetFont('helvetica', '', '11');
					$this->Cell(0, 5, "Traccia scelta: ".$student['tracce']['scritti'][$test['materie']], 0, 1, 'L', 0, '', 0);
					$this->MultiCell(0, 5, "Giudizio: ".$student['giudizi']['scritti'][$test['materie']], 0, 'L', false, 1, 15);
					$this->MultiCell(0, 5, "Valutazione: ".$student['voti']['scritti'][$test['materie']], 0, 'L', false, 1, 15);
				}
				else {
					list ($ita, $mat) = explode("#", $student['giudizi']['scritti'][$test['materie']]);
					$tot = $ita + $mat;
					$this->SetFont('helvetica', 'B', '12');
					$this->Cell(0, 5, "Prova nazionale INVALSI", 0, 1, 'L', 0, '', 0);
					$this->SetFont('helvetica', '', '11');
					$this->Cell(0, 5, "Italiano: ".$ita, 0, 1, 'L', 0, '', 0);
					$this->MultiCell(0, 5, "Matematica: ".$mat, 0, 'L', false, 1, 15);
					$this->MultiCell(0, 5, "Totale: ".$tot, 0, 'L', false, 1, 15);
					$this->MultiCell(0, 5, "Valutazione: ".$student['voti']['scritti'][$test['materie']], 0, 'L', false, 1, 15);
				}

				$this->MultiCell(30, 5, "", 0, 'L', false, 1, 15);
				$this->Ln();
			}
			/*
			 * colloquio
			 */
			$this->SetFont('helvetica', 'B', '12');
			$this->Cell(0, 5, "Colloquio pluridisciplinare", 0, 1, 'L', 0, '', 0);
			$this->SetFont('helvetica', '', '11');
			$this->MultiCell(0, 5, "Traccia del colloquio: ", 0, 'L', false, 1, 15);
			$this->SetFont('helvetica', 'I', '11');
			$this->MultiCell(0, 5, $student['tracce']['orale'], 0, 'L', false, 1, 15);
			$this->SetFont('helvetica', '', '11');
			$this->MultiCell(0, 5, "Valutazione: ".$student['voti']['orale'], 0, 'L', false, 1, 15);

			$this->Ln();
			$this->Ln();
			$voto = $student['finale']['voto'];
			if($student['voti']['lode'] == 1) {
				$voto .= " e lode";
			}
			$this->MultiCell(100, 15, "Valutazione proposta dalla sottocommissione:  ", 0, 'L', false, 0, 15);
			$this->SetFont('helvetica', 'B', '11');
			$this->MultiCell(30, 15, $voto, 0, 'L', false, 0, 100);

			$this->AddPage("P", "A4");
			$page++;
			$this->setPage($page, true);
			$this->SetFont('times', 'B', '15');
			$this->Cell(0, 14, "LA SOTTOCOMMISSIONE N. ".$comm, 0, 1, 'C', 0, '', 0);
			$this->Ln();
			$this->SetFont('helvetica', 'B', '11');
			foreach ($docenti as $docente) {
				$this->MultiCell(60, 10, $docente['cognome']." ".$docente['nome'], 0, 'L', false, 0, 15);
				$this->MultiCell(130, 10, '_____________________________________________', 0, 'L', false, 0, 65);
				$this->Ln();
			}
			$this->Ln();
			$this->Ln();
			$this->SetFont('helvetica', '', '11');

			$str = "La Commissione plenaria, visto il curriculum scolastico e le risultanze dell'esame, delibera che $art ".$student['cognome']." ".$student['nome']." avendo superato l'esame di Stato venga dichiarat{$fin} licenziat{$fin} con la valutazione di ".$voto;
			$this->MultiCell(0, 10, $str, 0, 'L', false, 0, 15);

			$page++;
		}
	}
}
