$(function() {
    var formRegister = $('#register-content');
    var formLogin = $('#login-content');
     $('#login-submit').click(function() {
      logSend();
     });
  	
    $('#register-submit').click(function() {
        regSend();
    });
    
    $('#view-forget-pass').click(function() {
        viewForgetPass();
    });
    
    $('#forget-pass').click(function() {
       forgetPass(); 
    });
    
    Ladda.bind( '.button-demo button', { timeout: 2000 } );

    /*FUNCIONES*/
    function regSend(){
      $.ajax({
        type: 'POST',
        url: 'php/manager/mapToro/insertUser',
        dataType: 'text',
        data: { 
                name: formRegister.find('input[name = user-name]').val(), 
                email: formRegister.find('input[name = user-email]').val(), 
                password: formRegister.find('input[name = user-password]').val() }
      }).done(function(data) {
        $('#error-form').html();
        viewRegUser(data);
      }).fail(function() {
        console.log("error", arguments);
      });
    }

    function logSend(){
      $.ajax({
        type: 'POST',
        url: 'php/manager/maptoro/logUser',
        dataType: 'text',
        data: { 
                email: formLogin.find('input[name = log-email]').val(),
                password: formLogin.find('input[name = log-password]').val() }
      }).done(function(data) {
        $('#error-form-log').html();
        //Cambio de interfaz
        viewUser(data);
      }).fail(function() {
        console.log("error", arguments);
      });
    }
   
    function viewUser(user_name) {
        if(user_name){
            $('#login-content').remove();
            var $newElement = $('<a/>', {
               html : user_name,
               href : '#user'
            });

            $newElement.appendTo('#menu_top');
        }else{
            console.log ("El email y la contraseña no coinciden.");
        }
    }
    
    function viewRegUser(txt_reg){
        debugger;
        
        if(txt_reg == 1){
            $('#ok-form').html("Hemos enviado un mensaje de activación a su direccion de correo electrónico.");
            $('#ok-form').removeClass('hidden');
            $('#error-form').addClass('hidden');
            //Limpiar campos del formulario
            $(':input','#register-content').val('');
        }else if(txt_reg){
            $('#error-form').html(txt_reg);
            $('#error-form').removeClass('hidden');
        }else{
            console.log ("Error al registrar el usuario, intentelo más tarde.");
        }
    }
    
    function viewForgetPass(){
        //Generar nuevo formulario de olvido de contraseña
        $('#modal-title').html('Introduce su email para recuperar la contraseña: ');
        
        formRegister.find('input[name = user-name]').parent().addClass('hidden');
        formRegister.find('input[name = user-password]').parent().addClass('hidden');
        formRegister.find('#register-submit').parent().addClass('hidden');
        formRegister.find('#view-forget-pass').addClass('hidden');
        
        formRegister.find('#forget-pass').removeClass('hidden');
    }
    
    function forgetPass(){
      $.ajax({
        type: 'POST',
        url: 'php/manager/mapToro/forgetPass',
        dataType: 'text',
        data: { 
                email: formRegister.find('input[name = user-email]').val() }
      }).done(function(data) {
        $('#error-form').html(data);
        console.log("error",data);
      }).fail(function() {
        console.log("error", arguments);
      });
    }
  });