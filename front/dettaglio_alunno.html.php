<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Esami di stato: riepilogo alunno</title>
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../../font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="../../../css/general.css" type="text/css" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../../js/page.js"></script>
	<script type="text/javascript">
        $(function(){
            load_jalert();
            setOverlayEvent();
        });
	</script>
</head>
<body>
<?php include "../../../intranet/teachers/header.php" ?>
<?php include "../../../intranet/teachers/navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "menu_esami.php" ?>
	</div>
	<div id="left_col">
		<div style="width: 90%; margin: auto; display: flex; display: -webkit-flex; flex-wrap: wrap; -webkit-flex-wrap: wrap; justify-content: space-around; -webkit-justify-content: space-around" class="">
			<div style="order: 1; flex: 0 1 50%; -webkit-flex: 0 1 50%; font-size: 1.1em; " class="_center material_label _bold"><?php echo $al['cognome']." ".$al['nome'] ?></div>
			<div style="order: 2; flex: 0 1 50%; -webkit-flex: 0 1 50%; font-size: 1.1em; " class="_center ">Media voto:
                <span class="<?php if ($student['voti']['avg'] < 5.5) echo "attention _bold"; else if (ceil($student['voti']['avg']) < $student['voti']['ammissione']) echo "attention"; else echo "normal _bold" ?>">
                    <?php echo $student['voti']['avg'] ?>
                </span>
            </div>
        </div>
        <fieldset style="width: 80%; margin: auto">
            <legend style="font-size: 1em; font-weight: normal;" class="normal">Dati anagrafici</legend>
            <div class="dashboard_fieldset" style="margin-top: 0">
				<div style="order: 1; flex: 1 0 auto; -webkit-flex: 0 1 auto" class="dashboard_minicard">
					<p class="dashboard_card_title" style="background-color: #64ffda">Nata il</p>
					<p class="dashboard_card_body">
						<?php echo format_date($al['data_nascita'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>
					</p>
				</div>
				<div style="order: 2; flex: 1 0 auto; -webkit-flex: 0 1 auto" class="dashboard_minicard">
					<p class="dashboard_card_title" style="background-color: #64ffda">A</p>
					<p class="dashboard_card_body">
						<?php echo $al['luogo_nascita'] ?>
					</p>
				</div>
            </div>
        </fieldset>
        <fieldset style="width: 80%; margin: auto">
            <legend style="font-size: 1em; font-weight: normal;" class="normal">Presentazione</legend>
            <div class="dashboard_fieldset" style="margin-top: 0">
                <div style="order: 1" class="dashboard_minicard">
                    <p class="dashboard_card_title" style="background-color: #b388ff">Ammissione</p>
                    <p class="dashboard_card_body normal _bold" style="font-size: 1.5em">
                        <?php echo $student['voti']['ammissione'] ?>
                    </p>
                </div>
                <div style="order: 2" class="dashboard_minicard">
                    <p class="dashboard_card_title" style="background-color: #b388ff">
                        <a href="consiglio_orientativo.php" style="color: rgba(0, 0, 0, .84);">Consiglio orientativo</a>
                    </p>
                    <p class="dashboard_card_body">
						<?php if(isset($cons['consiglio'])) echo $cons['consiglio']; else echo "Non presente" ?>
                    </p>
                </div>
            </div>
        </fieldset>
        <fieldset style="width: 80%; margin: auto">
            <legend style="font-size: 1em; font-weight: normal;" class="normal">Esame</legend>
            <div class="dashboard_fieldset" style="margin-top: 0">
                <?php
				$order = 1;
				foreach ($_SESSION['tests'] as $id_test => $test) {
                    $exam_test = new ExamTest($id_test, $year, new MySQLDataLoader($db), null, $_SESSION['__classe__']->get_ID());
                    $st = $exam_test->getStudent($_REQUEST['aid']);
					$link = "valuta_scritto.php?sub=".$test['materie'];
                    switch ($test['materie']) {
                        case "3":
                            $str = "Italiano";
                            break;
                        case "16":
                            $str = "Matematica";
                            break;
                        case "10":
                            $str = "Lingua inglese";
                            break;
                        case "11":
                            $str = "Lingua francese";
                            break;
                        default:
                            $str = 'Invalsi';
                            $link = "invalsi.php";
                            break;
                    }
				?>
                <div style="order: <?php echo $order ?>;" class="dashboard_card">
                    <p class="dashboard_card_title" style="margin-bottom: 0; background-color: #40c4ff">
                        <a href="<?php echo $link ?>" style="color: rgba(0, 0, 0, .84)"><?php echo $str ?></a>
                    </p>
                    <p class="dashboard_card_row normal _bold" style="font-size: 1.2em;">
						<?php echo $student['voti']['scritti'][$test['materie']] ?>
                    </p>
					<?php
					if ($str != "Invalsi") {
						?>
                        <p class="dashboard_card_row normal">
                            <?php if($str == "Matematica") : ?>
                            Quesiti svolti:
                            <?php else : ?>
                            Traccia n.
                            <?php endif; ?>
                            <?php echo $st['scelta']; ?>
                        </p>
                        <p class="dashboard_card_body normal" style="font-size: 0.88em">
                            <?php echo $st['giudizio']; ?>
                        </p>
						<?php
					} else {
						$exam_test = new NationalTest($id_test, $year, new MySQLDataLoader($db), null, $_SESSION['__classe__']->get_ID());
						$st = $exam_test->getStudent($_REQUEST['aid']);
						$total = $st['ita'] + $st['mat'];
					?>
                    <div class="dashboard_card_row normal" style="font-size: 0.88em; display: flex">
                        <p style="order: 1; flex: 0 1 33%">ITA</p>
                        <p style="order: 2; flex: 0 1 33%">MAT</p>
                        <p style="order: 3; flex: 0 1 33%" class="_bold">TOT</p>
                    </div>
                    <div class="dashboard_card_row normal" style="display: flex">
                        <p style="order: 1; flex: 0 1 33%"><?php echo $st['ita'] ?></p>
                        <p style="order: 2; flex: 0 1 33%"><?php echo $st['mat'] ?></p>
                        <p style="order: 3; flex: 0 1 33%" class="_bold"><?php echo $total ?></p>
                    </div>
					<?php
					}
                        ?>
                    </div>
				    <?php
					$order++;
				}
                ?>
                <div style="order: <?php echo $order ?>" class="dashboard_card">
                    <p class="dashboard_card_title" style="background-color: #40c4ff">Colloquio</p>
                    <p class="dashboard_card_body normal _bold" style="font-size: 1.5em">
						<?php echo $student['voti']['orale'] ?>
                    </p>
                </div>
            </div>
        </fieldset>
	</div>
	<p class="spacer"></p>
</div>
<?php include "../../../intranet/teachers/footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link"><a href="../../../intranet/teachers/index.php"><img src="../../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="../../../intranet/teachers/profile.php"><img src="../../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<div class="drawer_link"><a href="../../../modules/documents/load_module.php?module=docs&area=<?php echo $_SESSION['__area__'] ?>"><img src="../../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
		<?php if(is_installed("com")){ ?>
			<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=<?php echo $_SESSION['__area__'] ?>"><img src="../../../images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
		<?php } ?>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../../shared/do_logout.php"><img src="../../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
