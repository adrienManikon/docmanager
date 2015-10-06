var nbinput = 1;

function addFileInput() {
    
    if(nbinput < 5) {
        nbinput++;
        var $newline = $("#fileInput1").clone().prop('id', "fileInput" + nbinput);
        $newline.hide();
        $newline.appendTo("#inputBlock").show("slow");
    }else {
        showDialog("#limitFileDialog");
    }        
    
}