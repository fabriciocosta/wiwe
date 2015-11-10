<?Php
/* generating the random number */  
$rand = rand(11111,99999);  

$fontfile = "./arial.ttf";

/* generating the image */  
if (isset($_GET['mode']) && $_GET['mode'] == 'image')  
{  
	session_start();  
	$_SESSION['code'] = md5($rand);  
	$img = imagecreatetruecolor(200, 70);  
	
	//$bkg = imagecolorallocate($img, 80, 130, 80);  
	$bkg = imagecolorallocate($img, 0, 0, 0);
	//$color = imagecolorallocate($img, 5, 10, 5);
	$color = imagecolorallocate($img, 100, 100, 100);
	
	// Draw a white rectangle
	imagefilledrectangle( $img, 0, 0, 200, 70, $bkg );
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
	if (!function_exists("imageconvolution")) {

		function imageconvolution($src, $filter, $filter_div, $offset){
		    if ($src==NULL) {
		        return 0;
		    }
		   
		    $sx = imagesx($src);
		    $sy = imagesy($src);
		    $srcback = ImageCreateTrueColor ($sx, $sy);
		    ImageCopy($srcback, $src,0,0,0,0,$sx,$sy);
		   
		    if($srcback==NULL){
		        return 0;
		    }
		       
		    #FIX HERE
		    #$pxl array was the problem so simply set it with very low values
		    $pxl = array(1,1);
		    #this little fix worked for me as the undefined array threw out errors
		
		    for ($y=0; $y<$sy; ++$y){
		        for($x=0; $x<$sx; ++$x){
		            $new_r = $new_g = $new_b = 0;
		            $alpha = imagecolorat($srcback, $pxl[0], $pxl[1]);
		            $new_a = $alpha >> 24;
		           
		            for ($j=0; $j<3; ++$j) {
		                $yv = min(max($y - 1 + $j, 0), $sy - 1);
		                for ($i=0; $i<3; ++$i) {
		                        $pxl = array(min(max($x - 1 + $i, 0), $sx - 1), $yv);
		                    $rgb = imagecolorat($srcback, $pxl[0], $pxl[1]);
		                    $new_r += (($rgb >> 16) & 0xFF) * $filter[$j][$i];
		                    $new_g += (($rgb >> 8) & 0xFF) * $filter[$j][$i];
		                    $new_b += ($rgb & 0xFF) * $filter[$j][$i];
		                }
		            }
		
		            $new_r = ($new_r/$filter_div)+$offset;
		            $new_g = ($new_g/$filter_div)+$offset;
		            $new_b = ($new_b/$filter_div)+$offset;
		
		            $new_r = ($new_r > 255)? 255 : (($new_r < 0)? 0:$new_r);
		            $new_g = ($new_g > 255)? 255 : (($new_g < 0)? 0:$new_g);
		            $new_b = ($new_b > 255)? 255 : (($new_b < 0)? 0:$new_b);
		
		            $new_pxl = ImageColorAllocateAlpha($src, (int)$new_r, (int)$new_g, (int)$new_b, $new_a);
		            if ($new_pxl == -1) {
		                $new_pxl = ImageColorClosestAlpha($src, (int)$new_r, (int)$new_g, (int)$new_b, $new_a);
		            }
		            if (($y >= 0) && ($y < $sy)) {
		                imagesetpixel($src, $x, $y, $new_pxl);
		            }
		        }
		    }
		    imagedestroy($srcback);
		    return 1;
		}
		
	}
	
	imageconvolution( $img, array( 0=>array(0.0,7.0,0.0),1=>array(-5.0,0.1,0.0),2=>array(20.0,-4.0,-3.0)  ), 5.0, 0 );
	imageconvolution( $img, array( 0=>array(30.0,7.0,30.0),1=>array(-5.0,0.1,30.0),2=>array(3.0,-4.0,-3.0)  ), 27.0, 0 );
	//imageconvolution( $img, array( array(0.0,0.0,0.0),array(0.0,1.0,0.0),array(0.0,0.0,0.0)  ), 1.0, 0.0 );
	
	//imagefilter( $img, IMG_FILTER_EDGEDETECT );
	
	header('Content-type:image/jpeg');  
	imagejpeg($img);  
	imagedestroy($img);  
	exit;  
} 

?>