<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>Turni di assistenza</title>
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="../../css/general.css" type="text/css" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
        $(function(){
            load_jalert();
            setOverlayEvent();

            $('.shift').autocomplete({
                source: "../../shared/get_users.php?group=teachers&ord=1",
                minLength: 2,
                select: function(event, ui){
                    comm = $(this).data('comm');
                    uid = ui.item.uid;
                    name = ui.item.value;
                    if (trim($('#list'+comm).text()) === '') {
                        $('#list'+comm).append("<span id='t"+uid+"'>"+name+"</span>");
					}
					else {
                        $('#list'+comm).append(", <span id='t"+uid+"'>"+name+"</span>");
					}
					add_teacher(uid, comm, name);
                }
            });
        });

        var add_teacher = function(teacher, comm, name){
            $.ajax({
                type: "POST",
                url: 'test_manager.php',
                data: {action: 'add_teacher', test: <?php echo $_REQUEST['idp'] ?>, teacher: teacher, comm: comm, name: name},
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
                            //document.location = document.location;
                        }, 2000);
                    }
                }
            });
        }
	</script>
</head>
<body>
<?php include "../../intranet/manager/header.php" ?>
<?php include "../../intranet/manager/".$_SESSION['__administration_group__']."/navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "menu_esami.php" ?>
	</div>
	<div id="left_col">
		<div class="card_container">
		<?php
		foreach ($workshift as $k => $wf) {
			$title = "Commissione n. ".$wf['number'].", classe 3".$wf['class'];
			if ($wf['number'] == 0) {
				$title = 'Assistenza DSA';
			}
			?>
			<div class="card">
				<div class="card_title normal"><?php echo $title ?></div>
				<div class="card_varcontent">
					<div id="list<?php echo $k ?>">
					<?php
					if (count($wf['teachers']) > 0) {
						$index = 0;
						$max = count($wf['teachers']) - 1;
						foreach ($wf['teachers'] as $uid => $teacher) {
							?>
							<span id="t<?php echo $uid ?>"><?php echo $teacher['teacher'] ?></span><?php if ($index < $max): ?>, <?php endif; ?>
						<?php
							$index++;
						}
					}
					?>
					</div>
					<div style="margin-top: 15px" class="material_label">
						<label for="new_t<?php echo $k ?>">Aggiungi docente</label>
						<input type="text" data-comm="<?php echo $k ?>" class="shift" id="new_t<?php echo $k ?>" name="new_t<?php echo $k ?>" style="width: 150px; margin-left: 20px" /><br />
					</div>
				</div>
			</div>
			<?php
		}
		?>
		</div>
	</div>
	<p class="spacer"></p>
</div>
<?php include "../../intranet/manager/footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link"><a href="../../intranet/manager/index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="../../intranet/manager/profile.php"><img src="../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<div class="drawer_link"><a href="../../modules/documents/load_module.php?module=docs&area=<?php echo $_SESSION['__area__'] ?>"><img src="../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
		<?php if(is_installed("com")){ ?>
			<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=<?php echo $_SESSION['__area__'] ?>"><img src="../../images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
		<?php } ?>
		<div class="drawer_link"><a href="../../intranet/manager/utility.php"><img src="../../images/59.png" style="margin-right: 10px; position: relative; top: 5%" />Utility</a></div>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
