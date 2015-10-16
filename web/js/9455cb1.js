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

function sendRequest(page) {
    clearTimeout($ajaxRequest);
    console.log($ajaxRequest)
    form = $("#search-form");
    
    page = page === undefined ? 1 : page;
    data = form.serialize() + "&page=" + page;
    $ajaxRequest = $.ajax({
        type: 'POST',
        url: form.attr('action'),
        data: data,
        beforeSend: function() {
            //console.log(form.serialize());
            $("#documents-list").fadeTo(200, 0.5);
        },
        success: function(response){
            console.log(response);
            fillTable(response.documents);
            fillPagination(parseInt(response.pages), parseInt(response.pageCurrent));
            $("#documents-list").show("slow");
            $("#documents-list").fadeTo(200, 1);
        },
        dataType: "json"
    });    
}

function showList() {
    sendRequest();
}

function fillPagination(pages, pageCurrent) {
        
    var block = $("#results-pages");
    $(block).empty();
        
    if (pages > 1 && pageCurrent > 2)
        $(block).append('<span onclick="sendRequest(' + (pageCurrent - 1) +')" class="item"><</span>');
    
    var middle = pages / 2;
    var offset = pages;
    if ( middle > 3 ) {        
        if ( pageCurrent < middle )
            offset = pageCurrent + 3;
        else
            offset = pageCurrent - 3;        
    }
    
    for (i = 1; i <= pages; i++) {
        row = '';
        
        if (i === pageCurrent)
            row = '<span class="item current">' + i + '</span>';
        else if ( i < offset )
            row = '<span onclick="sendRequest(' + i +')" class="item">' + i + '</span>';
        else if ( i === offset )
            row = '<span class="item spaces">...</span>';
        else if ( i > offset )
            row = '<span onclick="sendRequest(' + i +')" class="item">' + i + '</span>';
                
        $(block).append(row);
    }
    
    if (pages > 1 && pageCurrent < pages)
        $(block).append('<span onclick="sendRequest(' + (pageCurrent + 1) +')" class="item">></span>');
    
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