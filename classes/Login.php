<?php
require_once "$BASE_DIR/classes/BD.php";
require_once "$BASE_DIR/classes/Pessoa.php";
require_once "$BASE_DIR/classes/Funcao.php";
require_once "$BASE_DIR/classes/Permite.php";
require_once "$BASE_DIR/classes/GrupoFuncao.php";
require_once "$BASE_DIR/classes/Log.php";

class Login 
{
    const ALUNO = "ALUNO";
    const PROFESSOR = "PROFESSOR";
    const ADMINISTRADOR = "ADMINISTRADOR";    
    
    private $nomeAcesso;
    private $bloqueado;
    private $motivoBloqueio;
    private $pessoa;
    private $foto;
    private $validouLog;
    private $perfil;

    private $permissoes; // array of Permite
    private $gruposFuncao;

    private function __construct($nomeAcesso, $bloqueado, $motivoBloqueio, Pessoa $pessoa) 
    {
        $this->nomeAcesso = $nomeAcesso;
        $this->bloqueado = $bloqueado;
        $this->motivoBloqueio = $motivoBloqueio;
        $this->pessoa = $pessoa;
        $this->foto = Login::obterFoto( $nomeAcesso);
        $this->validouLog = false;
    }

    private static function obterFoto( $nomeAcesso) 
    {
        $con = BD::conectar();
        $query=sprintf("select l.foto from Login l
            where l.nomeAcesso='%s'",
        mysqli_real_escape_string(BD::conectar(), $nomeAcesso));
        $result=BD::mysqli_query($query);
        $foto=BD::mysqli_result($result,0,0);
        return $foto;
    }
    
    public function getNomeAcesso() 
    {
        return $this->nomeAcesso;
    }

    public function setNomeAcesso($nomeAcesso) 
    {
        $this->nomeAcesso = $nomeAcesso;
    }

    public function isBloqueado() 
    {
        return $this->bloqueado;
    }

    public function setBloqueado($bloqueado) 
    {
        $this->bloqueado = $bloqueado;
    }

    public function getMotivoBloqueio() {
        return $this->motivoBloqueio;
    }

    public function setMotivoBloqueio($motivoBloqueio) {
        $this->motivoBloqueio = $motivoBloqueio;
    }

    public function getPessoa() 
    {
        return $this->pessoa;
    }

    public function setPessoa(Pessoa $pessoa) 
    {
        $this->pessoa = $pessoa;
    }

    public function getPermissoes() 
	{
        return $this->permissoes;
    }
    
    public function getGruposFuncao() 
    {
        return $this->gruposFuncao;
    }

    public function getValidouLog() 
    {
        return $this->validouLog;
    }    
    
    /**
     * Obtem um objeto de Login pelo idPessoa
     * @param integer $idPessoa
     * @return Login Objeto de login, ou null, se n�o encontrar
     */
    public static function obterLoginPorIdPessoa( $idPessoa) 
    {
        $con=BD::conectar();
        $query=sprintf("select * from Login where idPessoa=%d",$idPessoa);
        $result=BD::mysqli_query($query);
        $linha=mysqli_fetch_array($result);
        if($linha) {
            $login = new Login( $linha["nomeAcesso"],
                        $linha["bloqueado"] === "SIM",
                        $linha["motivoBloqueio"],
                        Pessoa::obterPessoaPorId( $idPessoa) );
            $login->permissoes = Permite::obterPermissoesPorIdPessoa($idPessoa);
            $login->gruposFuncao = GrupoFuncao::obterGruposDeFuncaoPorPermissoes($login->permissoes);
            return $login;
        } 
        else 
        {
            return null;
        }
    }
    
    public static function obterLoginsPorNome($nome) {
        $con   = BD::conectar();
        $query = sprintf("SELECT p.idPessoa
                          FROM Pessoa p 
                          INNER JOIN Login l ON p.idPessoa = l.idPessoa  
                          WHERE p.idPessoa NOT IN (select idPessoa from Aluno) 
                          AND p.nome like '%s%%'", mysqli_escape_string($con, $nome));

        $result = mysqli_query($con, $query);

        if (mysqli_affected_rows($con) > 0) {
            $logins = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $logins[] = Login::obterLoginPorIdPessoa($row['idPessoa']);
            }
            return $logins;
        } else {
            return null;
        }
    }

