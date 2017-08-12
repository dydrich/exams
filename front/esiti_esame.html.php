<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Registro personale: esiti esame conclusivo primo ciclo</title>
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
		$(function() {
            load_jalert();
            setOverlayEvent();
            $('#overlay').click(function (event) {
                if ($('#overlay').is(':visible')) {
                    show_drawer(event);
                }
                $('#other_drawer').hide();
            });
            $('#showsub').click(function (event) {
                var off = $(this).parent().offset();
                _show(event, off);
            });
            $('#imglink').click(function (event) {
                event.preventDefault();
                show_menu('imglink');
            });

            $('#upload_data').on('click', function (event) {
                upload_exam_data();
            });

        });

		var upload_exam_data = function () {
            $.ajax({
                type: "POST",
                url: "registra_dati_esame.php",
                data: {},
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
<div id="main" style="clear: both; ">
    <div id="right_col">
		<?php include "menu_esami.php" ?>
    </div>
    <div id="left_col">
        <div style="top: 75px; margin-left: 565px; margin-bottom: 10px; position: absolute" class="rb_button">
            <a href="pdf_esiti_esame.php" id="pdf_data">
                <i class="fa fa-file-pdf-o" style="padding: 8px 0 0 11px; font-size: 1.5em;"></i>
            </a>
        </div>
        <div style="top: 75px; margin-left: 625px; margin-bottom: 10px; position: absolute" class="rb_button">
            <a href="#" id="upload_data">
                <i class="fa fa-upload" style="padding: 8px 0 0 11px; font-size: 1.5em;"></i>
            </a>
        </div>
        <table style="width: 90%; margin: 20px auto">
            <tbody>
            <?php
            foreach ($students as $k => $student) {
                $positivo = "";
                $string_esito = "";
                    if ($student['finale']['esito'] != "") {
                        $positivo = $esiti_possibili[$student['finale']['esito']]['positivo'];
                        $string_esito = $esiti_possibili[$student['finale']['esito']]['esito'];
                    }
                ?>
                <tr class="bottom_decoration <?php if ($positivo == '0') echo "attention" ?>">
                    <td style="width: 20%; padding-left: 8px">
                        <a href="dettaglio_alunno.php?aid=<?php echo $k ?>" class="normal"><?php echo $student['cognome'] . " " . $student['nome'] ?></a>
                    </td>
                    <td style="width: 40%" class="_center _bold">
                        <?php echo $string_esito ?>
                    </td>
                    <td style="width: 20%" class="_center _bold">
                        <?php echo $student['finale']['voto']; if($student['voti']['lode'] == 1) echo " e lode"; ?>
                    </td>
                </tr>
            <?php
            }
            ?>
            </tbody>
            <tfoot>
            </tfoot>
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