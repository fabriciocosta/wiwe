<?
/*									*/
/*									*/
/*			STYLE 				*/
/*									*/
/*									*/
?>
<link rel="SHORTCUT ICON" href="<?=$_DIR_ADMABS?>/images/wiwe.ico" />
<link type="text/css" rel="stylesheet" href="../../inc/css/os3grid.css" />
<link rel="stylesheet"  href="../../inc/css/configuracion.css"  type="text/css"/>
<link href="../../inc/css/jquery.treeview.css" rel="stylesheet" media="screen"/>
<style>
  .conf_menuitem_left_sel { background-repeat: no-repeat;
    background-image: url(../images/conf_left_border.png);
    }

  .conf_menuitem_right_sel { background-position: right top;
    background-repeat: no-repeat;
    background-image: url(../images/conf_right_border.png);
    }
  .conf_menuitem_sel { font-weight: normal;
    background-position: center top;
    background-repeat: repeat-x;
    background-image: url(../images/conf_center_back.png);
    color: rgb(255, 255, 255);
    font-weight: bold;
    }
</style>


	<style>
		.setupresult {
			 display: block;			 
			 position: relative;
			 
			 text-align: center;
			 padding: 40px;
			 border: solid 3px #AFAFAF;
			 background-color: #CCCCCC;
		}
		
		.table_name {
			font-size: 13px;
			font-weight: bold;
		}
		
		.content_name {
			font-size: 12px;
			font-weight: bold;
		}		
		
		.field_name {
			font-size: 11px;
			font-weight: bold;
		}		
	
		.debugdetails,
		.errordetails,
		.showmessage,
		.showerror {
			display: none;
			position: relative;
			
						
			width: 100%;
			
			position: relative;
			background-color: #BBBBBB;
			font-weight: bold;
			text-align: left;
			padding: 10px;
			margin: 3px;			
			color: #000000;
			width: auto;
			height: auto;		
		}
		
		.debugdetails,
		.showmessage {
			border: solid 3px #00BB00;
		}
		
		.errordetails,
		.showerror {
			color: #FF0000;
			border: solid 2px #CC0000;
		}
		.showmessage,
		.showerror {
			background-color: #FFFFFF;
			border: solid 1px;
			display: block;
		}
	</style> 