<?php
class Config {
    
    // Banco de Dados
    const BANCO_SERVIDOR = "localhost";
    const BANCO_PORTA = "3306";
    const BANCO_USUARIO = "root";
    const BANCO_SENHA = "";
    const BANCO_NOME = "coruja"; // nome do database dentro do servidor
    
    const EMAIL_SERVIDOR = "ssl://smtp.gmail.com";
    const EMAIL_PORTA = "465";
    const EMAIL_USUARIO = "email";
    const EMAIL_SENHA = 'senha';
    
    const ADMINISTRADOR_ID_PESSOA = 1029; // Login ao qual ficar� associado logs de administra��o
    const SECRETARIA_ID_PESSOA = 1030; // Id pessoa que recebe mensagens da secretaria acad�mica
    const MAX_SOLICS_POR_ALUNO = 8; //CONTROLA A QUANTIDADE DE SOLICITA��ES
    const QTDE_EMAILS_POR_LOTE = 50;
    const DIR_AREA_DADOS="/var/coruja_dados"; // dir. para armazenamento de arquivos diversos
    
    const INSTITUICAO_NOME_COMPLETO = "FACULDADE DE EDUCA��O TECNOL�GICA DO ESTADO DO RIO DE JANEIRO FERNANDO MOTA";
    const INSTITUICAO_FILIACAO_1 = "GOVERNO DO ESTADO DO RIO DE JANEIRO";
    const INSTITUICAO_FILIACAO_2 = "SECRETARIA DE ESTADO DE CI�NCIA, TECNOLOGIA E INOVA��O";
    const INSTITUICAO_FILIACAO_3 = "FUNDA��O DE APOIO A ESCOLA T�CNICA - FAETEC";    
    const INSTITUICAO_NOME_CURTO = "FAETERJ - RIO";
    const INSTITUICAO_ENDERECO="Rua Clarimundo de Melo, 847 - Quintino Bocai�va - RJ - CEP 21311-281";
    const INSTITUICAO_TELEFONE="Telefone: (21) 2332-4048"; 
    
