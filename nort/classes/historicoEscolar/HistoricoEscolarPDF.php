<?php

/**
 * Gera um PDF contendo o Historico Escolar
 *
 * A maior parte do documento � gerada utilizando Cells (Tabela), tratando uma linha
 * por vez
 */

require("$BASE_DIR/nort/classes/FpdfNort.php");
require_once "$BASE_DIR/classes/Util.php";
require_once "$BASE_DIR/classes/MatriculaAluno.php";
require_once "$BASE_DIR/classes/Curso.php";
require_once "$BASE_DIR/classes/TipoCurso.php";
require_once "$BASE_DIR/classes/ComponenteCurricular.php";
require_once "$BASE_DIR/classes/SituacaoMatriculaHistorico.php";

class HistoricoEscolarPDF extends FpdfNort {

    private $numeroMatriculaAluno;
    private $matriculaAluno;

    private $nomeDoCurso;
    private $descricaoTipoCurso;

    private $nomeAluno;
    private $nomeDoPaiDoAluno;
    private $nomeDaMaeDoAluno;
    private $naturalidade;
    private $identidade;
    private $orgaoEmissor;
    private $nascimento;
    private $cpf;

    //vari�veis do ambiente
    private $larguraMaxima;
    private $alturaDaQuebraDePagina = 260; //Quanto maior, mais abaixo ocorre a quebra
    private $debug = 0;
    // $debug � usada para mostrar ou ocultar as bordas das tabelas
    //para fins de desenvolvimento


    /*
     * recebe como parametro apenas o numero da matricula do aluno, e n�o um objeto
     *
     */
    function HistoricoEscolarPDF($numeroMatriculaAluno) {
        parent::FpdfNort('P'); //Ajusta p�gina Vertical(Normal / Padrao)
        $this->AliasNbPages( '{total}' );
        $this->AddPage();

        $this->numeroMatriculaAluno = $numeroMatriculaAluno;
        $this->matriculaAluno = MatriculaAluno::obterMatriculaAluno( $this->numeroMatriculaAluno);
        $this->carregaNomeDoCurso();
        $this->carregarInformacaoSobreOAluno();

        $this->larguraMaxima = $this->w - ($this->lMargin + $this->rMargin);
    }

