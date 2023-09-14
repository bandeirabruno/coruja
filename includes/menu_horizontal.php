<?php
    if( ($login==null) || !isset($login) ) {
        trigger_error("N�o foi poss�vel identificar o login autenticado.",E_USER_ERROR);
    }
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light ">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto mx-auto">
        
        <?php
            if( $login->getPerfil() == Login::ADMINISTRADOR ) {
        ?>
        <li class="nav-item mr-3">
            <a class="nav-link" href="/coruja/baseCoruja/index.php">In�cio</a>
        </li>
        <li class="nav-item mr-3 dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Cadastro
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                <a class="dropdown-item" href="/coruja/baseCoruja/controle/manterAluno_controle.php?acao=consultar">Aluno</a>
                <a class="dropdown-item" href="/coruja/interno/manter_situacao_matricula/manterSituacaoMatricula_controle.php?acao=selecionarCurso">Administrar Matr�culas</a>
                <a class="dropdown-item" id="linkMenuPeriodoLetivo" href="/coruja/siro/controle/PeriodoLetivo_controle.php?action=curso">Per�odo Letivo</a>
                <a class="dropdown-item" href="/coruja/interno/selecionar_matricula_professor/selecionarMatricula_controle.php?acao=exibirFiltroPesquisa">Professor</a>
                <a class="dropdown-item" href="/coruja/interno/manter_espaco/manterEspaco_controle.php?acao=listar">Espa�o</a>
                <a class="dropdown-item" href="/coruja/interno/manter_tipocurso/manterTpcurso_controle.php?acao=listar">Tipo de Curso</a>
                <a class="dropdown-item" href="/coruja/interno/manter_curso/manterCurso_controle.php?acao=listar">Curso</a>

            </div>
      </li>
      <li class="nav-item mr-3 dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Emiss�o
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="/coruja/nort/controle/emitirDiarioDeClasse_controle.php">Di�rio de Classe</a>
            <a class="dropdown-item" href="/coruja/interno/emitir_grade_horario/GradeHorario_controle.php">Grade de Hor�rio</a>
            <a class="dropdown-item" href="/coruja/nort/controle/emitirHistoricoEscolar_controle.php?acao=buscarMatricula">Hist�rico Escolar</a>
            <a class="dropdown-item" href="/coruja/interno/emitir_hist_concl_controle/emitirHistConcl_controle.php?action=consultar">Hist�rico de Conclu�do</a>
            <a class="dropdown-item" href="/coruja/interno/emitir_ocupacao_espelho/emitirOcupacaoEspelho_controle.php?acao=emitirEspaco">Espelho de Ocupa��o de Espa�o</a>
            <a class="dropdown-item" href="/coruja/nort/controle/emitirRelatorioDeAlunosPorSituacao_controle.php">Alunos Por Situa��o</a>
            <a class="dropdown-item" href="/coruja/nort/controle/emitirListaDeAlunosPorTurma_controle.php">Lista de Alunos Por Turma</a>
            <a class="dropdown-item" href="/coruja/nort/controle/emitirFichaDeMatricula_controle.php?acao=buscarMatricula">Ficha de Matr�cula</a>
            <a class="dropdown-item" href="/coruja/interno/exportar_dados_carteira/exportarDadosCarteira_controle.php">Exportar Dados para Carteira de Estudante</a>
            <a class="dropdown-item" href="/coruja/interno/alocacao_professor/emitirAlocacao_professor_controle.php?action=AlocacaoProfessor">Aloca��o de Professor</a>
            <a class="dropdown-item" href="/coruja/interno/resumo_alocacao_professor/emitirResumoAlocacao_professor_controle.php?action=ResumoAlocacaoProfessor">Resumo de Aloca��o de Professores</a>
            <a class="dropdown-item" href="/coruja/interno/emitir_decl_matr_aluno/emitirDeclMatrAluno_controle.php">Declara��o de Matr�cula</a>
        </div>
      </li>
      <li class="nav-item mr-3 dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Turmas
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="/coruja/nort/controle/manterTurmas_controle.php">Manter Turmas</a>
            <a class="dropdown-item" href="/coruja/nort/controle/lancarNotas_controle.php">Lan�ar Notas</a>
            <a class="dropdown-item" href="/coruja/siro/controle/ManterAlunosQueCursamTurma_controle.php?act=main" id="manter_alunos_que_cursam_uma_turma">Manter Alunos que cursam uma Turma</a>
        </div>
      </li>
      <li class="nav-item mr-3 dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Inscri��es
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="/coruja/siro/controle/ExibirResultadoSolicitacaoInscricao_controle.php?act=main">Exibir Resultado da Solicita��o de Inscri��o</a>
            <a class="dropdown-item" href="/coruja/siro/controle/SolicitarInscricaoEmTurmas_controle.php?act=main">Solicitar Inscri��o em Turma</a>
            <a class="dropdown-item" href="/coruja/siro/controle/ManterSituacaoInscricaoTurma_controle.php?action=curso">Situa��o de Inscri��es em Turmas</a>
        </div>
      </li>
      <li class="nav-item mr-3 dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Permiss�es
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="/coruja/mmc_gpl/manterPermissao/grupoPermissoes_controle.php">Grupos</a>
            <a class="dropdown-item" href="/coruja/mmc_gpl/manterPermissao/buscarFuncionario_controle.php">Permiss�es</a>
            <a class="dropdown-item" href="/coruja/mmc_gpl/manterPermissao/gerenciaLog_controle.php">Ger�ncia de Log</a>
        </div>
      </li>
      <li class="nav-item mr-3 dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Matriz Curricular
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="/coruja/mmc_gpl/matrizCurricular/listaMatrizCurricularProposta_controle.php">Matriz Proposta </a>
            <a class="dropdown-item" href="/coruja/mmc_gpl/matrizCurricular/imprimirMatriz/imprimirMatriz_controle.php">Imprimir Matriz </a>
        </div>
      </li>
    <?php
        } else if( $login->getPerfil() == Login::ALUNO ) {
    ?>
        <li class="nav-item mr-3">
            <a class="nav-link" href="/coruja/baseCoruja/index.php">In�cio</a>
        </li>
        <li class="nav-item mr-3 dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Inscri��o
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="/coruja/siro/controle/SolicitarInscricaoEmTurmas_controle.php?action=listar">Solicitar Inscri��o</a>
                <a class="dropdown-item" href="/coruja/siro/controle/EmitirGradeHoraria_controle.php">Emitir Grade Hor�ria do Per�odo Vigente</a>
            </div>
        </li>
        <li class="nav-item mr-3 dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Matr�cula
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="/coruja/nort/controle/emitirFichaDeMatricula_controle.php?acao=gerarPDFproprioAluno">Ficha de Matr�cula</a>
            </div>
        </li>
        <li class="nav-item mr-3 dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Emiss�es
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                
                <a class="dropdown-item" href="/coruja/web/alunos/carteirinha/carteirinha_controller.php?acao=exibir">Carteirinha</a>
                <a class="dropdown-item" href="/coruja/interno/emitir_decl_matr_aluno/emitirDeclMatrAluno_controle.php?acao=exibirResumo&matriculaAluno=<?php echo $login->getNomeAcesso(); ?>">Declara��o de Matr�cula</a>
            
            </div>
        </li>
    <?php
        } else if( $login->getPerfil() == Login::PROFESSOR) {
    ?>
    <li class="nav-item mr-3">
        <a class="nav-link" href="/coruja/espacoProfessor/index_controle.php?acao=exibirIndex">In�cio</a>
    </li>
     
    <?php
        }
    ?>
    </ul>
  </div>
</nav>