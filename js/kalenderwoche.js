// gibt die AKtuelle Kalerwoche eines übergebenen Datum Objekts zurück
function GetKwFromDate(date)
{
	var KWDatum = date;
	
	var DonnerstagDat = new Date(KWDatum.getTime() +
	(3-((KWDatum.getDay()+6) % 7)) * 86400000);
	
	KWJahr = DonnerstagDat.getFullYear();
	
	var DonnerstagKW = new Date(new Date(KWJahr,0,4).getTime() +
	(3-((new Date(KWJahr,0,4).getDay()+6) % 7)) * 86400000);
	
	KW = Math.floor(1.5 + (DonnerstagDat.getTime() -
	DonnerstagKW.getTime()) / 86400000/7);
		
	return KW;
}

// Gibt die Letze Kw von übergenem Jahr zurück
function GetLastKwFromJear(jahr)
{
	var i=31;
	var date =new Date(jahr, 11, i);
	//var kw = GetKwFromDate(date);
	while(1>=(kw=GetKwFromDate(date)))
	{
		i--;
		date =new Date(jahr, 11, i);
	}
	return kw;

}
