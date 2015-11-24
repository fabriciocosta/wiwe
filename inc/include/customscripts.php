<?
global $_csistema_;
global $__lang__;
?>
<link rel="SHORTCUT ICON" href="<?=$_DIR_SITEABS?>/inc/moldeo/moldeologo.ico" />
<meta name="keywords" content="<?=$_KEYWORDS_?>">
<meta name="description" content="<?=$_DESCRIPTION_?>">

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Moldeo.org Site">
<meta name="author" content="Fabricio Costa Alisedo">

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<script>
//PERSONALIZADOS
function cartvalidation() {
	return true;
}	

function changelang(page) {
	
	var lang = document.formlang.lang.options[document.formlang.lang.selectedIndex].value;
	
	var cart = document.formcart;
	
	if (cart) {
		cart.action = "/principal/home/"+lang+page;
		cart.lang.value = lang;
		cart.submit();
	} else {	
		document.formlang.action = lang+page;
		document.formlang.submit();
	}
	
}

function changecurr(page) {
	var curr = document.formcurr.curr.options[document.formcurr.curr.selectedIndex].value;
	var cart = document.formcart;
	
	if (cart) {
		cart.curr.value = curr;
		cart.submit();
	} else {	
		document.formcurr.action = page+"?curr="+curr;
		document.formcurr.submit();
	}
		
}

function findPos(obj) {
	var curleft = curtop = 0;
	if (obj.offsetParent) {
		curleft = obj.offsetLeft
		curtop = obj.offsetTop
		while (obj = obj.offsetParent) {
			curleft += obj.offsetLeft
			curtop += obj.offsetTop
		}
	}
	return [curleft,curtop];
}

function showmenu( item, menu, ev, offx, offy ) {

	
	var obj;
	if (ev.srcElement) {
		obj = ev.srcElement;		
	} else if (ev.target) {
		obj = ev.target;				
	} else {
		obj = document.getElementById(item);
	}
	//alert(obj);
	var poss = findPos(obj);
	
	var divmenu = document.getElementById(menu);
	
	if (offx=='childright') {
		var divmenuparent = document.getElementById(offy);
		var sizemenuparent = document.getElementById(offy+'size');
		var posdiv = findPos(divmenuparent);
		var sizediv = findPos(sizemenuparent);		
		widthdiv = sizediv[0] - posdiv[0];
		heightdiv = sizediv[1] - posdiv[1];
		divmenu.style.left = poss[0]+widthdiv;
	} else if (offx>0) {
		divmenu.style.left = poss[0]+offx;
	} else divmenu.style.left = poss[0];
	
	if (offy>0)
		divmenu.style.top = poss[1]+offy;
	else divmenu.style.top = poss[1];
	
	//alert(divmenu.style.width);
	showdiv(menu);
	
}

function hidemenu( item, menu, ev ) {

	var itemmenu = document.getElementById(item);;
	var divmenu = document.getElementById(menu);
	var sizemenu = document.getElementById(menu+'size');
	
	
	if (ev.x) {
		var posmouse = [ev.x,ev.y];
	} else if (ev.pageX) {
		var posmouse = [ev.pageX,ev.pageY];
	}	
	
	var posdiv = findPos(divmenu);
	var sizediv = findPos(sizemenu);
	widthdiv = sizediv[0] - posdiv[0];
	heightdiv = sizediv[1] - posdiv[1];
	var positem = findPos(itemmenu);
	//alert(widthdiv);
	//alert(heightdiv);
	
	if ((posmouse[0]-2)<posdiv[0] || (posmouse[0]+2)>(posdiv[0]+widthdiv) || 
	(posmouse[1]-2)<posdiv[1] || (posmouse[1]+2)>(posdiv[1]+heightdiv)) {
		hidediv(menu);
	}
	
}

function RegisterValidation() {

}

function SearchValidation() {
	var ff = document.formcart;
	
	if (ff.hometype.value>0) {
		if ( ff._e_PRODUIT_COUNTRY.options[ff._e_PRODUIT_COUNTRY.selectedIndex].value > 0 ) {
			//if (ff._e_PRODUIT_CITY.options[ff._e_PRODUIT_CITY.selectedIndex].value != '' ) {
	
					if (ff.rentper.value!='') {
						return true;
					} else {
						alert("You must choose a period");
					}			
		/*
			} else {
				alert("You must choose a city");
			}*/
		} else {
			alert("You must choose a country first");
		}
	} else {
		alert("You must choose a property first");
	}
	
	return false;
}

