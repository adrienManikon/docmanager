$("button").each(function(){
    $(this).click(function(){
        
        $.ajax({
            type: 'GET',
            url: $(this).attr("data-url")
        });
        
    })
});