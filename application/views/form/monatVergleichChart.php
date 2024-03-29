<?php $url = base_url() . 'js/'; ?>
<script type="text/javascript" src="<?php echo $url?>jquery-1.8.3.js"></script>
<script type="text/javascript" src="<?php echo $url?>jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo $url?>epoch_classes.js"></script>
<script type="text/javascript" src="<?php echo $url?>highstock/js/highstock.js"></script>
<script type="text/javascript" src="<?php echo $url?>highstock/js/modules/exporting.js"></script>
<script type="text/javascript" src="<?php echo $url?>kalenderwoche.js"></script>
<script type="text/javascript" src="<?php echo $url?>globalChartingProperties.js"></script>

		
<script type="text/javascript">

		var dp_cal1,dp_cal2;
	
$(document).ready(function() {

});

var chart;

function drawLineChart(gets) {
var numberOfValues = 0;
var offsetLegende = parseInt((gets.length - 1)/4)*80 ;

$("#container").append('<p><img src="<?php echo base_url(); ?>/img/ajax-loader.gif" alt="Loading"></p>');

	chart = new Highcharts.StockChart({
		chart: {
	    	renderTo: 'container',
	        type: 'spline',
	        //inverted: false,
	        width: chartSize.width,
	        height: chartSize.height + offsetLegende,
	        borderWidth: 2,
	        marginBottom: chartSize.legende + offsetLegende,
	        style: {
	        	margin: '0 auto'
	        }
	    },
	    navigator: {
	    	top: chartSize.navigatorTop,
	    	xAxis: {
	    		dateTimeLabelFormats: { // don't display the dummy year
                hour: '%e. %H:%M',
                day: '%e',
                month: '%e. %H:%M',
                year: '%Y'
            	}
          },
	    },
	    title: {
	    	text: "Monats Vergleich"
	    },
	    subtitle: {
	    	text: "Max Beckmann Saal, Luxemburger Straße 10, 13353 Berlin"
	    },
	    xAxis: {
	    	type: 'datetime',
			//maxZoom: 14 * 24 * 3600000, // fourteen days
	        title: {
	        	enabled: true,
	            text: '<br/>Datum / Uhrzeit'
	        },
	        ordinal: false,
	        dateTimeLabelFormats: { // don't display the dummy year
                    hour: '%e. %H:%M',
                    day: '<br/>%e.Tag',
                    month: '%e. %H:%M',
                    year: '%Y'
            }
	    },
	    yAxis: {
	    	title: {
	        	text: ""
	        },
	        lineWidth: 2
	    },
	    rangeSelector: rangeSelector, 
	    legend: legend,
	    exporting: exporting,
		tooltip: tooltip,
		credits: credits,
	    plotOptions: plotOptions,
        series: MeterValues(gets)
	});
		
	Highcharts.setOptions({
		lang: lang
	});

	function MeterValues(gets){
		var series = new Array();
		for (var i=0;i< gets.length; i++)
		{
			
			var MeterDaten = getJson("<?php echo base_url(); ?>index.php/data/getDataFromMeter/"+gets[i].ID);
			gets[i]["Unit"]=MeterDaten.Unit;
			gets[i]["Name"]=MeterDaten.Name;
			series.push({
		     	tooltip: {
		    		valueDecimals: 3
		       	},
		     		
		        data: (function() {
		            var data = [];
		            var daten = getValuesOffsetMonth(gets[i].ID,gets[i].timeVon,gets[i].timeBis,"<?php echo base_url(); ?>");
		            //offset berechnen
		            
		            gets[i]["Unit"]=MeterDaten.Unit;
					gets[i]["Name"]=MeterDaten.Name;
		            gets[i]["Mma"]= getJson("<?php echo base_url(); ?>index.php/data/getAreaValuesmma/"+gets[i].ID+"/"+gets[i].timeVon+"/"+gets[i].timeBis);
		            
		            gets[i]["arbeit"]=0;
		            
		            for (var k=0,l = daten.length; k<l; k++)
		            {
		            	if (gets[i]["Unit"].indexOf('W') >= 0)
		            	{
		            		gets[i].arbeit+=daten[k].Value/12;
		            	}
			          	data.push({
				            x: daten[k].TimeStamp,
			    	        y: daten[k].Value
			            });
			        }
			        numberOfValues += daten.length;
			        return data;
		        })(),
		        // Legende
		        name: (function() {
		        	var arbeit = '';
		        	if (gets[i].arbeit>0 )
		        	{
		        		arbeit ='<br>Arbeit: '+ runde(gets[i].arbeit,3)+' '+ gets[i].Unit+'h';
		        	}
		        			        	
		        	return MeterDaten.Name+" ("+MeterDaten.Unit+"), " + gets[i].monat + ". " + gets[i].jahr +
		        	arbeit +
		        	'<br>Maximum: '+ runde(gets[i].Mma[0].Max,3) + ' ' + gets[i].Unit+
		        	'<br>Mittelwert: '+ runde(gets[i].Mma[0].Avg,3) + ' ' + gets[i].Unit+
		        	'<br>Minimum: '+ runde(gets[i].Mma[0].Min,3) + ' ' + gets[i].Unit;
		        	
		        	})(), 
		        turboThreshold: numberOfValues,
			});
		}
		//alert(numberOfValues);
		return series;
	};


}


