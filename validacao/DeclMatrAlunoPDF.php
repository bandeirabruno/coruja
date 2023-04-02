<?php

//require_once "$BASE_DIR/classes/Aluno.php";
require_once "../includes/comum.php";
require_once "$BASE_DIR/classes/util/FpdfCoruja.php";
require_once "$BASE_DIR/classes/Util.php";


class DeclMatrAlunoPDF extends FpdfCoruja {

    //vari�veis do ambiente
    private $larguraMaxima; /* ajustada no construtor */
    private $tamFontValores = 11;
    private $debug = 0;
    private $dadosInfo;
    

    function __construct() {
    
       
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
        $this->gerarAssinaturaEletronica();
        $this->gerarRodapeDePagina();

        global $declaracao;

        $this->dadosInfo = $declaracao->getDados();


    }

    function gerarCabecalho() {

        $posOriginal = $this->GetY();

        $margemDoCabecalho = 32;
        $tamHorizontalDoCabecalho =  $this->larguraMaxima;
        $tamFonteGrande = 12;
        $tamFonteMedia = 9;
        $tamFontePequena = 8;

        $this->Image("../imagens/logorj.jpg",$this->larguraMaxima / 2 - 5,$this->tMargin,12);

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

        global $declaracao;

        $this->dadosInfo = $declaracao->getDados();

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

        if( $this->dadosInfo['sexo'] == "M") $sufixo="o";
        else $sufixo="a";

        $nomeCurso = Util::obterNomedoCursoPelaMatricula($this->dadosInfo['matricula']);


        $texto = "Declaramos para os devidos fins que " . $this->dadosInfo['nome'].
                ", matr�cula " . $this->dadosInfo['matricula'] . ", encontra-se regularmente " .
                "matriculad" . $sufixo . " nesta Institui��o, no " . $this->dadosInfo['periodo'] . "� " .
                "per�odo do Curso Superior de " . $nomeCurso . ", " .
                "no turno da " . mb_strtolower($this->dadosInfo['turnoIngresso'],'ISO-8859-15') . ".";

        $this->SetFont("Arial", "", 14);
        $this->moverCursorAbaixo(10);
        $this->MultiCell(180, 10, $texto);

    }

    private function gerarAssinaturaEletronica() {
        
        global $declaracao;

        $this->dadosInfo = $declaracao->getDados();

        $xInicial = $this->GetX();
        $yInicial = $this->GetY();

        $posicaoXDaLinha = $xInicial;

        $data = new DateTime( $this->dadosInfo['dataEmisao'] );

        $data->format('Y-m-d');

        $txt = 'Rio de Janeiro, ' . Util::gerarDataExtenso( $data->format('Y-m-d') ) .' : '. $data->format('H:i:s');;
        $this->Text($posicaoXDaLinha, $yInicial+20, $txt);

        //Desenha os Campos
        $alturaDaLinha = $yInicial + 40;
        $tamanhoDaLarguraDaLinha = 103;

        //Assinatura

        $assinaturaExibicao = $declaracao->getAssinaturaExib();

        $this->Text($posicaoXDaLinha, $alturaDaLinha - 4, $assinaturaExibicao);
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
