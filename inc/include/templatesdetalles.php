<?php

	$this->TiposDetalles->SetTemplate( OBRAS_CATEGORIAS_ESCENICAS, '<a href="/obras/categorias/*#OBRAS_CATEGORIAS_ESCENICAS:NOMBREURL#*"  title="Buscar otras obras en esta categor�a esc�nica">*#OBRAS_CATEGORIAS_ESCENICAS:NOMBRE#*</a>', 'Categor�as esc�nicas: ', '', ', ' );	
	$this->TiposDetalles->SetTemplate( OBRAS_ESPACIOS, '<a href="/obras/espacios/*#OBRAS_ESPACIOS:TITULOURL#*">*#OBRAS_ESPACIOS:TITULO#*</a>', '<b>Espacios:</b> ', '', ', ' );
	$this->TiposDetalles->SetTemplate( OBRAS_FESTIVALES, '<a href="/obras/festivales/*#OBRAS_FESTIVALES:TITULOURL#*">*#OBRAS_FESTIVALES:TITULO#*</a>', '<b>Festivales:</b> ', '', ', ' );
	$this->TiposDetalles->SetTemplate( OBRAS_CATEGORIAS_ESCENICAS, '<a href="/obras/categorias/*#OBRAS_CATEGORIAS_ESCENICAS:NOMBREURL#*">*#OBRAS_CATEGORIAS_ESCENICAS:NOMBRE#*</a>', '<b>Categor�as:</b> ', '', ', ' );
	$this->TiposDetalles->SetTemplate( EVENTOS_OBRAS, '<a href="/obras/*#EVENTOS_OBRAS:TITULOURL#*">*#EVENTOS_OBRAS:TITULO#*</a>', '<b>Obras:</b> ', '', ', ' );
	
?>