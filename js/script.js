jQuery(document).ready(function($){

  $(document).on( 'change', '.smsiSelect', function(){
    $(".providerDiv").fadeOut();

    $("#"+$(this).find(':selected').data('provider')).fadeIn();
  });


  $(document).on( 'click', '.smsiFailed, .smsiHold, .smsiProcessing, .smsiCompleted, .smsiRefunded, .smsiCancelled, .smsiPending', function(){

    var clickedElement = $(this);
    var textVariable = clickedElement.text().replace("Add variable ","");
    var textarea = $("."+$(this).attr('class')+"TextArea");

    textarea.val(textarea.val()+" "+textVariable);
  });

});
