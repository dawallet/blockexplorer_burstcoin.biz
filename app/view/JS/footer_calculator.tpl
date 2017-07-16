$(function() {        
  $("input, select").change(miningCalculator).keyup(miningCalculator);
  function miningCalculator() {                
    var a = $("#plotUnit").val().trim(),
	c = parseInt($("#baseTarget").val()),
	d = parseInt($("#coinsPerBlock").val()),
	b = 1;
    switch (a) {
	case "PB":
	    b *= 1E3;
	case "TB":
	    b *= 1E3;
	case "GB":
	    b *= 1E3
    }
    a = 4 * parseFloat($("#plotSize").val()) * b;
    a = Math.pow(2, 64) / c / a;
    d = 3600 * d / a;
    a = 24 * d;
    $("#burstPerDay").text(Math.round(a)+" Burst");
    $("#burstPerWeek").text(Math.round(7*a)+" Burst");
    $("#burstPerMonth").text(Math.round(30*a)+" Burst");
  }
  miningCalculator();

  $("#currency").change(function() {
    if ($(this).val() == "USD") {
      $("#valueCurrency").val("{$btcUSD}");
      $(".input-currency").text("$ / BTC");
    } else if ($(this).val() == "EUR") {
      $("#valueCurrency").val("{$btcEUR}");
      $(".input-currency").text("€ / BTC");
    }
  });
  $("input, select").change(priceCalculator).keyup(priceCalculator);
  function priceCalculator() {                
    if ($("#currency").val() == "USD") {
      $("#currencySign").text("$");                    
    } else {
      $("#currencySign").text("€");
    }
    if ($("#cryptoCurrency").val() == "Burst") {
      $("#crypto").text("BTC");
      var burstbtc = $("#amount").val()*$("#rate").val(),
	  currencyamount = burstbtc*$("#valueCurrency").val();
    } else {
      $("#crypto").text("Burst");
      var burstbtc = $("#amount").val()/$("#rate").val(),
	  currencyamount = $("#amount").val()*$("#valueCurrency").val();
    }
    $("#cryptoAmount").text(burstbtc.toFixed(8));
    $("#currencyAmount").text(currencyamount.toFixed(2));
  }
  priceCalculator();
});