    function gerarCabecalho($mat) {

        //Selecionar a Matriz Curricular
        $con = BD::conectar();
        // OBTEM TODOS OS COMPONENTES CURRICULARES DA MATRIZ CURRICULAR DA MATRICULA DO ALUNO
        $query=sprintf(''
            . ' SELECT'
            . ' idMatriz'
            . ' FROM'
            . ' MatriculaAluno MA'
            . ' WHERE'
            . " MA.matriculaAluno = '%s'",
            mysqli_real_escape_string($con, $mat));
        
        $result=BD::mysqli_query($query,$con) or die("ERRO");

        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

        $matrizId = $row['idMatriz'];

        $margemDoCabecalho = 32;
        $tamHorizontalDoCabecalho =  $this->larguraMaxima - 30;
        $tamFonteGrande = 12;
        $tamFonteMedia = 9;
        $tamFontePequena = 8;

        //Logo do estado do Rio de Janeiro
        $this->Image('../../imagens/logorj.jpg',$this->lMargin,$this->tMargin,20);

        $this->SetX($margemDoCabecalho);
        $this->SetFont('Arial','',$tamFontePequena);
        $txt = Config::INSTITUICAO_FILIACAO_1 . "\n" .
            Config::INSTITUICAO_FILIACAO_2 . "\n" .
            Config::INSTITUICAO_FILIACAO_3;
        $this->MultiCell($tamHorizontalDoCabecalho,4.0,$txt, $this->debug,'L');

        $espacamentoHorizontal = 5;

        $txt = Config::INSTITUICAO_NOME_COMPLETO;
        $this->SetY($this->GetY() - 0.8);
        $this->SetX($margemDoCabecalho);
        $this->Cell($tamHorizontalDoCabecalho, $espacamentoHorizontal, $txt, $this->debug , 1, 'L');
        $this->SetFont('Arial','',$tamFontePequena);
        //$this->SetY(30);

        $espacamentoHorizontal = 5;

        $txt = Config::INSTITUICAO_NOME_CURTO;
        $this->SetY($this->GetY()-1);
        $this->SetX($margemDoCabecalho);
        $this->Cell($tamHorizontalDoCabecalho, $espacamentoHorizontal, $txt, $this->debug , 1, 'L');
        $this->SetFont('Arial','',$tamFontePequena);
        $this->SetY(32);

        //Nome Do Curso
        $this->SetFont('Arial','',$tamFonteGrande);

       /* if ($matrizId == 6){ //Matriz Antiga

            $txt = "Curso de Gradua��o em Tecnologia em An�lise e Desenvolvimento de Sistemas";
        }
        else{ // Matriz Nova

            $txt = "Curso de " . $this->descricaoTipoCurso . " em " . $this->nomeDoCurso;

        }*/

        $txt = "Curso de " . $this->descricaoTipoCurso . " em " . $this->nomeDoCurso;

        
        $this->SetX($margemDoCabecalho);
        $this->Cell($tamHorizontalDoCabecalho, $espacamentoHorizontal, $txt, $this->debug , 1, 'L');

        //Texto abaixo do nome do curso
        $this->SetX($margemDoCabecalho);
        $this->SetFont('Arial','',$tamFontePequena);

        if ($matrizId < 6){ //Matriz Antiga

            $txt = 'DECRETO DE CRIA��O N�MERO 30.938 DE 18/03/2002 D.O.E.R.J. 19/03/2002
RECONHECIMENTO:PARECER C.E.E N�MERO 066/2009 DE 09/06/2009 D.O.E.R.J. 14/07/2009
RENOVA��O DE RECONHECIMENTO: PARECER C.E.E 100 DE 11/12/2018
HOMOLOGADO PELA PORTARIA C.E.E N�MERO 3710 DE 25/02/2019 D.O.E.R.J. 28/02/2019
RECREDENCIAMENTO: PARECER C.E.E N�MERO 018 DE 19/03/2019
HOMOLOGADO PELA PORTARIA C.E.E N�MERO 3716 DE 26/04/2019 D.O.E.R.J 30/04/2019';
        }
        else{ // Matriz Nova
            $txt = 'DECRETO DE CRIA��O N�MERO 30.938 DE 18/03/2002 D.O.E.R.J. 19/03/2002
RECONHECIMENTO:PARECER C.E.E N�MERO 066/2009 DE 09/06/2009 D.O.E.R.J. 14/07/2009
RENOVA��O DE RECONHECIMENTO: PARECER C.E.E 100 DE 11/12/2018
HOMOLOGADO PELA PORTARIA C.E.E N�MERO 3710 DE 25/02/2019 D.O.E.R.J. 28/02/2019
RECREDENCIAMENTO: PARECER C.E.E N�MERO 018 DE 19/03/2019
HOMOLOGADO PELA PORTARIA C.E.E N�MERO 3716 DE 26/04/2019 D.O.E.R.J 30/04/2019';
        }


        /* $txt = 'DECRETO DE CRIA��O N�MERO 30.938 DE 18/03/2002 D.O.E.R.J. 19/03/2002
RECONHECIMENTO:PARECER C.E.E 066/2009 DE 09/06/2009 D.O.E.R.J. 14/07/2009
RECREDENCIAMENTO:PORTARIA N�mero 3716 DE 26/04/2019 D.O.E.R.J. 30/04/2019'; */


        $this->MultiCell($tamHorizontalDoCabecalho,4.0,$txt, $this->debug,'L');

        //Texto: Hist�rico Escolar
        $this->SetFont('Arial','B',$tamFonteGrande);
        $txt = 'HIST�RICO ESCOLAR';
        $this->SetY(63);
        $this->Cell($this->larguraMaxima, $espacamentoHorizontal, $txt, $this->debug , 1, 'C');

    }

