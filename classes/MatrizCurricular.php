<?php
require_once "$BASE_DIR/classes/ComponenteCurricular.php";
require_once "$BASE_DIR/classes/Curso.php";

class MatrizCurricular {
    private $siglaCurso;
    private $idMatriz;
    private $dataInicioVigencia;
    private $curso; // Objeto da classe Curso associado

    /*
     * Gera um array associativo que relaciona o número do período
     * com a carga horária dele.
     * periodo => totalCH
     * Ex:
     * [1] => 280
     * [2] => 320
     * ...
     */
    public function obterCargaPorPeriodo() {
        $con = BD::conectar();
        $query=sprintf("select periodo,sum(cargaHoraria) as totalCH
            from ComponenteCurricular
            where siglaCurso='%s' and
                idMatriz=%d
            group by periodo",
                mysqli_real_escape_string($con, $this->siglaCurso),
                $this->idMatriz);
        $result=BD::mysqli_query($query,$con);
        $col = array();
        while( $reg = mysqli_fetch_array($result) ) {
            $col[$reg["periodo"]] = $reg["totalCH"];
        }
        mysqli_close($con);
        return $col;
    }

    /*
    * Construtor da Classe
    */
    function __construct($siglaCurso,$idMatriz) {
        $this->siglaCurso = $siglaCurso;
        $this->idMatriz = $idMatriz;
    }

    /*
     * Função cuja finalidade é obter uma Matriz Curricular
     * @param: siglaCurso (Sigla de um curso válido)
     * @param: idMatriz (Id de uma Matriz válida)
     * @result: matrizCurricular (Objeto Matriz Curricular)
    */
    public static function obterMatrizCurricular($siglaCurso,$idMatriz) {
        $con = BD::conectar();

        $query=sprintf("select siglaCurso, idMatriz, dataInicioVigencia " .
                "from MatrizCurricular " .
                "where siglaCurso='%s' and idMatriz = %d ",
                mysqli_real_escape_string($con, $siglaCurso),$idMatriz);
        $result=BD::mysqli_query($query,$con);

        $matrizCurricular = null;

        if(mysqli_num_rows($result) > 0){ //Valida se alguma Matriz foi encontrada no sistema.
            $resMatrizCurricular = mysqli_fetch_array($result); //Obtem o resultado do banco
            $matrizCurricular = new MatrizCurricular($resMatrizCurricular['siglaCurso'],$resMatrizCurricular['idMatriz']); //Cria um objeto de Matriz
            $matrizCurricular->setDataInicioVigencia($resMatrizCurricular['dataInicioVigencia']);
        }
        mysqli_close($con);
        return $matrizCurricular;
    }


    /*
     * Função cuja finalidade é obter uma lista de Matrizes Curriculares de um curso
     * @param: siglaCurso (Sigla de um curso válido)
     * @author: Marcelo Atie
     * @result: array de MatrizCurricular (Objeto Matriz Curricular)
     *
     * Casos de uso:
     *      UC01.03.02 - Criar Turma
    */
    public static function obterListaMatrizCurricularPorSiglaCurso($siglaCurso, $ordem = 'DESC') {
        $con = BD::conectar();

        $query=sprintf("select siglaCurso, idMatriz, dataInicioVigencia " .
                "from MatrizCurricular " .
                "where siglaCurso = '%s' ".
                "order by dataInicioVigencia %s",
                mysqli_real_escape_string($con, $siglaCurso),
                mysqli_real_escape_string($con, $ordem));
        $result=BD::mysqli_query($query,$con);

        $arrayMatrizCurricular = Array();

        while($resMatrizCurricular = mysqli_fetch_array($result)){
            $matrizCurricular = new MatrizCurricular($resMatrizCurricular['siglaCurso'],$resMatrizCurricular['idMatriz']);
            $matrizCurricular->setDataInicioVigencia($resMatrizCurricular['dataInicioVigencia']);
            array_push($arrayMatrizCurricular, $matrizCurricular);
        }
        mysqli_close($con);
        return $arrayMatrizCurricular;
    }

    /**
     * Retorna um array de componentes curriculares dessa matriz
     * @result array de ComponenteCurricular
     */
    public function obterComponentesCurriculares() {
        $con = BD::conectar();
        $query=sprintf("select siglaCurso, idMatriz, siglaDisciplina ".
                "from ComponenteCurricular ".
                "where siglaCurso='%s' and idMatriz = %d",
                $this->getSiglaCurso(),
                $this->getIdMatriz());
        $col = array();
        $result = BD::mysqli_query($query,$con);
        while( $resCC = mysqli_fetch_array($result) ) {
            $siglaCurso = $resCC["siglaCurso"];
            $idMatriz = $resCC["idMatriz"];
            $siglaDisciplina = $resCC["siglaDisciplina"];
            $ce = ComponenteCurricular::obterComponenteCurricular($siglaCurso,
                $idMatriz, $siglaDisciplina);
            array_push($col, $ce);
        }
        mysqli_close($con);
        return $col;
    }

