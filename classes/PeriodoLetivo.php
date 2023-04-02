<?php
include_once "$BASE_DIR/classes/Curso.php";
require_once "$BASE_DIR/classes/Util.php";

class PeriodoLetivo 
{
    private $idPeriodoLetivo;       
    private $siglaPeriodoLetivo;
    private $dataInicio;
    private $dataFim;
    private $siglaCurso;
    private $rodouBloqueioAutomatico;
    
    public function __construct( $idPeriodoLetivo = null, 
            $siglaPeriodoLetivo = null,
            $dataInicio = null,
            $dataFim = null,
            $siglaCurso = null,
            $rodouBloqueioAutomatico = null)
    {
        $this->setIdPeriodoLetivo( $idPeriodoLetivo);
        $this->setSiglaPeriodoLetivo($siglaPeriodoLetivo);
        $this->setDataInicio( $dataInicio);
        $this->setDataFim( $dataFim);
        $this->setSiglaCurso( $siglaCurso);
        $this->setRodouBloqueioAutomatico($rodouBloqueioAutomatico);
    }

    public function setIdPeriodoLetivo($idPeriodoLetivo) {
        $this->idPeriodoLetivo = $idPeriodoLetivo;
    }
    public function getIdPeriodoLetivo() {
        return $this->idPeriodoLetivo;
    }
    
    public function setSiglaPeriodoLetivo($siglaPeriodoLetivo){
    	$this->siglaPeriodoLetivo = $siglaPeriodoLetivo;
    }    
    public function getSiglaPeriodoLetivo() {
        return $this->siglaPeriodoLetivo;
    }
    
    public function setDataInicio($dataInicio){
    	$this->dataInicio = $dataInicio;
    }    
    public function getDataInicio() {
        return $this->dataInicio;
    }
    
    public function setDataFim($dataFim){
    	$this->dataFim = $dataFim;
    }    
    public function getDataFim() {
        return $this->dataFim;
    }
    
    public function setSiglaCurso($siglaCurso){
    	$this->siglaCurso = $siglaCurso;
    }    
    public function getSiglaCurso() {
        return $this->siglaCurso;
    }
    
    function obterDataInicioAulas() {
        return $this->getDataInicio();
    }
    
    function obterDataFimAulas() {
        return $this->getDataFim();
    }

    public function getRodouBloqueioAutomatico() {
        return $this->rodouBloqueioAutomatico;
    }

    public function setRodouBloqueioAutomatico($rodouBloqueioAutomatico) {
        $this->rodouBloqueioAutomatico = ($rodouBloqueioAutomatico === "SIM" ? true : false);
    }
        
    /**
     * Retorna a data do fim do período de trancamento
     * de matrícula (FIM_TRANC_MATRICULA) do período letivo.
     * Se não houver, retorna null;
     * @return Data do fim da trancamento de matr., ou null, se não existir
     */
    public function obterDataFimTrancMatricula() {
        $con=BD::conectar();
        $query=sprintf("select data from EventoPeriodoLetivo
            where idPeriodoLetivo=%d and
            tipoEvento='FIM_TRANC_MATRICULA'",
                $this->getIdPeriodoLetivo());
        $result=BD::mysqli_query($query,$con);
        if(mysqli_num_rows($result)==0) {
            return null;
        } else {
            $linha = mysqli_fetch_array($result);
            return $linha["data"];
        }
    }

    /**
     * Retorna a data do fim do período de inscrição
     * em turmas (FIM_SOLIC_INSCR_TURMA) do período letivo.
     * Se não houver, retorna null;
     * @return Data do fim da solic.inscrição, ou null, se não existir
     */
    public function obterDataFimSolicInscrTurma() {
        $con=BD::conectar();
        $query=sprintf("select data from EventoPeriodoLetivo
            where idPeriodoLetivo=%d and
            tipoEvento='FIM_SOLIC_INSCR_TURMA'",
                $this->getIdPeriodoLetivo());
        $result=BD::mysqli_query($query,$con);
        if(mysqli_num_rows($result)==0) {
            return null;
        } else {
            $linha = mysqli_fetch_array($result);
            return $linha["data"];
        }
    }

