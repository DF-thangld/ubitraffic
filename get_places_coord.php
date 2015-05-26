<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places&sensor=false"></script>
	<script src="javascript/jquery.js" type="text/javascript"></script>
    <title>Get places's coords</title>
	<script>
		var geocoder;
		$(function(){
			geocoder = new google.maps.Geocoder();
			window.setInterval(function(){
				$.ajax({
					type: "GET",
					url: "oulunliikenne_places.php?action=geocode",
					cache: false,
					dataType: "xml",
					success: function(xml) 
					{
						$(xml).find('place').each(function()
						{
							//console.log($(this));
							var company_id = $(this).find("company_id").text();
							var address = $(this).find("address").text() + ', Oulu, Finland';
							
							geocoder.geocode( { 'address': address + ',Oulu, Finland'}, function(results, status) {
								if (status == google.maps.GeocoderStatus.OK)
								{
									lat = results[0].geometry.location.A;
									lon = results[0].geometry.location.F;
									$.ajax({
										type: "GET",
										url: "oulunliikenne_places.php?action=coord&lat="+lat+"&lon="+lon+"&company_id="+company_id,
										cache: false,
										dataType: "xml",
										success: function(xml) 
										{
											console.log(address + ":" + lat + "-" + lon);
										}
									});
								}
							});
							/*$.ajax({
								type: "GET",
								url: "oulunliikenne_places.php?action=coord&lat=",
								cache: false,
								dataType: "xml",
								success: function(xml) 
								{
								
								}
							});*/
						});
					}
				});
			}, 5000);
			
		});
	</script>
  </head>

  

  </body>
</html>
