<?php



class conexion {

    private $server;
    private $user;
    private $password;
    private $database;
    private $port;
    private $conexion;

  /**mandamos a llamr el metodo constructor */
    function __construct(){
        $listadatos = $this->datosConexion();  /**esta variable esta guardando todos los datos de la conexion del archivo config */
       /**aqui vamos a recorerlo.. recorremos $listadatos y lo almacenamos en value */
        foreach ($listadatos as $key => $value) { 
            /**aqui se igualan los atributos  de la clase a los valores */ 
            /** la variable o el atributo server va a ser igual a $value  y el valor del archivo */
            $this->server = $value['server'];
            $this->user = $value['user'];
            $this->password = $value['password'];
            $this->database = $value['database'];
            $this->port = $value['port'];
        }
        /**creamos a crear la conexion */
        /**mysqli resive  esos parametros */
        $this->conexion = new mysqli($this->server,$this->user,$this->password,$this->database,$this->port);
        if($this->conexion->connect_errno){  /**esta condicion nos dira si huvo un error en ela conexion */
            echo "algo va mal con la conexion";
            die();
        }

    }
  

  /*  con e esta funcion vamos a obtener los datos del archivo config  */ 
    /** basicamente lo que se ará en esta funcion es obtener los datos de archivo json (config) y comvertilo en un Array  */

    private function datosConexion(){   
        $direccion = dirname(__FILE__);  /**almacenamos la direccion del archivo  config*/
        $jsondata = file_get_contents($direccion . "/" . "config");  /** aqui se habre el archivo guardar todo el contenido y lo devuelve  */
        return json_decode($jsondata, true); /**aqui retornamos pero convertido en un Array */
    }

  
/** aqui convertimos los caracteres a UTF8..para quitarnos losproblemas de legivilidad como tildes ñ etc  */
    private function convertirUTF8($array){
     /**este es un metodo de php (array_walk_recursive) */   
     array_walk_recursive($array,function(&$item,$key){
        /**si  detecta ningun caracter  $item lon cambia a utf8  */
            if(!mb_detect_encoding($item,'utf-8',true)){
                $item = utf8_encode($item);
            }
        });
        return $array;
    }

/** con este metodo obtenemos todo los datos  */
    public function obtenerDatos($sqlstr){
        $results = $this->conexion->query($sqlstr);
        $resultArray = array();
        foreach ($results as $key) {
            $resultArray[] = $key;
        }
        return $this->convertirUTF8($resultArray);

    }


/**este es un metodo para guardar los datos */
    public function nonQuery($sqlstr){
        $results = $this->conexion->query($sqlstr);
        return $this->conexion->affected_rows;
    }


    //INSERT  /**solo nos retorna o devuelve  el ID del la  fila nueva o afecctada  */
    public function nonQueryId($sqlstr){
        $results = $this->conexion->query($sqlstr);
         $filas = $this->conexion->affected_rows;
         if($filas >= 1){
            return $this->conexion->insert_id;
         }else{
             return 0;
         }
    }
     
    //encriptar

    protected function encriptar($string){
        return md5($string);
    }





}



?>