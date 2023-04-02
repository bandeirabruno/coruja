<?php
class Mensagem {
    
    private $idMensagem;
    private $assunto;
    private $texto;
    private $dataMensagem;
        
    function __construct($idMensagem, $assunto, $texto, DateTime $dataMensagem) 
    {
        $this->idMensagem = $idMensagem;
        $this->assunto = $assunto;
        $this->texto = $texto;
        $this->dataMensagem = $dataMensagem;
    }

    public function getIdMensagem() 
    {
        return $this->idMensagem;
    }
    
    public function getAssunto() 
    {
        return $this->assunto;
    }

    public function getTexto() 
    {
        return $this->texto;
    }

    public function getDataMensagem() 
    {
        return $this->dataMensagem;
    }
        
    public function foiLidaPor( int $idPessoa ) 
    {
        $con = BD::conectar();
        $query = sprintf("select count(*) from MensagemPessoa MP 
            where MP.idPessoa = %d and 
            MP.idMensagem = %d and
            MP.lido = 'SIM'",
                $idPessoa,
                $this->getIdMensagem() );
        $result = BD::mysqli_query($query, $con);
        if(BD::mysqli_result($result, 0, 0) == 1) 
        {
            return true;
        }
        return false;
    }
        
    public static function depositarMensagem($assunto, $mensagem, array $arrIdPessoa, $con = null) 
    {
        if( $con == null )
        {
            $con = BD::conectarOO();
        }

        $cmdInsMsg = sprintf("insert into Mensagem (assunto,texto) values ('%s','%s')",
            $con->real_escape_string($assunto),
            $con->real_escape_string($mensagem));

        $result = $con->query($cmdInsMsg);

        $idMensagem = $con->insert_id
        ;
        if(!$result) {
            throw new Exception("Erro ao inserir na tabela Mensagem.");
        }
        
        foreach($arrIdPessoa as $idPessoa) {
            $cmdInsMsgPessoa = sprintf("insert into MensagemPessoa (idMensagem, 
                idPessoa) values ( %d, %d )", $idMensagem, $idPessoa );

            $result = $con->query($cmdInsMsgPessoa);

            if(!$result) {
                throw new Exception("Erro ao inserir na tabela MensagemPessoa.");
            }
        }
    }

    /**
     * Retorna a última mensagem não lida do usuário informado, ou nulo,
     * se não houver
     * @param int $idPessoa identificador da pessoa
     * @return Mensagem ou nulo, se não existir
     */
    public static function obterMensagemNaoLidaMaisAntiga(int $idPessoa) 
    {
        $con = BD::conectar();
        $query = sprintf("select * from Mensagem M inner join 
            MensagemPessoa MP on M.idMensagem = MP.idMensagem 
            where MP.lido = 'NÃO' and 
            MP.idPessoa = %d
            order by M.dataMensagem 
            limit 1", 
                $idPessoa );
        $result = BD::mysqli_query($query, $con);
        if( mysqli_num_rows($result) > 0 ) 
        {
            return Mensagem::converterLinhaBanco( mysqli_fetch_array($result) );
        } else {
            return null;
        }
    }

    /**
     * Obtém a mensagem mais recente, lida ou não lida.
     * @param int $idPessoa identificador da pessoa
     * @return Mensagem ou nulo, se não existir
     */
    public static function obterMensagemMaisRecente(int $idPessoa) 
    {
        $con = BD::conectar();
        $query = sprintf("select * from Mensagem M inner join 
            MensagemPessoa MP on M.idMensagem = MP.idMensagem 
            where MP.idPessoa = %d
            order by M.dataMensagem DESC limit 1", 
                $idPessoa );
        $result = BD::mysqli_query($query, $con);
        if( mysqli_num_rows($result) > 0 ) {
            return Mensagem::converterLinhaBanco( mysqli_fetch_array($result) );
        } else {
            return null;
        }
    }
    
    private static function converterLinhaBanco( array $linha ) 
    {
        return new Mensagem($linha["idMensagem"], $linha["assunto"], $linha["texto"], 
                Util::converteDateTime($linha["dataMensagem"]));
    }

