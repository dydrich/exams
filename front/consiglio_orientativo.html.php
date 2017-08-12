<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Esami di stato: giudizio orientativo</title>
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

            $('.sel').on('change', function(event) {
                _stid = $(this).data('st');
                val = $(this).val();
                save_cons(_stid, val);
            });
        });

        var save_cons = function(stid, value){
            $.ajax({
                type: "POST",
                url: 'registra_consiglio.php',
                data: {action: "reg", stid: stid, value: value},
                dataType: 'json',
                error: function() {
                    j_alert("error", "Errore di trasmissione dei dati");
                },
                succes: function() {

                },
                complete: function(data){
                    r = data.responseText;
                    if(r == "null"){
                        return false;
                    }
                    var json = $.parseJSON(r);
                    if (json.status == "kosql"){
                        j_alert("error", json.message);
                        console.log(json.dbg_message);
                    }
                    else if(json.status == "ko") {
                        j_alert("error", "Impossibile completare l'operazione richiesta. Riprovare tra qualche secondo o segnalare l'errore al webmaster");
                        return;
                    }
                    else {

                    }
                }
            });
        };
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
				<td style="width: 40%" class="_bold normal">Studente</td>
				<td style="width: 60%" class="_bold normal _center">Consiglio orientativo</td>
			</tr>
			<?php
			foreach ($students as $k => $student) {
				?>
				<tr class="bottom_decoration">
					<td style="width: 40%" class="">
						<a href="dettaglio_alunno.php?aid=<?php echo $k ?>">
							<?php echo $student['cognome']." ".$student['nome'] ?>
						</a>
					</td>
					<td style="width: 60%" class=" _center">
						<select id="cons<?php echo $k ?>" name="cons<?php echo $k ?>" class="sel" data-st="<?php echo $k ?>" style="width: 90%;">
							<option value="0">.</option>
							<?php
                            $r_cons->data_seek(0);
							while ($r = $r_cons->fetch_assoc()) {
							?>
							<option value="<?php echo $r['id_giudizio'] ?>" <?php if(isset($cons[$k]['id']) && $r['id_giudizio'] == $cons[$k]['id']) echo "selected" ?>><?php echo $r['giudizio'] ?></option>
							<?php
							}
							?>
						</select>
					</td>
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
