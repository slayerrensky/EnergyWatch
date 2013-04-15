<html>
	<head>
		


<script type="text/javascript" src="http://88.198.38.147/neu/js/highstock/js/highstock.js"></script>
<script type="text/javascript" src="http://88.198.38.147/neu/js/highstock/js/modules/exporting.js"></script>
<script type="text/javascript" src="http://88.198.38.147/neu/js/dumydata.js"></script>
<script type="text/javascript" src="http://88.198.38.147/neu/js/jquery-1.8.3.js"></script>



<script type="text/javascript">

$(document).ready(function () {
var numberOfValues = 0;
var offsetLegende = parseInt((1 - 1)/4)*80 ;
    var gets = new Array();
    var value = new Array();
    var chartSize = Array();
chartSize['height']=740;       // Höhe des Gesamten Chart Bereichs
chartSize['width']=1300;       // Breite des Chartbereichs
chartSize['chartHeight']=550;  // Höhe des inneren Charts
chartSize['navigatorTop']=chartSize.chartHeight;  // Anfang des Navigators
chartSize['legende']= chartSize.height - chartSize.chartHeight ;
    
    value['ID']=4;
    value["timeVon"] = "2013-1-1 00:00:00";
    value["timeBis"] = "2013-1-31 23:59:59";
    value["Unit"] = "kW";
    gets.push(value);



    function MeterValues(gets){    
        
		var series = new Array();
		for (var i=0;i< gets.length; i++)
		{
			
			//var MeterDaten = getJson("http://88.198.38.147/neu/index.php/data/getDataFromMeter/"+gets[i]["ID"]);
			//gets[i]["Unit"]=MeterDaten.Unit;
			//gets[i]["Name"]=MeterDaten.Name;
			series.push({
		     	tooltip: {
		    		valueDecimals: 3
		       	},
		     		
		        data: (function() {
		            var data = [];
		            var daten = getValues(gets[i].ID,gets[i].timeVon,gets[i].timeBis,"http://88.198.38.147/neu/");
		            //offset berechnen
		            
		            gets[i]["Unit"]=MeterDaten.Unit;
					gets[i]["Name"]=MeterDaten.Name;
		            gets[i]["Mma"]= getJson("http://88.198.38.147/neu/index.php/data/getAreaValuesmma/"+gets[i].ID+"/"+gets[i].timeVon+"/"+gets[i].timeBis);
		            
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
        	borderColor: 'black',
        	borderWidth: 2,
	    	layout: 'horizontal',
	    	verticalAlign: 'bottom',
	    	shadow: true
	    },
		tooltip: {
			xDateFormat: '%d',
			headerFormat: '',
			shared: true,
			enabled: false
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
})

function getJson(destination) {
// strUrl is whatever URL you need to call
/*jQuery.ajax({
    type: "POST",
url: destination,
dataType: "jsonp",
success: function(html) {
    strReturn = html;
},
});*/
var json = $.parseJSON(dumydaten);
return json.data;
} 

function getValues(id,from,to,basePath)
{
	var daten = getJson(basePath+"index.php/data/getAreaValues/"+id+"/"+from+"/"+to);
	for (var i=0,l = daten.length; i<l; i++)
	{
		daten[i].TimeStamp = splitTS(daten[i].TimeStamp);
		daten[i].Value = parseFloat(daten[i].Value);
	}
	return daten;
}

</script>
	</head>
<body>
	
	<div id="container" style="height: 500px; min-width: 500px"></div>
</body>
</html>