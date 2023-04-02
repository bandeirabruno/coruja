<?php

require_once "$BASE_DIR/classes/BD.php";
require_once "$BASE_DIR/classes/Curso.php";
require_once "$BASE_DIR/classes/TipoDocumento.php";

/**
 * Classe de Associação que indica o cumprimento, não cumprimento,
 * ou isenção de um documento exigido para uma matrícula em um curso
 * * @author mbelo
 */
class ExigeDocumento {
    private $curso;
    private $tipoDocumento;
    private $isento;
    private $dataEntrega;

    /**
     * Altera a situação de um documento exigido para o aluno
     * @param <type> $idPessoa
     * @param <type> $matriculaAluno
     * @param <type> $idTipoDocumento
     * @param <type> $situacaoDocEntregue
     * @param <type> $con 
     */
    public static function mudarSituacaoDocEntregue($matriculaAluno, $siglaCurso, $idTipoDocumento, $situacaoDocEntregue, $con) {
        if($con==null) $con=BD::conectar ();
        if($situacaoDocEntregue=='PENDENTE') {
            $query=sprintf("delete from ExigenciaDocumento where 
                matriculaAluno='%s' and siglaCurso='%s' and idTipoDocumento=%d",
            mysqli_escape_string($con, $matriculaAluno),
            mysqli_escape_string($con, $siglaCurso),
            mysqli_escape_string($con, $idTipoDocumento));
            $result = BD::mysqli_query($query,$con);
            if(!$result) throw new Exception ("Erro ao atualizar ExigenciaDocumento.");
            return;
        } else {
            $isento = ($situacaoDocEntregue=="ISENTO" ? "SIM" : "NÃO");
            $queryAtualiza=sprintf("update ExigenciaDocumento 
                set isento='%s',dataEntrega=CURDATE() 
               where matriculaAluno='%s' and siglaCurso='%s' and idTipoDocumento=%d",
                mysqli_escape_string($con, $isento),
                mysqli_escape_string($con, $matriculaAluno),
                mysqli_escape_string($con, $siglaCurso),
                mysqli_escape_string($con, $idTipoDocumento));
            $result = mysqli_query($con, $queryAtualiza);
            if(!$result) throw new Exception ("Erro ao atualizar ExigenciaDocumento.");
            if(mysqli_affected_rows($con)==0) {
            $queryInsere=sprintf("insert into ExigenciaDocumento
                (matriculaAluno,siglaCurso,idTipoDocumento,isento,dataEntrega) 
                values ('%s','%s',%d,'%s',CURDATE())",
                mysqli_escape_string($con, $matriculaAluno),
                mysqli_escape_string($con, $siglaCurso),
                mysqli_escape_string($con, $idTipoDocumento),
                mysqli_escape_string($con, $isento) );
                $result = mysqli_query($con, $queryInsere);
                if(!$result) throw new Exception ("Erro ao inserir em ExigenciaDocumento.");
            }
        }
    }

    /**
     * Retorna um array com todos os tipos de documentos
     * exigidos por todos os cursos
     * @return array de ExigeDocumento
     */
    public static function obterTodosExigeDocumento() {
        $con=BD::conectar();
        $query="select CTD.siglaCurso,CTD.idTipoDocumento from
            CursoTipoDocumento CTD order by CTD.siglaCurso";
        $result = BD::mysqli_query($query, $con);
        if(!result) trigger_error("Erro ao consultar CursoTipoDocumento.",E_USER_ERROR);
        $col = array();
        while($linha = mysqli_fetch_array($result)) {
            $exigeDocumento = new ExigeDocumento();
            $exigeDocumento->setCurso(Curso::obterCurso($linha["siglaCurso"]));
            $exigeDocumento->setTipoDocumento(TipoDocumento::obterTipoDocumentoPorId($linha["idTipoDocumento"]));
            $exigeDocumento->setDataEntrega(null);
            $exigeDocumento->setIsento(null);
            array_push($col, $exigeDocumento);
        }
        return $col;
    }

    /***
     *
     * Obtem um coleção deRetorna uma entrada existente: CursoTipoDocumento
     * @param matriculaAluno
     * @param siglaCurso
     * @result new CursoTipoDocumento
     **/
    public static function obterTodosExigeDocumentoPorCursoMatricula( $siglaCurso, $matriculaAluno ) {
        $con = BD::conectar();
        $query=sprintf("select ctd.siglaCurso,ctd.idTipoDocumento,
            ed.isento,ed.dataEntrega
            from CursoTipoDocumento ctd
            left outer join ExigenciaDocumento ed
                on ctd.siglaCurso = ed.siglaCurso and
                ctd.idTipoDocumento = ed.idTipoDocumento and
                ed.matriculaAluno = '%s'
            where ctd.siglaCurso='%s'",  mysqli_escape_string($con, $matriculaAluno),
            mysqli_escape_string($con, $siglaCurso));
        $result = BD::mysqli_query($query,$con);
        if(!result) trigger_error("Erro ao consultar CursoTipoDocumento e ExigenciaDocumento.",E_USER_ERROR);
        $col = array();
        while($linha = mysqli_fetch_array($result)) {
            $exigeDocumento = new ExigeDocumento();
            $exigeDocumento->setCurso(Curso::obterCurso($linha["siglaCurso"]));
            $exigeDocumento->setTipoDocumento(TipoDocumento::obterTipoDocumentoPorId($linha["idTipoDocumento"]));
            $exigeDocumento->setDataEntrega($linha["dataEntrega"]);
            $exigeDocumento->setIsento($linha["isento"]);
            array_push($col, $exigeDocumento);
        }
        return $col;
    }

    public function getCurso() {
        return $this->curso;
    }

    private function setCurso($curso) {
        $this->curso = $curso;
    }

    public function getTipoDocumento() {
        return $this->tipoDocumento;
    }

    private function setTipoDocumento($tipoDocumento) {
        $this->tipoDocumento = $tipoDocumento;
    }

    public function getIsento() {
        return $this->isento;
    }

    public function setIsento($isento) {
        $this->isento = $isento;
    }

    public function getDataEntrega() {
        return $this->dataEntrega;
    }

    public function setDataEntrega($dataEntrega) {
        $this->dataEntrega = $dataEntrega;
    }

}
?>
