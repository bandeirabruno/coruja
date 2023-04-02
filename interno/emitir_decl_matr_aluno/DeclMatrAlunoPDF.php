<?php

//require_once "$BASE_DIR/classes/Aluno.php";
require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/util/FpdfCoruja.php";
require_once "$BASE_DIR/classes/Util.php";

class DeclMatrAlunoPDF extends FpdfCoruja {

    //vari�veis do ambiente
    private $larguraMaxima; /* ajustada no construtor */
    private $tamFontValores = 11;
    private $debug = 0;
    private $login;

    function __construct() {
        
        $this->login = $_SESSION["login"];
        parent::FpdfCoruja('P');
        $this->AliasNbPages( '{total}' );
        $this->AddPage(); //Adiciona a primeira pagina
        $this->setAlturaDoRodape(1);
        $this->bMargin = $this->bMargin - 10;

        //Ajusta as vari�veis de ambiente
        $this->larguraMaxima = $this->w - ($this->lMargin + $this->rMargin);

        $this->SetFont('Arial', '', $this->tamFontValores);

        //Desenha as partes do formul�rio
        $this->gerarCabecalho();
        $this->moverCursorAbaixo(1);
        $this->gerarCorpo();

        if($this->login->isAluno()){
            $this->gerarAssinaturaEletronica();
        }
        else{
            $this->gerarAssinatura();
        }
        


        $this->gerarRodapeDePagina();

    }

    function gerarCabecalho() {

        $posOriginal = $this->GetY();

        $margemDoCabecalho = 32;
        $tamHorizontalDoCabecalho =  $this->larguraMaxima;
        $tamFonteGrande = 12;
        $tamFonteMedia = 9;
        $tamFontePequena = 8;

        $this->Image("../../imagens/logorj.jpg",$this->larguraMaxima / 2 - 5,$this->tMargin,12);

        $this->SetX(60);
        $this->SetY(27);
        $this->SetFont('Arial','B',12);
        $txt = Config::INSTITUICAO_FILIACAO_1 . "\n" .
            Config::INSTITUICAO_FILIACAO_2 . "\n" .
            Config::INSTITUICAO_FILIACAO_3 . "\n" .
            Config::INSTITUICAO_NOME_COMPLETO . "\n" .
            Config::INSTITUICAO_NOME_CURTO;               
        $this->MultiCell($this->larguraMaxima-10,4.5,$txt, $this->debug,'C');

        $this->SetY($posOriginal);       
    }

    private function gerarCorpo() {

        $espacoEntreOsCampos = 2;
        $espacoTurno = 50;
        $espacoCurso = $this->larguraMaxima - ($espacoEntreOsCampos + $espacoTurno);

        $this->moverCursorAbaixo(80);
        $this->SetFont("Arial", "B", 18);
        $this->Text(85,$this->GetY(),"DECLARA��O",0,0,"C");

        $this->moverCursorAbaixo(1);

        global $aluno;
        global $numMatriculaAluno;
        global $periodoReferencia;
        global $matriculaAluno;
        global $curso;

        if($aluno->getSexo()=="M") $sufixo="o";
        else $sufixo="a";

        if($this->login->isAluno()){
            $nomeCurso = Util::obterNomedoCursoPelaMatricula($numMatriculaAluno);
        }
        else{
            $nomeCurso = $_REQUEST['nome_curso'];
        }

        $texto = "Declaramos para os devidos fins que " . $aluno->getNome() .
                ", matr�cula " . $numMatriculaAluno . ", encontra-se regularmente " .
                "matriculad" . $sufixo . " nesta Institui��o, no " . $periodoReferencia . "� " .
                "per�odo do Curso Superior de " . $nomeCurso . ", " .
                "no turno da " . mb_strtolower($matriculaAluno->getTurnoIngresso(),'ISO-8859-15') . ".";

        $this->SetFont("Arial", "", 14);
        $this->moverCursorAbaixo(10);
        $this->MultiCell(180, 10, $texto);

    }

    private function gerarAssinatura() {
  
        $xInicial = $this->GetX();
        $yInicial = $this->GetY();

        $posicaoXDaLinha = $xInicial;

        $txt = 'Rio de Janeiro, ' . Util::gerarDataExtenso( Util::obterDataAtual(BD::conectar()));
        $this->Text($posicaoXDaLinha, $yInicial+20, $txt);

        //Desenha os Campos
        $alturaDaLinha = $yInicial + 40;
        $tamanhoDaLarguraDaLinha = 80;

        //Secret�ria
        $this->Line($posicaoXDaLinha, $alturaDaLinha, $posicaoXDaLinha + $tamanhoDaLarguraDaLinha, $alturaDaLinha);
        $this->Text($posicaoXDaLinha + 14, $alturaDaLinha + 6, "Secret�ria Acad�mica");

    }
    private function gerarAssinaturaEletronica() {

        global $declaracao;
  
        $xInicial = $this->GetX();
        $yInicial = $this->GetY();

        $posicaoXDaLinha = $xInicial;

        $txt = 'Rio de Janeiro, ' . Util::gerarDataExtenso( Util::obterDataAtual(BD::conectar())) .' : '.$declaracao->getHoramin();
        $this->Text($posicaoXDaLinha, $yInicial+20, $txt);

        //Desenha os Campos
        $alturaDaLinha = $yInicial + 40;
        $tamanhoDaLarguraDaLinha = 103;

        //Assinatura
        $this->Text($posicaoXDaLinha, $alturaDaLinha - 4, $declaracao->getAssinaturaExib());
        $this->Line($posicaoXDaLinha, $alturaDaLinha, $posicaoXDaLinha + $tamanhoDaLarguraDaLinha, $alturaDaLinha);
        $this->Text($posicaoXDaLinha + 22, $alturaDaLinha + 6, "Assinatura Eletr�nica");


    }

    private function gerarRodapeDePagina() {
  
        $xInicial = $this->GetX();
        $yInicial = $this->GetY();

        $posicaoXDaLinha = $xInicial;

        // Identifica��o da Institui��o
        $texto = Config::INSTITUICAO_NOME_COMPLETO . "\n" .
        Config::INSTITUICAO_NOME_CURTO . "\n" .
        Config::INSTITUICAO_ENDERECO . "\n" .
        Config::INSTITUICAO_TELEFONE . "\n";
        $this->SetFont("Arial", "", "10");
        $this->SetX(0);
        $this->SetY(260);

        if($this->login->isAluno()){
            $this->Text(4, 250, "Para verificar a autenticidade desse documento, acesse: https://www.faeterj-rio.edu.br/coruja/validacao");
        }

        $this->MultiCell($this->larguraMaxima, 4, $texto, 0, 'C');

    }

    private function moverCursorAbaixo($px) {
        $this->SetY($this->GetY() + $px);
    }

    public function Footer() {
        parent::rodapePadrao($this->PageNo(), '{total}');
    }

}
?>
