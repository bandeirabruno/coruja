<?php
require_once("../../includes/comum.php");
require_once("$BASE_DIR/baseCoruja/classes/ManterAlunoForm.php");
require_once("$BASE_DIR/baseCoruja/classes/ManterMatriculaForm.php");
require_once "$BASE_DIR/classes/Pessoa.php";
require_once "$BASE_DIR/classes/Aluno.php";
require_once "$BASE_DIR/classes/MatriculaAluno.php";
require_once "$BASE_DIR/classes/MatrizCurricular.php";
require_once "$BASE_DIR/classes/PeriodoLetivo.php";
require_once("$BASE_DIR/classes/FormaIngresso.php");
require_once("$BASE_DIR/classes/ExigeDocumento.php");
require_once("$BASE_DIR/classes/TipoDocumento.php");

$acao = $_REQUEST["acao"];

if($acao === "consultar") 
{
    // Verifica antes se login tem permiss�o
   /* if(!$login->temPermissao($MANTER_ALUNO_CONSULTAR)) 
    {
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    }
    */
    require_once("$BASE_DIR/baseCoruja/formularios/consultarPessoa.php");
} 
else if($acao === "buscaAluno") 
{
    // Verifica antes se login tem permiss�o
    if(!$login->temPermissao($MANTER_ALUNO_CONSULTAR)) {
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    }

    $tipoBusca = $_REQUEST["tipoBusca"];

    require_once "$BASE_DIR/baseCoruja/classes/BuscaAluno.php";

    if($tipoBusca == "nome"){ $string = $_REQUEST["nome"]; }
    elseif($tipoBusca == "cpf"){ $string = $_REQUEST["cpf"];}
    elseif($tipoBusca == "matricula"){ $string = $_REQUEST["matricula"]; }

    $retornaBusca = new BuscaAluno();

    // Monta a vis�o dinamicamente
    require_once("$BASE_DIR/includes/topo.php");
    echo '<div id="menuprincipal">';
    require_once("$BASE_DIR/includes/menu_lateral.php");
    echo '</div>';
    echo '<div id="conteudo">';
    echo $retornaBusca->retornaBusca($tipoBusca,$string);
    echo '</div>';
    require_once("$BASE_DIR/includes/rodape.php");
    exit;
} 
else if($acao=="novoCadastro") 
{
    // Verifica antes se usu�rio tem permiss�o
    if(!$login->temPermissao($MANTER_ALUNO_INSERIR)) 
    {
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    }

    $formAluno = new ManterAlunoForm();
    require_once("$BASE_DIR/baseCoruja/formularios/novoAluno.php");
} 
else if($acao=="exibirAluno") 
{
    // Verifica antes se usu�rio tem permiss�o
    if(!$login->temPermissao($MANTER_ALUNO_CONSULTAR)) {
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    }

    require_once("$BASE_DIR/baseCoruja/formularios/exibe_cad_aluno.php");
} 
else if( $acao === "salvarNovoAluno") 
{
    // Verifica antes se usu�rio tem permiss�o
    if(!$login->temPermissao($MANTER_ALUNO_INSERIR)) 
    {
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    }
    $formAluno = new ManterAlunoForm();
    $formAluno->atualizarDadosForm();

    // Valida��es
    $msgsErro = $formAluno->validarDados();
    if( count($msgsErro) != 0 ) {
        require_once("$BASE_DIR/baseCoruja/formularios/novoAluno.php");
        exit;
    }

    $con = BD::conectar();
    try {
        BD::mysqli_query("BEGIN", $con); // Inicia transa��o

        $idPessoa = Pessoa::inserirPessoa($formAluno->nome, $formAluno->sexo, $formAluno->enderecoLogradouro,
            $formAluno->enderecoNumero, $formAluno->enderecoComplemento,
            $formAluno->enderecoBairro, $formAluno->enderecoMunicipio,
            $formAluno->enderecoEstado, $formAluno->enderecoCEP,
            $formAluno->getDataNascimento(),
            $formAluno->nacionalidade,
            $formAluno->naturalidade,
            $formAluno->getTelefoneResidencial(),
            $formAluno->getTelefoneComercial(),
            $formAluno->getTelefoneCelular(),
            $formAluno->email,
            $con);

        Aluno::inserirAluno( $idPessoa,
            $formAluno->nomeMae,
            $formAluno->rgMae,
            $formAluno->nomePai,
            $formAluno->rgPai,
            $formAluno->rgNumero,
            $formAluno->getRgDataEmissao(),
            $formAluno->rgOrgaoEmissor,
            $formAluno->getCpf(),
            $formAluno->cpfProprio,
            $formAluno->certidaoNascimentoNumero,
            $formAluno->certidaoNascimentoLivro,
            $formAluno->certidaoNascimentoFolha,
            $formAluno->certidaoNascimentoCidade,
            $formAluno->certidaoNascimentoSubDistrito,
            $formAluno->certidaoNascimentoUF,
            $formAluno->certidaoCasamentoNumero,
            $formAluno->certidaoCasamentoLivro,
            $formAluno->certidaoCasamentoFolha,
            $formAluno->certidaoCasamentoCidade,
            $formAluno->certidaoCasamentoSubDistrito,
            $formAluno->certidaoCasamentoUF,
            $formAluno->estabCursoOrigem,
            $formAluno->estabCursoOrigemCidade,
            $formAluno->estabCursoOrigemUF,
            $formAluno->cursoOrigemAnoConclusao,
            $formAluno->modalidadeCursoOrigem,
            $formAluno->ctps,
            $formAluno->corRaca,
            $formAluno->estadoCivil,
            $formAluno->deficienciaVisual,
            $formAluno->deficienciaMotora,
            $formAluno->deficienciaAuditiva,
            $formAluno->deficienciaMental,
            $formAluno->responsavelLegal,
            $formAluno->rgResponsavel,
            $formAluno->tituloEleitorNumero,
            $formAluno->getTituloEleitorData(),
            $formAluno->tituloEleitorZona,
            $formAluno->tituloEleitorSecao,
            $formAluno->certificadoAlistamentoMilitarNumero,
            $formAluno->certificadoAlistamentoMilitarSerie,
            $formAluno->getCertificadoAlistamentoMilitarData(),
            $formAluno->certificadoAlistamentoMilitarRM,
            $formAluno->certificadoAlistamentoMilitarCSM,
            $formAluno->certificadoReservistaNumero,
            $formAluno->certificadoReservistaSerie,
            $formAluno->getCertificadoReservistaData(),
            $formAluno->certificadoReservistaCAT,
            $formAluno->certificadoReservistaRM,
            $formAluno->certificadoReservistaCSM,
            $con );

        MatriculaAluno::criarMatriculaAluno($idPessoa,
            $formAluno->novaMatriculaAluno,
            $formAluno->getDataNovaMatricula(),
            $formAluno->siglaCursoNovaMatricula,
            $formAluno->turnoIngressoNovaMatricula,
            $formAluno->getConcursoPontosNovaMatricula(),
            $formAluno->concursoClassificacaoNovaMatricula,
            $formAluno->idFormaIngressoNovaMatricula,
            $con);

        // Insere dados no Log
        $alunoInserido = Aluno::getAlunoByIdPessoa($idPessoa);
        $strLog = "Inserido o aluno com os dados:<br/>" .
            $alunoInserido->toString();
        $login->incluirLog($MANTER_ALUNO_INSERIR,$strLog,$con);

        BD::mysqli_query("COMMIT", $con);
    } 
    catch (Exception $ex) 
    {
        BD::mysqli_query("ROLLBACK", $con);
        $msgsErro=array();
        array_push($msgsErro, $ex->getMessage());
        require_once("$BASE_DIR/baseCoruja/formularios/novoAluno.php");
        exit;
    }

    // Exibe mensagem de sucesso e remete para consulta ao aluno
    $msgs = array();
    array_push($msgs, "Novo aluno inserido com sucesso.");
    $_REQUEST["idPessoa"] = $idPessoa;
    $_REQUEST["aba"] = 8;
    require_once("$BASE_DIR/baseCoruja/formularios/exibe_cad_aluno.php");
    exit;

} 
else if( $acao === "preparaEdicaoAluno") 
{
    // Verifica antes se usu�rio tem permiss�o
    if(!$login->temPermissao($MANTER_ALUNO_EDITAR)) 
    {
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    }
    $idPessoa = $_REQUEST["idPessoa"];
    $formAluno = new ManterAlunoForm("edicao");
    $aluno = Aluno::getAlunoByIdPessoa($idPessoa);
    $formAluno->atualizarDadosAluno($aluno);
    $formAluno->aba = $_REQUEST["aba"];

    require_once("$BASE_DIR/baseCoruja/formularios/editaAluno.php");

} 
else if( $acao === "salvarAlunoEditado") 
{
    // Verifica antes se usu�rio tem permiss�o
    if(!$login->temPermissao($MANTER_ALUNO_EDITAR)) {
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    }
    $formAluno = new ManterAlunoForm();
    $formAluno->atualizarDadosForm();

    $msgsErro = $formAluno->validarDados();
    if( count($msgsErro) !== 0 ) 
    {
        require_once("$BASE_DIR/baseCoruja/formularios/editaAluno.php");
        exit;
    }
    $idPessoa = $formAluno->idPessoa;
    $aba = $formAluno->aba;

    $con = BD::conectar();
    try 
    {
        BD::mysqli_query("BEGIN", $con); // Inicia transa��o

        $alunoAntes = Aluno::getAlunoByIdPessoa($idPessoa);

        Pessoa::atualizar(
            $idPessoa,
            $formAluno->nome,
            $formAluno->sexo,
            $formAluno->enderecoLogradouro,
            $formAluno->enderecoNumero,
            $formAluno->enderecoComplemento,
            $formAluno->enderecoBairro,
            $formAluno->enderecoMunicipio,
            $formAluno->enderecoEstado,
            $formAluno->enderecoCEP,
            $formAluno->getDataNascimento(),
            $formAluno->nacionalidade,
            $formAluno->naturalidade,
            $formAluno->getTelefoneResidencial(),
            $formAluno->getTelefoneComercial(),
            $formAluno->getTelefoneCelular(),
            $formAluno->email,
            $con);

        Aluno::atualizar( $idPessoa,
            $formAluno->nomeMae,
            $formAluno->rgMae,
            $formAluno->nomePai,
            $formAluno->rgPai,
            $formAluno->rgNumero,
            $formAluno->getRgDataEmissao(),
            $formAluno->rgOrgaoEmissor,
            $formAluno->getCpf(),
            $formAluno->cpfProprio,
            $formAluno->certidaoNascimentoNumero,
            $formAluno->certidaoNascimentoLivro,
            $formAluno->certidaoNascimentoFolha,
            $formAluno->certidaoNascimentoCidade,
            $formAluno->certidaoNascimentoSubDistrito,
            $formAluno->certidaoNascimentoUF,
            $formAluno->certidaoCasamentoNumero,
            $formAluno->certidaoCasamentoLivro,
            $formAluno->certidaoCasamentoFolha,
            $formAluno->certidaoCasamentoCidade,
            $formAluno->certidaoCasamentoSubDistrito,
            $formAluno->certidaoCasamentoUF,
            $formAluno->estabCursoOrigem,
            $formAluno->estabCursoOrigemCidade,
            $formAluno->estabCursoOrigemUF,
            $formAluno->cursoOrigemAnoConclusao,
            $formAluno->modalidadeCursoOrigem,
            $formAluno->ctps,
            $formAluno->corRaca,
            $formAluno->estadoCivil,
            $formAluno->deficienciaVisual,
            $formAluno->deficienciaMotora,
            $formAluno->deficienciaAuditiva,
            $formAluno->deficienciaMental,
            $formAluno->responsavelLegal,
            $formAluno->rgResponsavel,
            $formAluno->tituloEleitorNumero,
            $formAluno->getTituloEleitorData(),
            $formAluno->tituloEleitorZona,
            $formAluno->tituloEleitorSecao,
            $formAluno->certificadoAlistamentoMilitarNumero,
            $formAluno->certificadoAlistamentoMilitarSerie,
            $formAluno->getCertificadoAlistamentoMilitarData(),
            $formAluno->certificadoAlistamentoMilitarRM,
            $formAluno->certificadoAlistamentoMilitarCSM,
            $formAluno->certificadoReservistaNumero,
            $formAluno->certificadoReservistaSerie,
            $formAluno->getCertificadoReservistaData(),
            $formAluno->certificadoReservistaCAT,
            $formAluno->certificadoReservistaRM,
            $formAluno->certificadoReservistaCSM,
            $con );

        $alunoDepois = Aluno::getAlunoByIdPessoa($idPessoa);

        $strLog = "Dados do Aluno " . $alunoAntes->getNome() . " alterados:<br/>";
        $strLog .= Util::obterEntradasLogAlteradas($alunoAntes->toString(),$alunoDepois->toString());
        $login->incluirLog($MANTER_ALUNO_EDITAR,$strLog,$con);

        BD::mysqli_query("COMMIT", $con);
    } 
    catch (Exception $ex) 
    {
        BD::mysqli_query("ROLLBACK", $con);
        $msgsErro=array();
        array_push($msgsErro, $ex->getMessage());
        require_once("$BASE_DIR/baseCoruja/formularios/editaAluno.php");
        exit;
    }

    // Exibe mensagem de sucesso e remete para consulta ao aluno
    $msgs=array();
    array_push($msgs, "Dados do aluno alterados com sucesso.");
    $_REQUEST["idPessoa"] = $idPessoa;
    $_REQUEST["aba"] = $aba;
    require_once("$BASE_DIR/baseCoruja/formularios/exibe_cad_aluno.php");
    exit;

} 
else if( $acao === "preparaEdicaoMatricula") 
{
    // Verifica antes se usu�rio tem permiss�o
    if(!$login->temPermissao($MANTER_ALUNO_EDITAR_MATRICULA)) 
    {
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    }
    $idPessoa = $_REQUEST["idPessoa"];
    $matriculaAluno = $_REQUEST["matriculaAluno"];

    $formMatricula = new ManterMatriculaForm("edicao");
    $formMatricula->atualizarDadosMatricula( $idPessoa, $matriculaAluno);

    require_once("$BASE_DIR/baseCoruja/formularios/editaMatriculaAluno.php");

} 
else if( $acao === "mudarCursoMatricula") 
{
    // Verifica antes se usu�rio tem permiss�o
    if(!$login->temPermissao($MANTER_ALUNO_EDITAR_MATRICULA)) {
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    }
    $formMatricula = new ManterMatriculaForm();
    $formMatricula->atualizarDadosForm();
    require_once("$BASE_DIR/baseCoruja/formularios/editaMatriculaAluno.php");

} 
else if( $acao === "salvarMatriculaEditada") 
{
    // Verifica antes se usu�rio tem permiss�o
    if(!$login->temPermissao($MANTER_ALUNO_EDITAR_MATRICULA)) 
    {
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    }
    $idPessoa = $_REQUEST["idPessoa"];
    $formMatricula = new ManterMatriculaForm();
    $formMatricula->atualizarDadosForm();

    $msgsErro = $formMatricula->validarDados();
    if(count($msgsErro)>0) {
        require_once("$BASE_DIR/baseCoruja/formularios/editaMatriculaAluno.php");
        exit;
    }

    $con = BD::conectar();
    try {
        BD::mysqli_query("BEGIN", $con); // Inicia transa��o

        if($formMatricula->modo=="edicao") {

            MatriculaAluno::atualizar($idPessoa,
                    $formMatricula->matriculaAlunoAntiga,
                    $formMatricula->matriculaAlunoNova,
                    $formMatricula->siglaCurso,
                    $formMatricula->idMatriz,
                    $formMatricula->getDataMatricula(),
                    $formMatricula->getDataConclusao(),
                    $formMatricula->turnoIngresso,
                    $formMatricula->idPeriodoLetivo,
                    $formMatricula->getConcursoPontos(),
                    $formMatricula->concursoClassificacao,
                    $formMatricula->idFormaIngresso,
                    $con);
        } 
        else 
        { // "novo"
            MatriculaAluno::criarMatriculaAluno($idPessoa,
                $formMatricula->matriculaAlunoNova,
                $formMatricula->getDataMatricula(),
                $formMatricula->siglaCurso,
                $formMatricula->turnoIngresso,
                $formMatricula->getConcursoPontos(),
                $formMatricula->concursoClassificacao,
                $formMatricula->idFormaIngresso,
                $con);
        }

        // Salvar log de altera��o ou cria��o de matr�cula
        $strLog="";
        $uc="";
        $aluno = Aluno::getAlunoByIdPessoa($idPessoa);
        $nome = $aluno->getNome();
        if($formMatricula->modo=="edicao") {
            $strLog .= "Matr�cula do aluno $nome alterada<br/>";
            $strLog .= "Matr�cula anterior: " . $formMatricula->matriculaAlunoAntiga . "<br/>";
            $uc = $MANTER_ALUNO_EDITAR_MATRICULA;
        } else {
            $strLog="Matr�cula do aluno $nome inserida.<br/>";
            $uc = $MANTER_ALUNO_INCLUIR_MATRICULA;
        }
        $strLog .= "Matr�cula: " . $formMatricula->matriculaAlunoNova . "<br/>";
        $strLog .= "Curso: " . $formMatricula->siglaCurso . "<br/>";
        $strLog .= "Turno: " . $formMatricula->turnoIngresso . "<br/>";
        $strLog .= "Data da matr�cula: " . $formMatricula->dataMatriculaD .
                "/" . $formMatricula->dataMatriculaM . "/"
                . $formMatricula->dataMatriculaA . "<br/>";
        if($formMatricula->modo=="edicao") {
            $pl = PeriodoLetivo::obterPeriodoLetivo($formMatricula->idPeriodoLetivo);
            $strLog .= "Per�odo Letivo: " . $pl->getSiglaPeriodoLetivo() . "<br/>";
            $matriz = MatrizCurricular::obterMatrizCurricular($formMatricula->siglaCurso, $formMatricula->idMatriz);
            $strLog .= "Matriz: " . Util::dataSQLParaBr($matriz->getDataInicioVigencia()) . "<br/>";
        }
        $strLog .= "Concurso Pontos: " . $formMatricula->concursoPontos . "<br/>";
        $strLog .= "Concurso Classifica��o: " . $formMatricula->concursoClassificacao . "<br/>";
        $formaIngresso = FormaIngresso::getFormaIngressoById($formMatricula->idFormaIngresso);
        $strLog .= "Forma Ingresso: " . $formaIngresso->getDescricao() . "<br/>";
        $login->incluirLog($uc,  $strLog, $con);

        BD::mysqli_query("COMMIT", $con);
    } 
    catch (Exception $ex) 
    {
        BD::mysqli_query("ROLLBACK", $con);
        $msgsErro=array();
        array_push($msgsErro, $ex->getMessage());
        require_once("$BASE_DIR/baseCoruja/formularios/editaMatriculaAluno.php");
        exit;
    }

    // Exibe mensagem de sucesso e remete para consulta ao aluno
    $msgs=array();
    $msgOK = $formMatricula->modo=="edicao" ? "Matr�cula alterada com sucesso." :"Matr�cula criada com sucesso.";
    array_push($msgs, $msgOK);
    $_REQUEST["idPessoa"] = $idPessoa;
    $_REQUEST["aba"] = 7; // exibe aba de matr�cula
    require_once("$BASE_DIR/baseCoruja/formularios/exibe_cad_aluno.php");
    exit;

} 
else if( $acao === "preparaNovaMatricula") 
{
    // Verifica antes se usu�rio tem permiss�o
    if(!$login->temPermissao($MANTER_ALUNO_INCLUIR_MATRICULA)) {
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    }

    $formMatricula = new ManterMatriculaForm("novo");
    $formMatricula->idPessoa = $_REQUEST["idPessoa"];
    require_once("$BASE_DIR/baseCoruja/formularios/editaMatriculaAluno.php");
} 
else if( $acao === "mudarSituacaoDocEntregue") 
{
    // Verifica antes se usu�rio tem permiss�o
    if(!$login->temPermissao($MANTER_ALUNO_MUDAR_SITUACAO_DOC_ENTREGUE)) 
    {
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    }

    $idPessoa = $_REQUEST["idPessoa"];
    $matriculaAluno = $_REQUEST["matriculaAluno"];
    $idTipoDocumento = $_REQUEST["idTipoDocumento"];
    $situacaoDocEntregue = $_REQUEST["situacaoDocEntregue"];
    $ma = MatriculaAluno::obterMatriculaAluno($matriculaAluno);
    $siglaCurso = $ma->getSiglaCurso();

    $con = BD::conectar();

    try 
    {
        BD::mysqli_query("BEGIN", $con); // Inicia transa��o
        ExigeDocumento::mudarSituacaoDocEntregue($matriculaAluno,$siglaCurso,$idTipoDocumento,$situacaoDocEntregue,$con);

        $tipoDocumento = TipoDocumento::obterTipoDocumentoPorId($idTipoDocumento);
        $descricaoDocumento = $tipoDocumento->getDescricao();
        $strLog="Alterada a situa��o de Docs.Entregue, " .
        "da matr�cula $matriculaAluno, curso $siglaCurso, documento $descricaoDocumento, para $situacaoDocEntregue.";
        $login->incluirLog($MANTER_ALUNO_MUDAR_SITUACAO_DOC_ENTREGUE,  $strLog, $con);

        BD::mysqli_query("COMMIT", $con);
    } 
    catch (Exception $ex) 
    {
        BD::mysqli_query("ROLLBACK", $con);
        $msgsErro=array();
        array_push($msgsErro, $ex->getMessage());
        require_once("$BASE_DIR/baseCoruja/formularios/exibe_cad_aluno.php");
        exit;
    }
    echo "<html><head><script>window.alert('Situa��o Doc.Entregue alterada com sucesso.');</script><meta http-equiv='refresh' content='0;URL=/coruja/baseCoruja/controle/manterAluno_controle.php?acao=exibirAluno&aba=8&idPessoa=$idPessoa'/></head>";
    exit;

} 
else 
{
    trigger_error("A��o n�o identificada.",E_USER_ERROR);
}