    /**
     * Obtém as últimas mensagens registradas para essa usuário.
     * @param int $idPessoa
     * @return array de mensagens
     */
    public static function obterUltimasMensagens(int $idPessoa) 
    {
        $con = BD::conectar();
        $query = sprintf("select * from Mensagem M inner join 
            MensagemPessoa MP on M.idMensagem = MP.idMensagem 
            where MP.idPessoa = %d
            order by M.dataMensagem DESC limit 10", 
                $idPessoa );
        $result = BD::mysqli_query($query, $con);
        $col = array();
        while( $linha = mysqli_fetch_array($result) ) {
            $col[] = Mensagem::converterLinhaBanco( $linha );
        }
        return $col;
    }

    /**
     * Retorna o total de mensagens para um usuário
     * @param int $idPessoa
     * @return int total de mensagens
     */
    public static function obterTotalMensagem(int $idPessoa) 
    {
        $con = BD::conectar();
        $query = sprintf("select count(*) from Mensagem M inner join MensagemPessoa MP 
            on M.idMensagem = MP.idMensagem 
            where MP.idPessoa = %d", $idPessoa );
        $result = BD::mysqli_query($query, $con);
        return BD::mysqli_result($result, 0, 0);
    }

    public function obterIdMensagemAnterior(int $idPessoa) 
    {
        $con = BD::conectar();
        $query = sprintf("select M.idMensagem from Mensagem M 
            inner join MensagemPessoa MP on M.idMensagem = MP.idMensagem 
            where MP.idPessoa = %d and
            M.dataMensagem > '%s' 
            order by dataMensagem ASC",
                $idPessoa,
                $this->dataMensagem->format("Y-m-d H:i:s"));
        $result = BD::mysqli_query($query, $con);
        if( mysqli_num_rows($result) > 0 ) {
            return BD::mysqli_result($result, 0, 0);
        } else {
            return null;
        }
    }

    public function obterIdMensagemPosterior(int $idPessoa) 
    {
        $con = BD::conectar();
        $query = sprintf("select M.idMensagem from Mensagem M 
            inner join MensagemPessoa MP on M.idMensagem = MP.idMensagem 
            where MP.idPessoa = %d and
            M.dataMensagem < '%s' 
            order by dataMensagem DESC",
                $idPessoa,
                $this->dataMensagem->format("Y-m-d H:i:s"));
        $result = BD::mysqli_query($query, $con);
        if( mysqli_num_rows($result) > 0 ) 
        {
            return BD::mysqli_result($result, 0, 0);
        } else {
            return null;
        }    
    }

    public static function obterMensagemPorId($idMensagem, $idPessoa) 
    {
        $con = BD::conectar();
        $query = sprintf("select * from Mensagem M
            inner join MensagemPessoa MP on M.idMensagem = MP.idMensagem
            where M.idMensagem = %d and
            MP.idPessoa = %d", 
                $idMensagem,
                $idPessoa );
        $result = BD::mysqli_query($query, $con);
        if( mysqli_num_rows($result) > 0 ) {
            return Mensagem::converterLinhaBanco( mysqli_fetch_array($result) );
        } else {
            return null;
        }        
    }

    public function marcarComoLidaPor(int $idPessoa, $con = null) 
    {
        if( $con == null) {
            $con = BD::conectar();
        }
        $cmd = sprintf("update MensagemPessoa set lido='SIM', tentouEmail='SIM' 
            where idMensagem = %d and 
            idPessoa = %d",
                $this->getIdMensagem(),
                $idPessoa);
        BD::mysqli_query($cmd, $con);
    }

    public function marcarComoEnviadoPorEmailPara($idPessoa, $con) 
    {
        $cmd = sprintf("update MensagemPessoa set tentouEmail='SIM', dataHoraEnvioEmail=NOW()
            where idMensagem = %d and 
            idPessoa = %d",
                $this->getIdMensagem(),
                $idPessoa);
        BD::mysqli_query($cmd, $con);
    }
}