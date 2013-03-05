<nav>
<ul id="nav">
	
	<li><a class="u3" href="<?php echo site_url("main/index"); ?>">Hauptseite</a></li>
	<li><a class="u1">Z&auml;hler</a>
		<ul>
			<li><a href="<?php echo site_url("main/changeWebsite/addMeter"); ?>">Z&auml;hler anlegen</a></li>
			<li><a href="<?php echo site_url("main/changeWebsite/listmeters"); ?>">Z&auml;hler anzeigen</a></li>
		</ul>
	</li>
	<li><a>Verbrauch</a>
		<ul>
			<li><a href="<?php echo site_url("main/changeWebsite/chart2"); ?>">aktuellen Verbrauch anzeigen</a></li>
			<li><a href="<?php echo site_url("main/changeWebsite/chart"); ?>">Verbrauchs History</a></li>
			<li><a href="<?php echo site_url("main/changeWebsite/lineChart"); ?>">Line Chart</a></li>
			<li><a href="<?php echo site_url("main/changeWebsite/multiChart"); ?>">Gesamtübersicht</a></li>
			<li><a href="<?php echo site_url("main/changeWebsite/meterChart"); ?>">Meter Chart</a></li>
			<li><a href="<?php echo site_url("main/changeWebsite/visualisierung"); ?>">Visualisierung</a></li>
		</ul>
	</li>
	<li><a>Hilfe</a>
		<ul>
			<li><a class="u2" href="<?php echo site_url("main/hilfe"); ?>">Hilfe</a></li>
		</ul>
	</li>
	<?php if(! $this->config->item('meter_mode') == 'single'){?>
	
	<li><a>Administration</a>
		<ul>
			<li><a href="<?php echo site_url("main/changeWebsite/administration"); ?>">Zugang anlegen</a></li>
			<li><a href="<?php echo site_url("main/changeWebsite/adminList"); ?>">Zug&auml;nge anzeigen und l&ouml;schen</a></li>
			<li><a href="<?php echo site_url("/main/changeWebsite/changePW"); ?>">Zugangspasswort &auml;ndern</a></li>
		</ul>
	</li>
	<?php } ?>
</ul>
</nav>
