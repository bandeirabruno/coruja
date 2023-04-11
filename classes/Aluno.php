<?php
/**
 * Descri��o da classe Aluno
 *
 * Utilizada para representar objetos Alunos
 */
require_once "$BASE_DIR/classes/Pessoa.php";
require_once "$BASE_DIR/classes/Util.php";

class Aluno extends Pessoa {

    private $nomeMae;
    private $rgMae;
    private $nomePai;
    private $rgPai;
    private $rgNumero;
    private $rgDataEmissao;
    private $rgOrgaoEmissor;
    private $cpf;
    private $cpfProprio;
    private $certidaoNascimentoNumero;
    private $certidaoNascimentoLivro;
    private $certidaoNascimentoFolha;
    private $certidaoNascimentoCidade;
    private $certidaoNascimentoSubdistrito;
    private $certidaoNascimentoUF;
    private $certidaoCasamentoNumero;
    private $certidaoCasamentoLivro;
    private $certidaoCasamentoFolha;
    private $certidaoCasamentoCidade;
    private $certidaoCasamentoSubdistrito;
    private $certidaoCasamentoUF;
    private $estabCursoOrigem;
    private $estabCursoOrigemCidade;
    private $estabCursoOrigemUF;
    private $cursoOrigemAnoConclusao;
    private $modalidadeCursoOrigem;
    private $ctps;
    private $corRaca;
    private $estadoCivil;
    private $deficienciaVisual;
    private $deficienciaMotora;
    private $deficienciaAuditiva;
    private $deficienciaMental;
    private $responsavelLegal;
    private $rgResponsavel;
    private $tituloEleitorNumero;
    private $tituloEleitorData;

    private $tituloEleitorZona;
    private $tituloEleitorSecao;
    private $certificadoAlistamentoMilitarNumero;
    private $certificadoAlistamentoMilitarSerie;
    private $certificadoAlistamentoMilitarData;
    private $certificadoAlistamentoMilitarRM;
    private $certificadoAlistamentoMilitarCSM;
    private $certificadoReservistaNumero;
    private $certificadoReservistaSerie;
    private $certificadoReservistaData;
    private $certificadoReservistaCAT;
    private $certificadoReservistaRM;
    private $certificadoReservistaCSM;


    /* M�todos de manipula��o de dados */

    protected function carregaDadosAluno($idPessoa) {
        $con = BD::conectar();
        $query=sprintf("SELECT `idPessoa`, `nomeMae`, `rgMae`, `nomePai`, `rgPai`, ".
                "`rgNumero`, `rgDataEmissao`, `rgOrgaoEmissor`, `cpf`, `cpfProprio`, ".
                "`certidaoNascimentoNumero`, `certidaoNascimentoLivro`, ".
                "`certidaoNascimentoFolha`, `certidaoNascimentoCidade`, ".
                "`certidaoNascimentoSubdistrito`, `certidaoNascimentoUF`, ".
                "`certidaoCasamentoNumero`, `certidaoCasamentoLivro`, ".
                "`certidaoCasamentoFolha`, `certidaoCasamentoCidade`, ".
                "`certidaoCasamentoSubdistrito`, `certidaoCasamentoUF`, ".
                "`estabCursoOrigem`, `estabCursoOrigemCidade`, `estabCursoOrigemUF`, ".
                "`cursoOrigemAnoConclusao`, `modalidadeCursoOrigem`, `ctps`, `corRaca`, `estadoCivil`, ".
                "`deficienciaVisual`, `deficienciaMotora`, `deficienciaAuditiva`, ".
                "`deficienciaMental`, `responsavelLegal`, `rgResponsavel`, ".
                "`tituloEleitorNumero`, `tituloEleitorData`, `tituloEleitorZona`, ".
                "`tituloEleitorSecao`, `certificadoAlistamentoMilitarNumero`, ".
                "`certificadoAlistamentoMilitarSerie`, `certificadoAlistamentoMilitarData`, ".
                "`certificadoAlistamentoMilitarRM`, `certificadoAlistamentoMilitarCSM`, ".
                "`certificadoReservistaNumero`, `certificadoReservistaSerie`, ".
                "`certificadoReservistaData`, `certificadoReservistaCAT`, ".
                "`certificadoReservistaRM`, `certificadoReservistaCSM` ".
                "FROM `Aluno` ".
                "WHERE `idPessoa` = %s ",mysqli_real_escape_string($con, $idPessoa));
        $result=BD::mysqli_query($query,$con);
        while( $resAluno = mysqli_fetch_array($result) ) {

            $this->setCertidaoCasamentoCidade($resAluno['certidaoCasamentoCidade']);
            $this->setCertidaoCasamentoFolha($resAluno['certidaoCasamentoFolha']);
            $this->setCertidaoCasamentoLivro($resAluno['certidaoCasamentoLivro']);
            $this->setCertidaoCasamentoNumero($resAluno['certidaoCasamentoNumero']);
            $this->setCertidaoCasamentoSubdistrito($resAluno['certidaoCasamentoSubdistrito']);
            $this->setCertidaoCasamentoUF($resAluno['certidaoCasamentoUF']);
            $this->setCertidaoNascimentoCidade($resAluno['certidaoNascimentoCidade']);
            $this->setCertidaoNascimentoFolha($resAluno['certidaoNascimentoFolha']);
            $this->setCertidaoNascimentoLivro($resAluno['certidaoNascimentoLivro']);
            $this->setCertidaoNascimentoNumero($resAluno['certidaoNascimentoNumero']);
            $this->setCertidaoNascimentoSubdistrito($resAluno['certidaoNascimentoSubdistrito']);
            $this->setCertidaoNascimentoUF($resAluno['certidaoNascimentoUF']);
            $this->setCertificadoAlistamentoMilitarCSM($resAluno['certificadoAlistamentoMilitarCSM']);
            $this->setCertificadoAlistamentoMilitarData($resAluno['certificadoAlistamentoMilitarData']);
            $this->setCertificadoAlistamentoMilitarNumero($resAluno['certificadoAlistamentoMilitarNumero']);
            $this->setCertificadoAlistamentoMilitarRM($resAluno['certificadoAlistamentoMilitarRM']);
            $this->setCertificadoAlistamentoMilitarSerie($resAluno['certificadoAlistamentoMilitarSerie']);
            $this->setCertificadoReservistaCAT($resAluno['certificadoReservistaCAT']);
            $this->setCertificadoReservistaCSM($resAluno['certificadoReservistaCSM']);
            $this->setCertificadoReservistaData($resAluno['certificadoReservistaData']);
            $this->setCertificadoReservistaNumero($resAluno['certificadoReservistaNumero']);
            $this->setCertificadoReservistaRM($resAluno['certificadoReservistaRM']);
            $this->setCertificadoReservistaSerie($resAluno['certificadoReservistaSerie']);
            $this->setCorRaca($resAluno['corRaca']);
            $this->setCpf($resAluno['cpf']);
            $this->setCpfProprio($resAluno['cpfProprio']);
            $this->setCtps($resAluno['ctps']);
            $this->setCursoOrigemAnoConclusao($resAluno['cursoOrigemAnoConclusao']);
            $this->setDeficienciaAuditiva($resAluno['deficienciaAuditiva']);
            $this->setDeficienciaMental($resAluno['deficienciaMental']);
            $this->setDeficienciaMotora($resAluno['deficienciaMotora']);
            $this->setDeficienciaVisual($resAluno['deficienciaVisual']);
            $this->setEstabCursoOrigem($resAluno['estabCursoOrigem']);
            $this->setEstabCursoOrigemCidade( $resAluno['estabCursoOrigemCidade'] );
            $this->setEstabCursoOrigemUF($resAluno['estabCursoOrigemUF']);
            $this->setEstadoCivil($resAluno['estadoCivil']);
            $this->setIdPessoa($resAluno['idPessoa']);
            $this->setModalidadeCursoOrigem($resAluno['modalidadeCursoOrigem']);
            $this->setNomeMae($resAluno['nomeMae']);
            $this->setNomePai($resAluno['nomePai']);
            $this->setResponsavelLegal($resAluno['responsavelLegal']);
            $this->setRgDataEmissao($resAluno['rgDataEmissao']);
            $this->setRgMae($resAluno['rgMae']);
            $this->setRgNumero($resAluno['rgNumero']);
            $this->setRgOrgaoEmissor($resAluno['rgOrgaoEmissor']);
            $this->setRgPai($resAluno['rgPai']);
            $this->setRgResponsavel($resAluno['rgResponsavel']);
            $this->setTituloEleitorData($resAluno['tituloEleitorData']);
            $this->setTituloEleitorNumero($resAluno['tituloEleitorNumero']);
            $this->setTituloEleitorSecao($resAluno['tituloEleitorSecao']);
            $this->setTituloEleitorZona($resAluno['tituloEleitorZona']);
        }
    }

