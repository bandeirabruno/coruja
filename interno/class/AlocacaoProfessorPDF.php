<?php
require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/BD.php";
require_once "$BASE_DIR/baseCoruja/classes/fpdf/fpdf.php";
require_once "$BASE_DIR/classes/Util.php";

class AlocacaoProfessorPDF extends FPDF {
    private $turno;
    private $gradeHorario;
    private $siglaPeriodoLetivo;
    private $horaInicio;
    private $horaFim;
    private $diaSemana;
    private $nomeDisciplina;
    private $siglaDisciplina;
    private $periodo;
    private $siglaCurso;
    private $idPeriodoLetivo;   
   
    private $larguraMaxima;
    private $debug = 0;

    public function setTurno() {
        return $this->turno;
    }

    public function getGradeHorario() {
        return $this->gradeHorario;
    }

    public function setGradeHorario($gradeHorario) {
        $this->gradeHorario = $gradeHorario;
    }

    public function getSiglaPeriodoLetivo() {
        return $this->SiglaPeriodoLetivo;
    }

    public function setSiglaPeriodoLetivo($siglaPeriodoLetivo) {
        $this->siglaPeriodoLetivo = $siglaPeriodoLetivo;
    }


    public function setHoraInicio($horaInicio) {
        $this->horaInicio = $horaInicio;
    }
   
    public function getHoraInicio() {
        return $this->horaInicio;
    }
   
    public function setHoraFim($horaFim) {
        $this->horaFim = $horaFim;
    }
   
    public function getHoraFim() {
        return $this->horaFim;
    }

    public function setDiaSemana($diaSemana) {
        $this->diaSemana = $diaSemana;
    }
   
    public function getDiaSemana() {
        return $this->diaSemana;
    }
    public function setSiglaDisciplina($siglaDisciplina) {
        $this->nomeDisciplina = $siglaDisciplina;
    }
   
    public function getSiglaDisciplina() {
        return $this->siglaDisciplina;
    }
   
    public function setNomeDisciplina($nomeDisciplina) {
        $this->nomeDisciplina = $nomeDisciplina;
    }
   
    public function getNomeDisciplina() {
        return $this->nomeDisciplina;
    }

    public function setPeriodo($periodo) {
        $this->periodo = $periodo;
    }
   
    public function getPeriodo() {
        return $this->$periodo;
    }   
       
     public function setSiglaCurso($siglaCurso) {
        $this->siglaCurso = $siglaCurso;
    }
   
    public function getSiglaCurso() {
        return $this->$siglaCurso;
    }

     public function setIdPeriodoLetivo($idPeriodoLetivo) {
        $this->idPeriodoLetivo = $idPeriodoLetivo;
    }
   
    public function getIdPeriodoLetivo() {
        return $this->$idPeriodoLetivo;
    }


        public function obterHorarioSAB(){
        $query = sprintf("select SUBSTRING(ts.horaInicio,1,5) as horaInicio, SUBSTRING(ts.horaFim,1,5) as horaFim
        from TempoSemanal as ts left outer join (Aloca a inner join Turma t
        on a.idTurma=t.idTurma and t.idPeriodoLetivo='%s'
        inner join Espaco e on a.idEspaco = e.idEspaco and e.idEspaco='%s'
        inner join ComponenteCurricular cc on cc.siglaCurso = t.siglaCurso
        and cc.idMatriz = t.idMatriz and cc.siglaDisciplina = t.siglaDisciplina
	left outer join MatriculaProfessor mp inner JOIN Pessoa p
        ON p.idPessoa = mp.idPessoa on t.matriculaProfessor = mp.matriculaProfessor)
        on ts.idTempoSemanal=a.idTempoSemanal
        where ts.diaSemana='SAB' and ts.siglaCurso='%s' group by ts.horaInicio,ts.horaFim
        order by ts.horaInicio,ts.horaFim",
	addslashes($_REQUEST['periodo']),addslashes($_REQUEST['espaco']),addslashes($_REQUEST['curso']));
        $query= BD::mysqli_query($query);

      if(mysqli_num_rows($query) > 0){
        for($i=0;$i<mysqli_num_rows($query);$i++){
          $collection[$i]['idTempo']=$i;
          $collection[$i]['horaInicio']=BD::mysqli_result($query, $i,'horaInicio');
          $collection[$i]['horaFim']=BD::mysqli_result($query, $i,'horaFim');
        }
      }


      return $collection;

    }

