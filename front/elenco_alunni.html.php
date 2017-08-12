<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>Esami di stato: elenco alunni</title>
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

            $('.addlaude').on('click', function (event) {
                _stid = $(this).data('id');
                _laud = $(this).prop('checked');
                register_laude(_stid, _laud);
            });
        });

        var register_laude = function (stid, laude) {
            _l = 1;
            if (!laude) {
                _l = 0;
            }
            $.ajax({
                type: "POST",
                url: "registra_dati_esame.php",
                data: {action: 'laude', stid: stid, laude: _l},
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
                    }
                }
            });
        };
	</script>
    <style>
        tr {
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
        <div style="top: 75px; margin-left: 565px; margin-bottom: 10px; position: absolute" class="rb_button">
            <a href="pdf_schede_personali.php" id="pdf_data">
                <i class="fa fa-file-pdf-o" style="padding: 8px 0 0 11px; font-size: 1.5em;"></i>
            </a>
        </div>
		<?php
		$number_of_column = count($_SESSION['tests']) + 5;
		$col_width = 60 / ($number_of_column - 1);
		?>
		<table style="width: 90%; margin: auto">
			<tr class="accent_decoration">
				<td style="width: 30%" class="_bold normal">Studente</td>
				<td style="width: <?php echo $col_width?>%" class="_bold normal _center">AMM</td>
				<?php
				foreach ($_SESSION['tests'] as $test) {
                    switch ($test['materie']) {
                        case "3":
                            $str = "ITA";
                            break;
                        case "16":
                            $str = "MAT";
                            break;
                        case "10":
                            $str = "ING";
                            break;
                        case "11":
                            $str = "FRA";
                            break;
                        default:
                            $str = 'INV';
                            break;
                    }
				?>
                <td style="width: <?php echo $col_width?>%" class="_bold normal _center"><?php echo $str ?></td>
                <?php
                }
                ?>
                <td style="width: <?php echo $col_width?>%" class="_bold normal _center">ORA</td>
                <td style="width: <?php echo $col_width?>%" class="_bold normal _center">MED</td>
                <td style="width: <?php echo $col_width?>%" class="_bold normal _center">LOD</td>
			</tr>
            <?php
            foreach ($studenti as $k => $studente) {
                $limit = $studente['voti']['ammissione'] - 0.5;
				?>
            <tr class="bottom_decoration">
                <td style="width: 30%" class="">
                    <a href="dettaglio_alunno.php?aid=<?php echo $k ?>">
                        <?php echo $studente['cognome']." ".$studente['nome'] ?>
                    </a>
                </td>
                <td style="width: <?php echo $col_width?>%" class=" _center"><?php echo $studente['voti']['ammissione'] ?></td>
				<?php
				foreach ($studente['voti']['scritti'] as $item) {
                ?>
                <td style="width: <?php echo $col_width?>%" class=" _center"><?php echo $item ?></td>
            <?php
                }
                ?>
                <td style="width: <?php echo $col_width?>%" class=" _center"><?php echo $studente['voti']['orale'] ?></td>
                <td style="width: <?php echo $col_width?>%" class="<?php if($studente['voti']['avg'] < $limit) echo 'attention' ?> _bold _center"><?php echo $studente['voti']['avg'] ?></td>
                <td style="width: <?php echo $col_width?>%" class=" _center">
					<?php
                    if($studente['voti']['avg'] > 9.49) {
                    ?>
                    <input type="checkbox" data-id="<?php echo $k ?>" id="laude<?php echo $k ?>" name="laude<?php echo $k ?>" value="1" <?php if($studente['voti']['lode'] == 1) echo 'checked' ?> class="addlaude" />
                    <?php
					}
					?>
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
