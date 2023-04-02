<?php
/**
 * Descrição da classe Aluno
 *
 * Utilizada para representar objetos Alunos
 */
require_once "$BASE_DIR/classes/Pessoa.php";
require_once "$BASE_DIR/classes/Aluno.php";
require_once "$BASE_DIR/classes/Util.php";

class Declaracao {

    private $id;
    private $idPessoa;
    private $assinatura;
    private $assinaturaExib;
    private $matricula;
    private $dataEmissao;
    private $periodo;
    private $tipo;
    private $dados;


    function __construct($matricula, $periodo, $tipo, $array = 'novo') {
        
        $con = BD::conectarOO();

        //Se a tabela Declaracao não existir, ela será criada
        //$result = $con->query('SELECT 1 FROM Declaracao LIMIT 1') or $con->query("CREATE TABLE `Declaracao` ( `id` INT NOT NULL AUTO_INCREMENT, `tipo` TINYINT(2) NOT NULL, `validacao` VARCHAR(32) NOT NULL, `periodo` TINYINT(1) NULL, `matricula` VARCHAR(13) NOT NULL , `dataEmisao` DATETIME NOT NULL , PRIMARY KEY (`id`), UNIQUE (`validacao`)) ENGINE = InnoDB;") or die($con->error);;
        
        

        if($array == 'novo'){
            
            //Usar "new DateTime" para formatar a data caso necessário
            $this->dataEmissao = date('Y-m-d H:i:s');
            $this->gerarAssinatura($matricula);
            $this->periodo = $periodo;
            $this->tipo = $tipo;
            $this->matricula = $matricula;

        }
        else{

            $con = BD::conectarOO();

            $assinatura = $array['assinatura'];

            //ini_set('display_errors', 1);

            $query = "SELECT p.nome, d.validacao, d.periodo, d.matricula, d.dataEmisao, p.sexo, m.turnoIngresso, d.tipo
                        FROM Declaracao d, Pessoa p, MatriculaAluno m 
                        WHERE m.matriculaAluno = d.matricula
                        AND m.idPessoa = p.idPessoa ";
            $query .= $array["query"];

            $result = $con->query($query);

            $row = $result->fetch_assoc();

            //Usar "new DateTime" para formatar a data caso necessário
            $this->dataEmissao = $row['dataEmisao'];
            $this->periodo = $row['periodo'];
            $this->tipo = $row['tipo'];
            $this->matricula = $row['matricula'];
            $this->assinatura = $row['validacao'];
            $this->assinaturaExib = Declaracao::gerarAssinaturaExib($this->assinatura);
            

            if ($row == NULL) {

                $_SESSION["erro"] = 'Assinatura Não Encontrada';
                throw new Exception("Assinatura Não Encontrada");

            }
         
            $this->getDadosDeclaracao($this->tipo);

        }



        


    }

    public function getHoraMin(){

        $data = new DateTime( $this->dataEmissao );

        return $data->format('H:i:s');
    }

    public function getAssinaturaExib(){
        return $this->assinaturaExib;
    }

    public function getAssinatura(){
        return $this->assinatura;
    }

    public function getDados(){
        return $this->dados;
    }

    public function getTipo(){
        return $this->tipo;
    }
    public function getIdPessoa(){
        return $this->idPessoa;
    }


    

    public function salvarDeclaracao()
    {

        $query  = "INSERT INTO Declaracao (validacao, tipo, matricula, periodo, dataEmisao) 
                            VALUES(?, ?, ?, ?, ?)";

        $con = BD::conectarOO();
       
        $stmt = $con->prepare($query);
        $stmt->bind_param("sisis", $vali, $tipo, $mat, $periodo, $data );

        $vali = $this->assinatura;
        $tipo = $this->tipo;
        $mat = $this->matricula;
        $periodo = $this->periodo;
        $data = $this->dataEmissao;

        if(!$stmt->execute()){
            //echo "Statement insert error: {$stmt->error}";
            echo "Ocorreu um erro no registro da sua carteirinha no banco de dados, Talvez a Validação online não seja possível";
        }

        $stmt->close();

    }

