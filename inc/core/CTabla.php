<?Php
require_once("../../inc/core/class.inputfilter_clean.php");

class Db {
	var $idioma;
	var $diccio;	
	
	function Db($idiom) {
		$this->idioma = $idiom;
	}
}

function SqlSrvDisplayErrors()
{
     $errors = sqlsrv_errors(SQLSRV_ERR_ERRORS);
     foreach( $errors as $error )
     {
          return "Error: ".$error['message']."\n";
     }
}

function SqlSrvDisplayWarnings()
{
     $warnings = sqlsrv_errors(SQLSRV_ERR_WARNINGS);
     if(!is_null($warnings))
     {
          foreach( $warnings as $warning )
          {
               return "Warning: ".$warning['message']."\n";
          }
     }
}


/// Objeto de acceso a una tabla de la base de datos
/**
 * Tabla es la clase base para acceder a una tabla de una base de datos en el lenguaje SQL
 * Es compatible con el SQL standar y aunque se probó más que nada con mySQL, es fácil de portar a otros motores de base de datos como
 * Interbase, Firebird y otros compatibles con el standar de SQL.
 * La mayoría de sus funciones están orientadas a simplificar la construcción de consultas y a preparar 
 * las sentencias SQL más complejas en pocos pasos.
 */

class Tabla {
	///definicion de base de datos
	var $db; 		

	///coneccion o transaccion
	var $CONN;		

	///nombre de la tabla
	var $nombre;

	///definicion de campos
	var $campos;
	
	///cantidad de campos	
	var $ncampos;	

	///último id insertado automáticamente
	var $lastinsertid;
	
	///arreglo de los indices
	var $indices;

	///cantidad de indices
	var $nindices;

	var $permisos;	
	
	///sentencia SQL
	var $SQL;
	///sentencia SQL
	var $SQLCOUNT;	
	///$resultados del query		
	var $resultados;
	///# registros
	var $nresultados; 	
	///indice primario (obligatorio)
	var $primario;		

	var $templateresultados;

	var $Limite;
	var $totalitems;
	var $startitem;
	var $maxitems;	
	
	var $nresultadosamostrar;
	var $resultadosa;
	var $resultadosb;	
	
	var $camposalias;
	var $tablasalias;
	var $guardasalias;
	var $ordenalias;	
	
	var $tagspermitidos;
	var $attrpermitidos;
	var $filtrohtml;
	var $blobplanar;

	var $irow;
		
	//RESERVADOS (uso interno)
	///blobs
	var $blobs;	
	///para los filtros del SQL
	var	$and;	
	///debug
	var $debug;	
	///para Insertar,Modificar,Borrar
	var $exito; 
	
	var $ultimo_error;
	
	///datos pasados erroneos, aqui figuran error y fuente
	var $errores;
	///definicion de columnas dentro de "$resultados" 
	var $cols;	
	///numero de columnas recibidas
	var $coln; 

	
	/** Definicion de la tabla
	* @param nombre: nombre real de la tabla dentro de la base
	* @param db: nombre de la base de datos	
	* @param servidor: nombre del servidor		
	* @param usuario: nombre del usuario	
	* @param password: contraseña del usuario				
	* @param tipodb: 'interbase' | 'mysql' | 'mssql' | 'mysqli'
	*/				
	function Tabla($nombre,$db,$servidor,$usuario,$password,$tipodb) {
		$this->db['nombre'] = $db;
		if ($servidor=='') $this->db['servidor'] = 'localhost';
		else $this->db['servidor'] = $servidor;
		$this->db['usuario'] = $usuario;
		$this->db['password'] = $password;
		$this->db['tipodb'] = $tipodb;
		$this->nombre = $nombre;
		$this->ncampos = 0;
		$this->nresultados = 0;
		$this->resultados = 0;
		$this->SQL = '';
		$this->SQLCOUNT = '';		
		$this->nindices= 0;
		$this->indices = Array();
		$this->templateresultado = '';
		$this->filtrohtml = null;
		$this->ultimo_error = "";
		

	
		if ($this->db['tipodb'] == "mssql") {
			if (!function_exists("mssql_connect")) {
				if (!function_exists("sqlsrv_connect")) {
					die("Error: mssql or sqlsrv extension not loaded.");
				} else {
					Debug("Changed from mssql to sqlsrv (detected)");
					$this->db['tipodb']  = "sqlsrv";
				}
			}
		}

	}
	
	function CopiarTabla( &$tabla ) {
		$this->db = $tabla->db;
		$this->nombre = $tabla->nombre;
		$this->permisos = $tabla->permisos;

		$this->campos = $tabla->campos;
		$this->ncampos = $tabla->ncampos;
		$this->primario = $tabla->primario;
		$this->templateresultado = $tabla->templateresultado;
		$this->indices = $tabla->indices;
		$this->nindices = $tabla->nindices;		
	}
	
	function FiltroHtml( $__tags__, $__atributos__, $__blobplanar__="no") {
		$this->tagspermitidos = $__tags__;
		$this->attrpermitidos = $__atributos__;
		$this->blobplanar = $__blobplanar__;
		$this->filtrohtml = new InputFilter( $this->tagspermitidos, $this->attrpermitidos );
	}

	function Convert($__texto__) {
		if ($this->blobplanar=="br") $__texto__ = str_replace(array("\n"),array("<br>"), $__texto__);
		return $__texto__;
	}
	
	function TextoML( $_contenidoml_, $lang ) {
	
		$a = strpos( $_contenidoml_,"<".$lang.">")+strlen("<".$lang.">");
		$b = strpos( $_contenidoml_,"</".$lang.">");
		if ($a!=false and $b!=false) {
			if ($b>$a) $_ret_ = substr($_contenidoml_,$a,($b-$a));
			else $_ret_='';
		} else $_ret_='';	
		
		if ($this->blobplanar=="br") $_ret_ = str_replace(array("\n"),array("<br>"), $_ret_);
		return $_ret_;	
	}
	
	function ML($_contenidoml_,$_contenido_) {
		//si no hay idioma definido devuelve el campo $_contenido
		if (($this->idioma=='') || ($_contenidoml_=='')) {
		    $_ret_ = $_contenido_;
		} else {		
			//sino, devuelve el valor correspondiente dentro de $_contenidoml, parseando por <LG></LG>
			$a = strpos($_contenidoml_,"<".$this->idioma.">")+strlen("<".$this->idioma.">");
			$b = strpos($_contenidoml_,"</".$this->idioma.">");
			if ($a!=false and $b!=false) {
				if ($b>$a) $_ret_ = substr($_contenidoml_,$a,($b-$a));
				else $_ret_='';
			} else $_ret_='';					
		}
		$_ret_ = trim($_ret_);
		if(strlen($_ret_)==0) return $_contenido_;
		else return $_ret_;		
					
	}	

	/* otorga permisos de ABM, array('agregar'=>'no','modificar'=>'si','borrar'=>'no')*/
	function Permisos($permisos) {
		$this->permisos = $permisos;
	}
	
	function Exists($id) {
		
		$this->SQLCOUNT = 'SELECT COUNT('.$this->primario.') FROM '.$this->nombre.' WHERE '.$this->primario.' = '.$id;	
		$n = $this->Count(); 
		if ($n>=1) {
			return true;
		}
		$this->ultimo_error = $GLOBALS["CLang"]->Get("RECORD_NOT_FOUND"); 
		return false;
	}
	
	/* Borra el registro identificado por el id*/	
	function Borrari($id) {
		
		if ($this->Exists($id)) {		
			if ($this->permisos['borrar']=='si') {
				$this->SQL = 'DELETE FROM '.$this->nombre.' WHERE '.$this->primario.' = '.$id;
				if ($this->EjecutaSQL()) { 
					$this->exito = $GLOBALS['CLang']->m_Messages['RECORD_DELETED'];
					$this->FinalizarSQL(); 
					return(true); 
				}
				else { 
					$this->exito = $GLOBALS['CLang']->m_ErrorMessages['RECORD_DELETE_ERROR']; 
					$this->exito.= $this->ShowSqlLastError();
					DebugError($this->exito);
					$this->FinalizarSQL();
					return(false);
				}
				
			} else {
				DebugError('ERROR: No tiene suficientes permisos para borrar registros de esta tabla.');
				$this->ultimo_error = "No tiene suficientes permisos para borrar registros de esta tabla.";
				return(false);
			}
		} else {
			DebugError($GLOBALS['CLang']->Get("RECORD_NOT_FOUND"));
			return(false);
		}
	}

	function Borrar() {
		$id = $GLOBALS['_primario_'.$this->primario];
		return $this->Borrari($id);
	}


	/**
	* Modifica el registro identificado por id con los valores del array campomod
	*/
	function Modificari($id) {
		if ($this->permisos['modificar']=='si') {
			$coma = "";
			$SQL2 = 'UPDATE '.$this->nombre.' SET ';
			//los campos editables se cambian
			foreach ($this->campos as $campo) {	
	 			if (($campo['editable']=='si') 
	 						and ($campo['tipo']!='BLOB') 
	 						and ($campo['tipo']!='ARCHIVO')  
	 						and ( ($campo['tipo']!='PASSWORD') 
	 								or 
	 									($GLOBALS['_e_'.$campo['nombre'].'_confirm']!="") 
	 								) 
	 					) {
							
					if (isset($GLOBALS['_e_'.$campo['nombre']])) {
						$SQL2.= $coma;
						$coma = ",";
						//$defecto = str_replace( array('"',"'"),array("&quot;","&#39;"),$GLOBALS['_e_'.$campo['nombre']]);
						$defecto = $GLOBALS['_e_'.$campo['nombre']];
						if (is_array($defecto)) {
							$defecto_str="";
							$coma = "";
							for($r=0;$r<count($defecto);$r++) {
								$defecto_str.= $coma."(".$defecto[$r].")";
								$coma = ", ";
							}
							$defecto = $defecto_str;
						}
						//ShowMessage('_e_'.$campo['nombre'].":".$defecto);
						//if ($this->filtrohtml!=null && $this->blobplanar=="si") $defecto = str_replace( array("\n"),array("<br>"),$defecto);
						//if ($this->filtrohtml!=null) $defecto = $this->filtrohtml->process($defecto);
						//si el campo esta definido pero es vacio y numerico lo consideramos un NULL para que sea valido
						if ( ( !is_numeric($defecto) ) && ( ( $campo['tipo']=='ENTERO' ) || ( $campo['tipo']=='DECIMAL' ) ) ) $defecto='NULL';						//el valor existe en el espacio GLOBAL: lo asignamos
						
						if ( ($campo['tipo']=='TEXTO') or ($campo['tipo']=='TEXTOML') or ($campo['tipo']=='BLOBTEXTO') or ($campo['tipo']=='BLOBTEXTOML')) {
						 	$SQL2.= $campo['nombre']."='".$this->EscapeString($defecto)."'";//ES TEXTO!!
						} elseif ($campo['tipo']=='FECHA') {
							$SQL2.= $campo['nombre']."='".$defecto."'";							
						} elseif (($campo['tipo']=='PASSWORD') and ($GLOBALS['_e_'.$campo['nombre']]!='') and ($GLOBALS['_e_'.$campo['nombre']]==$GLOBALS['_e_'.$campo['nombre'].'_confirm'])) {
							$SQL2.= $campo['nombre']."= ".$GLOBALS['_PASSWORD_VERSION']."('".$defecto."')";
						} elseif ($campo['tipo']!='PASSWORD') {
							$SQL2.= $campo['nombre']."=".$defecto;//ES OTRO!!
						}
					} else {//error!! terrible!!!
						//proceso los valores por defecto
						if ($campo['defecto']!='') {
							$SQL2.= $coma;
							$coma = ",";							
							$SQL2.= $campo['nombre']."=";
							if ( ($campo['tipo']=='TEXTO') or ($campo['tipo']=='TEXTOML') or ($campo['tipo']=='BLOBTEXTO') or ($campo['tipo']=='BLOBTEXTOML')) {
								$SQL2.= "'".$campo['defecto']."'";
							} else {
								$SQL2.= $campo['defecto'];
							}							
						} else DebugError('la variable _e_'.$campo['nombre'].' no está definida globalmente');
					}
				}
			}
			$this->SQL = $SQL2;
			$this->SQL.= ' WHERE '.$this->primario.'='.$id;			
			if ($this->EjecutaSQL()) { 
				$this->exito = $GLOBALS['CLang']->m_Messages['RECORD_UPDATED'];
				$this->FinalizarSQL(); 
				return(true); 
			}
			else { 
				$this->exito = $GLOBALS['CLang']->m_ErrorMessages['RECORD_UPDATE_ERROR'];
				$this->exito.= $this->ShowSqlLastError();
				DebugError($this->exito);
				$this->FinalizarSQL(); 
				return(false); 
			}
		} else {
			DebugError('ERROR: No tiene suficientes permisos para modificar registros de esta tabla.');
			return false;
		}
		return false;		
	}

	function Modificar() {		
		$id = $GLOBALS['_primario_'.$this->primario];
		return $this->Modificari($id);
	}


	function ModificarRegistro($id,$_registro_) {		
		if ($this->permisos['modificar']=='si') {
			$p = true;
			$SQL2 = 'UPDATE '.$this->nombre.' SET ';
			//los campos editables se cambian
			foreach ($this->campos as $campo) {	
	 			if (($campo['editable']=='si') and ($campo['tipo']!='BLOB') and ($campo['tipo']!='ARCHIVO') and (($campo['tipo']!='PASSWORD') or ($_registro_['_e_'.$campo['nombre'].'_confirm']!="")) ) {						
					if ( isset( $_registro_[$campo['nombre']] ) || isset( $_registro_[$this->nombre.".".$campo['nombre']] ) ) {
						
						isset( $_registro_[$campo['nombre']] ) ? $val = $_registro_[$campo['nombre']] : $val = $_registro_[$this->nombre.".".$campo['nombre']]; 
						
						//$defecto = str_replace( array('"',"'"),array("&quot;","&#39;"), $val );
						$defecto = $val;
						
						if (is_array($defecto)) {
							$defecto_str="";
							$coma = "";
							for($r=0;$r<count($defecto);$r++) {
								$defecto_str.= $coma."[".$defecto[$r]."]";
								$coma = ", ";
							}
							$defecto = $defecto_str;
						}						
						
						
						//if ($this->filtrohtml!=null && $this->blobplanar=="si") $defecto = str_replace( array("\n"),array("<br>"),$defecto);
						//if ($this->filtrohtml!=null) $defecto = $this->filtrohtml->process($defecto);
						($p==true) ? $p=false : $SQL2.=',';	
						//si el campo esta definido pero es vacio y numerico lo consideramos un NULL para que sea valido
						if ( ( !is_numeric($defecto) ) && ( ( $campo['tipo']=='ENTERO' ) || ( $campo['tipo']=='DECIMAL' ) ) ) $defecto='NULL';
						//el valor existe en el espacio GLOBAL: lo asignamos
						if ( ($campo['tipo']=='TEXTO') or ($campo['tipo']=='TEXTOML') or ($campo['tipo']=='BLOBTEXTO') or ($campo['tipo']=='BLOBTEXTOML')) {
						 	$SQL2.= $campo['nombre']."='".$this->EscapeString($defecto)."'";//ES TEXTO!!						 	
						} elseif ($campo['tipo']=='FECHA') {
							if ($defecto=='NOW()') $SQL2.= $campo['nombre']."=NOW()";
							else $SQL2.= $campo['nombre']."='".$defecto."'";							
						} elseif (($campo['tipo']=='PASSWORD') and ($_registro_['_e_'.$campo['nombre']]!='') and ($_registro_['_e_'.$campo['nombre']]==$_registro_['_e_'.$campo['nombre'].'_confirm'])) {
							$SQL2.= $campo['nombre']."= ".$GLOBALS['_PASSWORD_VERSION']."('".$defecto."')";
						} elseif ($campo['tipo']!='PASSWORD') {
							$SQL2.= $campo['nombre']."=".$defecto;//ES OTRO!!
						}
					}
				}
			}
			$this->SQL = $SQL2;
			if (is_numeric($id)) $this->SQL.= ' WHERE '.$this->primario.'='.$id;
			else $this->SQL.= ' WHERE '.$this->primario."='".$id."'";
			if ($this->EjecutaSQL()) { 
				$this->exito = $GLOBALS['CLang']->m_Messages['RECORD_UPDATED'];
				$this->FinalizarSQL(); 
				return(true); 
			}
			else { 
				$this->exito = $GLOBALS['CLang']->m_ErrorMessages['RECORD_UPDATE_ERROR'];
				$this->exito.= $this->ShowSqlLastError();
				DebugError( $this->exito );
				$this->FinalizarSQL();
				return(false);  
			}
			
		} else {
			DebugError("ERROR: No tiene suficientes permisos para modificar registros de esta tabla.");
			return(false);
		} 
	}

	/**
	* Inserta los campos que encuentra en el array $camposmod el array tiene la forma array('CAMPO1'=>'valor1','CAMPO2'=>'valor2')
	*/
	function Insertar() {
		if ($this->permisos['agregar']=='si') {
			$p = true;	
			$sql2 = '';
			$sql1 = 'INSERT INTO '.$this->nombre.' ';
	
			foreach ($this->campos as $campo) {	
	 			if (($campo['editable']=='si') and ($campo['tipo']!='BLOB') and ($campo['tipo']!='ARCHIVO') and (($campo['tipo']!='PASSWORD') or ($GLOBALS['_e_'.$campo['nombre'].'_confirm']!="")) ) {
					if ($p==true) { $sql1.= ' ('; $sql2 ='VALUES('; $p = false; } else {$sql1.=',';	$sql2.=',';}
					
					if (isset($GLOBALS['_e_'.$campo['nombre']])) {
						//$defecto = str_replace( array('"',"'"),array("&quot;","&#39;"),$GLOBALS['_e_'.$campo['nombre']]);
						$defecto = $GLOBALS['_e_'.$campo['nombre']];
						
						if (is_array($defecto)) {
							$defecto_str="";
							$coma = "";
							for($r=0;$r<count($defecto);$r++) {
								$defecto_str.= $coma."[".$defecto[$r]."]";
								$coma = ", ";
							}
							$defecto = $defecto_str;
						}	
						//if ($this->filtrohtml!=null && $this->blobplanar=="si") $defecto = str_replace( array("\n"),array("<br>"),$defecto);
						//if ($this->filtrohtml!=null) $defecto = $this->filtrohtml->process($defecto);						
						//si el campo esta definido pero es vacio y numerico lo consideramos un NULL para que sea valido
						if ( ( !is_numeric($defecto) ) && ( ( $campo['tipo']=='ENTERO' ) || ( $campo['tipo']=='DECIMAL' ) ) ) $defecto='NULL';
												
						//asignamos al campo el valor
						$sql1.= $campo['nombre'];
						if ( ($campo['tipo']=='TEXTO') or ($campo['tipo']=='TEXTOML') or ($campo['tipo']=='BLOBTEXTO') or ($campo['tipo']=='BLOBTEXTOML')) {
							$sql2.="'".$this->EscapeString($defecto)."'";//ES TEXTO!!
						} elseif (($campo['tipo']=='FECHA')) {
							if ($defecto=='NOW()') $sql2.='NOW()';
							else $sql2.="'".$defecto."'";							
						} elseif (($campo['tipo']=='PASSWORD') and ($GLOBALS['_e_'.$campo['nombre']]!='') and ($GLOBALS['_e_'.$campo['nombre']]==$GLOBALS['_e_'.$campo['nombre'].'_confirm'])) {
							$sql2.="".$GLOBALS['_PASSWORD_VERSION']."('".$defecto."')";
						} elseif ($campo['tipo']!='PASSWORD') {
							$sql2.= $defecto;//ES OTRO!!
						}
						
					} else {//error!! terrible!!!
						//proceso los valores por defecto
						if ($campo['defecto']!='') {
							$sql1.= $campo['nombre'];
							if ( ($campo['tipo']=='TEXTO') or ($campo['tipo']=='TEXTOML') or ($campo['tipo']=='BLOBTEXTO') or ($campo['tipo']=='BLOBTEXTOML')) {
								$sql2.= "'".$campo['defecto']."'";
							} else {
								$sql2.= $campo['defecto'];
							}
						} else DebugError('la variable _e_'.$campo['nombre'].' no está definida globalmente');
					}
				}
			}
			$this->SQL = $sql1.') '.$sql2.')';
			if ($this->EjecutaSQL()) { 
				$this->exito = $GLOBALS['CLang']->m_Messages['RECORD_CREATED']; 
				$this->GetLastInsertId();
				$this->FinalizarSQL(); 
				return(true); 
			}
			else {
				$this->exito = $GLOBALS['CLang']->m_ErrorMessages['RECORD_CREATION_ERROR'];
				$this->exito.= $this->ShowSqlLastError();
				DebugError( $this->exito );
				$this->FinalizarSQL();				
				return(false);
			}
						
		} else {
			DebugError('ERROR: No tiene suficientes permisos para agregar registros de esta tabla.');			
			return(false);
		}
		return(false);
	}				

