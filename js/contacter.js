$("#contactForm").on("submit", function (event) {
  submitForm();
});

function submitForm(){
    // Initiate Variables With Form Content
    var nom = $("#nom").val();
    var mail = $("#mail").val();
    var message = $("#message").val();

    $.post('fonction/contacter.php',
      {
        nom: nom,
        mail: mail,
        message:message
      }
      ,
      function(text){
          alert(text);
          if (text == "success"){
            $("#contactForm")[0].reset();
            submitMSG(true, "Message envoyé.")
          } else {
            submitMSG(false,"Message non envoyé !");
          }
      }
    );
}


function submitMSG(valid, msg){
    if(valid){
        var msgClasses = "h3 text-center text-success";
    } else {
        var msgClasses = "h3 text-center text-danger";
    }
    $("#msgSubmit").empty();
    $("#msgSubmit").removeClass().addClass(msgClasses).append(msg);
}
