<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>::Esami di stato: sottocommissione</title>
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
        var user = 0;
        var subs = 0;

        $(function(){
            load_jalert();
            setOverlayEvent();

            $('.chg_link').on('click', function (event) {
                event.preventDefault();
                user = $(this).data('id');
                var off = $(this).parent().parent().offset();
                show_div(event, off);
            });

            $('.del_link').on('click', function (event) {
                user = $(this).data('id');
                event.preventDefault();
                registra('del_sub');
            });

            $("#subs").autocomplete({
                source: "../../../shared/get_users.php?group=teachers&ord=1",
                minLength: 2,
                select: function(event, ui){
                    subs = ui.item.uid;
                    registra('substitute');
                    $('#get_user').slideUp(500);
                }
            });
        });

        var show_div = function(e, off){
            if ($('#get_user').is(":visible")) {
                $('#get_user').slideUp(500);
                return;
            }
            off.top += 20;
            off.left -= 10;
            $('#get_user').css({top: off.top+"px", left: off.left+"px"}).slideDown(500);
        };

        var registra = function(action){
            $.ajax({
                type: "POST",
                url: 'gestione_commissioni.php',
                data: {action: action, user: user, sub: subs, comm: <?php echo $id_comm ?>},
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
                    if (json.status === "kosql"){
                        alert(json.message);
                        console.log(json.dbg_message);
                    }
                    else if(json.status === "ko") {
                        j_alert("error", "Impossibile completare l'operazione richiesta. Riprovare tra qualche secondo o segnalare l'errore al webmaster");
                        return;
                    }
                    else {
                        j_alert("alert", json.message);
                        setTimeout(function () {
                            location.reload(true);
                        }, 2000);
                    }
                }
            });
        }

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
		<div class="card_container" style="margin-top: 20px">
			<?php
			foreach ($docenti as $k => $teacher) {
				if ($teacher['materie'] == '') {
					$teacher['materie'] = 'Sostegno';
				}
				?>
				<div class="minicard" style="width: 46%; margin-right: 1%">
					<div class="card_title">
						<?php
                        if (count($teacher['sub']) < 1) {
							echo $teacher['cognome'] . " " . $teacher['nome'];
						}
						else {
							echo $teacher['sub']['cognome'] . " " . $teacher['sub']['nome']." (".$teacher['cognome'] . " " . $teacher['nome'].")";
                        }
                        ?>
                        <div style="float: right; margin-right: 20px; color: #1E4389">
                <?php if (count($teacher['sub']) < 1): ?>
                            <a href="#" class="normal chg_link" data-id="<?php echo $k ?>">
                                <i class="fa fa-exchange "></i>
                            </a>
                <?php else: ?>
                            <a href="#" class="normal del_link" data-id="<?php echo $k ?>">
                                <i class="fa fa-trash "></i>
                            </a>
                <?php endif; ?>
                        </div>
					</div>
					<div class="card_varcontent">
						<span style="font-size: 0.9em"><?php echo $teacher['materie'] ?></span>
					</div>
				</div>
				<?php
			}
			?>
		</div>
	</div>
	<p class="spacer" style="clear:both;"></p>
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
<div id="get_user" style="display: none; padding: 10px; background-color: white; width: 200px; height: 75px; border: 1px solid #03a9f4; border-radius: 3px; position: fixed; box-shadow: rgba(0, 0, 0, 0.156863) 0 2px 5px 0, rgba(0, 0, 0, 0.117647) 0 2px 10px 0; z-index: 7;">
    <label for="subs" class="normal">Sostituto</label>
    <input type="text" name="subs" id="subs" style="width: 150px" />
</div>
</body>
</html>
