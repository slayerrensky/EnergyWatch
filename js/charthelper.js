function getJson(destination) {
  // strUrl is whatever URL you need to call

  jQuery.ajax({
    url: destination,
    success: function(html) {
      strReturn = html;
    },
    async:false
  });
  var json = $.parseJSON(strReturn);
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

function getValue(id,basePath) {
  var daten = getJson(basePath+"index.php/data/getLastValue/"+id);
  daten[0].TimeStamp = splitTS(daten[0].TimeStamp);
  daten[0].Value = parseFloat(daten[0].Value);
  return daten[0];
}

function splitTS(date)
{
	var t = date.split(/[- :]/);
	var d = new Date(t[0], t[1]-1,t[2],t[3], t[4],t[5]);
	 
	return Date.parse(d);
}


function dp2dateTS(date,time)
{
	var t = date.split(/[.]/);
	var d = t[2]+"-"+t[1]+"-"+t[0]+" "+time;
	 
	return d;
}

function isToday(date){
	var d = new Date();
	if(d.getFullYear() == date.getFullYear() && d.getDate() == date.getDate() && d.getMonth() == date.getMonth()){
		return true;
	}else{
		return false;
	}
}

function addDigit(n){
	return n<10? '0'+n:''+n;
}

function runde(x, n) {
  if (n < 1 || n > 14) return false;
  var e = Math.pow(10, n);
  var k = (Math.round(x * e) / e).toString();
  if (k.indexOf('.') == -1) k += '.';
  k += e.toString().substring(1);
  return k.substring(0, k.indexOf('.') + n+1);
}
