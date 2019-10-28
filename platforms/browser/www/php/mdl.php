<?php
	include_once "PDO_database.php"; 
session_start();

	class App extends PDO_database {

		private $tablaUsuarios = [
			'co_usuario'   		=> 'co_usuario',
			'tx_nombre'    		=> 'tx_nombre',
			'tx_apellido'  		=> 'tx_apellido',
			'tx_nick'      		=> 'tx_nick',
			'tx_pass'      		=> 'tx_pass',
			'fe_nacimiento'		=> 'fe_nacimiento',
			'co_sexo'      		=> 'co_sexo',
			'tx_email'     		=> 'tx_email',
			'tx_tlf'       		=> 'tx_tlf',
			'fe_creado'    		=> 'fe_creado',
			'co_creado_por'		=> 'co_creado_por',
			'co_activo'    		=> 'co_activo',
			'co_rol'       		=> 'co_rol'
		];

		public function entrar( $d ){
			$sql = "SELECT *
					FROM t01_usuarios u
					WHERE (u.tx_email = '".$d['tx_email']."' OR u.tx_nick = '".$d['tx_email']."')
					AND u.tx_pass = '".$d['tx_pass']."' AND u.co_activo = '1'
					";
			$a = $this->pdo->_query($sql);
			if( count( $a ) ){
				$this->setSession( $a );
			}
			$tf = count( $a ) ? "true" : "false";
			return "entrar({'success':".$tf.",datos:".json_encode($a)."})";
		}

		public function setSession( $a ){
			$_SESSION['co_usuario']   		= $a[0]['co_usuario'];                        
			$_SESSION['tx_nombre']    		= $a[0]['tx_nombre'];                       
			$_SESSION['tx_apellido']  		= $a[0]['tx_apellido'];                         
			$_SESSION['tx_nick']      		= $a[0]['tx_nick'];                     
			$_SESSION['tx_pass']      		= $a[0]['tx_pass'];                     
			$_SESSION['fe_nacimiento']		= $a[0]['fe_nacimiento'];                           
			$_SESSION['co_sexo']      		= $a[0]['co_sexo'];                     
			$_SESSION['tx_email']     		= $a[0]['tx_email'];                      
			$_SESSION['tx_tlf']       		= $a[0]['tx_tlf'];                    
			$_SESSION['fe_creado']    		= $a[0]['fe_creado'];                       
			$_SESSION['co_creado_por']		= $a[0]['co_creado_por'];                           
			$_SESSION['co_activo']    		= $a[0]['co_activo'];                       
			$_SESSION['co_rol']       		= $a[0]['co_rol'];                    
		}

/*		public function buscarUsuario( $datos ){
			$sql = "SELECT *
					FROM t04_usuarios u
					JOIN t05_rol r ON r.co_rol = u.co_rol
					WHERE u.tx_indicador = '".$datos['tx_indicador']."'";
			$ar = $this->pdo->_query($sql);
			return count( $ar ) ? "{success:true,result:".count( $ar ).",datos:".json_encode($ar)."}" : "{success:false}";
		}

		

		

		public function verUsuarios(){
			$sql = "SELECT *
					FROM t04_usuarios u
					JOIN t05_rol r ON r.co_rol = u.co_rol";
			$ar = $this->pdo->_query($sql);
			return count( $ar ) ? "{success:true,datos:".json_encode($ar)."}" : "{success:false}";
		}

		public function verRoles(){
			$sql = "SELECT * FROM t05_rol";
			$ar = $this->pdo->_query($sql);
			return count( $ar ) ? "{success:true,datos:".json_encode($ar)."}" : "{success:false}";
		}

		public function buscarDupUsr( $datos ){
			$sql = "SELECT * FROM t04_usuarios u 
					WHERE u.tx_indicador = '".$datos['tx_indicador']."'";
			$ar = $this->pdo->_query($sql);
			return count( $ar );
		}

		public function guardarUsuario( $sesion, $datos ){
			$insercion = array_intersect_key($datos, $this->tablaUsuarios);
			try {
				if( $datos['co_usuario'] == '' ){
					if( !$this->buscarDupUsr( $datos ) ){
						$a = $this->pdo->_insert('t04_usuarios', $insercion);
					}else{
						throw new PDOException('El indicador que desea ingresar ya existe.');	
					}
				}else{
					$cond = " co_usuario = '".$insercion['co_usuario']."'";
					$a = $this->pdo->_update('t04_usuarios', $insercion, $cond );
				}
				if( $a ){
					return "{success:true}";
				}else{
					throw new PDOException('Ocurrió un error. Intente de nuevo o reinicie la aplicación.');	
				}
			} catch (PDOException $e) {
				return "{success:false,msg:'".$e->getMessage()."'}";
			}
		}

		public function actDesUsr( $datos ){
			$act = $datos['bo_activo'] == 1 ? 0 : 1;
			$msg = $datos['bo_activo'] == 1 ? 'desactivado' : 'activado';
			$upd = [ "bo_activo" => $act ];
			$cond = " co_usuario = '".$datos['co_usuario']."' ";
			$r = $this->pdo->_update( 't04_usuarios' , $upd, $cond  );
			return $r === 1 ? "{success:true, msg:'El usuario ha sido ".$msg." satisfactoriamente'}" : "{success:false,msg:'Ocurrió un problema. Intente más tarde'}";
		}

		public function verRequerimientos( $datos ){
			$join = $datos['sel'] == 1 ? " JOIN t08_req_pos rp ON rp.co_requerimiento = r.co_requerimiento " : '';
			$stat = $datos['co_status'] == '' ? 0 : $datos['co_status'];
			$sql = "SELECT * 
					FROM t01_requerimiento r 
					JOIN t02_gerencia g ON r.co_gerencia = g.co_gerencia
					JOIN t09_tipo_req t ON t.co_tipo_req = r.co_tipo_req
					JOIN t06_nivel n ON n.co_nivel = r.co_nivel
					$join
					WHERE co_status = '$stat'
					ORDER BY fe_requerimiento DESC, r.co_requerimiento DESC";
			$ar = $this->pdo->_query($sql);
			return count( $ar ) ? "{success:true,datos:".json_encode($ar)."}" : "{success:false}";
		}

		public function verGerencias(){
			$sql = "SELECT *
					FROM t02_gerencia";
			$ar = $this->pdo->_query($sql);
			return count( $ar ) ? "{success:true,datos:".json_encode($ar)."}" : "{success:false}";
		}

		public function verNiveles(){
			$sql = "SELECT * FROM t06_nivel";
			$ar = $this->pdo->_query($sql);
			return count( $ar ) ? "{success:true,datos:".json_encode($ar)."}" : "{success:false}";
		}

		public function verTiposReq(){
			$sql = "SELECT *
					FROM t09_tipo_req";
			$ar = $this->pdo->_query($sql);
			return count( $ar ) ? "{success:true,datos:".json_encode($ar)."}" : "{success:false}";
		}

		public function guardarRequerimiento( $datos ){
			$insercion = array_intersect_key($datos, $this->tablaRequerimientos);
			$insercion['fe_requerimiento'] = date( 'Y-m-d' );
			$insercion['fe_estimada_cont'] = date( 'Y-m-d', strtotime( $datos['fe_estimada_cont'] ) );
			$insercion['tx_especialidad'] = $insercion['tx_especialidad'] != '' ? $insercion['tx_especialidad'] : 'NO APLICA';
			try {
				if( $datos['co_requerimiento'] == '' ){
					$a = $this->pdo->_insert('t01_requerimiento', $insercion);
				}else{
					$cond = " co_requerimiento = '".$insercion['co_requerimiento']."'";
					$a = $this->pdo->_update('t01_requerimiento', $insercion, $cond );
				}
				if( $a ){
					return "{success:true}";
				}else{
					throw new PDOException('Ocurrió un error. Intente de nuevo o reinicie la aplicación.');	
				}
			} catch (PDOException $e) {
				return "{success:false,msg:'".$e->getMessage()."'}";
			}
		}

		public function guardarPostulado( $datos ){
			$insercion = array_intersect_key($datos, $this->tablaPostulados);
			try {
				if( $datos['co_postulado'] == '' ){
					$insercion['fe_postulacion'] = date( 'Y-m-d' );
					$a = $this->pdo->_insert('t03_postulado', $insercion);
				}else{
					$cond = " co_postulado = '".$insercion['co_postulado']."'";
					$a = $this->pdo->_update('t03_postulado', $insercion, $cond );
				}
				if( $a ){
					return "{success:true}";
				}else{
					throw new PDOException('Ocurrió un error. Intente de nuevo o reinicie la aplicación.');	
				}
			} catch (PDOException $e) {
				return "{success:false,msg:'".$e->getMessage()."'}";
			}
		}	

		public function borrarRequerimiento( $datos ){
			$cond = [ 'co_requerimiento' => $datos['co_requerimiento'] ];
			$r = $this->pdo->_delete( 't08_req_pos' , $cond  );
			$cond = [ 'co_requerimiento' => $datos['co_requerimiento'] ];
			$r = $this->pdo->_delete( 't01_requerimiento' , $cond  );
			return $r === 1 ? "{success:true, msg:'El requerimiento ha sido borrado satisfactoriamente'}" : "{success:false,msg:'Ocurrió un problema. Intente más tarde'}";
			
			$ar = $this->pdo->_query($sql);
			return count( $ar ) ? "{success:true,datos:".json_encode($ar)."}" : "{success:false}";
		}

		public function verEstados(){
			$sql = "SELECT * FROM t10_estados";
			$ar = $this->pdo->_query($sql);
			return count( $ar ) ? "{success:true,datos:".json_encode($ar)."}" : "{success:false}";
		}

		public function verPostulados( $datos ){
			$join = $datos['co_tipo_req'] == 1 ? '' : ( $datos['co_tipo_req'] == 2 ? " JOIN t02_gerencia g ON g.co_gerencia = p.co_gerencia_origen_i " : '' );
			$select = $datos['co_tipo_req'] == 1 ? '' : ( $datos['co_tipo_req'] == 2 ? " , g.co_gerencia co_gerencia_origen_i, g.tx_gerencia tx_gerencia_origen_i " : '' );

			$join .= $datos['co_tipo_req'] == 1 ? '' : " JOIN t06_nivel n ON n.co_nivel = p.co_nivel_ie ";
			$select .= $datos['co_tipo_req'] == 1 ? '' : " , n.co_nivel co_nivel_ie, n.tx_nivel tx_nivel_ie";

			$tabla = " LEFT JOIN t08_req_pos rp ON rp.co_postulado = p.co_postulado ";
			$join .= $datos['lor'] == '' ? '' : $tabla;
			
			$datos['lor'] = $datos['lor'] == "IS NOT NULL" ? "IS NOT NULL AND rp.co_requerimiento = '".$datos['co_requerimiento']."'" : $datos['lor'];
			$cond = $datos['lor'] == '' ? '' : " AND rp.co_postulado ".$datos['lor'];

			$cond = $datos['co_status'] == '' ? $cond : ( $datos['co_status'] == 6 ? " AND p.co_status >= ".$datos['co_status'] : " AND p.co_status = ".$datos['co_status'] );

			$sql = "SELECT p.*, e.*
					$select 
					FROM t03_postulado p
					JOIN t10_estados e ON e.co_estado = p.co_estado
					$join
					WHERE co_tipo_req = '".$datos['co_tipo_req']."' $cond ";
			$ar = $this->pdo->_query($sql);
			return count( $ar ) ? "{success:true,total:".count( $ar ).",datos:".json_encode($ar)."}" : "{success:false}";
		}

		public function borrarPostulado( $datos ){
			$cond = [ 'co_postulado' => $datos['co_postulado'] ];
			$ar = $this->pdo->_delete( 't03_postulado', $cond );
			return count( $ar ) ? "{success:true}" : "{success:false}";
		}

		public function vincularPostulado( $datos ){
			$actualiz = [ 'co_status' => 2	];
			$cond = " co_postulado = '".$datos['co_postulado']."'";
			$a = $this->pdo->_update('t03_postulado', $actualiz, $cond );
			
			if( $a ){
				$insercion = [ 
					'co_postulado' => $datos['co_postulado'],
					'co_requerimiento' => $datos['co_requerimiento'] 
				];
				$b = $this->pdo->_insert('t08_req_pos', $insercion);	
				return count( $b ) ? "{success:true}" : "{success:false}";	
			}
		}

		public function desvincularPostulado( $datos ){
			$actualiz = [ 'co_status' => 1	];
			$cond = " co_postulado = '".$datos['co_postulado']."'";
			$a = $this->pdo->_update('t03_postulado', $actualiz, $cond );

			if( $a ){
				$cond = [ 
					'co_postulado' => $datos['co_postulado'],
					'co_requerimiento' => $datos['co_requerimiento'] 
				];
				$b = $this->pdo->_delete( 't08_req_pos', $cond );
				return count( $b ) ? "{success:true}" : "{success:false}";	
			}
			
		}

		public function cambiarIME( $datos ){
			$actualiz = [ 
							'nu_IME_ae' => $datos['nu_IME_ae'],
							'co_status' => $datos['co_status'] 
						];
			$cond = " co_postulado = '".$datos['co_postulado']."'";
			$a = $this->pdo->_update('t03_postulado', $actualiz, $cond );			
			return count( $a ) ? "{success:true}" : "{success:false}";
		}

		public function cambiarMED( $datos ){
			$actualiz = [ 
							'bo_apto_med_ae' => $datos['bo_apto_med_ae'],
							'co_status' => $datos['co_status'] 
						];
			$cond = " co_postulado = '".$datos['co_postulado']."'";
			$a = $this->pdo->_update('t03_postulado', $actualiz, $cond );			
			return count( $a ) ? "{success:true}" : "{success:false}";
		}

		public function cambiarPCP( $datos ){
			$fi = $datos['bo_apto_pcp_ae'] == 1 ? date('Y-m-d') : NULL;
			$actualiz = [
				'fe_ingreso' => $fi,
				'bo_apto_pcp_ae' => $datos['bo_apto_pcp_ae'],
				'co_status' => $datos['co_status']
			];
			$cond = " co_postulado = '".$datos['co_postulado']."'";
			$a = $this->pdo->_update('t03_postulado', $actualiz, $cond );
			if( $a ){
				$actualiz = [ 'co_status' => 1 ];
				$cond = " co_requerimiento = '".$datos['co_requerimiento']."'";
				$b = $this->pdo->_update('t01_requerimiento', $actualiz, $cond );
				return count( $b ) ? "{success:true}" : "{success:false}";
			}
		}

		public function cambiarSUP( $datos ){
			$fi = $datos['bo_aprob_sup'] == 1 ? date('Y-m-d') : NULL;
			$actualiz = [
				'fe_ingreso' => $fi,
				'bo_aprob_sup' => $datos['bo_aprob_sup'],
				'co_status' => $datos['co_status']
			];
			$cond = " co_postulado = '".$datos['co_postulado']."'";
			$a = $this->pdo->_update('t03_postulado', $actualiz, $cond );
			if( $a ){
				$actualiz = [ 'co_status' => 1 ];
				$cond = " co_requerimiento = '".$datos['co_requerimiento']."'";
				$b = $this->pdo->_update('t01_requerimiento', $actualiz, $cond );
				return count( $b ) ? "{success:true}" : "{success:false}";
			}
		}

		public function verUsuariosReporte(){
			$sql = "SELECT u.tx_indicador, u.tx_usuario, r.tx_rol, u.bo_activo
					FROM t04_usuarios u
					JOIN t05_rol r ON r.co_rol = u.co_rol";
			$ar = $this->pdo->_query($sql);
			return $ar;
		}

		public function verRequerimientosReporte(){
			$sql = "SELECT r.fe_requerimiento, t.tx_tipo_req, r.fe_estimada_cont, g.tx_gerencia, r.tx_especialidad,
			n.tx_nivel, r.tx_edificio, r.tx_piso, r.tx_oficina, r.tx_tutor, r.tx_cargo, r.tx_extension,
			r.tx_observaciones, r.co_status   
					FROM t01_requerimiento r 
					JOIN t02_gerencia g ON r.co_gerencia = g.co_gerencia
					JOIN t09_tipo_req t ON t.co_tipo_req = r.co_tipo_req
					JOIN t06_nivel n ON n.co_nivel = r.co_nivel
					ORDER BY fe_requerimiento DESC, r.co_requerimiento DESC";
			$ar = $this->pdo->_query($sql);
			return $ar;
		}

		public function verCAReporte( $datos ){
			$status = $datos == 5 ? " AND p.co_status = $datos " : ( $datos == 6 ? " AND p.co_status >= $datos " : '' );
			$sql = "SELECT p.tx_cedula, p.tx_nombres, p.tx_apellidos, p.fe_nacimiento, p.bo_sexo, p.tx_direccion, e.tx_estado, p.tx_tlf, p.fe_postulacion, p.tx_especialidad, p.tx_instituto, p.fe_estudio_desde_a, p.fe_estudio_hasta_a, p.nu_IME_ae, p.bo_apto_med_ae, p.bo_apto_pcp_ae, p.fe_ingreso, s.tx_status
					FROM t03_postulado p
					JOIN t09_tipo_req tr ON tr.co_tipo_req = p.co_tipo_req
					JOIN t10_estados e ON e.co_estado = p.co_estado
					JOIN t07_status s ON s.co_status = p.co_status
					WHERE p.co_tipo_req = '1' $status
					ORDER BY p.co_status";
			$ar = $this->pdo->_query($sql);
			return $ar;
		}

		public function verCIReporte( $datos ){
			$status = $datos == 5 ? " AND p.co_status = $datos " : ( $datos == 6 ? " AND p.co_status >= $datos " : '' );
			$sql = "SELECT p.tx_cedula, p.tx_nombres, p.tx_apellidos, p.fe_nacimiento, p.bo_sexo, p.tx_direccion, e.tx_estado, p.tx_tlf, p.fe_postulacion, p.tx_especialidad, p.tx_instituto, n.tx_nivel, p.tx_area_origen_i, g.tx_gerencia, p.co_tipo_nomina_i, p.tx_cargo_i, p.fe_ingreso, s.tx_status
					FROM t03_postulado p
					JOIN t02_gerencia g ON g.co_gerencia = p.co_gerencia_origen_i
					JOIN t09_tipo_req tr ON tr.co_tipo_req = p.co_tipo_req
					JOIN t10_estados e ON e.co_estado = p.co_estado
					JOIN t07_status s ON s.co_status = p.co_status
					JOIN t06_nivel n ON n.co_nivel = p.co_nivel_ie
					WHERE p.co_tipo_req = '2' $status
					ORDER BY p.co_status";
			$ar = $this->pdo->_query($sql);
			return $ar;
		}

		public function verCEReporte( $datos ){
			$status = $datos == 5 ? " AND p.co_status = $datos " : ( $datos == 6 ? " AND p.co_status >= $datos " : '' );
			$sql = "SELECT p.tx_cedula, p.tx_nombres, p.tx_apellidos, p.fe_nacimiento, p.bo_sexo, p.tx_direccion, e.tx_estado, p.tx_tlf, p.fe_postulacion, p.tx_especialidad, p.tx_instituto, n.tx_nivel, p.nu_IME_ae, p.bo_apto_med_ae, p.bo_apto_pcp_ae, p.fe_ingreso, s.tx_status
					FROM t03_postulado p
					JOIN t09_tipo_req tr ON tr.co_tipo_req = p.co_tipo_req
					JOIN t10_estados e ON e.co_estado = p.co_estado
					JOIN t07_status s ON s.co_status = p.co_status
					JOIN t06_nivel n ON n.co_nivel = p.co_nivel_ie
					WHERE p.co_tipo_req = '3' $status
					ORDER BY p.co_status";
			$ar = $this->pdo->_query($sql);
			return $ar;
		}*/
	}

?>