<?php
global $_seccion_;
global $CLang;
global $texto;


$this->Sistema('SISTEMA_CARACTERISTICAS', $texto);

global $quees;
$this->Sistema( 'SISTEMA_QUEES', $quees );

$TIPOCONTENIDO = FICHA_TUTORIAL;

?>
<a name="head"></a>
<div class="container">
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