function addItem()
{
	var meter = getJson("<?php echo base_url(); ?>index.php/data/getMeter/1");
	var element4 = document.getElementById("combo");
	
	//for (var i in meter)
	
	for (var i=0,l = meter.length; i<l; i++)
	{
	 	var option1 = document.createElement("option");
	 	option1.value=meter[i].ID;
	 	option1.innerHTML=meter[i].Name;
	 	element4.options.add(option1);
 	} 	
}

function drawChart(){
	var elemnetlist = document.getElementsByClassName('FormArray');
	
	var ID = new Array();
	var timeVon = new Array();
	var timeBis = new Array();
	var values = new Array();
	var gets = new Array();
	for(var i = 0; i < elemnetlist.length;i++)
	{
		ID.push(elemnetlist[i][0].value);
		values = new Array();
		values["ID"]= elemnetlist[i][0].value;
		var jahr = elemnetlist[i][1].value;
		var monat = elemnetlist[i][2].value;
		values["monat"] = monat;
		values["jahr"]= elemnetlist[i][1].value
		switch (parseInt(monat-1))
		{
			case 0: values["monat"] = "Jan";break;
			case 1: values["monat"] = "Feb";break;
			case 2: values["monat"] = "Mär";break;
			case 3: values["monat"] = "Apr";break;
			case 4: values["monat"] = "Mai";break;
			case 5: values["monat"] = "Jun";break;
			case 6: values["monat"] = "Jul";break;
			case 7: values["monat"] = "Aug";break;
			case 8: values["monat"] = "Sep";break;
			case 9: values["monat"] = "Okt";break;
			case 10: values["monat"] = "Nov";break;
			case 11: values["monat"] = "Dec";break;
		}
		
		var StartTS = new Date(jahr,monat-1,1);
		if ( monat == 12)
		{
			var EndTS = new Date(parseFloat(jahr)+1,0,1);	
		}
		else
		{
			var EndTS = new Date(jahr,monat,1);
		}
		
		values["timeVon"]= StartTS.getFullYear()+"-"+(StartTS.getMonth()+1)+"-"+StartTS.getDate()+' 00:00:00';
		values["timeBis"]= EndTS.getFullYear()+"-"+(EndTS.getMonth()+1)+"-"+EndTS.getDate()+' 00:00:00';

		
		gets.push(values);
	}
	
	drawLineChart(gets);
}

var anzahl = 1;
function addMeterInView()
{
	var jahr;
	var meter = getJson("<?php echo base_url(); ?>index.php/data/getMeter/1");
	
	$("#config").append('<form id="f'+ (anzahl) +'" class="FormArray">');
	$("#f"+anzahl).append('<select name=monat" id="combo' + anzahl + '" ></select>');
	for (var i=0,l = meter.length; i<l; i++)
	{
		$("#combo"+anzahl).append('<option value="'+meter[i].ID+'">'+meter[i].Name+'</option>')
	}
	$("#f"+anzahl).append(' Jahr ');
	var now = new Date();
	var nowstr = now.getFullYear()+"/"+now.getMonth()+"/"+now.getDay();
	
	// Jahre ermitteln und in combo einfügen
	$("#f"+anzahl).append('<select name=Jahr" id="combojahr' + anzahl + '" onchange="updatemonat(\'combomonat'+anzahl+'\',this.value)"></select>');
	for (var i=2010; i<=now.getFullYear(); i++)
	{
		if (i==now.getFullYear())
		{
			$("#combojahr"+anzahl).append('<option value="'+i+'" selected="selected">'+i+'</option>')
		}
		{
			$("#combojahr"+anzahl).append('<option value="'+i+'">'+i+' </option>')
		}
		
	}

	$("#f"+anzahl).append(' Monat ');
	// monate einfügen
	$("#f"+anzahl).append('<select name="Jahr" id="combomonat' + anzahl + '" ></select>');
	for (var i=1; i<=12; i++)
	{
		$("#combomonat"+anzahl).append('<option value="'+i+'">'+i+'</option>')
	}
	
	$("#f"+anzahl).append('<input type="button" value="-" onclick="delmeter('+anzahl+')" />');
	anzahl++;
}

function updateKW(objid,jahr)
{
	$("#"+objid).children().remove();
	var kw = GetLastKwFromJear(jahr);
	for (var i=1; i<=kw; i++)
	{
		$("#"+objid).append('<option value="'+i+'">'+i+'</option>')
	}
} 

function delmeter(id)
{
	$("#f"+id).remove();	
}

</script>

<div id="config">
	<form name="hinzuf&uuml;gen">
			<input type="button" name="Hinzufuegen" value="Hinzuf&uuml;gen" onclick="addMeterInView()" />
			<input type="button" name="Anzeigen" value="Anzeigen" onclick="drawChart()"/>
	</form>
</div>		

<div id="container">

</div>


