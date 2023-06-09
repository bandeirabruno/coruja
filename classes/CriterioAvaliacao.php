<?php
require_once "$BASE_DIR/classes/ItemCriterioAvaliacao.php";

class CriterioAvaliacao {
    
    private $idCriterioAvalicao;
    private $rotulo;
    
    public function __construct($idCriterioAvalicao, $rotulo) {
        $this->idCriterioAvalicao = $idCriterioAvalicao;
        $this->rotulo = $rotulo;
    }
    
    public function getIdCriterioAvalicao() {
        return $this->idCriterioAvalicao;
    }

    public static function obterPorId($idCriterioAvaliacao) {
        $con = BD::conectar();
        $query = sprintf("select * from CriterioAvaliacao 
            where idCriterioAvaliacao = %d", $idCriterioAvaliacao);
        $result = BD::mysqli_query($query, $con);
        if( $linha = mysqli_fetch_array($result)) {
            return new CriterioAvaliacao( $linha["idCriterioAvaliacao"], 
                    $linha["rotulo"]);
        }
        return null;
    }

    public function getItensCriterioAvaliacao() {
        $con = BD::conectar();
        $query = sprintf("select * from ItemCriterioAvaliacao 
            where idCriterioAvaliacao = %d 
            order by ordem", $this->idCriterioAvalicao);
        $result = BD::mysqli_query($query, $con);
        $col = array();
        while( $linha = mysqli_fetch_array($result) ) {
            $col[] = new ItemCriterioAvaliacao($linha["idItemCriterioAvaliacao"],
                    $linha["idCriterioAvaliacao"],
                    $linha["rotulo"],
                    $linha["descricao"],
                    $linha["ordem"],
                    $linha["tipo"],
                    $linha["formulaCalculo"]);
        }
        return $col;
    }
    
}
?>
