<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
require_once "$BASE_DIR/classes/Util.php";
require_once "$BASE_DIR/nort/classes/historicoEscolar/HistoricoEscolarPDF.php";
?>

<form id="cadastro" action="<?php echo $_SERVER['PHP_SELF'] ?>?acao=gerarPDF" method="post">
    <input type="hidden" name="matricula" value="<?php echo $matricula; ?>" />
    <fieldset>
        <legend>Selecionar Opções de Geração do Histórico</legend>

        <div style="margin-bottom: 1.5rem; margin-top: 1.5rem;">
            
            <div id="campoa" style="width: 45%;float: left; mar">
                <input type="checkbox" name="exibeComponentesCurricularesPendentes" value="SIM" checked="checked" />Exibe Disciplinas Pendentes<br/>
                <input type="checkbox" name="exibeHistoricoDeSituacaoDeMatricula" value="SIM" checked="checked" />Exibe Histórico de Situações de Matrícula<br/>
                <input type="checkbox" name="exibeListaDeDocumentosPendentes" value="SIM" checked="checked" />Exibe Documentos Pendentes<br/>
                <input type="checkbox" name="exibeObs" value="SIM" checked="checked" />Exibe observações<br/><br/>
            </div>

            <div id="campoObs" style="display:inline-block" >
                Observações<br>
                <textarea id="obs" name="obs"><?php echo HistoricoEscolarPDF::getObsHistorico($matricula) ?></textarea>
            </div>
        </div>
      
        <center><input type="submit" value="Confirmar Opções" name="EH"></center>
    </fieldset>
</form>