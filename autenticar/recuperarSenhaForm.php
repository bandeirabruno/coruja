<html>
    <script type="text/javascript ">
        function validar() {
            if(document.getElementById("acao").value=="cancelar") return true;

            /*
             * Nome pelo qual um usu�rio-ator de identifica ao sistema.
             * Esse nome deve ser �nico entre os logins.
             * At� 20 caracteres. M�nimo de 4 caracteres.
             * Obrigat�rio.
             */
            nomeAcesso = document.getElementById("nomeAcesso");
            if( nomeAcesso.value.length<4 ) {
                alert("Preencha o campo Nome de Acesso (ao menos 4 caracteres)");
                nomeAcesso.focus();
                return false; // impede a submiss�o do formul�rio
            }
            /*
             * Senha de acesso criptografada, armazenada com o c�digo hash MD5
             * representando em d�gitos hexadecimais.
             * Obrigat�rio.
             * Restri��o: a senha real do usu�rio (n�o a armazenada),
             * deve ter at� 12 caracteres, e no m�nimo de 4 caracteres.
             */
             /*
            senha = document.getElementById("senha");
            if( senha.value.length<4 ) {
                alert("Preencha o campo Senha (ao menos 4 caracteres)");
                senha.focus();
                return false; // impede a submiss�o do formul�rio
            }
            */
            return true; // permite a submiss�o do formul�rio
        }

        function cancelar() {
            document.getElementById("acao").value = "cancelar";
            document.getElementById("formLogin").submit();
        }
    </script>
    <head>
        <title>Coruja - Autenticar</title>
        <link href="../estilos/login.css" rel="stylesheet" type="text/css" />
        <style>
            tr{margin-bottom: 10px;}
            .label{text-align:center;}
        </style>
    </head>
    <body onload="nomeAcesso.focus();">
        <table>
            <tr>
                <td>
                    <img src="../imagens/coruja_grande.png" />
                </td>
                <td>
                    <form method="post" id="formLogin" action="/coruja/autenticar/login_controle.php" onsubmit="return validar();">
                        <input type="hidden" name="acao" id="acao" value="recuperarSenhaConfirmarEmail" />
                        <table>
                            <?php
                            // Exibe mensagem de erro quando n�o conseguir conectar e/ou autenticar
                            if( isset($erro) ) {
                                ?>
                            <tr>
                                <td colspan="2">
                                    <span class="erro"><?php echo htmlspecialchars($erro, ENT_QUOTES, "iso-8859-1"); ?></span>
                                </td>
                            </tr>
                            <?php
                            }
                            ?>
                            <tr>
                                <td colspan="2"><span class="destaque">Preencha os dados a seguir para recuperar sua senha</span></td>
                            </tr>
                            <tr>
                                <td class="label">Nome de Acesso:</td>
                                <td><input type="text" id="nomeAcesso" name="nomeAcesso" maxlength="20" required autocomplete="off" tabindex="1" /></td>
                            </tr>
                            <tr>
                                <td  class="label">E-Mail:</td>
                                <td>
                                    <input type="email" id="email" name="email" required tabindex="2" />
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    &nbsp;
                                    <input type="submit" value="Continuar" tabindex="3" />
                                </td>
                            </tr>
                        </table>
                    </form>
                </td>
            </tr>
        </table>
    </body>
</html>