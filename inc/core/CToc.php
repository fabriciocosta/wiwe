<?php

/**
 *			CToc !!! para el menus 
 *
 * @version $Id$
 * @copyright 2003 
 **/

class CToc {

	var $_sprofundidad_, $_profundidad_, $_profundidadm0_ , $_profundidadm1_ , $_profundidadm2_;
	var $_seccionm0_, $_seccionm1_, $_seccionm2_ , $_seccionA_;
	var $color_fondo,$color_nivel0_sel,$color_nivel0,$color_nivel1_sel,$color_nivel1,$color_nivel2_sel,$color_nivel2;
	var $_nombrem0_,$_nombrem1_,$_nombrem2_;
	
	var $_tsecciones_,$_tseccionesa_,$_tseccionesb_,$_tseccionesc_;

	//nuevo
	var $_arbol_;
	var $_categoria_actual_;
	
	function	CToc($_tsecciones) {
		$this->_tsecciones_ = $_tsecciones;
		
		$this->_tsecciones_->LimpiarSQL();
		//ordenamos en relacion descendente de padres a hijos, de tal manera que el padre siempre 
		//venga primero, asi cuando doy de alta al hijo, el padre ya se encuentra
		$this->_tsecciones_->OrdenSQL('secciones.PROFUNDIDAD,secciones.ID_SECCION,secciones.ID');
		$this->_tsecciones_->Open();
		
		$this->_arbol_ = array();
		
		while ($row = $this->_tsecciones_->Fetch($this->_tsecciones_->resultados)) {
			
			//todas las ramas se encuentran ligadas a la raiz
			$this->_arbol_[$row['secciones.ID']] = array('id'=>$row['secciones.ID'],'padreid'=>$row['secciones.ID_SECCION'],'tipo'=>$row['secciones.ID_TIPOSECCION'],'nombre'=>$row['secciones.NOMBRE'],'profundidad'=>$row['secciones.PROFUNDIDAD'],'hijos'=>Array());			

			//ahora le ponemos ponemos sus referencias
			//en caso de no ser una categoria
			if ($row['secciones.PROFUNDIDAD']>0) 
			    //asignamos por referencia
				$this->_arbol_[$row['secciones.ID_SECCION']]['hijos'][$row['secciones.ID']] = &$this->_arbol_[$row['secciones.ID']];
		}				
		
		$this->color_fondo = "#ACB4DA";
		$this->color_nivel0 = "#ACB4DA";
		$this->color_nivel0_sel = "#636988";
		$this->color_nivel1 = "#ACB4DA";
		$this->color_nivel1_sel = "#636988";
		$this->color_nivel2 = "#ACB4DA";
		$this->color_nivel2_sel = "#636988";
		
		
	}
	
	//imprime la categoria en el menu
	function MostrarSeccion($_seccion) {
		
	}
	
	//imprime en pantalla la imagen de la categoria actual	
	function EncabezadoCategoria($_seccionid) {
	
		$nodo;
		
		$nodo = &$this->_arbol_[$_seccionid];
	
		while($nodo['profundidad']>0) $nodo = &$this->_arbol_[$nodo['padreid']];
	
		$this->_categoria_actual_ = $nodo['id'];
	
		switch($this->_categoria_actual_) {
		case 1:
			echo '<img src="../../inc/images/seccionrel/encabezadoESCUELAS.jpg" height="88" width="684" border="0"><br>';
			break;
		case 2:
			echo '<img src="../../inc/images/seccionrel/encabezadoBIBLIOTECAS.jpg" height="88" width="684" border="0"><br>';
			break;
		case 3:
			echo '<img src="../../inc/images/seccionrel/encabezadoCOMISIONES.jpg"  height="88" width="684" border="0"><br>';
			break;
		case 4:
			echo '<img src="../../inc/images/seccionrel/encabezadoCOMPRAS.jpg" height="88" width="684" border="0">';
			break;
		case 5:
			echo '<img src="../../inc/images/seccionrel/encabezadoFOROS.jpg" height="88" width="684" border="0">';
			break;
		case 13:
			echo '<img src="../../inc/images/seccionrel/encabezadoSUMATE.jpg" height="88" width="684" border="0">';
			break;
		case 14:
			echo '<img src="../../inc/images/seccionrel/encabezadoBUSCADOR.jpg" height="88" width="684" border="0">';			break;
		case 15:
			echo '<img src="../../inc/images/seccionrel/encabezadoSOMOS.jpg" height="88" width="684" border="0">';
			break;
			
		default:
			
		} // switch
	
	}
	
