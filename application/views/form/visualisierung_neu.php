<?php $url = base_url() . 'js/'; ?>
<script type="text/javascript" src="<?php echo $url?>jquery-1.8.3.js"></script>
<script type="text/javascript" src="<?php echo $url?>jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo $url?>epoch_classes.js"></script>
<script type="text/javascript" src="<?php echo $url?>highstock/js/highstock.js"></script>
<script type="text/javascript" src="<?php echo $url?>highstock/js/modules/exporting.js"></script>
		
<script type="text/javascript">
	
	var dp_cal1,dp_cal2;
	var chart;
$(document).ready(function() {
  addItem();
  dp_cal1 = new Epoch('epoch_popup','popup',document.getElementById('datevon'));
  dp_cal2 = new Epoch('epoch_popup','popup',document.getElementById('datebis'));
});


function drawLineChart(gets) {
		// Create the chart
		var numberOfValues;
		var Statistic = new Array();
		 
		$("#container").append('<p><img src="<?php echo base_url(); ?>/img/ajax-loader.gif" alt="Loading"></p>');
		chart = new Highcharts.StockChart({
		    chart: {
		        renderTo: 'container',
		        height:550,
		        width: 1300,
		    },
		    rangeSelector: {
		        selected: 0,
		        enabled: false
		    },
			type: 'line',
		    title: {
		        text: gets[0].Name
		    },
		    subtitle: {
                text: gets[0].Description
            },
            xAxis: {
                type: 'datetime',
				//maxZoom: 14 * 24 * 3600000, // fourteen days
                title: {
                    enabled: true,
                    text: 'Datum / Uhrzeit'
                }
            },
            yAxis: {
                title: {
                    text: gets[0].Unit
                },
                lineWidth: 2
            },
            legend: {
                enabled: false
            },
			tooltip: {
				shared: true
			},
			credits: {
            	enabled: false
        	}, 
            plotOptions: {
                spline: {
                	dataGrouping: {
                    	enabled: false
                       },
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
             exporting: {
        		enabled: true
    		},
    		navigation: {
     		   buttonOptions: {
            		enabled: true
        		}
    		},
		    series: [{
		    	
		        name: gets[0].Name+" ("+gets[0].Unit+")",
		        type: 'spline',
		    	tooltip: {
		    		valueDecimals: 3
		       },
		        data: gets[0].data,
                turboThreshold: numberOfValues,
		    }]
		});
		

		var pRx = chart.chartWidth - 200;
	    var pRy = 50;
	
	    var Mma = gets[0].Mma;
	    var max = runde(Mma[0].Max,3);
	    var min = runde(Mma[0].Min,3);
	    var avg = runde(Mma[0].Avg,3);
	   	if (gets[0].Unit == "kW" ||gets[0].Unit == "W")
		{
			var text = 'Max: '+max+' kW<br>Min: '+min+' kW<br>Durchschnitt: '+ avg+' kW<br>Arbeit: '+ runde(gets[0].arbeit,3) +' kWh';
	    }
	    else
	    {
	    	var text = 'Max: '+max+' '+ gets[0].Unit+'<br>Min: '+min+' '+ gets[0].Unit+'<br>Durchschnitt: '+ avg +' '+ gets[0].Unit ;
	    }
	    
	    colors = Highcharts.getOptions().colors,
	    chart.renderer.label(text, pRx+5, pRy-5)
	    	.attr({
	        	fill: 'White',
	            stroke: 'black',
	            'stroke-width': 2,
	            padding: 5,
	            zIndex: 3,
	            r: 5
	        })
	        .css({
	        	color: 'black',
	            width: '200px'
	        })
	        .add()
	        
		var pRx = chartSize.width -200;
		var pRy = chartSize.height + offsetLegende-25; 	
		chart.renderer.label("Ingenieurbüro Prof. Rauchfuss", pRx, pRy)
	    	.attr({
	        	//fill: colors[0],
	            //stroke: 'black',
	            'stroke-width': 2,
	            //padding: 5,
	            //r: 5
	        })
	        .css({
	        	color: 'black',
	            width: '210px'
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
	var ID = new Array();
	var timeVon = new Array();
	var timeBis = new Array();
	var values = new Array();
	var gets = new Array()
	
	var selObj = document.getElementById('combo');
	var selIndex = selObj.selectedIndex;
	values["ID"]= selObj.options[selIndex].value;
	values["timeVon"] = dp2dateTS(document.getElementById('datevon').value,'00:00:00');
	values["timeBis"] = dp2dateTS(document.getElementById('datebis').value,'23:59:59');
	values["arbeit"] = 0;
	var MeterDaten = getJson("<?php echo base_url(); ?>index.php/data/getDataFromMeter/"+values.ID);
	values["Unit"]=MeterDaten.Unit;
	values["Name"]=MeterDaten.Name;
	values["Discription"]=MeterDaten.Discription;
	
	
	var daten = getValues(values.ID,values.timeVon,values.timeBis,"<?php echo base_url(); ?>");
		                
	var data = new Array();
    
    for (var i=0,l = daten.length; i<l; i++)
    {
    	if (MeterDaten.Unit == "kW" ||MeterDaten.Unit == "W")
    	{
    		values.arbeit+=daten[i].Value/12;
    	}
    	data.push({
                x: daten[i].TimeStamp,
                y: daten[i].Value
            }); 
    }
    numberOfValues = daten.length;
	
	values["Mma"]= getJson("<?php echo base_url(); ?>index.php/data/getAreaValuesmma/"+values.ID+"/"+values.timeVon+"/"+values.timeBis);
	values["daten"]=data;
	gets.push(values);
	
	
	drawLineChart(gets);

}

</script>
<div id="config" style="margin-left: 1em">
	<form name=myform ">
		<select name=mytextarea id="combo" >
		</select>
		
		Datum: von
		<input type="text" id="datevon" value=""  />
		bis
		<input type="text" id="datebis" value="" />
		<input type="button" name="Anzeigen" value="Anzeigen" onclick="drawChart()"/>
	</form>
</div>
<div id="container">

</div>