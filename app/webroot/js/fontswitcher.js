
        $(document).ready(function(){
  
  $("#small").click(function(event){
    event.preventDefault();
    $("body").animate({"font-size":"12px"});
  
    
  });
  
  $("#medium").click(function(event){
    event.preventDefault();
    $("body").animate({"font-size":"16px"});
    
    
  });
  
  $("#large").click(function(event){
    event.preventDefault();
    $("body").animate({"font-size":"24px"});
    
    
  });
  
  $( "a" ).click(function() {
   $("a").removeClass("selected");
  $(this).addClass("selected");
  
 });

});
    