    /**
    * Inserir dados na tabela periodoletivo
    *         
    */
    function inserePeriodo() {
            $this->setIdPeriodoLetivo($this->getProximoId());
                        
            $queryPeriodo =  "INSERT INTO PeriodoLetivo   
                (
                idPeriodoLetivo,
                siglaPeriodoLetivo,
                dataInicio,
                dataFim,
                siglaCurso
                )   
                VALUES  
                (
                '".$this->getIdPeriodoLetivo()."',
                '".$this->getSiglaPeriodoLetivo()."',
                '".Util::dataBrParaSQL($this->getDataInicio())."',
                '".Util::dataBrParaSQL($this->getDatafim())."',
                '".$this->getSiglaCurso()."'
                )";
            
            $msg_erro = "Erro ao Inserir Informações!";
            $msg_erro_duplicado ="Sigla do periodo letivo já existente para este curso!" ;

            $classeCurso = Curso::obterCurso($this->getSiglaCurso());
            
            $descricao="Inserido o período letivo ".$this->getSiglaPeriodoLetivo()." (".
                        $this->getDataInicio()." - ".
                        $this->getDatafim().") ".
                        "do curso ".$this->getSiglaCurso()." (".$classeCurso->getNomeCurso()."). ";
            $con = BD::conectar();

            BD::mysqli_query($queryPeriodo,$con);
            
            if(mysqli_errno($con)==1062){// numero do erro no mysql que identifica a entrada de chave duplicada
            	return $msg_erro_duplicado;
            }elseif(mysqli_errno($con)!=0){
            	return $msg_erro;
            }else{

                $login = $_SESSION["login"];
                global $MANTER_PERIODO_LETIVO_INCLUIR;

                $login->incluirLog($MANTER_PERIODO_LETIVO_INCLUIR,$descricao,$con);

                return "";    
            }
    }
            
    /**
    * Atualizar dados na tabela de periodoletivo
    *         
    */
    public function atualizaPeriodo() {

            $queryPeriodo =  "UPDATE PeriodoLetivo SET  
                siglaPeriodoLetivo = '".$this->getSiglaPeriodoLetivo()."',
                dataInicio = '".$this->getDataInicio()."',
                dataFim = '".$this->getDataFim()."'
                WHERE idPeriodoLetivo = '".$this->getIdPeriodoLetivo()."'";
            
            $msg_erro = "Erro ao Atualizar Informações!";
            $msg_erro_duplicado ="Sigla do periodo letivo já existente para este curso!" ;
            
             //monta a descricao para o log de autoria
                    
            $periodoLetivoAntes = PeriodoLetivo::obterPeriodoLetivo( $this->getIdPeriodoLetivo() );
            $classeCurso = Curso::obterCurso( $periodoLetivoAntes->getSiglaCurso() );

            $descricao="Alterado o período letivo de ".$periodoLetivoAntes->getSiglaPeriodoLetivo()." (".
                        $periodoLetivoAntes->getDataInicio()." - ".
                        $periodoLetivoAntes->getDatafim().") ".
                        "para ".$this->getSiglaPeriodoLetivo()." (".
                        Util::dataSQLParaBr($this->getDataInicio())." - ".
                        Util::dataSQLParaBr($this->getDatafim()).") ".
                        "do curso ".$classeCurso->getSiglaCurso()." (".$classeCurso->getNomeCurso()."). ";
            $con = BD::conectar();

            BD::mysqli_query($queryPeriodo,$con);
            
            if(mysqli_errno($con)==1062){// numero do erro no mysql que identifica a entrada de chave duplicada
            	return $msg_erro_duplicado;
            }elseif(mysqli_errno($con)!=0){
                return $msg_erro;
            }else {
                $login = $_SESSION["login"];
                global $ALTERAR_PERIODO_LETIVO;

                $login->incluirLog($ALTERAR_PERIODO_LETIVO,$descricao,$con);
                return "";
            }
    }
    
    /**
    * Exclui o periodo letivo 
    */
    public function excluir()  {
        $queryPeriodo = "DELETE FROM PeriodoLetivo where idPeriodoLetivo = $this->idPeriodoLetivo";
        //monta a descricao para o log de autoria
        $curso = Curso::obterCurso($this->getSiglaCurso());
        $descricao = "Excluido o período letivo " . $this->getSiglaPeriodoLetivo() ." (" .
                    $this->getDataInicio() . " - " .
                    $this->getDatafim() . ") " .
                    "do curso " . $this->getSiglaCurso() . " (" . $curso->getNomeCurso() . "). ";

        $con = BD::conectar();

        $sqlPeriodo = BD::mysqli_query($queryPeriodo,$con);
        $msgErro = "Erro ao excluir Período Letivo!";
        if(!$sqlPeriodo){
            return $msgErro;
        } else {
            $login = $_SESSION["login"];
            global $EXCLUIR_PERIODO_LETIVO;
            $login->incluirLog($EXCLUIR_PERIODO_LETIVO,$descricao,$con);
            return "Período Letivo excluído com sucesso!";
        }
    }

