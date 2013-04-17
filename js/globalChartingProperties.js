
var chartSize = Array();
chartSize['height']=770; // Höhe des Gesamten Chart Bereichs
chartSize['width']=1300;       // Breite des Chartbereichs
chartSize['chartHeight']=550;  // Höhe des inneren Charts
chartSize['navigatorTop']=chartSize.chartHeight+20;  // Anfang des Navigators
chartSize['legende']= chartSize.height - chartSize.chartHeight - 30;

var lang =  {
	months: ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 
		'Juli', 'August', 'September', 'Oktober', 'November', 'December'],
	weekdays: ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'],
	downloadPDF: ['Download als PDF'],
};

var rangeSelector = {
	selected: 0,
    enabled: false
};

var exporting = { 
	buttons: {
        contextButton: {
            text: 'Drucken',
            onclick: function() {
            	this.exportChart({
                	type: 'application/pdf',
                	margin: 5,
               	});
            }

        }
	}
};

var legend = {
	enabled: true,
	borderColor: 'black',
	borderWidth: 2,
	layout: 'horizontal',
	verticalAlign: 'bottom',
	y: -10,
  	shadow: true,	
}; 
var tooltip = {	
	xDateFormat: '%d',
	headerFormat: '',
	shared: true,
		enabled: false
};

var credits = {
	text: 'Ingenieurbüro Prof. Rauchfuss',
	href: '',
}; 
var plotOptions= {
	spline: {
		marker: {
			enabled: false,
			states: {
				hover: {
					enabled: false,
					radius: 1
				}
			}
		},
		dataGrouping: {
            enabled: false
        }
    }
};