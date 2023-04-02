<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FAETERJ - Carteirinha</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- HTML5 shim e Respond.js para suporte no IE8 de elementos HTML5 e media queries -->
    <!-- ALERTA: Respond.js não funciona se você visualizar uma página file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script>
        $( document ).ready(function() {
            
           /* valor = $( window ).height();
            $("#a").css("margin-top",valor/2+"px");
            $("#a").css("width","800px");*/

            $(".qrcode").click(function(){
                $("#qrcode").show();
                $(".dimScreen").show();
            });

            $(".dimScreen").click(function(){
                $("#qrcode").hide();
                $(".dimScreen").hide();
            });


        });
    </script>

    <style>
        

        .foto img{
            margin-top: 2rem;
            border: #FFF solid 0.3rem;
            max-width: 118px;
            max-height: 153px;
        }

        .corpo-dados{
            margin-top: 2rem;
            margin-bottom: 1rem;
            width: 100%;
            height: 67%;
            background: #FEFEFE;
            -webkit-border-radius: 10px;
            -moz-border-radius: 10px;
            border-radius: 10px;
            padding: 1rem 0rem 1rem 1rem;
            display: table;

        }

        .dados{
            margin-top: 1rem;
            margin-bottom: 1rem;
            width: 100%;

        }

        .dados span{
            color: #2c6cae;
            font-weight: bolder;
            display:inline-block
        }

        .dados div{
            font-size: 1.2rem;
            font-weight: bolder;
            
        }

        .img_logo{
            display: block;
            max-width: 100%;
            height: auto;
        }

        .qrcode{
            max-width: 5rem;
            max-height: 5rem;
            position:absolute; 
            top:5rem; 
            right: 1.5rem;
        }

        #qrcode{
            display: none;
            position:absolute;
            left:0;
            right:0;
            margin-left:auto;
            margin-right:auto;
            width: 256px;
            z-index: 999;
 
        }

        .corpo{
            background-color:#2c6cae;
            overflow:auto;
            background-image: linear-gradient(141deg, #2c6cae 0%, #0ba1b2 51%, #2c6cae 85%);
            height: 100%;
            max-height: 800px;
            max-width: 400px;
        }

        .dimScreen
        {
            display: none;
            position:fixed;
            padding:0;
            margin:0;

            top:0;
            left:0;

            width: 100%;
            height: 100%;
            background:rgba(35,33,53,0.62);
            
        }



    </style>
  </head>
  <body>
        
        
        <div class="container h-100">
            <div class="row h-100 ">
               
                <div class="col-xs-3 col-md-3"></div>
                <div class="container h-100 corpo col-xs-6 col-md-6">
                    <div class="h-100">  
                        
                        <div class="foto text-center">
                            <?php echo "<img class='' src='/coruja/baseCoruja/controle/obterFotoLogado_controle.php' />"; ?>
                        </div>

                        <div class="qrcode"><img class="img_logo" src="../../assets/img/qr-code.png" /></div>
                        <div id="qrcode" class=""></div>


                        <div class="corpo-dados">
                            <img class="img_logo" src="../../assets/img/logo_nome_faeterj.jpg" />
                                
                                <div class="dados">
                                    <span>Nome</span>
                                    <div><?php echo $pessoa->getNome(); ?></div>
                                </div>
                                
                                <div class="dados">
                                    <span>Matrícula</span>
                                    <div><?php echo $matriculaAluno->getNumMatriculaAluno(); ?></div>
                                
                                </div>
                                <div class="dados">
                                    <span>Curso</span>
                                    <div><?php echo Util::obterNomedoCursoPelaMatricula($login->getNomeAcesso()); ?></div>
                                
                                </div>
                                <div class="dados">
                                    <span>CPF</span>
                                    <div><?php echo Util::formatCpf($aluno->getCpf()); ?></div>
                                
                                </div>

                                
                            </div>
                        <div>

                        </div>
                    </div>
                </div>
                <div class="col-xs-3 col-md-3"></div>

                
            </div>
        </div>
        <div class="dimScreen"></div>
    
  </body>

  <!-- jQuery (obrigatório para plugins JavaScript do Bootstrap) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../../assets/js/qrcode.min.js"></script>
    <script type="text/javascript">
        new QRCode(document.getElementById("qrcode"), "https://www.faeterj-rio.edu.br/coruja/validacao/index.php?assin=<?php echo $declaracao->getAssinatura(); ?>");
    </script>
</html>