    // METODO NECESSARIO PARA VERIFICAR SE A DATA INICIAL É MENOR QUE A ÚLTIMA CADASTRADA
    /**
    * retorna verdadeiro se é menor ou igual e falso se é maior
    *         
    * @param $dataInicial : data inicial digitada no novo periodo a ser inserido
    */	
    function isDataMenor($dataInicial,$id) {
			$encontrou = false;
			$dataInicial = Util::dataBrParaSQL($dataInicial);
			$con = BD::conectar();
            $dataAnterior = "select CASE
			                           WHEN dataInicio >= '$dataInicial' THEN 'invalido'
			                        END AS data
                             from PeriodoLetivo where idPeriodoLetivo = ".$id;
            $sqlDataAnterior = BD::mysqli_query($dataAnterior,$con);
         
            if($sqlDataAnterior){
            	
            	if(BD::mysqli_result($sqlDataAnterior,0,'data')=='invalido'){
            	  $encontrou = true;
            	}
            }
            return $encontrou;
    }
		
    public function getIdPeriodoAnterior($id) {
		    $idAnterior = null;
			$con = BD::conectar();
			
            $query = "select idPeriodoLetivo from PeriodoLetivo pl1
                       where pl1.dataInicio =
                        (select max(dataInicio) from
                            PeriodoLetivo pl
                        where pl.dataInicio < (select dataInicio from PeriodoLetivo where idPeriodoLetivo = ".$id."))";
            $sql = BD::mysqli_query($query,$con);
            if(mysqli_num_rows($sql) > 0){
            	$idAnterior = (BD::mysqli_result($sql,0,'idPeriodoLetivo'));
            }
            return $idAnterior;
    }	
        
    /**
    * retorna uma lista de objetos dos 10(dez) ultimos periodos letivos da tabela periodoletivo
    *
    * @param $inicio: inicio da busca na querie
    * @param $fim: fim da busca na querie
    * @param $siglaCurso: qual curso exibirá os registros da querie
    */
    function listaPeriodos($inicio,$fim,$siglaCurso) {
        $lista = null;
        $collection = null;
        $con = BD::conectar();

        // lista os peridos
        $lista = BD::mysqli_query("SELECT date_format(dataInicio,'%d/%m/%Y') as dataInicio, date_format(dataFim,'%d/%m/%Y') as dataFim,idPeriodoLetivo,siglaCurso,siglaPeriodoLetivo FROM PeriodoLetivo where siglaCurso = '$siglaCurso'  order by siglaPeriodoLetivo desc limit $inicio,$fim");
        if(mysqli_num_rows($lista) > 0) 
        {
            $collection = array();
            while( $objeto = mysqli_fetch_array($lista) )
            {
                    $periodo = new PeriodoLetivo( $objeto["idPeriodoLetivo"],
                            $objeto["siglaPeriodoLetivo"], $objeto["dataInicio"],
                            $objeto["dataFim"], $objeto["siglaCurso"], 
                            $objeto["rodouBloqueioAutomatico"]);
                    $collection[] = $periodo;
            }
        }
        return $collection;
    }
	    

    public function getMaxId($siglaCurso) 
    {
        $con = BD::conectar();
        $maxId = null;
        $query = "select ifNULL(max(idPeriodoLetivo),0) as maxId from PeriodoLetivo where siglaCurso = %s".mysqli_real_escape_string($con, $siglaCurso);
        $sql = BD::mysqli_query($query,$con);
        if(mysqli_num_rows($sql) > 0) 
        {
           $maxId = (BD::mysqli_result($sql,0,'maxId'));
        }
        return $maxId;
    }   
	 
    // METODO QUE BUSCA O PROXIMO ID DA TABELA DE PERIODOLETIVO
    /**
    * retorna o id seguinte para ser inserido na tabela de periodo letivo
    *         
    */
     public function getProximoId(){
            $con = BD::conectar();
            $queryProximoId = "select ifNULL(max(idPeriodoLetivo),0)+1 as proximoId from PeriodoLetivo";
            $resultado = BD::mysqli_query($queryProximoId,$con);
            $proximoId = BD::mysqli_result($resultado,0,'proximoId');
            return $proximoId; 
     }

    /* Funcao que obtem o atual periodo letivo
     *
     * Casos de Uso: UC02.06.00; UC02.01
     * @param: sigla do curso;
     * @return: periodo Letivo -> objeto
     * @throws Exception
     */
    public static function obterPeriodoLetivoAtual($siglaCurso) {
        $con = BD::conectar();
        $query = sprintf("SELECT * FROM PeriodoLetivo p where dataFim >= CURDATE() and
            dataInicio <= CURDATE() and siglaCurso='%s'",
                mysqli_real_escape_string($con, $siglaCurso));
        $sqlPeriodo = BD::mysqli_query($query, $con);
        $qtdResultado = mysqli_num_rows($sqlPeriodo);

        if ($qtdResultado == 1) {
            $itensPeriodo = BD::mysqli_result($sqlPeriodo, 0, 'idPeriodoLetivo');
            return PeriodoLetivo::obterPeriodoLetivo($itensPeriodo);
        } elseif($qtdResultado > 1) {
            throw new Exception(sprintf("Erro de inconsistência: existem mais de um período letivo para a data atual do curso %s.",$siglaCurso));
        } else {
            throw new Exception(sprintf("Não existem período letivos disponíveis na data atual do curso %s.",$siglaCurso));
        }
    }

