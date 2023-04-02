<?php

error_reporting(E_ALL ^ E_DEPRECATED);

class BD {
    

    private $con;

    /**
     * Obtem uma conexão do pool de conexões.
     * @return Connection conexï¿½o com o banco de dados
     */
    public static function conectar() 
    {
        $con = mysqli_connect(Config::BANCO_SERVIDOR,
                    Config::BANCO_USUARIO,
                    Config::BANCO_SENHA,
                    Config::BANCO_NOME);
        
        mysqli_set_charset($con, "latin1");

        //var_dump(mysqli_get_charset($con));
        
        if (mysqli_connect_errno()) {
            echo "Nãoo foi possí­vel conectar ao servidor de banco de dados." . PHP_EOL;
			echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
			echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
			exit;
        }

        return $con;
        
    }

    public static function conectarOO() 
    {
        $con = new mysqli(Config::BANCO_SERVIDOR,
        Config::BANCO_USUARIO,
        Config::BANCO_SENHA,
        Config::BANCO_NOME);
        if ($con->connect_errno) {
            echo "Não foi possí­vel conectar ao servidor de banco de dados: " . $mysqli->connect_error;
        }
        
        $con->set_charset("latin1");

        //var_dump(mysqli_get_charset($con));
        
        return $con;
      
    }
    
    public static function conectarPDO()
    {
        $pdo = new PDO( "mysql:host=" . Config::BANCO_SERVIDOR . ";dbname=" . 
                Config::BANCO_NOME . ";charset=utf8mb4", 
                Config::BANCO_USUARIO, Config::BANCO_SENHA);
        $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    /**
     * Converte a antiga funcao para extensao mysql para mysqli.
     * @return Connection conexão com o banco de dados
     */
    public static function mysqli_result($res, $row, $field=0) { 
        $res->data_seek($row); 
        $datarow = $res->fetch_array(); 
        return $datarow[$field]; 
    }

    public static function mysqli_query($query, &$con = null) {

        if($con==null) { $con=self::conectar(); }

        $result = mysqli_query($con,$query) or trigger_error(mysqli_error($con));
        return $result;
    }
}