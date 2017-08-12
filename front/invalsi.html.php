<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>Esami di stato: valutazione scritto</title>
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

            $('.reg_grade').on('change', function (event) {
                event.preventDefault();
                std = $(this).data('std');
                grade = $(this).val();
                register_grade(std, '', grade)
            });

            var register_grade = function (std, sub, grade) {
                $.ajax({
                    type: "POST",
                    url: "test_manager.php",
                    data: {action: "register_grade", std: std, sub: sub, grade: grade},
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
                                if (sub == '') {
                                    document.location.href = "invalsi.php";
								}
								else {
                                    document.location.href = "valuta_scritto.php?sub="+sub;
								}
                            }, 500);
                        }
                    }
                });
            };

            $('.mat').on('blur', function (event) {
                event.preventDefault();
                stud = $(this).data('std');
                _ita = $('#ita'+stud).val();
                _mat = $(this).val();
                if (_mat != '' && _ita != '') {
                    register_invalsi('register_invalsi', stud, _ita, _mat)
                }
                update_total(stud);
            }).on('change', function (event) {
                event.preventDefault();
                stud = $(this).data('std');
                _ita = $('#ita'+stud).val();
                _mat = $(this).val();
                if (_mat != '' && _ita != '') {
                    register_invalsi('register_invalsi', stud, _ita, _mat)
                }
                update_total(stud);
            });

            $('.ita').on('change', function (event) {
                event.preventDefault();
                stud = $(this).data('std');
                _mat = $('#mat'+stud).val();
                _ita = $(this).val();
                if (_mat != '' && _ita != '') {
                    register_invalsi('register_invalsi', stud, _ita, _mat)
                }
                update_total(stud);
            });

            var register_invalsi = function (action, stud, ita, mat) {
                $.ajax({
                    type: "POST",
                    url: "test_manager.php",
                    data: {action: action, std: stud, ita: ita, mat: mat},
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

            var update_total = function (std) {
                v_ita = $('#ita'+stud).val();
                v1 = (v_ita != '') ? parseInt(v_ita) : 0;
                v_mat = $('#mat'+stud).val();
                v2 = (v_mat != '') ? parseInt(v_mat) : 0;
                total = v1 + v2;
                $('#tot'+std).text(total);
            };
        });
	</script>
    <style>
        TR {
            height: 25px;
        }
    </style>
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
				<td style="width: 20%" class="_bold normal _center">Voto</td>
				<td style="width: 20%" class="_bold normal _center">ITA</td>
				<td style="width: 20%" class="_bold normal _center">MAT</td>
                <td style="width: 10%" class="_bold normal _center">TOT</td>
			</tr>
			<?php
			foreach ($studenti as $k => $studente) {
				$student = $exam_test->getStudent($k);
				$total = $student['ita'] + $student['mat'];
				?>
				<tr class="bottom_decoration">
					<td style="width: 40%"><?php echo $studente['cognome']." ".$studente['nome'] ?></td>
					<td style="width: 20%" class="_center">
						<select id="voto<?php echo $k ?>" name="voto<?php echo $k ?>" data-std="<?php echo $k ?>" style="width: 80%" class="reg_grade">
							<option value="0">.</option>
							<?php
							for ($i = 10; $i > 0; $i--) {
								?>
								<option value="<?php echo $i ?>" <?php if ($studente['voti']['scritti']['3#16'] == $i) print "selected" ?>><?php echo $i ?></option>
								<?php
							}
							?>
						</select>
					</td>
					<td style="width: 20%" class="_center">
                        <input type="number" min="0" max="50" id="ita<?php echo $k ?>" name="ita<?php echo $k ?>" data-std="<?php echo $k ?>" value="<?php if($student['ita'] > 0) echo $student['ita']; else echo '' ?>" class="ita" style="width: 80%" />
                    </td>
					<td style="width: 20%" class="_center">
                        <input type="number" min="0" max="50" id="mat<?php echo $k ?>" name="mat<?php echo $k ?>" data-std="<?php echo $k ?>" value="<?php if($student['mat'] > 0) echo $student['mat']; else echo '' ?>" class="mat" style="width: 80%" />
                    </td>
                    <td style="width: 20%" class="_center"><span id="tot<?php echo $k ?>" class="_bold"><?php echo $total ?></span></td>
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