    public function obterHorarios(){
        $query = sprintf("select SUBSTRING(ts.horaInicio,1,5) as horaInicio, SUBSTRING(ts.horaFim,1,5) as horaFim
        from TempoSemanal as ts left outer join (Aloca a inner join Turma t
        on a.idTurma=t.idTurma and t.idPeriodoLetivo='%s'
        inner join Espaco e on a.idEspaco = e.idEspaco and e.idEspaco='%s'
        inner join ComponenteCurricular cc on cc.siglaCurso = t.siglaCurso
        and cc.idMatriz = t.idMatriz and cc.siglaDisciplina = t.siglaDisciplina
	left outer join MatriculaProfessor mp inner JOIN Pessoa p
        ON p.idPessoa = mp.idPessoa on t.matriculaProfessor = mp.matriculaProfessor)
        on ts.idTempoSemanal=a.idTempoSemanal
        where ts.diaSemana<>'SAB' and ts.siglaCurso='%s' group by ts.horaInicio,ts.horaFim
        order by ts.horaInicio,ts.horaFim",
	addslashes($_REQUEST['periodo']),addslashes($_REQUEST['espaco']),addslashes($_REQUEST['curso']));
        
        

        $result= BD::mysqli_query($query);

      if(mysqli_num_rows($result) > 0){
        for($i=0;$i<mysqli_num_rows($result);$i++){
          $collection[$i]['idTempo']=$i;
          $collection[$i]['horaInicio']=BD::mysqli_result($result, $i,'horaInicio');
          $collection[$i]['horaFim']=BD::mysqli_result($result, $i,'horaFim');
        }
      }

      $colSab=array();$arr=array();
      $colSab=$this->obterHorarioSAB();
      $i=0;
      if(sizeof($collection)>0){
        for($i=0;$i<sizeof($collection);$i++) {
          $arr[$i]['idTempo']=$collection[$i]['idTempo'];
          $arr[$i]['horaInicio']=$collection[$i]['horaInicio'];
          $arr[$i]['horaFim']=$collection[$i]['horaFim'];
         
          $arr[$i]['horaInicioSab']=$colSab[$i]['horaInicio'];
          $arr[$i]['horaFimSab']=$colSab[$i]['horaFim'];
         

        }
      }
      
      return $arr;

    }


