<?php
require_once "$BASE_DIR/classes/TempoSemanal.php";

/* 
 * Função utilizada nos casos de uso de Manter Turma, Criar Turma e Editar Turma
 */
function obterMatrizAlocacoes($siglaCurso, $idPeriodoLetivo, $periodo, $turno, $gradeHorario, $excetoATurma = NULL) {
    $con = BD::conectar();

    if ($excetoATurma == NULL) {
        $turmaFiltro = '';
    } else {
        $turmaFiltro = "and TU.`idTurma` <> " . mysqli_real_escape_string($con, $excetoATurma);
    }

    // utilizado para identificar os registros da tabela TempoSemanal de acordo com o turno
    $turnoFiltro = NULL;
    switch (strtoupper($turno)){
        case "MANHÃ":
            $horaInicioTurno = TempoSemanal::$INICIO_TURNO_MANHA;
            $horaFimTurno = TempoSemanal::$FIM_TURNO_MANHA;             
            break;
        case "TARDE":
            $horaInicioTurno = TempoSemanal::$INICIO_TURNO_TARDE;
            $horaFimTurno = TempoSemanal::$FIM_TURNO_TARDE;             
            break;
        case "NOITE":
            $horaInicioTurno = TempoSemanal::$INICIO_TURNO_NOITE;
            $horaFimTurno = TempoSemanal::$FIM_TURNO_NOITE;  
            break;
        default :
            trigger_error("Turno desconhecido '".$turno."'", E_USER_ERROR);
    }

    $query = sprintf(
        "SELECT TS.`diaSemana`, TS.`horaInicio`, TS.`horaFim`, ES.`idEspaco`, ES.`nome`, ".
        "AL.`idTurma`, TU.`siglaCurso`, TU.`siglaDisciplina`, TS.`idTempoSemanal`, ".
        "CC.`nomeDisciplina`, TU.`idPeriodoLetivo`, timediff(TS.`horaFim`,TS.`horaInicio`) as DURACAO ".

        "FROM  `Aloca` AL ".

        "inner join `Turma` TU ".
        "on AL.`idTurma` = TU.`idTurma` ".
    	"and tipoSituacaoTurma <> 'CANCELADA' and TU.turno='%s' ". // #1
        "%s ". // #2
        "and TU.`idPeriodoLetivo` = %d ". // #3
        "and TU.`gradeHorario` = '%s' ". // #4

        "inner join `ComponenteCurricular` CC ".
        "on TU.`siglaCurso` = CC.`siglaCurso` ".
        "and TU.`idMatriz` = CC.`idMatriz` ".
        "and TU.`siglaDisciplina` = CC.`siglaDisciplina` ".
        "and CC.`periodo` = %d ". // #5

        "left join `Espaco` ES ".
        "on AL.`idEspaco` = ES.`idEspaco` ".

        "right join `TempoSemanal` TS ".
        "on TS.`idTempoSemanal` = AL.`idTempoSemanal` ".

        "where ( horaInicio >= '%s' and horaFim <= '%s' ". // #6 e #7
        " or diaSemana = 'SAB') ". /* mostra o sabado independente do turno */
        " and TS.`siglaCurso` = '%s' ". //#8
        "order by diaSemana, horaInicio"
        ,mysqli_real_escape_string($con, $turno) // #1
        ,$turmaFiltro // #2
        ,mysqli_real_escape_string($con, $idPeriodoLetivo) // #3
        ,mysqli_real_escape_string($con, $gradeHorario) // #4
        ,mysqli_real_escape_string($con, $periodo) //5
        ,$horaInicioTurno // #6
        ,$horaFimTurno // #7    
        ,$siglaCurso); // #8

    $result = BD::mysqli_query( $query, $con );

    $matrizTempos = array();

    $seqTempo = NULL;
    $auxDiaSemanaAnterior = null;    
    while($umTempo = mysqli_fetch_array($result)){

        if($auxDiaSemanaAnterior != $umTempo['diaSemana']){
            $auxDiaSemanaAnterior = $umTempo['diaSemana'];
            $seqTempo = 0;
        }

        if($auxDiaSemanaAnterior == $umTempo['diaSemana']){
            $seqTempo++;
        }

        if($seqTempo == NULL){
            trigger_error("Erro ao montar dinamicamente o sequencial de cada tempo semanal", E_USER_ERROR);
        }
        $matrizTempos[$umTempo['diaSemana']][$seqTempo] = $umTempo;
    }

    return $matrizTempos;
}

function obterEspacosDisponiveis($idTempoSemanal, $idPeriodoLetivo) {
    $con = BD::conectar();

    $query = sprintf("".
        "select ".
        "    ES.`idEspaco`, ES.nome ".
        "from ".
        "Turma TU inner join Aloca AL ".
        "    on TU.`idTurma` = AL.`idTurma` ".
        "    and TU.`tipoSituacaoTurma` <> 'CANCELADA' ".
        "    and TU.`idPeriodoLetivo` = %d ".    // #1
        "right join TempoSemanal TS ".
        "    on AL.`idTempoSemanal` = TS.`idTempoSemanal` ".
        "    and TS.`idTempoSemanal` = %d ".     // #2
        "right join Espaco ES ".
        "    on AL.`idEspaco` = ES.`idEspaco` ".
        "where ".
        "    isnull(TU.`idTurma`) ".
        "order by ES.`idEspaco`, TS.idTempoSemanal "
        ,mysqli_real_escape_string($con, $idPeriodoLetivo) // #1
        ,mysqli_real_escape_string($con, $idTempoSemanal)); // #2

    $result = BD::mysqli_query($query);

    $espacosLivres = array();

    while ($espaco = mysqli_fetch_array($result)){
        array_push($espacosLivres, $espaco);
    }
    return $espacosLivres;
}
?>
