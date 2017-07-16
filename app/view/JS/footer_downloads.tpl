$(function() {        
  $("select[name=filter]").change(function() {
      window.location.href = "{$httpUrl}downloads/"+$("select[name=filter]").val();
  });
});