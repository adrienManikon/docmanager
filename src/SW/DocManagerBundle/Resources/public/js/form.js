$(function(){
        
    $(".select-tools").each(function(){
        
        $(".rows-select option", this).hide();
        value = $(".header-select select option:first-child", this).val();
        
        $("."+value, this).show();
                
        $(".header-select select", this).change(function(){
            
            value = $( "option:selected", this).val();
            $(".rows-select option", this.parentNode.parentNode).hide();
            $("."+value, this.parentNode.parentNode).show();
            
        });
                
    });    
    
});