    /**
 * Este m�todo retorna uma cole��o de dados de grades existentes, baseado nos parametros : $siglaCurso,$idPeriodoLetivo
 * @author Vin�cius
 *
 */
public function obterGrade(){
    $query = sprintf("select
        ts.diaSemana as diaSemana,SUBSTRING(ts.horaInicio,1,5) as horaInicio,
        SUBSTRING(ts.horaFim,1,5) as horaFim, cc.siglaDisciplina as siglaDisciplina,
        SUBSTRING(p.nome,1,16) as professor,
        mp.matriculaProfessor,
        e.nome as nome
    from TempoSemanal as ts left outer join (Aloca a inner join Turma t on a.idTurma=t.idTurma and t.idPeriodoLetivo=%d and t.tipoSituacaoTurma<>'CANCELADA'
        inner join Espaco e on a.idEspaco = e.idEspaco inner join ComponenteCurricular cc
        on cc.siglaCurso = t.siglaCurso and cc.idMatriz = t.idMatriz and cc.siglaDisciplina = t.siglaDisciplina
        inner join MatriculaProfessor mp inner JOIN Pessoa p ON p.idPessoa='%s'
    and p.idPessoa = mp.idPessoa and t.matriculaProfessor = mp.matriculaProfessor)
    on ts.idTempoSemanal=a.idTempoSemanal where ts.siglaCurso='%s'
    order by ts.diaSemana,t.turno,ts.horaInicio",
    addslashes($_REQUEST['periodo']),
    addslashes($_REQUEST['professor']),
    addslashes($_REQUEST['curso']));
        
    $query= BD::mysqli_query($query);

    $arr=array();
   
    if(mysqli_num_rows($query) > 0){
        $col=$this->obterHorarios();
    
        $z=0;$collection=array();
        foreach ($col as $collection) {
        
            $arr[$z]['horaInicio']= $collection['horaInicio'];
            $arr[$z]['horaFim']=$collection['horaFim'];
            $arr[$z]['horaInicioSab']= $collection['horaInicioSab'];
            $arr[$z]['horaFimSab']=$collection['horaFimSab'];
            
            for($i=0;$i<mysqli_num_rows($query);$i++){// loop dados do sql
         
                $horaInicio=BD::mysqli_result($query, $i,'horaInicio');
                $horaFim=BD::mysqli_result($query, $i,'horaFim');

                if(($collection['horaInicio']==$horaInicio && $collection['horaFim']==$horaFim) || ($collection['horaInicioSab']==$horaInicio && $collection['horaFimSab']==$horaFim)){
                    
                    if(BD::mysqli_result($query, $i,'diaSemana')=='SEG'){
                        $arr[$z]['SEG']=BD::mysqli_result($query, $i,'siglaDisciplina').'  '.BD::mysqli_result($query, $i,'professor');
                        $arr[$z]['espacoSEG']=BD::mysqli_result($query, $i,'nome');
                    
                    }
                    
                    if(BD::mysqli_result($query, $i,'diaSemana')=='TER'){
                        $arr[$z]['TER']=BD::mysqli_result($query, $i,'siglaDisciplina').'  '.BD::mysqli_result($query, $i,'professor');
                        $arr[$z]['espacoTER']=BD::mysqli_result($query, $i,'nome');

                    }

                    if(BD::mysqli_result($query, $i,'diaSemana')=='QUA'){
                        $arr[$z]['QUA']=BD::mysqli_result($query, $i,'siglaDisciplina').'  '.BD::mysqli_result($query, $i,'professor');
                        $arr[$z]['espacoQUA']=BD::mysqli_result($query, $i,'nome');

                    }

                    if(BD::mysqli_result($query, $i,'diaSemana')=='QUI'){
                        $arr[$z]['QUI']=BD::mysqli_result($query, $i,'siglaDisciplina').'  '.BD::mysqli_result($query, $i,'professor');
                        $arr[$z]['espacoQUI']=BD::mysqli_result($query, $i,'nome');

                    }

                    if(BD::mysqli_result($query, $i,'diaSemana')=='SEX'){
                        $arr[$z]['SEX']=BD::mysqli_result($query, $i,'siglaDisciplina').'  '.BD::mysqli_result($query, $i,'professor');
                        $arr[$z]['espacoSEX']=BD::mysqli_result($query, $i,'nome');

                    }

                    if(BD::mysqli_result($query, $i,'diaSemana')=='SAB'){
                        $arr[$z]['SAB']=BD::mysqli_result($query, $i,'siglaDisciplina').'  '.BD::mysqli_result($query, $i,'professor');
                        $arr[$z]['espacoSAB']=BD::mysqli_result($query, $i,'nome');

                    }



       }
     }
     $z++;
    }
   }

	return $arr;
}