    private function gerarAssinatura($matricula){
        
        //Gerar assinatura
        $this->assinatura = strtoupper(substr(md5(uniqid( $matricula.date('H:i:s') , true )), 0, 32));

        $assinaturaTMP = str_split($this->assinatura, 4);
        $assinaturaExibicao = '';

        foreach ($assinaturaTMP as $key => $value) {
            
            $assinaturaExibicao .= $value;

            if ($key < 7) {
                $assinaturaExibicao .=":";
            }
        }

        $this->assinaturaExib = $assinaturaExibicao;

    }

    public static function gerarAssinaturaExib($assinatura){

        $assinaturaTMP = str_split($assinatura, 4);

        $assinaturaExibicao = '';

        foreach ($assinaturaTMP as $key => $value) {
            
            $assinaturaExibicao .= $value;

            if ($key < 7) {
                $assinaturaExibicao .=":";
            }
        }

       return $assinaturaExibicao;
        
    }

    /**
     * Retorna um objeto de Declaracao dado sua assinatura.
     * @param type $assinatura
     * @return Declaracao 
     */
    public static function getDeclaracaoPorAssinatura($assinatura){

        $assinatura = addslashes(strtoupper(implode('', $assinatura)));

        

        $declaracao = new Declaracao('','','', ["assinatura" => $assinatura, "query" => "AND d.validacao = '$assinatura'"]);
        return $declaracao;
    
    }

    /**
     * Retorna um objeto de Declaracao dado a matricula de MatriculaAluno.
     * Só server para retornar declarações únicas ex. carteirinha, pois é a mesma sempre e não é gerado PDF
     * @param type MatriculaAluno.matricula > $matricula
     * @return Declaracao
     */
    public static function obterDeclaracaoPorMatricula($matricula){

        $declaracao = new Declaracao('','','',  ["query" => "AND d.tipo = 2 AND d.matricula = '$matricula'"]);
        return $declaracao;
    
    }

    /**
     * Pegar os dados das declarações e passar para a variável de instância: dados 
     * @param type none
     * @return none
     */
    public function getDadosDeclaracao($tipo){

        $con = BD::conectarOO();

        $query = "SELECT p.nome, d.validacao, d.periodo, d.matricula, d.dataEmisao, p.sexo, m.turnoIngresso, a.cpf, l.foto, p.idPessoa
                    FROM Declaracao d, Pessoa p, MatriculaAluno m, Aluno a, Login l
                    WHERE m.matriculaAluno = d.matricula
                    AND m.idPessoa = p.idPessoa
                    AND a.idPessoa = p.idPessoa
                    AND l.idPessoa = p.idPessoa
                    AND d.validacao = '$this->assinatura'";

        

        $result = $con->query($query);

        $row = $result->fetch_assoc();
        
        $this->idPessoa = $row['idPessoa'];

        if ($row == NULL) {

            $_SESSION["erro"] = 'Assinatura Não Encontrada Para os Dados';
            throw new Exception("Assinatura Não Encontrada Para os Dados");

        }else{

            $this->dados = $row;

        }
        
    }

    /**
     * Verificar se já existe registro de carteirinha (tipo = 2) na tabela da declaração e retorna um bool
     * @param type MatriculaAluno.matricula > $matricula
     * @return bool
     */
    public static function verificarRegistroCarteirinha($matricula)
    {
        $query = "SELECT id
                    FROM Declaracao
                    WHERE matricula = '$matricula'
                    AND tipo = 2";
        
        $con = BD::conectarOO();

        $result = $con->query($query);

        $row = $result->fetch_assoc();

        if ($row == NULL) {

           return false;

        }else{
            return true;
        }
    }

}
?>
