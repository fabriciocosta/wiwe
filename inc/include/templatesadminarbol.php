<?php
		global $_TIPOS_;
		
		foreach($_TIPOS_['tipossecciones'] as $tipo=>$id) {
			$this->TiposSecciones->SetTemplateArbolNodo($id,'','','','','','');
			$this->Secciones->SetTemplateArbolRama($id, '','');
		}
?>