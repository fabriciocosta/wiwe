#!/usr/bin/perl

#! perl

print "Content-type: text/html\n\n";

use CGI ;
my $q= new CGI ;

######## CONFIGURATION BEGIN
my $filesize= 50000 ;
my $pooldir= "../../jscript/uploads"; //always relative to upload.pl
my $poolurl= "http://ngocanh.virtualave.net/jscript/uploads"; //absolute or relative to upload.pl
######## CONFIGURATION END


my $filename= $q->param('file') ;
my $file_name ;

if(!$filename)
 { 
   print "<body bgcolor=#c0c0a0 scroll=no><center><h2>No file selected.</h2>"; 
   print "<br><a href='javascript:history.back()'>Back</a></center></body></html>";
   exit ; 
 }

my @fnA = split(/\\/,$filename) ;
if(! @fnA){ @fnA = split(/\//,$filename) ;}
if($fnA[-1]) { $file_name = $fnA[-1] } ;

my $status= open(INX, "$pooldir/$file_name") ;
if($status)
 { 
   close(INX); 
   print "<body bgcolor=#c0c0a0 scroll=no><center><h2>Filename exists, please choose another name.</h2>"; 
   print "<a href=\"$poolurl/$file_name\" target=nw123>$poolurl/$file_name</a>";
   print "<br><a href='javascript:history.back()'>Back</a></center></body></html>";
   exit ; 
 }


my $fs= (-s $filename) ; ## file size ;
if($filesize ne "" and $fs>$filesize)
 {
   print "<body bgcolor=#c0c0a0 scroll=no><center><h2>File too big: $fs>$filesize</h2>"; 
   print "<a href='javascript:history.back()'>Back</a></center></body></html>";
   exit ; 
 }


my $file_type= $q->uploadInfo($filename)->{'Content-Type'}; ## file_type;

## security solution, if(!image) only for download;
if( $file_type !~ /image\// 
    and $file_name !~ /.js$/i
    and $file_name !~ /.doc$/i
	and $file_name !~ /.exe$/i
	and $file_name !~ /.xls$/i
	and $file_name !~ /.zip$/i
	and $file_name !~ /.tar$/i
	and $file_name !~ /.gz$/i
  ){$file_name .= "~.js";}


open(OUTX, ">$pooldir/$file_name") ;
binmode(OUTX);
my $buffer ;
while(read($filename,$buffer,1024)){  print OUTX $buffer;  }
close(OUTX) ;



###############################################
###############################################
print "<html><head><title>Upload and Insert Local File</title></head><body bgcolor=#c0c0a0 scroll=no>";

my $htmlstr = qq(
<html>
<head><title>Upload and Insert Local File</title></head>
<body bgcolor=#c0c0a0 scroll=no>
<center><b>File "$file_name" was uploaded.<br>You can now access the file with URL: 
<a href="$poolurl/$file_name" target=nw123>$poolurl/$file_name</a>
);


if( $file_type =~ /image\// )
 { $htmlstr .= "<br><a href=\"javascript:window.opener.doFormatF('InsertImage,$poolurl/$file_name')\">"; }
else 
 { $htmlstr .= "<br><a href=\"javascript:window.opener.insertLink('$poolurl/$file_name')\">"; }
$htmlstr .= "Insert Into The Document</a></b>";

$htmlstr .= "<br><b><a href=$poolurl target=nw123>Files-Pool</a></b>";
$htmlstr .= "<br><b><a href='javascript:history.back()'>Back</a></b>";

$htmlstr .= "</center></body></html>";


print "$htmlstr";

################################################
1; #return

