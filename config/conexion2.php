<?php 
	class Conexion{
		private $host, $user, $BD, $passw, $link;

		public function __construct($host, $user, $BD, $passw){
			$this->host = $host;
			$this->user = $user;
			$this->BD = $BD;
            $this->passw = $passw;
		}

		public function conectar(){
			if (!($this->link=mysqli_connect($this->host,$this->user,$this->passw))){ 
      			echo "Error al conectar con el servidor"; 
      			exit(); 
      		}
      		
      		if(!mysqli_select_db($this->link,$this->BD)){
      			echo "Error al conectar con la base de datos"; 
      			exit();
      		}
		}

		public function consultas($col, $tabla, $con){
			return mysqli_query($this->link, "SELECT $col FROM $tabla $con");
		}

		public function guardar($tabla, $values){
			mysqli_query($this->link, "INSERT INTO $tabla VALUES ($values)");
		}

		public function cerrar(){
			mysqli_close($this->link);
		}
	}
?>