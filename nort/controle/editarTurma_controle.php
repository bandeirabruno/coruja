<?php
require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/BD.php";
require_once "$BASE_DIR/classes/MatriculaProfessor.php";
require_once "$BASE_DIR/classes/ComponenteCurricular.php";
require_once "$BASE_DIR/classes/Espaco.php";
require_once "$BASE_DIR/classes/TempoSemanal.php";
require_once "$BASE_DIR/classes/Turma.php";
require_once "$BASE_DIR/classes/PeriodoLetivo.php";
require_once "$BASE_DIR/nort/includes/manterTurmas_obterMatrizAlocacoes.php";
require_once "$BASE_DIR/nort/includes/manterTurmas_validacoes.php";

// Verifica Permiss�o
if(!$login->temPermissao($EDITAR_TURMA)) {
    require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    exit();
}

//A��o Inicial Padr�o
if ($_REQUEST["acao"] == NULL){
    $_REQUEST["acao"] = 'apresentacao';
}

$acao = $_REQUEST["acao"];

switch ($acao) {
    case 'apresentacao':

        $dadosDaTurma = consultarTurma($_POST['idTurma']);
        
        $listaDeMatProfessores = MatriculaProfessor::obterTodasMatriculasProfessorVigentes();

        $siglaCurso = $dadosDaTurma['siglaCurso'];
        $cc = ComponenteCurricular::obterComponenteCurricular($siglaCurso, $dadosDaTurma['idMatriz'], $dadosDaTurma['siglaDisciplina']);
        $matrizTempos = obterMatrizAlocacoes($siglaCurso, $dadosDaTurma['idPeriodoLetivo'], $cc->getPeriodo(), $dadosDaTurma['turno'], $dadosDaTurma['gradeHorario'], NULL);

        foreach ($matrizTempos as $diaDaSemana => $auxValue) {
            foreach ($matrizTempos[$diaDaSemana] as $tempo => $auxValue2) {
                //Se n�o houver disciplina, ent�o o campo esta propenso a altera��o
                if (!$matrizTempos[$diaDaSemana][$tempo]['siglaDisciplina']){
                    $matrizTempos[$diaDaSemana][$tempo]['espacosLivres'] = obterEspacosDisponiveis($matrizTempos[$diaDaSemana][$tempo]['idTempoSemanal'], $_POST['idPeriodoLetivo']);
                }
                //Se a turma sendo modificada, estiver alocada neste tempo, ent�o ele tamb�m esta sucetivel a altera��o
                if ($matrizTempos[$diaDaSemana][$tempo]['idTurma'] == $_POST['idTurma']){
                    //$matrizTempos[$diaDaSemana][$tempo]['espacosLivres'] = obterEspacosDisponiveis($matrizTempos[$diaDaSemana][$tempo]['idTempoSemanal'], $_POST['idPeriodoLetivo']);

                    //Obtem os espa�os que n�o possuem turma alocada naquele horario
                    $espLivres = obterEspacosDisponiveis($matrizTempos[$diaDaSemana][$tempo]['idTempoSemanal'], $_POST['idPeriodoLetivo']);
                    //Inclui os espa�os que est�o sendo utilizados pela turma sendo modificada

                    //array_push($espLivres, $espUtilizadoPelaTurma);
                    for ($i = 0; $i < count($espLivres); $i++) {
                        if ($espLivres[$i]['idEspaco'] == $matrizTempos[$diaDaSemana][$tempo]['idEspaco']){
                            $espLivres[$i]['flagSendoEditado'] = TRUE;
                            $matrizTempos[$diaDaSemana][$tempo]['flagPintarDeVerde'] = TRUE;
                        }
                    }
                    
                    $matrizTempos[$diaDaSemana][$tempo]['espacosLivres'] = $espLivres;
                }
            }
        }
        $listaDeEspacos = Espaco::obterEspacos();

        require_once "$BASE_DIR/nort/formularios/editarTurma/editarTurma_editar.php";
        require_once "$BASE_DIR/includes/rodape.php";
        break;

    case 'gradeDeHorarioAJAX':
        header("Content-Type: text/html;  charset=ISO-8859-1",true);
        /*
         * Observa��o importante.
         * existem dois periodos, o periodo letivo e o periodo do componente curricular
         */

        $dadosDaTurma = consultarTurma($_POST['idTurma']);
        //$listaDeMatProfessores = MatriculaProfessor::lista_matriculaprofessor(' 1 ORDER BY p.`nome`');
        $listaDeMatProfessores = MatriculaProfessor::obterTodasMatriculasProfessorVigentes();
        //$matrizDeAlocacoes = obterMatrizAlocacoes($dadosDaTurma['siglaCurso'], $dadosDaTurma['idPeriodoLetivo'], $dadosDaTurma['turno'], $dadosDaTurma['gradeHorario'], $_POST['idTurma']);

        $siglaCurso = $dadosDaTurma['siglaCurso'];
        $cc = ComponenteCurricular::obterComponenteCurricular($siglaCurso, $dadosDaTurma['idMatriz'], $dadosDaTurma['siglaDisciplina']);
        $matrizTempos = obterMatrizAlocacoes($siglaCurso, $dadosDaTurma['idPeriodoLetivo'], $cc->getPeriodo(), $dadosDaTurma['turno'], $dadosDaTurma['gradeHorario'], /*$_POST['idTurma']*/NULL);

        foreach ($matrizTempos as $diaDaSemana => $auxValue) { // Similar ao i de uma matriz
            foreach ($matrizTempos[$diaDaSemana] as $tempo => $auxValue2) { // Similar ao j de uma matriz
        
                //Se n�o houver disciplina, ent�o o campo esta propenso a altera��o
                if (!$matrizTempos[$diaDaSemana][$tempo]['siglaDisciplina']){
                    $matrizTempos[$diaDaSemana][$tempo]['espacosLivres'] = obterEspacosDisponiveis($matrizTempos[$diaDaSemana][$tempo]['idTempoSemanal'], $_POST['idPeriodoLetivo']);
                }
                //Se a turma sendo modificada, estiver alocada neste tempo, ent�o ele tamb�m esta sucetivel a altera��o
                if ($matrizTempos[$diaDaSemana][$tempo]['idTurma'] == $_POST['idTurma']){
                    //$matrizTempos[$diaDaSemana][$tempo]['espacosLivres'] = obterEspacosDisponiveis($matrizTempos[$diaDaSemana][$tempo]['idTempoSemanal'], $_POST['idPeriodoLetivo']);

                    //Obtem os espa�os que n�o possuem turma alocada naquele horario
                    $espLivres = obterEspacosDisponiveis($matrizTempos[$diaDaSemana][$tempo]['idTempoSemanal'], $_POST['idPeriodoLetivo']);
                    
                    for ($i = 0; $i < count($espLivres); $i++) {
                        if ($espLivres[$i]['idEspaco'] == $matrizTempos[$diaDaSemana][$tempo]['idEspaco']){
                            $espLivres[$i]['flagSendoEditado'] = TRUE;
                            $matrizTempos[$diaDaSemana][$tempo]['flagPintarDeVerde'] = TRUE;
                        }
                    }

                    $matrizTempos[$diaDaSemana][$tempo]['espacosLivres'] = $espLivres;
                }
            }
        }
        $listaDeEspacos = Espaco::obterEspacos();

        require "$BASE_DIR/nort/formularios/editarTurma/editarTurma_gradeHorario.php";

        break;
    case 'editarRegistroAJAX':
        header("Content-Type: text/html;  charset=ISO-8859-1",true);
        // Verifica se os espa�os escolidos estao disponiveis naquele determinado tempo semanal
        // Verifica se o tempo semanal j� foi ocupado por alguma outra disciplina de mesmo periodo
        // Cria a turma
        // Cria suas aloca��es

        //Corrige codifica��o (acentua��o)
        $_POST['turno'] = utf8_decode($_POST['turno']);

        $con = BD::conectar();

        try {
            BD::mysqli_query("BEGIN", $con);
            
            //Primeiro guarda algumas informa��es que ser�o utilizadas no registrarLog
            $histTurma = Turma::getTurmaById($_POST['idTurma']);
            $histNomeProfessor = ($histTurma->getProfessor()?$nomeProfessor = $histTurma->getProfessor()->getNome():"Sem Professor Alocado");
            $histQuantidade = $histTurma->getQtdeTotal();

            // TODO MB se aplicavel, refatorar para usa Turma#obterAlocacoesComoString
            $arrayAlocacoesAux = $histTurma->getAlocacoes();
            $histAlocacoes = '';
            foreach ($arrayAlocacoesAux as $aloca) {
                $histAlocacoes .= $aloca->getTempoSemanal()->getDiaSemana() . ' ';
                $histAlocacoes .= $aloca->getTempoSemanal()->obterTempoOrdinalDoTurno() . ' ';
                $histAlocacoes .= $aloca->getEspaco()->getNome() . ', ';
            }

            //O clique(aspas simples) � ajustado aqui (NULL / 'matricula')
            if ($_POST['matriculaProfessor'] == "") { //Sem professor
                $matProf = "NULL";
            } else { //Com professor
                $matProf = "'" . mysqli_real_escape_string($con, $_POST['matriculaProfessor']) . "'";
            }
            $query = sprintf("" .
                            "update Turma " .
                            "set `matriculaProfessor` = %s , " . // #1
                            "`qtdeTotal` = %d " .
                            "where " .
                            "idTurma = %d",
                            $matProf, // #1
                            mysqli_real_escape_string($con, $_POST['qtdeTotal']), // #2
                            mysqli_real_escape_string($con, $_POST['idTurma']) // #3
            );

            // ATUALIZA A TURMA
            BD::mysqli_query($query, $con);
            if (mysqli_errno($con) != 0) {
                //Ocorreu erro, rollback
                throw new Exception("Erro MySql: ".mysqli_errno($con)." - ".mysqli_error($con));
            }
            $idTurma = $_POST['idTurma'];
            $turma = Turma::getTurmaById($idTurma);

            //REMOVE TODAS AS ALOCA��ES ANTIGAS DA TURMA
            $query = sprintf("" .
                            "delete from Aloca " .
                            "where `idTurma` = %d",
                            mysqli_real_escape_string($con, $idTurma)
            );
            BD::mysqli_query($query, $con);
            if (mysqli_errno($con) != 0) {
                //Ocorreu erro, rollback
                throw new Exception("Erro MySql: ".mysqli_errno($con)." - ".mysqli_error($con));
            }


            //INSERE AS NOVAS ALOCA��ES
            foreach ($_POST as $nameForm => $valueForm) {
                if (substr($nameForm, 0, 13) == 'tempoSemanal-') {
                    if ($valueForm != "") {
                        $idTempoSemanal = substr($nameForm, 13);
                        $idEspaco = $valueForm;

                        //VALIDA CONFLITO DE HOR�RIO DISPON�VEL DO PROFESSOR (1 professor nao pode dar 2 aulas ao mesmo tempo)
                        if ($_POST['matriculaProfessor'] != ""){
                            if ( ! professorEstaDisponivel($_POST['matriculaProfessor'], $idTempoSemanal, $turma->getIdPeriodoLetivo())){
                                // ATEN��O: a mensagem lan�ada na exce��o abaixo � interpretada por outro c�digo
                                // em javascript. N�o a modifique a custo de falhas na interpreta��o.
                                throw new Exception("Conflito de aloca��o de professor no tempo:$idTempoSemanal");
                            }
                        }

                        // Valida se h� conflito na aloca��o de espa�o
                        $espaco=Espaco::obterEspacoPorId($idEspaco);
                        $tempoSemanal=TempoSemanal::getTempoSemanalById($idTempoSemanal);
                        $turma=Turma::getTurmaById($idTurma);
                        $periodoLetivo=$turma->getPeriodoLetivo();
                        if(!$espaco->espacoEstaDisponivel($tempoSemanal, $periodoLetivo)) {
                            // ATEN��O: a mensagem lan�ada na exce��o abaixo � interpretada por outro c�digo
                            // em javascript. N�o a modifique a custo de falhas na interpreta��o.
                            throw new Exception("Conflito de aloca��o de espa�o no tempo:$idTempoSemanal");
                        }

                        // QUERY DE INSER��O
                        $query = sprintf("" .
                                        "insert into Aloca (idTurma, idTempoSemanal, idEspaco) " .
                                        "values (%d,%d,%d)",
                                        mysqli_real_escape_string($con, $idTurma),
                                        mysqli_real_escape_string($con, $idTempoSemanal),
                                        mysqli_real_escape_string($con, $idEspaco)
                        );
                        // INSERE AS ALOCA��ES DA TURMA
                        BD::mysqli_query($query, $con);
                        if (mysqli_errno($con) != 0) {
                            //Ocorreu erro, rollback
                            throw new Exception("Erro MySql: " . mysqli_errno($con) . " - " . mysqli_error($con));
                        }
                    }
                }
            }

            //------------

            registrarLog(Turma::getTurmaById($_POST['idTurma']), $histNomeProfessor, $histQuantidade, $histAlocacoes);
            
            BD::mysqli_query("COMMIT", $con);

            echo 'OK';
        } catch (Exception $ex){
            
            BD::mysqli_query("ROLLBACK", $con);
            echo $ex->getMessage();
        }

        break;
    default:
        //ERRO - USO INESPERADO
        trigger_error("N�o foi poss�vel identificar \"$passo\" como o pr�ximo passo da funcionalide de edi��o de turma", E_USER_ERROR);
        break;
}

