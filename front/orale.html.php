<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Esami di stato: orali</title>
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

            $('#buttonset').buttonset();

            $('#sendbutton').on('click', function (event) {
                event.preventDefault();
                check_data();
            });
        });

        var check_data = function () {
            var trace = $('#log').val();
            var grade = $("input[name=grade]:checked").val();
            if (trace === '' || grade === undefined) {
                j_alert("error", "Traccia e voto sono campi obbligatori");
                return false;
            }
            var action = "<?php echo $action ?>";
            $.ajax({
                type: "POST",
                url: "gestione_orale.php",
                data: {action: action, trace: trace, grade: grade, stid: <?php echo $_REQUEST['aid'] ?>},
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
</head>
<body>
<?php include "../../../intranet/teachers/header.php" ?>
<?php include "../../../intranet/teachers/navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "menu_esami.php" ?>
	</div>
	<div id="left_col">
		<div style="display: flex; width: 90%; margin: auto; align-items: center; flex-wrap: wrap">
			<div style="width: 20%; order: 1">
				<label for="log" style="order: 1">Traccia colloquio</label>
			</div>
			<textarea id="log" name="log" style="width: 75%; height: 75px; margin-left: 20px; order: 2;"><?php if(isset($data)) echo $data['giudizio'] ?></textarea>
			<div style="width: 20%; order: 3; margin-top: 20px">
				Voto finale
			</div>
			<div id="buttonset" style="width: 75%; order: 4; margin-left: 20px; margin-top: 20px">
				<?php
				for($i = 4; $i < 11; $i++){
				?>
				<input name="grade" id="g<?php echo $i ?>" type="radio" value="<?php echo $i ?>" <?php if($i == $student['voti']['orale']) echo "checked" ?> />
				<label for="g<?php echo $i ?>" style="width: 65px"><?php echo $i ?></label>
				<?php
				}
				?>
			</div>
		</div>
        <div style="width: 90%; margin: 40px auto 0 auto; text-align: right; padding-right: 40px">
            <a href="#" id="sendbutton" class="material_link">Invia</a>
        </div>
        <?php
		$previous = get_sibling($students, $_REQUEST['aid'], PREVIOUS);
		$next = get_sibling($students, $_REQUEST['aid'], NEXT);
		if($previous == INDEX_OUT_OF_BOUND){
			$link_p = "#";
			$text_p = "";
		}
		else{
			$link_p = "orale.php?aid=".$previous['id'];
			$text_p = $previous['value'];
		}
		if($next == INDEX_OUT_OF_BOUND){
			$link_n = "#";
			$text_n = "";
		}
		else{
			$link_n = "orale.php?aid=".$next['id'];
			$text_n = $next['value'];
		}
        ?>
        <div style="width: 90%; margin: 40px auto 0 auto; border-bottom: 1px solid #ccc; border-top: 1px solid #ccc; display: flex; height: 40px; align-items: center">
            <div style="order: 1; flex-grow: 1; text-align: left"?>
                <a href="<?php echo $link_p ?>">&lt;&lt; <?php echo $text_p ?></a>
            </div>
            <div style="order: 2; flex-grow: 1; text-align: right"?>
                <a href="<?php echo $link_n ?>"><?php echo $text_n ?> >></a>
            </div>
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
