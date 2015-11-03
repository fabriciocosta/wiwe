<?php

/**
 * class CUsuario
 *
 * @version 06/09/2010
 * @copyright 2006 Fabricio Costa Alisedo
 **/

 
class CUsuario {

	var $m_id,
		$m_nick,
		$m_password,
		$m_passmd5,
		$m_passkey,
		$m_sexo,
		$m_civil,
		$m_nacimiento,
		$m_nombre,
		$m_apellido,
		$m_nivel,
		$m_mail,
		$m_telefono,
		$m_celular,
		$m_interno,
		$m_pais,
		$m_provincia,
		$m_ciudad,
		$m_direccion,
		$m_piso,
		$m_cp,
		$m_empresa,
		$m_ocupacion,
		$m_oficina,
		$m_pagina,
		$m_newsletter,
		$m_idiomas,
		$m_contacto,
		$m_icq,
		$m_icono,
		$m_id_contenido,
		$m_id_usuario_creador,
		$m_id_usuario_modificador,
		$m_actualizacion,
		$m_creacion,
		$m_inscripcion,
		$m_sesion,
		$m_baja;

	function CUsuario( $__row__ = "" ) {
	
		if ( is_numeric($__row__) && $__row__ == 0 ) {			 
			$this->SetEmpty();
		}	else {
			($__row__=="") ? $this->SetFromGlobals() : $this->Set($__row__);
		}	
	}
	
	function Nombre() {
		
		return ucwords( strtolower($this->m_nombre) );
		
	}

	function Apellido() {
		
		return ucwords( strtolower($this->m_apellido) );
		
	}
	
	
	function NombreCompleto() {
		
		return ucwords( strtolower($this->m_nombre)." ".strtolower($this->m_apellido) );
		
	}
	
	function Mail() {
		
		return $this->m_mail;
		
	}
	
	function SetEmpty() {
		
		$this->m_id = 1;
		$this->m_nick = "invalid";
		$this->m_password = "";
		$this->m_passmd5 = "";
		$this->m_passkey = "";
		$this->m_nombre = "";
		$this->m_apellido = "";
		$this->m_sexo = "";
		$this->m_civil = "";
		$this->m_nacimiento = "";
		$this->m_nivel = 4;
		$this->m_mail = "";
		$this->m_telefono = "";
		$this->m_celular = "";
		$this->m_interno = "";
		$this->m_pagina = "";
		$this->m_newsletter = "";
		$this->m_idiomas = "";
		$this->m_pais = "";
		$this->m_provincia = "";
		$this->m_ciudad = "";
		$this->m_direccion = "";
		$this->m_piso = "";
		$this->m_cp = "";
		$this->m_empresa = "";
		$this->m_ocupacion = "";
		$this->m_oficina = "";
		$this->m_contacto = "";
		$this->m_icq = "";
		$this->m_icono = "";
		$this->m_id_contenido = 1;
		$this->m_id_usuario_creador = 1;
		$this->m_id_usuario_modificador = 1;
		$this->m_actualizacion = "NOW()";
		$this->m_creacion = "NOW()";
		$this->m_inscripcion = "NOW()";
		$this->m_sesion = "";
		$this->m_baja = "N";
				
	}
	
	function Get($__field__) {
		
		switch($__field__) {
			case '_conditions_': return $GLOBALS['_conditions_'];
			case 'NICK': return $this->m_nick;
			case 'PASSWORD':  return $this->m_password;
			case 'PASSMD5': return $this->m_passmd5;
			case 'PASSKEY': return $this->m_passkey;
			case 'NOMBRE': return $this->m_nombre;
			case 'APELLIDO': return $this->m_apellido;
			case 'SEXO': return $this->m_sexo;
			case 'CIVIL': return $this->m_civil;
			case 'NACIMIENTO': return $this->m_nacimiento;
			case 'NIVEL': return $this->m_nivel;
			case 'MAIL': return $this->m_mail;
			case 'TELEFONO': return $this->m_telefono;
			case 'CELULAR': return $this->m_celular;
			case 'INTERNO': return $this->m_interno;
			case 'PAGINA': return $this->m_pagina;
			case 'NEWSLETTER': return $this->m_newsletter;
			case 'IDIOMAS': return $this->m_idiomas;
			case 'PAIS': return $this->m_pais;
			case 'PROVINCIA': return $this->m_provincia;
			case 'CIUDAD': return $this->m_ciudad;
			case 'DIRECCION': return $this->m_direccion;
			case 'PISO': return $this->m_piso;
			case 'CP': return $this->m_cp;
			case 'EMPRESA': return $this->m_empresa;
			case 'OCUPACION': return $this->m_ocupacion;
			case 'OFICINA': return $this->m_oficina;
			case 'CONTACTO': return $this->m_contacto;
			case 'ICQ': return $this->m_icq;
			case 'ICONO': return $this->m_icono;
			case 'ID_CONTENIDO': return $this->m_id_contenido;
			case 'ID_USUARIO_CREADOR': return $this->m_id_usuario_creador;
			case 'ID_USUARIO_MODIFICADOR': return $this->m_id_usuario_modificador;
			case 'ACTUALIZACION': return $this->m_actualizacion;
			case 'BAJA': return $this->m_baja;
		}
		echo '<span class="error">invalid field : '.$__field__.'</span>';
		return '';
	}
	