    function gerarDescricaoDoAluno() {

        $tamanhoTerceiroGomo = 45;
        $tamanhoSegundoGomo = 45;
        $tamanhoPrimeiroGomo = $this->larguraMaxima - ($tamanhoTerceiroGomo + $tamanhoSegundoGomo);

        $fontePequena = 6;
        $espacamentoHorizontalPequeno = 2.5;
        $fonteMedia = 9;
        $espacamentoHorizontalGrande = 4;

        //linha1

        //parte superior
        $this->SetFont('Arial','',$fontePequena);
        $txt='NOME:';
        $this->Cell($tamanhoPrimeiroGomo, $espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'LTR' , 0, 'L');
        $txt='MATR�CULA:';
        $this->Cell($tamanhoSegundoGomo, $espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'LTR' , 0, 'L');
        $txt='NASCIMENTO:';
        $this->Cell($tamanhoTerceiroGomo, $espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'LTR' , 1, 'L');

        //parte inferior
        $this->SetFont('Arial','',$fonteMedia);
        $txt=$this->nomeAluno;
        $this->Cell($tamanhoPrimeiroGomo, $espacamentoHorizontalGrande, $txt, ($this->debug)?1:'LRB' , 0, 'L');
        $txt=$this->numeroMatriculaAluno;
        $this->Cell($tamanhoSegundoGomo, $espacamentoHorizontalGrande, $txt, ($this->debug)?1:'LRB' , 0, 'L');
        $txt = Util::dataSQLParaBr($this->nascimento);
        $this->Cell($tamanhoTerceiroGomo, $espacamentoHorizontalGrande, $txt, ($this->debug)?1:'LRB' , 1, 'L');

        //linha2

        //parte superior
        $this->SetFont('Arial','',$fontePequena);
        $txt='NOME DO PAI:';
        $this->Cell($tamanhoPrimeiroGomo, $espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'LTR' , 0, 'L');
        $txt='NATURALIDADE:';
        $this->Cell($tamanhoSegundoGomo, $espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'LTR' , 0, 'L');
        $txt='CPF:';
        $this->Cell($tamanhoTerceiroGomo, $espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'LTR' , 1, 'L');

        //parte inferior
        $this->SetFont('Arial','',$fonteMedia);
        $txt=$this->nomeDoPaiDoAluno;
        $this->Cell($tamanhoPrimeiroGomo, $espacamentoHorizontalGrande, $txt, ($this->debug)?1:'LRB' , 0, 'L');
        $txt=$this->naturalidade;
        $this->Cell($tamanhoSegundoGomo, $espacamentoHorizontalGrande, $txt, ($this->debug)?1:'LRB' , 0, 'L');
        $txt=$this->cpf;
        $this->Cell($tamanhoTerceiroGomo, $espacamentoHorizontalGrande, $txt, ($this->debug)?1:'LRB' , 1, 'L');

        //linha3

        //parte superior
        $this->SetFont('Arial','',$fontePequena);
        $txt='NOME DA MAE:';
        $this->Cell($tamanhoPrimeiroGomo, $espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'LTR' , 0, 'L');
        $txt='IDENTIDADE / ORG�O EXPEDITOR:';
        $this->Cell($tamanhoSegundoGomo, $espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'LTR' , 0, 'L');
        $txt='';
        $this->Cell($tamanhoTerceiroGomo, $espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'LTR' , 1, 'L');

        //parte inferior
        $this->SetFont('Arial','',$fonteMedia);
        $txt=$this->nomeDaMaeDoAluno;
        $this->Cell($tamanhoPrimeiroGomo, $espacamentoHorizontalGrande, $txt, ($this->debug)?1:'LRB' , 0, 'L');
        $txt=$this->identidade.'   '.$this->orgaoEmissor;
        $this->Cell($tamanhoSegundoGomo, $espacamentoHorizontalGrande, $txt, ($this->debug)?1:'LRB' , 0, 'L');
        $txt='';
        $this->Cell($tamanhoTerceiroGomo, $espacamentoHorizontalGrande, $txt, ($this->debug)?1:'LRB' , 1, 'L');
    }

