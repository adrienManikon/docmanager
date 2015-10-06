var nbinput = 1;

function addFileInput(element) {
        
    if(nbinput < 5) {
        nbinput++;
        
        var url = element.getAttribute("data-url");
        
        $.ajax({
            type: "POST",
            url: url,
            async: true,
            dataType: "html",
            success: function(response) 
            {
                //console.log(response);
                var newline = jQuery.parseHTML(response);
                //newline.hide();
                $("#inputBlock").append(response);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown)
            {
                // stuff there
            },
        });/*
        var $newline = $("#fileInput1").clone().prop('id', "fileInput" + nbinput);
        $newline.hide();
        $newline.appendTo("#inputBlock").show("slow");*/
    }else {
        showDialog("#limitFileDialog");
    }        
    
}