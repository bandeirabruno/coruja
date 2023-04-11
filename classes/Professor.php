<?php
require_once "$BASE_DIR/classes/Pessoa.php";
require_once "$BASE_DIR/classes/MatriculaProfessor.php";

class Professor extends Pessoa 
{
    private $matriculasProfessor;
    private $titulacaoAcademica;
    private $cvLattes;
    private $nomeGuerra;
    private $corFundo;

    public function getTitulacaoAcademica( ) {
        return $this->titulacaoAcademica;
    }


    public function getCvLattes( ) {
        return $this->cvLattes;
    }

   public function getNomeGuerra( ) {
        return $this->nomeGuerra;
    }

    public function getCorFundo( ) {
        return $this->corFundo;
    }

    function setTitulacaoAcademica( $titulacaoAcademica ) {
        $this->titulacaoAcademica = $titulacaoAcademica;
    }

    function setCvLattes( $cvLattes ) {
        $this->cvLattes = $cvLattes;
    }

    function setMatriculasProfessor($matriculasProfessor) {
        $this->matriculasProfessor = $matriculasProfessor;
    }

    public function getMatriculasProfessor() {
        return $this->matriculasProfessor;
    }

    function setNomeGuerra( $nomeGuerra ) {
        $this->nomeGuerra = $nomeGuerra;
    }

    function setCorFundo( $corFundo ) {
        $this->corFundo = $corFundo;
    }

    /**
     * Obt�m um professor dada a matr�cula
     * @param String $matricula
     * @return Professor refer�ncia ao objeto de professor, ou null, caso n�o
     * exista
     */
    public static function obterProfessorPorMatricula($matricula) {
         $con = BD::conectar();
         $query = sprintf("SELECT * FROM Pessoa P inner join Professor PR
             on P.idPessoa=PR.idPessoa
             inner join MatriculaProfessor MP
              on MP.idPessoa = PR.idPessoa
             WHERE LPAD(MP.matriculaProfessor,15,'0') = LPAD('%s',15,'0')",
             mysqli_real_escape_string($con, $matricula));
         $result=BD::mysqli_query($query, $con);
         $professor = null;
         while($reg=mysqli_fetch_array($result)) {
            $professor = new Professor();
            $professor->setTitulacaoAcademica($reg['titulacaoAcademica']);
            $professor->setCvLattes($reg['cvLattes']);
            $professor->setNomeGuerra($reg['nomeGuerra']);
            $professor->setCorFundo($reg['corFundo']);
            $professor->carregaDadosPessoa($reg['idPessoa']);
            $professor->setMatriculasProfessor(MatriculaProfessor::obterMatriculasPorIdPessoa($reg['idPessoa']));
         }
         return $professor;
    }

    /**
     * Retorna os professores vigentes, ou seja, que tenham uma ou
     * mais matr�culas vigentes.
     * @return array Cole��o de objetos de Professor
     */
    public static function obterProfessoresVigentes() {
        $con=BD::conectar();
        $query="select distinct * from Professor pr
            inner join Pessoa p on pr.idPessoa=p.idPessoa
            where exists (select * from MatriculaProfessor mp
                where mp.idPessoa=pr.idPessoa and
                (mp.dataEncerramento is NULL or
                mp.dataEncerramento > CURDATE()))
            order by p.nome";
        $result=BD::mysqli_query($query, $con);
        $col=array();
        while($reg=mysqli_fetch_array($result)) {
            // cria novo objeto
            $professor = new Professor();
            $professor->setIdPessoa($reg['idPessoa']);
            $professor->setTitulacaoAcademica($reg['titulacaoAcademica']);
            $professor->setCvLattes($reg['cvLattes']);
            $professor->carregaDadosPessoa($reg['idPessoa']);
            array_push($col, $professor);
        }
        //TODO carregar matriculasProfessor
        return $col;
    }

     /***
     * Retorna a lista de objetos de Pessoa por nome
     *
     * @result cole��o de objetos: Pessoa
     *
     * a raz�o de n�o utilizar o lista_pessoa() � que o mysqli_real_escape_string
     * gera problema com o % do parametro LIKE
     **/
    public static function obterProfessoresPorNome( $nome ) {

         $con = BD::conectar();
         $query = sprintf("SELECT * FROM Pessoa P inner join Professor PR
             on(P.idPessoa=PR.idPessoa)
             WHERE P.nome like '%s%%'
             order by P.nome",
             mysqli_real_escape_string($con, $nome));

         $result=BD::mysqli_query($query, $con);

         $col=array();
         while($reg=mysqli_fetch_array($result)) {
            $professor = new Professor();
            $professor->setTitulacaoAcademica($reg['titulacaoAcademica']);
            $professor->setCvLattes($reg['cvLattes']);
            $professor->carregaDadosPessoa($reg['idPessoa']);
            $professor->setMatriculasProfessor(MatriculaProfessor::obterMatriculasPorIdPessoa($reg['idPessoa']));
            array_push($col, $professor);

         }
         return $col;
    }

