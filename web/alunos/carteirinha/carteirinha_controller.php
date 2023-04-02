<?php 

require_once "../../../includes/comum.php";
require_once "$BASE_DIR/classes/Login.php";
require_once "$BASE_DIR/classes/Pessoa.php";
require_once "$BASE_DIR/classes/Declaracao.php";
require_once "$BASE_DIR/classes/Aluno.php";
require_once "$BASE_DIR/classes/MatriculaAluno.php";

$pessoa = $login->getPessoa();

$matriculaAluno = MatriculaAluno::obterMatriculasAlunoPorIdPessoa( $pessoa->getIdPessoa() );

$matriculaAluno = $matriculaAluno[0];//Nгo sei pq retorna um arrei. nгo tem pq pi sу passa um valor e tem q fazer essa POG pra funcionar

$aluno = $matriculaAluno->getAluno();


if($acao = 'exibir' && $matriculaAluno->getSituacaoMatricula() == "CURSANDO"){

    $aluno = Aluno::getAlunoByIdPessoa($pessoa->getIdPessoa());

    //Caso jб exista carteirinha no banco de cados da declaraзгo, nгo gerar outro registro e trabalhar com o existente
    if ( Declaracao::verificarRegistroCarteirinha($matriculaAluno->getNumMatriculaAluno() ) ) {
        //echo "existe";
        $declaracao = Declaracao::obterDeclaracaoPorMatricula($matriculaAluno->getNumMatriculaAluno());
    }
    else{
        $declaracao = new Declaracao($matriculaAluno->getNumMatriculaAluno(), NULL, 2);
        $declaracao->salvarDeclaracao();
    }

    require_once('exibir_carteirinha.php');

    unset($declaracao);
}

?>