 public function Cabecalho($largura,$curso,$professor,$periodoLetivo){

        $this->setXY(9,$this->GetY()+7);
        $this->Image("../../imagens/logorj.jpg",$this->lMargin,$this->tMargin,20);

        $this->SetX(32);
        $this->SetFont('Arial','',9);
        $txt = Config::INSTITUICAO_FILIACAO_1 . "\n" .
            Config::INSTITUICAO_FILIACAO_2 . "\n" .
            Config::INSTITUICAO_FILIACAO_3 . "\n" .
            Config::INSTITUICAO_NOME_COMPLETO;
        $this->MultiCell($tamHorizontalDoCabecalho,4.0,$txt, $this->debug,'L');

        $this->setXY(9,$this->GetY()+7);
	$this->SetFont('Arial','B',12);
	$txt='Espelho de Aloca��o do Professor : '.$professor;
        $this->Cell($largura,$espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'' , 0, 'L');

        $this->setXY(9,$this->GetY()+5);
        $txt='Curso : '.$curso;
	$this->Cell($largura,$espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'' , 0, 'L');

        $this->setXY(9,$this->GetY()+5);
        $txt='Per�odo Letivo : '.$periodoLetivo;
	$this->Cell($largura,$espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'' , 0, 'L');


}

public function geraGrade($largura,$siglaCurso,$espaco,$periodoLetivo){
	
	$collectionDados =$this->obterGrade();
        	
	$this->larguraMaxima=282;
	 
	$cumprimento2 = 25.7;
	$cumprimento1 = 15.4;

	$fontePequena = 6;
	$espacamentoHorizontalPequeno = 5;
	$fonteMedia = 9;
	$espacamentoHorizontalGrande = 6;
        $this->tMargin=2;
        $this->bMargin=1;
        $this->AliasNbPages( '{total}' );
        $this->AddPage('P','A4');

      
         $this->Cabecalho($largura, $siglaCurso, $espaco, $periodoLetivo);
	
	$cont=0;$a=0;$b=0;
			
         $this->setXY(9, $this->GetY()+3);
         $this->SetFont('Arial', 'B', $fonteMedia);

   $txt='HOR�RIO';
   $this->Cell($cumprimento1, $espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'LTRB', 0, 'C');

   $txt='SEG';
   $this->Cell($cumprimento2, $espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'LTRB', 0, 'C');

   $txt='TER';
   $this->Cell($cumprimento2, $espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'LTRB', 0, 'C');

   $txt='QUA';
   $this->Cell($cumprimento2, $espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'LTRB', 0, 'C');

   $txt='QUI';
   $this->Cell($cumprimento2, $espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'LTRB', 0, 'C');

   $txt='SEX';
   $this->Cell($cumprimento2, $espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'LTRB', 0, 'C');

   $txt='---';
   $this->Cell($cumprimento1, $espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'LTRB', 0, 'C');

   $txt='SAB';
   $this->Cell($cumprimento2, $espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'LTRB', 0, 'C');

   foreach ($collectionDados as $key => $collectionDados) {

       $this->setXY(9, $this->GetY() + 5);
            $this->SetFont('Arial', '', $fontePequena);
            $txt = $collectionDados['horaInicio'].' - '.$collectionDados['horaFim'];
            $this->Cell($cumprimento1, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LR', 0, 'C');

            $txt = $collectionDados['SEG'];
            $this->Cell($cumprimento2, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LR', 0, 'C');

            $txt = $collectionDados['TER'];
            $this->Cell($cumprimento2, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LR', 0, 'C');

            $txt = $collectionDados['QUA'];
            $this->Cell($cumprimento2, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LR', 0, 'C');

            $txt = $collectionDados['QUI'];
            $this->Cell($cumprimento2, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LR', 0, 'C');

            $txt = $collectionDados['SEX'];
            $this->Cell($cumprimento2, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LR', 0, 'C');

            $txt = $collectionDados['horaInicioSab'].' - '.$collectionDados['horaFimSab'];

            $this->Cell($cumprimento1, $espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'LTR', 0, 'C');


            $txt = $collectionDados['SAB'];
            $this->Cell($cumprimento2, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LR', 0, 'C');

            $this->setXY(9, $this->GetY() + 5);
            $this->SetFont('Arial', '', $fontePequena);
            $txt = '';
            $this->Cell($cumprimento1, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LRB', 0, 'C');

            $txt = $collectionDados['espacoSEG'];
            $this->Cell($cumprimento2, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LRB', 0, 'C');

            $txt = $collectionDados['espacoTER'];
            $this->Cell($cumprimento2, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LRB', 0, 'C');

            $txt = $collectionDados['espacoQUA'];
            $this->Cell($cumprimento2, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LRB', 0, 'C');

            $txt = $collectionDados['espacoQUI'];
            $this->Cell($cumprimento2, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LRB', 0, 'C');

            $txt = $collectionDados['espacoSEX'];
            $this->Cell($cumprimento2, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LRB', 0, 'C');

            $txt ='';
            $this->Cell($cumprimento1, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LRB', 0, 'C');


            $txt = $collectionDados['espacoSAB'];
            $this->Cell($cumprimento2, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LRB', 0, 'C');
        }

            $this->rodape(50,87, 40,$this->GetY()+65);
	
}//fim m�todo

public function rodape($c1,$c2,$c3,$c4){
	$this->setXY(9,$c4);
	$this->SetFont('Arial','',12);
	$this->setX(9);
	$txt='Coruja';
	$this->Cell($c1,$espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'' , 0, 'L');

        $txt="Emitido em " . date('d/m/Y');
	$this->Cell($c2,$espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'' , 0, 'C');

        $txt="P�gina " . $this->PageNo().' de {total}';
	$this->Cell($c1,$espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'' , 0, 'R');
	
}
 
}
?>