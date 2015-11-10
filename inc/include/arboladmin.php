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
									//$extra.= "<a href=\\\"javascript:nuevaseccionhija(".$hijo->m_id.",".(SECCION_CATEGORIA).");\\\"><img src=\\\"../../inc/images/agregarmini.gif\\\" border=\\\"0\\\"  alt=\\\"Add\\\" title=\\\"Add\\\"></a>";
								}
																
								if ($hijo->m_id_tiposeccion==SECCION_CATEGORIAS || $hijo->m_id_tiposeccion==SECCION_CATEGORIA) {									
									$extra.= "<a href=\\\"javascript:nuevaseccionhija(".$hijo->m_id.",".(SECCION_CATEGORIA).");\\\"><img src=\\\"../../inc/images/agregarmini.gif\\\" border=\\\"0\\\"  alt=\\\"Add\\\" title=\\\"Add\\\"></a>";
								}								
								
								if ($hijo->m_id_tiposeccion==SECCION_CATEGORIA) {
									$extra.= "<a href=\\\"javascript:borrarseccion(".$hijo->m_id.");\\\"><img src=\\\"../../inc/images/borrarmini.gif\\\"  border=\\\"0\\\"  alt=\\\"Delete\\\" title=\\\"Delete\\\"></a>";									
								}
								
								if ($hijo->m_id_tiposeccion==SECCION_DOCUMENTACION_CATEGORIA) {									
									$extra.= "<a href=\\\"javascript:borrarseccion(".$hijo->m_id.");\\\"><img src=\\\"../../inc/images/borrarmini.gif\\\"  border=\\\"0\\\"  alt=\\\"Delete\\\" title=\\\"Delete\\\"></a>";																		
									$extra.= "<a href=\\\"javascript:nuevaseccionhija(".$hijo->m_id.",".(SECCION_DOCUMENTACION_CATEGORIA).");\\\"><img src=\\\"../../inc/images/agregarmini.gif\\\" border=\\\"0\\\"  alt=\\\"Add\\\" title=\\\"Add\\\"></a>";
								}											
								
								if ($hijo->m_id_tiposeccion==SECCION_GALERIA_CATEGORIA || $hijo->m_id_tiposeccion==SECCION_GALERIA) {									
									$extra.= "<a href=\\\"javascript:borrarseccion(".$hijo->m_id.");\\\"><img src=\\\"../../inc/images/borrarmini.gif\\\"  border=\\\"0\\\"  alt=\\\"Delete\\\" title=\\\"Delete\\\"></a>";																		
									$extra.= "<a href=\\\"javascript:nuevaseccionhija(".$hijo->m_id.",".(SECCION_GALERIA_CATEGORIA).");\\\"><img src=\\\"../../inc/images/agregarmini.gif\\\" border=\\\"0\\\"  alt=\\\"Add\\\" title=\\\"Add\\\"></a>";
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
							echo 'Tree['.$ri.']  = "'.$hijo->m_id.'|'.$hijo->m_id_seccion.'|'.$hijo->m_nombre.'|'.$url.'|'.$extra.'";';
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


?>