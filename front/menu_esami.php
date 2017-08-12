<div class="smallbox" id="working">
	<p class="menu_label class_icon">Esame classe <?php print $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione() ?></p>
	<ul class="menublock" style="" dir="rtl">
        <li><a href="index.php">Home</a></li>
		<li><a href="elenco_alunni.php">Riepilogo alunni</a></li>
        <?php
		if($_SESSION['__user__']->isCoordinator($_SESSION['__classe__']->get_ID())):
        ?>
        <li><a href="invalsi.php">Prova INVALSI</a></li>
        <?php endif; ?>
        <?php
        if (count($_SESSION['teacher_subjects']) > 0) {
            foreach ($_SESSION['teacher_subjects'] as $k => $test) {
				?>
        <li><a href="valuta_scritto.php?sub=<?php echo $k ?>"><?php echo $test['prova'] ?></a></li>
        <li><a href="giudizi.php?sub=<?php echo $k ?>">Gestione giudizi</a></li>
				<?php
			}
		}
        ?>
        <li><a href="orali.php">Colloquio</a></li>
        <li><a href="consiglio_orientativo.php">Consiglio orientativo</a></li>
        <li><a href="esiti_esame.php">Esiti</a></li>
	</ul>
</div>
<?php
