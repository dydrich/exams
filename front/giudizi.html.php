<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Gestione giudizi</title>
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

            $('.choice').on('click', function (event) {
                type = $(this).data('type');
                tipo = '';
                if (type === 1) {
                    tipo = 'fissi';
                }
                else {
                    tipo = 'variabili';
                }
                msg = "Selezionati i giudizi "+tipo+". Per modificare la scelta, rientra in questa pagina e seleziona l'icona del tipo scelto.";
                register(type, msg);

            });

            var register = function (type, msg) {
                $.ajax({
                    type: "POST",
                    url: "tipo_giudizio.php",
                    data: {type: type, mat: <?php echo $_REQUEST['sub'] ?>},
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
                            j_alert("alert", msg);
                            link = '';
                            if (type === 1) {
                                link = 'giudizi_fissi.php?sub=<?php echo $_REQUEST['sub'] ?>';
                            }
                            else {
                                link = 'giudizi_variabili.php?sub=<?php echo $_REQUEST['sub'] ?>';
                            }
                            window.setTimeout(function () {
                                document.location.href = link;
                            }, 2000);
                        }
                    }
                });
            };
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
		<div class="welcome">
			<p id="w_head">Tipologia giudizi</p>
			<p class="w_text" style="width: 350px"></p>
			<table style="width: 50%">
				<tr>
					<td style="width: 50%" class="_center">
						<a href="#" data-type="1" class="choice">
							<div class="icon_button" style="background-color: #bf360c">
								<i class="fa fa-file-text" style="color: white"></i>
							</div>
							<p style="font-size: 12px; margin-top: 5px; width: 100%" class="normal _center">Giudizi fissi per voto</p>
						</a>
					</td>
					<td style="width: 50%">
						<a href="#" data-type="2" class="choice">
							<div class="icon_button" style="background-color: #0d47a1">
								<i class="fa fa-magic" style="color: white"></i>
							</div>
							<p style="font-size: 12px; margin-top: 5px; width: 100%" class="normal _center">Giudizi variabili con scelta</p>
						</a>
					</td>
				</tr>
			</table>
		</div>
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
