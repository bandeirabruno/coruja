$(function() {

    $( "#dialog-form" ).dialog({
        autoOpen: false,
        height: 400,
        width: 350,
        modal: true,
        buttons: {
       
        }
    });

    $( "#resetarSenha" ).on( "click", function() {
        $( "#dialog-form" ).dialog( "open" );
    });

    $( "#formSenha" ).submit(function( event ) {

        //Caso as senhas não sejam iguais.
        if ($(this).find('#senha').val() != $(this).find('#senha1').val()) {
            event.preventDefault();
            $("#erroInModal").html("As senhas não conferem");
            $("#erro").removeClass( "d-none" );
            $( "#erroInModal" ).effect( "bounce", {}, 800 );
        }
        //alert( "Handler for .submit() called." );
        
    });

});