	function Set($__row__) {
		
		$this->m_id = $__row__['usuarios.ID'];
		$this->m_nick = $__row__['usuarios.NICK'];
		$this->m_password = $__row__['usuarios.PASSWORD'];
		$this->m_passmd5 = $__row__['usuarios.PASSMD5'];
		$this->m_passkey = $__row__['usuarios.PASSKEY'];
		$this->m_nombre = $__row__['usuarios.NOMBRE'];
		$this->m_apellido = $__row__['usuarios.APELLIDO'];
		$this->m_sexo = $__row__['usuarios.SEXO'];
		if (isset($__row__['usuarios.CIVIL'])) $this->m_civil = $__row__['usuarios.CIVIL'];
		$this->m_nacimiento = $__row__['usuarios.NACIMIENTO'];
		$this->m_nivel = $__row__['usuarios.NIVEL'];
		$this->m_mail = $__row__['usuarios.MAIL'];
		$this->m_telefono = $__row__['usuarios.TELEFONO'];
		$this->m_celular = $__row__['usuarios.CELULAR'];
		$this->m_interno = $__row__['usuarios.INTERNO'];
		$this->m_pagina = $__row__['usuarios.PAGINA'];
		if (isset($__row__['usuarios.NEWSLETTER'])) $this->m_newsletter = $__row__['usuarios.NEWSLETTER'];
		$this->m_idiomas = $__row__['usuarios.IDIOMAS'];
		$this->m_pais = $__row__['usuarios.PAIS'];
		$this->m_provincia = $__row__['usuarios.PROVINCIA'];
		$this->m_ciudad = $__row__['usuarios.CIUDAD'];
		$this->m_direccion = $__row__['usuarios.DIRECCION'];
		$this->m_piso = $__row__['usuarios.PISO'];
		$this->m_cp = $__row__['usuarios.CP'];
		$this->m_empresa = $__row__['usuarios.EMPRESA'];
		$this->m_ocupacion = $__row__['usuarios.OCUPACION'];
		$this->m_oficina = $__row__['usuarios.OFICINA'];
		$this->m_contacto = $__row__['usuarios.CONTACTO'];
		if (isset($__row__['usuarios.ICQ'])) $this->m_icq = $__row__['usuarios.ICQ'];
		if (isset($__row__['usuarios.ICONO'])) $this->m_icono = $__row__['usuarios.ICONO'];
		$this->m_id_contenido = $__row__['usuarios.ID_CONTENIDO'];
		if (isset($__row__['usuarios.ID_USUARIO_CREADOR'])) $this->m_id_usuario_creador = $__row__['usuarios.ID_USUARIO_CREADOR'];
		if (isset($__row__['usuarios.ID_USUARIO_MODIFICADOR'])) $this->m_id_usuario_modificador = $__row__['usuarios.ID_USUARIO_MODIFICADOR'];
		if (isset($__row__['usuarios.ACTUALIZACION'])) $this->m_actualizacion = $__row__['usuarios.ACTUALIZACION'];
		if (isset($__row__['usuarios.CREACION'])) $this->m_creacion = $__row__['usuarios.CREACION'];
		if (isset($__row__['usuarios.INSCRIPCION'])) $this->m_inscripcion = $__row__['usuarios.INSCRIPCION'];
		if (isset($__row__['usuarios.SESION'])) $this->m_sesion = $__row__['usuarios.SESION'];
		if (isset($__row__['usuarios.BAJA'])) $this->m_baja = $__row__['usuarios.BAJA'];
		
	}

