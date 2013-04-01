<?php $url = base_url() . 'js/'; ?>
<script type="text/javascript" src="<?php echo $url?>jquery-1.8.3.js"></script>
<script type="text/javascript" src="<?php echo $url?>jquery-ui.js"></script>
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
var chartSize = Array();
chartSize['height']=700;
chartSize['width']=1300;
chartSize['chartHeight']=550;
chartSize['navigatorTop']=chartSize.chartHeight;
chartSize['mmaTop']= chartSize.chartHeight + 50;

function drawLineChart(gets) {
var numberOfValues = 0;
var arbeit = 0;

$("#container").append('<p><img src="<?php echo base_url(); ?>/img/ajax-loader.gif" alt="Loading"></p>');

	function MeterValues(gets){
		var series = new Array();
		for (var i=0;i< gets.length; i++)
		{
			var MeterDaten = getJson("<?php echo base_url(); ?>index.php/data/getDataFromMeter/"+gets[i].ID);
			series.push({
		     	tooltip: {
		    		valueDecimals: 3
		       	},
		     	
		        data: (function() {
		            var data = [];
		            var daten = getValuesOffsetKw(gets[i].ID,gets[i].timeVon,gets[i].timeBis,"<?php echo base_url(); ?>");
		           	
		           	gets[i]["Unit"]=MeterDaten.Unit;
					gets[i]["Name"]=MeterDaten.Name;
		            gets[i]["Mma"]= getJson("<?php echo base_url(); ?>index.php/data/getAreaValuesmma/"+gets[i].ID+"/"+gets[i].timeVon+"/"+gets[i].timeBis); 
		            
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
		        // Legende
		        name: MeterDaten.Name+" ("+MeterDaten.Unit+"), " + gets[i].kw + "KW " + gets[i].jahr +
		        	'<br>Arbeit: '+ runde(gets[i].arbeit,3)+' '+ gets[i].Unit+'h' +
		        	'<br>Max: '+ runde(gets[i].Mma[0].Max,3) + ' ' + gets[i].Unit+'h', 
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
	        width: chartSize.width,
	        height: chartSize.height,
	        borderWidth: 2,
	        marginBottom: 150,
	        style: {
	        	margin: '0 auto'
	        }
	    },
	    navigator: {
	    	top: chartSize.navigatorTop
	    },
	    
		rangeSelector: {
			selected: 0,
		    enabled: false
		},
	    title: {
	    	text: "Kalenderwochen Vergleich"
	    },
	    subtitle: {
	    	text: ""
	    },
	    xAxis: {
	    	type: 'datetime',
			//maxZoom: 14 * 24 * 3600000, // fourteen days
	        title: {
	        	enabled: true,
	            text: 'Datum / Uhrzeit'
	        },
	        ordinal: false,
	        dateTimeLabelFormats: { // don't display the dummy year
                    hour: '%a, %H:%M',
                    day: '%a, %H:%M',
                    month: '%a, %H:%M',
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
	    	//align: 'right',
        	borderColor: 'black',
        	borderWidth: 2,
	    	layout: 'horizontal',
	    	verticalAlign: 'bottom',
	    	//y: chartSize.mma,
	    	//y: 25,
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
							enabled: true,
							radius: 5
						}
					}
				} 
	        }
	    },
        series: MeterValues(gets)
	});
		
/*	var legegndx = chart.legend.group.translateX;
	var pRx = 10; //chart.chartWidth - 210;
    var pRy = chartSize.mmaTop + (15*gets.length);
    
    var anzeigeText= "Arbeit: <br>"
    //var Mma = getJson("<?php //echo base_url(); ?>index.php/data/getAreaValuesmma/"+id+"/"+from+"/"+to);
	for (var i=0;i< gets.length; i++)
	{
		anzeigeText+= gets[i].Name + ' '+ gets[i].kw +'KW : '+ runde(gets[i].arbeit,3) +' '+gets[i].Unit+'h<br>';
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
            width: '210px'
        })
        .add()
*/
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
		var kw = elemnetlist[i][2].value;
		values["jahr"]= elemnetlist[i][1].value
		values["kw"]= elemnetlist[i][2].value
		
		var StartTS = GetDateFromKw(jahr,kw);
		var EndTS = new Date(StartTS);
		EndTS.setDate(EndTS.getDate() + 6);
		
		values["timeVon"]= StartTS.getFullYear()+"-"+(StartTS.getMonth()+1)+"-"+StartTS.getDate()+' 00:00:00';
		values["timeBis"]= EndTS.getFullYear()+"-"+(EndTS.getMonth()+1)+"-"+EndTS.getDate()+' 23:59:59';
		
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
	$("#f"+anzahl).append('<select name=KW" id="combo' + anzahl + '" ></select>');
	for (var i=0,l = meter.length; i<l; i++)
	{
		$("#combo"+anzahl).append('<option value="'+meter[i].ID+'">'+meter[i].Name+'</option>')
	}
	$("#f"+anzahl).append(' Jahr ');
	var now = new Date();
	var nowstr = now.getFullYear()+"/"+now.getMonth()+"/"+now.getDay();
	
	// Jahre ermitteln und in combo einfügen
	$("#f"+anzahl).append('<select name=Jahr" id="combojahr' + anzahl + '" onchange="updateKW(\'combokw'+anzahl+'\',this.value)"></select>');
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

	$("#f"+anzahl).append(' KW ');
	// KWs ermitteln und in combo einfügen
	var kw = GetLastKwFromJear(now.getFullYear());
	$("#f"+anzahl).append('<select name="Jahr" id="combokw' + anzahl + '" ></select>');
	for (var i=1; i<=kw; i++)
	{
		$("#combokw"+anzahl).append('<option value="'+i+'">'+i+'</option>')
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


