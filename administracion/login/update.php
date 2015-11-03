<?php
//version 4.1 22/08/2006 : corregido el id usuario creador de los contenidos...

function AddingField( $__table_name__, $__field_name__, $__field_SQL__ ) {
	
	global $link;
	global $Messages;
	
	$mymess = "Updating table:"."$__table_name__ >>> adding field `<span class=\"field_name\">".$__field_name__."</span> >>>";	
	
	$AlterQuery = "ALTER TABLE `$__table_name__` ADD `$__field_name__` $__field_SQL__";
	
	$query = mysql_query( $AlterQuery, $link);
	if (!$query) { 
		$mymess.= "Couldn't update table:".mysql_error()."<br>"; 
		$Error.= mysql_error();
		$Messages.= ShowError( $mymess, false ); 
	}
	else { 
		$mymess.= "&nbsp;Success!.<br>";
		$Messages.= ShowMessage( $mymess, false );
	}
	
}

function UpdateQuery( $__message__, $AlterQuery ) {
	global $link;
	global $Messages;

	$mymess = $__message__;
	$query = mysql_query( $AlterQuery, $link);
	if (!$query) { 
		$mymess.= "Couldn't update table:".mysql_error()."<br>"; 
		$Error.= mysql_error();
		$Messages.= ShowError( $mymess, false ); 
	}
	else { 
		$mymess.= "&nbsp;Success!.<br>";
		$Messages.= ShowMessage( $mymess, false );
	}
}

AddingField( "relaciones", "DISTANCIA", "INT( 0 ) NOT NULL DEFAULT '0' AFTER `ID_TIPORELACION`" );
AddingField( "relaciones", "PESO", "INT( 0 ) NOT NULL DEFAULT '0' AFTER `ID_TIPORELACION`" );
AddingField( "relaciones", "SENTIDO", "VARCHAR( 50 ) NOT NULL DEFAULT 'direct' AFTER `ID_TIPORELACION`" );


AddingField( "contenidos", "ML_PALABRASCLAVE", "TEXT NULL DEFAULT '' AFTER `ML_CUERPO`" );
AddingField( "contenidos", "PALABRASCLAVE", "TEXT NULL DEFAULT '' AFTER `ML_CUERPO`" );

AddingField( "secciones", "ML_PALABRASCLAVE", "TEXT NULL DEFAULT '' AFTER `ML_DESCRIPCION`" );
AddingField( "secciones", "PALABRASCLAVE", "TEXT NULL DEFAULT '' AFTER `ML_DESCRIPCION`" );

AddingField( "usuarios", "PROVINCIA", "VARCHAR(80) NULL DEFAULT '' AFTER `PAIS`" );
AddingField( "usuarios", "CP", "VARCHAR(12) DEFAULT '' AFTER `PAIS`" );
AddingField( "usuarios", "OCUPACION", "VARCHAR(200) NULL DEFAULT '' AFTER `EMPRESA`" );
AddingField( "usuarios", "NACIMIENTO", "timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `EMPRESA`" );


UpdateQuery( "Actualizando IDs de creadores nulos por IDs válidos (1) cg_admin >>> ", 
			 "update contenidos SET contenidos.ID_USUARIO_CREADOR=1 WHERE ID_USUARIO_CREADOR<=0" );

UpdateQuery( "Actualizando IDs de editores nulos por IDs válidos (1) cg_admin >>> ", 
			 "update contenidos SET contenidos.ID_USUARIO_MODIFICADOR=1 WHERE ID_USUARIO_MODIFICADOR<=0" );

UpdateQuery( "Actualizando IDs de creadores nulos por IDs válidos (1) cg_admin >>> ", 
			 "update contenidos SET contenidos.ID_USUARIO_CREADOR=1 WHERE ID_USUARIO_CREADOR<=0" );

UpdateQuery( "Actualizando IDs de editores nulos por IDs válidos (1) cg_admin >>> ", 
			 "update contenidos SET contenidos.ID_USUARIO_MODIFICADOR=1 WHERE ID_USUARIO_MODIFICADOR<=0" );





UpdateQuery( "Actualizando FECHAALTA no definida >>> ", 
			 "update contenidos SET contenidos.FECHAALTA=NOW() WHERE contenidos.FECHAALTA='00-00-00 00:00:00'" );

UpdateQuery( "Actualizando FECHABAJA no definida >>> ", 
			 "update contenidos SET contenidos.FECHABAJA=NOW() WHERE contenidos.FECHABAJA='00-00-00 00:00:00'" );

UpdateQuery( "Actualizando FECHAEVENTO no definida >>> ", 
			 "update contenidos SET contenidos.FECHAEVENTO=NOW() WHERE contenidos.FECHAEVENTO='00-00-00 00:00:00'" );

UpdateQuery( "Actualizando ACTUALIZACION no definida >>> ", 
			 "update contenidos SET contenidos.ACTUALIZACION=NOW() WHERE contenidos.ACTUALIZACION='00-00-00 00:00:00'" );

UpdateQuery( "Agrandando TIPOCAMPO de tiposdetalles >>> ",
"ALTER TABLE `tiposdetalles` CHANGE `TIPOCAMPO` `TIPOCAMPO` CHAR( 5 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL" ); 



?>