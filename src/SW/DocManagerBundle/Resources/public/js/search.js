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