<p><img id="logojpg" src="<?php echo base_url() ?>/img/mitte.png" alt="Logo Berlin Mitte" width="54px" height="80px" /></p>
	
<h2>Max-Beckmann-Saal, Luxemburger Stra&szlig;e 20, 13353 Berlin</h2>
<p id="identv">Berlin Bezirksamt Mitte</p>
<p id="identv1">Energie- und Ressourcenmanagement</p>
<p id="identc">Ingenieurb&uuml;ro Prof. Rauchfuss</p>
<!-- <p align="right" >  -->

<?php
if(! $this->config->item('meter_mode') == 'single'){

if ($case == "OK"){
	$username = $this -> session -> userdata('username');
	$img = base_url().'img/edit.png';
?>
	<div id="acInfo">
	<p>Eingelogt als <?=$username?> 
	<?php 
	$adminid =$this -> session -> userdata('isAdmin');
	if (is_numeric($adminid) && $adminid > 0) {
		echo "<img src=\"$img\" alt=\"ist Admin\" height=\"20\" width=\"20\" ></a>";
	}
	?></p>
	</div>
<?php 
}
?>
<div>
<?php
if ($case == "OK"){
	$attributes = array('class' => 'main', 'id' => 'logoutButton');
	echo form_open("main/logout", $attributes);

	echo form_submit('Logout', 'Logout');
	echo form_close();
}
?>
</div>
<?php }?>
<!-- </p> -->


