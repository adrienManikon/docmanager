$(function(){
        
    $(".select-tools").each(function(){
        
        $(".rows-select option", this).hide();
        value = $(".header-select select option:first-child", this).val();
        
        $("."+value, this).show();
        $(".default-select", this).show();
                
        $(".header-select select", this).change(function(){
            
            value = $( "option:selected", this).val();
            $(".rows-select option", this.parentNode.parentNode).hide();
            $("."+value, this.parentNode.parentNode).show();
            $(".default-select", this.parentNode.parentNode).show();
            
        });
                
    });    
    
});
var idcategories = [-1, -1, -1, -1];

$(function(){
        
    $(".select-tools").each(function(){
                
        $(".rows-select", this).change(function(){
            
            position = $(this).attr('position');
            idcategories[position] = $("option:selected",this).val();
            
            showList();
        });
        
        $(".category-radio").click(function(){
            
            position = $(this).attr('position');
            idcategories[position] = $(this).val();
            
            showList();
            
        });
        
    });
    
    var status = $('#status').html();
    
    if (status === 'success') {
        showDialog("#success");
    } else if (status === 'failed'){
        showDialog("#alert");
    }
    
});
    
function showList() {
    $.ajax({
        type: "POST",
        url: "/web/app_dev.php/list",
        dataType: "json",
        data: {
            'idcategories': idcategories
        },
        beforeSend: function() {
            $("#documents-list").hide();
            $("#loading").show();
        },
        success: function(response) {
            console.log(response);
            fillTable(response.documents);
            $("#loading").hide();
            $("#documents-list").show("slow");
        },
        error: function() {
            $("#loading").hide();
        }
    });
}
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
            showDialog("#dialog-loading");
        },
        success: function(response){
            console.log(response);
            
            closeDialog("#dialog-loading");
            
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