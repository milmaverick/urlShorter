$(document).ready(function(){
  //При клике на кнопку GET SHORT -------------------------------------------
  $('#button-addon2').on('click', ()=>{
    let fulURl= $('.form-control').val();
    if(fulURl) {
      $.ajax({
        url : 'index/getShort/' ,
        method : 'GET' ,
        data: {
          fulURl: fulURl,
        },
        success : function(msg){
          if (msg =='Not Found' ) {
            alert(msg);
          }
          else {
            $('.response').html(msg);
          }
        },
        error : function(){
          alert("Не корректный URL");
        }
      });
    }
    else alert('No URl :C');
  });
});