        /*
     * Casos de Uso: UC02.01.00
     */
    public static function getAlunoByIdPessoa($idPessoa) {

        $aluno = new Aluno();
        //� necess�rio manter esta ordem para carregar os dados
        $aluno->carregaDadosAluno($idPessoa); 
        $aluno->carregaDadosPessoa($idPessoa);

        return $aluno;

    }

    /* Obtem um objeto aluno pelo numero de sua matr�cula
     *
     * Casos de uso:
     *      UC01.02.00 - Hist�rico Escolar
     *      UC01.09.00 - Emitir Ficha de Matr�cula
     *
     * @author: Marcelo Atie
     * @result: objeto aluno
     */
    public static function getAlunoByNumMatricula($numMatricula) {
        //encontra a Pessoa propietaria da matricula
        $con = BD::conectar();
        $query=sprintf("SELECT `idPessoa` ".
                "FROM `MatriculaAluno` ".
                "WHERE `matriculaAluno` = %s ",mysqli_real_escape_string($con, $numMatricula));
        $result=BD::mysqli_query($query,$con);

        $idPessoa = null;

        while( $resMatricula = mysqli_fetch_array($result) ) {
            $idPessoa = $resMatricula['idPessoa'];
        }

        //retorna o Aluno completo
        $aluno = new Aluno();
        //� necess�rio manter esta ordem para carregar os dados
        $aluno->carregaDadosAluno($idPessoa);
        $aluno->carregaDadosPessoa($idPessoa);

        return $aluno;
    }

    public function getMatricula() {
        return $this->matricula;
    }

    public function setMatricula($matricula) {
        $this->matricula = $matricula;
    }

    public function getAnoIngresso() {
        return $this->anoIngresso;
    }

    public function setAnoIngresso($anoIngresso) {
        $this->anoIngresso = $anoIngresso;
    }

    public function getPeriodoIngresso() {
        return $this->periodoIngresso;
    }

    public function setPeriodoIngresso($periodoIngresso) {
        $this->periodoIngresso = $periodoIngresso;
    }

    public function getSituacao() {
        return $this->situacao;
    }

    public function setSituacao($situacao) {
        $this->situacao = $situacao;
    }

    public function getNomeMae() {
        return $this->nomeMae;
    }

    public function setNomeMae($nomeMae) {
        $this->nomeMae = $nomeMae;
    }

    public function getRgMae() {
        return $this->rgMae;
    }

    public function setRgMae($rgMae) {
        $this->rgMae = $rgMae;
    }

    public function getNomePai() {
        return $this->nomePai;
    }

    public function setNomePai($nomePai) {
        $this->nomePai = $nomePai;
    }

    public function getRgPai() {
        return $this->rgPai;
    }

    public function setRgPai($rgPai) {
        $this->rgPai = $rgPai;
    }

    public function getRgNumero() {
        return $this->rgNumero;
    }

    public function setRgNumero($rgNumero) {
        $this->rgNumero = $rgNumero;
    }

    public function getRgDataEmissao() {
        return $this->rgDataEmissao;
    }

    public function setRgDataEmissao($rgDataEmissao) {
        $this->rgDataEmissao = $rgDataEmissao;
    }

    public function getRgOrgaoEmissor() {
        return $this->rgOrgaoEmissor;
    }

    public function setRgOrgaoEmissor($rgOrgaoEmissor) {
        $this->rgOrgaoEmissor = $rgOrgaoEmissor;
    }

    public function getCpf() {
        return $this->cpf;
    }

    public function setCpf($cpf) {
        $this->cpf = $cpf;
    }

    public function getCpfProprio() {
        return $this->cpfProprio;
    }

    public function setCpfProprio($cpfProprio) {
        $this->cpfProprio = $cpfProprio;
    }

    public function getCertidaoNascimentoNumero() {
        return $this->certidaoNascimentoNumero;
    }

    public function setCertidaoNascimentoNumero($certidaoNascimentoNumero) {
        $this->certidaoNascimentoNumero = $certidaoNascimentoNumero;
    }

    public function getCertidaoNascimentoLivro() {
        return $this->certidaoNascimentoLivro;
    }