function consultarTurma($idTurma) {

    $con = BD::conectar();

    $query = sprintf(
        'SELECT T.`idTurma` , T.`siglaDisciplina` , CC.`nomeDisciplina`, T.`matriculaProfessor`, P.`nome` as `nomeProfessor`,  '
        . 'T.`turno`, T.`gradeHorario`, T.`tipoSituacaoTurma` , PL.`siglaPeriodoLetivo`, '
        . 'CUR.`siglaCurso`, CUR.`nomeCurso`, MC.`dataInicioVigencia`, CC.`tipoComponenteCurricular`, '
        . 'CC.`creditos`, CC.`cargaHoraria`, CC.`periodo`, T.`qtdeTotal`, T.`idPeriodoLetivo`, T.`idMatriz` '
        . 'FROM `Curso` CUR, `MatrizCurricular` MC, `ComponenteCurricular` CC, `PeriodoLetivo` PL, '
        . '`Turma` T '
        . 'left join `MatriculaProfessor` MP '
        . 'on MP.`matriculaProfessor` = T.`matriculaProfessor` '
        . 'left join `Pessoa` P '
        . 'on MP.`idPessoa` = P.`idPessoa` '
        . 'WHERE T.`idTurma` = %d '
        . 'AND MC.`siglaCurso` = T.`siglaCurso` '
        . 'AND MC.`idMatriz` = T.`idMatriz` '
        . 'AND PL.`idPeriodoLetivo` = T.`idPeriodoLetivo` '
        . 'AND CUR.`siglaCurso` = T.`siglaCurso` '
        . 'AND CC.`siglaCurso` = T.`siglaCurso` '
        . 'AND CC.`idMatriz` = T.`idMatriz` '
        . 'AND CC.`siglaDisciplina` = T.`siglaDisciplina` '
        . 'ORDER BY T.`tipoSituacaoTurma`, T.`turno`, T.`gradeHorario`, T.`siglaDisciplina` ASC'
        ,  mysqli_real_escape_string($con, $idTurma));

    $result = BD::mysqli_query($query);

    $infoTurma = mysqli_fetch_array($result);

    return $infoTurma;
}

