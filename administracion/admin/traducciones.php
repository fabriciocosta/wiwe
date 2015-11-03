<div id="div_traducciones" >
TRADUCCIONES
<br>

<?

if (1==2) {

global $newcode;
global $newlangname;
global $oldcode;
global $showlang;
$showlang = "si";

global $newaliasname;
global $oldalias;


global $CMultiLang;
/*
<?Php
global $__modulo__;

$CMultiLang = new CMultiLang( array('español'=>'SP', 'français'=>'FR', 'deutsch'=>'DE') , true, "EN" );
$CMultiLang->SetBrowserAuto(true);
if ($__modulo__=="admin" || $__modulo__=="config") $CMultiLang->SelectLang( $GLOBALS['_LANG_'] );

?>
*/

function RemoveCode( $_trans_file_, $_code_ ) {

	$_code_ = strtoupper(trim($_code_));
	$Lignes = file($_trans_file_);
			
	$ln = $Lignes[0];
	$lnx = explode( ";" , $ln );
	$coma = "";
	$headers = "";
	$filestring = "";

	//***********************************
	//  HEADERS
	//***********************************
	$code_founded = false;
	$codei = 0;
		
	for( $i = 0; $i < count($lnx); $i++) {
		
		$cde = $lnx[$i];
		
		//find the first one and stop
		if (!$code_founded && strtoupper(trim($cde))==trim($_code_)) {
			$code_founded = true;
			$codei = $i;
		}
		else {
			$headers.= $coma.trim(  $lnx[$i]  );
			$coma = ';';
		}
		
	}
	echo "codei:".$codei;
	$filestring.= $headers."\n";
	
	//***********************************
	//  CODES
	//***********************************	
	
	for( $cn = 1; $cn < count($Lignes); $cn++ ) {
	 	$ln = $Lignes[$cn];
	 	$lnx = explode( ";" , $ln );
	 	
	 	$group = strtoupper(trim($lnx[0]));
	 	$code = strtoupper(trim($lnx[1]));
	 	
	 	$coma = "";
	 	$row = "";
	 	$lnxi = 0;
	 	foreach($lnx as $value) {
	 		if ( ($code_founded && $codei!=$lnxi) || !$code_founded) {
		 		$row.= $coma.trim($value);
		 		$coma = ';';
	 		}
	 		$lnxi++;
	 	}
	 	
	 	$filestring.= $row."\n";
	}	
	
	if ($code_founded) {
		
	} 
	
	copy( $_trans_file_, $_trans_file_.date("d-m-y h-i-s").".bk.csv");
	$file = fopen ( $_trans_file_, "w");
	if ($file) {
		fwrite( $file, $filestring);
		fclose ( $file );
		return true;
	} else return false;	
	
}
	
function AddCode( $_trans_file_, $_code_ ) {
	$_code_ = strtoupper(trim($_code_));
	$Lignes = file($_trans_file_);
			
	$ln = $Lignes[0];
	$lnx = explode( ";" , $ln );
	$coma = "";
	$headers = "";
	$codes = array();
	$filestring = "";

	//***********************************
	//  HEADERS
	//***********************************
	$code_founded = false;
		
	for( $i = 0; $i < count($lnx); $i++) {
		$cde = $lnx[$i];
		if ($cde==$_code_) $code_founded = true;
		$headers.= $coma.trim(  $lnx[$i]  );
		if ($i>=2) {
			$codes[trim(  $lnx[$i]  )] = $i;
			//echo "<br>".trim(  $lnx[$i]  )." > > ".$i;
		}
		$coma = ';';
	}
	if (!$code_founded) {
		$headers.= $coma.$_code_;
		$i++;
	}
	
	$filestring.= $headers."\n";
	
	//***********************************
	//  CODES
	//***********************************	
	
	for( $cn = 1; $cn < count($Lignes); $cn++ ) {
	 	$ln = $Lignes[$cn];
	 	$lnx = explode( ";" , $ln );
	 	
	 	$group = strtoupper(trim($lnx[0]));
	 	$code = strtoupper(trim($lnx[1]));
	 	
	 	$coma = "";
	 	$row = "";
	 	$lnxi = 0;
	 	foreach($lnx as $value) {
	 		if ($lnxi>=2) {
			 	if ( ($trans_group == $group) && ($code == $trans_code ) && 
			 		( $codes[trim($trans_lang)]==$lnxi )) {		 		
			 		$value = trim($trans_text);		 		
			 	} 		
	 		}
	 		$row.= $coma.trim($value);
	 		$coma = ';';
	 		//echo $coma.trim($value).":[".$lnxi."] "; 
	 		$lnxi++;
	 	}
	 	if ($lnxi<$i) {
	 		//we add a copy of the last value just to put something on the new language definition!!
	 		//the first value (the first code)
	 		$row.= $coma.trim($lnx[2]);
	 	}
	 	$filestring.= $row."\n";
	}	
	
	copy( $_trans_file_, $_trans_file_.date("d-m-y h-i-s").".bk.csv");
	$file = fopen ( $_trans_file_, "w");
	if ($file) {
		fwrite( $file, $filestring);
		fclose ( $file );
		return true;
	} else return false;	
	
}


function AddAlias( $_trans_file_, $_alias_ ) {
	$_alias_ = strtoupper(trim(str_replace(" ","",$_alias_)));
	$Lignes = file($_trans_file_);
			
	$ln = $Lignes[0];
	$lnx = explode( ";" , $ln );
	$coma = "";
	$headers = "";
	$codes = array();
	$filestring = "";

	//***********************************
	//  HEADERS
	//***********************************
	$alias_founded = false;
		
	for( $i = 0; $i < count($lnx); $i++) {
		$cde = $lnx[$i];
		$headers.= $coma.trim(  $lnx[$i]  );
		if ($i>=2) {
			$codes[trim(  $lnx[$i]  )] = $i;
			//echo "<br>".trim(  $lnx[$i]  )." > > ".$i;
		}
		$coma = ';';
	}
	
	$filestring.= $headers."\n";
	
	//***********************************
	//  CODES
	//***********************************	
	
	for( $cn = 1; $cn < count($Lignes); $cn++ ) {
	 	$ln = $Lignes[$cn];
	 	$lnx = explode( ";" , $ln );
	 	
	 	$group = strtoupper(trim($lnx[0]));
	 	$code = strtoupper(trim($lnx[1]));
	 	
	 	$coma = "";
	 	$row = "";
	 	$lnxi = 0;
	 	foreach($lnx as $value) {
	 		if ($lnxi>=2) {
			 	if ( ($trans_group == $group) && ($code == $trans_code ) && 
			 		( $codes[trim($trans_lang)]==$lnxi )) {		 		
			 		$value = trim($trans_text);		 		
			 	} 		
	 		}
	 		$row.= $coma.trim($value);
	 		$coma = ';';
	 		//echo $coma.trim($value).":[".$lnxi."] "; 
	 		$lnxi++;
	 	}
	 	if ($code==$_alias_) {
	 		$alias_founded = true;
	 	}
	 	$filestring.= $row."\n";
	}	
	
	if (!$alias_founded) {
		$row = ";".$_alias_;
		for($i=2; $i<count($lnx);$i++) {
			$row.= ";"."-";
		}
		$filestring.= $row."\n";
	}
	
	copy( $_trans_file_, $_trans_file_.date("d-m-y h-i-s").".bk.csv");
	$file = fopen ( $_trans_file_, "w");
	if ($file) {
		fwrite( $file, $filestring);
		fclose ( $file );
		return true;
	} else return false;		
}

function RemoveAlias( $_trans_file_, $_alias_ ) {
	$_alias_ = strtoupper(trim(str_replace(" ","",$_alias_)));
	$Lignes = file($_trans_file_);
			
	$ln = $Lignes[0];
	$lnx = explode( ";" , $ln );
	$coma = "";
	$headers = "";
	$codes = array();
	$filestring = "";

	//***********************************
	//  HEADERS
	//***********************************
	$alias_founded = false;
		
	for( $i = 0; $i < count($lnx); $i++) {
		$cde = $lnx[$i];
		$headers.= $coma.trim(  $lnx[$i]  );
		if ($i>=2) {
			$codes[trim(  $lnx[$i]  )] = $i;
			//echo "<br>".trim(  $lnx[$i]  )." > > ".$i;
		}
		$coma = ';';
	}
	
	$filestring.= $headers."\n";
	
	//***********************************
	//  CODES
	//***********************************	
	
	for( $cn = 1; $cn < count($Lignes); $cn++ ) {
	 	$ln = $Lignes[$cn];
	 	$lnx = explode( ";" , $ln );
	 	
	 	$group = strtoupper(trim($lnx[0]));
	 	$code = strtoupper(trim($lnx[1]));
	 	
	 	$coma = "";
	 	$row = "";
	 	$lnxi = 0;
	 	if (trim($lnx[1])==$_alias_) {
	 		$alias_founded = true;
	 	} else {
		 	foreach($lnx as $value) {
		 		if ($lnxi>=2) {
				 	if ( ($trans_group == $group) && ($code == $trans_code ) && 
				 		( $codes[trim($trans_lang)]==$lnxi )) {		 		
				 		$value = trim($trans_text);		 		
				 	} 		
		 		}
		 		$row.= $coma.trim($value);
		 		$coma = ';';
		 		//echo $coma.trim($value).":[".$lnxi."] "; 
		 		$lnxi++;
		 	}
		 	$filestring.= $row."\n";
		 	
	 	}
	}	
	
	copy( $_trans_file_, $_trans_file_.date("d-m-y h-i-s").".bk.csv");
	$file = fopen ( $_trans_file_, "w");
	if ($file) {
		fwrite( $file, $filestring);
		fclose ( $file );
		return true;
	} else return false;		
}

if ( $newcode != "" && $newlangname!='') {
	
	if ( AddCode( "../../inc/lang/languages.csv", $newcode ) && AddCode( "../../inc/lang/extended.csv", $newcode )) {
		echo "$newcode addded!!!";
		$CMultiLang->AddLang($newlangname,$newcode);	
		$CMultiLang->SaveLang();
	}
	
}

if ( $oldcode != "" ) {
	
	if ( RemoveCode( "../../inc/lang/languages.csv", $oldcode ) && RemoveCode( "../../inc/lang/extended.csv", $oldcode )) {
		echo "$oldcode deleted!!!";	
		$CMultiLang->RemoveLang($oldcode);	
		$CMultiLang->SaveLang();
	}
	
}

if ( $newaliasname != "" ) {
	
	if ( AddAlias( "../../inc/lang/extended.csv", $newaliasname )) {
		echo "$newaliasname addded!!!";
	}
	
}

if ( $oldalias != "" ) {
	
	if ( RemoveAlias( "../../inc/lang/extended.csv", $oldalias )) {
		echo "$oldalias deleted!!!";
	}
	
}

?>

<form name="formaddlanguage" action="#">
LANG NAME:<input name="newlangname" type="text" value="">    
CODE:<input name="newcode" type="text" value="CH">
<input type="submit" value="ADD NEW LANGUAGE"></form>
 | 
 <form name="formremovelanguage" action="#"><input name="oldcode" type="text" value=""><input type="submit" value="REMOVE LANGUAGE"></form>

<form name="formaddalias" action="#">
ALIAS:<input name="newaliasname" type="text" value="">    
<input type="submit" value="ADD ALIAS"></form> 
| <form name="formremovealias" action="#"><input name="oldalias" type="text" value=""><input type="submit" value="REMOVE ALIAS"></form>

<div class="result" >
<form name="savegrid" action="" method="post">
<!--<input type="button" name="Save" value="Save" onclick="javascript:collect_data(g);">
<input type="button" name="Add Language" onclick=""> -->		
		</form>
			<div id="save_statusloader" style="display:none;">SAVING: <img src="../../inc/images/loading.gif"></div>
			Status: <div id="save_status"></div>
			
			<div id="grid_lang" style="height:200;width:600;">
			<? echo "LANGUAGES.CSV";?>
			<script type="text/javascript">
			
				function row_modified_lang ( grid, cell_pos, row_num, new_val )
				{
					var attrs = grid.get_row_attrs ( row_num );
					
					var data = grid.get_row ( row_num );
					//alert( "r:" + row_num + " c:" + cell_pos+" lang:"+codes[cell_pos] );
					
					var params = "";
					params+='trans_group='+data[0];
					params+='&trans_code='+data[1];
					params+='&trans_lang='+codes[cell_pos];
					params+='&trans_text='+new_val;
					params+='&cell_pos='+cell_pos;
					params+='&row_num='+row_num;
					params+='&trans_file=../../inc/lang/languages.csv';
					
					DynamicRequest( 'save_status', '../../administracion/admin/traducciones_save.php', params, '' );
					
					attrs [ 'changed' ] = 'YES';
				}
			
				// Create an OS3Grid instance
				var grid_lang = new OS3Grid ();

				// Grid Headers are the grid column names
				
				//esto hay q sacarlo del LANG....				
				<?
				$Lignes = file("../../inc/lang/languages.csv");
						
				$ln = $Lignes[0];
				$lnx = explode( ";" , $ln );
				$coma = "";
				$headers = "";
				$codes = array();
				?>
				var codes = new Array();
				codes[0] = "GROUP";
				codes[1] = "CODE";
				<?
				for( $i = 0; $i < count($lnx); $i++) {
					$headers.= $coma.'\''.addslashes(  trim(  $lnx[$i]  )  ).'\'';
					if ($i>=2) {
						$codes[$i-2] = trim(  $lnx[$i]  );
						echo 'codes['.$i.'] = "'.trim(  $lnx[$i]  ).'";';						
					}
					$coma = ',';
				}
				?>
				grid_lang.set_headers(<?=trim($headers)?>);
				<?
				
				for( $cn = 1; $cn < count($Lignes); $cn++ ) {
				 	$ln = $Lignes[$cn];
				 	$lnx = explode( ";" , $ln );
				 	$group = strtoupper(trim($lnx[0]));
				 	$code = strtoupper(trim($lnx[1]));
				 	$text = $lnx[$langi];
				 	
				 	$coma = "";
				 	$row = "";
				 	foreach($lnx as $value) {
				 		$row.= $coma.'\''.addslashes(trim($value)).'\'';
				 		$coma = ',';
				 	}
				 	?>
				 	grid_lang.add_row(<?=trim($row)?>);
				 	
				 	grid_lang.set_row_attr ( -1, 'changed', 'NO' );	
				 	
				 <?
				}				
				?>
				//g.set_row_attr ( -1, 'magic', 'magic-fsoft' );
				
				//g.set_col_editable ( 1, "txt" );
				grid_lang.set_col_editable ( 2, "txt" );
				grid_lang.set_col_editable ( 3, "txt" );
				grid_lang.set_col_editable ( 4, "txt" );
				grid_lang.set_col_editable ( 5, "txt" );
				grid_lang.set_col_editable ( 6, "txt" );
				
				// Enable sortable rows
				grid_lang.set_sortable ( true );

				// Enable highlight of rows with the mouse
				grid_lang.set_highlight ( true );


				// If contents is bigger than container, Grid will automatically show scrollbars
				grid_lang.set_scrollbars ( true );

				// The grid will have a solid border (these are CSS attributes)
				grid_lang.set_border ( 1, "solid", "#cccccc" );

				grid_lang.resize_cols = true;
				grid_lang.sort_on_edit = true;
				grid_lang.onchange = row_modified_lang;
				
				// Show the grid replacing the original HTML object with the "grid" ID.
				grid_lang.render ( 'grid_lang' );
			</script>
		</div>


			<div id="grid_extended" style="height:200;width:600;">
			<? echo "EXTENDED.CSV";?>
			<script type="text/javascript">
			
				function row_modified_extended ( grid, cell_pos, row_num, new_val )
				{
					var attrs = grid.get_row_attrs ( row_num );
					
					var data = grid.get_row ( row_num );
					//alert( "r:" + row_num + " c:" + cell_pos+" lang:"+codes[cell_pos] );
					
					var params = "";
					params+='trans_group='+data[0];
					params+='&trans_code='+data[1];
					params+='&trans_lang='+codes[cell_pos];
					params+='&trans_text='+new_val;
					params+='&cell_pos='+cell_pos;
					params+='&row_num='+row_num;
					params+='&trans_file=../../inc/lang/extended.csv';
					
					DynamicRequest( 'save_status', '../../administracion/admin/traducciones_save.php', params, '' );
					
					attrs [ 'changed' ] = 'YES';
				}
			
				// Create an OS3Grid instance
				var grid_extended = new OS3Grid ();

				// Grid Headers are the grid column names
				
				//esto hay q sacarlo del LANG....				
				<?
				$Lignes = file("../../inc/lang/extended.csv");
						
				$ln = $Lignes[0];
				$lnx = explode( ";" , $ln );
				$coma = "";
				$headers = "";
				$codes = array();
				?>
				var codes = new Array();
				codes[0] = "GROUP";
				codes[1] = "CODE";
				<?
				for( $i = 0; $i < count($lnx); $i++) {
					$headers.= $coma.'\''.addslashes(  trim(  $lnx[$i]  )  ).'\'';
					if ($i>=2) {
						$codes[$i-2] = trim(  $lnx[$i]  );
						echo 'codes['.$i.'] = "'.trim(  $lnx[$i]  ).'";';						
					} else {
						if ($i==0) echo 'codes[0] = "GROUP";';
						if ($i==1) echo 'codes[1] = "CODE";';
					}
					$coma = ',';
				}
				?>
				grid_extended.set_headers(<?=trim($headers)?>);
				<?
				
				for( $cn = 1; $cn < count($Lignes); $cn++ ) {
				 	$ln = $Lignes[$cn];
				 	$lnx = explode( ";" , $ln );
				 	$group = strtoupper(trim($lnx[0]));
				 	$code = strtoupper(trim($lnx[1]));
				 	$text = $lnx[$langi];
				 	
				 	$coma = "";
				 	$row = "";
				 	foreach($lnx as $value) {
				 		$row.= $coma.'\''.addslashes(trim($value)).'\'';
				 		$coma = ',';
				 	}
				 	?>
				 	grid_extended.add_row(<?=trim($row)?>);
				 	
				 	grid_extended.set_row_attr ( -1, 'changed', 'NO' );	
				 	
				 <?
				}				
				?>
				//g.set_row_attr ( -1, 'magic', 'magic-fsoft' );
				
				//g.set_col_editable ( 1, "txt" );
				<? if ($_SESSION['nivel']==0) {  ?>
				grid_extended.set_col_editable ( 1, "txt" );
				 <? }  ?>
				grid_extended.set_col_editable ( 2, "txt" );
				grid_extended.set_col_editable ( 3, "txt" );
				grid_extended.set_col_editable ( 4, "txt" );
				grid_extended.set_col_editable ( 5, "txt" );
				grid_extended.set_col_editable ( 6, "txt" );
				
				// Enable sortable rows
				grid_extended.set_sortable ( true );

				// Enable highlight of rows with the mouse
				grid_extended.set_highlight ( true );


				// If contents is bigger than container, Grid will automatically show scrollbars
				grid_extended.set_scrollbars ( true );

				// The grid will have a solid border (these are CSS attributes)
				grid_extended.set_border ( 1, "solid", "#cccccc" );

				grid_extended.resize_cols = true;
				grid_extended.sort_on_edit = true;
				grid_extended.onchange = row_modified_extended;
				
				// Show the grid replacing the original HTML object with the "grid" ID.
				grid_extended.render ( 'grid_extended' );
			</script>
		</div>


	</div>
<?} ?>
</div>