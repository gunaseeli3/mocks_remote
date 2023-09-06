
//VALIDADOR DE CONTRASEÑAS PARA PAGINA perfil.php  
$('#pass1, #pass2').on('keyup', function () {
    if ($('#pass1').val() == $('#pass2').val()) {
        $('#message').html(' /// Las contrase&ntilde;as coinciden').css('color', 'green');
    } else 
        $('#message').html(' /// Las contrase&ntilde;as no coinciden').css('color', 'red');
});
console.log("me ven?");