	function InsertarRegistro($_registro_) {
		if ($this->permisos['agregar']=='si') {
			$p = true;	
			$sql2 = '';
			$sql1 = 'INSERT INTO '.$this->nombre.' ';
	
			foreach ($this->campos as $campo) {	
	 			if (($campo['editable']=='si') and ($campo['tipo']!='BLOB') and ($campo['tipo']!='ARCHIVO') and (($campo['tipo']!='PASSWORD') or ($_registro_['_e_'.$campo['nombre'].'_confirm']!="") ) ) {
					if ($p==true) { $sql1.= ' ('; $sql2 ='VALUES('; $p = false; } else {$sql1.=',';	$sql2.=',';}
					if ( isset($_registro_[$campo['nombre']]) ) {
						//$defecto = str_replace( array('"',"'"),array("&quot;","&#39;"),$_registro_[$campo['nombre']]);
						$defecto = $_registro_[$campo['nombre']];
						
						if (is_array($defecto)) {
							$defecto_str="";
							$coma = "";
							for($r=0;$r<count($defecto);$r++) {
								$defecto_str.= $coma."[".$defecto[$r]."]";
								$coma = ", ";
							}
							$defecto = $defecto_str;
						}													
						//if ($this->filtrohtml!=null && $this->blobplanar=="si") $defecto = str_replace( array("\n"),array("<br>"),$defecto);
						//if ($this->filtrohtml!=null) $defecto = $this->filtrohtml->process($defecto);
						//si el campo esta definido pero es vacio y numerico lo consideramos un NULL para que sea valido
						if ( ( !is_numeric($defecto) ) && ( ( $campo['tipo']=='ENTERO' ) || ( $campo['tipo']=='DECIMAL' ) ) ) $defecto='NULL';
						//asignamos al campo el valor
						$sql1.= $campo['nombre'];
						if ( ($campo['tipo']=='TEXTO') or ($campo['tipo']=='TEXTOML') or ($campo['tipo']=='BLOBTEXTO') or ($campo['tipo']=='BLOBTEXTOML')) {							
							$sql2.="'".$this->EscapeString($defecto)."'";//ES TEXTO!!
						} elseif (($campo['tipo']=='FECHA')) {
							if ($defecto=='NOW()') $sql2.='NOW()';
							else $sql2.="'".$this->EscapeString($defecto)."'";							
						} elseif (($campo['tipo']=='PASSWORD') and ($_registro_['_e_'.$campo['nombre']]!='') and ($_registro_['_e_'.$campo['nombre']]==$_registro_['_e_'.$campo['nombre'].'_confirm'])) {
							$sql2.="".$GLOBALS['_PASSWORD_VERSION']."('".$defecto."')";
						} elseif ($campo['tipo']!='PASSWORD') {
							$sql2.= $defecto;//ES OTRO!!
						}
					} else {//error!! terrible!!!
						//proceso los valores por defecto
						if ($campo['defecto']!='') {
							$sql1.= $campo['nombre'];
							if ( ($campo['tipo']=='TEXTO') or ($campo['tipo']=='TEXTOML') or ($campo['tipo']=='BLOBTEXTO') or ($campo['tipo']=='BLOBTEXTOML')) {
								$sql2.= "'".$this->EscapeString($campo['defecto'])."'";
							} else {
								$sql2.= $campo['defecto'];
							}
						} else	DebugError('Tabla::InsertarRegistro la variable '.$campo['nombre'].' no está definida');
					}
				}
			}
			$this->SQL = $sql1.') '.$sql2.')';
			if ($this->EjecutaSQL()) { 
				$this->exito = $GLOBALS['CLang']->m_Messages['RECORD_CREATED']; 
				$this->GetLastInsertId();
				$this->FinalizarSQL(); 
				return(true); 
			}
			else {
				$this->exito = $GLOBALS['CLang']->m_ErrorMessages['RECORD_CREATION_ERROR'];
				$this->exito.= $this->ShowSqlLastError();
				DebugError( $this->exito );
				$this->FinalizarSQL();
				return(false);
			}
			
		} else {
			DebugError("ERROR: No tiene suficientes permisos para agregar registros en esta tabla. $this->nombre");
			return(false);
		}
	}

	/** Agrega un campo con su definicion 
	* @param nombre: nombre del campo dentro de la tabla 
	* @param etiqueta: nombre con el que se presentara el campo al cliente	
	* @param tipo: TEXTO | NUMERO | DECIMAL | BLOB | BLOBTEXTO | FECHA
	* @param ancho:  ej: 15%
	* @param nulo: NULL | NOT NULL (si puede o no ser nulo)
	* @param defecto: valor por defecto
	* @param editable: si el campo es para editar	
	* @param filtrar: si el campo es para filtrar		
	* @param tamanio: el tamanio del INPUT
	*/				
	function AgregarCampo($nombre, $etiqueta, $tipo, $ancho="10%", $nulo="", $defecto="", $editable='si', $filtrar='si', $tamanio="0", $tamanio2="0") {
		$campo = array('nombre'=>$nombre,'etiqueta'=>$etiqueta, 'tipo'=>$tipo,'referencias'=>Array(),'nreferencias'=>0,'ancho'=>$ancho,'nulo'=>$nulo, 'defecto'=>$defecto,'editable'=>$editable,'filtrar'=>$filtrar,'tamanio'=>$tamanio, 'tamanio2'=>$tamanio2);
		$this->ncampo += 1;
		$this->campos[$nombre] = $campo;
		if ($tipo=='BLOBTEXTO') $this->blobs[$nombre] = array('nombre'=>$nombre,'id'=>0);
		if ($tipo=='BLOB') $this->blobs[$nombre] = array('nombre'=>$nombre,'id'=>0);		
	}

	/* agrega una auto referencia (o sea una referencia a la misma tabla, pero con un alias)*/
	function AgregarAutoReferencia($nombre,$etiqueta, $tablaalias/*alias*/, $clave, $muestra) {
		$campo = $this->campos[$nombre];		
		$referencias = $campo['referencias'];
		
		$referencia = array('nombre'=>$nombre, 'tipo'=>'autoreferencia', 'etiqueta'=>$etiqueta , 'tabla'=>$tablaalias , 'clave'=>$clave , 'muestra'=>$muestra);
		
		$referencias[$campo['nreferencias']] = $referencia;
		$campo['referencias'] = $referencias;
		$campo['nreferencias']++;		
		$this->campos[$nombre] = $campo;		
	}
	
	/* agrega una referencia directa a un campo, asi: "nombre(campo local) referencia a "tablaref->clave" (campo ref MUESTRA) */
	function AgregarReferencia($nombre,$etiqueta, $tablaref, $clave, $muestra, $nested = "", $orden="" ) {
		$campo = $this->campos[$nombre];		
		$referencias = $campo['referencias'];

		
		$referencia = array('nombre'=>$nombre, 'tipo'=>'directa', 'etiqueta'=>$etiqueta , 'tabla'=>$tablaref , 'clave'=>$clave , 'muestra'=>$muestra, 'nested'=>$nested, 'orden'=>$orden);
		
		$referencias[$campo['nreferencias']] = $referencia;
		$campo['referencias'] = $referencias;
		$campo['nreferencias']++;
		$this->campos[$nombre] = $campo;
	}
	
	
	function AgregarReferenciaMultiples( $nombre,$etiqueta, $tablaref/*alias*/, $clave, $muestra, $nested = "", $orden="" ) {
		
		$campo = $this->campos[$nombre];
		$referencias = $campo['referencias'];
		$referencia = array('nombre'=>$nombre, 'tipo'=>'multiples', 'etiqueta'=>$etiqueta , 'tabla'=>$tablaref , 'clave'=>$clave , 'muestra'=>$muestra, 'nested'=>$nested, 'orden'=>$orden);		
		$referencias[$campo['nreferencias']] = $referencia;
		$campo['referencias'] = $referencias;
		$campo['nreferencias']++;
		$this->campos[$nombre] = $campo;
	}
	
	function AgregarReferencias( $campos_a, $tablas_a, $guardas_a, $orden_a ) {

		foreach($campos_a as $ca) {
			$this->camposalias[$ca] = 'si';			
		}
		foreach($tablas_a as $ta) {
			$this->tablasalias[$ta] = 'si';			
		}
		foreach($guardas_a as $gu) {
			$this->guardasalias[$gu] = 'si';			
		}
		foreach($orden_a as $gu) {
			$this->ordenalias[$gu] = 'si';			
		}
		
	}

	/* agrega una referencia anidada a un campo, asi:"nombre(campo local) referencia a "tabla1->clave1" que  referencia a "tabla->clave" */
	function AgregarReferenciaAnidada($nombre,$etiqueta, $tabla1, $clave1, $tabla, $clave, $muestra) {
		$campo = $this->campos[$nombre];		
		$referencias = $campo['referencias'];

		$referencia = array('nombre'=>$nombre, 'tipo'=>'anidada', 'etiqueta'=>$etiqueta , 'tabla1'=>$tabla1 , 'clave1'=>$clave1 , 'tabla'=>$tabla , 'clave'=>$clave , 'muestra'=>$muestra);

		$referencias[$campo['nreferencias']] = $referencia;
		$campo['referencias'] = $referencias;
		$campo['nreferencias']++;
		$this->campos[$nombre] = $campo;
	}

	/* agrega una referencia "virtual" a un campo, que corresponde a una lista de valores predefinidos por el usuario */
	function AgregarReferenciaCombo($nombre,$etiqueta, $combo) {
		$campo = $this->campos[$nombre];		
		$referencias = $campo['referencias'];

		$referencia = array('nombre'=>$nombre, 'tipo'=>'combo', 'etiqueta'=>$etiqueta , 'combo'=>$combo);

		$referencias[$campo['nreferencias']] = $referencia;
		$campo['referencias'] = $referencias;
		$campo['nreferencias']++;
		$this->campos[$nombre] = $campo;
	}

	/*Quitar la referencia! importante, para poder generar busquedas mas complejas*/
	function QuitarReferencia( $nombre, $etiqueta ) {
		$campo = $this->campos[$nombre];
		$nref = 0;
		foreach($campo['referencias'] as $rf) {
			if ($rf['etiqueta']!=$etiqueta)	{
				$referencias[$nref] = $rf;
				$nref++;
			}
		}
		$campo['referencias'] = $referencias;
		$campo['nreferencias'] = $nref;
		$this->campos[$nombre] = $campo;
	}
	
	function QuitarReferencias() {
		$this->camposalias = array();
		$this->tablasalias = array();
		$this->guardasalias = array();
		$this->ordenalias = array();		
	}	
	
	/* agrega un indice definido por el usuario que quedara disponible para ordenar el query */
	function AgregarIndice($nombre,$claves='', $tipo='') {
		if ($tipo=='PRIMARIO') {
			$this->primario = $nombre;
		} elseif ($claves!='') {
			$indice = array( 'nombre' => $nombre, 'indice'=>$claves);
			$this->indices[$nombre] = $indice;
			$this->nindices++;
		} else {
			$indice = array( 'nombre' => $nombre, 'indice'=>$nombre);
			$this->indices[$nombre] = $indice;
			$this->nindices++;			
		}
	}
	
	function LimpiarIndices() {
		$this->indices = array();
		$this->nindices = 0;
	}

	
	/* toma todos los campos de un registro especificado por el codigo primario*/
	function Edicion($id) {
		$this->nresultados = 0;
		$this->resultados = 0;
		$this->LimpiarSQL();
		$this->FiltrarSQL($this->primario, '', $id);				
		$this->Open();
		if ($this->nresultados>0) {			
			$row = $this->Fetch($this->resultados);
			$this->FinalizarSQL();
			foreach($this->campos as $campo) {
				//if ($campo['editable']=='si') {
					$GLOBALS['_e_'.$campo['nombre']] = $row[$this->nombre.".".$campo['nombre']];
					$this->campos[$campo['nombre']] = $campo;
				//}
			}
		} else {
			$this->FinalizarSQL();
		}		
	}

	/** 
	 * Asignamos valores por defecto a las variables editables
	 * y las declara globalmente... 
	*/
	function Nuevo() {
		foreach($this->campos as $campo) {
			if ($campo['editable']=='si') {
				if (!isset($GLOBALS['_e_'.$campo['nombre']])) {
					$GLOBALS['_e_'.$campo['nombre']] = $campo['defecto'];
					Debug( '_e_'.$campo['nombre'].": [".$GLOBALS['_e_'.$campo['nombre']]."]" );
				}
			}
		}
	}


	/* Muestra donde sucedio el error */
	function ImprimirErrores($echo=false) {
		$_errores = '';
		$_errores.='<div id="listaerrores" class="error">LISTA DE ERRORES<br>';
		foreach($this->campos as $campo) {
			if ($campo['editable']=='si') {
				if ($this->errores[$campo['nombre']]=='no') {
					$_errores.='';
				} else {
					$_errores.='<div class="error">Error en el campo '.$campo['nombre'].': el valor ingresado es '.$this->errores[$campo['nombre']].'</div>';
				}
			}
		}
		$_errores.='</div>';
		if($echo) echo $_errores;
		return $_errores;
	}
	

	/* Verifica la validez de los valores de los campos array('CAMPO1'=>'valor1','CAMPO2'=>2,... ) */
	function Verificar() {
		$this->errores = array();
		$error = false;
		global $HTTP_POST_FILES;

		foreach($this->campos as $campo) {
			//si es nul o no
			if (isset($GLOBALS['_e_'.$campo['nombre']])) {
				if (($campo['tipo']!='PASSWORD') and ($campo['tipo']!='ARCHIVO')) {
					if ($GLOBALS['_e_'.$campo['nombre']]=='') {
						if ($campo['nulo']=='NULL')	$this->errores[$campo['nombre']] = 0;
						else	{
							$this->errores[$campo['nombre']] = 'nulo';
							$error = true;
						}
					} else {
						if (($campo['tipo']=='ENTERO') or ($campo['tipo']=='DECIMAL')) {
							//si no es numerico esta mal
							if (!is_numeric($GLOBALS['_e_'.$campo['nombre']]))	{
								$this->errores[$campo['nombre']] = 'no numerico';
								$error = true;
							} else	$this->errores[$campo['nombre']] = 'no';
						} else {
							//por ahora nada
							$this->errores[$campo['nombre']] = 'no';
						}
					}
				} elseif ($campo['tipo']=='ARCHIVO') {
					//if (isset($GLOBALS['_archivo_'+$campo['nombre']])) {
					/*
					if (isset($HTTP_POST_FILES['_archivo_'.$campo['nombre']]['tmp_name'])) {
						//todo bien
						$this->errores[$campo['nombre']] = 'no';
					} else {		
						$this->errores[$campo['nombre']] = 'No se definió _archivo_'.$campo[nombre];									
						$error = 1;
					}
					*/
				} elseif ($campo['tipo']=='PASSWORD') {
					if ($GLOBALS['_p_'+$campo['nombre']]=='cambiar') {
						if ($GLOBALS['_e_'+$campo['nombre']]=='') {
							$this->errores[$campo['nombre']] = 'nulo';
							$error = true;	
						} else {
							$this->errores[$campo['nombre']] = 0;
						}
					}
				}
				if ($this->debug=='si') 
					Debug('Any error on:'.$campo['nombre'].' >> '.$this->errores[$campo['nombre']].'<br>');
			}
		}

		return $error;
	}