    public static function inserirProfessor( $idPessoa, $titulacaoAcademica, $cvLattes, $nomeGuerra, $corFundo,$con=null ) {

        if($con==null) $con = BD::conectar();
        $query=sprintf("INSERT INTO `Professor` (`idPessoa`, `titulacaoAcademica`, `cvLattes`, `nomeGuerra`, `corFundo`) " .
            "VALUES (%d,'%s','%s','%s','%s')",
            $idPessoa,
            mysqli_real_escape_string($con, $titulacaoAcademica),
            mysqli_real_escape_string($con, $cvLattes),
            mysqli_real_escape_string($con, $nomeGuerra),
            mysqli_real_escape_string($con, $corFundo));
        $result=BD::mysqli_query($query,$con);
        if(!$result) {
            throw new Exception("Erro ao inserir na tabela Professor.");
        }
    }

    public static function atualizarProfessor( $idPessoa, $titulacaoAcademica,$cvLattes,$nomeGuerra,$corFundo, $con=null ) {
        if($con==null) $con = BD::conectar();
        $query = sprintf("UPDATE Professor set
            titulacaoAcademica='%s',
            cvLattes='%s',
            nomeGuerra='%s',
            corFundo='%s'
            where idPessoa=%d",
            mysqli_real_escape_string($con, $titulacaoAcademica),
            mysqli_real_escape_string($con, $cvLattes),
            mysqli_real_escape_string($con, $nomeGuerra),
            mysqli_real_escape_string($con, $corFundo),
            mysqli_real_escape_string($con, $idPessoa) );
        $result=BD::mysqli_query($query,$con);
       
        if(!$result) {
            throw new Exception("Erro ao atualizar na tabela Professor.");
        }
    }


     protected function carregaDadosProfessor($idPessoa) {
        $con = BD::conectar();
        $query=sprintf("SELECT * FROM `Professor` ".
                "WHERE `idPessoa` = %d ",$idPessoa);
        $result=BD::mysqli_query($query,$con);
        while( $resProfessor = mysqli_fetch_array($result) ) {

            $this->setCvLattes($resProfessor['cvLattes']);
            $this->setTitulacaoAcademica($resProfessor['titulacaoAcademica']);
            $this->setNomeGuerra($resProfessor['nomeGuerra']);
            $this->setCorFundo($resProfessor['corFundo']);

        }
        $this->setMatriculasProfessor(MatriculaProfessor::obterMatriculasPorIdPessoa($idPessoa));
    }

    /**
     * Recupera um professor pelo id de pessoa.
     * @param int $idPessoa identificador da Pessoa-Professor
     * @return Professor professor recuperado
     */
    public static function getProfessorByIdPessoa($idPessoa) {

        $professor = new Professor();
        //� necess�rio manter esta ordem para carregar os dados
        $professor->carregaDadosProfessor($idPessoa);
        $professor->carregaDadosPessoa($idPessoa);

        return $professor;
    }

    /**
     * Gera uma vers�o leg�vel do estado desse objeto. Usado para inserir
     * log de auditoria.
     * @return String
     */
    public function toString() {
        $str = "";
        // Dados de Pessoa
        $str .= sprintf("Nome: %s<br/>",$this->getNome());
        $str .= sprintf("Sexo: %s<br/>",$this->getSexo());
        $str .= sprintf("Endere�o Logradouro: %s<br/>",$this->getEnderecoLogradouro());
        $str .= sprintf("Endere�o N�mero: %s<br/>",$this->getEnderecoNumero());
        $str .= sprintf("Endere�o Complemento: %s<br/>",$this->getEnderecoComplemento());
        $str .= sprintf("Endere�o Bairro: %s<br/>",$this->getEnderecoBairro());
        $str .= sprintf("Endere�o Munic�pio: %s<br/>",$this->getEnderecoMunicipio());
        $str .= sprintf("Endere�o Estado: %s<br/>",$this->getEnderecoEstado());
        $str .= sprintf("Endere�o CEP: %s<br/>",$this->getEnderecoCEP());
        $str .= sprintf("Data de Nascimento: %s<br/>",Util::dataSQLParaBr($this->getDataNascimento()));
        $str .= sprintf("Nacionalidade: %s<br/>",$this->getNacionalidade());
        $str .= sprintf("Naturalidade: %s<br/>",$this->getNaturalidade());
        $str .= sprintf("Tel.Residencial: %s<br/>",$this->getTelefoneResidencial());
        $str .= sprintf("Tel.Comercial: %s<br/>",$this->getTelefoneComercial());
        $str .= sprintf("Tel.Celular: %s<br/>",$this->getTelefoneCelular());
        $str .= sprintf("E-mail: %s<br/>",$this->getEmail());

        // Professor
        $str .= sprintf("Titula��o : %s<br/>",$this->getTitulacaoAcademica());
        $str .= sprintf("Lattes :%s<br/>",$this->getCvLattes());
        $str .= sprintf("Nome de Guerra : %s<br/>",$this->getNomeGuerra());
        $str .= sprintf("Cor : %s<br/>",$this->getCorFundo());
       
        return $str;
    }

}
?>