function registrarLog($turma, $histNomeProfessor, $histQuantidade, $histAlocacoes){

// Exemplo
//    Criada a turma do curso TASI (Tecnologia em Sistemas Informatizados),
//            no Per�odo Letivo 2011.1, disciplina AL1 ? Algoritmos e Linguagens
//            de Programa��o I na matriz curricular vigente desde 01/01/2006,
//            Turno NOITE, Grade A, Professor Sem Professor Alocado,
//            com as aloca��es (SEG 21 Sala1, SEG 3 Sala 1,...)
    
            
    $siglaCurso = $turma->getSiglaCurso();
    $nomeCurso = $turma->getCurso()->getNomeCurso();
    $siglaPeriodoLetivo = $turma->getPeriodoLetivo()->getSiglaPeriodoLetivo();
    $siglaDisciplina = $turma->getSiglaDisciplina();
    $nomeDisciplina = $turma->getComponenteCurricular()->getNomeDisciplina();
    $dataInicioMatriz = Util::dataSQLParaBr(MatrizCurricular::obterMatrizCurricular($turma->getSiglaCurso(), $turma->getIdMatriz())->getDataInicioVigencia());
    $turno = $turma->getTurno();
    $gradeHorario = $turma->getGradeHorario();
    $quantidade = $turma->getQtdeTotal();
    
    $alocacoes = $turma->getAlocacoes();

    if($turma->getProfessor()){
        $nomeProfessor = $turma->getProfessor()->getNome();
    } else {
        $nomeProfessor = "Sem Professor Alocado";
    }

    $traco = utf8_decode("-");

    $mensagem = "Alterada a turma do curso $siglaCurso $traco $nomeCurso, ";
    $mensagem .= "no per�odo letivo $siglaPeriodoLetivo, ";
    $mensagem .= "disciplina $siglaDisciplina $traco $nomeDisciplina, ";
    $mensagem .= "na matriz curricular vigente desde $dataInicioMatriz, ";
    $mensagem .= "Turno $turno, Grade $gradeHorario, ";
    $mensagem .= "os seguintes dados: ";
    $mensagem .= "do professor $histNomeProfessor para $nomeProfessor, ";
    $mensagem .= "da quantidade $histQuantidade para $quantidade, ";
    
    $mensagem .= "das aloca��es ($histAlocacoes) para (";
    
    foreach ($alocacoes as $aloca) {
        $mensagem .= $aloca->getTempoSemanal()->getDiaSemana().' ';
        $mensagem .= $aloca->getTempoSemanal()->obterTempoOrdinalDoTurno().' ';
        $mensagem .= $aloca->getEspaco()->getNome().', ';
    }
    
    $mensagem .= ")";

    global $EDITAR_TURMA;
    $_SESSION["login"]->incluirLog($EDITAR_TURMA, $mensagem);
}