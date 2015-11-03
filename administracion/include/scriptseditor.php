<?
/*									*/
/*									*/
/*			SCRIPTS PARA EDITAR TEXTO				*/
/*									*/
/*									*/
?>
<script>
	var imagechooser;
	var linkeditor;
		
	function seleccionarimagenes(campo) {
		//evaluo el id de la seccion
		var seccionid;
		seccionid = document.confirmar._e_ID_SECCION.options[document.confirmar._e_ID_SECCION.selectedIndex].value;
		//llamar a una ventana....
		imagechooser = window.open('selector.php?_campo_='+campo+'&_seccion_='+seccionid,'selector','width=300,height=400,scrollbars=yes');
		
	}
	/*
	function insertarimagenes(campo) {
		var cb;
		var img;
		var i;
		for(i=1;i<=imagechooser.nimagenes;i++) {			
			eval('cb = imagechooser.selector.cb'+i+'.checked');
			eval('img = imagechooser.selector.img'+i+'.value');			
			if (cb) {//lo insertamos
				imgstr = '<img src=\''+img+'\' border=0>';
				eval('document.confirmar.'+campo+'.value = document.confirmar.'+campo+'.value+imgstr');			}
		}
	}
*/
	function seleccionarlink(campo) {
		var lnk;
		linkeditor = window.open('linkeditor.php?_campo_='+campo,'linkeditor','width=550,height=120');		
	}

	function insertarlink(campo) {
		linkstr = linkeditor.linktexto;	
		eval('document.confirmar.'+campo+'.value = document.confirmar.'+campo+'.value+linkstr');	
	}

	function insertarbr(campo) {
		var txt;
		txt = '<br>';
		eval('document.confirmar.'+campo+'.value = document.confirmar.'+campo+'.value+txt');	
	}

	function insertarbold(campo) {
		var txt;	
		txt = '<b></b>';
		eval('document.confirmar.'+campo+'.value = document.confirmar.'+campo+'.value+txt');	
	}

	function insertaritalica(campo) {
		var txt;	
		txt = '<i></i>';
		eval('document.confirmar.'+campo+'.value = document.confirmar.'+campo+'.value+txt');	
	}

	
</script>
<script src='../include/teditor/quickbuild.js'></script>