    /*
     * <sigla_curso_1>,<id_regra_bloqueio_1>[,<id_regra_bloqueio_2>]...;<sigla_curso_2>,<id_regra_bloqueio_1>[,<id_regra_bloqueio_2>]...
     * ExcedeuTempoMaximoCurso: matr�cula n�o pode exceder o tempo m�ximo de integraliza��o permitido para o curso
     * ReprovadoDuasVezes: matr�cula com ao menos duas reprova��es no mesmo componente e ainda pendente
     */
    const BLOQUEIO_AUTOMATICO = "TASI,ExcedeuTempoMaximoCurso,ReprovadoDuasVezes;PGTIAE,ReprovadoDuasVezes";
}
    
    /** Constantes para as funcionalidades da aplica��o  NORT*/
    $EMITIR_HISTORICO="UC01.02.00";
    $MANTER_TURMAS="UC01.03.00";
    $EDITAR_TURMA="UC01.03.01";
    $CRIAR_TURMA="UC01.03.02";
    $MUDAR_SITUACAO_DA_TURMA="UC01.03.03";
    $REABRIR_TURMA_FINALIZADA = "UC01.03.04";
    $DEVOLVER_PAUTA_TURMA = "UC01.03.05";
    $EMITIR_DIARIO_DE_CLASSE="UC01.04.00";
    $EMITIR_RELATORIO_DE_ALUNOS_POR_SITUACAO="UC01.05.00";
    $EMITIR_LISTAGEM_DE_ALUNOS_POR_TURMA="UC01.07.00";
    $LANCAR_NOTAS_E_SITUACAO_DO_ALUNO_EM_TURMA="UC01.08.00";
    $EDITAR_NOTAS_E_SITUACAO_DO_ALUNO_EM_TURMA="UC01.08.01";
    $EMITIR_FICHA_DE_MATRICULA="UC01.09.00";
	
    /** Constantes para as funcionalidades da aplica��o  SIRO */
    $MANTER_PERIODO_LETIVO="UC02.02.00";
    $MANTER_PERIODO_LETIVO_INCLUIR="UC02.02.03";
    $MANTER_ALUNOS_QUE_CURSAM_TURMA="UC02.07.00";
    $SOLICITAR_INSCRICOES_EM_TURMAS="UC02.06.00";
    $INCLUIR_ALUNOS_QUE_CURSAM_TURMA="UC02.07.01";
    $SELECIONAR_ALUNO_PARA_INSCRICAO="UC02.06.03";
    $EMITIR_PROTOCOLO_GRADE_HORARIA="UC02.06.01";
    $EXCLUIR_SOLICITACAO_INSCRICAO_TURMA="UC02.06.02";
    $MANTER_SITUACAO_INSCRICOES_TURMAS="UC02.01.00";
    $DEFERIR_SOLICATACAO_INSCRICAO_JUSTIFICATIVA="UC02.01.01";
    $INDEFERIR_SOLICATACAO_INSCRICAO="UC02.01.02";
    $CANCELAR_SOLICATA��O_INSCRICAO="UC02.01.03";
    $MANTER_EVENTOS_PERIODO_LETIVO="UC02.02.01";
    $INCLUIR_EVENTOS_PERIODO_LETIVO="UC02.02.01.00";
    $ALTERAR_EVENTOS_PERIODO_LETIVO="UC02.02.01.01";
    $EXCLUIR_EVENTOS_PERIODO_LETIVO="UC02.02.01.02";
    $EMITIR_CALENDARIO_LETIVO="UC02.02.02";
    $ALTERAR_PERIODO_LETIVO="UC02.02.04";
    $EXCLUIR_PERIODO_LETIVO="UC02.02.05";
    $EXIBIR_RESULTADO_SOLICITACAO_INSCRICAO="UC02.03.00";

    /** Constantes para as funcionalidades da aplica��o INTERNO */
    $MANTER_ALUNO_CONSULTAR="UC01.01.00";
    $MANTER_ALUNO_INSERIR="UC01.01.01";
    $MANTER_ALUNO_EDITAR="UC01.01.02";
    $MANTER_ALUNO_EDITAR_MATRICULA="UC01.01.03";
    $MANTER_ALUNO_INCLUIR_MATRICULA="UC01.01.04";
    $MANTER_ALUNO_MUDAR_SITUACAO_DOC_ENTREGUE="UC01.01.05";
    $MANTER_SITUACAO_MATRICULAS="UC03.02.00";
    $MANTER_SITUACAO_MATRICULAS_REATIVAR="UC03.02.01";
    $MANTER_SITUACAO_MATRICULAS_TRANCAR="UC03.02.02";
    $MANTER_SITUACAO_MATRICULAS_PROC_REMATR_AUTO="UC03.02.03";
    $MANTER_SITUACAO_MATRICULAS_CONCLUIR="UC03.02.04";
    $MANTER_SITUACAO_MATRICULAS_DESISTIR="UC03.02.05";
    $MANTER_SITUACAO_MATRICULAS_DESLIGAR="UC03.02.06";
    $MANTER_SITUACAO_MATRICULAS_RENOVAR="UC03.02.07";
    $EXIBIR_GRADE_HORARIO="UC03.03.00";
    $SELECIONAR_MATRICULA_ALUNO="UC03.04.00";
    $EXIBIR_ESPELHO_OCUPACAO ="UC03.05.00";
    $EXPORTAR_DADOS_CARTEIRA = "UC03.06.00";
    $EXIBIR_ALOCACAO_PROFESSOR = "UC03.07.00";
    $RESUMO_ALOCACAO_PROFESSOR="UC03.08.00";
    $MANTER_LOGIN="UC03.09.00";
    $CRIAR_LOGIN="UC03.09.01";
    $ALTERAR_FOTO_LOGIN="UC03.09.02";
    $RESETAR_SENHA_LOGIN="UC03.09.03";
    $ALTERAR_LOGIN="UC03.09.04";
    $MANTER_ESPACO="UC03.10.00";
    $INCLUIR_ESPACO="UC03.10.01";
    $ALTERAR_ESPACO="UC03.10.02";
    $EXCLUIR_ESPACO="UC03.10.03";
    $BLOQUEAR_LOGIN = "UC03.11.01";

    $ENVIAR_EXTRATO_TURMA_PARA_PROFESSOR = "UC03.12.00";

    $MANTER_TIPOCURSO="UC04.11.00";
    $INCLUIR_TIPOCURSO="UC04.11.01";
    $ALTERAR_TIPOCURSO="UC04.11.02";
    $EXCLUIR_TIPOCURSO="UC04.11.03";

    $MANTER_CURSO="UC05.10.00";
    $INCLUIR_CURSO="UC05.10.01";
    $ALTERAR_CURSO="UC05.10.02";
    $EXCLUIR_CURSO="UC05.10.03";

    $MANTER_PROFESSOR="UC06.10.00";
    $INCLUIR_PROFESSOR="UC06.10.01";
    $ALTERAR_PROFESSOR="UC06.10.02";

    $EMITIR_DECL_MATR_CURSO = "UC07.00.00";
    $EMITIR_DECL_MATR_CURSO_ALTERAR_PERIODO = "UC07.00.01";

    $APONTAR_DIA_LETIVO_TURMA = "UC08.00.00";
    $REABRIR_DIA_LETIVO_TURMA = "UC08.00.01";
    $ALTERAR_DIA_LETIVO_TURMA = "UC08.00.02";
    $RECLAMAR_ALUNO_PAUTA_TURMA = "UC08.00.03";
    $LIBERAR_NOTAS_TURMA = "UC08.00.04";
    $REABRIR_NOTAS_TURMA = "UC08.00.05";
    $LIBERAR_PAUTA_TURMA = "UC08.00.06";
