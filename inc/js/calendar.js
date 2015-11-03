///////////////////////////////////////////////////////////
//www.fortochka.com
//Alexander Babichev 2004 Coopyright
//this script is free for private use "as is"
//under the condition:
//the copyright notice should be left unchanged.
////////////////////////////////////////////////////////////


function IsHollyday( ho ,dd, mm, yyyy ) {
	var i;
	
	mm++;
	
	//alert(dd + "/" + mm + "/" + yyyy );
	
	for( i=0; i < ho.length; i++) {		
		if (ho[i] == (dd + "/" + mm + "/" + yyyy) ) return true;			
	}	
	return false;	
}


function maxDays(mm, yyyy){
var mDay;
	if((mm == 3) || (mm == 5) || (mm == 8) || (mm == 10)){ 
		mDay = 30;
  	}
  	else{
  		mDay = 31
  		if(mm == 1){
   			if (yyyy/4 - parseInt(yyyy/4) != 0){
   				mDay = 28
   			}
		   	else{
   				mDay = 29
  			}
		}
  }
return mDay; 
}

function changeBg(id){
	if (eval("document.getElementById('"+id+"')").style.backgroundColor != "yellow"){
		eval("document.getElementById('"+id+"')").style.backgroundColor = "#C5F913"
	}
	else{
		eval("document.getElementById('"+id+"')").style.backgroundColor = "#d1e1e1"
	}
}


function writeCalendar(){
var now = new Date
var dd = now.getDate()
var mm = now.getMonth()
var dow = now.getDay()
var yyyy = now.getFullYear()

var arrM = new Array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre")
var arrY = new Array()
	for (ii=0;ii<=4;ii++){
		arrY[ii] = yyyy + ii
	}
var arrD = new Array("Dom","Lun","Mar","Mie","Jue","Vie","Sab")

var text = ""
text = "<form name=calForm>"
text += "<table border=0 cellpadding=0 cellspacing=0>"

text += "<tr><td>"
text += "<table border=0 cellpadding=0 cellspacing=0 width=100%><tr>"
text += "<td align=left>"
text += "<select name=selMonth onChange='changeCal()'>"
	for (ii=0;ii<=11;ii++){
		if (ii==mm){
			text += "<option value= " + ii + " Selected>" + arrM[ii] + "</option>"
		}
		else{
			text += "<option value= " + ii + ">" + arrM[ii] + "</option>"
		}
	}
text += "</select>"
text += "</td>"

text += "<td align=right>"
text += "<select name=selYear onChange='changeCal()'>"
	for (ii=0;ii<=4;ii++){
		if (ii==0){
			text += "<option value= " + arrY[ii] + " Selected>" + arrY[ii] + "</option>"
		}
		else{		
			text += "<option value= " + arrY[ii] + ">" + arrY[ii] + "</option>"
		}
	}
text += "</select>"
text += "</td>"

text += "<td align=left>"
text += "<select name=selCountry onChange='changeCal()'>"
text += "<option value=0 Selected>AR</option>"
text += "<option value=1>CL</option>"
text += "<option value=2>UY</option>"
text += "</select>"
text += "</td>"

text += "</tr></table>"
text += "</td></tr>"

text += "<tr><td>"
text += "<table border=0 cellpadding=0 cellspacing=5>"
text += "<tr>"
	for (ii=0;ii<=6;ii++){
		text += "<td align=center><span class=label>" + arrD[ii] + "</span></td>"
	}
text += "</tr>"

aa = 0

	for (kk=0;kk<=5;kk++){
		text += "<tr>"
		for (ii=0;ii<=6;ii++){
			text += "<td align=center width=100%><span id=sp" + aa + " onClick='changeBg(this.id)'>1</span></td>"
			aa += 1
		}
		text += "</tr>"
	}
text += "</table>"
text += "</td></tr>"
text += "</table>"
text += "</form>"
document.write(text)
changeCal()
}



function changeCal(){
var now = new Date
var dd = now.getDate()
var mm = now.getMonth()
var dow = now.getDay()
var yyyy = now.getFullYear()

var currC = parseInt(document.calForm.selCountry.value)
var hollydays = feriados[currC]


var currM = parseInt(document.calForm.selMonth.value)
var prevM
	if (currM!=0){
		prevM = currM - 1
	}
	else{
		prevM = 11
	}
	
var currY = parseInt(document.calForm.selYear.value)

var mmyyyy = new Date()
mmyyyy.setFullYear(currY,currM,1)

var day1 = mmyyyy.getDay()
	if (day1 == 0){
		day1 = 7
	}

var arrN = new Array(41)
var arrH = new Array(41)
var aa

	for (ii=0;ii<day1;ii++){
		arrN[ii] = maxDays((prevM),currY) - day1 + ii + 1;
		arrH[ii] = IsHollyday(hollydays,arrN[ii],prevM,currY);
		
	}

	aa = 1
	for (ii=day1;ii<=day1+maxDays(currM,currY)-1;ii++){	
		arrN[ii] = aa
		arrH[ii] = IsHollyday(hollydays,arrN[ii],currM,currY);
		aa += 1
	}
	
	aa = 1
	for (ii=day1+maxDays(currM,currY);ii<=41;ii++){
		arrN[ii] = aa
		arrH[ii] = IsHollyday(hollydays,arrN[ii],currM,currY);
		aa += 1
	}

	for (ii=0;ii<=41;ii++){
		eval("document.getElementById('sp"+ii+"')").style.backgroundColor = "#d1e1e1"
	}

var dCount = 0
	for (ii=0;ii<=41;ii++){		
		if (((ii<7)&&(arrN[ii]>20))||((ii>27)&&(arrN[ii]<20))){
			eval("document.getElementById('sp"+ii+"')").innerHTML = arrN[ii]
			eval("document.getElementById('sp"+ii+"')").className = "c3"	
		}
		else{
			eval("document.getElementById('sp"+ii+"')").innerHTML = arrN[ii]
			if ((dCount==0)||(dCount==6)){
				eval("document.getElementById('sp"+ii+"')").className = "c2"
			} else if ( arrH[ii]== true ) {
				eval("document.getElementById('sp"+ii+"')").className = "cH"
			} else {
				eval("document.getElementById('sp"+ii+"')").className = "c1"
			}
			if ((arrN[ii]==dd)&&(mm==currM)&&(yyyy==currY)){
				eval("document.getElementById('sp"+ii+"')").style.backgroundColor="#90EE90"
			}
		}
	dCount += 1
		if (dCount>6){
			dCount=0
		}
	}
}