    public function setCertidaoNascimentoLivro($certidaoNascimentoLivro) {
        $this->certidaoNascimentoLivro = $certidaoNascimentoLivro;
    }

    public function getCertidaoNascimentoFolha() {
        return $this->certidaoNascimentoFolha;
    }

    public function setCertidaoNascimentoFolha($certidaoNascimentoFolha) {
        $this->certidaoNascimentoFolha = $certidaoNascimentoFolha;
    }

    public function getCertidaoNascimentoCidade() {
        return $this->certidaoNascimentoCidade;
    }

    public function setCertidaoNascimentoCidade($certidaoNascimentoCidade) {
        $this->certidaoNascimentoCidade = $certidaoNascimentoCidade;
    }

    public function getCertidaoNascimentoSubdistrito() {
        return $this->certidaoNascimentoSubdistrito;
    }

    public function setCertidaoNascimentoSubdistrito($certidaoNascimentoSubdistrito) {
        $this->certidaoNascimentoSubdistrito = $certidaoNascimentoSubdistrito;
    }

    public function getCertidaoNascimentoUF() {
        return $this->certidaoNascimentoUF;
    }

    public function setCertidaoNascimentoUF($certidaoNascimentoUF) {
        $this->certidaoNascimentoUF = $certidaoNascimentoUF;
    }

    public function getCertidaoCasamentoNumero() {
        return $this->certidaoCasamentoNumero;
    }

    public function setCertidaoCasamentoNumero($certidaoCasamentoNumero) {
        $this->certidaoCasamentoNumero = $certidaoCasamentoNumero;
    }

    public function getCertidaoCasamentoLivro() {
        return $this->certidaoCasamentoLivro;
    }

    public function setCertidaoCasamentoLivro($certidaoCasamentoLivro) {
        $this->certidaoCasamentoLivro = $certidaoCasamentoLivro;
    }

    public function getCertidaoCasamentoFolha() {
        return $this->certidaoCasamentoFolha;
    }

    public function setCertidaoCasamentoFolha($certidaoCasamentoFolha) {
        $this->certidaoCasamentoFolha = $certidaoCasamentoFolha;
    }

    public function getCertidaoCasamentoCidade() {
        return $this->certidaoCasamentoCidade;
    }

    public function setCertidaoCasamentoCidade($certidaoCasamentoCidade) {
        $this->certidaoCasamentoCidade = $certidaoCasamentoCidade;
    }

    public function getCertidaoCasamentoSubdistrito() {
        return $this->certidaoCasamentoSubdistrito;
    }

    public function setCertidaoCasamentoSubdistrito($certidaoCasamentoSubdistrito) {
        $this->certidaoCasamentoSubdistrito = $certidaoCasamentoSubdistrito;
    }

    public function getCertidaoCasamentoUF() {
        return $this->certidaoCasamentoUF;
    }

    public function setCertidaoCasamentoUF($certidaoCasamentoUF) {
        $this->certidaoCasamentoUF = $certidaoCasamentoUF;
    }

    public function getEstabCursoOrigem() {
        return $this->estabCursoOrigem;
    }

    public function setEstabCursoOrigem($estabCursoOrigem) {
        $this->estabCursoOrigem = $estabCursoOrigem;
    }

    public function getEstabCursoOrigemCidade() {
        return $this->estabCursoOrigemCidade;
    }

    public function setEstabCursoOrigemCidade($estabCursoOrigemCidade) {
        $this->estabCursoOrigemCidade = $estabCursoOrigemCidade;
    }

    public function getEstabCursoOrigemUF() {
        return $this->estabCursoOrigemUF;
    }

    public function setEstabCursoOrigemUF($estabCursoOrigemUF) {
        $this->estabCursoOrigemUF = $estabCursoOrigemUF;
    }

    public function getCursoOrigemAnoConclusao() {
        return $this->cursoOrigemAnoConclusao;
    }

    public function setCursoOrigemAnoConclusao($cursoOrigemAnoConclusao) {
        $this->cursoOrigemAnoConclusao = $cursoOrigemAnoConclusao;
    }

    public function getModalidadeCursoOrigem() {
        return $this->modalidadeCursoOrigem;
    }

    public function setModalidadeCursoOrigem($modalidadeCursoOrigem) {
        $this->modalidadeCursoOrigem = $modalidadeCursoOrigem;
    }

    public function getCtps() {
        return $this->ctps;
    }

    public function setCtps($ctps) {
        $this->ctps = $ctps;
    }

    public function getCorRaca() {
        return $this->corRaca;
    }

    public function setCorRaca($corRaca) {
        $this->corRaca = $corRaca;
    }

    public function getEstadoCivil() {
        return $this->estadoCivil;
    }

    public function setEstadoCivil($estadoCivil) {
        $this->estadoCivil = $estadoCivil;
    }

    public function getDeficienciaVisual() {
        return $this->deficienciaVisual;
    }

    public function setDeficienciaVisual($deficienciaVisual) {
        $this->deficienciaVisual = $deficienciaVisual;
    }

    public function getDeficienciaMotora() {
        return $this->deficienciaMotora;
    }

    public function setDeficienciaMotora($deficienciaMotora) {
        $this->deficienciaMotora = $deficienciaMotora;
    }

    public function getDeficienciaAuditiva() {
        return $this->deficienciaAuditiva;
    }

    public function setDeficienciaAuditiva($deficienciaAuditiva) {
        $this->deficienciaAuditiva = $deficienciaAuditiva;
    }

    public function getDeficienciaMental() {
        return $this->deficienciaMental;
    }

    public function setDeficienciaMental($deficienciaMental) {
        $this->deficienciaMental = $deficienciaMental;
    }

    public function getResponsavelLegal() {
        return $this->responsavelLegal;
    }

    public function setResponsavelLegal($responsavelLegal) {
        $this->responsavelLegal = $responsavelLegal;
    }

    public function getRgResponsavel() {
        return $this->rgResponsavel;
    }

    public function setRgResponsavel($rgResponsavel) {
        $this->rgResponsavel = $rgResponsavel;
    }

    public function getTituloEleitorNumero() {
        return $this->tituloEleitorNumero;
    }

    public function setTituloEleitorNumero($tituloEleitorNumero) {
        $this->tituloEleitorNumero = $tituloEleitorNumero;
    }

    public function getTituloEleitorData() {
        return $this->tituloEleitorData;
    }

    public function setTituloEleitorData($tituloEleitorData) {
        $this->tituloEleitorData = $tituloEleitorData;
    }

    public function getTituloEleitorZona() {
        return $this->tituloEleitorZona;
    }

