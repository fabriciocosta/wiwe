<?Php
/* generating the random number */  
$rand = rand(11111,99999);  

$fontfile = "./arial.ttf";

/* generating the image */  
if (isset($_GET['mode']) && $_GET['mode'] == 'image')  
{  
	session_start();  
	$_SESSION['code'] = md5($rand);  
	$img = imagecreate(200, 70);  
	$bkg = imagecolorallocate($img, 80, 130, 80);  
	$color = imagecolorallocate($img, 5, 10, 5);
	$a = floor( $rand/10000 );  
	$b = floor(  ($rand % 10000)/1000 );
	$c = floor(  (($rand % 10000) % 1000 )/100);
	$d = floor(  ((($rand % 10000) % 1000 ) % 100)/10);
	$e = floor(  (((($rand % 10000) % 1000 ) % 100) % 10 ));
	for($i=-200;$i<200;$i+=30) {
		imageline($img, $i-2,0,$i-2+90+(20*$i/20),70,$color);
		imageline($img, $i-1,0,$i-1+90+(20*$i/20),70,$color);
		imageline($img, $i,0,$i+90+(20*$i/20),70,$color);
		imageline($img, $i+1,0,$i+1+90+(20*$i/20),70,$color);
		imageline($img, $i+2,0,$i+2+90+(20*$i/20),70,$color);
		imageline($img, $i+3,0,$i+3+90+(20*$i/20),70,$color);
	}
	imagefttext($img, 46.0, 10.0*(1-rand(1,2)), 23.0, 49.0, $color, $fontfile, $a );
	imagefttext($img, 55.0, -20.0*(1-rand(1,2)), 55.0, 58.0, $color, $fontfile, $b );
	imagefttext($img, 50.0, 5.0*(1-rand(1,2)), 90.0, 52.0, $color, $fontfile, $c );
	imagefttext($img, 70.0, 1.0*(1-rand(1,2)), 120.0, 70.0, $color, $fontfile, $d );
	imagefttext($img, 59.0, 2.0*(1-rand(1,2)), 150.0, 63.0, $color, $fontfile, $e );
	//imageconvolution( $img, array( 0=>array(0.0,7.0,0.0),1=>array(-5.0,0.1,0.0),2=>array(20.0,-4.0,-3.0)  ), 5.0, 0 );
	//imageconvolution( $img, array( 0=>array(30.0,7.0,30.0),1=>array(-5.0,0.1,30.0),2=>array(3.0,-4.0,-3.0)  ), 27.0, 0 );
	

	
	header('Content-type:image/gif');  
	imagegif($img);  
	imagedestroy($img);  
	exit;  
} 

?>