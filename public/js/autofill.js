
var stops = {};
var namesArray = [];
var selectedStopIndex;
var stopCoords = {};

$( function searchStop() {
  $( "#stop" ).autocomplete({

    source: function( request, response ) {
      $.ajax( {
        url: 'http://api-ext.trafi.com/locations?region=vilnius&api_key=sandbox_key_not_for_production',
        dataType: 'json',
        data: {
           q: request.term
        },
        success: function( data ) {
          namesArray = [];
          for (var i = 0; i < data.length; i++) {
            namesArray.push(data[i].Name);
          }
          response( namesArray );
          stops = data;
          },
        error: function() {

           console.log('An error has occurred');
        },
      } );
    },
    minLength: 3,
    select: function( event, ui ) {

      selectedStopIndex = jQuery.inArray( ui.item.value, namesArray );
      stopCoords = stops[selectedStopIndex].Coordinate;
      console.log( "Selected: " + ui.item.value + " aka " + selectedStopIndex);
      addDirectionOptions();
      }


  });
}

);

function addDirectionOptions() {
  $.ajax( {
    url: 'http://api-ext.trafi.com/stops/nearby?api_key=sandbox_key_not_for_production',
    dataType: 'json',
    data: {
       lat: stopCoords.Lat,
       lng: stopCoords.Lng,
       radius: 200
    },
    success: function( data ) {
      console.log(data);
      // namesArray = [];
      // for (var i = 0; i < data.length; i++) {
      //   namesArray.push(data[i].Name);
      // }
      // response( namesArray );
      // stops = data;
      },
    error: function() {
      console.log('An error has occurred');
    },
  } );
}
