<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Gestione giudizi orientativi</title>
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
        var _id = 0;
        $(function(){
            load_jalert();
            setOverlayEvent();

            $('#save_btn').on('click', function(event) {
				var value = $('#new_j').val();
				save_data(0, value, 'insert');
			});

            $('.edit').editable('registra_consiglio.php', {
                indicator : 'Saving...',
                tooltip   : 'Click to edit...'
            });

            $('.del_ist').on('click', function(event) {
                event.preventDefault();
                _id = $(this).data('id');
                j_alert("confirm", "Eliminare l'istituto?");
			});

            $('#okbutton').on('click', function (event) {
                event.preventDefault();
                delete_institute(_id);
            });
        });

        var save_data = function(id, value, action){
            $.ajax({
                type: "POST",
                url: 'registra_consiglio.php',
                data: {action: action, id: id, value: value},
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
                    else if (action == "insert") {
                        j_alert("alert", json.message);
                        window.setTimeout(function(){
                            document.location.href = 'consigli_orientativi.php';
                        }, 2000);
                    }
                }
            });
        };

        var delete_institute = function(id){
            $('#confirm').fadeOut(10);
            $('#overlay').fadeOut(10);
            $.ajax({
                type: "POST",
                url: 'registra_giudizio.php',
                data: {action: 'delete', id: id},
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
                        $('#row'+id).hide(400);
                    }
                }
            });
        };
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
		<div style="margin-left: 40px; margin-top: 25px">
			<label for="new_j"></label>
			<input type="text" id="new_j" name="new_j" style="width: 250px; margin-right: 15px" />
			<a href="#" id="save_btn" class="material_link">Inserisci</a>
		</div>
		<p style="margin-left: 50px; margin-top: 20px" class="_bold">
			Istituti presenti
		</p>
		<table style="width: 65%; margin-left: 50px">
			<?php
			while ($row = $res_g->fetch_assoc()) {
				?>
				<tr style="height: 25px" class="bottom_decoration" id="row<?php echo $row['id_giudizio'] ?>">
					<td style="width: 80%; text-align: left">
						<p id="<?php print $row['id_giudizio'] ?>" class="edit" style="margin: 0"><?php echo $row['giudizio'] ?></p>
					</td>
					<td style="width: 20%; text-align: center">
						<a href="#" class="del_ist" data-id="<?php echo $row['id_giudizio'] ?>">
							<i class="fa fa-trash normal" style="font-size: 1.1em"></i>
						</a>
					</td>
				</tr>
				<?php
			}
			?>
		</table>
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
