/*** Freeware Open Source writen by ngoCanh 2002-05                  */
/*** Original by Vietdev  http://vietdev.sourceforge.net             */
/*** Release 2003-05-04  R8.5                                        */
/*** GPL - Copyright protected                                       */
/*********************************************************************/
function iEditor(idF)
{
  //var obj= document.getElementById(idF).contentWindow;
  var obj= document.frames[idF]
  obj.document.designMode="On"

  obj.document.onmousedown= function(){ TXTOBJ=null; fID=idF;}
  obj.document.onmouseup= FMUp
  obj.document.onkeypress=FKPress
  obj.document.onkeydown=FKDown

  
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

   //setTimeout("document.getElementById('"+idF+"').contentWindow.document.body.innerHTML='"+val+"'",200)
   setTimeout("document.frames['"+idF+"'].document.body.innerHTML='"+val+"'",200)


   TXTOBJ= null
   format[idF]='HTML'
   viewm[idF]=1;

   obj.focus();
}




function changetoIframeEditor(el)
{
   if( navigator.platform!="Win32" ) return null;

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




//////////////////////////////
// for text mode
function doFormat(arr,caret)
{
  var wrd=TXTOBJ.curword.text

  var cmd = new Array();
  cmd = arr.split(',')

  if(!cmd[0]) return 
  if(cmd[0]=='SelectAll') { TXTOBJ.focus(); TXTOBJ.select(); return }
  if(cmd[0]=='Cut') { caret.execCommand("Cut"); return }
  if(cmd[0]=='Copy') { caret.execCommand("Copy"); return }
  if(cmd[0]=='Paste') { caret.execCommand("Paste"); return }

  TXTOBJ.curword=caret.duplicate();
  TXTOBJ.curword.text= cmd[0]+wrd+cmd[1]
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



//NOTAFA: 8oD
//personalizar
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
<img src='IURL/fgcolor.gif' alt='Foreground' class=vdev onclick='selectStyle()'>\
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

  el.outerHTML= str;

}




//NOTAFA 8oD
//actualizarlos!!!!
function selectEmoticon()
{ 
  //var el=document.getElementById(fID).contentWindow;
  var el=document.frames[fID]
  if(!el){alert('Please click to select the editor');return}
  el.focus();
  doFormatDialog('emoticon.html','InsertImage',QBPATH)
}

function selectBgColor()
{ 
  doFormatDialog('selcolor.html',"BackColor",'')
}


function selectFgColor()
{ 
  doFormatDialog('selcolor.html','ForeColor','')
}



//NOTAFA 8oD
//Anularlo, o bien: nueva pagina de upload de archivos simplificadas! por ejemplo
//que:
//A: ya suba el archivo en esa misma seccion.
//B: que sea de tipo imagen por defecto (caso contrario debera subirlo en forma convencional, zip y/o otros) 
//C: que permita subir varias juntas (seleccionando nombre, etc...)
//D: LISTO ----
function doUploadFile()
{
  //var el= document.getElementById(fID).contentWindow;
  var el=document.frames[fID]
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
  //var el= document.getElementById(fID).contentWindow;
  var el=document.frames[fID]
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
  //var el= document.getElementById(fID).contentWindow;
  var el=document.frames[fID]
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


//NOTAFA 8oD
//Si es interno->>>navegador interno
//VER CreateLink()
function insertLink(linkurl)
{
  //var el= document.getElementById(fID).contentWindow;
  var el=document.frames[fID]
  if(!el && !TXTOBJ){alert('Please click a text element');return}

  if(el)
  {
	el.focus();
    var sel = el.document.selection;
	var strx= "<A href='"+linkurl+"' target=nwin>" + linkurl + "</A>"

	var Range = sel.createRange();
	if(!Range.duplicate) return;
	Range.pasteHTML(strx);
  }
  else 
  {
	TXTOBJ.focus();
    var caret= TXTOBJ.document.selection.createRange()
	TXTOBJ.curword=caret.duplicate();
	var strx= "<A href='"+linkurl+"' target=nwin>" + linkurl + "</A>,"
	doFormat(strx,caret)
  }

}





function editDivBorder()
{
  //var el= document.getElementById(fID).contentWindow;
  var el=document.frames[fID]
  if(!el){alert('Please click to select the editor');return}
  el.focus()
  
  var sel = el.document.selection;
  if(sel==null || sel.type!='Control') {alert('Please click once to select a div-layer');return} 

  var Range = sel.createRange();
  if(Range(0).tagName!='DIV') return

  var urlx= './divborder.html'
  if(FULLCTRL) urlx= QBPATH + '/divborder.html'

  var twidth= 0.8*screen.width, theight=215;
  var tposx= (screen.width- twidth)/2
  var tposy= screen.height- theight - 55
  	    	  
  var newWin1=window.open(urlx,"divborder","toolbar=no,width="+ twidth+",height="+ theight+ ",directories=no,status=no,scrollbars=yes,resizable=no, menubar=no")
  newWin1.moveTo(tposx,tposy);
  newWin1.focus()

}




function editDivFilter()
{
  //var el= document.getElementById(fID).contentWindow;
  var el=document.frames[fID]
  if(!el){alert('Please click to select the editor');return}
  el.focus()

  var sel = el.document.selection;
  if(sel==null || sel.type!='Control') {alert('Please click once to select a div-layer');return} 

  var Range = sel.createRange();
  if(Range(0).tagName!='DIV') return

  var urlx= './divfilter.html'
  if(FULLCTRL) urlx= QBPATH + '/divfilter.html'

  var twidth= 0.8*screen.width, theight=210;
  var tposx= (screen.width- twidth)/2
  var tposy= screen.height- theight - 55
  	    	  
  var newWin1=window.open(urlx,"divfilter","toolbar=no,width="+ twidth+",height="+ theight+ ",directories=no,status=no,scrollbars=yes,resizable=no, menubar=no")
  newWin1.moveTo(tposx,tposy);
  newWin1.focus()

}





function findTextHotKey(forward)
{
  if(!fID && !TXTOBJ){alert('Please click to select the editor');return}
  //if(fID) el= document.getElementById(fID).contentWindow;
  if(fID) el=document.frames[fID]
  else el= TXTOBJ
  el.focus();

  var rng = el.document.selection.createRange();
  el.curword=rng.duplicate();

  if(!FWORD && !el.curword.text ){ alert('No find string definition'); return }
  else if(el.curword.text)FWORD= el.curword.text

  if(el.curword.text)
   {
     if(forward==1) rng.moveEnd("character", -1 );  
	 else rng.moveStart("character", 1);  
   }

  if(rng.findText(FWORD,100000,FLAGS+forward)==true)
   { rng.select();  rng.scrollIntoView(); return }

  alert("Finish")
  return

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
/*
   setTimeout("document.getElementById('"+fID+"').contentWindow.document.body.style.fontFamily='"+DFFACE+"'",200)
   setTimeout("document.getElementById('"+fID+"').contentWindow.document.body.style.fontSize='"+DFSIZE+"'",200)
   setTimeout("document.getElementById('"+fID+"').contentWindow.document.body.style.color='"+DCOLOR+"'",200)
   setTimeout("document.getElementById('"+fID+"').contentWindow.document.body.style.backgroundColor='"+DBGCOL+"'",200)
   setTimeout("document.getElementById('"+fID+"').contentWindow.document.body.style.backgroundImage='url("+DBGIMG+")'",200)
   setTimeout("CSS['"+fID+"']=document.getElementById('"+fID+"').contentWindow.document.createStyleSheet('"+DCSS+"')",200)
*/
   setTimeout("document.frames['"+fID+"'].document.body.style.fontFamily='"+DFFACE+"'",200)
   setTimeout("document.frames['"+fID+"'].document.body.style.fontSize='"+DFSIZE+"'",200)
   setTimeout("document.frames['"+fID+"'].document.body.style.color='"+DCOLOR+"'",200)
   setTimeout("document.frames['"+fID+"'].document.body.style.backgroundColor='"+DBGCOL+"'",200)
   setTimeout("document.frames['"+fID+"'].document.body.style.backgroundImage='url("+DBGIMG+")'",200)
   setTimeout("CSS['"+fID+"']=document.frames['"+fID+"'].document.createStyleSheet('"+DCSS+"')",200)

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
/*
  setTimeout("document.getElementById('"+fID+"').contentWindow.document.body.style.fontFamily='"+retArr[0]+"'",200)
  setTimeout("document.getElementById('"+fID+"').contentWindow.document.body.style.fontSize='"+retArr[1]+"'",200)
  setTimeout("document.getElementById('"+fID+"').contentWindow.document.body.style.backgroundColor='"+retArr[2]+"'",200)
  setTimeout("document.getElementById('"+fID+"').contentWindow.document.body.style.color='"+retArr[3]+"'",200)
  setTimeout("document.getElementById('"+fID+"').contentWindow.document.body.style.backgroundImage='url("+retArr[4]+")'",200)
  setTimeout("CSS['"+fID+"']=document.getElementById('"+fID+"').contentWindow.document.createStyleSheet('"+retArr[5]+"')",200)
*/
  setTimeout("document.frames['"+fID+"'].document.body.style.fontFamily='"+retArr[0]+"'",200)
  setTimeout("document.frames['"+fID+"'].document.body.style.fontSize='"+retArr[1]+"'",200)
  setTimeout("document.frames['"+fID+"'].document.body.style.backgroundColor='"+retArr[2]+"'",200)
  setTimeout("document.frames['"+fID+"'].document.body.style.color='"+retArr[3]+"'",200)
  setTimeout("document.frames['"+fID+"'].document.body.style.backgroundImage='url("+retArr[4]+")'",200)
  setTimeout("CSS['"+fID+"']=document.frames['"+fID+"'].document.createStyleSheet('"+retArr[5]+"')",200)

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

 fobj[idA[1]].value= strx

}	


function removeSpans() {
  var el= document.frames[fID]
  if(!el){alert('Please click to select the editor');return}
  el.focus()
  
  var strx="";
  
  if(el)
  {
	el.focus();
    var sel = el.document.selection;	 

	//sel.clear();
	
	var Range = sel.createRange();
	if(!Range.duplicate) return;
	var	pel = Range.parentElement();
	if (pel!=null) {
		if (pel.tagName=='SPAN') {
			if (pel.innerHTML == Range.text) {
				 //pel.className = _style;
				 strx = '<span class=\"'+_style+'\">'+Range.text+'</span>';
				 Range.moveToElementText(pel);					
                 Range.pasteHTML(strx);
				 return;
			}
		}
	}
  }
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





//////////////////////////////////////////////////////////////////////


function addEventToObj()
{
  // addEventListener -> all Textarea
  var oArr= document.getElementsByTagName("textarea")
  var i=-1;
  while(oArr[++i])
   {
	 oArr[i].onmousedown=doMDown
	 oArr[i].onmouseup= doMUp
	 oArr[i].onkeydown=doKDown
   }

  // addEventListener -> all Input
  oArr= document.getElementsByTagName("input")
  i=-1
  while(oArr[++i])
   {
	 oArr[i].onmousedown=doMDown
	 oArr[i].onmouseup= doMUp
	 if(oArr[i].type!="text") continue
	 oArr[i].onkeydown=doKDown
   }
}


addEventToObj();







function editorContents(fid)
{
  //var el= document.getElementById(fid).contentWindow;
  var el= document.frames[fid]
  if(!el)return

  var strx, strx1;
  if(format[fid]=="HTML")
	{
	  if(curTD)
	   { 
   	     curTD.runtimeStyle.backgroundColor = "";
		 curTD.runtimeStyle.color = "";
		 curTD=null 
		 curTB.runtimeStyle.backgroundColor = "";
		 curTB.runtimeStyle.color = "";
		 curTB=null 
	   }
	  strx= el.document.body.innerHTML
	  strx1= el.document.body.innerText
	}
  else
	{
	  strx = el.document.body.innerText
	  strx1=el.document.body.innerHTML
    }
  if(strx1=='' && strx.indexOf('<IMG')<0 && strx.indexOf('<HR')<0 ) return ''


  strx = doCleanCode(strx,fid);

  return strx
}


//NOTAFA: 8oD
//Cambiar esta funcion....
function doFormatF(arr)
{
  //var el=document.getElementById(fID).contentWindow;
  var el= document.frames[fID]
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
	 alert('Please notice:\nThe "Picture Source" in following Dialog must be a URL, not a local address.'); 
	 edit.execCommand(cmd[0],true,null) 
   }
   else if(cmd[0]=='RemoveFormat') {
   	removeSpans();
   	edit.execCommand(cmd[0],false,null);
   }
  else if(cmd[1]!=null) edit.execCommand(cmd[0],false,cmd[1]) 
  else edit.execCommand(cmd[0],false,null)

}




function swapCharCode()
{
 //var el=document.getElementById(fID).contentWindow; 
 var el= document.frames[fID]
 if(!el){alert('Please click to select the editor');return}
 el.focus()

 var eStyle= el.document.body.style;
 var strx;
 if(format[fID]=="HTML")
 {
  swapMode();
  strx= el.document.body.innerText
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
  strx= el.document.body.innerText
 }

 if(viewm[fID]) strx=toUnicode(strx)
 else strx=viewISOCode(strx)
 
 el.document.body.innerText=strx

 viewm[fID]=1 - viewm[fID]
}



function swapMode()
{
 //var el=document.getElementById(fID).contentWindow; 
 var el= document.frames[fID]
 if(!el){alert('Please click to select the editor');return}
 el.focus()

 var MARK= "ViEtDeVtRiCk"
 var selType=el.document.selection.type

 if(selType!="Control")
 {
   var caret=el.document.selection.createRange();
   el.curword=caret.duplicate();
   var selwrd= el.curword.text
   el.curword.text = selwrd + MARK;
 }

 var eStyle= el.document.body.style
	 
 if(format[fID]=="HTML")
 {
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
  var innerHTML= el.document.body.innerHTML
  var reg= eval("/"+MARK+"/ig");
  var res= innerHTML.match(reg);
  if(res)
   for(var i=0; i<res.length-1; i++)
	 innerHTML= innerHTML.replace(res[i],"") 

  el.document.body.innerText= innerHTML;
  format[fID]="Text"
 }
 else
 {
  eStyle.fontFamily= FACE[fID]
  eStyle.fontSize= SIZE[fID]
  eStyle.color= COLOR[fID]
  eStyle.backgroundColor= BCOLOR[fID]
  eStyle.backgroundImage= "url(" + BIMAGE[fID] + ")"

  var temp=el.document.body.innerText
  el.document.body.innerHTML= temp;

  format[fID]="HTML"
  viewm[fID]=1

  // addeventlistener for table-cell
  var tdA= el.document.getElementsByTagName('td')
  for(var i=0; i<tdA.length;i++)
   { tdA[i].attachEvent("onclick", clickTD) }

 }


 if(selType!="Control")
 {
  caret = el.document.selection.createRange();
  var found= caret.findText(MARK,100000,5) // backward
  if(found==false) 
   found= caret.findText(MARK,100000,4) // foreward

  if(found==false && format[fID]=="HTML") 
   {
     var strx= el.document.body.innerHTML
	 strx= strx.replace(/ViEtDeVtRiCk/ig,"");
	 el.document.body.innerHTML= strx
	 return;
   }

  caret.select();
  el.curword=caret.duplicate();
  el.curword.text = '' ;  // erase trick selection 

  if(selwrd!="") caret.findText(selwrd,100000,5); // real selection
  caret.select();  caret.scrollIntoView(); 
 }

}




function selectAll()
{ 
  //var el=document.getElementById(fID).contentWindow; 
  var el= document.frames[fID]
  if(!el){alert('Please click to select the editor');return}
  el.focus()
  
  var s=el.document.body.createTextRange()
  s.execCommand('SelectAll',false,null)
}





function highLight(key)
{
  function doDefFormat()
	{
     //var el= document.getElementById(fID).contentWindow;
	 var el= document.frames[fID]
     el.focus();
	 var rng = el.document.selection.createRange();
     rng.moveEnd("character", 1);
	 rng.select();
	 el.curword=rng.duplicate();
	 if(el.curword.text=='') doFormatF('RemoveFormat'); 
	 else
	  {
	    rng.moveEnd("character", -1);
   	    rng.select();
		doFormatF('ForeColor,'); doFormatF('BackColor,'); 
	  }
    }

  switch(key)
	{  
	  case 48: doDefFormat(); break; // ctrl+0  no highlight
	  case 49: doFormatF('ForeColor,red'); break; // ctrl+1
	  case 50: doFormatF('ForeColor,green'); break; // ctrl+2
	  case 51: doFormatF('ForeColor,blue'); break; // ctrl+3
      case 52: doFormatF('ForeColor,#00AAFF'); break; // ctrl+4
      case 53: doFormatF('ForeColor,magenta'); break; // ctrl+5
	  case 54: doFormatF('BackColor,yellow'); doFormatF('ForeColor,black'); break; // ctrl+6
	  case 55: doFormatF('BackColor,cyan'); doFormatF('ForeColor,black'); break; // ctrl+7
	  case 56: doFormatF('BackColor,#00FF00'); doFormatF('ForeColor,black'); break; // ctrl+8
	  case 57: doFormatF('BackColor,#FF00AA'); doFormatF('ForeColor,white'); break; // ctrl+9
    }
}



function FKPress()
{
}




function FKDown()
{
  //var el= document.getElementById(fID).contentWindow; 
  var el= document.frames[fID]
  var event= el.event

  if(!el ||!event){alert('Please click to select the editor');return}
  if(event.altKey) return;

  var key= event.keyCode
  var shft= event.shiftKey
  var ctrl= event.ctrlKey


  if(RETURNNL && !shft && key==13){ insertNewline(el); return false }
  else if(RETURNNL && key==13){ insertNewParagraph(el); return false }

  if(ctrl && key==71){ findText(); return false }  // ctrl+G search
  else if(ctrl && key==75){ findTextHotKey(0); return false } // ctrl+K  search forward
  else if(ctrl && key==74){ findTextHotKey(1); return false } // ctrl+J  search backward 
  else if(ctrl && key==83 && SYMBOLE!=''){ SmartcardData(); return false } // ctrl+S content rewrite
  else if(ctrl && key==84){ swapMode(); return false } // ctrl+T swapMode
  else if(ctrl && (key>=48 && key<=57)){ highLight(key); return false } // ctrl+1 Highlight
}







function insertHTML(el,html)
{
  var sel = el.document.selection;
  if(sel.type=="Control") return 

  var Range = sel.createRange();
  if(!Range.duplicate) return;
  var wrd='' ;
  el.curword=Range.duplicate();
  wrd= el.curword.text;

  var Range = sel.createRange();
  if(!Range.duplicate) return;
  Range.pasteHTML(html);
}



function insertDivLayer()
{
  //var el= document.getElementById(fID).contentWindow; 
  var el= document.frames[fID]
  if(!el){alert('Please click to select the editor');return}
  el.focus()
  
  var sel = el.document.selection;
  if(sel==null) return

  var Range = sel.createRange();
  var wrd='' ;

  if(sel.type!="Control")
  {
  	if(!Range.duplicate) return;
  	el.curword=Range.duplicate();
  	wrd= el.curword.text;
	if(wrd=='') wrd="I'm a DIV-Layer. Select me and click the button once more to change properties. Or doubleclick me to change the text."
	var arr= "<DIV style='position:relative; width:150px; height:100px; font-family:Arial; font-size:12px; background-color:#f0fdd0; border:1 solid'>"+ wrd + "</DIV>" ;
	Range.pasteHTML(arr);
	return
  }  

  if(Range(0).tagName!='DIV') return

  var urlx= './divstyle.html'
  if(FULLCTRL) urlx= QBPATH + '/divstyle.html'

  var twidth= 0.8*screen.width, theight=190;
  var tposx= (screen.width- twidth)/2
  var tposy= screen.height- theight - 55
  	    	  
  var newWin1=window.open(urlx,"divstyle","toolbar=no,width="+ twidth+",height="+ theight+ ",directories=no,status=no,scrollbars=yes,resizable=no, menubar=no")
  newWin1.moveTo(tposx,tposy);
  newWin1.focus()

}




function formatDialog()
{
  TXTOBJ.focus();
  var caret=TXTOBJ.document.selection.createRange()
  TXTOBJ.curword=caret.duplicate();
  
  var y = screen.height -parseInt('27em')*14 - 30 
  var feature = "font-family:Arial;font-size:10pt;dialogWidth:30em;dialogHeight:27em;dialogTop:"+y
      feature+= ";edge:sunken;help:no;status:no"

  var dialog= QBPATH+'/dialog.html'
  var arr= showModalDialog(dialog, "", feature); //////////////////////////////////////////
  if(arr==null) return ;

  if(arr=='VISUAL') changetoIframeEditor(TXTOBJ);
  else doFormat(arr,caret)
}



//NOTAFA 8oD
//Modificar esta funcion!!! los links pueden ser externos
//o bien internos de la pagina: alli deberiamos tener unmini-navegador para buscar un link especifico
//lo ideal seria diseñar (de una vez por todas) el "arbol" de secciones
//se elige la seccion del arbol, luego se elige el tipo de contenido (nota,referencia,etc...)
//y se elige de los resultados, tambien podria tener un mini-buscador, aunque seria mejor
//llegado el caso que busque desde el del site.
function createLink()
{
  //var el= document.getElementById(fID).contentWindow; 
  var el= document.frames[fID]
  if(!el){alert('Please click to select the editor');return}
  el.focus()

  var urlx= QBPATH + '/' + 'createlink.html'

  //imagechooser es un objeto externo usado por el navegador de imagenes
  imagechooser = showModalDialog(urlx, el, 
	  "font-family:Verdana;font-size:12;dialogWidth:30em;dialogHeight:14em; edge:sunken;help:no;status:no");

}

function applyStyle(_style) {
  var el= document.frames[fID]
  if(!el){alert('Please click to select the editor');return}
  el.focus()
  
  var strx="";
  
  if(el)
  {
	el.focus();
    var sel = el.document.selection;	 

	var Range = sel.createRange();
	if(!Range.duplicate) return;
	var	pel = Range.parentElement();
	if (pel!=null) {
		if (pel.tagName=='SPAN') {
			if (pel.innerHTML == Range.text) {
				 pel.className = _style;
				 return;
			} else {
			strx = '<span class=\"'+_style+'\">'+Range.text+'</span>';
			Range.pasteHTML(strx);
			}
		} else {
		strx = '<span class=\"'+_style+'\">'+Range.text+'</span>';
		Range.pasteHTML(strx);
		}
	} else {
		strx = '<span class=\"'+_style+'\">'+Range.text+'</span>';
		Range.pasteHTML(strx);
	}
  }
}

function selectStyle() {
  //var el= document.getElementById(fID).contentWindow; 
  var el= document.frames[fID]
  if(!el){alert('Please click to select the editor');return}
  el.focus()

  //NOTAFA:8oD
  //por cada seccion se podria tener un estilo de css elegido por defecto....
  //es una suggestion, aca dejo el cosito de la seccion
  var seccionid = document.confirmar._e_ID_SECCION.options[document.confirmar._e_ID_SECCION.selectedIndex].value;
  var urlx=  '/administracion/include/teditor/styles.html';
  
  var twidth= 640;
  var theight=480;
  var tposx= (screen.width- twidth)/2
  var tposy= screen.height- theight - 55 
  stylechooser = window.open(urlx, "images","toolbar=no,width="+ twidth+",height="+ theight+ ",directories=no,status=no,scrollbars=yes,resizable=no, menubar=no");

}

function insertarimagenes(campo,_align) {

  var el= document.frames[fID]
  if(!el){alert('Please click to select the editor');return}
  el.focus()
  
	var cb;
	var tipo;
	var img;
	var alttext;
	var id;
	var i;
	var strx="";
	
	//imagechooser es un objeto externo usado por el navegador de imagenes	
	for(i=1;i<=imagechooser.nimagenes;i++) {							
		eval('cb = imagechooser.selector.cb'+i+'.checked');
		eval('img = imagechooser.selector.img'+i+'.value');		
		eval('alttext = imagechooser.selector.alt'+i+'.value');		
		eval('id = imagechooser.selector.id'+i+'.value');		
		eval('tipo = imagechooser.selector.tipo'+i+'.value');
					
		if (cb) {//lo insertamos
			alert("insertando imagen: " + img);
			if (tipo == 'swf') {
				strx = strx + '<embed pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" src="'+img+'" type="application/x-shockwave-flash" width="80" quality="best" play="true">';
			} else if (tipo=='jpg' || tipo =='gif') {
				strx = strx + '<img id="'+id+'" align="'+_align+'" src=\"'+img+'\" alt="'+alttext+'" border=0>';			
			}
			if (_align=="baseline") { 
				strx = strx + '<br><span class=comentario>'+alttext+'</span>'; 
			}
		}		
	}
	
  if(el)
  {
	el.focus();
    var sel = el.document.selection;	 

	var Range = sel.createRange();
	if(!Range.duplicate) return;
	Range.pasteHTML(strx);
  }
  
  imagechooser.close();
  /*
  else 
  {
	TXTOBJ.focus();
    var caret= TXTOBJ.document.selection.createRange()
	TXTOBJ.curword=caret.duplicate();
	var strx= "<A href='"+linkurl+"' target=nwin>" + linkurl + "</A>,"
	doFormat(strx,caret)
  }
  */  
  	
}

function browseImages() {
  //var el= document.getElementById(fID).contentWindow; 
  var el= document.frames[fID]
  if(!el){alert('Please click to select the editor');return}
  el.focus()

  var seccionid = document.confirmar._e_ID_SECCION.options[document.confirmar._e_ID_SECCION.selectedIndex].value;
  var urlx=  '/administracion/contenidos/selector.php?_campo_=_e_CUERPO&_seccion_='+seccionid ;
  
  var twidth= 640;
  var theight=480;
  var tposx= (screen.width- twidth)/2
  var tposy= screen.height- theight - 55 
  imagechooser = window.open(urlx, "images","toolbar=no,width="+ twidth+",height="+ theight+ ",directories=no,status=no,scrollbars=yes,resizable=no, menubar=no");


}




function doFormatDialog(file,cmd,arg)
{ 
  var urlx= QBPATH + '/' + file

  var el=document.frames[fID];
  if(!el){alert('Please click to select the editor');return}

  var arr=showModalDialog(urlx, arg, "font-family:Verdana;font-size:12;dialogWidth:30em;dialogHeight:30em; edge:sunken;help:no;status:no");
  if(arr !=null) doFormatF(cmd+','+arr)
}



function characters()
{
  var el=document.frames[fID];
  if(!el){alert('Please click to select the editor');return}
  el.focus();

  var sel = el.document.selection;
  if(sel.type=="Control") return 

  var urlx= QBPATH + '/selchar.html'

  var arr=showModalDialog(urlx, '', "font-family:Verdana;font-size:12;dialogWidth:30em;dialogHeight:34em; edge:sunken;help:no;status:no");
  if(arr==null) return

  var arrA = arr.split(';QuIcKbUiLd;')

  var strx= "<FONT FACE='" + arrA[0] + "'>" + arrA[1] + "</FONT>"

  var Range = sel.createRange();
  if(!Range.duplicate) return;
  Range.pasteHTML(strx);

}




///////////////////////////////////////////////////////////////////////
if(USETABLE) document.writeln('<script src="'+QBPATH+'/tabedit.js"></script>');
if(RETURNNL) document.writeln('<script src="'+QBPATH+'/returnnl.js"></script>');
document.writeln('<script src="'+QBPATH+'/recover.js"></script>');



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
 var el;
 el= event.srcElement
 
 var button= event.button

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
 el= event.srcElement

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


////////////////////////////
// noch for Moz
function doKDown(e)
{
  var ctrl= event.ctrlKey
  if(!ctrl) return;

  var el=event.srcElement 
  if(el.type!='text' && el.type!='textarea') return
  TXTOBJ=el; fID='';

  var key= event.keyCode
  if(ctrl && key==71) { findText(); return false }  // ctrl+G search
  else if(ctrl && key==75){ findTextHotKey(0); return false } // ctrl+K  search forward
  else if(ctrl && key==74){ findTextHotKey(1); return false } // ctrl+J  search backward 
  else if(ctrl && key==83 && SYMBOLE!=''){ SmartcardData(); return false } // ctrl+S content rewrite
 
}




function findText()
{
  if(!fID && !TXTOBJ){alert('Please click to select the editor');return}
  //if(fID) document.getElementById(fID).contentWindow.focus();
  if(fID) document.frames[fID].focus()
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


