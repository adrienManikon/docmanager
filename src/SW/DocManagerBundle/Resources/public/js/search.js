var $ajaxRequest;

$(function(){
    
    $(".request-text").each(function(){
        
        $(this).keyup(function(){
            sendRequest();
        });
        
    });
    
    $(".request-date").each(function(){
        
        $(this).change(function(){
            sendRequest();
        });
        
    });
    
    $(".request-radio").each(function(){
        
        $(this).change(function(){
            sendRequest();
        });
        
    });
    
    $(".request-select").each(function(){

        $( this ).change(function(){
            sendRequest();
        });

    }); 
    
    $(".clear-request").each(function(){
        
        $(this).click(function(e){
            
            e.preventDefault();
            var $nameInput = $(this).attr("data-clear");
            
            $('[name="'+$nameInput+'"]').val("");
        });
        
    });
});

function sendRequest() {
    clearTimeout($ajaxRequest);
    console.log($ajaxRequest)
    form = $("#search-form");

    $ajaxRequest = $.ajax({
        type: 'POST',
        url: form.attr('action'),
        data: form.serialize(),
        beforeSend: function() {
            console.log(form.serialize());
        },
        success: function(response){
            console.log(response);
            fillTable(response.documents);
            $("#documents-list").show("slow");
        },
        dataType: "json"
    });    
}