    /**
     * Retorna o período letivo vigente mais antigo cadastrado para um determinado curso
     * @param String $siglaCurso
     * @return PeriodoLetivo
     */
    public static function obterPeriodoLetivoVigenteMaisAntigo($siglaCurso) {
        $con = BD::conectar();
        $query = sprintf("select * from PeriodoLetivo pl where 
            dataFim >= CURDATE() 
            and siglaCurso='%s' 
            order by dataInicio",
        mysqli_escape_string($con, $siglaCurso),
        mysqli_escape_string($con, $siglaCurso));
        $result = BD::mysqli_query($query, $con);

        $qtdResultado = mysqli_num_rows($result);
        if ($qtdResultado >= 1) 
        {
            $linha = mysqli_fetch_array($result);
            $pl = new PeriodoLetivo( $linha["idPeriodoLetivo"],
                    $linha["siglaPeriodoLetivo"], 
                    $linha["dataInicio"],
                    $objeto["dataFim"],
                    $objeto["siglaCurso"],
                    $objeto["rodouBloqueioAutomatico"] );
            return $pl;
        } else {
            throw new RuntimeException("Erro ao identificar o período letivo mais antigo vigente do curso.");
        }
    }
     
    /***
     * Retorna uma entrada existente: periodoletivo
     * @param idPeriodoLetivo
     * @result new PeriodoLetivo
     **/
    /**
     * Retorna um instância de período letivo pelo id
     * @param integer $idPeriodoLetivo
     * @return PeriodoLetivo
     */
    public static function obterPeriodoLetivo( $idPeriodoLetivo) {
        // retorna o dado
        $con = BD::conectar();

        // retorna o valor no DB
        $query = sprintf("SELECT * FROM PeriodoLetivo WHERE idPeriodoLetivo = %d",
            $idPeriodoLetivo );
        $rs = BD::mysqli_query($query,$con);
        if( mysqli_num_rows($rs) != 1) return null;
        $rsPL = mysqli_fetch_array($rs);
        $pl = new PeriodoLetivo( $rsPL["idPeriodoLetivo"], 
                $rsPL["siglaPeriodoLetivo"],
                $rsPL["dataInicio"],
                $rsPL["dataFim"],
                $rsPL["siglaCurso"],
                $rsPL["rodouBloqueioAutomatico"] );
        return $pl;
    }

    public static function obterPeriodosLetivoPorSiglaCurso($siglaCurso) {
        $lista = null;
        $collection = null;
        $con = BD::conectar();
        $query = sprintf("SELECT * FROM PeriodoLetivo WHERE siglaCurso='%s'
            order by siglaPeriodoLetivo DESC",
        mysqli_real_escape_string($con, $siglaCurso));
        $lista = BD::mysqli_query($query);
        $collection = array();
        if (mysqli_num_rows($lista) > 0) 
        {
            while ($objeto = mysqli_fetch_array($lista) ) 
            {
                $periodoLetivo = new PeriodoLetivo( $objeto['idPeriodoLetivo'],
                        $objeto["siglaPeriodoLetivo"],
                        $objeto["dataInicio"],
                        $objeto["dataFim"],
                        $objeto["siglaCurso"],
                        $objeto["rodouBloqueioAutomatico"] );
                array_push( $collection, $periodoLetivo);
            }
        }
        return $collection;
    }
    
    /**
     * Indica se está fora do período letivo
     * @param string $data data no formato AAAA-MM-DD
     * @return boolean true, se estiver fora do período letivo
     */
    public function isDataForaPeriodo( $data ) {
        if( $data < $this->dataInicio || $data > $this->dataFim ) {
            return true;
        } else {
            return false;
        }
    }

    public function registrarRodadaBloqueioAutomatico() 
    {
        $con = BD::conectar();
        $update = sprintf("update PeriodoLetivo set rodouBloqueioAutomatico='SIM' "
                . "where idPeriodoLetivo = %d",
            mysqli_real_escape_string($con,  $this->idPeriodoLetivo) );
        $result = BD::mysqli_query($update, $con);
        if($result)
        {
            $this->rodouBloqueioAutomatico = true;
        }
        else 
        {
            throw new Exception("Erro ao registrar rodada bloqueio automatico");
        }
    }
}