	/* Genera un select que tiene como opciones los valores de "campoclave" */
	/* modo: 'hereda' (toma el valor de la variable de mismo nombre) */
	/*       '' (vacio: Todos--- por defecto) */
	/* nombre: nombre de la variable a la que se le asigna el valor*/	
	/* etiqueta: nombre con el que se muestra el combo*/		
	/* tabla: nombre de la tabla de donde se extrae el valor de  la opcion*/			
	/* campoclave: valor de opcion*/				
	/* campomuestra: valor con el que figura la opcion*/				
	/* tablas: array de tablas anidadas*/					
	/* claves: array de claves anidadas (mismo orden que tablas)*/					
	/* muestras: array de muestras anidadas  (mismo orden que tablas)*/						
	/* orden: agrega el ORDEN BY <orden>*/						
	/* nested: valores externos que condicionan la busqueda, de la forma: CAMPOX='345345' AND CAMPOY='hola mundo' AND CAMPONN=3  (atencion!! si un valor no existe o esta vacio, al parsear este query se anulara la condicion)*/ 							
//				 Combo(''   ,'EMPRESA',''     ,'contenidos','ID','TITULO'      ,''        ,''        ,''          ,''       ,'contenidos.ID_TIPOCONTENIDO=3','');
	function Combo($modo,$nombre,$etiqueta,$tabla,$campoclave,$campomuestra,$tablas='',$claves='',$muestras='',$orden='',$nested='',$escondido='') {
			if ($modo=='hereda') {
				if (!isset($GLOBALS['_fcomboe_'.$nombre])) {
					$GLOBALS['_fcomboe_'.$nombre] = $GLOBALS['_fcombo_'.$nombre];
				}
			}
			if ($modo!='') $modo = 'e';
			$defecto = $GLOBALS['_fcombo'.$modo.'_'.$nombre];
			
			if ($escondido=='') {
				$this->SQL = "SELECT ".$tabla.".".$campoclave;
				$this->SQLCOUNT = "SELECT COUNT(*) ";
				if ($campomuestra!='') 	$this->SQL.=",".$tabla.".".$campomuestra;
				$tablasref = " FROM ".$tabla;

				//si hay tablas referenciadas las proceso aqui	
							
				if ($claves!='') {
					$muestrasref = "";				
					$guardasref = "";
					$and = '';
					$coma = '';
					foreach($claves as $clave=>$claveref) {
						$muestrasref.= $coma.$tablas[$clave].".".$muestras[$clave];
						$tablasref.= ",".$tablas[$clave];
						$guardasref.= $and.$tablas[$clave].".".$claveref."=".$tabla.".".$clave;
						$and = ' and ';
						$coma = ' , ';
					}				
					if ($muestrasref!="") $this->SQL.= ",".$muestrasref;
				}
					
				$this->SQL.=$tablasref;
				$this->SQLCOUNT.=$tablasref;				
				
				/*
				//si esta filtrado por otro combo
				$guardaSQL = '';
				$guardas = explode(" AND ",$nested);
				$p = true;
				foreach ($guardas as $guarda) {
					$guardac = explode("=",$guarda);					
					if ($guardac[1]!='') {						
						if ($p) { $guardaSQL.= " WHERE "; $p = false; } else { $guardaSQL.=' AND ';}
						$guardaSQL.= $guardac[0]."=".$guardac[1];
					}
				}
				$this->SQL.= $guardaSQL;
				$this->SQLCOUNT.=$guardaSQL;
				*/
				$guardas_q = '';
				$guardas_sql = '';	
				$p = true;		
				if ($nested!='') {
					$guardas = explode("/*SPECIAL*/",$nested);
					if ($guardas[0]!=$nested) {
						$guardas_q[$nested] = 'si';
						if ($p) { $guardas_sql.= " WHERE "; $p = false; }
					} else {						
						$guardas = explode(" AND ",$nested);
						foreach ($guardas as $guarda) {
							$guardac = explode("=",$guarda);					
							if ($guardac[1]!='') {						
								if ($p) { $guardas_sql.= " WHERE "; $p = false; }
								$campos_q[$guardac[0]]='si';
								$guardas_q[$guardac[0]."=".$guardac[1]]='si';
							}
						}
					}
					if ($guardas_q!='') {
						$and = '';
						foreach ($guardas_q as $KK=>$VV) {
							$guardas_sql.= $and.$KK." ";
							$and = ' and ';
						}
					}				
					$this->SQL.= $guardas_sql;
					$this->SQLCOUNT.= $guardas_sql;				
				}				
				
					
				//si hay tablas referenciadas las ponemos al final
				if ($guardasref!="") {
					$p ? $this->SQL.= " WHERE " : $this->SQL.= " AND ";
					$p ? $this->SQLCOUNT.= " WHERE " : $this->SQLCOUNT.= " AND ";
					$this->SQL.= $guardasref;
					$this->SQLCOUNT.=$guardasref;
				}
				
				if ($orden!='') $this->SQL.= " ORDER BY ".$orden;
				$this->Open();
				echo '<table cellspacing="0" cellpadding="0"><tr><td colspan="3" class="titulocombo">'.$etiqueta.'</td></tr>';
				echo '<tr><td><SELECT NAME="_fcombo'.$modo.'_'.$nombre.'" SIZE="1" onChange="javascript:filtrarcombos'.$modo.'();"  class="combo">';
				if ($defecto=='') $selected = 'SELECTED'; else $selected = '';
				echo '<OPTION VALUE="" '.$selected.' class="combo">'.$GLOBALS['CLang']->m_Words['ALL'].' - - - -</OPTION>';
				
				if ($this->nresultados>0) {
					while ($row = $this->Fetch($this->resultados)) {
						if (($defecto==$row[$tabla.".".$campoclave]) and $selected=='') $selected='SELECTED'; else $selected = '';							
						if ($campomuestra!='') $rowmuestras = $row[$tabla.".".$campomuestra]; else $rowmuestras = "";
						if ($muestras!='') foreach($muestras as $clave=>$muestra) $rowmuestras.= " ".$row[$tablas[$clave].".".$muestra];
						echo '<OPTION VALUE="'.$row[$tabla.".".$campoclave].'"  '.$selected.' class="combo">'.$rowmuestras.'</OPTION>';
					}
				}
				$this->FinalizarSQL();
				echo '</SELECT></td></tr></table>';	
			} else {
				echo '<INPUT type="hidden" NAME="_fcombo'.$modo.'_'.$nombre.'" VALUE="'.$defecto.'">';
			}
	}


	//AGREGAR
	//filtro de fecha
	//mejorar filtro de numero
	//HECHO
	//filtro de texto
	//filtro de numero
	//filtro de blobs de texto
	
	function FiltrarCampo($nombre,$nested='',$escondido='') {
		echo	$this->FiltrarCampoStr($nombre,$nested,$escondido);
	}	
	

	function FiltrarCampoStr($nombre,$nested='',$escondido='') {

		$resstr = "";
		
		$campo = $this->campos[$nombre];
		$referencias = $campo['referencias'];

		$campos_q='';
		$tablas_q='';
		$guardas_q='';
		
		$defecto = $GLOBALS['_f_'.$nombre];
		$tipofiltro = $GLOBALS['_tf_'.$nombre];		
		
		if ($escondido=='' || $escondido=='label') {			
			if ($campo['nreferencias']==0) {
				if ($escondido=='') {					
					if (($campo['tipo'] == 'TEXTO') or ($campo['tipo'] == 'BLOBTEXTO')) {
						if ($tipofiltro=='_empieza_'.$campo['nombre']) $empieza = 'checked'; else $empieza = '';
						if ($tipofiltro=='_contiene_'.$campo['nombre']) $contiene = 'checked'; else $contiene = '';
						$resstr.= '<table cellspacing="0" cellpadding="0"><tr><td colspan="3"><span class="titulofiltro">'.$campo['etiqueta'].'</span></td></tr>';
						$resstr.= '<tr><td><input name="_f_'.$campo['nombre'].'" type="text" value="'.$defecto.'" size="'.$campo['tamanio'].'" class="filtro"></td>';
						$resstr.= '<td><input name="_tf_'.$campo['nombre'].'" type="hidden" value="'.$tipofiltro.'"></td>';
						$resstr.= '<td class="tipofiltro"><input name="_empieza_'.$campo['nombre'].'" type="checkbox" '.$empieza.' onClick="'."javascript:filtroempieza('".$campo['nombre']."');".'"  class="tipofiltro">empieza por</td>';
						$resstr.= '<td class="tipofiltro"><input name="_contiene_'.$campo['nombre'].'" type="checkbox" '.$contiene.' onClick="'."javascript:filtrocontiene('".$campo['nombre']."');".'"  class="tipofiltro">contiene</td></tr></table>';
					} elseif (($campo['tipo'] == 'ENTERO') or ($campo['tipo'] == 'DECIMAL')) {
						if ($tipofiltro=='_inferior_'.$campo['nombre']) $inferiora = 'checked'; else $inferiora = '';
						if ($tipofiltro=='_superior_'.$campo['nombre']) $superiora = 'checked'; else $superiora = '';
						if ($escondido=='') $tipoinput = 'text'; else $tipoinput = $escondido;
						$resstr.= '<table cellspacing="0" cellpadding="0"><tr><td colspan="3"><span class="titulofiltro">'.$campo['etiqueta'].'</span></td></tr>';
						$resstr.= '<tr><td><input name="_f_'.$campo['nombre'].'" type="text" value="'.$defecto.'" size="'.$campo['tamanio'].'" class="filtro"></td>';
						$resstr.= '<td><input name="_tf_'.$campo['nombre'].'" type="hidden" value="'.$tipofiltro.'"></td>';
						$resstr.= '<td class="tipofiltro"><input name="_inferior_'.$campo['nombre'].'" type="checkbox" '.$inferiora.' onClick="'."javascript:filtroinferior('".$campo['nombre']."');".'" class="tipofiltro">inferior a</td>';
						$resstr.= '<td class="tipofiltro"><input name="_superior_'.$campo['nombre'].'" type="checkbox" '.$superiora.'  onClick="'."javascript:filtrosuperior('".$campo['nombre']."');".'" class="tipofiltro">superior a</td></tr></table>';
					} elseif (($campo['tipo'] == 'FECHA')) {
						if ($tipofiltro=='_inferior_'.$campo['nombre']) $inferiora = 'checked'; else $inferiora = '';
						if ($tipofiltro=='_superior_'.$campo['nombre']) $superiora = 'checked'; else $superiora = '';					
						$resstr.= '<table cellspacing="0" cellpadding="0"><tr><td colspan="3"><span class="titulofiltro">'.$campo['etiqueta'].'</span></td></tr>';
						$resstr.= '<tr><td><input id="_f_'.$campo['nombre'].'" name="_f_'.$campo['nombre'].'" type="text" value="'.$defecto.'" size="'.$campo['tamanio'].'" class="filtro"></td>';
						$resstr.= '<td><a href="javascript:NewCal(\'_f_'.$campo['nombre'].'\',\'yyyymmdd\',true,24)"><img src="'.$GLOBALS['_DIR_SITEABS'].'/inc/images/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a></td>';					
						$resstr.= '<td><input name="_tf_'.$campo['nombre'].'" type="hidden" value="'.$tipofiltro.'"></td>';
						$resstr.= '<td class="tipofiltro"><input name="_inferior_'.$campo['nombre'].'" type="checkbox" '.$inferiora.' onClick="'."javascript:filtroinferior('".$campo['nombre']."');".'" class="tipofiltro">inferior a</td>';
						$resstr.= '<td class="tipofiltro"><input name="_superior_'.$campo['nombre'].'" type="checkbox" '.$superiora.'  onClick="'."javascript:filtrosuperior('".$campo['nombre']."');".'"  class="tipofiltro">superior a</td></tr></table>';
					}
				} else if ($escondido=='label') {
					$resstr.= '<table cellspacing="0" cellpadding="0"><tr><td colspan="3"><span class="titulofiltro">';
					$resstr.= $defecto.'<input type="hidden" name="_f_'.$campo['nombre'].'" value="'.$defecto.'">';
					$resstr.= '<input name="_tf_'.$campo['nombre'].'" type="hidden" value="'.$tipofiltro.'"></span></td></tr></table>';								
				}
			} else {
				$referencia = $referencias[0];
				if (($referencia['tipo']=='directa') or ($referencia['tipo']=='autoreferencia') or ($referencia['tipo']=='multiples')) {
					for( $i = 0; $i<$campo['nreferencias']; $i++) {
						$referencia_mas = $referencias[$i];
								if ( ($referencia_mas['tipo'] == 'directa') or ($referencia_mas['tipo'] == 'multiples')) {
									$campos_q[$referencia_mas['tabla'].".".$referencia_mas['clave']]='si';
									$campos_q[$referencia_mas['tabla'].".".$referencia_mas['muestra']]='si';
									$tablas_q[$referencia_mas['tabla']]='si';				
									if ($referencia_mas['orden']!="")
										$orden_q[$referencia_mas['tabla'].".".$referencia_mas['orden']]='si';
									//acá la guarda no va, porque queremos todos los datos para filtrar //$guardas_q[$this->nombre.".".$campo['nombre']."=".$referencia_mas['tabla'].".".$referencia_mas['clave']]='si';								
								} elseif ($referencia_mas['tipo'] == 'autoreferencia') {
									//$campos_q[$referencia_mas['tabla'].".".$referencia_mas['muestra']]='si';
									$campos_q[$referencia_mas['tabla'].".".$referencia_mas['clave']]='si';
									$campos_q[$referencia_mas['tabla'].".".$referencia_mas['muestra']]='si';
									$tablas_q[$this->nombre." ".$referencia_mas['tabla']]='si';
									//acá no se incluye la tabla porque sino se repiten datos //$tablas_q[$this->nombre]='si';	
									//acá la guarda no va, porque queremos todos los datos para filtrar//$guardas_q[$this->nombre.".".$campo['nombre']."=".$referencia_mas['tabla'].".".$referencia_mas['clave']]='si';
								} elseif ($referencia_mas['tipo'] == 'anidada') {
									$campos_q[$referencia_mas['tabla'].".".$referencia_mas['muestra']]='si';
									$tablas_q[$referencia_mas['tabla']]='si';				
									$guardas_q[$referencia_mas['tabla1'].".".$referencia_mas['clave1']."=".$referencia_mas['tabla'].".".$referencia_mas['clave']]='si';
								}
					}
	
				
					if ($nested!='') {						
						$guardas = explode("/*SPECIAL*/",$nested);
						if ($guardas[0]!=$nested) {
							$guardas_q[$nested] = 'si';
						} else {						
							$guardas = explode(" AND ",$nested);
							foreach ($guardas as $guarda) {
								$guardac = explode("=",$guarda);					
								if ($guardac[1]!='') {						
									$campos_q[$guardac[0]]='si';
									$guardas_q[$guardac[0]."=".$guardac[1]]='si';
								}
							}
						}
					}
	
					$campos_sql = '';
					$tablas_sql = '';
					$guardas_sql = '';
					$orden_sql = '';
	
					if ($campos_q!='') {
						$coma = '';
						foreach ($campos_q as $KK=>$VV) {
							$campos_sql.= $coma.$KK." ";
							$coma = ',';
						}
					}
					if ($tablas_q!='') {
						$coma = '';
						foreach ($tablas_q as $KK=>$VV) {
							$tablas_sql.= $coma.$KK." ";
							$coma = ',';
						}
					}
					if ($guardas_q!='') {
						$and = '';
						foreach ($guardas_q as $KK=>$VV) {
							$guardas_sql.= $and.$KK." ";
							$and = ' and ';
						}
					}
					if ($orden_q!='') {
						$and = '';
						foreach ($orden_q as $KK=>$VV) {
							$orden_sql.= $and.$KK." ";
							$and = ', ';
						}
					}					
					
					$this->SQL = "SELECT ".$campos_sql." FROM ".$tablas_sql;
					if ($guardas_sql!='') $this->SQL.=" WHERE ".$guardas_sql;
					
					//$this->SQLCOUNT = "SELECT COUNT() FROM ".$tablas_sql." WHERE ".$guardas_sql;
					
					//mostramos todo
					if ($escondido=='') {
						if ($orden_sql!='') $this->SQL.=" ORDER BY ".$orden_sql;
						$this->Open();
						$resstr.= '<table cellspacing="0" cellpadding="0"><tr><td colspan="3"><span class="titulofiltro">'.$referencia['etiqueta'].'</span></td></tr>';
						$resstr.= '<tr><td><SELECT NAME="_f_'.$campo['nombre'].'" SIZE="1" class="filtro">';
						if ($defecto=='') $selected = 'SELECTED'; else $selected = '';
						$resstr.= '<OPTION VALUE="" '.$selected.' class="filtro">'.$GLOBALS['CLang']->m_Words['ALL'].' - - - -</OPTION>';
						
						//ShowMessage( "REFERENCIA:".print_r( $referencia,true ) );
						//ShowMessage( "CAMPOS_SQL:".$campos_sql );
												
						if ($this->nresultados>0) {
							while ($row = $this->Fetch(  $this->resultados, $RAW_RESULTS=true, $campos_sql ) ) {								
								$rowmuestras='';
								(($defecto==$row[$referencia['tabla'].".".$referencia['clave']]) and $selected=='') ? $selected='SELECTED' : $selected='';
								foreach($referencias as $ref) ($ref['muestra']!='') ? $rowmuestras.= " ".$row[$ref['tabla'].".".$ref['muestra']] : $rowmuestras.='';
								$resstr.= '<OPTION VALUE="'.$row[$referencia['tabla'].".".$referencia['clave']].'"  '.$selected.' class="combo">'.$rowmuestras.'</OPTION>';
							}
						}
						$this->FinalizarSQL();
						$resstr.= '</SELECT>';
						$resstr.= '</td></tr></table>';
					} else if ($escondido=='label') {
						if ($guardas_sql!='') $this->SQL.=" AND "; else $this->SQL.=" WHERE ";
						$this->SQL.= $referencia['tabla'].".".$referencia['clave']."=".$defecto;
						if ($orden_sql!='') $this->SQL.=" ORDER BY ".$orden_sql;
						$this->Open();
						if ($this->nresultados>0) {
							$row = $this->Fetch( "", $RAW_RESULTS=true, $campos_sql );
							foreach($referencias as $ref) ($ref['muestra']!='') ? $rowmuestras.= " ".$row[$ref['tabla'].".".$ref['muestra']] : $rowmuestras.='';
							$resstr.= '<table cellspacing="0" cellpadding="0"><tr><td colspan="3"><span class="titulofiltro">';
							$resstr.= $rowmuestras.'<input type="hidden" name="_f_'.$campo['nombre'].'" value="'.$defecto.'"></span></td></tr></table>';							
						} else $resstr.= $GLOBALS['CLang']->m_Words['NORESULTS'];
					}
	
				} elseif ($referencia['tipo']=='combo') {
					if ($escondido=='') {
						$resstr.= '<table cellspacing="0" cellpadding="0"><tr><td colspan="3"><span class="titulofiltro">'.$referencia['etiqueta'].'</span></td></tr>';
						$resstr.= '<tr><td>';				
						$resstr.= '<SELECT NAME="_f_'.$campo['nombre'].'" SIZE="1" class="filtro">';
						if ($defecto=='') $selected = 'SELECTED'; else $selected = '';
						$resstr.= '<OPTION VALUE=""  '.$selected.' class="filtro">'.$GLOBALS['CLang']->m_Words['ALL'].' - - - -</OPTION>';								
						$combo = $referencia['combo'];
						foreach($combo as $k=>$comboitem) {										
							/*
							if (is_numeric($k)) {
								if (($defecto==$comboitem) and $selected=='') $selected='SELECTED'; else $selected = '';
								$resstr.= '<OPTION VALUE="'.$comboitem.'"  '.$selected.' class="filtro">'.$comboitem.'</OPTION>';					
							} else { 
							*/
								if (($defecto==$k) and $selected=='') $selected='SELECTED'; else $selected = '';
								$resstr.= '<OPTION VALUE="'.$k.'"  '.$selected.' class="filtro">'.$comboitem.'</OPTION>';
							/*}*/
						}					$resstr.= '</SELECT>';
						$resstr.= '</td></tr></table>';
					} else if ($escondido=='label') {
						$combo = $referencia['combo'];
						foreach($combo as $k=>$comboitem) {										
							if (is_numeric($k)) {
								if (($defecto==$comboitem) and $selected=='') {
									$resstr.= '<table cellspacing="0" cellpadding="0"><tr><td colspan="3"><span class="titulofiltro">';
									$resstr.= $comboitem.'<input type="hidden" name="_f_'.$campo['nombre'].'" value="'.$comboitem.'"></span></td></tr></table>';
								}
							} else { 
								if (($defecto==$k) and $selected=='') {
									$resstr.= '<table cellspacing="0" cellpadding="0"><tr><td colspan="3"><span class="titulofiltro">';
									$resstr.= $comboitem.'<input type="hidden" name="_f_'.$campo['nombre'].'" value="'.$k.'"></span></td></tr></table>';
								}
								
							}
						}
					}								
				} else 	die('Error: La primer referencia debe ser de tipo \'directa\' o \'combo\' o \'autoreferencia\'> campo:'.$campo['nombre']);
			}
		} else if ($escondido=='escondido') {
			$resstr.= '<INPUT type="hidden" NAME="_f_'.$campo['nombre'].'" VALUE="'.$defecto.'">';
		}			
		
		
		return $resstr;
	}
	
