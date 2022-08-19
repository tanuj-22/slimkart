
let x = false;   
function toastFunc() {
  
  $(document).ready(function(){
    if(x == false){
    $('.toast').toast('show');
    x = true;
    }
    else if(x == true){
      $('.toast').toast('hide');
      x = false;
    }
  });
}
      
function toastFuncEnd() {
  
  $(document).ready(function(){
    $('.toast').toast('hide');
  });
}