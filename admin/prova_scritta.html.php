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
    <script type="text/javascript" src="../../../js/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="../../../js/page.js"></script>
	<script type="text/javascript">
        $(function(){
            load_jalert();
            setOverlayEvent();

            $('#subjects').buttonset();

            $('#test_date').datetimepicker({
				altField: "#test_time",
                dateFormat: "dd/mm/yy",
                altFieldTimeOnly: true,
                altTimeFormat: "HH:mm",
                currentText: "Ora",
                closeText: "Chiudi"
			});

            $('#save_form').on('click', function (event) {
                event.preventDefault();
                save_data();
            });

            var save_data = function () {
                $.ajax({
                    type: "POST",
                    url: "test_manager.php",
                    data: $('#my_form').serialize(),
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
                                document.location.href = "prove.php";
                            }, 2000);
                        }
                    }
                });
            };
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
		<form id="my_form" method="post" action="" style="margin-top: 30px; text-align: left; width: 80%; margin-left: auto; margin-right: auto">
			<table style="width: 90%; margin-left: auto; margin-right: auto; margin-top: 30px; margin-bottom: 5px">
                <tr style="height: 30px">
                    <td style="width: 20%"><label for="test">Prova</label></td>
                    <td style="width: 80%">
                        <input type="text" name="test" id="test" style="width: 85%" value="<?php if(isset($wt)) echo $wt->getDescription() ?>" />
                    </td>
                </tr>
				<tr>
					<td style="width: 20%">Materie</td>
					<td style="width: 80%; font-size: 0.7em" id="subjects">
						<?php
						while ($row = $res_subjs->fetch_assoc()) {
						?>
						<input type="checkbox" name="materie[]" id="subj<?php echo $row['id_materia'] ?>" <?php if(isset($wt) && in_array($row['id_materia'], $wt->getSubjects())) echo "checked" ?> value="<?php echo $row['id_materia'] ?>"><label for="subj<?php echo $row['id_materia'] ?>"><?php echo $row['materia'] ?></label>
						<?php
						}
						?>
                        <input type="checkbox" name="materie[]" id="subj0" value="0" <?php if(isset($wt) && implode(",", $wt->getSubjects()) == "3,16") echo "checked" ?>><label for="subj0">Invalsi</label>
					</td>
				</tr>
				<tr style="height: 30px">
					<td style="width: 20%">
						<label for="test_date">Data prova</label>
					</td>
					<td style="width: 80%">
						<input type="text" style="width: 250px" id="test_date" name="test_date" value="<?php if (isset($wt)) echo format_date(substr($wt->getDatetime(), 0, 10), SQL_DATE_STYLE, IT_DATE_STYLE, "/"); ?>" />
						<label for="test_time" style="margin-left: 20px">Ora</label>
						<input type="text" style="width: 50px; margin-left: 20px" id="test_time" name="test_time" value="<?php if (isset($wt)) echo substr($wt->getDatetime(), 11, 5) ?>"/>
					</td>
				</tr>
				<tr>
					<td style="width: 20%">
						<label for="duration">Durata (in minuti)</label>
					</td>
					<td style="width: 80%">
						<input type="text" style="width: 250px" id="duration" name="duration" value="<?php if(isset($wt)) echo ($wt->getDuration()->getTime()) / 60 ?>" />
					</td>
				</tr>
				<tr>
					<td colspan="2">
                        <input type="hidden" name="action" id="action" value="<?php echo $action ?>" />
                        <input type="hidden" name="idp" id="idp" value="<?php echo $_REQUEST['idp'] ?>" />
                    </td>
				</tr>
				<tr>
					<td colspan="2" style="text-align: right; margin-right: 50px; padding-top: 20px">
						<a href="#" id="save_form" class="material_link">Registra</a>
					</td>
				</tr>
			</table>
		</form>
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
