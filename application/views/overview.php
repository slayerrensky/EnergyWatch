<div id="overview">
	<h3>Willkommen im Energy Watch Portal.</h3>
	
	<p>Letzter Datensatz vom: 
	<?php 
		$data = $this -> Data_model -> getLastTimeStamp();
		$date_time = explode(" ",$data[0]['TimeStamp']);
		$date = explode("-",$date_time[0]);
		echo $date[2].".".$date[1].".".$date[0]." ".$date_time[1];
	?>
</p>
</div>