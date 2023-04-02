<?php
/*   UC 01.02.00
 *
 * Controlador respons�vel pela gera��o do hist�rico escolar de um aluno
 * Suas principais passos s�o
 */
require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/BD.php";
require_once "$BASE_DIR/nort/classes/historicoEscolar/HistoricoEscolarPDF.php";

// Verifica Permiss�o
if(!$login->temPermissao($EMITIR_HISTORICO)) {
    require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    exit();
}

$acao = $_REQUEST["acao"];

switch($acao) {
    case "buscarMatricula":
        header("Location: /coruja/interno/selecionar_matricula_aluno/selecionarMatricula_controle.php?acao=selecionarCurso&controleDestino=/coruja/nort/controle/emitirHistoricoEscolar_controle.php&acaoControleDestino=selecionarOpcaoGerarPDF&controleDestinoTitulo=" . urlencode('Emitir Hist�rico Escolar'));
        break;

    case 'selecionarOpcaoGerarPDF':
        $matricula = $_REQUEST['matriculaAluno'];
        require "$BASE_DIR/nort/formularios/historicoEscolar/emitirHistoricoEscolar_selecionarOpcaoGerarPDF.php";
        require_once "$BASE_DIR/includes/rodape.php";
        break;

    case 'gerarPDF':

        $exibeComponentesCurricularesPendentes = $_POST["exibeComponentesCurricularesPendentes"];
        $exibeHistoricoDeSituacaoDeMatricula = $_POST["exibeHistoricoDeSituacaoDeMatricula"];
        $exibeListaDeDocumentosPendentes = $_POST["exibeListaDeDocumentosPendentes"];
        $exibeObs = $_POST["exibeObs"];
        $ObsTexto = $_POST["obs"];
        $matricula = $_POST['matricula'];

        if ($matricula != NULL) {

            HistoricoEscolarPDF::setObsHistorico($matricula, 1, $ObsTexto);
            
            //Gera desenha todo o documento e o salva em uma variavel, mas ainda nao o exibe
            $pdf = gerarPDF($matricula,$exibeComponentesCurricularesPendentes,
                    $exibeHistoricoDeSituacaoDeMatricula,
                    $exibeListaDeDocumentosPendentes,
                    $exibeObs);

            //Salva o documento na sess�o do usu�rio
            $_SESSION['FPDF'] = $pdf;

            registrarLog($matricula);

            //MOSTRA P�GINA DE EMISS�O DE LISTAGEM DE ALUNOS POR TURMA
            require "$BASE_DIR/nort/formularios/historicoEscolar/emitirHistoricoEscolar_gerarPDF.php";
            require_once "$BASE_DIR/includes/rodape.php";
        }
        break;
    case 'exibirPDF':
        /* O comum.php iniciou a sess�o antes de ter carregado a Classe do PDF,
         * ent�o devemos fechala e reabrila agora que a classe ja foi carregada
         */
        session_write_close();
        session_start();
        $_SESSION['FPDF']->Output();
        break;
    default:
        //ERRO - USO INESPERADO
        trigger_error("N�o foi poss�vel identificar \"$acao\" como o pr�ximo passo da funcionalide de emiss�o da lista de alunos por turma", E_USER_ERROR);
        break;
}

function gerarPDF($matricula,
                    $exibeComponentesCurricularesPendentes,
                    $exibeHistoricoDeSituacaoDeMatricula,
                    $exibeListaDeDocumentosPendentes,
                    $exibeObs) {
    $pdf=new HistoricoEscolarPDF($matricula);
    $pdf->gerarCabecalho($matricula);
    $pdf->gerarDescricaoDoAluno();
    $pdf->gerarListaDisciplinasCusadas();

    

    if($exibeComponentesCurricularesPendentes=="SIM")
    {
        $pdf->gerarComponentesCurricularesPendentes();
    }

    if($exibeHistoricoDeSituacaoDeMatricula=="SIM")
    {
        $pdf->gerarHistoricoDeSituacaoDeMatricula();
    }

    if($exibeListaDeDocumentosPendentes=="SIM")
    {
        //$pdf->gerarListaDeDocumentosPendentes();
    }

    if ($exibeObs == "SIM") {
        
        $pdf->ExibeObsDoHistorico();
        
    }

    $pdf->gerarCR();

    return $pdf;
}

function registrarLog($matricula) {

    $con = BD::conectar();

    $query =
        sprintf(
            "select "
            ."    PE.`nome`, MA.`matriculaAluno`, CUR.`siglaCurso`, CUR.`nomeCurso` "
            ."from "
            ."    Pessoa PE, MatriculaAluno MA, Curso CUR "
            ."where "
            ."    MA.`matriculaAluno` = '%s' "
            ."    and PE.`idPessoa` = MA.`idPessoa` "
            ."    and MA.`siglaCurso` = CUR.`siglaCurso` "
            ,  mysqli_real_escape_string($con, $matricula));
    $result = BD::mysqli_query($query,$con);
    $resDadosLog = mysqli_fetch_array($result);
    $nome = Util::formataNome($resDadosLog["nome"]);
    $matriculaAluno = $resDadosLog["matriculaAluno"];
    $siglaCurso = $resDadosLog["siglaCurso"];
    $nomeCurso = $resDadosLog["nomeCurso"];

    $mensagem = "Emitido Hist�rico do aluno $nome, matr�cula $matriculaAluno, do curso $siglaCurso ($nomeCurso)";

    $_SESSION["login"]->incluirLog('UC01.02.00', $mensagem);
}
?>