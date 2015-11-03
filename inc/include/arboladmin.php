<?Php

	
	if ($echoactivate) {
			
			
			
			/*
			echo $user_is_cg;
			foreach($this->Secciones->rama as $raiz=>$rama) {
				echo $raiz."=>".$rama."<br>";
				foreach($rama as $padre=>$hijo) {
					echo "----".$padre."=>".$hijo->m_nombre."<br>";
				}
			}*/

		//USUARIOS que no son SUPERUSUARIOS:0 ni ADMINISTRADORES:1
		//
		if ($this->UsuarioAdmin->m_nivel>1) {			
			
			//ShowMessage("Acceso limitado: ".$this->UsuarioAdmin->m_nivel);		
			/* Ver roles... */
			$permited_root_id = 1;			
			$last_profundidad = 10000000;
			
			$rol_secciones = $this->Usuarios->GetUsuarioSecciones( $this->UsuarioAdmin->m_id );
			
			if ( is_array($rol_secciones) )
				foreach($rol_secciones as $ids=>$CS) {
					//ShowMessage( "Nombre Sección:".$CS->Nombre()." Permitida:".$this->Usuarios->SeccionPermitida($ids,$rol_secciones) );				

					if ($CS->m_profundidad<$last_profundidad) {
						$last_profundidad = $CS->m_profundidad;
						$permited_root_id = $CS->m_id;
					}
					
				}

			$newimg = '<img src="/wiwe/inc/images/agregarmini.gif" border="0"  alt="Add" title="Add">';
			$editimg = '<img src="/wiwe/inc/images/editarmini.gif"  border="0"  alt="Edit" title="Edit">';
			$deleteimg = '<img src="/wiwe/inc/images/borrarmini.gif"  border="0"  alt="Delete" title="Delete">';
			$upimg = '<img src="/wiwe/inc/images/upmini.gif"  border="0"  alt="Up" title="Up" vspace="2">';
			$downimg = '<img src="/wiwe/inc/images/downmini.gif"  border="0"  alt="Down" title="Down" vspace="2">';
			
				
			//error_reporting(E_ALL);
			$rol_tipossecciones = $this->Usuarios->GetUsuarioTiposSecciones($this->UsuarioAdmin->m_id);
			if ( is_array($rol_tipossecciones) )
				foreach($rol_tipossecciones as $idts=>$CTS) {
					
					//ShowMessage( "Tipo Sección:".$CTS->m_tipo." Permitida:".$this->Usuarios->TipoSeccionPermitida($idts,$rol_tipossecciones) );				
					
					$this->Secciones->SetTemplateArbolRama( $idts, '<ul id="rama_*ID*_" class="rama">','</ul>' );
					$extra = '';
					
					
/*PERMISOS DE EDICION DE SECCIONES*/					
/*					
					if ( $idts==SECCION_TAXONES ) {
						$extra.= '<a href="javascript:modificarseccion(*ID*);">'.$editimg.'</a>';
						$extra.= '<a href="javascript:nuevaseccionhija(*ID*,'.(SECCION_TAXON_CATEGORIA).');">'.$newimg.'</a>';
					}
					if ($idts==SECCION_TAXONCAMPO) {
						$extra.= '<a href="javascript:modificarseccion(*ID*);">'.$editimg.'</a>';
					}
					if ( $idts==SECCION_TAXON_CATEGORIA ) {
						$extra.= '<a href="javascript:upseccion(*ID*);">'.$upimg.'</a>';
						$extra.= '<a href="javascript:downseccion(*ID*);">'.$downimg.'</a>';
						$extra.= '<a href="javascript:nuevaseccionhija(*ID*,'.(SECCION_TAXON_CATEGORIA).');">'.$newimg.'</a>';
						$extra.= '<a href="javascript:modificarseccion(*ID*);">'.$editimg.'</a>';									
						$extra.= '<a href="javascript:borrarseccion(*ID*);">'.$deleteimg.'</a>';
					}					
*/					
					
					$this->TiposSecciones->SetTemplateArbolNodo( $idts, '
					<li><span class="folder"><a class="nombre" href="javascript:consultarseccion(\'*ID*\',\'*IDTIPOSECCION*\');">*NOMBRE*</a>
					'.$extra.'</span>{MOSTRARRAMA}</li>' );	
										
				}
	
			$this->TiposSecciones->SetTemplateArbolNodo( 2, '{MOSTRARRAMA}');
				
			echo '<ul id="arbolito" class="filetree treeview">';
			//ShowMessage("<pre>".print_r( $this->Secciones->rama, true)."</pre>");
			$this->Secciones->MostrarArbol();
			echo '</ul>';
			echo "<script>
			
				$('#arbolito').treeview({
						persist: 'location',
						collapsed: true,
						unique: true,
						/*persist: 'cookie',*/
						animated: 'fast'
					});
			
			</script>
			";
			
} else {			
			echo '<script type="text/javascript">
			<!--
			var Tree = new Array;';
			$ri = 0;
			foreach($this->Secciones->rama as $raiz=>$rama) {	
						
				if ($raiz=="root") {
					foreach($rama as $padre=>$hijo) {
						if ( ( $hijo->m_id_tiposeccion==$GLOBALS['_ID_ROOT_TYPE_SECTION'] && $hijo->m_id_tiposeccion!=$GLOBALS['_ID_SYSTEM_TYPE_SECTION'])
						|| $user_is_cg	) {							
							echo 'Tree['.$ri.']  = "'.$hijo->m_id.'|0|'.$hijo->m_nombre.'|#|";';
							$ri++;
						}					
					}
				}	else {
					foreach($rama as $padre=>$hijo) {
						if ( $hijo->m_id_tiposeccion!=$GLOBALS['_ID_ROOT_TYPE_SECTION'] && $hijo->m_id_tiposeccion!=$GLOBALS['_ID_SYSTEM_TYPE_SECTION']) {
							
							if ($onlynavigate) {
								
								$url = "javascript:navegarseccion('".$hijo->m_id."');";
								$extra = "";
								
							} else {
								
								$url = "javascript:consultarseccion('".$hijo->m_id."','".$hijo->m_id_tiposeccion."');";								
								$extra = "";
																	
									$extra.= "<a href=\\\"javascript:modificarseccion(".$hijo->m_id.");\\\"><img src=\\\"../../inc/images/editarmini.gif\\\"  border=\\\"0\\\"  alt=\\\"Edit\\\" title=\\\"Edit\\\"></a>";
									
									$extra.= "<a href=\\\"javascript:upseccion(".$hijo->m_id.");\\\"><img src=\\\"../../inc/images/upmini.gif\\\"  border=\\\"0\\\"  alt=\\\"Up\\\" title=\\\"Up\\\"></a>";
									$extra.= "<a href=\\\"javascript:downseccion(".$hijo->m_id.");\\\"><img src=\\\"../../inc/images/downmini.gif\\\"  border=\\\"0\\\"  alt=\\\"Down\\\" title=\\\"Down\\\"></a>";
								 
								
								if ($hijo->m_id_tiposeccion==SECCION_MOLDEO) {
									$extra.= "<a href=\\\"javascript:modificarseccion(".$hijo->m_id.");\\\"><img src=\\\"../../inc/images/editarmini.gif\\\"  border=\\\"0\\\"  alt=\\\"Edit\\\" title=\\\"Edit\\\"></a>";
									$extra.= "<a href=\\\"javascript:borrarseccion(".$hijo->m_id.");\\\"><img src=\\\"../../inc/images/borrarmini.gif\\\"  border=\\\"0\\\"  alt=\\\"Delete\\\" title=\\\"Delete\\\"></a>";
									//$extra.= "<a href=\\\"javascript:nuevaseccionhija(".$hijo->m_id.",".(SECCION_CATEGORIA).");\\\"><img src=\\\"../../inc/images/agregarmini.gif\\\" border=\\\"0\\\"  alt=\\\"Add\\\" title=\\\"Add\\\"></a>";
								}

								if ( $hijo->m_id_tiposeccion==SECCION_TAXONES ) {
									$extra.= "<a href=\\\"javascript:nuevaseccionhija(".$hijo->m_id.",".(SECCION_TAXON_CATEGORIA).");\\\"><img src=\\\"../../inc/images/agregarmini.gif\\\" border=\\\"0\\\"  alt=\\\"Add\\\" title=\\\"Add\\\"></a>";
								}

								
								if ( $hijo->m_id_tiposeccion==SECCION_TAXON_CATEGORIA ) {
									$extra.= "<a href=\\\"javascript:nuevaseccionhija(".$hijo->m_id.",".(SECCION_TAXON_CATEGORIA).");\\\"><img src=\\\"../../inc/images/agregarmini.gif\\\" border=\\\"0\\\"  alt=\\\"Add\\\" title=\\\"Add\\\"></a>";
									$extra.= "<a href=\\\"javascript:modificarseccion(".$hijo->m_id.");\\\"><img src=\\\"../../inc/images/editarmini.gif\\\"  border=\\\"0\\\"  alt=\\\"Edit\\\" title=\\\"Edit\\\"></a>";									
									$extra.= "<a href=\\\"javascript:borrarseccion(".$hijo->m_id.");\\\"><img src=\\\"../../inc/images/borrarmini.gif\\\"  border=\\\"0\\\"  alt=\\\"Delete\\\" title=\\\"Delete\\\"></a>";
								}
								
								
							}
						} elseif ($hijo->m_id_tiposeccion==$GLOBALS['_ID_ROOT_TYPE_SECTION']) {
							$url = "#";
							if ($onlynavigate) {
								$extra = "";
							} else {
								$extra = "<a href=\\\"javascript:nuevaseccionhija(".$hijo->m_id.");\\\"><img src=\\\"../../inc/images/agregarmini.gif\\\" width=\\\"18\\\" height=\\\"18\\\" border=\\\"0\\\"></a>";						
							}
						} else {
							$url = "#";
							$extra = "";
						}
						
						
						if ( $hijo->m_id_tiposeccion!=$GLOBALS['_ID_SYSTEM_TYPE_SECTION'] ) {
							if (is_array($rol_tipossecciones)) {
								if ( ! $this->Usuarios->TipoSeccionPermitida($hijo->m_id_tiposeccion,$rol_tipossecciones)) {
									$url = "";
									$extra = "";
								}
								echo 'Tree['.$ri.']  = "'.$hijo->m_id.'|'.$hijo->m_id_seccion.'|'.$hijo->m_nombre.'|'.$url.'|'.$extra.'";';
								
							} else {							
								echo 'Tree['.$ri.']  = "'.$hijo->m_id.'|'.$hijo->m_id_seccion.'|'.$hijo->m_nombre.'|'.$url.'|'.$extra.'";';
							}						
							$ri++;
						}				

						
					}
				}			
			}
			echo '//-->
			</script>
			<div class="tree">
			<script type="text/javascript">
			<!--
				document.sitename = "";
				createTree(Tree);
			//-->
			</script>
			</div>		
			';		
}

		}


?>