    public static function obterLoginPorNomeAcesso($nomeAcesso) 
    {
        $con   = BD::conectar();
        $query = sprintf("SELECT p.idPessoa
                          FROM Pessoa p 
                          INNER JOIN Login l ON p.idPessoa = l.idPessoa  
                          AND l.nomeAcesso = '%s'", mysqli_escape_string(BD::conectar(), $nomeAcesso));
        
        $result = BD::mysqli_query($query);
        $row    = mysqli_fetch_assoc($result);
        
        $login = null;
        if ($row) {
            $login = Login::obterLoginPorIdPessoa($row['idPessoa']);
        }
        return $login;
    }

    /**
     * Altera a foto de um login pelo id da pessoa.
     * @param <type> $idPessoa
     * @param <type> $foto
     * @param <type> $con 
     */
    public static function atualizarFoto($idPessoa, $foto, $con) 
    {
        if($con == null) { $con = BD::conectar(); }
        $query=sprintf("update Login set foto='%s' where idPessoa=%d",
                addslashes($foto),
                $idPessoa);
        mysqli_query($con, $query);
        if(mysqli_affected_rows($con)!=1) {
            throw new Exception("Erro ao alterar foto do Login.");
        }
    }

    /**
     * Cria um novo login
     * @param <type> $idPessoa
     * @param <type> $nomeAcesso
     * @param <type> $senha senha sugerida pelo sistema
     * @param <type> $con opcional, conex�o externa usada na transa��o
     */
    public static function criarLogin($idPessoa, $nomeAcesso, $senha, $con=null) 
    {
        if($con == null) { $con=BD::conectar(); }
        $query=sprintf("insert into Login (idPessoa,nomeAcesso,senha)
            values (%d,'%s','%s')",
                $idPessoa,
                mysqli_real_escape_string(BD::conectar(), $nomeAcesso),
                md5(mysqli_real_escape_string(BD::conectar(), $senha)));
        mysqli_query($con, $query);
        if(mysqli_affected_rows($con)!=1) {
            throw new Exception("Erro ao inserir novo Login.");
        }
        mysqli_close($con);
    }

    public static function alterarSenhaLogin($idPessoa,$nomeAcesso,$novaSenha,$con=null) 
    {

        if($con==null) { $con=BD::conectar(); }
        $query=sprintf("update Login set senha='%s',bloqueado='N�O' where idPessoa=%d and nomeAcesso='%s'",
            md5(mysqli_real_escape_string($con, $novaSenha)),
            $idPessoa,
            mysqli_real_escape_string(BD::conectar(), $nomeAcesso));
            
            mysqli_query($con, $query);

        if(mysqli_affected_rows($con)!=1) {
            //throw new Exception(mysqli_error($con));
        }
    }

    public static function incluirLogAdministrador($idCasoUso, $descricao, $con) 
    {
        if($con==null) { $con = BD::conectar(); }
        $query=sprintf("insert into Log (idPessoa,idCasoUso,descricao) values (%d,'%s','%s')",
                Config::ADMINISTRADOR_ID_PESSOA,
                $idCasoUso,
                mysqli_escape_string(BD::conectar(), $descricao));
        $result=BD::mysqli_query($query);
        if(!$result) {
            throw new Exception("Erro ao inserir na tabela Log.");
        }
    }

    public static function recuperarSenha($idPessoa, $nomeAcesso) 
    {
        $con = BD::conectar();
        $senha = Util::gerarSenhaAleatoria();
        $query = sprintf("update Login set senha='%s'
            where idPessoa = %d and nomeAcesso='%s'",
                mysqli_real_escape_string(BD::conectar(), md5($senha)),
                $idPessoa,
                mysqli_real_escape_string(BD::conectar(), $nomeAcesso));

        try {
            BD::mysqli_query("BEGIN"); // Inicia transa��o

            // Atualiza senha
            $result=BD::mysqli_query($query);
            if(!$result) {
                throw new Exception("N�o foi poss�vel resetar a senha do usu�rio.");
            }

            $pessoa = Pessoa::obterPessoaPorId($idPessoa);
            $email = $pessoa->getEmail();
            $assunto = "Senha resetada";
            $nome = $pessoa->getNome();
            $texto = "Prezado(a) $nome, \n\n
                Sua conta $nomeAcesso no sistema Coruja foi resetada.\n
                Sua nova senha � $senha\n
                � fortemente recomendado que voc� a altere o mais r�pido poss�vel. 
                Caso n�o tenha sido voc� a solicitar essa opera��o, 
                comunique imediatamente � institui��o.";
            Util::enviarEmail($email,$assunto,$texto);

            BD::mysqli_query("COMMIT");
        } catch (Exception $ex) {
            BD::mysqli_query("ROLLBACK");
            throw new Exception($ex->getMessage());
        }
    }
    
    public function getFoto() 
    {
        return $this->foto;
    }
    
    /**
    * Bloqueia um login dado o nome de acesso e um motivo
    */
    public static function bloquear( $nomeAcesso, $motivoBloqueio) 
    {
        $con = BD::conectar();
        $query = sprintf("update Login set bloqueado='SIM', motivoBloqueio='%s' where " .
                "nomeAcesso='%s'",
                mysqli_real_escape_string(BD::conectar(), $motivoBloqueio),
                mysqli_real_escape_string(BD::conectar(), $nomeAcesso) );
        $result = BD::mysqli_query( $query);
        if( !$result ) 
        {
            throw new Exception("Erro ao bloquear login de usuario");
        }
    }

    public function desbloquear( $nomeAcesso, $con = null) 
    {
        if( $con == null)
        {
            $con = BD::conectar();
        }
        $query = sprintf("update Login set bloqueado='N�O', motivoBloqueio=null where " .
                "nomeAcesso='%s'",
                mysqli_real_escape_string(BD::conectar(), $nomeAcesso) );
        $result = BD::mysqli_query( $query);
        if( !$result ) 
        {
            throw new Exception("Erro ao bloquear login de usuario");
        }        
    }

    public function getEmail() 
    {
        return $this->pessoa->getEmail();
    }
    
    /**
     * Retorna a quantidade de avisos que ainda n�o foram
     * lidos (dado aceite) por este login.
     */
    public function obterQtdeAvisosNaoLidos() 
    {
        $con = BD::conectar();
        $query = sprintf("select count(*) from MensagemPessoa 
            where idPessoa = %d and
            lido='N�O'", $this->pessoa->getIdPessoa());
        $result = BD::mysqli_query($query);
        return BD::mysqli_result($result, 0, 0);
    }    
    
    /**
    * Obtem todos os registros de Logs deste usu�rio ainda n�o conferidos
    */
    public function getLogsNaoConferidos() 
    {
        $naoConferidos = Log::getLogsNaoConferidos( $this->nomeAcesso);
        if( empty( $naoConferidos))
        {
            $this->validouLog = true;
        }
        return $naoConferidos;
    }

    public function getIdPessoa() 
    {
        return $this->pessoa->getIdPessoa();
    }
    
    /**
    * Retorna um booleano indicado se, para um dado c�digo de caso de uso,
    * o login tem ou n�o permiss�o de uso.
    */
    public function temPermissao( $idCasoUso) 
    {
        foreach ($this->permissoes as $permite)
        {
            if( $permite->getFuncao()->getIdCasoUso() === $idCasoUso )
            {
                return true;
            }
        }
        return false;
    }

    /**
    * Registra o confere por parte do usu�rio sobre um registro de Log
    */
    public function incluirLog($idCasoUso,$descricao,$con=null) 
    {
        return Log::incluirLog( $this->getIdPessoa(), $idCasoUso, $descricao, $con);
    }
    
    public function trocarSenha($senhaAtual,$novaSenha) 
    {
        $con = BD::conectar();
        $query = sprintf("update Login set senha='%s'
            where senha='%s' and idPessoa=%d and nomeAcesso='%s'",
        mysqli_escape_string(BD::conectar(), md5($novaSenha)),
        mysqli_escape_string(BD::conectar(), md5($senhaAtual)),
        mysqli_escape_string(BD::conectar(), $this->getIdPessoa()),
        mysqli_escape_string(BD::conectar(), $this->getNomeAcesso()));
        $result = mysqli_query($con, $query);
        if( (!$result) || mysqli_affected_rows($con)==0 ) 
        {
            throw new Exception("N�o foi poss�vel alterar a senha. Verifique se a senha atual est� correta.");
        }
    }
    
    /**
     * Retorna um valor l�gico indicado se o login
     * autenticado � aluno ou n�o. Confere se ele tem matr�cula
     * e n�o tem nenhum permiss�o.
     */
    public function isAluno() 
    {
        return $this->perfil === Login::ALUNO;
    }

    /**
     * Retorna um valor l�gico indicado se o usu�rio
     * autenticado � administrador ou n�o.
     */
    public function isAdministrador() 
    {
        return $this->perfil === Login::ADMINISTRADOR;
    }
    
    /**
     * Retorna um valor l�gico indicado se o usu�rio
     * autenticado � professor ou n�o. Confere se ele tem matr�cula vigente.
     */
    public function isProfessor() 
    {
        return $this->perfil === Login::PROFESSOR;
    }
    
    /**
    * Realiza o carregamento do login
     * 
    */
    /**
     * Cria um objeto Login autenticado
     * @param string $nomeAcesso
     * @param string $senha
     * @param string $perfil
     * @return Login se autenticado
     * @throws Exception se n�o p�de autenticar
     */
    public static function autenticar( $nomeAcesso , $senha, $perfil) 
    {
        $con = BD::conectar();
        $query = sprintf("select senha, tentativas, bloqueado, motivoBloqueio "
                . "from Login where nomeAcesso='%s'",
            mysqli_real_escape_string(BD::conectar(),  $nomeAcesso) );
        $result = BD::mysqli_query($query);
        $loginExiste = mysqli_num_rows($result) === 1;
        
        if( $loginExiste ) 
        {
            $tentativas = BD::mysqli_result( $result,0,1);

            // confere nao senha
            if( BD::mysqli_result($result, 0, 0) !== md5( $senha))
            {
                if( $tentativas >= 3  )
                {
                    Login::bloquearLogin( $nomeAcesso, $con);
                    Login::registrarLoginErro("Login existente "
                        . "$nomeAcesso tentou autenticar com perfil $perfil "
                            . "e falhou auntentica��o mais do que 3 vezes. "
                            . "Login foi bloqueado.", $con);
                    throw new Exception("Conta bloqueada. Procure a secretaria.");
                }
                else
                {
                    Login::incrementarErrosAutenticacao( $nomeAcesso, $con);
                    Login::registrarLoginErro("Login existente "
                        . "$nomeAcesso tentou autenticar com perfil $perfil "
                            . "e falhou a senha. Quantidade de erros foi "
                            . "incrementada.", $con);
                    Login::lancarErroGenericoAutenticacao();
                }
            }
            
            $bloqueado = BD::mysqli_result( $result,0,2) === "SIM";
            if( $bloqueado )
            {
                Login::registrarLoginErro("Login existente "
                    . "$nomeAcesso tentou autenticar com perfil $perfil "
                        . "mas est� bloqueado.", $con);
                $motivoBloqueio = BD::mysqli_result( $result,0,3);
                throw new Exception( sprintf("Conta %s bloqueada. Motivo: %s", 
                        $nomeAcesso,
                        $motivoBloqueio) );
            }
            else 
            {
                $login = Login::obterLoginPorNomeAcesso( $nomeAcesso);
                $mudouPerfil = $login->mudarPerfil( $perfil);
                if( !$mudouPerfil)
                {
                    Login::registrarLoginErro("Login existente "
                        . "$nomeAcesso tentou autenticar com perfil $perfil "
                        . "mesmo n�o o possuindo.", $con);
                    Login::incrementarErrosAutenticacao( $nomeAcesso, $con);
                    Login::lancarErroGenericoAutenticacao();
                }
                else
                {
                    if( $tentativas > 0) // reseta quantidade de tentativas
                    {
                        Login::resetarTentativas( $nomeAcesso, $con);
                    }
                }
                return $login;
            }
        }
        else 
        {
            Login::registrarLoginErro("Login inexistente "
                    . "$nomeAcesso tentou autenticar com perfil $perfil", $con);
            Login::lancarErroGenericoAutenticacao();
        }
    }

    private static function incrementarErrosAutenticacao( $nomeAcesso, $con) 
    {
        $updateQtdeErros = sprintf("update Login set "
                . "tentativas=tentativas+1 "
                . "where nomeAcesso='%s'", 
                $nomeAcesso);
        BD::mysqli_query( $updateQtdeErros);
    }    
    
    private static function lancarErroGenericoAutenticacao()
    {
        throw new Exception("Nome acesso, senha e ou perfil n�o conferem. "
                . "Sua conta ser� bloqueada ap�s 3 erros.");
    }

    private static function registrarLoginErro( $msg, $con)
    {
        $updateLoginErro = sprintf("insert LoginErro (texto) values ('%s')", 
                mysqli_real_escape_string(BD::conectar(), $msg));
        BD::mysqli_query( $updateLoginErro);        
    }
    
    private static function resetarTentativas($nomeAcesso, $con) 
    {
        $updateResetaTentativas = sprintf("update Login set "
                . "tentativas=0 where nomeAcesso='%s'", 
                $nomeAcesso);
        BD::mysqli_query( $updateResetaTentativas);
    }

    private static function bloquearLogin($nomeAcesso, $con) 
    {
        // bloquear Login
        $updateBloquear = sprintf("update Login set bloqueado='SIM',"
                . "motivoBloqueio='Errou a senha "
                . "mais do que 3 vezes'"
                . ", tentativas=tentativas+1 where nomeAcesso='%s'", 
                $nomeAcesso);
        BD::mysqli_query( $updateBloquear);
    }
    
    public function getPerfil() 
    {
        return $this->perfil;
    }
    
    /**
     * Muda o perfil do login
     * @param String $perfil
     * @return boolean indica se mudou com sucesso ou n�o
     */
    private function mudarPerfil( $perfil ) 
    {
        if( $perfil === Login::ALUNO && $this->isPerfilAluno() ) 
        {
            $this->perfil = Login::ALUNO;
            return true;
        } 
        else if( $perfil === Login::PROFESSOR && $this->isPerfilProfessor() ) 
        {
            $this->perfil = Login::PROFESSOR;
            return true;
        } 
        else if( $perfil === Login::ADMINISTRADOR && $this->isPerfilAdministrador() ) 
        {
            $this->perfil = Login::ADMINISTRADOR;
            return true;
        }
        return false;
    }

    /**
     * Retorna um valor l�gico indicado se o usu�rio
     * autenticado � aluno ou n�o. Confere se ele tem matr�cula
     * e n�o tem nenhum permiss�o.
     */
    private function isPerfilAluno() {
        $con = BD::conectar();
        $query = sprintf("select count(*) from MatriculaAluno ma
               where ma.idPessoa=%d and not exists (select * from Permite pe
                where pe.idPessoa=ma.idPessoa)",
            $this->pessoa->getIdPessoa());
        $result = BD::mysqli_query($query);
        if(BD::mysqli_result($result, 0, 0) >= 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Retorna um valor l�gico indicado se o usu�rio
     * autenticado � professor ou n�o. Confere se ele tem matr�cula vigente.
     */
    private function isPerfilProfessor() 
    {
        $con = BD::conectar();
        $query = sprintf("select count(*) from MatriculaProfessor mp
               where mp.idPessoa = %d and (dataEncerramento is NULL or dataEncerramento >= CURDATE())",
            $this->pessoa->getIdPessoa());
        $result = BD::mysqli_query($query);
        if(BD::mysqli_result($result, 0, 0) >= 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Retorna um valor l�gico indicado se o usu�rio
     * autenticado � administrador ou n�o.
     */
    private function isPerfilAdministrador() 
    {
        $con = BD::conectar();
        $query = sprintf("select count(*) from Permite permite
               where permite.idPessoa = %d",
            $this->pessoa->getIdPessoa());
        $result = BD::mysqli_query($query);
        if(BD::mysqli_result($result, 0, 0) >= 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
    * Registra o confere por parte do usu�rio sobre um registro de Log
    */
    public function aceitarLog( $idCasoUso, $dataHora) 
    {
        $con = BD::conectar();
        $query = sprintf("update Log set confere='SIM' where idPessoa=%d " .
                " and idCasoUso='%s' and dataHora='%s'",
                $this->pessoa->getIdPessoa(), $idCasoUso, $dataHora);
        if( !BD::mysqli_query( $query))
        {
            throw new Exception("Erro ao atualizar conferencia de log");
        }
    }
}
