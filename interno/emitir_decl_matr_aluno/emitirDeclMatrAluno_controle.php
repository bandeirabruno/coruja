<?php
require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/Aluno.php";
require_once "$BASE_DIR/classes/MatriculaAluno.php";
require_once "$BASE_DIR/classes/Curso.php";
require_once "$BASE_DIR/classes/MatrizCurricular.php";
require_once "$BASE_DIR/classes/Declaracao.php";

require_once "$BASE_DIR/interno/emitir_decl_matr_aluno/DeclMatrAlunoPDF.php";

$aluno = $var ?? "default";
$acao = $_REQUEST["acao"];
if(!isset ($acao)) { // aзгo inicial

    // Verifica Permissгo
    if(!$login->temPermissao($EMITIR_DECL_MATR_CURSO)) {
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
        exit();
    }

    header("Location: /coruja/interno/selecionar_matricula_aluno/selecionarMatricula_controle.php?acao=selecionarCurso&controleDestino=/coruja/interno/emitir_decl_matr_aluno/emitirDeclMatrAluno_controle.php&acaoControleDestino=exibirResumo&controleDestinoTitulo=" . urlencode('Emitir Declaraзгo de Matrнcula em Curso'));

} else if($acao=="exibirResumo") {


    // Verifica Permissгo
    if(!$login->temPermissao($EMITIR_DECL_MATR_CURSO) && !$login->isAluno() ) {
        echo 'nгo permitido';
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
        exit();
    }
    
    

    $numMatriculaAluno = $_REQUEST["matriculaAluno"];
    $aluno = Aluno::getAlunoByNumMatricula($numMatriculaAluno);
    $matriculaAluno = MatriculaAluno::obterMatriculaAluno($numMatriculaAluno);
    $periodoReferencia = $matriculaAluno->getPeriodoReferencia();
    $temPermissaoAlterarPeriodo = $login->temPermissao($EMITIR_DECL_MATR_CURSO_ALTERAR_PERIODO);

    if($login->isAluno() && $numMatriculaAluno != $login->getNomeAcesso() )
    {
        echo 'nгo permitido';
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
        exit();
    }

    // Verifica se o aluno estб cursando
    if( $matriculaAluno->getSituacaoMatricula() != 'CURSANDO') {
        $msgsErro = array();
        array_push($msgsErro, "A matrнcula nгo estб na situaзгo CURSANDO.");

        require_once("$BASE_DIR/interno/emitir_decl_matr_aluno/telaResumoDeclMatrAluno.php");
        exit;
    }
    
    require_once("$BASE_DIR/interno/emitir_decl_matr_aluno/telaResumoDeclMatrAluno.php");

} else if($acao=="emitirDeclMatrAluno") {
    $numMatriculaAluno = $_POST["numMatriculaAluno"];
    $aluno = Aluno::getAlunoByNumMatricula($numMatriculaAluno);
    $periodoReferencia = $_POST["periodoReferencia"];
    $matriculaAluno = MatriculaAluno::obterMatriculaAluno($numMatriculaAluno);
    $periodoReferenciaReal = $matriculaAluno->getPeriodoReferencia();
    $periodoMaxPermitido = $matriculaAluno->getMatrizCurricular()->obterQuantidadePeriodos();
    $temPermissaoAlterarPeriodo = $login->temPermissao($EMITIR_DECL_MATR_CURSO_ALTERAR_PERIODO);

    $matrizCurricular = $matriculaAluno->getMatrizCurricular();
    $curso = $matrizCurricular->getCurso();

    // Verifica Permissгo
    if( (!$login->temPermissao($EMITIR_DECL_MATR_CURSO) && (!$login->temPermissao($EMITIR_DECL_MATR_CURSO_ALTERAR_PERIODO))) && !$login->isAluno() || ($periodoReferencia != $periodoReferenciaReal) ) {
        
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
        exit();
    }

    // Verifica inconsistкncia no perнodo referкncia em relaзгo а matriz do aluno
    if( $periodoReferencia > $periodoMaxPermitido ) {
        $msgsErro = array();
        array_push($msgsErro, "O maior perнodo permitido para a matriz dessa matrнcula й " . $periodoMaxPermitido );

        require_once("$BASE_DIR/interno/emitir_decl_matr_aluno/telaResumoDeclMatrAluno.php");
        exit;
    }

    // Verifica se o aluno estб cursando
    if($matriculaAluno->getSituacaoMatricula() != 'CURSANDO') {
        $msgsErro = array();
        array_push($msgsErro, "Nгo й possнvel gerar declaraзгo para matrнcula diferente de CURSANDO.");

        require_once("$BASE_DIR/interno/emitir_decl_matr_aluno/telaResumoDeclMatrAluno.php");
        exit;
    }

    // Gravar mensagem de auditoria
    if($periodoReferencia == $periodoReferenciaReal) {
        $uc = $EMITIR_DECL_MATR_CURSO;
    } else {
        $uc = $EMITIR_DECL_MATR_CURSO_ALTERAR_PERIODO;
    }
    $strLog = "Emitida declaraзгo de matrнcula para o aluno " . $aluno->getNome() .
            ", matrнcula " . $numMatriculaAluno . ", do curso " .
            $matriculaAluno->getSiglaCurso() . ", com perнodo de referкncia " . $periodoReferencia;
    $login->incluirLog($uc,  $strLog);

    //gravar declaraзгo no banco de dados aqui
    $declaracao = new Declaracao($numMatriculaAluno, $periodoReferencia, 1);
    $declaracao->salvarDeclaracao();

    $emitirPDF = true;
    $pdf = new DeclMatrAlunoPDF();
    $_SESSION["relatorio"] = $pdf;

    require_once("$BASE_DIR/interno/emitir_decl_matr_aluno/telaResumoDeclMatrAluno.php");
    exit;
} else if($acao=="gerarPDF") {

    session_write_close();
    session_start();
    $pdf = $_SESSION["relatorio"];
    $pdf->Output();
    $_SESSION[""] = null;
    exit;
} else {
    trigger_error("Aзгo nгo identificada.",E_USER_ERROR);
}
?>