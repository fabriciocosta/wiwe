<?php
global $_seccion_;
global $CLang;
global $__lang__;
global $_csistema_;
global $en;
if ($_csistema_==null) $this->GetVariablesSistema();
$detalles = $_csistema_->m_detalles;

$this->ModuloSignIn();

$this->InicializarTemplatesCompletos();

$this->Contenidos->m_tcontenidos->LimpiarSQL();
$this->Contenidos->m_tcontenidos->FiltrarSQL('ID_TIPOCONTENIDO','',FICHE_PARTNER);
//$this->Contenidos->m_tcontenidos->OrdenSQL();
$this->Contenidos->m_tcontenidos->Open();

$cn = 0;
echo '<table width="100%" border="0" cellpadding="0"  cellspacing="0">';
if ( $this->Contenidos->m_tcontenidos->nresultados>0) {
	echo '<tr>';
	
	while($rrr = $this->Contenidos->m_tcontenidos->Fetch($this->Contenidos->m_tcontenidos->resultados)) {
		
		$Partner = new CContenido($rrr);
		echo "<td>";
		$this->TiposContenidos->MostrarCompleto($Partner);
		echo "</td>";
		$cn++;
		
		if ( ( $cn % 2 ) == 0 ) {
			echo "</tr><tr>";
		}		
	}
	
	if ( ( $cn % 2 ) == 0 ) {
		echo "</tr>";
	} else {
		echo "<td></td></tr>";
	}
}
echo '</table>';

?>


