function editDocument(id, name, date, code, creator){           
    
    $( "#edit-dialog-form input[name='id']" ).val(id);
    $( "#edit-dialog-form input[name='name']" ).val(name);
    $( "#edit-dialog-form input[name='date']" ).val(date);
    $( "#edit-dialog-form input[name='code']" ).val(code);
    $( "#edit-dialog-form input[name='creator']" ).val(creator);
    
    showDialog("#edit-dialog");
    
}

/* attach a submit handler to the form */
$("#edit-dialog-form").submit(function(event) {
    
    /* stop form from submitting normally */
    event.preventDefault();

    /* get some values from elements on the page: */
    var $form = $( this ),
    url = $form.attr( 'action' );
    
    sendForm($form, url, "#edit-dialog");
});

$("#confirmOverride").click(function(){
    
    var $form = $("#edit-dialog-form");
    url = $form.attr( 'data-url-force' );
    
    sendForm($form, url, "#already-exist-dialog");
    
});

function sendForm(form, url, dialog) {
    $.ajax({
        type: 'POST',
        url: url,
        data: form.serialize(),
        beforeSend: function() {
            closeDialog(dialog);
            showDialog("#edit-dialog-loading");
        },
        success: function(response){
            console.log(response);
            
            closeDialog("#edit-dialog-loading");
            
            if (response.status) {
                
                showDialog("#edit-dialog-success");
                showList();
                
            } else {
                
                if(response.alreadyExists) {
                    
                    showDialog("#already-exist-dialog");
                    
                } else {
                
                    $("#error-message").html(response.message);
                    showDialog("#edit-dialog-error");
                    
                }
                
            }
        },
        dataType: "json"
    });
    
}