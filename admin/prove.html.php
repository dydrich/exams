<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>Prove</title>
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
<?php include "../../../intranet/manager/header.php" ?>
<?php include "../../../intranet/manager/".$_SESSION['__administration_group__']."/navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "menu_esami.php" ?>
	</div>
	<div id="left_col">
		<div id="prove_scritte" style="width: 90%; margin: auto">
			<p class="_bold normal bottom_decoration" style="font-size: 1.2em">Prove scritte</p>
			<?php
			if ($res_prove->num_rows < 1) {
				echo "<p class='_center _bold _normal'>Nessuna prova inserita</p>";
			}
			else {
			    while ($row = $res_prove->fetch_assoc()) {
			setlocale(LC_TIME, "it_IT.utf8");
			$rbt = new RBTime(0, 0, 0);
			$rbt->setTime($row['durata'] * 60);
			$wt = new WrittenTest($row['id_prova'], $row['prova'], $row['materie'], $row['data'], $row['anno'], $rbt, new MySQLDataLoader($db));
			$wt->loadWorkshift();
			$workshift = $wt->getWorkshift();
			$day = ucfirst(strftime("%A %d %B %R", strtotime($row['data'])));
			?>
            <div class="card_container" style="margin-top: 20px">
                <div class="card">
                    <div class="card_title">
                        <a href="prova_scritta.php?idp=<?php echo $row['id_prova'] ?>" class="normal"><?php echo $wt->getDescription() ?></a>
                        <div class="fright" style="margin-right: 40px">
							<?php echo $day ?>
                        </div>
                    </div>
                    <div class="card_varcontent">
                        <a href="turni_scritti.php?idp=<?php echo $row['id_prova'] ?>" class="_bold normal" style="margin-top: 15px">Turni di assistenza:</a>
                        <ul>
							<?php
                            $res_comm->data_seek(0);
							while ($r = $res_comm->fetch_assoc()) {
							    $t = [];
							    foreach ($workshift[$r['id_commissione']]['teachers'] as $k => $teachers) {
                                    $t[] = $teachers['teacher'];
                                }
								?>
                            <li>Commissione n. <?php echo $r['numero'] ?>, classe 3<?php echo $r['sezione'] ?>: <?php echo implode(", ", $t) ?></li>
								<?php
							}
							$t = [];
							foreach ($workshift['0']['teachers'] as $k => $teachers) {
								$t[] = $teachers['teacher'];
							}
							?>
                            <li>DSA: <?php echo implode(", ", $t) ?></li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
				}
			}
			?>
			<p>
				<a href="prova_scritta.php?idp=0" class="material_link">Inserisci nuova prova</a>
			</p>
		</div>
	</div>
	<p class="spacer"></p>
</div>
<?php include "../../../intranet/manager/footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link"><a href="../../../intranet/manager/index.php"><img src="../../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="../../../intranet/manager/profile.php"><img src="../../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<div class="drawer_link"><a href="../../../modules/documents/load_module.php?module=docs&area=<?php echo $_SESSION['__area__'] ?>"><img src="../../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
		<?php if(is_installed("com")){ ?>
			<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=<?php echo $_SESSION['__area__'] ?>"><img src="../../../images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
		<?php } ?>
		<div class="drawer_link"><a href="../../../intranet/manager/utility.php"><img src="../../../images/59.png" style="margin-right: 10px; position: relative; top: 5%" />Utility</a></div>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../../shared/do_logout.php"><img src="../../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
