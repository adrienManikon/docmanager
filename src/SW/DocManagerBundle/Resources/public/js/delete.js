var $idToDelete;
function deleteDocument(id, name){           
    
    $idToDelete = id;
    $( "#name-delete" ).html(name);
    
    showDialog("#delete-dialog");
    
}

$("#confirmDelete").click(function(){
    
    url = $( this ).attr('data-url');
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            id : $idToDelete
        },
        beforeSend: function() {
            closeDialog("#delete-dialog");
            showDialog("#dialog-loading");
        },
        success: function(response){
            
            console.log(response);
            
            closeDialog("#dialog-loading");
            
            if (response.status) {
                
                showDialog("#delete-dialog-success");
                showList();
                
            } else {               
                
                $("#error-message").html(response.message);
                showDialog("#deletedialog-error");
                
            }
        },
        dataType: "json"
    });
    
    
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