    /*
     * Funcao para carregar os atributos de uma Matriz Curricular
     ** Casos de Uso: UC02.06.00
     */
     public function carregarMatrizCurricular() {
        $con = BD::conectar();
        $query=sprintf("select siglaCurso, idMatriz, dataInicioVigencia ".
                "from MatrizCurricular ".
                "where idMatriz = %s ",$this->idMatriz);
        $result=BD::mysqli_query($query,$con);

           if(mysqli_num_rows($result) > 0){ //Valida se alguma Matriz foi encontrada no sistema.
              $resMatrizCurricular = mysqli_fetch_array($result); //Obtem o resultado do banco
              $this->setSiglaCurso($resMatrizCurricular['siglaCurso']);
              $this->setIdMatriz($resMatrizCurricular['idMatriz']);
              $this->setDataInicioVigencia($resMatrizCurricular['dataInicioVigencia']);
        }
        mysqli_close($con);
    }


    /*
 * Início do Bloco de comandos gets
    */

    public function getSiglaCurso() {
        return $this->siglaCurso;
    }

    public function getIdMatriz() {
        return $this->idMatriz;
    }
    public function getDataInicioVigencia() {
        return $this->dataInicioVigencia;
    }


    /*
 * Final do Bloco Gets
    */

    /*
 * Início do Bloco Sets
    */
    public function setSiglaCurso($siglaCurso) {
        $this->siglaCurso=$siglaCurso;
    }

    public function setidMatriz($idMatriz) {
        $this->idMatriz=$idMatriz;
    }

    public function setDataInicioVigencia($dataInicioVigencia) {
        $this->dataInicioVigencia=$dataInicioVigencia;
    }

    /*
 * Final do Bloco Sets
    */

    /**
     * Retorna um inteiro positivo indicando a quantidade de período
     * letivos que forma a matriz curricular
     */
    public function obterQuantidadePeriodos() {
        $con = BD::conectar();
        $query=sprintf("select max(cc.periodo) as qtdePeriodos from ComponenteCurricular cc " .
            " where cc.siglaCurso = '%s' and cc.idMatriz=%d",
            $this->getSiglaCurso(),
            $this->getIdMatriz());
        $result=BD::mysqli_query($query,$con);
        if(mysqli_num_rows($result) == 1){ //Valida se alguma Matriz foi encontrada no sistema.
            $resQtdePeriodos = mysqli_fetch_array($result); //Obtem o resultado do banco
            mysqli_close($con);
            return $resQtdePeriodos['qtdePeriodos'];
        } else {
            mysqli_close($con);
            trigger_error(sprintf("Não foi possível determinar a quantidade de períodos " .
                "do curso %s da matriz %d.",$this->getSiglaCurso(),
                $this->getIdMatriz()),E_USER_ERROR);
        }
        mysqli_close($con);
    }

    /***
     * Retorna o objeto de Curso associado a esta
     * MatrizCurricular, em comportamento Lazy.
     * @result objeto de Curso
     * @author Marcio Belo
     **/
    public function getCurso() {
        if($this->curso==null) {
            $this->curso = Curso::obterCurso($this->siglaCurso);
        }
        return $this->curso;
    }

    /*
     * Função cuja finalidade é obter a Matriz Curricular atual de um curso específico
     * @param: siglaCurso (Sigla de um curso válido)
     * @result: matrizCurricular (Objeto Matriz Curricular)
     */
    public static function obterMatrizCurricularAtual($siglaCurso) {
        $con = BD::conectar();

        $query = sprintf("select MC1.`siglaCurso`, MC1.`idMatriz`, MC1.`dataInicioVigencia` " .
                        "from MatrizCurricular MC1 " .
                        "where MC1.`siglaCurso` = '%s' " .
                        "and MC1.`dataInicioVigencia` = ( " .
                        "    SELECT max(MC2.`dataInicioVigencia`) " .
                        "    FROM MatrizCurricular MC2 " .
                        "    WHERE MC2.`siglaCurso` = MC1.`siglaCurso` " .
                        "    AND MC2.`dataInicioVigencia` < now() " .
                        ") ",
                        mysqli_real_escape_string($con, $siglaCurso)
                );
        $result = BD::mysqli_query($query, $con);

        $matrizCurricular = null;

        if(mysqli_num_rows($result) > 0){ //Valida se alguma Matriz foi encontrada no sistema.
            $resMatrizCurricular = mysqli_fetch_array($result); //Obtem o resultado do banco
            $matrizCurricular = new MatrizCurricular($resMatrizCurricular['siglaCurso'],$resMatrizCurricular['idMatriz']); //Cria um objeto de Matriz
            $matrizCurricular->setDataInicioVigencia($resMatrizCurricular['dataInicioVigencia']);
        }
        mysqli_close($con);
        return $matrizCurricular;
    }
    
    public function criar() {
        $con = BD::conectar();
        $query = sprintf("INSERT INTO MatrizCurricular (siglaCurso, idMatriz, dataInicioVigencia) VALUES ('%s', %d, '%s')",
                           $this->getSiglaCurso(), $this->getIdMatriz(), mysqli_escape_string($con, $this->getDataInicioVigencia()));
        
        $result = BD::mysqli_query($query,$con);
        if (!$result) {
            mysqli_close($con);
            throw new Exception("Erro ao criar Matriz Curricular.");
        }
        mysqli_close($con);
    }
}