    public function setTituloEleitorZona($tituloEleitorZona) {
        $this->tituloEleitorZona = $tituloEleitorZona;
    }

    public function getTituloEleitorSecao() {
        return $this->tituloEleitorSecao;
    }

    public function setTituloEleitorSecao($tituloEleitorSecao) {
        $this->tituloEleitorSecao = $tituloEleitorSecao;
    }

    public function getCertificadoAlistamentoMilitarNumero() {
        return $this->certificadoAlistamentoMilitarNumero;
    }

    public function setCertificadoAlistamentoMilitarNumero($certificadoAlistamentoMilitarNumero) {
        $this->certificadoAlistamentoMilitarNumero = $certificadoAlistamentoMilitarNumero;
    }

    public function getCertificadoAlistamentoMilitarSerie() {
        return $this->certificadoAlistamentoMilitarSerie;
    }

    public function setCertificadoAlistamentoMilitarSerie($certificadoAlistamentoMilitarSerie) {
        $this->certificadoAlistamentoMilitarSerie = $certificadoAlistamentoMilitarSerie;
    }

    public function getCertificadoAlistamentoMilitarData() {
        return $this->certificadoAlistamentoMilitarData;
    }

    public function setCertificadoAlistamentoMilitarData($certificadoAlistamentoMilitarData) {
        $this->certificadoAlistamentoMilitarData = $certificadoAlistamentoMilitarData;
    }

    public function getCertificadoAlistamentoMilitarRM() {
        return $this->certificadoAlistamentoMilitarRM;
    }

    public function setCertificadoAlistamentoMilitarRM($certificadoAlistamentoMilitarRM) {
        $this->certificadoAlistamentoMilitarRM = $certificadoAlistamentoMilitarRM;
    }

    public function getCertificadoAlistamentoMilitarCSM() {
        return $this->certificadoAlistamentoMilitarCSM;
    }

    public function setCertificadoAlistamentoMilitarCSM($certificadoAlistamentoMilitarCSM) {
        $this->certificadoAlistamentoMilitarCSM = $certificadoAlistamentoMilitarCSM;
    }

    public function getCertificadoReservistaNumero() {
        return $this->certificadoReservistaNumero;
    }

    public function setCertificadoReservistaNumero($certificadoReservistaNumero) {
        $this->certificadoReservistaNumero = $certificadoReservistaNumero;
    }

    public function getCertificadoReservistaSerie() {
        return $this->certificadoReservistaSerie;
    }

    public function setCertificadoReservistaSerie($certificadoReservistaSerie) {
        $this->certificadoReservistaSerie = $certificadoReservistaSerie;
    }

    public function getCertificadoReservistaData() {
        return $this->certificadoReservistaData;
    }

    public function setCertificadoReservistaData($certificadoReservistaData) {
        $this->certificadoReservistaData = $certificadoReservistaData;
    }

    public function getCertificadoReservistaCAT() {
        return $this->certificadoReservistaCAT;
    }

    public function setCertificadoReservistaCAT($certificadoReservistaCAT) {
        $this->certificadoReservistaCAT = $certificadoReservistaCAT;
    }

    public function getCertificadoReservistaRM() {
        return $this->certificadoReservistaRM;
    }

    public function setCertificadoReservistaRM($certificadoReservistaRM) {
        $this->certificadoReservistaRM = $certificadoReservistaRM;
    }

    public function getCertificadoReservistaCSM() {
        return $this->certificadoReservistaCSM;
    }

    public function setCertificadoReservistaCSM($certificadoReservistaCSM) {
        $this->certificadoReservistaCSM = $certificadoReservistaCSM;
    }