	function SetFromGlobals() {
		//$this->m_id = $GLOBALS['_e__user_ID'];
		if (isset($GLOBALS['_primario_ID'])) $this->m_id = $GLOBALS['_primario_ID'];
		$this->m_nick = $GLOBALS['_e_NICK'];
		$this->m_password = $GLOBALS['_e_PASSWORD'];
		if (isset($GLOBALS['_e_PASSMD5'])) $this->m_passmd5 = $GLOBALS['_e_PASSMD5'];
		if (isset($GLOBALS['_e_PASSKEY'])) $this->m_passkey = $GLOBALS['_e_PASSKEY'];
		$this->m_nombre = $GLOBALS['_e_NOMBRE'];
		$this->m_apellido = $GLOBALS['_e_APELLIDO'];
		$this->m_sexo = $GLOBALS['_e_SEXO'];
		if (isset($GLOBALS['_e_CIVIL'])) $this->m_civil = $GLOBALS['_e_CIVIL'];
		$this->m_nacimiento = $GLOBALS['_e_NACIMIENTO'];
		if (isset($GLOBALS['_e_NIVEL'])) $this->m_nivel = $GLOBALS['_e_NIVEL'];
		$this->m_mail = $GLOBALS['_e_MAIL'];
		$this->m_telefono = $GLOBALS['_e_TELEFONO'];
		$this->m_celular = $GLOBALS['_e_CELULAR'];
		$this->m_interno = $GLOBALS['_e_INTERNO'];
		$this->m_pagina = $GLOBALS['_e_PAGINA'];
		if (isset($GLOBALS['_e_NEWSLETTER'])) $this->m_newsletter = $GLOBALS['_e_NEWSLETTER'];
		$this->m_idiomas = $GLOBALS['_e_IDIOMAS'];
		$this->m_pais = $GLOBALS['_e_PAIS'];
		$this->m_provincia = $GLOBALS['_e_PROVINCIA'];
		$this->m_ciudad = $GLOBALS['_e_CIUDAD'];
		$this->m_direccion = $GLOBALS['_e_DIRECCION'];
		$this->m_piso = $GLOBALS['_e_PISO'];
		$this->m_cp = $GLOBALS['_e_CP'];
		$this->m_empresa = $GLOBALS['_e_EMPRESA'];
		$this->m_ocupacion = $GLOBALS['_e_OCUPACION'];
		$this->m_oficina = $GLOBALS['_e_OFICINA'];
		$this->m_contacto = $GLOBALS['_e_CONTACTO'];
		if (isset($GLOBALS['_e_ICQ'])) $this->m_icq = $GLOBALS['_e_ICQ'];
		if (isset($GLOBALS['_e_ICONO'])) $this->m_icono = $GLOBALS['_e_ICONO'];
		if (isset($GLOBALS['_e_ID_CONTENIDO'])) $this->m_id_contenido = $GLOBALS['_e_ID_CONTENIDO'];
		if (isset($GLOBALS['_e_ID_USUARIO_CREADOR'])) $this->m_id_usuario_creador = $GLOBALS['_e_ID_USUARIO_CREADOR'];
		if (isset($GLOBALS['_e_ID_USUARIO_MODIFICADOR'])) $this->m_id_usuario_modificador = $GLOBALS['_e_ID_USUARIO_MODIFICADOR'];
		if (isset($GLOBALS['_e_ACTUALIZACION'])) $this->m_actualizacion = $GLOBALS['_e_ACTUALIZACION'];
		if (isset($GLOBALS['_e_BAJA'])) $this->m_baja = $GLOBALS['_e_BAJA'];		
	}
	
