<?php
global $_seccion_;
global $CLang;

global $texto;
global $texto_firstline;

$this->Sistema('SISTEMA_DOCUMENTACION', $texto);

$texto_firstline = $CLang->Get("DOCUMENTATION");
?>

<div class="container">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header"><?=$texto_firstline?>
				<small>Crea, clona, comparte...</small>
			</h1>
		</div>
	</div>
		
	<div class="row">
		<div class="jumbotron">
			<h1></h1>
			<p><?=$texto?></p>
		</div>
	</div>
</div>