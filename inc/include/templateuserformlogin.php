<?php

global $_cID_;

if ($_cID_!="" || $_cID_>0) {
	$_accion_ = "confirmrecord";
} else {
	$_accion_ = "";
}

$resstr.= '

<div id="div_debug" class="debugdetails">
	<div id="id_pedido">
		<input type="text" value="'.$_cID_.'" name="_cID_">
		<input type="text" value="'.$_accion_.'" name="_accion_">
	</div>
</div>


';



?>