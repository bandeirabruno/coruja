<!DOCTYPE html>
<html>
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>

<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

<title>Coruja - Validar</title>

<link rel="shortcut icon" href="../imagens/favicon.ico" type="image/x-icon"/>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
<link href="../estilos/login.css" rel="stylesheet" type="text/css" />

<style>
  body{
    overflow: auto;
    background: #588099 !important;
  }
</style>

<script>
    $( document ).ready(function() {
      
      <?php if (isset($_REQUEST['assin'])) { ?>

        var assinatura = '<?php echo $assinaturaExib; ?>';

        var arrayAssinatura = assinatura.split(':');

        

        $('.ae').each(function(index, element) {

            element.value = arrayAssinatura[index];

        });

      <?php } ?>

    });
</script>

</head>
<body>
    <div>
      <table>
          <tr>
            <td>
              <img src="../imagens/coruja_grande.png" />
            </td>
              <td>
                <form method="post" id="formLogin" action="">
                  <input type="hidden" name="acao" id="acao" value="validar" />
                    <table>
                      <?php
                        // Exibe mensagem de erro
                        if( isset($_SESSION["erro"]) ) {
                      ?>
                      <tr>
                        <td>
                        <div class="alert alert-danger" role="alert">
                           <?php echo $_SESSION["erro"]; unset($_SESSION["erro"]); ?> 
                        </div>
                        </td>
                      </tr>

                      <?php
                        }
                      ?>
                      <?php
                        // Exibe mensagem de sucesso
                        if( isset($msg) ) {
                      ?>

                      <tr>
                        <td>
                          <span class="destaque"><?php echo htmlspecialchars($msg, ENT_QUOTES, "iso-8859-1"); ?></span>
                        </td>
                      </tr>

                      <?php
                        }
                      ?>
                      
                      <tr>
                        <td style="text-align:center">Assinatura Eletrônica:</td>
                        <td><input class="ae" type="text" id="ae[]" name="ae[]" required maxlength="4" autocomplete="off" tabindex="1" />
                        <span class="fs30">:</span><input class="ae" type="text" id="ae[]" name="ae[]" required maxlength="4" autocomplete="off" tabindex="2" />
                        <span class="fs30">:</span><input class="ae" type="text" id="ae[]" name="ae[]" required maxlength="4" autocomplete="off" tabindex="3" /> 
                        <span class="fs30">:</span><input class="ae" type="text" id="ae[]" name="ae[]" required maxlength="4" autocomplete="off" tabindex="4" /> 
                        <span class="fs30">:</span><input class="ae" type="text" id="ae[]" name="ae[]" required maxlength="4" autocomplete="off" tabindex="5" /> 
                        <span class="fs30">:</span><input class="ae" type="text" id="ae[]" name="ae[]" required maxlength="4" autocomplete="off" tabindex="7" /> 
                        <span class="fs30">:</span><input class="ae" type="text" id="ae[]" name="ae[]" required maxlength="4" autocomplete="off" tabindex="8" /> 
                        <span class="fs30">:</span><input class="ae" type="text" id="ae[]" name="ae[]" required maxlength="4" autocomplete="off" tabindex="9" />   </td>
                        
                      </tr>
                    
                      <tr>
                        <td></td>
                        <td>
                          <input class="btn-submit" type="submit" value="Validar" tabindex="10" name="Validar" id="Validar"/>
                        </td>
                      </tr>
                    </table>
                </form>
              </td>
          </tr>
      </table>
    
  </div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</html>