	function Localizacion($_seccionid) {
		$nodo = &$this->_arbol_[$_seccionid];
		$nodopadre = &$nodo;
		
		$path = $nodo['nombre'];
		//recursiva por los padres		
		while($nodopadre['profundidad']>0) {
			$nodopadre = &$this->_arbol_[$nodopadre['padreid']];
			$path = '<a href="../../principal/home/home.php?_seccion_='.$nodopadre['id'].'">'.$nodopadre['nombre'].'</a> / '.$path;
		}
		
		echo '<span class="seccion-localizacion">Te encuentras en: '.$path.'</span>';
	}

	function MostrarSeccionSeleccionada($_seccion) {

		$nodo = &$this->_arbol_[$_seccion];
		$nodopadre = &$this->_arbol_[$_seccion];

		//primero muestro la categoria donde se encuentra esta seccion:
		//busco la categoria
		while($nodopadre['profundidad']>0) {
			$nodopadre = &$this->_arbol_[$nodopadre['padreid']];	
		}
		?>
	<tr>
		<td>	
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="left" valign="top" bgcolor="white" width="10"><img src="../../inc/images/siterel/bordemenu01.jpg" alt="" height="16" width="7" border="0"></td>
					<td bgcolor="white" width="100%"><span class="toc-categoria"><?=strtoupper($nodopadre['nombre'])?></span></td>
					<td align="right" valign="top" bgcolor="white" width="12"><img src="../../inc/images/siterel/bordemenu02.jpg" alt="" height="16" width="9" border="0"></td>
				</tr>
			</table>
		</td>
	</tr>
	<?  //BUSCANDO PROFUNDIDAD
		if ($nodo['profundidad']>0) {//no es solo una categoria
		
			//SUB-CATEGORIAS
			$nodopadre = &$nodo;								
			while($nodopadre['profundidad']>0) {								
				if ($nodopadre['tipo']==DNK_SECCION_SUBCATEGORIA) {
				    			
?>
	<tr>
		<td bgcolor="white" width="114">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="100%"><span class="toc-subcategoria"><?=strtolower($nodopadre['nombre'])?></span></td>
				</tr>
			</table>
		</td>
	</tr>
<?				}
				$nodopadre = &$this->_arbol_[$nodopadre['padreid']];	
			}//FIN SUBCATEGORIAS
		
			//SECCION-PRINCIPAL, mientras no sea el ultimo tenemos una seccion
			//busquemosla
			$nodopadre = &$nodo;								
			while($nodopadre['profundidad']>0) {								
					if ($nodopadre['tipo']==DNK_SECCION_SECCIONPRINCIPAL) {
			
?>
	<tr>
		<td bgcolor="white" width="114">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="100%"><span class="toc-seccion"><?=strtolower($nodopadre['nombre'])?></span></td>
				</tr>
			</table>
		</td>
	</tr>
<?					break;
					}
					$nodopadre = &$this->_arbol_[$nodopadre['padreid']];
			}//FIN SECCION PRINCIPAL


			//SUBSECCIONES
			if ($nodopadre['tipo']==DNK_SECCION_SECCIONPRINCIPAL) {
				//SUBSECCIONES DE LA SECCION
				//mostramos las subsecciones de la seccion
				$hijos = &$nodopadre['hijos'];
				foreach($hijos as $hijo) {
?>
	<tr>
		<td bgcolor="#ffbc8b" width="114">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="100%"><span class="toc-subseccion"><?=strtolower($hijo['nombre'])?></span></td>
				</tr>
			</table>
		</td>
	</tr>
<?				}
			}//FIN DE LAS SUBSECCIONES
		}//FIN BUSCANDO PROFUNDIDAD
	}//FIN

