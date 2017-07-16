$(function() {        
  $("#surfbarForm").submit(function(event) {
    event.preventDefault();
    $('button[type=submit]').attr('disabled',true).text('Please wait...');

    var $form = $(this);
    var posting = $.post($form.attr("action"), $("#surfbarForm").serialize());

    posting.done(function(data) {
      $("#surfbarResult").empty().append(data);
    });
  });
});