<?php
    class conectar{
        private $host;
        private $nombreBD;
        private $usuario;
        private $password;

        private $conexion;
        //1: Creo la variable 'statement' para guardar la sentencia SQL
        private $statement;
        //2: Se crea la variable que almacena los resultados obtenidos
        private $rs; 


        public function __construct($host, $nombreBD, $usuario, $password){
            $this->host=$host;
            $this->nombreBD=$nombreBD;
            $this->usuario=$usuario;
            $this->password= $password;                        
        }

        public function getConexion(){            

            try{
                $this->conexion= new PDO("mysql:host=".$this->host.
                ";dbname=".$this->nombreBD,
                $this->usuario,$this->password);

            }catch (PDOException $e){
                print json_encode($e->getMessage());
                die();
            }

            return $this->conexion;
        }

        public function desconectar() {
            $this->rs=null;
            $this->statement=null;
            $this->conexion=null;
        }

        
    }

?>