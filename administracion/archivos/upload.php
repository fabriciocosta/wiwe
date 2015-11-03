<?
// ********  CONFIGURATION BEGIN
$filesize= "50000"; //size limit in bytes;
$pooldir= "."; //always relative to upload.php
$poolurl= "http://www.buanlir.com/administracion/archivos"; //always absolute
// ********  CONFIGURATION END


if(!$file_name)
 { 
   echo "<body bgcolor=#c0c0a0 scroll=no><center><h2>No file selected.</h2>"; 
   echo "<br><a href='javascript:history.back()'>Back</a></center></body></html>";
   exit ; 
 }

if(file_exists("$pooldir/$file_name")) 
 {
   echo "<body bgcolor=#c0c0a0 scroll=no><center><h2>Filename exists, please choose another name.</h2>"; 
   echo "<a href=\"$poolurl/$file_name\" target=nw123>$poolurl/$file_name</a>";
   echo "<br><a href='javascript:history.back()'>Back</a></center></body></html>";
   exit ; 
 }

if(($file_size!="") && ($file_size>$filesize)) 
{
   print "<body bgcolor=#c0c0a0 scroll=no><center><h2>File too big: $file_size > $filesize</h2>"; 
   print "<a href='javascript:history.back()'>Back</a></center></body></html>";
   exit ; 
}

// security solution, if(!image) only for download;
if( !preg_match("/image\//",$file_type) 
    && !preg_match("/.js$/i",$file_name)
    && !preg_match("/.doc$/i",$file_name)
	&& !preg_match("/.exe$/i",$file_name)
	&& !preg_match("/.xls$/i",$file_name)
	&& !preg_match("/.zip$/i",$file_name)
	&& !preg_match("/.tar$/i",$file_name)
	&& !preg_match("/.gz$/i",$file_name)
  ) $file_name .= "~.js" ;

//@copy($file, "$pooldir/$file_name") or die("The file couldn't be copied to the server");
rename($file, $_SERVER['DOCUMENT_ROOT']."/tmp/$file_name") or die("The file couldn't be copied to the server");

echo "
<html>
<head><title>Upload and Insert Local File</title></head>
<body bgcolor=#c0c0a0 scroll=no>
<center>
<b>File \"$file_name\" was uploaded.<br>You can now access the file with URL:
<a href=\"$poolurl/$file_name\" target=nw123>$poolurl/$file_name</a>";

if( preg_match("/image\//",$file_type) )
 echo "<br><a href=\"javascript:window.opener.doFormatF('InsertImage,$poolurl/$file_name')\">";
else 
 echo "<br><a href=\"javascript:window.opener.insertLink('$poolurl/$file_name')\">";

echo "Insert Into The Document</a></b>";

echo "<br><b><a href=$poolurl target=nw123>Files-Pool</a></b>";
echo "<br><b><a href='javascript:history.back()'>Back</a></b>";

echo "</center></body></html>";


?>