    function gerarListaDisciplinasCusadas() {
        $this->SetY($this->GetY()+7);
        //Dece um pouco o cursor

        $tamanGomoN1 = 20;
        $tamanGomoN2 = 20;
        $tamanGomoN4 = 15;
        $tamanGomoN5 = 15;
        $tamanGomoN6 = 15;
        $tamanGomoN7 = 15;
        $tamanGomoN3 = $this->larguraMaxima - ($tamanGomoN1 + $tamanGomoN2 + $tamanGomoN4 + $tamanGomoN5
            + $tamanGomoN6 + $tamanGomoN7 );

        $fonteMedia = 9;
        $espacamentoHorizontalMedio = 2.5;
        $fonteGrande = 9;
        $espacamentoHorizontalGrande = 4;

        $this->SetFont('Arial','B',$fonteGrande);

        //Cabe�alho, Linha 1, Nome da tabela
        $txt='DISCIPLINAS CURSADAS';
        $this->SetFillColor(210);
        $this->Cell($this->larguraMaxima, $espacamentoHorizontalGrande, $txt, 1, 1, 'C', 1);
        //Cabe�alho, Linha 2, Colunas
        $this->SetFillColor(255);
        $txt='Per�odo';
        $this->Cell($tamanGomoN1, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
        $txt='Turno/Grade';
        $this->Cell($tamanGomoN2, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
        $txt='Disciplina';
        $this->Cell($tamanGomoN3, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
        $txt='CRED';
        $this->Cell($tamanGomoN4, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
        $txt='CH';
        $this->Cell($tamanGomoN5, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
        $txt='MF';
        $this->Cell($tamanGomoN6, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
        $txt='SF';
        $this->Cell($tamanGomoN7, $espacamentoHorizontalGrande, $txt, 1, 1, 'C');


        $con = BD::conectar();
        $matricula=$this->numeroMatriculaAluno;
        
        // DISCIPLINAS CURSADAS
        $query=sprintf("select PL.`siglaPeriodoLetivo`, T.`turno`, "
            ."T.`gradeHorario`, CC.`siglaDisciplina`, "
            ."CC.`nomeDisciplina`, CC.`creditos`, "
            ."CC.`cargaHoraria`, I.`mediaFinal`, I.`situacaoInscricao` "
            ."from Inscricao I, Turma T, ComponenteCurricular CC, PeriodoLetivo PL "
            ."where I.`matriculaAluno` = '%s' "
            ."and I.`idTurma` = T.`idTurma` "
            ."and T.`siglaCurso` = CC.`siglaCurso` "
            ."and T.`idMatriz` = CC.`idMatriz` "
            ."and T.`siglaDisciplina` = CC.`siglaDisciplina` "
            ."and T.`idPeriodoLetivo` = PL.`idPeriodoLetivo` "
            ."and I.`situacaoInscricao` in ('AP','RF','RM','ID') " //ID -> Isento de Disciplina
            ."and T.`tipoSituacaoTurma` = 'FINALIZADA' " //ID -> Isento de Disciplina
            ."ORDER BY PL.`siglaPeriodoLetivo`, CC.`siglaDisciplina` ",
            mysqli_real_escape_string($con, $matricula));
        $result=BD::mysqli_query($query,$con);

        $this->SetFont('Arial','',$fonteMedia);
        while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
            $periodo = $row['siglaPeriodoLetivo'];
            $turno = $row['turno'];
            $gradeHorario = $row['gradeHorario'];
            $siglaDisciplina = $row['siglaDisciplina'];
            $nomeDisciplina = $row['nomeDisciplina'];
            $creditos = $row['creditos'];
            $cargaHoraria = $row['cargaHoraria'];
            $mediaFinal = number_format($row['mediaFinal'], 1, ",", "");
            $situacaoFinal = $row['situacaoInscricao'];

            $txt=$periodo;
            $this->Cell($tamanGomoN1, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
            $txt="$turno / $gradeHorario";
            $this->Cell($tamanGomoN2, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
            $txt=$siglaDisciplina.' - '.$nomeDisciplina;
            $this->Cell($tamanGomoN3, $espacamentoHorizontalGrande, $txt, 1, 0, 'L');
            $txt=$creditos;
            $this->Cell($tamanGomoN4, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
            $txt=$cargaHoraria;
            $this->Cell($tamanGomoN5, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
            if($situacaoFinal=='ID') {
                $txt="---";
            } else {
                $txt=$mediaFinal;
            }
            $this->Cell($tamanGomoN6, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
              
            //Gabiarra solicitada pela Jamile, Informei que iria demorar pois alteraria a regra de neg�cio
            //A mesma n�o quis esperar informando q era urgente, e assim est� sendo feito mais um gambiarra no sistema
            //S� espec�ficada caso o per�odo = "2020.1" e a situacaoFinal = "RF"
            if($periodo == '2020.1' && $situacaoFinal == "RF") {

                $txt= '---';

            } else {

                $txt=$situacaoFinal;
                
            }
            //Fim Gambiarra Feito em 12/01/2020
            
            $this->Cell($tamanGomoN7, $espacamentoHorizontalGrande, $txt, 1, 1, 'C');
			
            if ($this->GetY() > $this->alturaDaQuebraDePagina) {
                $this->addPage();
            }
        }

        
    }

    function gerarComponentesCurricularesPendentes() {
        //Obter todos os componentes curriculares que o aluno deve cumprir da matriz dele

        $matricula=$this->numeroMatriculaAluno;
        $con = BD::conectar();
        // OBTEM TODOS OS COMPONENTES CURRICULARES DA MATRIZ CURRICULAR DA MATRICULA DO ALUNO
        $query=sprintf(''
            . ' SELECT'
            . ' CC.`siglaCurso`, CC.`idMatriz`, CC.`siglaDisciplina`'
            . ' FROM'
            . ' `ComponenteCurricular` CC, `MatriculaAluno` MA'
            . ' WHERE'
            . ' CC.`siglaCurso` = MA.`siglaCurso`'
            . ' and CC.`idMatriz` = MA.`idMatriz`'
            . " and MA.`matriculaAluno` = '%s'"
            . ' ORDER BY CC.`periodo`, CC.`siglaDisciplina`',
            mysqli_real_escape_string($con, $matricula));
        
        $result=BD::mysqli_query($query,$con);

        $this->SetY($this->GetY()+7);
        //Desce um pouco o cursor

        $tamanGomoN1 = 20;
        $tamanGomoN3 = 15;
        $tamanGomoN4 = 15;
        $tamanGomoN5 = 15;
        $tamanGomoN6 = 15;
        $tamanGomoN2 = $this->larguraMaxima - ($tamanGomoN1 + $tamanGomoN3 + $tamanGomoN4
            + $tamanGomoN5 + $tamanGomoN6 );

        $fonteMedia = 9;
        $espacamentoHorizontalMedio = 2.5;
        $fonteGrande = 9;
        $espacamentoHorizontalGrande = 4;

        $this->SetFont('Arial','B',$fonteGrande);

        //Cabe�alho, Linha 1, Nome da tabela
        $txt='DISCIPLINAS PENDENTES';
        $this->SetFillColor(210);
        $this->Cell($this->larguraMaxima, $espacamentoHorizontalGrande, $txt, 1, 1, 'C', 1);
        //Cabe�alho, Linha 2, Colunas
        $this->SetFillColor(255);
        $txt='Per�odo';
        $this->Cell($tamanGomoN1, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
        $txt='Disciplina';
        $this->Cell($tamanGomoN2, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
        $txt='CRED';
        $this->Cell($tamanGomoN3, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
        $txt='CH';
        $this->Cell($tamanGomoN4, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
        $txt='TIPO';
        $this->Cell($tamanGomoN5 + $tamanGomoN6, $espacamentoHorizontalGrande, $txt, 1, 1, 'C');

        $this->SetFont('Arial','',$fonteMedia);
        while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
            $siglaCurso = $row['siglaCurso'];
            $idMatriz = $row['idMatriz'];
            $siglaDisciplina = $row['siglaDisciplina'];
            $componenteCur = ComponenteCurricular::obterComponenteCurricular($siglaCurso, $idMatriz, $siglaDisciplina);
            
            //Se n�o h� quita��o, entao este componente curricular esta na lista de Componentes Pendentes
            if($componenteCur->obterQuitacao($this->matriculaAluno) == null && $componenteCur->getTipoComponenteCurricular() == "OBRIGAT�RIA"){
                //$periodoLetivo = Periodoletivo::obterPeriodoLetivo($componenteCur->getPeriodo()) ;
                $txt = $componenteCur->getPeriodo().'�';//$periodoLetivo->getSiglaPeriodoLetivo();
                $this->Cell($tamanGomoN1, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
                $txt = $componenteCur->getSiglaDisciplina().' - '.$componenteCur->getNomeDisciplina();
                $this->Cell($tamanGomoN2, $espacamentoHorizontalGrande, $txt, 1, 0, 'L');
                $txt = $componenteCur->getCreditos();
                $this->Cell($tamanGomoN3, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
                $txt = $componenteCur->getCargaHoraria();
                $this->Cell($tamanGomoN4, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
                $txt = $componenteCur->getTipoComponenteCurricular();
                $this->Cell($tamanGomoN5 + $tamanGomoN6, $espacamentoHorizontalGrande, $txt, 1, 1, 'C');
				
                if($this->GetY() > $this->alturaDaQuebraDePagina){
                    $this->addPage();
                }
            }
        }
    }
    
    function gerarHistoricoDeSituacaoDeMatricula() {

        $this->SetY($this->GetY() + 7);
        //Desce um pouco o cursor
        
        if ($this->GetY() > $this->alturaDaQuebraDePagina) {
            $this->addPage(); //Quebra p�gina caso nescess�rio
        }

        $situacoes = SituacaoMatriculaHistorico::getAllByNumMatriculaAluno($this->numeroMatriculaAluno);

        $fonteMedia = 9;
        $fonteGrande = 9;
        
        $espacamentoVerticalGrande = 4;
        $tamanGomoN1 = 20; //Data
        $tamanGomoN2 = 20; //Situacao
        $tamanGomoN3 = $this->larguraMaxima - ($tamanGomoN1 + $tamanGomoN2); //Texto da Observa��o
        
        //FONTE
        $this->SetFont('Arial','B',$fonteGrande);
        //Cabe�alho, Linha 1, Nome da tabela
        $txt='HIST�RICO DE SITUA��ES DE MATR�CULA';
        $this->SetFillColor(210);
        $this->Cell($this->larguraMaxima, $espacamentoVerticalGrande, $txt, 1, 1, 'C', 1);
        //Cabe�alho, Linha 2, Colunas
        $this->SetFillColor(255);
        if (!empty ($situacoes)){
            $txt='Data';
            $this->Cell($tamanGomoN1, $espacamentoVerticalGrande, $txt, 1, 0, 'C');
            $txt='Situa��o';
            $this->Cell($tamanGomoN2, $espacamentoVerticalGrande, $txt, 1, 0, 'C');
            $txt='Observa��o';
            $this->Cell($tamanGomoN3, $espacamentoVerticalGrande, $txt, 1, 0, 'C');
            $this->Ln();
        }

        //FONTE
        $this->SetFont('Arial','',$fonteMedia);
        
        foreach ($situacoes as $sit) {

            $txt = Util::dataSQLParaBr($sit->getDataHistorico());
            $this->Cell($tamanGomoN1, $espacamentoVerticalGrande, $txt, 1, 0, 'C');
            $txt = $sit->getSituacaoMatricula();
            $this->Cell($tamanGomoN2, $espacamentoVerticalGrande, $txt, 1, 0, 'C');
            $txt = $sit->getTexto();
            $this->MultiCell($tamanGomoN3, $espacamentoVerticalGrande, $txt, 1, 'C');
            //this->Ln(); -> MultiCell j� aplica um breakLine ao final da celula

            if ($this->GetY() > $this->alturaDaQuebraDePagina) {
                $this->addPage();
            }
        }
        
        if (empty ($situacoes)){
            $txt = 'N�o existem dados no hist�rico desta matr�cula';
            $this->Cell($this->larguraMaxima, $espacamentoVerticalGrande, $txt, 1, 0, 'L');
            $this->Ln();
        }
    }
    
    function gerarListaDeDocumentosPendentes() {
        
        $this->SetY($this->GetY() + 7);
        //Desce um pouco o cursor
        
        if ($this->GetY() > $this->alturaDaQuebraDePagina) {
            $this->addPage(); //Quebra p�gina caso nescess�rio
        }

        $documentos = $this->matriculaAluno->obterTipoDocumentosNaoEntregues();
        
        $fonteMedia = 9;
        $fonteGrande = 9;
        
        $espacamentoVerticalGrande = 4;
        
        
        //FONTE
        $this->SetFont('Arial','B',$fonteGrande);
        //Cabe�alho, Linha 1, Nome da tabela
        $txt='DOCUMENTOS PENDENTES';
        $this->SetFillColor(210);
        $this->Cell($this->larguraMaxima, $espacamentoVerticalGrande, $txt, 1, 1, 'C', 1);
        //Cabe�alho, Linha 2, Colunas
        $this->SetFillColor(255);
        
        //FONTE
        $this->SetFont('Arial','',$fonteMedia);
        
        foreach ($documentos as $doc) {

            $txt=$doc->getDescricao();
            $this->Cell($this->larguraMaxima, $espacamentoVerticalGrande, $txt, 1, 0, 'C');
            $this->Ln();

            if ($this->GetY() > $this->alturaDaQuebraDePagina) {
                $this->addPage();
            }
        }
        
        if (empty ($documentos)){
            $txt = 'Todos os documentos necess�rios j� foram entregues';
            $this->Cell($this->larguraMaxima, $espacamentoVerticalGrande, $txt, 1, 0, 'L');
            $this->Ln();
        }
    }

    //Pegar as obcerva�~�oes do hist�rico

    public static function getObsHistorico($numeroMatriculaAluno)
    {
        $con = BD::conectar();
        // OBTEM a observa��o do hist�rico
        $query=sprintf("SELECT msg
        FROM HistoricoObservacao
        WHERE matriculaAluno = '%s'
        AND tipo = 1",
        mysqli_real_escape_string($con, $numeroMatriculaAluno));
        
        $result = BD::mysqli_query($query,$con);

        $obs = mysqli_fetch_array($result,MYSQLI_ASSOC);

        if (is_null($obs)) {
            return $obs;
        }
        else{
            return $obs['msg'];
        }

        

    }

    public static function setObsHistorico($numeroMatriculaAluno, $tipo, $msg)
    {
        
        $con = BD::conectar();
        // OBTEM a observa��o do hist�rico
        $query ="REPLACE INTO HistoricoObservacao (matriculaAluno, tipo, msg) 
        VALUES ('$numeroMatriculaAluno', $tipo, '$msg') ";
    
        
        $result = BD::mysqli_query($query,$con);

    }
    
    function ExibeObsDoHistorico() {

        $matricula = $this->numeroMatriculaAluno;

        $obs = self::getObsHistorico($matricula);

        $this->SetY($this->GetY() + 7);
        //Desce um pouco o cursor
        
        if ($this->GetY() > $this->alturaDaQuebraDePagina) {
            $this->addPage(); //Quebra p�gina caso nescess�rio
        }

        $documentos = $this->matriculaAluno->obterTipoDocumentosNaoEntregues();
        
        $fonteMedia = 9;
        $fonteGrande = 9;
        
        $espacamentoVerticalGrande = 4;
        
        
        //FONTE
        $this->SetFont('Arial','B',$fonteGrande);
        //Cabe�alho, Linha 1, Nome da tabela
        $txt='OBSERVA��O';
        $this->SetFillColor(210);
        $this->Cell($this->larguraMaxima, $espacamentoVerticalGrande, $txt, 1, 1, 'C', 1);
        //Cabe�alho, Linha 2, Colunas
        $this->SetFillColor(255);
        
        //FONTE
        $this->SetFont('Arial','',$fonteMedia);
        
        $txt = $obs;
        $this->MultiCell($this->larguraMaxima, $espacamentoVerticalGrande, $txt, 1, 'L');
        $this->Ln();
        


    }

    function gerarCR() {
        $this->Ln(2);

        //legenda
        $this->SetFont('Arial','',7);
        $txt = 'CRED - Cr�ditos; CH - Carga Hor�ria; MF - M�dia Final; SF - Situa��o Final';
        $this->Cell(140,4 /*$espacamentoHorizontalGrande*/, $txt, $this->debug, 0, 'L');
        
        //CR
        $this->SetFont('Arial','B',10);
        $txt='CR: ';
        $this->Cell(20, 4 /*$espacamentoHorizontalGrande*/, $txt, 1, 0, 'C');
        $txt = number_format($this->matriculaAluno->calcularCR(), 1, ",", "");
        $this->Cell(20, 4 /*$espacamentoHorizontalGrande*/, $txt, 1, 1, 'C');
    }

    function  Footer() {
        //parent::Footer();
        //$this->gerarFooter();
        parent::rodapePadrao($this->PageNo(), '{total}');
    }

    function gerarFooter() {
        $altura = 5;
        $largura = $this->larguraMaxima/3;
        //$this->SetFont('Arial','',10);
        $this->SetY(-1 * ($this->bMargin + $altura)); //posiciona o cursor
        //acima do margem de baixo do documento
        //$this->SetFillColor(80, 80, 255);
        $this->Cell($largura, $altura, 'Coruja', $this->debug, 0, 'L');
        $this->Cell($largura, $altura, 'P�gina: '.$this->PageNo().' de {total}', $this->debug, 0, 'C');
        $data = date("d/m/Y");
        $this->Cell($largura, $altura, 'Emitido em '.$data, $this->debug, 0, 'R');

    }

    private function carregaNomeDoCurso() 
    {
        $matriculaAluno = MatriculaAluno::obterMatriculaAluno( $this->numeroMatriculaAluno );
        $curso = Curso::obterCurso( $matriculaAluno->getSiglaCurso() );

        $this->nomeDoCurso = $curso->getNomeCurso(); // TODO Arruma essa pog aqui

        /*if ($matrizId < 6){ //Matriz Antiga

            $this->nomeDoCurso = $curso->getNomeCurso(); // TODO Arruma essa pog aqui
        }
        else{ // Matriz Nova

            $this->nomeDoCurso = "Tecnologia Em An�lise E Desenvolvimento De Sistemas";

        }*/

        $this->nomeDoCurso = $curso->getNomeCurso(); // TODO Arruma essa pog aqui


        $tipoCurso = $curso->getTipoCurso();
        $this->descricaoTipoCurso = $tipoCurso->getDescricao();
    }

    private function carregarInformacaoSobreOAluno() {
        $con = BD::conectar();
        $matricula=$this->numeroMatriculaAluno;
        $query=sprintf("select P.nome, A.`nomePai`, A.`nomeMae`, P.`naturalidade`, A.`rgNumero`, "
            ."A.`rgOrgaoEmissor`,P.`dataNascimento`, A.`cpf` "
            ."from Pessoa P, Aluno A, MatriculaAluno MA "
            ."where MA.`matriculaAluno` = '%s' "
            ."and MA.`idPessoa`= A.`idPessoa` "
            ."and P.`idPessoa` = A.`idPessoa`",
            mysqli_real_escape_string($con, $matricula));
        $result=BD::mysqli_query($query,$con);
        $nome=BD::mysqli_result($result,0,0);
        $nomePai=BD::mysqli_result($result,0,1);
        $nomeMae=BD::mysqli_result($result,0,2);
        $naturalidade=BD::mysqli_result($result,0,3);
        $rgNumero=BD::mysqli_result($result,0,4);
        $rgOrgaoEmissor=BD::mysqli_result($result,0,5);
        $dataNascimento=BD::mysqli_result($result,0,6);
        $cpf=BD::mysqli_result($result,0,7);

        $this->nomeAluno = $nome;
        $this->nomeDoPaiDoAluno = $nomePai;
        $this->nomeDaMaeDoAluno = $nomeMae;
        $this->naturalidade = $naturalidade;
        $this->identidade = $rgNumero;
        $this->orgaoEmissor = $rgOrgaoEmissor;
        $this->nascimento = $dataNascimento;
        $this->cpf = $cpf;

    }
}


?>
