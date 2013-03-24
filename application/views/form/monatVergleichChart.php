<?php $url = base_url() . 'js/'; ?>
<script type="text/javascript" src="<?php echo $url?>jquery-1.8.3.js"></script>
<script type="text/javascript" src="<?php echo $url?>jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo $url?>highcharts/js/highcharts.js"></script>
<script type="text/javascript" src="<?php echo $url?>epoch_classes.js"></script>
<script type="text/javascript" src="<?php echo $url?>highstock/js/highstock.js"></script>
<script type="text/javascript" src="<?php echo $url?>highstock/js/modules/exporting.js"></script>
<script type="text/javascript" src="<?php echo $url?>kalenderwoche.js"></script>

		
<script type="text/javascript">

		var dp_cal1,dp_cal2;
	
$(document).ready(function() {
  //addItem();
  //dp_cal1 = new Epoch('epoch_popup','popup',document.getElementById('datevon'));
  //dp_cal2 = new Epoch('epoch_popup','popup',document.getElementById('datebis'));
});

var chart;


function drawLineChart(gets) {
var numberOfValues = 0;

$("#container").append('<p><img src="<?php echo base_url(); ?>/img/ajax-loader.gif" alt="Loading"></p>');

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
		     	name: MeterDaten.Name+" ("+MeterDaten.Unit+"), " + gets[i].monat + " " + gets[i].jahr ,
		     	
		        data: (function() {
		            var data = [];
		            var daten = getValuesOffsetMonth(gets[i].ID,gets[i].timeVon,gets[i].timeBis,"<?php echo base_url(); ?>");
		            //offset berechnen
		            
		            gets[i]["arbeit"]=0;
		            
		            for (var k=0,l = daten.length; k<l; k++)
		            {
		            	gets[i].arbeit+=daten[k].Value/12;
			          	data.push({
				            x: daten[k].TimeStamp,
			    	        y: daten[k].Value
			            });
			        }
			        numberOfValues += daten.length;
			        return data;
		        })(),
		        turboThreshold: numberOfValues,
			});
		}
		//alert(numberOfValues);
		return series;
	};
	
	chart = new Highcharts.StockChart({
		chart: {
	    	renderTo: 'container',
	        type: 'spline',
	        //inverted: false,
	        //width: 500,
	        height:550,
	        style: {
	        	margin: '0 auto'
	        }
	    },
	    
		rangeSelector: {
			selected: 0,
		    enabled: false
		},
	    title: {
	    	text: "Monats Vergleich"
	    },
	    subtitle: {
	    	text: ""
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
	    legend: {
	    	enabled: true,
	    	align: 'right',
        	borderColor: 'black',
        	borderWidth: 2,
	    	layout: 'vertical',
	    	verticalAlign: 'top',
	    	y: 25,
	    	shadow: true
	    },
		tooltip: {
			xDateFormat: '%d',
			headerFormat: '',
			shared: true
		},
		credits: {
            enabled: false
        }, 
	    plotOptions: {
	    	spline: {
	        	marker: {
					enabled: false,
					states: {
						hover: {
							enabled: false,
							radius: 5
						}
					}
				} 
	        }
	    },
        series: MeterValues(gets)
	});
	
	
	var legegndx = chart.legend.group.translateX;
	var pRx = legegndx; //chart.chartWidth - 210;
    var pRy = 70 + (15*gets.length);
    
    var anzeigeText= "Arbeit: <br>"
    //var Mma = getJson("<?php //echo base_url(); ?>index.php/data/getAreaValuesmma/"+id+"/"+from+"/"+to);
	for (var i=0;i< gets.length; i++)
	{
		anzeigeText+= gets[i].Name +' ' + gets[i].monat+': '+ runde(gets[i].arbeit,3) +' '+gets[i].Unit+'h<br>';
	}
	chart.renderer.label(anzeigeText, pRx+5, pRy-5)
    	.attr({
        	//fill: colors[0],
            stroke: 'black',
            'stroke-width': 2,
            padding: 5,
            r: 5
        })
        .css({
        	color: 'black',
            width: '200px'
        })
        .add()
	

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


