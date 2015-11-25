<?php
global $_seccion_;
global $CLang;
global $texto;


$this->Sistema('SISTEMA_CARACTERISTICAS', $texto);

global $quees;
$this->Sistema( 'SISTEMA_QUEES', $quees );

$TIPOCONTENIDO = FICHA_TUTORIAL;

$texto_firstline = $CLang->Get("FEATURES");
?>
<a name="head"></a>
<div class="container">

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header"><?=$texto_firstline?>
			<small>Crea, clona, comparte...</small>
		</h1>
	</div>
</div>

	<table cellpadding="0" cellspacing="0" border="0" width="95%">
		<tr>
			<td style="text-align:justify;"><br>
			<span class="text_white" ><?=$quees?></span>
			<br/>
			<br/>
			<span class="text_white" ><?=$texto?></span>
			</td>
		</tr>		
	</table>
</div>