	function ToGlobals() {
		//$this->m_id = $GLOBALS['_e__user_ID'];
		$GLOBALS['_e_NICK'] = $this->m_nick;
		$GLOBALS['_e_PASSWORD']  = $this->m_password;
		$GLOBALS['_e_PASSMD5'] = $this->m_passmd5;
		$GLOBALS['_e_PASSKEY'] = $this->m_passkey;
		$GLOBALS['_e_NOMBRE'] = $this->m_nombre;
		$GLOBALS['_e_APELLIDO'] = $this->m_apellido;
		$GLOBALS['_e_SEXO'] = $this->m_sexo;
		$GLOBALS['_e_CIVIL'] = $this->m_civil;
		$GLOBALS['_e_NACIMIENTO'] = $this->m_nacimiento;
		$GLOBALS['_e_NIVEL'] = $this->m_nivel;
		$GLOBALS['_e_MAIL'] = $this->m_mail;
		$GLOBALS['_e_TELEFONO'] = $this->m_telefono;
		$GLOBALS['_e_CELULAR'] = $this->m_celular;
		$GLOBALS['_e_INTERNO'] = $this->m_interno;
		$GLOBALS['_e_PAGINA'] = $this->m_pagina;
		$GLOBALS['_e_NEWSLETTER'] = $this->m_newsletter;
		$GLOBALS['_e_IDIOMAS'] = $this->m_idiomas;
		$GLOBALS['_e_PAIS'] = $this->m_pais;
		$GLOBALS['_e_PROVINCIA'] = $this->m_provincia;
		$GLOBALS['_e_CIUDAD'] = $this->m_ciudad;
		$GLOBALS['_e_DIRECCION'] = $this->m_direccion;
		$GLOBALS['_e_PISO'] = $this->m_piso;
		$GLOBALS['_e_CP'] = $this->m_cp;
		$GLOBALS['_e_EMPRESA'] = $this->m_empresa;
		$GLOBALS['_e_OCUPACION'] = $this->m_ocupacion;
		$GLOBALS['_e_OFICINA'] = $this->m_oficina;
		$GLOBALS['_e_CONTACTO'] = $this->m_contacto;
		$GLOBALS['_e_ICQ'] = $this->m_icq;
		$GLOBALS['_e_ICONO'] = $this->m_icono;
		$GLOBALS['_e_ID_CONTENIDO'] = $this->m_id_contenido;
		$GLOBALS['_e_ID_USUARIO_CREADOR'] = $this->m_id_usuario_creador;
		$GLOBALS['_e_ID_USUARIO_MODIFICADOR'] = $this->m_id_usuario_modificador;
		$GLOBALS['_e_ACTUALIZACION'] = $this->m_actualizacion;
		$GLOBALS['_e_BAJA'] = $this->m_baja;		
	}

	function FullArray() {
		
		return array(
			'NICK'=>$this->m_nick,
			'NOMBRE'=>$this->m_nombre,
			'APELLIDO'=>$this->m_apellido,
			'SEXO'=>$this->m_sexo,
			'NIVEL'=>$this->m_nivel,
			'CIVIL'=>$this->m_civil,
			'NACIMIENTO'=>$this->m_nacimiento,
			'PASSWORD'=>$this->m_password,
			'_e_PASSWORD'=>$this->m_password,
			'_e_PASSWORD_confirm'=>$this->m_password,					
			'PASSMD5'=>$this->m_passmd5,
			'PASSKEY'=>$this->m_passkey,
			'DIRECCION'=>$this->m_direccion,
			'TELEFONO'=>$this->m_telefono,
			'CELULAR'=>$this->m_celular,
			'OFICINA'=>$this->m_oficina,
			'CIUDAD'=>$this->m_ciudad,
			'PROVINCIA'=>$this->m_provincia,
			'PAIS'=>$this->m_pais,
			'INTERNO'=>$this->m_interno,
			'PAGINA'=>$this->m_pagina,							
			'NEWSLETTER'=>$this->m_newsletter,
			'PISO'=>$this->m_piso,
			'CP'=>$this->m_cp,
			'MAIL'=>$this->m_mail,
			'IDIOMAS'=>$this->m_idiomas,
			'ICONO'=>$this->m_icono,
			'EMPRESA'=>$this->m_empresa,
			'OCUPACION'=>$this->m_ocupacion,
			'CONTACTO'=>$this->m_contacto,
			'ID_CONTENIDO'=>$this->m_id_contenido,
			'ACTUALIZACION'=>$this->m_actualizacion,
			'BAJA'=>$this->m_baja
			);
		
	}
	
	function PasswordVerification() {
		if ( isset($GLOBALS["_e_PASSWORD_confirm"]) ) {
			return ($this->m_password==$GLOBALS["_e_PASSWORD_confirm"]);
		}
		return false;
	}

	function RequiredFields( &$requiredfields, $error ) {
		$missing = false;
		foreach($requiredfields as $key=>$value) {

			$valor = $this->Get( $key );
			if ( trim($valor) == "" ) {
				$missing = true;
				$requiredfields[$key] = $error;
				DebugError("key: $key => ".$error);
			}
			
			//caso especial: contraseña nueva
			if ( $key == "PASSWORD" ) {
				
				///ademas debemos chequear que este la confirmación del pass:
				$p = $valor;
				$p_confirm = $GLOBALS['_e_PASSWORD_confirm'];
				if ($p_confirm=="") {
					$missing = true;
					$requiredfields[$key] = $error;
				}
				
			}
			
		}
		//ademas siempre reseteamos el password a cada vez
		if ($missing && isset($requiredfields['PASSWORD'])) {
			$requiredfields['PASSWORD'] = $error;
		}
		return $missing;
		
	}
	
	function UpdateRequiredFields( &$requiredfields, &$templ ) {
		foreach($requiredfields as $key=>$mess) {
			$templ = str_replace( "#".$key."#", $mess, $templ );
		}
	}	
}

 
?>