    /***
     * Cria novo registro: Aluno
     *
     * @param idPessoa
     * @param nomeMae
     * @param rgMae
     * @param nomePai
     * @param rgPai
     * @param rgNumero
     * @param rgDataEmissao
     * @param rgOrgaoEmissor
     * @param cpf
     * @param cpfProprio
     * @param certidaoNascimentoNumero
     * @param certidaoNascimentoLivro
     * @param certidaoNascimentoFolha
     * @param certidaoNascimentoCidade
     * @param certidaoNascimentoSubdistrito
     * @param certidaoNascimentoUF
     * @param certidaoCasamentoNumero
     * @param certidaoCasamentoLivro
     * @param certidaoCasamentoFolha
     * @param certidaoCasamentoCidade
     * @param certidaoCasamentoSubdistrito
     * @param certidaoCasamentoUF
     * @param estabCursoOrigem
     * @param estabCursoOrigemUF
     * @param cursoOrigemAnoConclusao
     * @param modalidadeCursoOrigem
     * @param ctps
     * @param corRaca
     * @param estadoCivil
     * @param deficienciaVisual
     * @param deficienciaMotora
     * @param deficienciaAuditiva
     * @param deficienciaMental
     * @param responsavelLegal
     * @param rgResponsavel
     * @param tituloEleitorNumero
     * @param tituloEleitorData
     * @param tituloEleitorZona
     * @param tituloEleitorSecao
     * @param certificadoAlistamentoMilitarNumero
     * @param certificadoAlistamentoMilitarSerie
     * @param certificadoAlistamentoMilitarData
     * @param certificadoAlistamentoMilitarRM
     * @param certificadoAlistamentoMilitarCSM
     * @param certificadoReservistaNumero
     * @param certificadoReservistaSerie
     * @param certificadoReservistaData
     * @param certificadoReservistaCAT
     * @param certificadoReservistaRM
     * @param certificadoReservistaCSM
     * @result void
     **/
    public static function inserirAluno( $idPessoa, $nomeMae, $rgMae, $nomePai, $rgPai, $rgNumero,
        $rgDataEmissao, $rgOrgaoEmissor, $cpf, $cpfProprio, $certidaoNascimentoNumero,
        $certidaoNascimentoLivro, $certidaoNascimentoFolha, $certidaoNascimentoCidade,
        $certidaoNascimentoSubdistrito, $certidaoNascimentoUF, $certidaoCasamentoNumero,
        $certidaoCasamentoLivro, $certidaoCasamentoFolha, $certidaoCasamentoCidade,
        $certidaoCasamentoSubdistrito, $certidaoCasamentoUF, $estabCursoOrigem,  $estabCursoOrigemCidade, $estabCursoOrigemUF,
        $cursoOrigemAnoConclusao, $modalidadeCursoOrigem, $ctps, $corRaca, $estadoCivil, $deficienciaVisual,
        $deficienciaMotora, $deficienciaAuditiva, $deficienciaMental, $responsavelLegal, $rgResponsavel,
        $tituloEleitorNumero, $tituloEleitorData, $tituloEleitorZona, $tituloEleitorSecao,
        $certificadoAlistamentoMilitarNumero, $certificadoAlistamentoMilitarSerie,
        $certificadoAlistamentoMilitarData, $certificadoAlistamentoMilitarRM,
        $certificadoAlistamentoMilitarCSM, $certificadoReservistaNumero, $certificadoReservistaSerie,
        $certificadoReservistaData, $certificadoReservistaCAT, $certificadoReservistaRM,
        $certificadoReservistaCSM,
        $con=null ) {

        if($con==null) $con = BD::conectar();
        $query=sprintf("INSERT INTO `Aluno` (`idPessoa`, `nomeMae`, `rgMae`, `nomePai`, `rgPai`, `rgNumero`, " .
            "`rgDataEmissao`, `rgOrgaoEmissor`, `cpf`, `cpfProprio`, `certidaoNascimentoNumero`, " .
            "`certidaoNascimentoLivro`, `certidaoNascimentoFolha`, `certidaoNascimentoCidade`, " .
            "`certidaoNascimentoSubdistrito`, `certidaoNascimentoUF`, `certidaoCasamentoNumero`, " .
            "`certidaoCasamentoLivro`, `certidaoCasamentoFolha`, `certidaoCasamentoCidade`, " .
            "`certidaoCasamentoSubdistrito`, `certidaoCasamentoUF`, `estabCursoOrigem`, `estabCursoOrigemCidade`, `estabCursoOrigemUF`, " .
            "`cursoOrigemAnoConclusao`, `modalidadeCursoOrigem`, `ctps`, `corRaca`, `estadoCivil`, " .
            "`deficienciaVisual`, `deficienciaMotora`, `deficienciaAuditiva`, `deficienciaMental`, " .
            "`responsavelLegal`, `rgResponsavel`, `tituloEleitorNumero`, `tituloEleitorData`, " .
            "`tituloEleitorZona`, `tituloEleitorSecao`, `certificadoAlistamentoMilitarNumero`, " .
            "`certificadoAlistamentoMilitarSerie`, `certificadoAlistamentoMilitarData`, " .
            "`certificadoAlistamentoMilitarRM`, `certificadoAlistamentoMilitarCSM`, " .
            "`certificadoReservistaNumero`, `certificadoReservistaSerie`, `certificadoReservistaData`, " .
            "`certificadoReservistaCAT`, `certificadoReservistaRM`, `certificadoReservistaCSM`) " .
            "VALUES (%d,'%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s'," .
            "'%s','%s','%s','%s','%s',%d,'%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',%s,'%s','%s','%s','%s'," .
            "%s,'%s','%s','%s','%s',%s,'%s','%s','%s')",
            $idPessoa,
            mysqli_real_escape_string($con, $nomeMae),
            mysqli_real_escape_string($con, $rgMae),
            mysqli_real_escape_string($con, $nomePai),
            mysqli_real_escape_string($con, $rgPai),
            mysqli_real_escape_string($con, $rgNumero),
            mysqli_real_escape_string($con, $rgDataEmissao),
            mysqli_real_escape_string($con, $rgOrgaoEmissor),
            mysqli_real_escape_string($con, $cpf),
            mysqli_real_escape_string($con, $cpfProprio),
            mysqli_real_escape_string($con, $certidaoNascimentoNumero),
            mysqli_real_escape_string($con, $certidaoNascimentoLivro),
            mysqli_real_escape_string($con, $certidaoNascimentoFolha),
            mysqli_real_escape_string($con, $certidaoNascimentoCidade),
            mysqli_real_escape_string($con, $certidaoNascimentoSubdistrito),
            mysqli_real_escape_string($con, $certidaoNascimentoUF),
            mysqli_real_escape_string($con, $certidaoCasamentoNumero),
            mysqli_real_escape_string($con, $certidaoCasamentoLivro),
            mysqli_real_escape_string($con, $certidaoCasamentoFolha),
            mysqli_real_escape_string($con, $certidaoCasamentoCidade),
            mysqli_real_escape_string($con, $certidaoCasamentoSubdistrito),
            mysqli_real_escape_string($con, $certidaoCasamentoUF),
            mysqli_real_escape_string($con, $estabCursoOrigem),
            mysqli_real_escape_string($con, $estabCursoOrigemCidade),
            mysqli_real_escape_string($con, $estabCursoOrigemUF),
            $cursoOrigemAnoConclusao,
            mysqli_real_escape_string($con, $modalidadeCursoOrigem),
            mysqli_real_escape_string($con, $ctps),
            mysqli_real_escape_string($con, $corRaca),
            mysqli_real_escape_string($con, $estadoCivil),
            mysqli_real_escape_string($con, $deficienciaVisual),
            mysqli_real_escape_string($con, $deficienciaMotora),
            mysqli_real_escape_string($con, $deficienciaAuditiva),
            mysqli_real_escape_string($con, $deficienciaMental),
            mysqli_real_escape_string($con, $responsavelLegal),
            mysqli_real_escape_string($con, $rgResponsavel),
            mysqli_real_escape_string($con, $tituloEleitorNumero),
            Util::tratarDataNullSQL($tituloEleitorData),
            mysqli_real_escape_string($con, $tituloEleitorZona),
            mysqli_real_escape_string($con, $tituloEleitorSecao),
            mysqli_real_escape_string($con, $certificadoAlistamentoMilitarNumero),
            mysqli_real_escape_string($con, $certificadoAlistamentoMilitarSerie),
            Util::tratarDataNullSQL($certificadoAlistamentoMilitarData),
            mysqli_real_escape_string($con, $certificadoAlistamentoMilitarRM),
            mysqli_real_escape_string($con, $certificadoAlistamentoMilitarCSM),
            mysqli_real_escape_string($con, $certificadoReservistaNumero),
            mysqli_real_escape_string($con, $certificadoReservistaSerie),
            Util::tratarDataNullSQL($certificadoReservistaData),
            mysqli_real_escape_string($con, $certificadoReservistaCAT),
            mysqli_real_escape_string($con, $certificadoReservistaRM),
            mysqli_real_escape_string($con, $certificadoReservistaCSM)
            );
        $result=BD::mysqli_query($query,$con);
        if(!$result) {
            throw new Exception("Erro ao inserir na tabela Aluno.");
        }
    }