	//AGREGAR:
	//edicion de archivos
	//edicion de campos por checkbox
	//HECHO:
	//edicion de texto
	//edicion de blob texto
	//edicion de numeros
	function EditarCampo($nombre,$nested='',$event='',$lang='',$form='', $min='' , $max='', $html='') {
		echo $this->EditarCampoStr( $nombre, $nested, $event, $lang, $form, $min, $max, $html );
	}


	function EditarCampoStr( $nombre, $nested='',$event='',$lang='',$form='', $min='' , $max='', $html='') {
		$resstr = "";
		$disabled = "";
		$selected = "";
				
		if ( strpos( $nombre, "PASSWORD" )===false )
			$campo = $this->campos[$nombre];
		else
			$campo = $this->campos["PASSWORD"];
		$referencias = $campo['referencias'];
		$guardas_q = "";
		if (isset($GLOBALS['_e_'.$nombre]))  { $defecto = $GLOBALS['_e_'.$nombre]; }
		else { $defecto = $campo['defecto']; }
		
		$defecto = str_replace( array('"',"'"),array("&quot;","&#39;"),$defecto);		 
		$defecto = $this->UnescapeString($defecto);
		
		
		$eti = trim($campo['etiqueta']);
		
		($campo['editable']=='no')? $disabled = "disabled" : $disabled = "";
		
		if ($form!="") { $formpoint = $form."."; } else { $formpoint=""; }
		
		if ($campo['nreferencias']==0) {
			$resstr.= '<div class="editar-campo">';
			if ($eti!="") {
				$resstr.= '<div id="etiqueta_'.$campo['nombre'].'" class="campo_etiqueta">'.$eti.'</div>';
			}
						
			if (($campo['tipo'] == 'TEXTO')) {				
				$resstr.= '<input '.$disabled.' id="'.$formpoint.'_e_'.$campo['nombre'].'" name="_e_'.$campo['nombre'].'" type="text" value="'.$defecto.'" size="'.$campo['tamanio'].'">';
				if ( is_numeric($max) || is_numeric($min) )
					$resstr.= TextCounter( $formpoint.'_e_'.$campo['nombre'], $min, $max );
			}
			elseif (($campo['tipo'] == 'TEXTOML')) {				
				if ($lang=="") {//la primera llamada sin el lang especificado, es para contener todos los idiomas
					$resstr.= '<input '.$disabled.' id="'.$formpoint.'_e_'.$campo['nombre'].'" name="_e_'.$campo['nombre'].'" type="hidden" value="'.$defecto.'" size="'.$campo['tamanio'].'" class="campo">';				
				} else {//aqui debe estar especificado el lang: EN | FR | SP
					$resstr.= '<input '.$disabled.' id="'.$formpoint.'_e_'.$campo['nombre'].'_'.$lang.'" name="_e_'.$campo['nombre'].'_'.$lang.'" type="text" value="'.$this->TextoML($defecto,$lang).'" size="'.$campo['tamanio'].'" class="campo" onChange="javascript:setForm(\''.$form.'\');completeML(\'_e_'.$campo['nombre'].'\',\''.$lang.'\')" >';	
				}
				if ( is_numeric($max) || is_numeric($min) )
					$resstr.= TextCounter( $formpoint.'_e_'.$campo['nombre'], $min, $max );
			}			
			elseif ($campo['tipo'] == 'FECHA') {
				if ($defecto!="") $defecto = date("Y-m-d h:i:s",strtotime($defecto));
				$resstr.= '<input '.$disabled.' type="text" id="'.$formpoint.'_e_'.$campo['nombre'].'" border="0" name="_e_'.$campo['nombre'].'" value="'.$defecto.'" size="'.$campo['tamanio'].'" class="campo">
				'.UI_DateTimePicker($formpoint.'_e_'.$campo['nombre']);
				//<a class="pickadate" href="javascript:setForm(\''.$form.'\');NewCal(\'_e_'.$campo['nombre'].'\',\'yyyymmdd\',true,24)"><img src="'.$GLOBALS['_DIR_SITEABS'].'/inc/images/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a>';
			}				
			elseif ($campo['tipo'] == 'BLOBTEXTO') {
				$resstr.= '<TEXTAREA '.$disabled.'  id="'.$formpoint.'_e_'.$campo['nombre'].'" name="_e_'.$campo['nombre'].'" rows="'.$campo['tamanio2'].'" cols="'.$campo['tamanio'].'" class="campo">'.$defecto.'</TEXTAREA>';
				if ( is_numeric($max) || is_numeric($min) )
					$resstr.= TextCounter( $formpoint.'_e_'.$campo['nombre'], $min, $max );				
				if ($html=="html")						
					$resstr.= '<script> 
								setForm(\''.$form.'\'); 
								textareaEdit( \''.$formpoint.'_e_'.$campo['nombre'].'\',\'\' ); 
							</script>';				
			}
			elseif ($campo['tipo'] == 'BLOBTEXTOML') {
				if ($lang=="") {//la primera llamada sin el lang especificado, es para contener todos los idiomas
					$resstr.= '<input id="'.$formpoint.'_e_'.$campo['nombre'].'" name="_e_'.$campo['nombre'].'" type="hidden" value="'.$defecto.'" size="'.$campo['tamanio'].'" class="campo">';				
				} else {//aqui debe estar especificado el lang: EN | FR | SP
					$resstr.= '<TEXTAREA '.$disabled.' id="'.$formpoint.'_e_'.$campo['nombre'].'_'.$lang.'" name="_e_'.$campo['nombre'].'_'.$lang.'" rows="'.$campo['tamanio2'].'" cols="'.$campo['tamanio'].'" class="campo" onChange="javascript:setForm(\''.$form.'\');completeML(\'_e_'.$campo['nombre'].'\',\''.$lang.'\')">'.$this->TextoML($defecto,$lang).'</TEXTAREA>';
				}			
				if ( is_numeric($max) || is_numeric($min) )
					$resstr.= TextCounter( $formpoint.'_e_'.$campo['nombre'], $min, $max );
				
				if ($html=="html")
					$resstr.= '<script> 
								setForm(\''.$form.'\'); 
								textareaEdit( \''.$formpoint.'_e_'.$campo['nombre'].'\',\''.$lang.'\' ); 
							</script>';
			}			
			elseif (($campo['tipo'] == 'ENTERO') or ($campo['tipo'] == 'DECIMAL')) {
				$resstr.= '<input '.$disabled.'  id="'.$formpoint.'_e_'.$campo['nombre'].'" name="_e_'.$campo['nombre'].'" type="text" value="'.$defecto.'" size="'.$campo['tamanio'].'" class="campo">';
			}
			elseif ($campo['tipo'] == 'PASSWORD') {
				if ( $nombre != $campo['nombre'] ) {
					if ($nombre=="PASSWORD_new")
						$nombre = "PASSWORD";
					$resstr.= '<input '.$disabled.' id="'.$formpoint.'_e_'.$nombre.'" name="_e_'.$nombre.'" type="password" value="" size="'.$campo['tamanio'].'" class="campo">';
				} else {
					$resstr.= '<label id="_label_'.$campo['nombre'].'">'.$GLOBALS['CLang']->m_Users['USERPASSWORD'].'</label><input id="_e_'.$campo['nombre'].'" name="_e_'.$campo['nombre'].'" type="password" value="" size="'.$campo['tamanio'].'" class="campo">';
					$resstr.= '<label id="_label_'.$campo['nombre'].'_confirm">'.$GLOBALS['CLang']->m_Users['USERPASSWORDCONFIRM'].'</label><input id="_e_'.$campo['nombre'].'_confirm" name="_e_'.$campo['nombre'].'_confirm" type="password" value="" size="'.$campo['tamanio'].'" class="campo">';
				}
			}
			elseif ($campo['tipo'] == 'ARCHIVO') {
				$resstr.= '<input '.$disabled.'  id="'.$formpoint.'_e_'.$campo['nombre'].'" name="_archivo_'.$campo['nombre'].'" type="file" size="'.$campo['tamanio'].'" class="campo">';
			}
			$resstr.= '</div>';
		} else {//si el campo tiene alguna referencia, entonces: mostrar un combo con el lookup
			$referencia = $referencias[0];
			if ( ($referencia['tipo']=='multiples') or ($referencia['tipo']=='directa') or ($referencia['tipo']=='autoreferencia') ) {
				for( $i = 0; $i<$campo['nreferencias']; $i++) {
					$referencia_mas = $referencias[$i];
							if ($referencia_mas['tipo'] == 'directa' or $referencia_mas['tipo'] == 'multiples') {
								
								$aliases = explode( " ", $referencia_mas['tabla'] );
								if (count($aliases)>1) {
									$latabla = $aliases[0];
									$elalias = $aliases[1];
								} else {
									$latabla = $aliases[0];
									$elalias = $latabla;
								}								
								
								$campos_q[$elalias.".".$referencia_mas['clave']]='si';								
								if ( $referencia_mas['muestra'] == "NOMBRE" && $lang!='' ) {
									$referencias[$i]['muestra'] = "ML_NOMBRE";
									$campos_q[ $elalias.".ML_".$referencia_mas['muestra']]='si';
								} else if ( $referencia_mas['muestra'] == "TITULO" && $lang!='' ) {
									$referencias[$i]['muestra'] = "ML_TITULO";
									$campos_q[ $elalias.".ML_".$referencia_mas['muestra']]='si';
								} else $campos_q[ $elalias.".".$referencia_mas['muestra']]='si'; 

								if ($referencia_mas['orden']!="")
									$orden_q[$elalias.".".$referencia_mas['orden']]='si';
								
								$tablas_q[$referencia_mas['tabla']]='si';				
								//acá la guarda no va, porque queremos todos los datos para filtrar //$guardas_q[$this->nombre.".".$campo['nombre']."=".$referencia_mas['tabla'].".".$referencia_mas['clave']]='si';								
							} elseif ($referencia_mas['tipo'] == 'autoreferencia') {
								//$campos_q[$referencia_mas['tabla'].".".$referencia_mas['muestra']]='si';
								$campos_q[$referencia_mas['tabla'].".".$referencia_mas['clave']]='si';
								$campos_q[$referencia_mas['tabla'].".".$referencia_mas['muestra']]='si';
								$tablas_q[$this->nombre." ".$referencia_mas['tabla']]='si';
								//acá no se incluye la tabla porque sino se repiten datos //$tablas_q[$this->nombre]='si';	
								//acá la guarda no va, porque queremos todos los datos para filtrar//$guardas_q[$this->nombre.".".$campo['nombre']."=".$referencia_mas['tabla'].".".$referencia_mas['clave']]='si';
							} elseif ($referencia_mas['tipo'] == 'anidada') {
								$campos_q[$referencia_mas['tabla'].".".$referencia_mas['muestra']]='si';
								$tablas_q[$referencia_mas['tabla']]='si';				
								$guardas_q[$referencia_mas['tabla1'].".".$referencia_mas['clave1']."=".$referencia_mas['tabla'].".".$referencia_mas['clave']]='si';
							}
				}
				if ($nested=="") {
					if ($referencia['nested']!="")
						$nested = $referencia['nested'];
				}

				if ($nested!='') {						
					$guardas = explode("/*SPECIAL*/",$nested);
					if ($guardas[0]!=$nested) {
						$guardas_q[$nested] = 'si';
					} else {						
						$guardas = explode(" AND ",$nested);
						foreach ($guardas as $guarda) {
							$guardac = explode("=",$guarda);					
							if ($guardac[1]!='') {						
								$campos_q[$guardac[0]]='si';
								$guardas_q[$guardac[0]."=".$guardac[1]]='si';
							}
						}
					}
				}

				$campos_sql = '';
				$tablas_sql = '';
				$guardas_sql = '';
				$orden_sql= '';

				if ($campos_q!='') {
					$coma = '';
					foreach ($campos_q as $KK=>$VV) {
						$campos_sql.= $coma.$KK." ";
						$coma = ',';
					}
				}
				if ($tablas_q!='') {
					$coma = '';
					foreach ($tablas_q as $KK=>$VV) {
						$tablas_sql.= $coma.$KK." ";
						$coma = ',';
					}
				}
				if ($guardas_q!='') {
					$and = '';
					foreach ($guardas_q as $KK=>$VV) {
						$guardas_sql.= $and.$KK." ";
						$and = ' and ';
					}
				}
				if ($orden_q!='') {
					$and = '';
					foreach ($orden_q as $KK=>$VV) {
						$orden_sql.= $and.$KK." ";
						$and = ', ';
					}
				}				
				
				$this->SQL = "SELECT ".$campos_sql." FROM ".$tablas_sql;
				if ($guardas_sql!='') $this->SQL.=" WHERE ".$guardas_sql;
				if ($orden_sql!='') $this->SQL.=" ORDER BY ".$orden_sql;
				//$this->SQLCOUNT = "SELECT COUNT() FROM ".$tablas_sql." WHERE ".$guardas_sql;
				//ShowMessage(" SQL:".$this->SQL);
				$this->Open();
				
				$aliases = explode( " ", $referencia['tabla'] );
				if (count($aliases)>1) {
					$latabla = $aliases[0];
					$elalias = $aliases[1];
				} else {
					$latabla = $aliases[0];
					$elalias = $latabla;
				}				
	
				//mostramos todo
//				if ($defecto=='') $selected = 'SELECTED'; else $selected = '';
//				$resstr.= '<OPTION VALUE="" '.$selected.' class="filtro">Vacio - - - -</OPTION>';
				$value_id = 2;
				$value_str = "";
				if ($this->nresultados>0) {
					$resstr.= '<table class="editar-campo" cellspacing="0" cellpadding="0"><tr><td colspan="3"><span class="titulocampo">'.$referencia['etiqueta'].'</span></td></tr>
					<tr><td>';
						
					$multiple = "";
					$multiple_str = "";
					$multiple_script = "";
					$multiple_n = 0;
					
					if ( $this->nresultados<200 ) {
						$size_n = "1";						
						
						if ( $referencia['tipo'] == "multiples" ) { 
								$size_n = "30"; 
								$multiple='multiple="multiple"'; 
								$multiple_str="[]"; 
						}
						
						if ( $this->nresultados<100 && $multiple!='') {
							$resstr.= '<div class="multiple multiple-checkbox">';
						} else {
							$resstr.= '<SELECT '.$disabled.' '.$multiple.' 
														ID="'.$formpoint.'_e_'.$campo['nombre'].'" 
														NAME="_e_'.$campo['nombre'].$multiple_str.'" 
														SIZE="'.$size_n.'" 
														class="combo" '.$event.'>';
						}
					}
					//ShowMessage( "REFERENCIA:".print_r( $referencia,true ) );
					//ShowMessage( "CAMPOS_SQL:".$campos_sql );
						
					$valores = array();
																	
					if ($multiple) {
						$defecto_x = explode(",",trim($defecto));
						for($d=0;$d<count($defecto_x);$d++) {
							$def = trim($defecto_x[$d]);
							if (	is_numeric( strpos($def,'(') ) 
										||	is_numeric( strpos($def,'[') )	) 
								$def = trim(substr($def, 1, strlen($def) - 2));								
							$valores[$def] = "ok";
							//ShowMessage("[".$def."] valor: ".$valores[$def]);
						}
					}
					
					while ($row = $this->Fetch( "", $RAW_RESULTS=true, $campos_sql ) ) {
						
						//ShowMessage( "ALIAS:".$elalias.".".$referencia['clave'] );
						//ShowMessage( "ROW[...]:".print_r( $row[$elalias.".".$referencia['clave']], true ) );						
						//ShowMessage( "ROWRAW[]:".print_r( $row, true ) );
						
						$rowmuestras='';
						if ($multiple && is_array($valores)) {
							($valores[trim($row[$elalias.".".$referencia['clave']])]=="ok") ? $selected='SELECTED' : $selected='';
							

							if ($selected!="") {								
								if ( $this->nresultados<100 && $multiple!='') {
									//$multiple_script.= " s_multiple.options[".$multiple_n."].selected=true; "."\n";
								} else {
									$multiple_script.= " s_multiple.options[".$multiple_n."].selected=true; "."\n";								
								}
							}
							
						} else {
							((trim($defecto)==trim($row[$elalias.".".$referencia['clave']])) and $selected=='') ? $selected='SELECTED' : $selected='';
						}
						
						$multiple_n++;
						$coma = "";
						foreach($referencias as $ref) {
							$r_aliases = explode( " ", $ref['tabla'] );
							if (count($r_aliases)>1) {
								$r_latabla = $r_aliases[0];
								$r_elalias = $r_aliases[1];
							} else {
								$r_latabla = $r_aliases[0];
								$r_elalias = $r_latabla;
							}															
							if ( $lang!='') {
								if ( strpos($ref['muestra'],"ML_") == 0 ) {
									$rowmuestras.= $coma." ".TextoML( $row[$r_elalias.".".$ref['muestra']], $lang );
									$coma = ",";
								}
							} else {
								($ref['muestra']!='') ? $rowmuestras.= $coma." ".$row[$r_elalias.".".$ref['muestra']] : $rowmuestras.='';
								$coma = ",";
							}
							
							if ($selected=="SELECTED") {
								$value_str = $rowmuestras;
								$value_id = $row[$elalias.".".$referencia['clave']];								
							}
						}
						$GLOBALS['CLang']->Translate($rowmuestras);
						if ( $this->nresultados<200 ) {
							if ( $this->nresultados<100 && $multiple!="") {
								if ($selected=="SELECTED") $selected="checked";
								$resstr.= '<div class="multiple-checkbox-input">
									<input type="hidden" 
										ID="'.$formpoint.'_e_'.$campo['nombre'].'_'.trim($row[$elalias.".".$referencia['clave']]).'_clave"
										NAME="_e_'.$campo['nombre'].'_'.trim($row[$elalias.".".$referencia['clave']]).'_clave"
										value="'.trim($row[$elalias.".".$referencia['clave']]).'" >
									<input type="checkbox" 
											ID="'.$formpoint.'_e_'.$campo['nombre'].'_'.trim($row[$elalias.".".$referencia['clave']]).'"
											NAME="_e_'.$campo['nombre'].'_'.trim($row[$elalias.".".$referencia['clave']]).'"
											onchange="javascript:completar_multiple(\''.$formpoint.'_e_'.$campo['nombre'].'\',this.id);"
											'.$selected.'>
											<label>
											'.trim($row[$elalias.".".$referencia['clave']]).' - '.trim($rowmuestras).'</label></div>';
							} else $resstr.= '<OPTION VALUE="'.trim($row[$elalias.".".$referencia['clave']]).'"  '.$selected.' class="combo">'.trim($rowmuestras).'</OPTION>';
						}
					}
					
					if ( !( $this->nresultados < 200 ) ) {
						$resstr.= '<input ID="'.$formpoint.'_e_'.$campo['nombre'].'_str" NAME="_e_'.$campo['nombre'].'_str" type="text" value="'.$value_str.'">';
						$resstr.= '<input ID="'.$formpoint.'_e_'.$campo['nombre'].'" NAME="_e_'.$campo['nombre'].'" type="text" value="'.$defecto.'">';
					} else if ( $this->nresultados < 100  && $multiple!='') {
						$resstr.= '<input ID="'.$formpoint.'_e_'.$campo['nombre'].'" NAME="_e_'.$campo['nombre'].'" type="text" value="'.$defecto.'">';
					}
					
					if ( $this->nresultados<200 ) {
						
						if ( $this->nresultados<100 && $multiple!='') {
							$resstr.= '</div>';
						} else {
							$resstr.= '</SELECT>';
						}
						
						if ($multiple) {
							$multiple_script = "
								<script> 
								s_multiple = document.getElementById('".$formpoint.'_e_'.$campo['nombre']."');
								".$multiple_script."
								</script>";
							
							$resstr.= $multiple_script;
						}
					}
					$resstr.= '</td></tr></table>';
											
				} else {
					//sin resultados: OJO!
					//...
					$resstr.= '<SELECT '.$disabled.' ID="'.$formpoint.'_e_'.$campo['nombre'].'" NAME="_e_'.$campo['nombre'].'" SIZE="1" class="combo" '.$event.'>';
					$resstr.= '<OPTION VALUE=""  '.$selected.' class="combo">sin resultados</OPTION>';
					$resstr.= '</SELECT>';
				}
				$this->FinalizarSQL();
	
			} elseif ($referencia['tipo']=='combo') {
				$resstr.= '<table cellspacing="0" cellpadding="0"><tr><td colspan="3"><span class="titulocampo">'.$referencia['etiqueta'].'</span></td></tr>';
				$resstr.= '<tr><td>';				
				$resstr.= '<SELECT ID="'.$formpoint.'_e_'.$campo['nombre'].'" NAME="_e_'.$campo['nombre'].'" SIZE="1" class="combo">';
				//if ($defecto=='') $selected = 'SELECTED'; else $selected = '';
				//$resstr.= '<OPTION VALUE=""  '.$selected.' class="filtro">'.$GLOBALS['CLang']->m_Words['ALL'].' - - - -</OPTION>';								
				$combo = $referencia['combo'];
				foreach($combo as $k=>$comboitem) {										
					/*
					if (is_numeric($k)) {
						if (($defecto==$comboitem) and $selected=='') $selected='SELECTED'; else $selected = '';
						$comboitem2 = $comboitem;
						$GLOBALS['CLang']->Translate($comboitem2);
						$resstr.= '<OPTION VALUE="'.$comboitem.'"  '.$selected.' class="combo">'.$comboitem2.'</OPTION>';					
					} else { 
					*/
						if (($defecto==$k) && $selected=='') $selected='SELECTED'; else $selected = '';
						$GLOBALS['CLang']->Translate($comboitem);
						$resstr.= '<OPTION VALUE="'.$k.'"  '.$selected.' class="combo">'.$comboitem.'</OPTION>';
					/*}*/
				}
				$resstr.= '</SELECT>';
				$resstr.= '</td></tr></table>';								
			} else 	die('Error: La primer referencia debe ser de tipo \'directa\' o \'combo\'> campo:'.$campo['nombre']);
		}
		return $resstr;
	}
	
	
	//genera el query que trae todos los campos, junto con sus referencias
	function LimpiarSQL() {
		$this->Limite = false;
		$this->irow = -1;
		$this->startitem = '';
		$this->maxitems = '';
		$this->totalitems = '';
		$this->and = '';		
		$campos_q='';
		$tablas_q='';
		$guardas_q='';
		$orden_q = '';

		if (is_array($this->camposalias)) 
		foreach($this->camposalias as $k=>$v) {
			$campos_q[$k] = 'si';
		}
		if (is_array($this->tablasalias))
		foreach($this->tablasalias as $k=>$v) {
			$tablas_q[$k] = 'si';			
		}
		if (is_array($this->guardasalias))
		foreach($this->guardasalias as $k=>$v) {
			$guardas_q[$k] = 'si';			
		}	
		if (is_array($this->ordenalias))
		foreach($this->ordenalias as $k=>$v) {
			$orden_q[$k] = 'si';			
		}	
		
		foreach($this->campos as $campo) {
		  if ($campo['tipo']!='ARCHIVO') {//salteamos los 'ARCHIVOS'
		    //agrego el campo a devolver, y la tabla
			$campos_q[$this->nombre.".".$campo['nombre']] = 'si';
			$tablas_q[$this->nombre]='si';
			
			if ($campo['nreferencias']>0) {				
				$referencias = $campo['referencias'];
				
				foreach($referencias as $referencia) {
					if ($referencia['tipo'] == 'directa') {
						$aliases = explode( " ", $referencia['tabla'] );
						if (count($aliases)>1) {
							$latabla = $aliases[0];
							$elalias = $aliases[1];
						} else {
							$latabla = $aliases[0];
							$elalias = $latabla;
						}
						$campos_q[$elalias.".".$referencia['clave']]='si';
						$campos_q[$elalias.".".$referencia['muestra']]='si';
						$tablas_q[$referencia['tabla']]='si';				
						$guardas_q[$this->nombre.".".$campo['nombre']."=".$elalias.".".$referencia['clave']]='si';
						//$this->and = 'and ';
					} elseif ($referencia['tipo'] == 'autoreferencia') {						
						$campos_q[$referencia['tabla'].".".$referencia['clave']]='si';
						$campos_q[$referencia['tabla'].".".$referencia['muestra']]='si';
						$tablas_q[$this->nombre." ".$referencia['tabla']]='si';
						//$tablas_q[$this->nombre]='si';	
						$guardas_q[$this->nombre.".".$campo['nombre']."=".$referencia['tabla'].".".$referencia['clave']]='si';
						//$this->and = 'and ';
					} elseif ($referencia['tipo'] == 'anidada') {
						$campos_q[$referencia['tabla'].".".$referencia['muestra']]='si';
						$tablas_q[$referencia['tabla']]='si';				
						$guardas_q[$referencia['tabla1'].".".$referencia['clave1']."=".$referencia['tabla'].".".$referencia['clave']]='si';
						//$this->and = 'and ';
					}		
				}
			}
		 }//fin prueba 'ARCHIVO'
		}//fin foreach
		
		$campos_sql = '';
		$tablas_sql = '';
		$guardas_sql = '';
		$orden_sql = '';		

		if ($campos_q!='') {
			$coma = '';
			foreach ( $campos_q as $KK=>$VV ) {
				if ( $this->db['tipodb']=='sqlsrv' || $this->db['tipodb']=='mssql') {
					$campos_sql.= $coma.$KK." as '".$KK."' ";
				} else $campos_sql.= $coma.$KK." ";
				$coma = ',';
			}
		}
		if ($tablas_q!='') {
			$coma = '';
			foreach ($tablas_q as $KK=>$VV) {
				$tablas_sql.= $coma.$KK." ";
				$coma = ',';
			}
		}
		if ($guardas_q!='') {
			$and = '';
			foreach ($guardas_q as $KK=>$VV) {
				$guardas_sql.= $this->and.$KK." ";
				$this->and = 'and ';
			}
		}
		if ($orden_sql!='') {
			$and = '';
			foreach ($orden_q as $KK=>$VV) {
				$orden_sql.= $this->and.$KK." ";
				$this->and = 'and ';
			}
		}
		
		$this->SQL = "SELECT ".$campos_sql." FROM ".$tablas_sql;
		//$this->SQLCOUNT = "SELECT COUNT(".$campos_sql.") FROM ".$tablas_sql;
		$this->SQLCOUNT = "SELECT COUNT(*) FROM ".$tablas_sql;
		if ($guardas_sql!='') {
			$this->SQL.=" WHERE ".$guardas_sql;
			$this->SQLCOUNT.=" WHERE ".$guardas_sql;			
		}
	}


	function FiltrarSQL($nombre,$nested='',$valor='',$ttvalor='') {
		if ($valor=='') {
			if (isset($GLOBALS['_f_'.$nombre])) $valor = $GLOBALS['_f_'.$nombre];
		}
		if ($ttvalor=='') {
			if (isset($GLOBALS['_tf_'.$nombre])) $ttvalor = $GLOBALS['_tf_'.$nombre];
		}		
		if( is_numeric($valor) && $valor==0 ) $valor='0';
		if ($valor!='') {//si el $valor con el que filtramos es nulo, no lo tomamos en cuenta
			$campo = $this->campos[$nombre];
			if (!is_array($campo)) return ShowError('Field doesnt exist');
			$referencias = $campo['referencias'];
			
			if ($this->and!='and ') { $this->SQL.= 'WHERE '; $this->SQLCOUNT.= 'WHERE '; }
			
			$igual = '=';
			if ($ttvalor=='_empieza_'.$campo['nombre'] || $ttvalor=='empieza') { $empieza = '%'; } else { $empieza=''; }
			if ($ttvalor=='_contiene_'.$campo['nombre'] || $ttvalor=='contiene') { $contiene = '%'; }	else { $contiene=''; }
			if ($ttvalor=='_inferior_'.$campo['nombre'] || $ttvalor=='<' || $ttvalor=='<=') { $inferior = '<'; } else { $inferior=''; }
			if ($ttvalor=='_superior_'.$campo['nombre'] || $ttvalor=='>' || $ttvalor=='>=' ) { $superior = '>'; }	else { $superior=''; }
	
			if ($campo['nreferencias']==0) {
				if (($campo['tipo']=='TEXTO') or ($campo['tipo']=='BLOBTEXTO')) {
					$this->SQL.= $this->and.$this->nombre.".".$campo['nombre']." LIKE '".$contiene.$valor.$contiene.$empieza."' ";	
					$this->SQLCOUNT.= $this->and.$this->nombre.".".$campo['nombre']." LIKE '".$contiene.$this->EscapeString($valor).$contiene.$empieza."' ";
				}
				elseif (($campo['tipo']=='ENTERO') or ($campo['tipo']=='DECIMAL')) {
					$this->SQL.= $this->and.$this->nombre.".".$campo['nombre'].$inferior.$superior.$igual.$valor." ";
					$this->SQLCOUNT.=  $this->and.$this->nombre.".".$campo['nombre'].$inferior.$superior.$igual.$valor." ";
				}
				elseif (($campo['tipo']=='FECHA') or ($campo['tipo']=='HORA')) {
					$this->SQL.= $this->and.$this->nombre.".".$campo['nombre'].$inferior.$superior.$igual."'".$valor."' ";
					$this->SQLCOUNT.=  $this->and.$this->nombre.".".$campo['nombre'].$inferior.$superior.$igual."'".$valor."' ";
				}
				elseif (($campo['tipo']=='PASSWORD') and ($this->db['tipodb']=='mysql' || $this->db['tipodb']=='mysqli')) {
					$this->SQL.= $this->and.$this->nombre.".".$campo['nombre'].$igual."".$GLOBALS['_PASSWORD_VERSION']."('".$valor."') ";
					$this->SQLCOUNT.= $this->and.$this->nombre.".".$campo['nombre'].$igual."".$GLOBALS['_PASSWORD_VERSION']."('".$valor."') ";
				}
			} else {
				$referencia = $referencias[0];//la directa, obviamente
				if ($referencia['tipo']=='directa') {
					if (($campo['tipo']=='TEXTO') or ($campo['tipo']=='BLOBTEXTO')) {
						$this->SQL.= $this->and.$this->nombre.".".$campo['nombre']." LIKE '".$valor."' ";
						$this->SQLCOUNT.= $this->and.$this->nombre.".".$campo['nombre']." LIKE '".$valor."' ";
					} else {
						$this->SQL.= $this->and.$this->nombre.".".$campo['nombre'].$inferior.$superior.$igual.$valor." ";
						$this->SQLCOUNT.= $this->and.$this->nombre.".".$campo['nombre'].$inferior.$superior.$igual.$valor." ";						
					}
				} else	if ($referencia['tipo']=='multiples') {
					if (($campo['tipo']=='TEXTO') or ($campo['tipo']=='BLOBTEXTO')) {
						$this->SQL.= $this->and.$this->nombre.".".$campo['nombre']." LIKE '%(".trim($valor).")%' ";
						$this->SQLCOUNT.= $this->and.$this->nombre.".".$campo['nombre']." LIKE '%(".trim($valor).")%' ";
					}
				} elseif ($referencia['tipo']=='autoreferencia') {
					$this->SQL.= $this->and.$this->nombre.".".$campo['nombre'].$inferior.$superior.$igual.$valor." ";
					$this->SQLCOUNT.= $this->and.$this->nombre.".".$campo['nombre'].$inferior.$superior.$igual.$valor." ";
				} elseif ($referencia['tipo']=='combo') {
					if (($campo['tipo']=='ENTERO') or ($campo['tipo']=='DECIMAL')) {  	
						$this->SQL.= $this->and.$this->nombre.".".$campo['nombre'].$inferior.$superior.$igual.$valor." ";
						$this->SQLCOUNT.= $this->and.$this->nombre.".".$campo['nombre'].$inferior.$superior.$igual.$valor." ";
						
					} elseif (($campo['tipo']=='TEXTO') or ($campo['tipo']=='BLOBTEXTO')) {
						$this->SQL.= $this->and.$this->nombre.".".$campo['nombre']."='".$this->EscapeString($valor)."' ";
						$this->SQLCOUNT.= $this->and.$this->nombre.".".$campo['nombre']."='".$this->EscapeString($valor)."' ";
					}
				}  else die('Error: La primer referencia debe ser de tipo \'directa\' o \'combo\'> campo:'.$campo['nombre']);
			}
			
			$this->and='and '; 

			$guardas_q = '';
			$guardas_sql = '';			
			if ($nested!='') {
				$guardas = explode("/*SPECIAL*/",$nested);
				if ($guardas[0]!=$nested) {
					$guardas_q[$nested] = 'si';
				} else {						
					$guardas = explode(" AND ",$nested);
					foreach ($guardas as $guarda) {
						$guardac = explode("=",$guarda);					
						if ($guardac[1]!='') {						
							$campos_q[$guardac[0]]='si';
							$guardas_q[$guardac[0]."=".$guardac[1]]='si';
						}
					}
				}
				if ($guardas_q!='') {
					$and = '';
					foreach ($guardas_q as $KK=>$VV) {
						$guardas_sql.= $and.$KK." ";
						$and = ' and ';
					}
				}				
				$this->SQL.= $this->and.$guardas_sql;
				$this->SQLCOUNT.= $this->and.$guardas_sql;				
			}

		} else {//no se paso valor de filtrado, sin embargo tenemos tablas anidadas que filtran

			$guardas_q = '';
			$guardas_sql = '';			
			if ($nested!='') {
				$guardas = explode("/*SPECIAL*/",$nested);
				if ($guardas[0]!=$nested) {
					$guardas_q[$nested] = 'si';
				} else {						
					$guardas = explode(" AND ",$nested);
					foreach ($guardas as $guarda) {
						$guardac = explode("=",$guarda);					
						if ($guardac[1]!='') {						
							$campos_q[$guardac[0]]='si';
							$guardas_q[$guardac[0]."=".$guardac[1]]='si';
						}
					}
				}
				if ($guardas_q!='') {
					$and = '';
					foreach ($guardas_q as $KK=>$VV) {
						$guardas_sql.= $and.$KK." ";
						$and = ' and ';
					}
				}				
				$this->SQL.= $this->and.$guardas_sql;
				$this->SQLCOUNT.= $this->and.$guardas_sql;				
			}

		}
	}

	function GetLastInsertId() {
		if ($this->db['tipodb']=='mysql') {
			$this->lastinsertid = mysql_insert_id($this->CONN);
		}  elseif ($this->db['tipodb']=='mysqli') {
			$this->lastinsertid = mysqli_insert_id($this->CONN);
		}  elseif ($this->db['tipodb']=='interbase') {
			$this->lastinsertid = "";
		} elseif ($this->db['tipodb']=='mssql') {
			$this->lastinsertid = "";
		} elseif ($this->db['tipodb']=='sqlsrv') {
			$this->lastinsertid = "";
		}
		return 	$this->lastinsertid;
	}
	
	function ShowSqlLastError() {
		$pre = " SQL:<span style=\"font-size: 1.2em; font-weight: normal; color: blue;\">".$this->SQL."</span> sql(".$this->db['tipodb']."): <b style=\" font-size: 1.5em; color:red; font-style:italic;\">";
		$post = "</b>";
		if ($this->db['tipodb']=='mysql') {
			return $pre.mysql_error().$post;
		}  elseif ($this->db['tipodb']=='mysqli') {
			return $pre.mysqli_error($this->CONN).$post;
		}  elseif ($this->db['tipodb']=='interbase') {
			return $pre.ibase_errmsg().$post;
		} elseif ($this->db['tipodb']=='mssql') {
			return $pre.mssql_get_last_message().$post;
		} elseif ($this->db['tipodb']=='sqlsrv') {
			return $pre.SqlSrvDisplayErrors().$post;
		}
		return $pre.$post;
			
	}

	//ejecuta el query
	function EjecutaSQL( $sql="" ) {
		
		$this->Disconnect();
		$this->Connect();
					
		if (trim($sql)!="") $this->SQL = $sql;
		
		if ($this->debug=='si') echo $this->SQL;
		if ($this->db['tipodb']=='mysql') {
			$this->resultados = mysql_query ($this->SQL,$this->CONN);
			if (! (strpos($this->SQL,"SELECT ")===false)) $this->nresultados = mysql_num_rows($this->CONN);
		} elseif ($this->db['tipodb']=='mysqli') {
			$this->resultados = mysqli_query ($this->SQL,$this->CONN);
			if (! (strpos($this->SQL,"SELECT ")===false)) $this->nresultados = mysqli_num_rows($this->CONN);
		} elseif ($this->db['tipodb']=='interbase') {
			$this->resultados = ibase_query ($this->SQL);
		} elseif ($this->db['tipodb']=='mssql') {
			$this->resultados = mssql_query ($this->SQL, $this->CONN);
			if (! (strpos($this->SQL,"SELECT ")===false)) $this->nresultados = mssql_num_rows($this->resultados);
		} elseif ($this->db['tipodb']=='sqlsrv') {
			$this->resultados = sqlsrv_query ($this->SQL, $this->CONN);
			if (! (strpos($this->SQL,"SELECT ")===false)) $this->nresultados = sqlsrv_num_rows($this->resultados);
		}

		if (!$this->resultados) {
			DebugError( "CTabla::EjecutaSQL &gt; <span style=\"color:red;\"> error SIN RESULTADOS: ".$this->ShowSqlLastError()."<span>" );
		}
		
		return $this->resultados;
	}
	
	function Connect() {
		global $connected_calls;
		if ($connected_calls=='') $connected_calls=0;
		if (!$this->CONN) {
			if ($this->db['tipodb']=='mysql') {
				//Debug("Connect  connection open");
				$this->CONN = mysql_connect ($this->db['servidor'],$this->db['usuario'],$this->db['password']) or die ("Error: No pudo conectarse al servidor [".$this->db['servidor']."], revise los datos de la configuración.  (servidor,usuario y contraseña).<br>Gracias.");
				mysql_select_db($this->db['nombre'],$this->CONN) or die('Error: No se pudo seleccionar la base datos ['.$this->db['nombre'].'], revise los datos de la configuración.<br>Gracias.');				
				$connected_calls++;				
			} elseif ($this->db['tipodb']=='mysqli') {
				//Debug("Connect  connection open");
				$this->CONN = mysqli_connect($this->db['servidor'],$this->db['usuario'],$this->db['password'],$this->db['nombre']) or die ("Error: No pudo conectarse al servidor [".$this->db['servidor']."], revise los datos de la configuración.  (servidor,usuario y contraseña).<br>Gracias.");
				$connected_calls++;				
			} else if ($this->db['tipodb']=='interbase') {
				//Debug("Connect  connection open");
				//$dbname = "\\\\".$this->db['servidor']."\\".$this->db['nombre'];
				$dbname = $this->db['servidor'].":".$this->db['nombre'];
				$this->CONN = ibase_connect ( $dbname,$this->db['usuario'],$this->db['password']) or die ("Error: No pudo conectarse a [".$dbname."], revise los datos de la configuración.  (servidor,usuario y contraseña).<br>Gracias.");
			} else if ($this->db['tipodb']=='mssql') {
				//Debug("Connect  connection open");
				$this->CONN = mssql_connect( $this->db['servidor'], $this->db['usuario'], $this->db['password']) or die ("Error: No pudo conectarse a [".$this->db['servidor']."], revise los datos de la configuración.  (tipodb [".$this->db['tipodb']."], servidor,usuario y contraseña).<br>Gracias.");				
				mssql_select_db ( $this->db['nombre'] , $this->CONN ) or die('Error: No se pudo seleccionar la base datos ['.$this->db['nombre'].'], revise los datos de la configuración.<br>Gracias.');				
			} else if ($this->db['tipodb']=='sqlsrv') {
				
				//Debug("Connect  connection open");
				
				$dbname = " Servidor:".$this->db['servidor']." Base:".$this->db['nombre']." Usuario:".$this->db['usuario']." Pass:".$this->db['password'];
				
				$this->CONN = sqlsrv_connect( 
						
						$this->db['servidor'], 
	
						array("Database"=>$this->db['nombre'],
									"UID"=>$this->db['usuario'], 
									"PWD"=>$this->db['password'])
						
						) or die ("Error:".SqlSrvDisplayErrors()." No pudo conectarse a [".$dbname."], revise los datos de la configuración.  (servidor,usuario y contraseña).<br>Gracias.");				

			}
		}
		return $this->CONN;
	}
	
	function Disconnect() {
		global $connected_calls;
		if (!$this->CONN) {
			return;
		}
		
		if ($this->db['tipodb']=='mysql') {
			mysql_close();
			$connected_calls--;
			//Debug("Disconnect connection closed");
		} elseif ($this->db['tipodb']=='mysqli') {
			mysqli_close();
			$connected_calls--;
		} elseif ($this->db['tipodb']=='interbase') {
			ibase_free_result($this->resultados);
			ibase_close($this->CONN);			
			$connected_calls--;	
			//Debug("connection closed");
		} elseif ($this->db['tipodb']=='mssql') {
			mssql_close($this->CONN);
			//Debug("connection closed");
		} elseif ($this->db['tipodb']=='sqlsrv') {
			sqlsrv_close($this->CONN);
			//Debug("connection closed");
		}
		$this->CONN = false;
		
	}
	
	function EscapeString( $__str__) {
		if ($__str__!="") {
			if (!$this->CONN) {
				$this->Connect();
			}
			if ($this->CONN) {
				if ($this->db['tipodb']=='mysql') $__res__ = mysql_real_escape_string($__str__, $this->CONN);
				if ($this->db['tipodb']=='mysqli') $__res__ = mysqli_real_escape_string($this->CONN,$__str__);				
				if ($__res__!="") {
					return $__res__;
				} else {
					DebugError("CTabla::EscapeString error : [".$__str__."] sqlerror: ".$this->ShowSqlLastError());
				}
			}
		}
		return $__str__;
	}
	
	function FixHtmlMess( $__str__, $iteration=1,$maxiterations=10) {
		
		$replacements = array(			"&quot;"=>'"',
								'\"'=>'"',
								'""'=>'"' );
		
		if ($iteration>=$maxiterations)
			return $__str__;
		
		foreach( $replacements as $find=>$replace) {

				if (is_numeric(strpos( $__str__, $find ))) {
					
					$__str__ = str_replace( $find, $replace, $__str__ );
					
					return $this->FixHtmlMess( $__str__, $iteration+1, $maxiterations );
					
				}
			
		}
						
		return $__str__;
	}
	
	function UnescapeString( $__str__) {
		//return $__str__;
		if ($__str__!="") {
			if (!$this->CONN) {
				$this->Connect();
			}			
			if ($this->CONN) {
				//if ($this->db['tipodb']=='mysql') $__res__ = mysql_real_escape_string($__str__, $this->CONN);
				//if ($this->db['tipodb']=='mysqli') $__res__ = mysqli_real_escape_string($this->CONN,$__str__);
				$__res__ = $__str__;
				if ($__res__!="") {					
					
					return stripslashes($__str__);
					//return stripslashes($this->FixHtmlMess($__str__));
				} else {					
					DebugError("CTabla::UnescapeString error : [".$__str__."] sqlerror: ".$this->ShowSqlLastError());
				}				
			}
		}
		return $this->FixHtmlMess($__str__);
	}
	
	/**
	 * Cuenta la cantidad de registros devueltos por la sentencia SQL, SQLCOUNT
	 * si se usaron las funciones LimparSQL() FiltrarSQL() OrdenSQL() LimitarSQL() y Open()
	 * esta funcion devuelve correctamente la cantidad de registros
	 * si se generar a mano las sentencias se debe completar la sentencia SQLCOUNT a mano.
	 *
	 * @return cantidad de registros devueltos por la consulta
	 */
	function Count($limited=true) {
		if ($this->debug=='si') { echo $this->SQL;	echo "<BR>"."\n".$this->SQLCOUNT; }
		
		//$this->Disconnect();
		$this->Connect();
		
		$SQLCOUNT = "";
		
		($limited) ? $SQLCOUNT = $this->SQLCOUNT.$this->_withLimitSQL() : $SQLCOUNT = $this->SQLCOUNT;
		if ($SQLCOUNT=='') $this->nresultados = 0;
		
		if ($this->db['tipodb']=='mysql') {
			
			$this->resultados = mysql_query ($SQLCOUNT,$this->CONN);
			
			if ($this->resultados) {
				//$count = mysql_num_rows($this->resultados);
				$count = mysql_fetch_row($this->resultados);
				$this->nresultados = $count[0];
			} 																
		} elseif ($this->db['tipodb']=='mysqli') {
				$this->resultados = mysqli_query ($SQLCOUNT,$this->CONN);
				if ($this->resultados) {
					//$count = mysqli_num_rows($this->resultados);					
					$count = mysqli_fetch_row($this->resultados);
					$this->nresultados = $count[0];
				}
		} elseif ($this->db['tipodb']=='interbase') {
				$this->resultados = ibase_query ($SQLCOUNT);
				if ($this->resultados) {
					$count = ibase_fetch_row($this->resultados);
					$this->nresultados = $count[0];
				}
		} elseif ($this->db['tipodb']=='mssql') {
				$this->resultados = mssql_query ($SQLCOUNT,$this->CONN);
				if ($this->resultados) {
					$count = mssql_fetch_row($this->resultados);
					$this->nresultados = $count[0];
				}
		} elseif ($this->db['tipodb']=='sqlsrv') {
				$this->resultados = sqlsrv_query ( $this->CONN, $SQLCOUNT );
				if ($this->resultados) {
					if (sqlsrv_fetch($this->resultados)) {
						$count = sqlsrv_get_field($this->resultados, 0);
					}
					$this->nresultados = $count;
				}
		}
		
		if (!$this->resultados) {
			DebugError( "CTabla::Count error SIN RESULTADOS: ".$this->ShowSqlLastError().' nresultados:'. $this->nresultados.' sqlcount: '.$SQLCOUNT );
			//ShowError("CTabla::Count error SIN RESULTADOS:".$this->ShowSqlLastError().' nresultados:'. $this->nresultados.' sqlcount: '.$SQLCOUNT);
			$this->nresultados = 0;
		}
		
		//ShowMessage('CTabla::Count res: '.$this->nresultados.' limited='.$limited.',Limite: '.$this->Limite.' SQLCOUNT='.$SQLCOUNT);
		//$this->Disconnect();
		return $this->nresultados;
	}	

	
	function _withLimitSQL() {
		if ($this->Limite) {
			if ($this->db['tipodb']=='mysql' || $this->db['tipodb']=='mysqli') {				
				return ' LIMIT '.$this->startitem.','.$this->maxitems;
			}			
			
			if ($this->db['tipodb']=='mssql') {
				$this->SQL = str_replace( "SELECT ","SELECT TOP ".($this->startitem+$this->maxitems)." ",$this->SQL);
			}
			
			if ($this->db['tipodb']=='sqlsrv') {
				$this->SQL = str_replace( "SELECT ","SELECT TOP ".($this->startitem+$this->maxitems)." ",$this->SQL);
				//$this->SQL = str_replace( "SELECT ","SELECT ROW_NUMBER() OVER (ORDER BY name) as row,", $this->SQL );
				//$this->SQL = str_replace( "WHERE ","WHERE row >=".$this->startitem." AND row<".($this->startitem+$this->maxitems)." AND ", $this->SQL );
			}			
	 }
	 return "";
		
	}
	
	function Query( $SQL , $CONN='') {
		
			if ($SQL=='') return DebugError('CTabla::Query sql query empty');
			if ($CONN=='') $CONN = $this->CONN;
			Debug("calling CTabla Query on: ".$SQL);
			
			switch($this->db['tipodb']) {
				case 'mysql':					
					Debug("Query mysql server");
					$RESULTADOS = mysql_query($SQL,$CONN);
					$this->nresultados = mysql_num_rows($RESULTADOS);
					break;
				case 'mysqli':
					Debug("Query mysqli server");
					$RESULTADOS = mysqli_query($SQL,$CONN);						
					$this->nresultados = mysqli_num_rows($RESULTADOS);		
					break;
				case 'interbase':							
					Debug("Query interbase server");	
					$RESULTADOS = ibase_query($CONN,$SQL);//tambien funciona: ibase_query($SQL)
					$this->nresultados = $this->nresultados;
					break;
				case 'mssql':		
					Debug("Query mssql server");
					$RESULTADOS = mssql_query($SQL,$CONN);
					$this->nresultados = mssql_num_rows($RESULTADOS);		
					break;
				case 'sqlsrv':
					Debug("Query sqlsrv server");
					$RESULTADOS = sqlsrv_query($SQL,$CONN);
					$this->nresultados = sqlsrv_num_rows($RESULTADOS);		
					break;
			}				
			$this->resultados = $RESULTADOS;
			return $RESULTADOS;
	}
	
	//realiza el query de la sentencia SQL (como el open de Delphi)
	function Open( $limited=true ) {
		
		$this->Disconnect();
		$this->Connect();
		
		Debug("calling CTabla::Open table ".$this->nombre);
		$this->totalitems = 0;
		$this->totalitems = $this->Count(false);		
		
		Debug("totalitems: ".$this->totalitems);
		
		//contamos nuevamente los resultados (limitados)
		$this->nresultados = $this->Count($limited);		
		
		//ejecutamos la consulta finalmente 
		$this->Connect();//dejando abierta la conección				

		($limited) ? $SQL = $this->SQL.$this->_withLimitSQL() : $SQL = $this->SQL;
		$this->Query($SQL,$this->CONN);		
		
		Debug("nresultados: ".$this->nresultados);
		
		//guardamos que columnas devolvio la consulta
		$this->SqlFetchCols();
		
	}
	
	function NavegacionOpen() {
		return $this->Open(true);
	}
	
	function Close() {
		$this->Disconnect();
	}
	
	function FinalizarSQL() {
		$this->Disconnect();
		$this->nresultados = 0;
		$this->resultados = false;
		$this->SQL = '';
		$this->CONN = false;
	}
	
	function AgruparSQL($grupo) {
		if ($grupo!='') $this->SQL.= ' GROUP BY '.$grupo.' ';
	}
	
	function OrdenSQL($orden) {
		if ($orden!='') $this->SQL.= ' ORDER BY '.$orden;
	}

	function Ordenar($orden,$escondido='',$echo=true) {
		$resstr = "";
		global $CLang;
		if ($escondido=='') {
			$resstr.='<select name="_orden_" onChange="javascript:ordenar();" class="indice ordenar">';
			foreach ($this->indices as $indice) {
				if ($orden == $indice['indice']) $selected = 'selected'; else $selected = '';
				$trans_indice = $indice['nombre'];
				if ($CLang) $trans_indice = $CLang->Translate($trans_indice);
				$resstr.= '<option value="'.$indice['indice'].'" '.$selected.' class="indice">'.$trans_indice.'</option>';
			}
			$resstr.= '</select>';
		} else {
			$resstr.= '<INPUT TYPE="hidden" VALUE="'.$orden.'" NAME="_orden_">';
		}
		if ($echo) echo $resstr;
		return $resstr;
	}

	
	function LimiteSQL( $start=0, $max=0) {
		$this->Limite = true;
		if ($start==0 && $max==0) {
			$this->startitem 	= $GLOBALS['_items_STARTITEM'];
			$this->maxitems 	= $GLOBALS['_items_MAXITEMS'];
		} else {
			$this->startitem 	= $start;
			$this->maxitems 	= $max;			
		}
		
		if ($this->startitem=='') $this->startitem = 0;
		if ($this->maxitems=='') $this->maxitems = 30;		
	}	
	
	function DistinctSQL() {
		$this->SQL = str_replace('SELECT','SELECT DISTINCT ', $this->SQL );
		$this->SQLCOUNT = str_replace('SELECT','SELECT DISTINCT ', $this->SQLCOUNT );
	}
	
	function Limitar() {
		echo '<table border="0">';
		$lastitem = ($this->startitem + $this->maxitems -1);
		if ($lastitem > ($this->totalitems-1)) $lastitem = ($this->totalitems-1);
		echo '<td valign="bottom"><span class="resultados">'.$this->startitem.'-'.$lastitem.' de '.$this->totalitems.' records&nbsp&nbsp&nbsp&nbsp</span></td>';
		if ( $this->startitem >= $this->maxitems ) {
		echo '<td align="left">';
		echo '<a href="javascript:Anteriores();"><span class="resultados">[ << Previous 30 ]</span></a> ';
		echo '</td>';
		}
		if ( ($this->startitem + $this->maxitems)  < $this->totalitems ) {
		echo '<td align="left">';
		echo '<a href="javascript:Siguientes();"><span class="resultados">[ Next 30 >> ]</span></a> ';
		echo '</td>';
		}
		echo '<td>';
		echo '<input name="_items_STARTITEM" type="hidden" value="'.$this->startitem.'">';
		echo '<input name="_items_MAXITEMS" type="hidden" value="'.$this->maxitems.'">';		
		echo '<input name="_items_TOTALITEMS" type="hidden" value="'.$this->totalitems.'">';		
		echo '</td>';
		echo '</table>';
	}
	
	
	
	function SqlFetchCols($RESULTADOS='') {
			
		$this->cols = array();
				
		if ($RESULTADOS=='') $RESULTADOS=$this->resultados;
		switch($this->db['tipodb']) {
			case 'mysql':
			case 'mysqli':
				$this->MySqlFetchCols($RESULTADOS);								
				break;
			case 'interbase':								
				$this->IBFetchCols($RESULTADOS);
				break;
			case 'mssql':		
				$this->MsSqlFetchCols($RESULTADOS);				
				break;
			case 'sqlsrv':
				$this->SqlSrvFetchCols($RESULTADOS);						
				break;
		}
		
		return $this->cols;
	}
	
	function IBFetchCols($RESULTADOS) {
		//INFORMACION DE LAS COLUMNAS DEVUELTAS	
		$this->coln = ibase_num_fields($RESULTADOS);
		if ($this->debug=='si') {
			echo '<img src="../images/debug.png" alt="';
			echo 'Hay '.$this->coln.' campos devueltos'."\n"."\n";
		}
				
		if ($this->coln>0) {
			for ($i=0; $i < $this->coln; $i++) {
				$col_info = ibase_field_info($RESULTADOS, $i); 
				$this->cols[$i] = $col_info;
				if ($this->debug=='si') {
				    echo "name: ".$col_info['name']." table: ".$col_info['relation']."\n"; 
				    echo "type: ".$col_info['type']." length: ".$col_info['length']."\n"."\n"; 
				}
			} 
	    }
		
		if ($this->debug=='si') echo '">';
	}


	function MySqlFetchCols($RESULTADOS) {
		//INFORMACION DE LAS COLUMNAS DEVUELTAS		
		$this->coln = mysql_num_fields($RESULTADOS);
		if ($this->debug=='si') {
			echo '<img src="../images/debug.png" alt="';
			echo 'Hay '.$this->coln.' campos devueltos'."\n"."\n";
		}

		if ($this->coln>0) {
			for ($i=0; $i < $this->coln; $i++) {
				
				if ($this->db['tipodb']=='mysql') $col_info = mysql_fetch_field ($RESULTADOS, $i); 
				if ($this->db['tipodb']=='mysqli') $col_info = mysqli_fetch_field ($RESULTADOS, $i); 
				
				$this->cols[$i] = $col_info;
				if ($this->debug=='si') {
				    echo "name: ".$col_info->name." table: ".$col_info->table."\n"; 
				    echo "type: ".$col_info->type." length: ".$col_info->max_length."\n"."\n"; 
				}
			} 
	    }
		
		if ($this->debug=='si') echo '">';
	}

	function MsSqlFetchCols($RESULTADOS) {
		//INFORMACION DE LAS COLUMNAS DEVUELTAS		
		$this->coln = mssql_num_fields($RESULTADOS);
		if ($this->debug=='si') {
			echo '<img src="../images/debug.png" alt="';
			echo 'Hay '.$this->coln.' campos devueltos'."\n"."\n";
		}

		if ($this->coln>0) {
			for ($i=0; $i < $this->coln; $i++) {
				
				$col_info = mssql_fetch_field ($RESULTADOS, $i);

				if ($col_info->table=="") $col_info->table = $this->nombre;
				if ( is_numeric(strpos($col_info->name,".") )) {
					$xc = explode(".", $col_info->name );
					$col_info->table=$xc[0];
					$col_info->name=$xc[1];
				}
				
				$this->cols[$i] = $col_info;				
				ShowMessage( "name: ".$col_info->name." table: ".$col_info->table); 
				ShowMessage( "type: ".$col_info->type." length: ".$col_info->max_length); 
				
			} 
	    }
		
		if ($this->debug=='si') echo '">';
	}	
	

	function SqlSrvFetchCols($RESULTADOS) {
		//INFORMACION DE LAS COLUMNAS DEVUELTAS		
		$this->coln = sqlsrv_num_fields($RESULTADOS);		
		if ($this->debug=='si') {
			echo '<img src="../images/debug.png" alt="';
			echo 'Hay '.$this->coln.' campos devueltos'."\n"."\n";
		}
		
		$fields = sqlsrv_field_metadata($RESULTADOS);
		$this->coln = count($fields);		
		//echo "<pre>".	print_r($fields,true)."</pre>";
		if ($this->coln>0) {
			
			$field_names = array();
			
			for ($i=0; $i < $this->coln; $i++) {
				
				$fieldname_x = explode( ".", $fields[$i]["Name"] );
				if (is_array($fieldname_x)) {
					$tablename = $fieldname_x[0];
					$fieldname = $fieldname_x[1];
				} else {
					$tablename = $this->nombre;
					$fieldname = $fields[$i]["Name"];
				}
		
				$col_info = (object) array(
					"name"=>$fieldname,
					"table"=>$tablename,
					"type"=>$fields[$i]["Type"],
					"length"=>$fields[$i]["Size"]
				);
								
				$this->cols[$i] = $col_info;
				if ($this->debug=='si') {
				    echo "name: ".$col_info->name." table: ".$col_info->table."\n"; 
				    echo "type: ".$col_info->type." length: ".$col_info->length."\n"."\n"; 
				}
			} 
			
			//echo "<pre>".print_r($this->cols, true)."</pre>";
	  }
	  
		
		if ($this->debug=='si') echo '">';
	}		
	
	function RawFetch( &$_row_raw_, $_campos_sql_="") {
		if ($_campos_sql_=="") {
			return $_row_raw_;
		}
		$row_field_strings = array();
		$campos_sql_x = explode(",",$_campos_sql_);
		
		if (count($_row_raw_)==count($campos_sql_x)) {
			
			for( $k=0; $k<count($_row_raw_); $k++ ) {
				
				//$row_field_strings[ trim($campos_sql_x[$k]) ] = str_replace( array("&quot;","&#39;"), array('"',"'"), $_row_raw_[$k] );
				$row_field_strings[ trim($campos_sql_x[$k]) ] = $this->UnescapeString( $_row_raw_[$k] );
				
			}
		}
		return $row_field_strings;
	}
	
	function Fetch($RESULTADOS="",$RAW_RESULTS=false, $campos_sql="" ) {
	   //TOMAMOS LA FILA Y LA PASAMOS CORRECTAMENTE
	   if ($RESULTADOS=="") $RESULTADOS = $this->resultados;
	   if ($this->db['tipodb'] =='interbase') {
		   if ($row1 = ibase_fetch_row($RESULTADOS)) {
			   $this->irow+=1;
		   	   if ($this->Limite) {
		   	   		if ( ($this->irow < $this->startitem) || ($this->irow >= ($this->startitem + $this->maxitems) ) ) {
		   	   			return FALSE;
		   	   		}
		   	   }
			   if ($RAW_RESULTS)
					return $this->RawFetch($row1,$campos_sql);
		  	 	   	
         for ( $i = 0 ; $i < $this->coln ; $i++) {
				    $col_info = $this->cols[$i];
					$row2[$col_info['relation'].".".$col_info['name']] = $this->UnescapeString( $row1[$i] );
			   }
			   
		   	   return $row2;
		   } else {
		   	 return FALSE;
		   }
		} elseif ($this->db['tipodb'] =='mysql') {
			if ($row1 = mysql_fetch_row($RESULTADOS)) {
				if ($RAW_RESULTS)
					return $this->RawFetch($row1,$campos_sql);				
		        for ( $i = 0 ; $i < $this->coln ; $i++) {
				    $col_info = $this->cols[$i];
					$row2[$col_info->table.".".$col_info->name] = $this->UnescapeString($row1[$i] );
				}
		   	   return $row2;
		   } else {
		   	 return FALSE;
		   }		
		} elseif ($this->db['tipodb'] =='mssql') {
		   if ($row1 = mssql_fetch_row($RESULTADOS)) {
		   		if ($RAW_RESULTS)
			   		return $this->RawFetch($row1,$campos_sql);
		   		
			   for ( $i = 0 ; $i < $this->coln ; $i++) {
				    $col_info = $this->cols[$i];
						//$row2[$col_info->table.".".$col_info->name] = str_replace( array("&quot;","&#39;"), array('"',"'"), $row1[$i] );
						 $row2[$col_info->table.".".$col_info->name] = $this->UnescapeString( $row1[$i] ); 
			   }
		   	 return $row2;
		   } else {
		   	 return FALSE;
		   }		
		} elseif ($this->db['tipodb'] =='sqlsrv') {
			$row1 = sqlsrv_fetch_array($RESULTADOS);
			$row2 = array();
			if (is_array($row1)) {
		     	if ($RAW_RESULTS)
			   		return $this->RawFetch($row1,$campos_sql);
				 for ( $i = 0 ; $i < $this->coln ; $i++) {
				    $col_info = $this->cols[$i];						
				    //$row2[$col_info->table.".".$col_info->name] = str_replace( array("&quot;","&#39;"), array('"',"'"), $row1[$i] );				    
				    $row2[$col_info->table.".".$col_info->name] = $this->UnescapeString( $row1[$i] ); 
			     }			   
			   //echo "<pre>".print_r($row2,true)."</pre>";
	            return $row2;
	   		} else {
	   			return FALSE;
	   		}
		}
 	}
 	
 	function NavegacionVars( $template="", $fields=array() ) {
 		
 		global $_search_;
 		
 		global $_page_;
 		global $_nxpage_;
 		global $_order_;
 		
 		global $_field_order_;
 		global $_field_order_sens_;
 		
 		global $_id_ref_;

 		$vars_str = '
 						<input type="hidden" name="_search_" value="'.$_search_.'">
 		 				<input type="hidden" name="_page_" value="'.$_page_.'">
 		 				<input type="hidden" name="_nxpage_" value="'.$_nxpage_.'">
 		 				<input type="hidden" name="_order_" value="'.$_order_.'">
 		 				<input type="hidden" name="_field_order_" value="'.$_field_order_.'">
 		 				<input type="hidden" name="_field_order_sens_" value="'.$_field_order_sens_.'">
 		';
 		
 		if (count($fields)>0) {
 			foreach($fields as $field=>$value) {
 				$vars_str.= '
 		 				<input type="hidden" name="'.$field.'" value="'.$value.'">
 				';
 			}
 		}
 		
 		if ($template!="") {
 			return str_repalce( array("[NAVVARS]"), array($vars_Str), $template);
 		} else return $vars_str;
 	}
 	
 	function Search( $template ) {
 		
 		global $_search_;
 		
 		$_search_ = trim($_search_);
 		
 		if (isset($_search_) && $_search_!="") {
 			$search_str = $GLOBALS['CLang']->Get("FILTEREDBY")." $_search_ ";
 		}
 		
 		$search_str =' 
 		<div id="search">
			<label>'.$GLOBALS['CLang']->Get('SEARCH').'&nbsp;:&nbsp;</label>
		<form id="formsearch" name="formsearch" method="post" action="">			
			<input type="text" name="_search_" value="'.$_search_.'" size="20"/>
			<div id="searchicon"></div>
		</form>
			<div id="filtered">'.$search_str.'</div>
		</div>
		';
 		
 		if ($template!="") {
 			 return str_replace( "[SEARCH]", $search_str, $template );	
 		} else return $search_str;
 	}
 	
 	function OrdenEncabezado( $template, $options = array() ) {
 		
 		global $_order_;
 		global $_field_order_;
 		global $_field_order_sens_;
 		
 		$fields = $options['fields'];
 		
 		if ($_field_order_sens_=='ASC') $fieldordered_class = 'class="field-order field-ordered-asc"';
 		else  $fieldordered_class = 'class="field-order field-ordered-desc"';
 		
 		
 		if ($_field_order_sens_=='ASC') $_field_order_sens2_='DESC';
 		if ($_field_order_sens_=='DESC' || $_field_order_sens_=='') $_field_order_sens2_='ASC';
 		
 		foreach( $fields as $field ) {
 			$field_click = ' onclick="
 			javascript:
 			document.pagenavigation._order_.value=\''.$field.' '.$_field_order_sens2_.'\';
 			document.pagenavigation._field_order_.value=\''.$field.'\';
 			document.pagenavigation._field_order_sens_.value=\''.$_field_order_sens2_.'\';
 			document.pagenavigation.submit();
 			" ';
 			if ($_field_order_==$field) {
 				
 				if ($_field_order_sens_=='ASC') $fieldordered_class = 'class="field-order field-ordered-asc"';
 				else  $fieldordered_class = 'class="field-order field-ordered-desc"'; 	
 							
 				$template = str_replace( '['.$field.']', $fieldordered_class.$field_click, $template );
 			} else 	{
 				$fieldordered_class = ' class="field-order"';
 				$template = str_replace( '['.$field.']', $fieldordered_class.$field_click , $template );
 			}
 		}
 		
 		return $template;
 		
 	}
 	
 	function Navegacion( $template, $options = array() ) {
 		
 		global $_search_;
 		global $_page_;
 		global $_nxpage_;
 		global $_field_order_;
 		global $_field_order_sens_;
		global $pnavigation_used;
		
		if ($pnavigation_used=="") $pnavigation_used = 0;
		
		if ($pnavigation_used==0) { 
			$pnavigation_used_str = "pagenavigation";  
		} else {
			$pnavigation_used_str.="pagenavigation".$pnavigation_used;
		}
		//$pnavigation_used_str = "pagenavigation"; 		

 		/*PREDETERMINADOS*/
 		$nav_mode = "image"; // o text
 		$show_disabled = true;
 		$class_disabled= "nav_disabled";
 		$default_page = 1; 		
 		$default_nxpage = 1;
 		$default_nxpage = 30;
 		
 		$nxpage_selector = true;
 		$nxpage_selector_options = array(
 			"2"=>"2 ".$GLOBALS["RECORDS"],
 			"50"=>"50 ".$GLOBALS["RECORDS"],
 			"100"=>"100 ".$GLOBALS["RECORDS"],
 			"all"=>$GLOBALS["CLang"]->Get("ALL")
 		);
 		$return_page = false;
 		$return_page_text = "";
 		$return_page_action = "";
 		$id_record = "";
 		$action = "";
 		$search_form = false;
 		$search_str = "";
 		
 		$order_selector = false;
 		$order_selector_options = array();
 		
 		if (isset($options['nav_mode'])) $nav_mode = $options['nav_mode'];
 		if (isset($options['class_disabled'])) $class_disabled = $options['class_disabled'];
 		if (isset($options['show_disabled'])) $show_disabled = $options['show_disabled'];
 		if (isset($options['default_page'])) $default_page = $options['default_page']; 		
 		if (isset($options['default_nxpage'])) $default_nxpage = $options['default_nxpage'];
 		if (isset($options['nxpage_selector'])) $nxpage_selector = $options['nxpage_selector'];
 		if (isset($options['nxpage_selector_options'])) $nxpage_selector_options = $options['nxpage_selector_options'];
 		
 		if (isset($options['order_selector'])) $order_selector = $options['order_selector'];
 		if (isset($options['order_selector_options'])) $order_selector_options = $options['order_selector_options'];
 		
 		if (isset($options['return_page'])) $return_page = $options['return_page'];
 		if (isset($options['return_page_text'])) $return_page_text = $options['return_page_text'];
 		if (isset($options['return_page_action'])) $return_page_action = $options['return_page_action'];
 		if (isset($options['id_record'])) $id_decord = $options['id_record'];
 		if (isset($options['action'])) $action = $options['action'];
 		
 		if ( !isset( $GLOBALS["_page_"] ) ) {
 			$_page_ = $default_page;
 			$_nxpage_ = $default_nxpage;
 		}
 		
		if ($_page_=="") $_page_ = $default_page;
		if ($_nxpage_=="") $_nxpage_ = $default_nxpage;
 		
		if (!$nxpage_selector) {
			$nxpage_str = '<input type="hidden" name="_nxpage_" value="'.$_nxpage_.'">';
		} else {
			if ( count($nxpage_selector_options) > 0 ) {
				
				$nxpage_str = '<select id="nxpage_selector" name="_nxpage_" onchange="javascript:document.'.$pnavigation_used_str.'._page_.value=1;document.'.$pnavigation_used_str.'.submit();">';
				
				foreach( $nxpage_selector_options as $value=>$option ) {
					($value == $_nxpage_) ? $sel = "selected" : $sel = "" ;
					$nxpage_str.= '<option value="'.$value.'" '.$sel.'>'.$option.'</option>';
				}
				$nxpage_str.= '</select>';
			}
		}

		if (!$order_selector) {
			$order_str = '<input type="hidden" name="_order_" value="'.$_order_.'">';
		} else {
			if ( count($order_selector_options) > 0 ) {
				
				$order_str = '<select id="order_selector" name="_order_" onchange="javascript:document.'.$pnavigation_used_str.'._page_.value=1;document.'.$pnavigation_used_str.'.submit();">';
				
				foreach( $order_selector_options as $value=>$option ) {
					($value == $_order_) ? $sel = "selected" : $sel = "" ;
					$order_str.= '<option value="'.$value.'" '.$sel.'>'.$option.'</option>';
				}
				$order_str.= '</select>';
			}
		}

		
		
		if ($id_record!="") {
			$record_str = '<input name="id_record" type="hidden" value="'.$id_record.'">';
		} else $record_str = "";
		

 		$form_vars = '
 			<form action="" name="'.$pnavigation_used_str.'" id="'.$pnavigation_used_str.'" method="post">
 				<input type="hidden" name="_page_" value="'.$_page_.'">
 				<input type="hidden" name="_search_" value="'.$_search_.'">
 				<input type="hidden" name="_field_order_" value="'.$_field_order_.'">
 				<input type="hidden" name="_field_order_sens_" value="'.$_field_order_sens_.'">
 				'.$order_str.$nxpage_str.$record_str.'
 			</form>
 		';
 		$pnavigation_used = $pnavigation_used+1;
		//echo "ok";
		
		$this->Disconnect();
 		$this->totalitems = $this->Count(false);

 		
 		//echo "ok2";
 		/*seteamos valores generales*/
 		 		
 		if ($_nxpage_=="all") $_nxpage_ = $this->totalitems; //para all tomamos todos los registros
 		$_npages_ = ceil( $this->totalitems / $_nxpage_); //la cantidad de paginas en total
 		if ($_page_>$_npages_) $_page_ = $_npages_; //la pagina actual no puede ser mayor al total de paginas...
 		
 		$pages = "";
 		
 		$ini_pages = max( 	1, 						$_page_ - 6 );
 		$end_pages = min( 	$_npages_,		$_page_ + 6 );
 		
 		for( $p=$ini_pages; $p<=$end_pages; $p++) {
 			$odd = ($p%2==0) ? "odd" : "even";
 			$first = ($p==1) ? "pagina-primera" : "";
 			$first = ($p==$_npages_) ? "pagina-ultima" : "";
 			$page_click = 'onclick="javascript:document.pagenavigation._page_.value='.$p.';document.pagenavigation.submit();"';
 			if ($_page_== $p ) { $psel = " pagina-sel "; } else $psel = "";
 			$pstr = $p;
 			( $p==$ini_pages && $p==($_page_ - 6) )? $pstr = "..." : $pstr = $p;
 			( $p==$end_pages && $p==($_page_ + 6) )? $pstr = "..." : $pstr = $pstr;
 			$pages.= '<a class="navegacion-pagina '.$psel.' pagina-'.$odd.' '.$first.'"  '.$page_click.'>'.$pstr.'</a>';
 		}
 		
 		$_desde_ = max( 0, ($_page_ - 1) * $_nxpage_ + 1); // registro inicial es...
 		$_hasta_ = max( 0, min( ($_desde_+$_nxpage_-1) , $this->totalitems )); // último registro max entre ( rango o total)
 		 		
 		/*seteamos las paginas donde vamos a navegar*/
 		$rewind_page = 1;  // siempre la primera... podria ser de a N 
 		$prev_page = max( 1, $_page_-1 ); // pagina anterior
 		$next_page = min( $_page_+1, $_npages_ ); // pagina siguiente
 		$forward_page = $_npages_; // ultima pagina
 		
 		
		//echo "ok3";
 		
 		/*acciones de clicks*/
 		$return_click = ' 
 		onclick="javascript: document.'.$pnavigation_used_str.'.action = \''.$return_page_action.'\'; document.'.$pnavigation_used_str.'.submit();" 
 		
 		';
 		
 		$rewind_click = 'onclick="javascript:document.pagenavigation._page_.value='.$rewind_page.';document.pagenavigation.submit();"';
 		$prev_click = 'onclick="javascript:document.pagenavigation._page_.value='.$prev_page.';document.pagenavigation.submit();"';
 		$next_click = ' onclick="javascript:document.pagenavigation._page_.value='.$next_page.';document.pagenavigation.submit();" ';
 		$forward_click = ' onclick="javascript:document.pagenavigation._page_.value='.$forward_page.';document.pagenavigation.submit();" ';
 		
 		/*posicionado en primera pagina*/
	 	if ($_page_==1) {
			$rewind_class = ' class="'.$class_disabled.'"';
 			$prev_class = ' class="'.$class_disabled.'"';
 			
 			$rewind_click = "";
 			$prev_click = "";
 		}
 		
 		/*posicionado en ultima pagina*/
 		if ($next_page==$_page_) {
 			$next_class = ' class="'.$class_disabled.'"';
 			$forward_class = ' class="'.$class_disabled.'"';
 			
 			$forward_click = "";
 			$next_click = "";
 			
 		} 		
 		
 		if ($return_page) {
 			$return = '<div id="return" '.$return_click.$return_class.' >'.$return_page_text.'</div>';
 		}
 		
 		if (!$return_page) $rewind = '<div id="rewind" '.$rewind_click.$rewind_class.' ></div>';
 		if (!$return_page) $prev = '<div id="prev" '.$prev_click.$prev_class.' ></div>';
 		if (!$return_page) $range = '<div id="rango">[START]-[END] de [COUNT]</div>';
 		if (!$return_page) $next = '<div id="next" '.$next_click.$next_class.' ></div>';
 		if (!$return_page) $forward = '<div id="forward" '.$forward_click.$forward_class.' ></div>';
 		
 		
 		
 		/*
if ($_nxintervalo_=='') $_nxintervalo_ = '30';
			if ($_intervalo_=='') $_intervalo_ = 1;
						
			//RESULTADOS					
			if ( $_nresultados_=='' || !isset($_nresultados_)) {
				$this->Contenidos->m_tcontenidos->Count();
				$_nresultados_ = $this->Contenidos->m_tcontenidos->nresultados;
				if ($_nxintervalo_=='max') $nint=$_nresultados_;
				else $nint=$_nxintervalo_;
			}		
			
			if ($_nresultados_>0) {
				$_desde_ = ($_intervalo_ - 1) * $nint + 1;
				$_hasta_ = min( ($_desde_+$nint-1) , $_nresultados_);
				$_nintervalos_ = ceil( $_nresultados_ / $nint);
				$this->Contenidos->m_tcontenidos->LimiteSQL( ($_desde_-1), $nint );
				$this->Contenidos->m_tcontenidos->Open();
			}
* */
 		
 		$range = str_replace(
 				array("[START]","[END]","[COUNT]","[PAGES]"),
 				array( $_desde_, $_hasta_,  $this->totalitems, $pages ),
 					$range );
 		
 		/// el prev y el next se muestran 
 		$nav_str = $form_vars.$return.$rewind.$prev.$range.$next.$forward;
 		//echo "ok4";
 		//Debug($template);
 		
		/*Al fin limitamos los resultados...*/ 		
 		$this->LimiteSQL( ($_desde_-1), $_nxpage_ );
 		 		
 		if ($template!="") {

 			return str_replace( 
 					array("[PAGENAVIGATION]","[START]","[END]","[COUNT]","[PAGES]"), 
 					array($nav_str, $_desde_, $_hasta_, $this->totalitems, $pages) , 
 					$template );
 			
 		} else return $nav_str;
 		 		
 	}
 	

	function SetTemplateResultado($template) {
		$this->templateresultado = $template;
	}

	function TextoEncabezado( $rowtemplate="" ) {
		$res = "";
		
		if ($rowtemplate=="") {
			$__template__ = $this->templateresultado;
		}
		else 
		{
			$__template__ = $rowtemplate;
		}


		foreach ($this->campos as $campo) {
			//MOSTRAMOS LAS REFERENCIAS A OTRAS TABLAS
			if ($campo['nreferencias']>0) { 
				$referencias = $campo['referencias'];
					foreach($referencias as $referencia) {
						if ($referencia['etiqueta']!='') {
							$__template__ = str_replace( "*".$this->nombre.".".$campo['nombre']."*", $referencia['etiqueta'] , $__template__);
						}
					}
			}

			if (count($this->camposalias)>0)
			foreach($this->camposalias as $calias=>$val ) {
				$__template__ = str_replace( "*".$calias."*", $calias , $__template__);			
			}
			
			if ($campo['tipo']=='FECHA') {
				$__template__ = str_replace("*".$this->nombre.".".$campo['nombre']."*",$campo['etiqueta'],$__template__);
				$__template__ = str_replace("*".$this->nombre.".".$campo['nombre']."*FECHA*", $campo['etiqueta']." fecha",$__template__);				
				$__template__ = str_replace("*".$this->nombre.".".$campo['nombre']."*HORA*", $campo['etiqueta']." hora",$__template__);
			} else if ($campo['tipo']=='ENTERO') {					
				//$__template__ = str_replace("*".$this->nombre.".".$campo['nombre']."*F2HMSC*",F2HMSC($row[$this->nombre.".".$campo['nombre']]),$__template__);
				$__template__ = str_replace("*".$this->nombre.".".$campo['nombre']."*",$campo['etiqueta'],$__template__);
			}	else $__template__ = str_replace("*".$this->nombre.".".$campo['nombre']."*", $campo['etiqueta'] ,$__template__);

		}			
		$res = $__template__;

		
		return $res;
	}
	
	function TextoRegistro( $row, $rowtemplate="" ) {
		$res = "";
		
		if ($rowtemplate=="") {
			$__template__ = $this->templateresultado;
		}
		else 
		{
			$__template__ = $rowtemplate;
		}
		
		if (is_array($row)) {
			foreach ($this->campos as $campo) {
				
				//MOSTRAMOS LAS REFERENCIAS A OTRAS TABLAS
				if ($campo['nreferencias']>0) { 
					$referencias = $campo['referencias'];
						foreach($referencias as $referencia) {
							if ($referencia['etiqueta']!='') {
								$__template__ = str_replace( "*".$this->nombre.".".$campo['nombre']."*",$row[$referencia['tabla'].".".$referencia['muestra']], $__template__);
							}
						}
				}

				if (count($this->camposalias)>0)
				foreach($this->camposalias as $calias=>$val ) {
					$__template__ = str_replace( "*".$calias."*",$row[$calias] , $__template__);			
				}
				
				if ($campo['tipo']=='FECHA') {
					$__template__ = str_replace("*".$this->nombre.".".$campo['nombre']."*",$row[$this->nombre.".".$campo['nombre']],$__template__);
					$__template__ = str_replace("*".$this->nombre.".".$campo['nombre']."*FECHA*",Fecha($row[$this->nombre.".".$campo['nombre']]),$__template__);				
					$__template__ = str_replace("*".$this->nombre.".".$campo['nombre']."*HORA*",Hora($row[$this->nombre.".".$campo['nombre']]),$__template__);
				}	else if ($campo['tipo']=='ENTERO') {					
					//$__template__ = str_replace("*".$this->nombre.".".$campo['nombre']."*F2HMSC*",F2HMSC($row[$this->nombre.".".$campo['nombre']]),$__template__);
					$__template__ = str_replace("*".$this->nombre.".".$campo['nombre']."*",$row[$this->nombre.".".$campo['nombre']],$__template__);
				}	else $__template__ = str_replace("*".$this->nombre.".".$campo['nombre']."*", $row[$this->nombre.".".$campo['nombre']] ,$__template__);
				
			}
			$res = $__template__;
		} else DebugError("registro no existe");
		
		return $res;
	}
	
	function ImprimirResultados($header="",$rowtemplate="",$footer="", $echo=true) {
		$invcolor = -1;
		$rcolorg = '#CCCCCC';
		$rcolorb = '#FFFFFF';
		$rcolor = $rcolorg;
		$res = "";
		
		if ($rowtemplate!="") {
			$this->templateresultado = $rowtemplate;
		}
		
		//LOS TITULOS DE LA TABLA
		if ($this->templateresultado=='') {
	  		$res.= '<table border=0 cellpadding=2 cellspacing=1  width="100%" bgcolor="#000000">';
	  		$res.= '<tr>';
	  		foreach ($this->campos as $campo) {
	  			if ($campo['etiqueta']!='') {
	  				$res.= '<td width="'.$campo['ancho'].'" bgcolor="#444444"><span class="titulotabla">'.$campo['etiqueta'].'</span></td>';				
	  			}
	  			if ($campo['nreferencias']>0) {
	  				$referencias = $campo['referencias'];
	  				foreach($referencias as $referencia) {
	  					if ($referencia['etiqueta']!='') $res.= '<td bgcolor="#444444"><span class="titulotabla">'.$referencia['etiqueta'].'</span></td>';
	  				}
	  			}
	  		}
	  		$res.= '<td colspan=3 bgcolor="#444444" width="77"><span class="titulotabla">ACCION</span></td></tr>';		  		
		}
		
		//LOS REGISTROS				
		if ($this->nresultados>0) {
			if ($this->templateresultado!='') {
				while ($row = $this->Fetch($this->resultados)) {
					
					//ShowMessage("row:".print_r($row,true));
					
					$__template__ = $this->templateresultado;
					
					foreach ($this->campos as $campo) {
						
						//MOSTRAMOS LAS REFERENCIAS A OTRAS TABLAS
						if ($campo['nreferencias']>0) { 
							$referencias = $campo['referencias'];
								foreach($referencias as $referencia) {
									if ($referencia['etiqueta']!='') {
										$__template__ = str_replace( "*".$this->nombre.".".$campo['nombre']."*",$row[$referencia['tabla'].".".$referencia['muestra']], $__template__);
									}
								}
						}
						

						if (count($this->camposalias)>0)
						foreach($this->camposalias as $calias=>$val ) {
							$__template__ = str_replace( "*".$calias."*",$row[$calias], $__template__);			
						}
						
						if ($campo['tipo']=='FECHA') {
							$__template__ = str_replace("*".$this->nombre.".".$campo['nombre']."*",Fecha($row[$this->nombre.".".$campo['nombre']])."&nbsp;".Hora($row[$this->nombre.".".$campo['nombre']]),$__template__);
							$__template__ = str_replace("*".$this->nombre.".".$campo['nombre'].":FECHA*",Fecha($row[$this->nombre.".".$campo['nombre']]),$__template__);				
							$__template__ = str_replace("*".$this->nombre.".".$campo['nombre'].":HORA*",Hora($row[$this->nombre.".".$campo['nombre']]),$__template__);
						}	else if ($campo['tipo']=='ENTERO') {					
							//$__template__ = str_replace("*".$this->nombre.".".$campo['nombre']."*F2HMSC*",F2HMSC($row[$this->nombre.".".$campo['nombre']]),$__template__);
							$__template__ = str_replace("*".$this->nombre.".".$campo['nombre']."*",$row[$this->nombre.".".$campo['nombre']],$__template__);
						}	else $__template__ = str_replace("*".$this->nombre.".".$campo['nombre']."*", $row[$this->nombre.".".$campo['nombre']] ,$__template__);
						
					}
					$res.= $__template__;
				}
			} else {
				//$res.= '<table border=0 cellpadding=2 cellspacing=1  width="100%" bgcolor="#000000">';
				while ($row = $this->Fetch($this->resultados)) {
					$res.= '<tr>';
				   if ($this->debug=='si') { 
	   					$res.= '<td>';
						$res.= '<img src="../images/debug.png" alt="';
						foreach($row as $key => $val) {
							$res.= 'key: '.$key.' val: '.$val.'\n';
						}
						$res.= '">';
	   					$res.= '</td>';
				   }					
				   $res.= '</tr><tr>';
					//ROWCOLORS !!!
					$invcolor = $invcolor*-1;								
					if ($invcolor==-1) { $rcolor = $rcolorg; } else { $rcolor = $rcolorb; }				
					foreach ($this->campos as $campo) {
						//SI EL VALOR ETIQUETA ESTA VACIO NO LO MOSTRAMOS
						if ($campo['etiqueta']!='') {$res.= '<td bgcolor="'.$rcolor.'" class="campotabla">'.$row[$this->nombre.".".$campo['nombre']].'</td>';}
						//MOSTRAMOS LAS REFERENCIAS A OTRAS TABLAS
						if ($campo['nreferencias']>0) {
							$referencias = $campo['referencias'];
								foreach($referencias as $referencia) {
									if ($referencia['etiqueta']!='') $res.= '<td bgcolor="'.$rcolor.'" class="campotabla">'.$row[$referencia['tabla'].".".$referencia['muestra']].'</td>';
								}
						}
					}
					$res.= '<td bgcolor="'.$rcolor.'" width="14">';
					if ($this->permisos['agregar']=='si') {
						$res.= '<a href="javascript:nuevo();" title="Nuevo"><img vspace="5" hspace="3" src="/wiwe/inc/images/agregar.gif" alt="" width="17" height="17" border="0"  alt="agregar" onMouseOver="javascript:showimg(\'../images/agregar_over.gif\');" onMouseOut="javascript:showimg(\'../images/agregar.gif\');"></a>';
					}
					$res.= '</td>';
					$res.= '<td bgcolor="'.$rcolor.'" width="17">';
					if ($this->permisos['modificar']=='si') {
						$res.= '<a href="javascript:modificar('.$row[$this->nombre.".".$this->primario].');"  title="Modificar"><img vspace="5" hspace="3" src="/wiwe/inc/images/editar.gif" alt="" width="17" height="17" border="0" alt="ver/editar" onMouseOver="javascript:showimg(\'../images/editar_over.gif\');" onMouseOut="javascript:showimg(\'../images/editar.gif\');"></a>';
					}
					$res.= '</td>';
					if ($this->permisos['borrar']=='si') {				
						$res.= '<td bgcolor="'.$rcolor.'" width="14"><a href="javascript:borrar('.$row[$this->nombre.".".$this->primario].')" title="Borrar"><img vspace="5" hspace="3" src="/wiwe/inc/images/borrar.gif" alt="" width="14" height="17" border="0" alt="borrar" onMouseOver="javascript:showimg(\'../images/borrar_over.gif\');" onMouseOut="javascript:showimg(\'../images/borrar.gif\');"></a>';
					}
					$res.= '</td>';
					$res.= '</tr>';
				}
				$this->FinalizarSQL();
				$res.= '</table>';
			}
		} else {
			$res.= 'No hay resultados';
			if ($this->SQL!='') $this->FinalizarSQL();
		}
		
		if ($header!="" && $footer!="") {
			$res = $header.$res.$footer;
		}
		
		if ($echo) echo $res;
		return $res;
	}
	
	/**
	 * Traduce los tipos de aquellos campos tipicos de SQL, MYSQL, INTERBASE al valor conocido por la clase
	 * 
	 *
	 * @param unknown_type $__sql_type__
	 * @return unknown
	 */
	function SqlTypeToStr( $__sql_type__) {
		
		//habria que sacar el size del int o del varchar sobretodo!!!
		
		$__sql_type__ = strtolower( $__sql_type__ );
		
		//TEXTO | NUMERO | DECIMAL | BLOB | BLOBTEXTO | FECHA
		if ( ! ( strpos( $__sql_type__, "int" ) === false ) ||
			! ( strpos( $__sql_type__, "bool" ) === false ) ) {
			return "ENTERO";
		} else
		if ( ! ( strpos( $__sql_type__, "varchar" ) === false ) ||
			! ( strpos( $__sql_type__, "char" ) === false )) {
			return "TEXTO";
		} else
		if ( ! ( strpos( $__sql_type__, "float" ) === false ) ||
			! ( strpos( $__sql_type__, "double" ) === false ) ||
			! ( strpos( $__sql_type__, "decimal" ) === false ) ) {
			return "DECIMAL";
		} else
		if ( ! ( strpos( $__sql_type__, "blob" ) === false ) ||
			! ( strpos( $__sql_type__, "binary" ) === false ) ) {
			return "BLOB";
		} else
		if ( ! ( strpos( $__sql_type__, "text" ) === false ) ) {
			return "BLOBTEXTO";
		} else
		if ( ! ( strpos( $__sql_type__, "enum" ) === false ) ) {
			return "TEXTO";
		} else		
		if (	! ( strpos( $__sql_type__, "timestamp" ) === false ) ||
				! ( strpos( $__sql_type__, "time" ) === false ) ||
				! ( strpos( $__sql_type__, "date" ) === false ) ||
				! ( strpos( $__sql_type__, "datetime" ) === false ) ) {
			return "FECHA";
		}	else {
			ShowError("Falta traducir:".$__sql_type__);
		}	
	}
	
	/**
	 * Carga automatica de defincion de campos de la tabla
	 *
	 */
	function LoadDefinition() {
		//Debug("connection open");
		
		$this->Connect();
		
		//$this->CONN = mysql_connect ($this->db['servidor'],$this->db['usuario'],$this->db['password']) or die ("Error: No pudo conectarse al servidor [".$this->db['servidor']."], revise los datos de la configuración.  (servidor,usuario y contraseña).<br>Gracias.");
		
		//mysql_select_db($this->db['nombre'],$this->CONN) or die('Error: No se pudo seleccionar la base datos ['.$this->db['nombre'].'], revise los datos de la configuración.<br>Gracias.');
		if ($this->db['tipodb']=='mysql'){
			$result = mysql_query("SHOW COLUMNS FROM ".$this->nombre );
			if (!$result) {
			    
				echo 'No se pudo ejecutar: ' . mysql_error();
			    
			    exit;
			}
			if (mysql_num_rows($result) > 0) {
			    while ($row = mysql_fetch_assoc($result)) {
					
			    	$tipo = $this->SqlTypeToStr($row['Type']);
					if ($tipo!="") {
			    		$this->AgregarCampo( $row['Field'], $row['Field'], $this->SqlTypeToStr($row['Type']), '10%','NULL','','si','si', 20 );
					} else {
						DebugError( " El tipo no se pudo traducir : ".$row['Type'] );
					}
			    }
			}
		} else if($this->db['tipodb']=='interbase') {
			
			$result = ibase_query("select * from RDB\$RELATION_FIELDS WHERE RDB\$RELATION_NAME='".$this->nombre."'" );
			if (!$result) {
			    
				echo 'No se pudo ejecutar: ' . ibase_errmsg();
			    
			    exit;
			}
			//if (mysql_num_rows($result) > 0) {
			    while ($row = ibase_fetch_assoc($result)) {
					//echo '<pre>';
			    	//print_r($row);
					//echo '</pre>';
			    	$tipo = $this->SqlTypeToStr($row['Type']);
			    	
					if ($tipo!="") {
			    		$this->AgregarCampo( $row['RDB$FIELD_NAME'], $row['RDB$FIELD_NAME'], $this->SqlTypeToStr($row['Type']), '10%','NULL','','si','si', 20 );
					} else {
						DebugError( " El tipo no se pudo traducir : ".$row['Type'] );
					}
			    }
			//}
		}
		
	}
	
	function Describe() {
		reset ($this->campos);
		echo '<H3>SERVIDOR:</H3>'.$this->db['servidor'].'<br><br>';
		echo '<H3>BASE DE DATOS:</H3>'.$this->db['nombre'].'<br><br>';
		echo '<H3>TABLA:</H3>'.$this->nombre.'<br><br>';		
		echo '<table border=1 width=100%  cellpadding="0" cellspacing="0"><tr><td colspan=7><b>'.$this->nombre.'</b></td></tr><tr><td><b>CAMPO</b></td><td><b>ETIQUETA</b></td><td><b>TIPO</b></td><td><b>REFERENCIAS</b></td></tr>';
		foreach( $this->campos as $campo) {
			echo '<tr><td>'.$campo['nombre'].'</td><td>'.$campo['etiqueta'].'</td><td>'.$campo['tipo'].'</td>';
			echo '<td>';
			if ($campo['nreferencias']>0) {
				$referencias = $campo['referencias'];
				echo '<table width=100%  border="1" cellpadding="0" cellspacing="0">';
				foreach($referencias as $referencia) {
					echo '<tr>';
					echo '<td>'.$referencia['tipo'].'</td>';					
					echo '<td>'.$referencia['etiqueta'].'</td>';
					if ($referencia['tipo']=='directa') {
						echo '<td>'.$referencia['tabla'].'</td>';
						echo '<td>'.$referencia['clave'].'</td>';										
					} elseif ($referencia['tipo']=='anidada') {
						echo '<td>'.$referencia['tabla1'].'</td>';
						echo '<td>'.$referencia['clave1'].'</td>';										
						echo '<td>'.$referencia['tabla'].'</td>';										
						echo '<td>'.$referencia['clave'].'</td>';																						
					} elseif ($referencia['tipo']=='combo') {
						$combo = $referencia['combo'];
						echo '<td>';
						foreach($combo as $comboitem) {
							echo $comboitem.'<br>';
						}
						echo '</td>';
					}
					echo '<td>'.$referencia['muestra'].'</td>';																					
					echo '</tr>';					
				}
				echo '</table>';
			} else echo 'NO';
			echo '</td>';			
			echo '</tr>';
		}
		echo "</table>";
		
		//INDICES
		echo '<H3>INDICES:</H3><br>PRIMARIO:'.$this->primario.'<br><br>';
		echo '<table border=1 cellpadding=0 cellspacing=0>'; 
		echo '<tr><td><b>NOMBRE</b></td><td><b>INDICE</b></td></tr>';
		foreach($this->indices as $indice) {
			echo '<tr><td>'.$indice['nombre'].'</td><td>'.$indice['indice'].'</td></tr>';
		}
		echo '</table>';
		
		
		echo "<table><tr><td>".$this->SQL."</td></tr></table>";		
		echo "</body></html>";		
	}
	
	
}

?>
