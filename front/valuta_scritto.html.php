<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Esami di stato: valutazione scritto</title>
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../../font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="../../../css/general.css" type="text/css" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
    <script type="text/javascript" src="../../../js/jquery.jeditable.mini.js"></script>
	<script type="text/javascript" src="../../../js/page.js"></script>
	<script type="text/javascript">
        $(function(){
            load_jalert();
            setOverlayEvent();

            $('.reg_grade').on('change', function (event) {
				event.preventDefault();
				std = $(this).data('std');
				sub = <?php echo $_REQUEST['sub'] ?>;
				grade = $(this).val();
                register_value('register_grade', std, sub, grade)
            });

            $('.reg_choice').on('change', function (event) {
                event.preventDefault();
                std = $(this).data('std');
                choice = $(this).val();
                if (choice != '') {
                    register_value('register_choice', std, '', choice)
                }
            });

            $('#download_judgs').on('click', function (event) {
                download_judgments();
            });

            $('.edit').editable('upd_student_j.php', {
                indicator : 'Saving...',
                tooltip   : 'Click to edit...'
            });
        });

        var register_value = function (action, std, sub, val) {
            $.ajax({
                type: "POST",
                url: "test_manager.php",
                data: {action: action, std: std, sub: sub, value: val},
                dataType: 'json',
                error: function(data, status, errore) {
                    j_alert("error", "Si è verificato un errore di rete");
                    return false;
                },
                succes: function(result) {

                },
                complete: function(data, status){
                    r = data.responseText;
                    var json = $.parseJSON(r);
                    if(json.status === "kosql"){
                        j_alert("error", "Errore SQL. \nQuery: "+json.query+"\nErrore: "+json.message);
                    }
                    else {
                        //j_alert("alert", json.message);
                        //window.setTimeout(function () {
                        //    document.location.href = "valuta_scritto.php?sub="+sub;
                        //}, 500);
                    }
                }
            });
        };

        var download_judgments = function () {
            $.ajax({
                type: "POST",
                url: "test_manager.php",
                data: {action: 'download_judgments'},
                dataType: 'json',
                error: function(data, status, errore) {
                    j_alert("error", "Si è verificato un errore di rete");
                    return false;
                },
                succes: function(result) {

                },
                complete: function(data, status){
                    r = data.responseText;
                    var json = $.parseJSON(r);
                    if(json.status === "kosql"){
                        j_alert("error", "Errore SQL. \nQuery: "+json.query+"\nErrore: "+json.message);
                    }
                    else {
                        j_alert("alert", json.message);
                        window.setTimeout(function () {
                            document.location.href = "valuta_scritto.php?sub=<?php echo $_REQUEST['sub'] ?>";
                        }, 500);
                    }
                }
            });
        };
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
		<table style="width: 90%; margin: auto">
			<tr class="accent_decoration">
				<td style="width: 30%" class="_bold normal">Studente</td>
				<td style="width: 10%" class="_bold normal _center">Voto</td>
                <td style="width: 10%" class="_bold normal _center"><?php if ($_REQUEST['sub'] != 16) echo "Traccia"; else echo "Quesiti"; ?></td>
				<td style="width: 50%" class="_bold normal _center">
                    Giudizio
                    <?php
                    if ($judg_type == 1) {
                    ?>
                    <a href="#" id="download_judgs" title="Click per inserimento automatico">
                        <i class="fa fa-download"></i>
                    </a>
                    <?php
                    }
                    ?>
                </td>
			</tr>
			<?php
			foreach ($studenti as $k => $studente) {
			    $student = $exam_test->getStudent($k);
				?>
				<tr class="bottom_decoration">
					<td style="width: 30%">
                        <a href="dettaglio_alunno.php?aid=<?php echo $k ?>" class=""><?php echo $studente['cognome']." ".$studente['nome'] ?></a>
                    </td>
					<td style="width: 10%" class="_center">
						<select id="voto<?php echo $k ?>" name="voto<?php echo $k ?>" data-std="<?php echo $k ?>" style="width: 80%" class="reg_grade">
							<option value="0">.</option>
							<?php
							for ($i = 10; $i > 0; $i--) {
								?>
								<option value="<?php echo $i ?>" <?php if ($studente['voti']['scritti'][$_REQUEST['sub']] == $i) print "selected" ?>><?php echo $i ?></option>
								<?php
							}
							?>
						</select>
					</td>
                    <td style="width: 10%" class="_center">
                        <input type="number" id="choice<?php echo $k ?>" name="choice<?php echo $k ?>" value="<?php echo $student['scelta']; ?>" data-std="<?php echo $k ?>" style="width: 80%" class="reg_choice" />
                    </td>
					<td style="width: 50%" class="_center edit" id="<?php echo $k ?>"><?php echo $student['giudizio'] ?></td>
				</tr>
				<?php
			}
			?>
		</table>
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