    /***
     * Atualiza Registro do Aluno
     *
     * @param idPessoa
     * @param nomeMae
     * @param rgMae
     * @param nomePai
     * @param rgPai
     * @param rgNumero
     * @param rgDataEmissao
     * @param rgOrgaoEmissor
     * @param cpf
     * @param cpfProprio
     * @param certidaoNascimentoNumero
     * @param certidaoNascimentoLivro
     * @param certidaoNascimentoFolha
     * @param certidaoNascimentoCidade
     * @param certidaoNascimentoSubdistrito
     * @param certidaoNascimentoUF
     * @param certidaoCasamentoNumero
     * @param certidaoCasamentoLivro
     * @param certidaoCasamentoFolha
     * @param certidaoCasamentoCidade
     * @param certidaoCasamentoSubdistrito
     * @param certidaoCasamentoUF
     * @param estabCursoOrigem
     * @param estabCursoOrigemUF
     * @param cursoOrigemAnoConclusao
     * @param modalidadeCursoOrigem
     * @param ctps
     * @param corRaca
     * @param estadoCivil
     * @param deficienciaVisual
     * @param deficienciaMotora
     * @param deficienciaAuditiva
     * @param deficienciaMental
     * @param responsavelLegal
     * @param rgResponsavel
     * @param tituloEleitorNumero
     * @param tituloEleitorData
     * @param tituloEleitorZona
     * @param tituloEleitorSecao
     * @param certificadoAlistamentoMilitarNumero
     * @param certificadoAlistamentoMilitarSerie
     * @param certificadoAlistamentoMilitarData
     * @param certificadoAlistamentoMilitarRM
     * @param certificadoAlistamentoMilitarCSM
     * @param certificadoReservistaNumero
     * @param certificadoReservistaSerie
     * @param certificadoReservistaData
     * @param certificadoReservistaCAT
     * @param certificadoReservistaRM
     * @param certificadoReservistaCSM
     * @param con Indica uma conex�o ativa, para participar de transa��o
     * @result void
     **/
    
    public static function atualizarAluno( 
        $idPessoa, $nomeMae, $rgMae, 
        $nomePai, $rgPai, $rgNumero,
        $rgDataEmissao, $rgOrgaoEmissor, $cpf, 
        $cpfProprio, $certidaoNascimentoNumero,$certidaoNascimentoLivro, 
        $certidaoNascimentoFolha, $certidaoNascimentoCidade,$certidaoNascimentoSubdistrito, 
        $certidaoNascimentoUF, $certidaoCasamentoNumero,$certidaoCasamentoLivro, 
        $certidaoCasamentoFolha, $certidaoCasamentoCidade,$certidaoCasamentoSubdistrito, 
        $certidaoCasamentoUF, $estabCursoOrigem, $estabCursoOrigemCidade, 
        $estabCursoOrigemUF,$cursoOrigemAnoConclusao, $modalidadeCursoOrigem, 
        $ctps, $corRaca, $estadoCivil, 
        $deficienciaVisual,$deficienciaMotora, $deficienciaAuditiva, 
        $deficienciaMental, $responsavelLegal, $rgResponsavel,
        $tituloEleitorNumero, $tituloEleitorData, $tituloEleitorZona, 
        $tituloEleitorSecao,$certificadoAlistamentoMilitarNumero, $certificadoAlistamentoMilitarSerie,
        $certificadoAlistamentoMilitarData, $certificadoAlistamentoMilitarRM,$certificadoAlistamentoMilitarCSM, 
        $certificadoReservistaNumero, $certificadoReservistaSerie,$certificadoReservistaData, 
        $certificadoReservistaCAT, $certificadoReservistaRM,$certificadoReservistaCSM, 
        $con = null ) {

        if($con==null){ $con = BD::conectar(); }

        $query=sprintf("UPDATE Aluno set
            `nomeMae`='%s',`rgMae`='%s',`nomePai`='%s',
            `rgPai`='%s',`rgNumero`='%s',`rgDataEmissao`='%s',
            `rgOrgaoEmissor`='%s',`cpf`='%s',`cpfProprio`='%s',
            `certidaoNascimentoNumero`='%s',`certidaoNascimentoLivro`='%s',`certidaoNascimentoFolha`='%s',
            `certidaoNascimentoCidade`='%s',`certidaoNascimentoSubdistrito`='%s',`certidaoNascimentoUF`='%s',
            `certidaoCasamentoNumero`='%s',`certidaoCasamentoLivro`='%s',`certidaoCasamentoFolha`='%s',
            `certidaoCasamentoCidade`='%s',`certidaoCasamentoSubdistrito`='%s',`certidaoCasamentoUF`='%s',
            `estabCursoOrigem`='%s',`estabCursoOrigemCidade`='%s',`estabCursoOrigemUF`='%s',
            `cursoOrigemAnoConclusao`=%d,`modalidadeCursoOrigem`='%s',`ctps`='%s',
            `corRaca`='%s',`estadoCivil`='%s',`deficienciaVisual`='%s',
            `deficienciaMotora`='%s',`deficienciaAuditiva`='%s',`deficienciaMental`='%s',
            `responsavelLegal`='%s',`rgResponsavel`='%s',`tituloEleitorNumero`='%s',
            `tituloEleitorData`=%s,`tituloEleitorZona`='%s',`tituloEleitorSecao`='%s',
            `certificadoAlistamentoMilitarNumero`='%s',`certificadoAlistamentoMilitarSerie`='%s',
            `certificadoAlistamentoMilitarData`=%s,`certificadoAlistamentoMilitarRM`='%s',
            `certificadoAlistamentoMilitarCSM`='%s',`certificadoReservistaNumero`='%s',
            `certificadoReservistaSerie`='%s',`certificadoReservistaData`=%s,
            `certificadoReservistaCAT`='%s',`certificadoReservistaRM`='%s',
            `certificadoReservistaCSM`='%s'
            where idPessoa=%d",
            mysqli_real_escape_string($con, $nomeMae),
            mysqli_real_escape_string($con, $rgMae),
            mysqli_real_escape_string($con, $nomePai),
            mysqli_real_escape_string($con, $rgPai),
            mysqli_real_escape_string($con, $rgNumero),
            mysqli_real_escape_string($con, $rgDataEmissao),
            mysqli_real_escape_string($con, $rgOrgaoEmissor),
            mysqli_real_escape_string($con, $cpf),
            mysqli_real_escape_string($con, $cpfProprio),
            mysqli_real_escape_string($con, $certidaoNascimentoNumero),
            mysqli_real_escape_string($con, $certidaoNascimentoLivro),
            mysqli_real_escape_string($con, $certidaoNascimentoFolha),
            mysqli_real_escape_string($con, $certidaoNascimentoCidade),
            mysqli_real_escape_string($con, $certidaoNascimentoSubdistrito),
            mysqli_real_escape_string($con, $certidaoNascimentoUF),
            mysqli_real_escape_string($con, $certidaoCasamentoNumero),
            mysqli_real_escape_string($con, $certidaoCasamentoLivro),
            mysqli_real_escape_string($con, $certidaoCasamentoFolha),
            mysqli_real_escape_string($con, $certidaoCasamentoCidade),
            mysqli_real_escape_string($con, $certidaoCasamentoSubdistrito),
            mysqli_real_escape_string($con, $certidaoCasamentoUF),
            mysqli_real_escape_string($con, $estabCursoOrigem),
            mysqli_real_escape_string($con, $estabCursoOrigemCidade),
            mysqli_real_escape_string($con, $estabCursoOrigemUF),
            mysqli_real_escape_string($con, $cursoOrigemAnoConclusao),
            mysqli_real_escape_string($con, $modalidadeCursoOrigem),
            mysqli_real_escape_string($con, $ctps),
            mysqli_real_escape_string($con, $corRaca),
            mysqli_real_escape_string($con, $estadoCivil),
            mysqli_real_escape_string($con, $deficienciaVisual),
            mysqli_real_escape_string($con, $deficienciaMotora),
            mysqli_real_escape_string($con, $deficienciaAuditiva),
            mysqli_real_escape_string($con, $deficienciaMental),
            mysqli_real_escape_string($con, $responsavelLegal),
            mysqli_real_escape_string($con, $rgResponsavel),
            mysqli_real_escape_string($con, $tituloEleitorNumero),
            Util::tratarDataNullSQL($tituloEleitorData),
            mysqli_real_escape_string($con, $tituloEleitorZona),
            mysqli_real_escape_string($con, $tituloEleitorSecao),
            mysqli_real_escape_string($con, $certificadoAlistamentoMilitarNumero),
            mysqli_real_escape_string($con, $certificadoAlistamentoMilitarSerie),
            Util::tratarDataNullSQL($certificadoAlistamentoMilitarData),
            mysqli_real_escape_string($con, $certificadoAlistamentoMilitarRM),
            mysqli_real_escape_string($con, $certificadoAlistamentoMilitarCSM),
            mysqli_real_escape_string($con, $certificadoReservistaNumero),
            mysqli_real_escape_string($con, $certificadoReservistaSerie),
            Util::tratarDataNullSQL($certificadoReservistaData),
            mysqli_real_escape_string($con, $certificadoReservistaCAT),
            mysqli_real_escape_string($con, $certificadoReservistaRM),
            mysqli_real_escape_string($con, $certificadoReservistaCSM),
            mysqli_real_escape_string($con, $idPessoa)
            );
        $result=BD::mysqli_query($query,$con);
        if(!$result) {
            throw new Exception("Erro ao atualizar na tabela Aluno.");
        }
    }

