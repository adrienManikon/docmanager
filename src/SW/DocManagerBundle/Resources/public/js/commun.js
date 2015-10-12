function showDialog(id){
        var dialog = $(id).data('dialog');
        dialog.open();
}

function closeDialog(id){
    var dialog = $(id).data('dialog');
    dialog.close();
}