	//Imprime todas las otras categorias menos la correspondiente a la seccion seleccionada
	function MostrarOtrasCategorias($_seccion) {

		$nodo = &$this->_arbol_[$_seccion];
		$nodopadre = &$nodo;

		//primero busco la categoria a excluir
		while($nodopadre['profundidad']>0) $nodopadre = &$this->_arbol_[$nodopadre['padreid']];
		
		$excluircategoria = $nodopadre['id'];		
		
		foreach($this->_arbol_ as $categoria) {
			if ($categoria['profundidad']>0) break;
			if ($categoria['id']!=$excluircategoria) {
?>
	<tr>
		<td bgcolor="white" width="114">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="100%"><span class="toc-categoria"><?=strtoupper($categoria['nombre'])?></span></td>
				</tr>
			</table>
		</td>
	</tr>
<?							
			}
		}
	
	}

	
	function MostrarToc($_seccion) {

		echo '<table width="114" border="0" cellspacing="2" cellpadding="0" height="100%">';
	
		$this->MostrarSeccionSeleccionada($_seccion);		
		$this->MostrarOtrasCategorias($_seccion);
		
		echo '</table>';
	
	}

	//Toc FUNCTIONS
	function nivel0_in($cl) {
			echo '<table width="116" border="0" bordercolor="#FF0000" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">';
	}
	
	function nivel0($id,$ttt,$cl) {
			echo '<tr>';
			echo '<td width="116" height="15"  bgcolor="'.$cl.'" align="left">';
			echo '<A HREF="../../principal/home/home.php?_seccion_='.$id.'">';
			echo strtoupper($ttt).'</A></td>';
			echo '</tr>';
	}
	
	function nivel0_b($cl) {
	
	}
	
	function nivel0_out() {
		echo '</table>';
	}
	
	function nivel1_in($cl) {
				echo '</table>';
				echo '<table width="116" border="0" cellpadding="0" cellspacing="0" bgcolor="'.$cl.'">';
	}
	
	function nivel1($id,$ttt,$cl) {
				echo '<tr>';
				echo '<td width="116" bgcolor="'.$cl.'" align="left">';
				echo '<A HREF="../../principal/home/home.php?_seccion_='.$id.'">&nbsp;';
				echo $ttt.'</A></td>';
				echo '</tr>';
	}
	
	
	function nivel1_b($cl) {

	}
	
	function nivel1_out($cl) {
		echo '</table>';	
		echo '<table width="116" border="0" bordercolor="#000000" cellpadding="0" cellspacing="0" bgcolor="'.$cl.'">';
	}
	
	function nivel2($id,$ttt,$cl) {
				echo '<tr>';
				echo '<td width="116" bgcolor="'.$cl.'" align="left">';
				echo '<A HREF="../../principal/home/home.php?_seccion_='.$id.'" onMouseover="javascript:document.all[\'itemc'.$id.'\'].src = \'../../inc/images/logo/minicg_over.gif\';" onMouseout="javascript:document.all[\'itemc'.$id.'\'].src = \'../../inc/images/logo/minicg.gif\';">&nbsp;';
				echo $ttt.'</A></td>';
				echo '</tr>';
	}
	
	function nivel2_in($cl) {
				echo '</table>';
				echo '<table width="116" border="0" cellpadding="0" cellspacing="0" bgcolor="'.$cl.'">';
	}
	
	function nivel2_b($cl) {
	}
	
	function nivel2_out($cl) {
		echo '</table>';
		echo '<table width="116" border="0" cellpadding="0" cellspacing="0" bgcolor="'.$cl.'">';
	}

} 
 
?>