    /***
     * Busca por um aluno por CPF
     * @param cpf String com o cpf do aluno
     * @result new Aluno, ou null, se n�o encontrar
     **/
    public static function obterAlunoPorCPF( $cpf ) {

        // retorna o dado
        $con = BD::conectar();

        // retorna o valor no DB
        $query=sprintf("SELECT * FROM Aluno WHERE cpf = '%s'",
                mysqli_real_escape_string($con, $cpf));
        $result=BD::mysqli_query($query,$con);
        mysqli_close($con);
        if(!$result) trigger_error("Erro ao consultar banco de dados.",E_USER_ERROR);
        if(mysqli_num_rows($result)==0) {
            return null;
        } else {
            $resAluno = mysqli_fetch_array($result);
            $idPessoa = $resAluno["idPessoa"];
            return Aluno::getAlunoByIdPessoa($idPessoa);
        }
    }

    /**
     * Este m�todo tr�s alguns dados pertinentes aos alunos na consulta do hist�rico de concluites por nome
     * 06/12/2010
     * Vin�cius
     * @param <type> $nome
     * @return array
     */
    function obter_concluinte_nome($nome) {

        $con = BD::conectar();

        $query=sprintf("select MatriculaAluno.matriculaAluno,MatriculaAluno.dataMatricula, 
            Pessoa.nome, Pessoa.dataNascimento as dataNascimento, Pessoa.email,
            MatriculaAluno.situacaoMatricula from MatriculaAluno inner join Pessoa
            ON(MatriculaAluno.idPessoa = Pessoa.idPessoa)
            where nome like '%s%%'", mysqli_real_escape_string($con, $nome));
        $result = BD::mysqli_query($query,$con);
        if(!$result) trigger_error("N�o foi poss�vel executar consulta ao banco.",E_USER_ERROR);

        $col = array();
        if (mysqli_num_rows($result) > 0) {
            $i=0;
            while ($arrayAluno = mysqli_fetch_array($result)) {

                $col[$i]['nome'] = $arrayAluno['nome'];
                $col[$i]['dataNascimento'] = Util::dataSQLParaBr($arrayAluno['dataNascimento']);
                $col[$i]['email'] = $arrayAluno['email'];
                $col[$i]['matriculaAluno'] = $arrayAluno['matriculaAluno'];
                $col[$i]['situacaoMatricula'] = $arrayAluno['situacaoMatricula'];
                $col[$i]['dataMatricula'] = Util::dataSQLParaBr($arrayAluno['dataMatricula']);
                $i++;
            }
        }
        return $col;
    }

    /**
     * Gera uma vers�o leg�vel do estado desse objeto. Usado para inserir
     * log de auditoria.
     * @return String
     */
    public function toString() {
        $str = "";
        // Dados de Pessoa
        $str .= sprintf("Nome: %s<br/>",$this->getNome());
        $str .= sprintf("Sexo: %s<br/>",$this->getSexo());
        $str .= sprintf("Endere�o Logradouro: %s<br/>",$this->getEnderecoLogradouro());
        $str .= sprintf("Endere�o N�mero: %s<br/>",$this->getEnderecoNumero());
        $str .= sprintf("Endere�o Complemento: %s<br/>",$this->getEnderecoComplemento());
        $str .= sprintf("Endere�o Bairro: %s<br/>",$this->getEnderecoBairro());
        $str .= sprintf("Endere�o Munic�pio: %s<br/>",$this->getEnderecoMunicipio());
        $str .= sprintf("Endere�o Estado: %s<br/>",$this->getEnderecoEstado());
        $str .= sprintf("Endere�o CEP: %s<br/>",$this->getEnderecoCEP());
        $str .= sprintf("Data de Nascimento: %s<br/>",Util::dataSQLParaBr($this->getDataNascimento()));
        $str .= sprintf("Nacionalidade: %s<br/>",$this->getNacionalidade());
        $str .= sprintf("Naturalidade: %s<br/>",$this->getNaturalidade());
        $str .= sprintf("Tel.Residencial: %s<br/>",$this->getTelefoneResidencial());
        $str .= sprintf("Tel.Comercial: %s<br/>",$this->getTelefoneComercial());
        $str .= sprintf("Tel.Celular: %s<br/>",$this->getTelefoneCelular());
        $str .= sprintf("E-mail: %s<br/>",$this->getEmail());

        // Aluno
        $str .= sprintf("Nome M�e: %s<br/>",$this->getNomeMae());
        $str .= sprintf("RG M�e: %s<br/>",$this->getRgMae());
        $str .= sprintf("Nome Pai: %s<br/>",$this->getNomePai());
        $str .= sprintf("RG Pai: %s<br/>",$this->getRgPai());
        $str .= sprintf("RG: %s<br/>",$this->getRgNumero());
        $str .= sprintf("RG Data Emiss�o: %s<br/>",Util::dataSQLParaBr($this->getRgDataEmissao()));
        $str .= sprintf("RG �rg�o Emissor: %s<br/>",$this->getRgOrgaoEmissor());
        $str .= sprintf("CPF: %s<br/>",$this->getCpf());
        $str .= sprintf("CPF Pr�prio: %s<br/>",$this->getCpfProprio());
        $str .= sprintf("Cert.Nasc.N�mero: %s<br/>",$this->getCertidaoNascimentoNumero());
        $str .= sprintf("Cert.Nasc.Livro: %s<br/>",$this->getCertidaoNascimentoLivro());
        $str .= sprintf("Cert.Nasc.Folha: %s<br/>",$this->getCertidaoNascimentoFolha());
        $str .= sprintf("Cert.Nasc.Cidade: %s<br/>",$this->getCertidaoNascimentoCidade());
        $str .= sprintf("Cert.Nasc.Subdistrito: %s<br/>",$this->getCertidaoNascimentoSubdistrito());
        $str .= sprintf("Cert.Nasc.UF: %s<br/>",$this->getCertidaoNascimentoUF());
        $str .= sprintf("Cert.Casamento N�mero: %s<br/>",$this->getCertidaoCasamentoNumero());
        $str .= sprintf("Cert.Casamento Livro: %s<br/>",$this->getCertidaoCasamentoLivro());
        $str .= sprintf("Cert.Casamento Folha: %s<br/>",$this->getCertidaoCasamentoFolha());
        $str .= sprintf("Cert.Casamento Cidade: %s<br/>",$this->getCertidaoCasamentoCidade());
        $str .= sprintf("Cert.Casamento Subdistrito: %s<br/>",$this->getCertidaoCasamentoSubdistrito());
        $str .= sprintf("Cert.Casamento UF: %s<br/>",$this->getCertidaoCasamentoUF());
        $str .= sprintf("Estab.Curso Origem: %s<br/>",$this->getEstabCursoOrigem());
        $str .= sprintf("Estab.Curso Origem Cidade: %s<br/>",$this->getEstabCursoOrigemCidade());
        $str .= sprintf("Estab.Curso Origem UF: %s<br/>",$this->getEstabCursoOrigemUF());
        $str .= sprintf("Curso Ano Conclus�o: %d<br/>",$this->getCursoOrigemAnoConclusao());
        $str .= sprintf("Modalidade Curso Origem: %s<br/>",$this->getModalidadeCursoOrigem());
        $str .= sprintf("CTPS: %s<br/>",$this->getCtps());
        $str .= sprintf("Cor/Ra�a: %s<br/>",$this->getCorRaca());
        $str .= sprintf("Estado Civil: %s<br/>",$this->getEstadoCivil());
        $str .= sprintf("Defici�ncia Visual: %s<br/>",$this->getDeficienciaVisual());
        $str .= sprintf("Defici�ncia Motora: %s<br/>",$this->getDeficienciaMotora());
        $str .= sprintf("Defici�ncia Auditiva: %s<br/>",$this->getDeficienciaMotora());
        $str .= sprintf("Defici�ncia Mental: %s<br/>",$this->getDeficienciaMental());
        $str .= sprintf("Respons�vel Legal: %s<br/>",$this->getResponsavelLegal());
        $str .= sprintf("RG Respons�vel: %s<br/>",$this->getRgResponsavel());
        $str .= sprintf("T�tulo Eleitor N�mero: %s<br/>",$this->getTituloEleitorNumero());
        $str .= sprintf("T�tulo Eleitor Data: %s<br/>",Util::dataSQLParaBr($this->getTituloEleitorData()));
        $str .= sprintf("T�tulo Eleitor Zona: %s<br/>",$this->getTituloEleitorZona());
        $str .= sprintf("T�tulo Eleitor Se��o: %s<br/>",$this->getTituloEleitorSecao());
        $str .= sprintf("Certif.Alistamento Militar N�mero: %s<br/>",$this->getCertificadoAlistamentoMilitarNumero());
        $str .= sprintf("Certif.Alistamento Militar S�rie: %s<br/>",$this->getCertificadoAlistamentoMilitarSerie());
        $str .= sprintf("Certif.Alistamento Militar Data: %s<br/>",Util::dataSQLParaBr($this->getCertificadoAlistamentoMilitarData()));
        $str .= sprintf("Certif.Alistamento Militar RM: %s<br/>",$this->getCertificadoAlistamentoMilitarRM());
        $str .= sprintf("Certif.Alistamento Militar CSM: %s<br/>",$this->getCertificadoAlistamentoMilitarCSM());
        $str .= sprintf("Certif.Reservista N�mero: %s<br/>",$this->getCertificadoReservistaNumero());
        $str .= sprintf("Certif.Reservista S�rie: %s<br/>",$this->getCertificadoReservistaSerie());
        $str .= sprintf("Certif.Reservista Data: %s<br/>",Util::dataSQLParaBr($this->getCertificadoReservistaData()));
        $str .= sprintf("Certif.Reservista RM: %s<br/>",$this->getCertificadoReservistaRM());
        $str .= sprintf("Certif.Reservista CSM: %s<br/>",$this->getCertificadoReservistaCSM());
        return $str;
    }

}
?>
