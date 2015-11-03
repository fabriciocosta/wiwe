/*** Freeware Open Source writen by ngoCanh 2002-05                  */
/*** Original by Vietdev  http://vietdev.sourceforge.net             */
/*** Release 2003-05-04  R8.5                                        */
/*** GPL - Copyright protected                                       */
/*********************************************************************/
function iEditor(idF)
{
  var obj= document.getElementById(idF).contentWindow;
  obj.document.designMode="On"

  obj.document.addEventListener("mousedown", function(){ TXTOBJ=null; fID=idF; }, false); 
  obj.document.addEventListener("mouseup", FMUp, false); 
  obj.document.addEventListener("keypress", FKPress, true); 
  
  var arr= idF.split("VDevID");
  var val= document.forms[arr[0]][arr[1]].value

   val= val.replace(/\r/g,"");
   val= val.replace(/\n</g,"<");
   
   var reg= /<pre>/i ;
   if( reg.test(val) )
	 { val= val.replace(/\n/g, "&#13;"); val= val.replace(/\t/g, "     "); }

   val= val.replace(/\n/g, "<br>");
   val= val.replace(/\t/g, "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");

   val= val.replace(/\\/g, "&#92;");
   val= val.replace(/\'/g, "&#39;");

   if(val && val.indexOf('ViEtDeVdIvId')>=0) val= initDefaultOptions1(val,idF)
   else initDefaultOptions0(idF)

   setTimeout("document.getElementById('"+idF+"').contentWindow.document.body.innerHTML='"+val+"'",200)


   TXTOBJ= null
   format[idF]='HTML'
   viewm[idF]=1;

   obj.focus();
}




function changetoIframeEditor(el)
{
   if( /Netscape/.test(navigator.userAgent) ) return null;

   var wi= '', hi= '';
   if(el.style.height) hi= el.style.height
   else if(el.rows) hi= (14*el.getAttribute('rows')+28)
   if(el.style.width) wi= el.style.width
   else if(el.cols) wi= (6*el.getAttribute('cols') +25)
   
   var parent= el.parentNode   

   
   while(parent.nodeName != 'FORM') parent= parent.parentNode
   var oform= parent
   var fidx=0; while(document.forms[fidx] != oform) fidx++ ; // form index


   var val='', fID;

   if(el.nodeName=='TEXTAREA' || el.nodeName=='INPUT')
	 { fID= fidx+'VDevID'+el.getAttribute('name'); val= el.value }
   else fID= fidx+'VDevID'+el.getAttribute('id')


   createEditor(el,fID,wi,hi);

   setTimeout("iEditor('"+fID+"')",200); 
   return true;
  
}




// init all found TEXTAREA in document
function changeAllTextareaToEditors()
{
  var i=0;
  while(document.getElementsByTagName('textarea')[i])
   { 
    if(!changetoIframeEditor(document.getElementsByTagName('textarea')[i])) break;
	if(++i>0 && !document.getElementsByTagName('textarea')[i] ) i=0;
   }
}



// init all found IFRAME in document to Editable
function changeAllIframeToEditors()
{
  var i=0;
  while(document.getElementsByTagName('iframe')[i])
  { 
	if(!changetoIframeEditor(document.getElementsByTagName('iframe')[i])) break;
	i++
  }

}



// init some IFRAMEs
// e.g. changeIframeToEditor('id1','id2',...); // id1= id of frame
function changeIframeToEditor()
{
  for(var j=0;j<arguments.length;j++)
   {
     var i=0;
	 while(document.getElementsByTagName('iframe')[i])
	  { 
		if(document.getElementsByTagName('iframe')[i].id == arguments[j])
		  {	changetoIframeEditor(document.getElementsByTagName('iframe')[i]); break; }
	    i++
	  }
   }
}




/////////////////////////////////////////////////////////////////
function controlRows(fid)
{
  var str = "\
<style>\
img.vdev {width:23; height:22}\
select.vdev {font-family:arial; font-size:12; height:22; background:#a0a080; color:#FFFFFF}\
input.vdev {font-family:arial; font-size:12; height:20; background:#a0a080; color:#FFFFFF}\
</style>\
<TR bgColor=#c0c0a0 align=center valign=middle EVENT>\
<TD nowrap style='cursor:pointer'>\
<img src='IURL/bold.gif' alt='Bold' class=vdev onclick='doFormatF(\"Bold\")'>\
<img src='IURL/left.gif' alt='Left' class=vdev onclick='doFormatF(\"JustifyLeft\")'>\
<img src='IURL/center.gif' alt='Center' class=vdev onclick='doFormatF(\"JustifyCenter\")'>\
<img src='IURL/right.gif' alt='Right' class=vdev onclick='doFormatF(\"JustifyRight\")'>\
<img src='IURL/outdent.gif' alt='Outdent' class=vdev onclick='doFormatF(\"Outdent\")'>\
<img src='IURL/indent.gif' alt='Indent' class=vdev onclick='doFormatF(\"Indent\")'>\
<img src='IURL/italic.gif' alt='Italic' class=vdev onclick='doFormatF(\"Italic\")'>\
<img src='IURL/under.gif' alt='Underline' class=vdev onclick='doFormatF(\"Underline\")'>\
<img src='IURL/strike.gif' alt='StrikeThrough' class=vdev onclick='doFormatF(\"StrikeThrough\")'>\
<img src='IURL/superscript.gif' alt='SuperScript' class=vdev onclick='doFormatF(\"SuperScript\")'>\
<img src='IURL/subscript.gif' alt='SubScript' class=vdev onclick='doFormatF(\"SubScript\")'>\
<img src='IURL/bgcolor.gif' alt='Background' class=vdev onclick='selectBgColor()'>\
<img src='IURL/fgcolor.gif' alt='Foreground' class=vdev onclick='selectFgColor()'>\
<img src='IURL/image.gif' alt='Insert Image' class=vdev onclick='browseImages()'>\
<img src='IURL/link.gif' alt='Create Link' class=vdev onclick='createLink()'>\
<img src='IURL/numlist.gif' alt='OrderedList' class=vdev onclick='doFormatF(\"InsertOrderedList\")'>\
<img src='IURL/bullist.gif' alt='UnorderedList' class=vdev onclick='doFormatF(\"InsertUnorderedList\")'>\
<img src='IURL/hr.gif' alt='HR' class=vdev onclick='doFormatF(\"InsertHorizontalRule\")'>\
<img src='IURL/pre.gif' alt='Pre-Block' class=vdev onclick='doFormatF(\"formatBlock,PRE\")'>\
<img src='IURL/unpre.gif' alt='Del Pre-Block' class=vdev onclick='doFormatF(\"formatBlock,P\")'>\
<img src='IURL/delformat.gif' alt='Delete Format' class=vdev onclick='doFormatF(\"RemoveFormat\")'>\
<img src='IURL/undo.gif' alt='Undo' class=vdev onclick='doFormatF(\"Undo\")'>\
<img src='IURL/redo.gif' alt='Redo' class=vdev onclick='doFormatF(\"Redo\")'>\
<img src='IURL/cool.gif' alt='Emotions' class=vdev onclick='selectEmoticon()'>\
<img src='IURL/wow.gif' alt='Characters' class=vdev onclick='characters()'>\
</TD></TR>"

if(FULLCTRL)
{
str += "\
<TR bgColor=#c0c0a0 valign=middle align=center EVENT>\
<TD nowrap style='cursor:pointer'>\
<img src='IURL/instable.gif' alt='InsertTable' class=vdev onclick='insertTable()'>\
<img src='IURL/tabprop.gif' alt='TableProperties' class=vdev onclick='tableProp()'>\
<img src='IURL/cellprop.gif' alt='CellProperties' class=vdev onclick='cellProp()'>\
<img src='IURL/inscell.gif' alt='InsertCell' class=vdev onclick='insertTableCell()'>\
<img src='IURL/delcell.gif' alt='DeleteCell' class=vdev onclick='deleteTableCell()'>\
<img src='IURL/insrow.gif' alt='InsertRow' class=vdev onclick='insertTableRow()'>\
<img src='IURL/delrow.gif' alt='DeleteRow' class=vdev onclick='deleteTableRow()'>\
<img src='IURL/inscol.gif' alt='InsertCol' class=vdev onclick='insertTableCol()'>\
<img src='IURL/delcol.gif' alt='DeleteCol' class=vdev onclick='deleteTableCol()'>\
<img src='IURL/mrgcell.gif' alt='IncreaseColSpan' class=vdev onclick='morecolSpan()'>\
<img src='IURL/spltcell.gif' alt='DecreaseColSpan' class=vdev onclick='lesscolSpan()'>\
<img src='IURL/mrgrow.gif' alt='IncreaseRowSpan' class=vdev onclick='morerowSpan()'>\
<img src='IURL/spltrow.gif' alt='DecreaseRowSpan' class=vdev onclick='lessrowSpan()'>\
<img src='IURL/div.gif' alt='CreateDiv/DivStyle' class=vdev onclick='insertDivLayer()'>\
<img src='IURL/divstyle.gif' alt='DivStyle' class=vdev onclick='editDivStyle()'>\
<img src='IURL/divborder.gif' alt='DivBorder' class=vdev onclick='editDivBorder()'>\
<img src='IURL/divfilter.gif' alt='DivFilter' class=vdev onclick='editDivFilter()'>\
<img src='IURL/marquee.gif' alt='Marquee' class=vdev onclick='doFormatF(\"InsertMarquee\")'>\
<img src='IURL/all.gif' alt='SelectAll' class=vdev onclick='selectAll()'>\
<img src='IURL/cut.gif' alt='Cut' class=vdev onclick='doFormatF(\"Cut\")'>\
<img src='IURL/copy.gif' alt='Copy' class=vdev onclick='doFormatF(\"Copy\")'>\
<img src='IURL/paste.gif' alt='Paste' class=vdev onclick='doFormatF(\"Paste\")'>\
<img src='IURL/chipcard.gif' alt='Content Recover/Insert-Smartcard-Data' class=vdev onclick='SmartcardData()'>\
<img src='IURL/search.gif' alt='Search/Replace' class=vdev onclick='findText()'>\
<img src='IURL/file.gif' alt='Open/Save File' class=vdev onclick='FileDialog()'>\
</TD></TR>\
";
}

str += "<TR bgColor=#a0a080 valign=middle align=center EVENT>\
<TD nowrap style='cursor:pointer'>\
<SELECT name='QBCNTRL1' class=vdev onchange='doFormatF(\"FontName,\"+this.value)' style='width:120'>\
<OPTION value=''>Default Font\
<OPTION value='Arial'>Arial\
<OPTION value='Times New Roman'>Times New Roman\
<OPTION value='Webdings'>Webdings\
</SELECT>\
<SELECT name='QBCNTRL2' class=vdev onchange='doFormatF(\"formatBlock,\"+this.value)' style='width:50'>\
<OPTION value=''>Head\
<OPTION value='H1'>H1\
<OPTION value='H2'>H2\
<OPTION value='H3'>H3\
<OPTION value='H4'>H4\
<OPTION value='H5'>H5\
<OPTION value='H6'>H6\
<OPTION value='P'>Remove</OPTION>\
</SELECT>\
<SELECT name='QBCNTRL3' class=vdev onchange='doFormatF(\"FontSize,\"+this.value)' style='width:55'>\
<OPTION value=3>FSize\
<OPTION value=7>Size=7\
<OPTION value=6>Size=6\
<OPTION value=5>Size=5\
<OPTION value=4>Size=4\
<OPTION value=3>Size=3\
<OPTION value=2>Size=2\
<OPTION value=1>Size=1\
</OPTION>\
</SELECT>"


if(USEFORM==1)
{
str += "\
<SELECT name='QBCNTRL4' class=vdev onchange=doFormatF(this.value) style='width:80'>\
<OPTION value=''>Form\
<OPTION value=InsertFieldset>Fieldset\
<OPTION value=InsertInputButton>Button\
<OPTION value=InsertInputReset>Reset\
<OPTION value=InsertInputSubmit>Submit\
<OPTION value=InsertInputCheckbox>Checkbox\
<OPTION value=InsertInputRadio>Radio\
<OPTION value=InsertInputText>Text\
<OPTION value=InsertSelectDropdown>Dropdown\
<OPTION value=InsertSelectListbox>Listbox\
<OPTION value=InsertTextArea>TextArea\
<OPTION value=InsertButton>IEButton\
<OPTION value=InsertIFrame>IFrame\
</SELECT>";
}

str += "\
<INPUT name='QBCNTRL6' value='qSave' class=vdev onclick='saveBefore()' type=button style='width:45'>\
<INPUT name='QBCNTRL5' value='SwapMode' class=vdev onclick='swapMode()' type=button style='width:70'>\
"

if(FULLCTRL)
{
str += "\
<INPUT name='QBCNTRL8' value='Upload' class=vdev onclick='doUploadFile()' type=button style='width:50'>\
<INPUT name='QBCNTRL9' value='Options' class=vdev onclick='doEditorOptions()' type=button style='width:50'>\
<INPUT name='QBCNTRL10' value='Help' class=vdev onclick='displayHelp()' type=button style='width:35'>\
";
}
else
{
str += "<INPUT name='QBCNTRL7' value='Extras' class=vdev onclick='doExtras()' type=button style='width:65; color:#00FF00'>"
}

str += "</TD></TR>"

 var iurl= QBPATH + '/imgedit'
 var event= "onmousedown='fID=\"" + fid +"\"'"
 str = str.replace(/IURL/g, iurl);
 str = str.replace(/EVENT/g, event);
 return str ;
}



function createEditor(el,id,wi,hi)
{
  if(parseInt(wi)<630) wi=630;
  
  var hval='';
  if(el.value) hval= el.value
  hval= hval.replace(/\'/g,"&#39;")
  hval= hval.replace(/&/g,"&amp;")

  var arr = id.split("VDevID")

  var strx = "<iframe id="+id+" style='height:"+hi+"; width:"+wi+"'></iframe>"
  strx += "<input name="+arr[1]+" type=hidden value='"+hval+"'></input>"
  var str="<TABLE border=1 cellspacing=0 cellpadding=1 width="+wi+"><tr><td align=center>"
  str += strx+"</td></tr>"
  str += controlRows(id);
  str += "</TABLE>" ;

  var parent = el.parentNode;
  var oDiv = document.createElement('div');
  parent.insertBefore(oDiv, el);
  parent.removeChild(el);	 

  oDiv.innerHTML= str
}





function selectEmoticon()
{ 
  var el=document.getElementById(fID).contentWindow;
  if(!el){alert('Please click to select the editor');return}
  el.focus();
  doFormatDialog('emoticon.html','InsertImage',QBPATH)
}

function selectBgColor()
{ 
  doFormatDialog('selcolor.html',"HiliteColor",'')
}


function selectFgColor()
{ 
  doFormatDialog('selcolor.html','ForeColor','')
}



//NTOAFA: 8oD
function doUploadFile()
{
  var el= document.getElementById(fID).contentWindow;
  if(!el){alert('Please click to select the editor');return}
  el.focus()

  var urlx= './upload.html'
  if(FULLCTRL) urlx= QBPATH + '/upload.html'

  var twidth= 0.8*screen.width, theight=190;
  var tposx= (screen.width- twidth)/2
  var tposy= screen.height- theight - 55
  	    	  
  var newWin1=window.open(urlx,"upload","toolbar=no,width="+ twidth+",height="+ theight+ ",directories=no,status=no,scrollbars=yes,resizable=no, menubar=no")
  newWin1.moveTo(tposx,tposy);
  newWin1.focus()

}


function doEditorOptions()
{
  var el= document.getElementById(fID).contentWindow;
  if(!el){alert('Please click to select the editor');return}
  el.focus()

  var urlx= './options.html'
  if(FULLCTRL) urlx= QBPATH + '/options.html'

  var twidth= 0.8*screen.width, theight=190;
  var tposx= (screen.width- twidth)/2
  var tposy= screen.height- theight - 55
  	    	  
  var newWin1=window.open(urlx,"options","toolbar=no,width="+ twidth+",height="+ theight+ ",directories=no,status=no,scrollbars=yes,resizable=no, menubar=no")
  newWin1.moveTo(tposx,tposy);
  newWin1.focus()

}


function displayHelp()
{
  var urlx= './edithelp.html'
  if(FULLCTRL) urlx= QBPATH + '/edithelp.html'

  var newWin=window.open(urlx,"help","toolbar=no, width=600px,height=400px,directories=no,status=no,scrollbars=yes,resizable=yes,menubar=no;scroll=no")
  newWin.focus()
}


function doExtras()
{
  var el= document.getElementById(fID).contentWindow;
  if(!el){alert('Please click to select the editor');return}
  el.focus()

  var urlx= QBPATH + '/extras.html'
  var twidth=400, theight=20;
  var tposx= (screen.width- twidth)/2
  var tposy= screen.height- theight - 155
  	    	  
  var newWin1=window.open(urlx,"extras","toolbar=no,width="+ twidth+",height="+ theight+ ",directories=no,status=no,scrollbars=yes,resizable=no, menubar=no")
  newWin1.moveTo(tposx,tposy);
  newWin1.focus()

}



//NTOAFA: 8oD
function insertLink(linkurl)
{
  if(!fID && !TXTOBJ){alert('Please click a text element');return}
  var strx= "<A href='"+linkurl+"' target=nwin>" + linkurl + "</A>"

  if(fID)
  {
    var el= document.getElementById(fID).contentWindow;
	el.focus();
    var sel = el.getSelection();
	var range = sel.getRangeAt(0);
	var container = range.startContainer;
	if(container.nodeType!=3) return; // text or empty
	insertHTML(el,strx);
  }
  else 
  {
	TXTOBJ.focus();
    var conts= TXTOBJ.value;

	var start= TXTOBJ.selectionStart
	var end= TXTOBJ.selectionEnd
    var conts1= conts.substring(0,start)
    var conts2= conts.substr(end)

	TXTOBJ.value= conts1 + strx + conts2

	var cursor= conts1.length+ strx.length
	TXTOBJ.setSelectionRange(cursor,cursor)
  }

}



var curDIV;
function addEventToDiv()
{
  var el= document.getElementById(fID).contentWindow; 
  // add event listen Div
  var objA= el.document.getElementsByTagName('div')
  for(var i=0; i<objA.length;i++)
   { objA[i].addEventListener("click", clickDIV, true) }
}


function clickDIV(e)
{
  curDIV=e.target
}



function insertDivLayer()
{
  var el= document.getElementById(fID).contentWindow; 
  if(!el){alert('Please click to select the editor');return}
  el.focus()
  
  var sel = el.getSelection();
  var range = sel.getRangeAt(0);
  var container = range.startContainer;
  
  if(container.nodeType!=3) return; // text or empty

  var wrd= sel;
  if(wrd=='') wrd="I'm a DIV-Layer."
  var div= "<DIV style='position:relative; font-family:Arial; font-size:12px; background-color:#f0fdd0; border:2px outset; width:150px;'>"+ wrd + "</DIV>" ;
  insertHTML(el,div);
  addEventToDiv()
  return

}



function editDivStyle()
{
  var el= document.getElementById(fID).contentWindow; 
  if(!el){alert('Please click to select the editor');return}
  el.focus()
  
  if(!curDIV){alert('Please click to select a div-layer'); addEventToDiv(); return}

  var urlx= './divstyle.html'
  if(FULLCTRL) urlx= QBPATH + '/divstyle.html'

  var twidth= 0.8*screen.width, theight=190;
  var tposx= (screen.width- twidth)/2
  var tposy= screen.height- theight - 55
  	    	  
  var newWin1=window.open(urlx,"divstyle","toolbar=no,width="+ twidth+",height="+ theight+ ",directories=no,status=no,scrollbars=yes,resizable=no, menubar=no")
  newWin1.moveTo(tposx,tposy);
  newWin1.focus()

}


function editDivBorder()
{
  var el= document.getElementById(fID).contentWindow; 
  if(!el){alert('Please click to select the editor');return}
  el.focus()

  if(!curDIV){alert('Please click to select a div-layer'); addEventToDiv(); return} 

  var urlx= QBPATH + '/divborder.html'

  var twidth= 0.8*screen.width, theight=215;
  var tposx= (screen.width- twidth)/2
  var tposy= screen.height- theight - 55
  	    	  
  var newWin1=window.open(urlx,"divborder","toolbar=no,width="+ twidth+",height="+ theight+ ",directories=no,status=no,scrollbars=yes,resizable=no, menubar=no")
  newWin1.moveTo(tposx,tposy);
  newWin1.focus()

}




function editDivFilter()
{
  var el= document.getElementById(fID).contentWindow; 
  if(!el){alert('Please click to select the editor');return}
  el.focus()

  if(!curDIV){alert('Please click select a div-layer'); addEventToDiv(); return} 

  var urlx= QBPATH + '/divfilter.html'

  var twidth= 0.8*screen.width, theight=210;
  var tposx= (screen.width- twidth)/2
  var tposy= screen.height- theight - 55
  	    	  
  var newWin1=window.open(urlx,"divfilter","toolbar=no,width="+ twidth+",height="+ theight+ ",directories=no,status=no,scrollbars=yes,resizable=no, menubar=no")
  newWin1.moveTo(tposx,tposy);
  newWin1.focus()

}






function FileDialog()
{
  var urlx= './filedialog.html'
  if(FULLCTRL) urlx= QBPATH + '/filedialog.html'

  var twidth= 0.5*screen.width, theight=100;
  var tposx= (screen.width- twidth)/2
  var tposy= screen.height- theight - 55
  	    	  
  var newWin1=window.open(urlx,"fdialog","toolbar=no,width="+ twidth+",height="+ theight+ ",directories=no,status=no,scrollbars=yes,resizable=no, menubar=no")
  newWin1.moveTo(tposx,tposy);
  newWin1.focus()
}




function initDefaultOptions0(fID)
{
   setTimeout("document.getElementById('"+fID+"').contentWindow.document.body.style.fontFamily='"+DFFACE+"'",200)
   setTimeout("document.getElementById('"+fID+"').contentWindow.document.body.style.fontSize='"+DFSIZE+"'",200)
   setTimeout("document.getElementById('"+fID+"').contentWindow.document.body.style.color='"+DCOLOR+"'",200)
   setTimeout("document.getElementById('"+fID+"').contentWindow.document.body.style.backgroundColor='"+DBGCOL+"'",200)
   setTimeout("document.getElementById('"+fID+"').contentWindow.document.body.style.backgroundImage='url("+DBGIMG+")'",200)

   // setTimeout("CSS['"+fID+"']=document.getElementById('"+fID+"').contentWindow.document.createStyleSheet('"+DCSS+"')",200)

   FACE[fID]= DFFACE;
   SIZE[fID]= DFSIZE;
   COLOR[fID]= DCOLOR;
   BCOLOR[fID]= DBGCOL;
   BIMAGE[fID]= DBGIMG;
}






function DefaultOptions(linex)
{
  var retArr= new Array('','','','','','','');
  var tempx, strx, objx, idx ;


  // DEFAULT DIV
  var idx= linex.indexOf('ViEtDeVdIvId')
  if(idx>=0) 
	{
	  strx= linex.substring(linex.indexOf('style="')+7,linex.indexOf('">'))

      var atrA= strx.split(";")
	  for(var i=0; i<atrA.length; i++)
		{
		  tempx= atrA[i].split(':')
		  switch(tempx[0].toUpperCase())
		   {
			case "FONT-FAMILY": retArr[0]= tempx[1]; break;
			case "FONT-SIZE": retArr[1]= tempx[1]; break;
			case "BACKGROUND-COLOR": retArr[2]= tempx[1]; break;
			case "COLOR": retArr[3]= tempx[1]; break;
			case "BACKGROUND-IMAGE": if(tempx[2]) tempx[1] += ':'+ tempx[2];
									 retArr[4]= tempx[1].substring(tempx[1].indexOf('url(')+4,tempx[1].indexOf(')') ); 
									 break;
		   }
	    }

	  linex= ""+ />.*<\/div>/i.exec(linex)
      linex= linex.substring(1,linex.length-6)	
    }


   // EXT STYLE
   idx= linex.indexOf('<style>@import url("')
   if( idx>=0 )
    {
	   var strx= linex.substring(idx+20, linex.indexOf('")'))
       retArr[5]= strx
	   linex= linex.substring(0,idx)
    }

   retArr[6]= linex

   return retArr

}





function initDefaultOptions1(linex,fID)
{
  var retArr= new Array();

  retArr= DefaultOptions(linex);

  setTimeout("document.getElementById('"+fID+"').contentWindow.document.body.style.fontFamily='"+retArr[0]+"'",200)
  setTimeout("document.getElementById('"+fID+"').contentWindow.document.body.style.fontSize='"+retArr[1]+"'",200)
  setTimeout("document.getElementById('"+fID+"').contentWindow.document.body.style.backgroundColor='"+retArr[2]+"'",200)
  setTimeout("document.getElementById('"+fID+"').contentWindow.document.body.style.color='"+retArr[3]+"'",200)
  setTimeout("document.getElementById('"+fID+"').contentWindow.document.body.style.backgroundImage='url("+retArr[4]+")'",200)
  setTimeout("CSS['"+fID+"']=document.getElementById('"+fID+"').contentWindow.document.createStyleSheet('"+retArr[5]+"')",200)
  FACE[fID]= retArr[0];
  SIZE[fID]= retArr[1];
  COLOR[fID]= retArr[3];
  BCOLOR[fID]= retArr[2];
  BIMAGE[fID]= retArr[4];

  return retArr[6]

}




function actualize()
{
  var i=0;
  while(document.getElementsByTagName('iframe')[i])
  { 
	setHiddenValue(document.getElementsByTagName('iframe')[i].id) 
	i++
  }
}



function setHiddenValue(fid)
{ 
 if(!fid) return

 var strx= editorContents(fid)

 var idA= fid.split('VDevID')
 if(!idA[0]) return;

 var fobj= document.forms[idA[0]]
 if(!fobj) return;

 var loc=location.href
 loc= loc.substring(0,loc.lastIndexOf('/'))
 if(! /http:\/\//.test(loc) || /http\:\/\/127\.0\.0\.1/.test(loc) || /http\:\/\/localhost/.test(loc))
  {
   loc= loc.replace(/\//g,"\\/")
   loc= loc.replace(/\./g,"\\.")
   var reg= eval("/"+loc+"/g");
   strx= strx.replace(reg,".")
  }

 strx= exchangeTags(strx,"<div>","</div>","",""); //delete trick div
 fobj[idA[1]].value= strx

}	





function doCleanCode(strx,fid) 
{    

  strx = strx.replace(/\r/g,""); 
  strx = strx.replace(/\n>/g,">"); 
  strx = strx.replace(/>\n/g,">"); 

  strx = strx.replace(/\\/g,"&#92;");
  strx = strx.replace(/\'/g,"&#39;")


  // Security
  if(SECURE==1)
	{
	  strx = strx.replace(/<meta/ig, "< meta"); 
	  strx = strx.replace(/&lt;meta/ig, "&lt; meta"); 

	  strx = strx.replace(/<script/ig, "< script"); 
	  strx = strx.replace(/&lt;script/ig, "&lt; script"); 
	  strx = strx.replace(/<\/script/ig, "< /script"); 
	  strx = strx.replace(/&lt;\/script/ig, "&lt; /script"); 

	  strx = strx.replace(/<iframe/ig, "< iframe"); 
	  strx = strx.replace(/&lt;iframe/ig, "&lt; iframe"); 
	  strx = strx.replace(/<\/iframe/ig, "< /iframe"); 
	  strx = strx.replace(/&lt;\/iframe/ig, "&lt; /iframe"); 

	  strx = strx.replace(/<object/ig, "< object"); 
	  strx = strx.replace(/&lt;object/ig, "&lt; object"); 
	  strx = strx.replace(/<\/object/ig, "< /object"); 
	  strx = strx.replace(/&lt;\/object/ig, "&lt; /object"); 

	  strx = strx.replace(/<applet/ig, "< applet"); 
	  strx = strx.replace(/&lt;applet/ig, "&lt; applet"); 
	  strx = strx.replace(/<\/applet/ig, "< /applet"); 
	  strx = strx.replace(/&lt;\/applet/ig, "&lt; /applet"); 

	  strx = strx.replace(/ on/ig, " o&shy;n"); 
	  strx = strx.replace(/script:/ig, "script&shy;:"); 
    }


  var idx= strx.indexOf('ViEtDeVdIvId')
  if( idx>=0 ) strx= strx.substring(strx.indexOf('>')+1,strx.lastIndexOf('</DIV>'))

  idx= strx.indexOf('<style>@import url(')
  if( idx>=0 ) strx= strx.substring(0,idx)
  if(CSS[fid] && CSS[fid].href) strx += '<style>@import url("'+CSS[fid].href+'");</style>';


  var defdiv="" ;
  if(FACE[fid]) defdiv += "; FONT-FAMILY:"+ FACE[fid] 
  if(SIZE[fid]) defdiv += "; FONT-SIZE:"+ SIZE[fid]
  if(COLOR[fid]) defdiv += "; COLOR:"+ COLOR[fid]
  if(BCOLOR[fid])defdiv += "; BACKGROUND-COLOR:"+ BCOLOR[fid]
  if(BIMAGE[fid] && BIMAGE[fid]!='about:blank')
	{
     BIMAGE[fid]= BIMAGE[fid].replace(/\\/g,"/"); 
	 defdiv += "; BACKGROUND-IMAGE:url("+ BIMAGE[fid]+")"
    }
  if(defdiv)
	{
	 defdiv = '<DIV id=ViEtDeVdIvId style="POSITION:Relative' + defdiv + '">'
	 strx = defdiv + strx + "</DIV>"
	}


  // From Valerio Santinelli, PostNuke Developer,(http://www.onemancrew.org)
  // removes all Class attributes on a tag eg. '<p class=asdasd>xxx</p>' returns '<p>xxx</p>'    
     //code = code.replace(/<([\w]+) class=([^ |>]*)([^>]*)/gi, "<$1$3")
  // removes all style attributes eg. '<tag style="asd asdfa aasdfasdf" something else>' returns '<tag something else>'
     //code = code.replace(/<([\w]+) style=\"([^\"]*)\"([^>]*)/gi, "<$1$3")
  // gets rid of all xml stuff... <xml>,<\xml>,<?xml> or <\?xml>
     //code = code.replace(/<]>/gi">\\?\??xml[^>]>/gi, "")
  // get rid of ugly colon tags <a:b> or </a:b>
     //code = code.replace(/<\/?\w+:[^>]*>/gi, "")
  // removes all empty <p> tags
     strx = strx.replace(/<p([^>])*>(&nbsp;)*\s*<\/p>/gi,"")
  // removes all empty span tags
     strx = strx.replace(/<span([^>])*>(&nbsp;)*\s*<\/span>/gi,"")
  return strx
}





function addEventToObj()
{
  // Textarea
  var oArr= document.getElementsByTagName("textarea")
  var i=-1
  while(oArr[++i])
   {
     oArr[i].addEventListener("mousedown", doMDown, false);
	 oArr[i].addEventListener("mouseup", doMUp, false);
   }

  // Input
  oArr= document.getElementsByTagName("input")
  i=-1
  while(oArr[++i])
   {
	 oArr[i].addEventListener("mousedown", doMDown, false);
	 oArr[i].addEventListener("mouseup", doMUp, false);
	 if(oArr[i].type!="text") continue
   }
}


addEventToObj();





function editorContents(fid)
{
  var el= document.getElementById(fid).contentWindow;
  if(!el)return

  var strx, strx1;
  if(format[fid]=="HTML")
	{
	  if(curTD)
	   { 
		curTD.setAttribute("bgcolor",oldCOLOR); curTD=null 
	    curTB.setAttribute("bgcolor",oldCOLOR1); curTB=null 
	   }
	  swapMode()
	  strx = objInnerHTML(el);
	  swapMode()
	}
  else
	{
      strx = objInnerHTML(el);
    }


  strx = doCleanCode(strx,fid);

  return strx
}




//NTOAFA: 8oD
function doFormatF(arr)
{
  var el=document.getElementById(fID).contentWindow; 
  if(!el){alert('Please click to select the editor');return}
  el.focus()

  var cmd = new Array();
  cmd = arr.split(',')

  var edit=el.document; 
  if(cmd[0]=='formatBlock')
   {
	 edit.execCommand(cmd[0],false,"<"+cmd[1]+">");
	 if(cmd[1]=='PRE' && format[fID]=="HTML") swapMode();
   }
  else if(cmd[0]=='InsertImage' && !cmd[1] )
   {
	var imgurl= prompt("Enter a Image-URL:", "");
	edit.execCommand(cmd[0],false,imgurl) 
   }
  else if(cmd[1]!=null) edit.execCommand(cmd[0],false,cmd[1]) 
  else edit.execCommand(cmd[0],false,null)

}





function swapCharCode()
{
 var el=document.getElementById(fID).contentWindow; 
 if(!el){alert('Please click to select the editor');return}
 el.focus()

 var eStyle= el.document.body.style;
 var strx;
 if(format[fID]=="HTML")
 {
  swapMode();
  strx= el.document.body.innerHTML
  format[fID]="Text"
 }
 else if(viewm[fID]==0)
 {
  strx= el.document.body.innerHTML
  strx= strx.replace(/\&amp;#/g,"&#")
  el.document.body.innerHTML= strx
  viewm[fID]=1 - viewm[fID]
  return
 }
 else
 {
  strx= el.document.body.innerHTML
 }

 if(viewm[fID]) strx=toUnicode(strx)
 else strx=viewISOCode(strx)
 
 strx= strx.replace(/\&#/g,"&amp;#")
 el.document.body.innerHTML= strx
 
 viewm[fID]=1 - viewm[fID]

}





function swapMode()
{
  var el=document.getElementById(fID).contentWindow; 
  if(!el){alert('Please click to select the editor');return}
  el.focus()

 var eStyle= el.document.body.style	 
 if(format[fID]=="HTML") // view -> source code
 {
  if(curTD)
   { 
	curTD.setAttribute("bgcolor",oldCOLOR); curTD=null 
    curTB.setAttribute("bgcolor",oldCOLOR1); curTB=null 
   }

  FACE[fID]= eStyle.fontFamily
  SIZE[fID]= eStyle.fontSize
  COLOR[fID]= eStyle.color
  BCOLOR[fID]= eStyle.backgroundColor
  BIMAGE[fID]= eStyle.backgroundImage
  BIMAGE[fID]= BIMAGE[fID].substring( BIMAGE[fID].indexOf('(')+1,BIMAGE[fID].indexOf(')') )

  eStyle.fontFamily="";
  eStyle.fontSize="12pt"
  eStyle.fontStyle="normal"
  eStyle.color="black"
  eStyle.backgroundColor="#e0e0f0"
  eStyle.backgroundImage=''

  el.document.body.innerHTML= objInnerText(el);
  format[fID]="Text"
 }
 else // source code -> preview
 {
  eStyle.fontFamily= FACE[fID]
  eStyle.fontSize= SIZE[fID]
  eStyle.color= COLOR[fID]
  eStyle.backgroundColor= BCOLOR[fID]
  eStyle.backgroundImage= "url(" + BIMAGE[fID] + ")"
  

  el.document.body.innerHTML= objInnerHTML(el);
  format[fID]="HTML"
  viewm[fID]=1

  // add event listen
  var tdA= el.document.getElementsByTagName('td')
  for(var i=0; i<tdA.length;i++)
   { tdA[i].addEventListener("click", clickTD, true) }

  // add event listen Div
  addEventToDiv()

 }


}





function objInnerText(el)
{
  var content= el.document.body.innerHTML
  content= content.replace(/<br>\r\n/g,"<br>");
  content= content.replace(/&/g,"&amp;");
  content= content.replace(/\</g,"&lt;");

  content= exchangeTags(content,"&lt;div>","&lt;/div>","",""); //delete trick div

  content= content.replace(/>&lt;table/ig,"><br>&lt;table");
  content= content.replace(/>&lt;tbody/ig,"><br>&lt;tbody");
  content= content.replace(/>&lt;tr/ig,"><br>&lt;tr");
  content= content.replace(/>&lt;td/ig,"><br>&lt;td");

  return content;
}





function objInnerHTML(el) 
{
  var content= el.document.body.innerHTML;

  content= content.replace(/\r\n/g," ");
  content= content.replace(/&amp;lt;/g,"&amp;amp;lt;");
  content= content.replace(/&amp;/g,"&");
  content= content.replace(/&lt;/g,"<");
  content= content.replace(/&gt;/g,">");
  content= content.replace(/&amp;lt;/g,"&lt;");

  content= content.replace(/><br>( *?)<table/ig,"><table");
  content= content.replace(/><br>( *?)<tbody/ig,"><tbody");
  content= content.replace(/><br>( *?)<tr/ig,"><tr");
  content= content.replace(/><br>( *?)<td/ig,"><td");

  return content;
}



/* not work */
function selectAll()
{ 
  return;
  var el=document.getElementById(fID).contentWindow; 
  if(!el){alert('Please click to select the editor');return}
  el.focus()
  el.execCommand('SelectAll',false,null)
}





function highLight(key)
{
  switch(key)
	{  
	  case 48: doFormatF('RemoveFormat'); break; // ctrl+0  no highlight
	  case 49: doFormatF('ForeColor,red'); break; // ctrl+1
	  case 50: doFormatF('ForeColor,green'); break; // ctrl+2
	  case 51: doFormatF('ForeColor,blue'); break; // ctrl+3
      case 52: doFormatF('ForeColor,#00AAFF'); break; // ctrl+4
      case 53: doFormatF('ForeColor,magenta'); break; // ctrl+5
	  case 54: doFormatF('HiliteColor,yellow'); doFormatF('ForeColor,black'); break; // ctrl+6
	  case 55: doFormatF('HiliteColor,cyan'); doFormatF('ForeColor,black'); break; // ctrl+7
	  case 56: doFormatF('HiliteColor,#00FF00'); doFormatF('ForeColor,black'); break; // ctrl+8
	  case 57: doFormatF('HiliteColor,#FF00AA'); doFormatF('ForeColor,white'); break; // ctrl+9
    }
}




function FKPress(e)
{
  var obj= document.getElementById(fID).contentWindow; 
  if(!obj){alert('Please click to select the editor');return}

  if(e.ctrlKey && (e.charCode==99 || e.charCode==120)) return; // Ctrl+C or X

  var key= e.charCode
  var stop= false
  if(e.ctrlKey)
   {
	switch(key)
	{
	 case 98: obj.document.execCommand("Bold", false, null); stop=true; break; // Ctrl+b
	 case 105: obj.document.execCommand("Italic", false, null); stop=true; break; // Ctrl+i
	 case 117: obj.document.execCommand("Underline", false, null); stop=true; break; // Ctrl+u
     case 71: findText(); stop=true ; break; // ctrl+G search
	 case 75: findTextHotKey(0); stop=true; break; // ctrl+K  search forward
	 case 74: findTextHotKey(1); stop=true; break; //ctrl+J  search backward 
	 case 83: if(SYMBOLE!=''){ SmartcardData(); stop=true }; break; // ctrl+S content rewrite
	 case 84: swapMode(); stop=true; break; // ctrl+T swapMode
	 case 48:case 49:case 50:case 51:case 52:
	 case 53:case 54:case 55:case 56:case 57: highLight(key); stop=true; break; // ctrl 0-9 Highlight
	}
   if(stop==true){ e.stopPropagation(); return}
  }
}





/*
You can use for inserting any Html-Tag into Editor
at cursor postion.
*/
function insertHTML(edi,html)
{
  edi.focus();
  
  var div = edi.document.createElement("div");
  div.innerHTML= html
  var child= div.firstChild
  if(!child.nextSibling) insertNodeAtSelection(edi, child)
  else insertNodeAtSelection(edi, div)
}






/*** This function is original from Mozdev.org (s. Midas-Demo)***/
function insertNodeAtSelection(win, insertNode)
{
   var afterNode;

   // get current selection
   var sel = win.getSelection();

   var range = sel.getRangeAt(0);

   sel.removeAllRanges();
   range.deleteContents();

   var container = range.startContainer;
   var pos = range.startOffset;

   // make a new range for the new selection
   range=document.createRange();

   if (container.nodeType==3 && insertNode.nodeType==3) 
	{
        // if we insert text in a textnode, do optimized insertion
        container.insertData(pos, insertNode.nodeValue);

        // put cursor after inserted text
        range.setEnd(container, pos+insertNode.length);
        range.setStart(container, pos+insertNode.length);

   } 
   else 
   {
     if (container.nodeType==3) 
      {
       // when inserting into a textnode
       // we create 2 new textnodes and put the insertNode in between
       var textNode = container;
       container = textNode.parentNode;
       var text = textNode.nodeValue;

       // text before the split
       var textBefore = text.substr(0,pos);
       // text after the split
       var textAfter = text.substr(pos);

       var beforeNode = document.createTextNode(textBefore);
       var afterNode = document.createTextNode(textAfter);

       // insert the 3 new nodes before the old one
       container.insertBefore(afterNode, textNode);
       container.insertBefore(insertNode, afterNode);
       container.insertBefore(beforeNode, insertNode);

        // remove the old node
        container.removeChild(textNode);

      } 
	  else 
	  {
        // else simply insert the node
        afterNode = container.childNodes[pos];
        container.insertBefore(insertNode, afterNode);
      }

      //range.setEnd(afterNode, 0);
      //range.setStart(afterNode, 0);
    }

   // sel.addRange(range);
};





function exchangeTags(text,oOpen,oClose,nOpen,nClose)
{
  var str1, str2, idx, idx1;
  var len1= oOpen.length;
  var len2= oClose.length;

  var oOpen1= oOpen.substring(0,len1-1)
  var chr1= oOpen1.replace(/^(.)/,"$1TrickTag");
  var chr2= oClose.replace(/^(.)/,"$1TrickTag");
  var oOpen2

  while(1)
   {
	str1=''
    while(2)
    {
     idx= text.indexOf(oClose);
	 if(idx<0) break;
	 str2 = text.substring(0,idx);
	 
	 text = text.substr(idx+len2);

	 idx1= str2.lastIndexOf(oOpen1)
	 idx= str2.lastIndexOf(oOpen)

     if(idx1>=0 && idx>=0 && idx1>idx)
	  {
		oOpen2= str2.substring(idx1+len1-1,idx1+len1)
		str1 += str2.substring(0,idx1) + chr1+oOpen2;
		str1 += str2.substr(idx1+len1) + chr2;
	    break;
	  }
	 else if(idx>=0)
	  {
		str1 += str2.substring(0,idx) + nOpen ;
		str1 += str2.substr(idx+len1) + nClose
	  }
	 else str1 += str2 + oClose
	} // while2

   str1 += text
   if(str1.indexOf(oOpen)<0) break
   text=str1; 
  } // while1

  str1= str1.replace(/TrickTag/g,"")
  return str1;
}





function formatDialog()
{
  var urlx= QBPATH + '/dialog.html'

  var twidth= 400;
  var theight=350;
  var tposx= (screen.width- twidth)/2
  var tposy= screen.height- theight - 55
  	    	  
  var newWin1=window.open(urlx,"format","toolbar=no,width="+ twidth+",height="+ theight+ ",directories=no,status=no,scrollbars=yes,resizable=no, menubar=no")
  newWin1.moveTo(tposx,tposy);
  newWin1.focus()
}



//NTOAFA: 8oD
function createLink()
{
  var el=document.getElementById(fID).contentWindow; 
  if(!el){alert('Please click to select the editor');return}

  var urlx= QBPATH + '/createlink.html'

  var twidth= 350;
  var theight=150;
  var tposx= (screen.width- twidth)/2
  var tposy= screen.height- theight - 55
  	    	  
  var newWin1=window.open(urlx,"format","toolbar=no,width="+ twidth+",height="+ theight+ ",directories=no,status=no,scrollbars=yes,resizable=no, menubar=no")
  newWin1.moveTo(tposx,tposy);
  newWin1.focus()

}


function browseImages() {
  //var el= document.getElementById(fID).contentWindow; 
  var el= document.frames[fID]
  if(!el){alert('Please click to select the editor');return}
  el.focus()

  var urlx=  '/administracion/contenidos/selector.php'

  var twidth= 640;
  var theight=480;
  var tposx= (screen.width- twidth)/2
  var tposy= screen.height- theight - 55  
  imagechooser = window.open(urlx, "images","toolbar=no,width="+ twidth+",height="+ theight+ ",directories=no,status=no,scrollbars=yes,resizable=no, menubar=no");

}




function doFormatDialog(file,cmd,arg)
{ 
  var el=document.getElementById(fID).contentWindow; 
  if(!el){alert('Please click to select the editor');return}

  var urlx= QBPATH + '/' + file +"?"+cmd

  var twidth= 350;
  var theight=300;
  var tposx= (screen.width- twidth)/2
  var tposy= screen.height- theight - 55
  	    	  
  var newWin1=window.open(urlx,"format","toolbar=no,width="+ twidth+",height="+ theight+ ",directories=no,status=no,scrollbars=yes,resizable=no, menubar=no")
  newWin1.moveTo(tposx,tposy);
  newWin1.focus()

}




function characters()
{
  var el= document.getElementById(fID).contentWindow; 
  if(!el){alert('Please click to select the editor');return}

  var urlx= QBPATH + '/selchar.html'

  var twidth= 350;
  var theight=400;
  var tposx= (screen.width- twidth)/2
  var tposy= screen.height- theight - 55
  	    	  
  var newWin1=window.open(urlx,"format","toolbar=no,width="+ twidth+",height="+ theight+ ",directories=no,status=no,scrollbars=yes,resizable=no, menubar=no")
  newWin1.moveTo(tposx,tposy);
  newWin1.focus()

}



if(USETABLE) document.writeln('<script src="'+QBPATH+'/tabedit.js"></script>');



// VISUAL=0 : Textarea to Editor after confirmation
// VISUAL=1 : all Textarea to Editor
// VISUAL=2 : change only specific textarea
// VISUAL=3 : all Iframe to Editor
// VISUAL=4 : some specific iframes 
// VISUAL=other : no Visual-Editor, only use Rightmouse-Control
switch(VISUAL)
{
  case 1: changeAllTextareaToEditors(); break;
  case 2: changetoIframeEditor(document.forms[xxx].yyy); break;// please replace xxx=formIndex and yyy=textareaName
  case 3: changeAllIframeToEditors(); break;
  case 4: changeIframeToEditor('contents2'); break;//please replace contents.. = frame id
}





function doMDown(e)
{
 var el= e.currentTarget
 
 var button= e.which

 if(el.type=='text' || el.type=='textarea')
   {
	TXTOBJ=el; fID=''
    if(button>1 && POPWIN==1){ formatDialog();}
   }
}




//*************************************************************/
/********************* not the same *************************/
function doMUp(e)
{
 var el= e.currentTarget

 if(!el.type) return
 if(el.type!='text'&&el.type!='textarea'&&el.type!='password'&&el.type!='file')
  {
	if(!el.name || el.name.substring(0,7)!='QBCNTRL')
	 { 
	   actualize(); 
	   if(el.type != 'select-one' && el.type != 'select-multiple') el.focus(); 
	 }
    return
  }

 var visual=''
 if(typeof(ASKED)=="undefined" && el.type=='textarea' && VISUAL==0)
  { visual=confirm("Use Visual Mode ?"); if(!visual) ASKED=1; }
 	 
 if(visual) changetoIframeEditor(el);

}




function findText()
{
  if(!fID && !TXTOBJ){alert('Please click to select the editor');return}
  if(fID) document.getElementById(fID).contentWindow.focus();
  else TXTOBJ.focus()

  var urlx= './dfindtext.html'
  if(FULLCTRL) urlx= QBPATH + '/dfindtext.html'

  var newWin=window.open(urlx,"find","toolbar=no, width=350px,height=220px,directories=no,status=no,scrollbars=yes,resizable=yes,menubar=no;scroll=no")
  newWin.moveTo(screen.width-500,50);
  newWin.focus()
}



function FMUp(e)
{ 
}


