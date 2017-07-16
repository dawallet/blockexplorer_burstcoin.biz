$(function() {        
  $("#faucetForm").submit(function(event) {
    event.preventDefault();
    $('button[type=submit]').attr('disabled',true).text('Please wait...');

    var $form = $(this);
    var posting = $.post($form.attr("action"), $("#faucetForm").serialize());

    posting.done(function(data) {
      $("#faucetResult").empty().append(data);
    });
  });
});