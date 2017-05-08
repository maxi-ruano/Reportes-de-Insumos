function pullItemsSelect(idSelect, idSubSelect, url)
{
	$('#'+idSelect).on('change', function(e){
		var idSelected = e.target.value;
		$.get(url+idSelected, function(data){
			$('#'+idSubSelect).select2("val", "");
			$('#'+idSubSelect).empty();
			$.each(data, function(index, obj){
				$('#'+idSubSelect).append('<option value="'+obj.id+'">'+obj.name+'</option>');
			});
		});
	});
}

//LLAMADA AJAX SINCRONA
function llamadaAjax(url,data, formLoad, funcion, tabla)
{
    $.ajax({
        async: false, //para q las llamadas sean sincronas
        url:url, //Url a donde la enviaremos
        type:'POST', //Metodo que usaremos
        contentType:false, //Debe estar en false para que pase el objeto sin procesar
        data:data, //Le pasamos el objeto que creamos con los archivos
        beforeSend: function () {
            if(formLoad != ''){
                var boton = document.getElementById(formLoad)
                load = Ladda.create(boton);
                load.start();
            }
        },
        error: function(jqXHR, status, error){
            console.log(status)
            console.log(error)
        },
        processData:false,
        cache:false
        }).done(function(msg){
            resultadoAjax(msg,funcion, data, tabla)
            $("#proceso").html("");
            $("#proceso").slideUp('fast');     
    });
}

function ajaxSmartConRetorno(url,data, formLoad, funcion, tabla)
{
    console.log('ajaxSmartConRetorno '+url+" "+funcion)
    var return_first = function () {
        var tmp = null;
        $.ajax({
            url:url, 
            async: false, //para q las llamadas sean sincronas
            type:'POST', 
            contentType:false, 
            data:data, 
            processData:false, 
            cache:false,
            beforeSend: function(){
            },
            error: function(jqXHR, status, error){
                console.log(status)
                console.log(error)
            } 
        }).done(function(request){
              tmp = eval(request);

              console.log(tmp)
        });   
        return tmp;
        }();
    return return_first;
}

function ajaxSmartConRetornoAsincrono(url,data, formLoad, funcion, tabla)
{
    console.log('ajaxSmartConRetornoAsincrono'+url+" "+funcion)
    var return_first = function () {
        var tmp = null;
        $.ajax({
            url:url, 
            async: false, //para q las llamadas sean sincronas
            type:'POST', 
            contentType:false, 
            data:data, 
            processData:false, 
            cache:false,
            beforeSend: function(){
            },
            error: function(jqXHR, status, error){
                console.log(status)
                console.log(error)
            } 
        }).done(function(request){
              tmp = eval(request);

              console.log(tmp)
        });   
        return tmp;
        }();
    return return_first;
}

//Admin

$('#passwords_check').on('ifChanged', function(event){
        if(this.checked){
            console.log('show')
            $('#passwords').show();
            
        }else{
            console.log('hide')
            $('#passwords').hide();
           
        }         
});
