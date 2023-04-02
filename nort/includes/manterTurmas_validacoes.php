<?php
/* 
 * Aqui estão algumas das velidalções do caso de uso de Criar Turma e Editar Turma
 */

/*
Fluxo Alternativo 3
 1. Ocorreu conflito de horário em um ou mais tempos de aula. Embora o tempo de alocação seja previamente verificado, outro usuário pode simultaneamente alocar aquele tempo de aula em conflito. O sistema exibe uma mensagem indicado que houve conflito na alocação dos tempos de aula e volta ao passo 5, mantendo apenas as seleções que não conflitaram;
*/

/*
Fluxo Alternativo 6
 1. Ocorreu conflito de alocação do mesmo professor (independente da matrícula dele sendo alocada) em um ou mais tempos de aula. O sistema exibe uma mensagem indicado que houve conflito na alocação do professor e volta ao passo 5, mantendo apenas as seleções que não conflitaram;
 */
function professorEstaDisponivel($numMatriculaProfessor, $idTempoSemanal, $idPeriodoLetivo) {

    $con = BD::conectar();

    $query = sprintf("".
        ' select'.
        '    MP.`idPessoa`,'.
        '    E.`idEspaco`,'.
        '    AL.`idTempoSemanal`,'.
        '    T.`idPeriodoLetivo`,'.
        '    T.`siglaDisciplina`,'.
        '    E.nome,'.
        '    TS.`horaInicio`'.
        ' from'.
        '    MatriculaProfessor MP,'.
        '    Turma T,'.
        '    Aloca AL,'.
        '    Espaco E,'.
        '    TempoSemanal TS'.
        ' where'.
        '    MP.`matriculaProfessor` = T.`matriculaProfessor`'.
        '    and T.`idTurma` = AL.`idTurma`'.
        '    and AL.`idTempoSemanal` = TS.`idTempoSemanal`'.
        '    and AL.`idEspaco` = E.`idEspaco`'.
        '    and MP.`idPessoa` in'.
        '        ('.
        '        select MP2.`idPessoa`'.
        '        from MatriculaProfessor MP2'.
        "        where MP2.`matriculaProfessor` = '%s'". // #1
        '        )'.
        '    and AL.`idTempoSemanal` = %d'. // #2
        '    and T.`idPeriodoLetivo` = %d'. // #3
        '    and T.tipoSituacaoTurma <>\'CANCELADA\' ' .
        ' order by'.
        '    T.`matriculaProfessor`',
        mysqli_real_escape_string($con, $numMatriculaProfessor),         // #1
        mysqli_real_escape_string($con, $idTempoSemanal),   // #2
        mysqli_real_escape_string($con, $idPeriodoLetivo)); // #3

    $result = BD::mysqli_query($query);

    //    $res = mysqli_fetch_array($result);

    if (mysqli_num_rows($result) == 0){
        return true;
    } else {
        return false;
    }

}
?>
