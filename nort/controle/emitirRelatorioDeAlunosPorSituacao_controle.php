<?php
require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/BD.php";
require_once "$BASE_DIR/classes/Curso.php";
require_once "$BASE_DIR/classes/PeriodoLetivo.php";
require_once "$BASE_DIR/nort/classes/relatorioDeAlunosPorSituacao/RelatorioDeAlunosPorSituacaoPDF.php";

// Verifica Permissão
if(!$login->temPermissao($EMITIR_RELATORIO_DE_ALUNOS_POR_SITUACAO)) {
    require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    exit();
}

$acao = filter_input( INPUT_GET, "acao", FILTER_SANITIZE_STRING);
if( $acao === NULL)
{
    $acao = filter_input( INPUT_POST, "acao", FILTER_SANITIZE_STRING);
    if( $acao === NULL)
    {
        $acao = 'exibirFiltro';
    }
}
$siglaCursoFiltro = Util::obterFiltroSiglaCurso();

switch ($acao) 
{
    case 'exibirFiltro':

        $cursos = Curso::obterCursosOrdemPorSigla();

        //Carrega uma lista com os periodos registrados no sistema
        if( $siglaCursoFiltro === "")
        {
            $siglaCurso = filter_input( INPUT_POST, "siglaCurso", FILTER_SANITIZE_STRING);
        }
        else
        {
            $siglaCurso = $siglaCursoFiltro;
        }
        if(!empty ($siglaCurso)) 
        {
            $periodosLetivos = PeriodoLetivo::obterPeriodosLetivoPorSiglaCurso($siglaCurso);
            if( count($periodosLetivos)>0) 
            {
                $periodoInicial = $periodosLetivos[count($periodosLetivos)-1]->getSiglaPeriodoLetivo();
                $periodoFinal = $periodosLetivos[0]->getSiglaPeriodoLetivo();
            }
        }
        $situacoesEscolhidas = filter_input( INPUT_POST, "situacoes", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        if( !isset($situacoesEscolhidas))
        {
            $situacoesEscolhidas = array("CURSANDO","TRANCADO","EVADIDO");
        }
        $turnos = filter_input( INPUT_POST, "turnos", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        if( !isset($turnos)) 
        {
            $turnos = array("MANHÃ","TARDE","NOITE");
        }

        require "$BASE_DIR/nort/formularios/relatorioDeAlunosPorSituacao/relatorioDeAlunosPorSituacao_filtro.php";
        require_once "$BASE_DIR/includes/rodape.php";
        break;
        
    case 'mostrarFormatoWeb':
        
        $curso = filter_input( INPUT_POST, "siglaCurso", FILTER_SANITIZE_STRING);
        $periodoInicial = filter_input( INPUT_POST, "periodoInicial", FILTER_SANITIZE_STRING);
        $periodoFinal = filter_input( INPUT_POST, "periodoFinal", FILTER_SANITIZE_STRING);
        $situacoesEscolhidas = filter_input( INPUT_POST, "situacoes", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $turnos = filter_input( INPUT_POST, "turnos", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $ordem = filter_input( INPUT_POST, "ordem", FILTER_SANITIZE_STRING);

        $con = BD::conectar();

        $query = "SELECT `matriculaAluno`, `nome`, `situacaoMatricula`, MA.`siglaCurso`, PL.`siglaPeriodoLetivo`\n"
                . "FROM MatriculaAluno MA, Pessoa P, PeriodoLetivo PL\n"
                . "WHERE MA.idPessoa = P.idPessoa\n"
                . "AND MA.idPeriodoLetivo = PL.idPeriodoLetivo\n";

        $query = $query . sprintf("\nAND (MA.`siglaCurso` = '%s' ", mysqli_real_escape_string($con, $curso));

        $query = $query . " ) ";

        $query = $query . "\nAND ( FALSE ";
        foreach ($situacoesEscolhidas as $tipoSituacao) {
            $query = $query . sprintf(" OR MA.`situacaoMatricula` = '%s' ", mysqli_real_escape_string($con, $tipoSituacao));
        }
        $query = $query . " ) ";

        $query = $query . sprintf("\nAND (  PL.`siglaPeriodoLetivo` BETWEEN '%s' AND '%s' ", mysqli_real_escape_string($con, $periodoInicial), mysqli_real_escape_string($con, $periodoFinal));

        $query = $query . "\nAND ( FALSE ";
        foreach ($turnos as $turno) {
            $query = $query . sprintf(" OR MA.`turnoIngresso` = '%s' ", mysqli_real_escape_string($con, $turno));
        }
        $query = $query . " ) ";


        $query = $query . " ) ";

        //Ordenação dos resultados
        if ($ordem === 'Matricula') 
        {
            $query.= 'ORDER BY `matriculaAluno` ASC';
        } 
        else if ($ordem === 'Nome') 
        {
            $query.= 'ORDER BY `nome` ASC';
        } 
        else if ($ordem === 'Situacao') 
        {
            $query.= 'ORDER BY `situacaoMatricula` ASC';
        } 
        else if ($ordem === 'Periodo') 
        {
            $query.= 'ORDER BY PL.`siglaPeriodoLetivo` ASC';
        }
        $resultListaDeAlunos = BD::mysqli_query($query, $con);

        //em prcs
        $listaDeMatriculaAluno = array();
        $listaDenome = array();
        $listaDeSituacaoMatricula = array();
        $listaDedescPeriodoLetivo = array();
        while($row = mysqli_fetch_array($resultListaDeAlunos,MYSQLI_ASSOC)) 
        {
            $listaDeMatriculaAluno[] = $row['matriculaAluno'];
            $listaDenome[] = Util::formataNome($row['nome']);
            $listaDeSituacaoMatricula[] = $row['situacaoMatricula'];
            $listaDedescPeriodoLetivo[] = $row['siglaPeriodoLetivo'];
        }

        $umCurso = Curso::obterCurso($curso);

        require "$BASE_DIR/nort/formularios/relatorioDeAlunosPorSituacao/relatorioDeAlunosPorSituacao_formatoWeb.php";
        require_once "$BASE_DIR/includes/rodape.php";
        
        //Gerar o PDF e salva-lo na sessão

        gerarPDF($listaDeMatriculaAluno, $listaDenome, $listaDeSituacaoMatricula, $listaDedescPeriodoLetivo, $situacoesEscolhidas, $umCurso->getSiglaCurso(), $umCurso->getNomeCurso(), $periodoInicial, $periodoFinal, $turnos);

        //Registrar LOG
        registrarLog($umCurso, $situacoesEscolhidas, $periodoInicial, $periodoFinal);

        break;

    case 'exibirPDF':
        /*O comum.php iniciou a sessão antes de ter carregado a Classe do PDF,
         * então devemos fechala e reabrila agora que a classe ja foi carregada
        */
        session_write_close();
        session_start();
        $_SESSION['FPDF']->Output();
        break;
    default:
        //ERRO - USO INESPERADO
        trigger_error("Não foi possível identificar \"$acao\" como o próximo passo da funcionalide de emissão do relatório de alunos por situação", E_USER_ERROR);
        break;
}


function gerarPDF($listaDeMatriculaAluno, $listaDenome, $listaDeSituacaoMatricula, $listaDedescPeriodoLetivo, $situacoesEscolhidas, $siglaCurso, $nomeCurso, $periodoInicial, $periodoFinal,$turnos) {

    /*
     *  parametros
     * 
    //Listagem
    $listaDeMatriculaAluno
    $listaDenome
    $listaDeSituacaoMatricula
    $listaDedescPeriodoLetivo

    //Cabeçalho do documento
    $situacoesEscolhidas
    $turnos
    $siglaCurso
    $nomeCurso
    $periodoInicial
    $periodoFinal
     * 
     */
    
    $pdf = new RelatorioDeAlunosPorSituacaoPDF($listaDeMatriculaAluno,
            $listaDenome,$listaDeSituacaoMatricula,$siglaCurso, $nomeCurso, $situacoesEscolhidas, $listaDedescPeriodoLetivo, $periodoInicial, $periodoFinal,$turnos);
    $pdf->gerarCabecalho();
    $pdf->desenharListaAlunos();
    $_SESSION['FPDF'] = $pdf;
}

function registrarLog($umCurso, $situacoes, $periodoLetivoIni, $periodoLetivoFim) {
    $siglaCurso = $umCurso->getSiglaCurso();
    $nomeCurso = $umCurso->getNomeCurso();
    $mensagem = "Consulta de listagem de Alunos por Situação, ";
    $mensagem .= "do curso $siglaCurso ($nomeCurso), ";
    $mensagem .= "que estejam nas situações ";
    foreach ($situacoes as $sit) {
        $mensagem .="$sit, ";
    }
    $mensagem .= "que ingressaram do período letivo $periodoLetivoIni até $periodoLetivoFim";

    $_SESSION["login"]->incluirLog('UC01.05.00', $mensagem);
}