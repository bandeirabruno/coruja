<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<!-- Bootstrap Js CDN -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });
    });
</script>

<?php
    if( ($login==null) || !isset($login) ) {
        trigger_error("Nao foi possivel identificar o login autenticado.",E_USER_ERROR);
    }
?>
<link href="../estilos/style4.css" rel="stylesheet" type="text/css" />
  <div class="wrapper">
    <nav id="sidebar">
        
        <?php
            if( $login->getPerfil() == Login::ADMINISTRADOR ) {
        ?>

        <ul class="list-unstyled components">
            <li>
                <a href="/coruja/baseCoruja/index.php">
                    <i class="glyphicon glyphicon-home"></i>
                    Inicio
                </a>
            </li>

            <li>
                <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false">
                    <i class="glyphicon glyphicon-duplicate"></i>
                    Cadastro
                </a>
                <ul class="collapse list-unstyled" id="pageSubmenu">
                    <li><a href="/coruja/baseCoruja/controle/manterAluno_controle.php?acao=consultar">Aluno</a></li>
                    <li><a href="/coruja/interno/manter_situacao_matricula/manterSituacaoMatricula_controle.php?acao=selecionarCurso">Administrar Matriculas</a></li>
                    <li><a id="linkMenuPeriodoLetivo" href="/coruja/siro/controle/PeriodoLetivo_controle.php?action=curso">Periodo Letivo</a></li>
                    <li><a href="/coruja/interno/selecionar_matricula_professor/selecionarMatricula_controle.php?acao=exibirFiltroPesquisa">Professor</a></li>
                    <li><a href="/coruja/interno/manter_espaco/manterEspaco_controle.php?acao=listar">Espaco</a></li>
                    <li><a href="/coruja/interno/manter_tipocurso/manterTpcurso_controle.php?acao=listar">Tipo de Curso</a></li>
                    <li><a href="/coruja/interno/manter_curso/manterCurso_controle.php?acao=listar">Curso</a></li>
                </ul>
            </li>

            <li>
                <a href="#pageSubmenu2" data-toggle="collapse" aria-expanded="false">
                    <i class="glyphicon glyphicon-duplicate"></i>
                    Emissao
                </a>
                <ul class="collapse list-unstyled" id="pageSubmenu2">
                    <li><a href="/coruja/nort/controle/emitirDiarioDeClasse_controle.php">Diario de Classe</a></li>
                    <li><a href="/coruja/interno/emitir_grade_horario/GradeHorario_controle.php">Grade de Horario</a></li>
                    <li><a href="/coruja/nort/controle/emitirHistoricoEscolar_controle.php?acao=buscarMatricula">Historico Escolar</a></li>
                    <li><a href="/coruja/interno/emitir_hist_concl_controle/emitirHistConcl_controle.php?action=consultar">Historico de Concluido</a></li>
                    <li><a href="/coruja/interno/emitir_ocupacao_espelho/emitirOcupacaoEspelho_controle.php?acao=emitirEspaco">Espelho de Ocupacao de Espaco</a></li>
                    <li><a href="/coruja/nort/controle/emitirRelatorioDeAlunosPorSituacao_controle.php">Alunos Por Situacao</a></li>
                    <li><a href="/coruja/nort/controle/emitirListaDeAlunosPorTurma_controle.php">Lista de Alunos Por Turma</a></li>
                    <li><a href="/coruja/nort/controle/emitirFichaDeMatricula_controle.php?acao=buscarMatricula">Ficha de Matricula</a></li>
                    <li><a href="/coruja/interno/exportar_dados_carteira/exportarDadosCarteira_controle.php">Exportar Dados para Carteira de Estudante</a></li>
                    <li><a href="/coruja/interno/alocacao_professor/emitirAlocacao_professor_controle.php?action=AlocacaoProfessor">Alocacao de Professor</a></li>
                    <li><a href="/coruja/interno/resumo_alocacao_professor/emitirResumoAlocacao_professor_controle.php?action=ResumoAlocacaoProfessor">Resumo de Alocacao de Professores</a></li>
                    <li><a href="/coruja/interno/emitir_decl_matr_aluno/emitirDeclMatrAluno_controle.php">Declaracao de Matricula</a></li>
                </ul>
            </li>

            <li>
                <a href="#pageSubmenu3" data-toggle="collapse" aria-expanded="false">
                    <i class="glyphicon glyphicon-duplicate"></i>
                    Turmas
                </a>
                <ul class="collapse list-unstyled" id="pageSubmenu3">
                    <li><a href="/coruja/nort/controle/manterTurmas_controle.php">Manter Turmas</a></li>
                    <li><a href="/coruja/nort/controle/lancarNotas_controle.php">Lancar Notas</a></li>
                    <li><a href="/coruja/siro/controle/ManterAlunosQueCursamTurma_controle.php?act=main" id="manter_alunos_que_cursam_uma_turma">Manter Alunos que cursam uma Turma</a></li>
                    
                </ul>
            </li>

            <li>
                <a href="#pageSubmenu4" data-toggle="collapse" aria-expanded="false">
                    <i class="glyphicon glyphicon-duplicate"></i>
                    Inscricoes
                </a>
                <ul class="collapse list-unstyled" id="pageSubmenu4">
                    <li><a href="/coruja/siro/controle/ExibirResultadoSolicitacaoInscricao_controle.php?act=main">Exibir Resultado da Solicitacao de Inscricao</a></li>
                    <li><a href="/coruja/siro/controle/SolicitarInscricaoEmTurmas_controle.php?act=main">Solicitar Inscricao em Turma</a></li>
                    <li><a href="/coruja/siro/controle/ManterSituacaoInscricaoTurma_controle.php?action=curso">Situacao de Inscricoes em Turmas</a></li>
                </ul>
            </li>

            <li>
                <a href="#pageSubmenu5" data-toggle="collapse" aria-expanded="false">
                    <i class="glyphicon glyphicon-duplicate"></i>
                    Permissoes
                </a>
                <ul class="collapse list-unstyled" id="pageSubmenu5">
                    <li><a href="/coruja/mmc_gpl/manterPermissao/grupoPermissoes_controle.php">Grupos</a></li>
                    <li><a href="/coruja/mmc_gpl/manterPermissao/buscarFuncionario_controle.php">Permissoes</a></li>
                    <li><a href=href="/coruja/mmc_gpl/manterPermissao/gerenciaLog_controle.php">Gerencia de Log</a></li>
                </ul>
            </li>

            <li>
                <a href="#pageSubmenu6" data-toggle="collapse" aria-expanded="false">
                    <i class="glyphicon glyphicon-duplicate"></i>
                    Matriz Curricular
                </a>
                <ul class="collapse list-unstyled" id="pageSubmenu6">
                    <li><a href="/coruja/siro/controle/ExibirResultadoSolicitacaoInscricao_controle.php?act=main">Matriz Proposta</a></li>
                    <li><a href="/coruja/siro/controle/ManterSituacaoInscricaoTurma_controle.php?action=curso">Imprimir Matriz</a></li>
                </ul>
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
        <a class="nav-link" href="/coruja/espacoProfessor/index_controle.php?acao=exibirIndex">Inicio</a>
    </li>
     
    <?php
        }
    ?>
    </ul>
  </div>
  
</nav>