function ChangeAndValidate( field ) {

	var ff = document.register;

	var perday = ff._edetalle_PRODUIT_RENTPERrentper___day__.checked;
	var perweek = ff._edetalle_PRODUIT_RENTPERrentper___week__.checked;
	var permonth = ff._edetalle_PRODUIT_RENTPERrentper___month__.checked;
	
	if (perday) {
		showdiv('divrentperday');
	} else hidediv('divrentperday');
	if (permonth) {
		showdiv('divrentpermonth');
	} else hidediv('divrentpermonth');
	if (perweek) {
		showdiv('divrentperweek');
	} else hidediv('divrentperweek');
	
}

function RecordValidation() {
	var ff = document.register;
	
	if (ff._accion_.value == "delete" || ff._accion_.value=="confirmdelete") {
		return true;
	}
	
	var nsize = new Number(ff._edetalle_PRODUIT_SIZE.value);
	var imageprincipal = ff._edetalle_PRODUIT_IMAGE.value;
	var title = ff._e_TITULO.value;
	var fimageprincipal = ff._fdetalle_PRODUIT_IMAGE.value;
	var country = ff._edetalle_PRODUIT_COUNTRY.options[ff._edetalle_PRODUIT_COUNTRY.selectedIndex].value;
	var city = ff._edetalle_PRODUIT_CITY.options[ff._edetalle_PRODUIT_CITY.selectedIndex].value;
	
	var ngallery = new Number( ff._ndetalle_PRODUIT_GALLERY.value);
	var ttitle = new String(title);
	var tlength = ttitle.length;
	
	var perday = ff._edetalle_PRODUIT_RENTPERrentper___day__.checked;
	var perweek = ff._edetalle_PRODUIT_RENTPERrentper___week__.checked;
	var permonth = ff._edetalle_PRODUIT_RENTPERrentper___month__.checked;
	var rateperday = true;
	var rateperweek = true;
	var ratepermonth = true;
	if ( perday ) { rateperday = new Number( ff._edetalle_PRODUIT_TARIF1DAY_0_tarif.value ); rateperday = ( rateperday > 0 ); }
	if ( perweek ) { rateperweek = new Number( ff._edetalle_PRODUIT_TARIF1WEEK_0_tarif.value ); rateperweek = ( rateperweek > 0 ); }
	if ( permonth ) { ratepermonth = new Number( ff._edetalle_PRODUIT_TARIF1MONTH_0_tarif.value ); ratepermonth = ( ratepermonth > 0 ); }
	
	if (perday || perweek || permonth) {
		if ( rateperday && rateperweek && ratepermonth ) {
		
		if ( country > 0 ) {
			if ( city!='' ) {
		
				if (title!="" && title!='undefined') {
					if (tlength<=35) {
						if (imageprincipal!='' || fimageprincipal!='') {
							if (ngallery>=1) {			
								//if ( nsize != "NaN" ) {
									//if (nsize>0) {
										return true;
									//} else alert("Put a positive number on size field");
								//} else alert("Put a number on size field");
							} else alert("You must put at least one image on the gallery");		
						} else alert("You must put the principal image");
					} else alert("Title to long, you must complete less than 35 characters");
				} else alert("You must put a title for your property");
			
			} else alert("You must choose a city");
		} else alert("You must choose a country");
		} else alert("You must enter a rate");
	} else alert("You must select a period for rent");
	return false;
}

function SignUpValidation() {

	var ff = document.register;
	
	//var agreed = ff._iagree_.checked;
	//var visualconfirmation = ff._visualconfirmation_.value;
	var nick = ff._e_NICK.value;
	var pass = ff._e_PASSWORD.value;
	var passconfirm = ff._e_PASSWORD_confirm.value;
	alert("signup validation");
	//if (!agreed) {
	//	alert('You must agree to the term and conditions before continuiing');
	//	return false;
	//} else {
		//if (visualconfirmation!='') {
			if ( /*nick=='' ||*/ pass=='' || passconfirm=='') {
				alert('You must complete all the fields');
				return false;
			}
			if (pass!=passconfirm) {
				alert('Password confirmation doesn´t match');
				return false;
			}
			return true;
		//} else {
		//	alert('You must enter the visual confirmation code');
		//	return false;
		//}
		
	//}
	
		

}




</script>

