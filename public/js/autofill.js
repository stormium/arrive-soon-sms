
var stops = {};
var namesArray = [];
var selectedStopIndex;
var selectedStopName;
var selectedStopId;
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
      $('#direction').find('option').remove();

      selectedStopIndex = jQuery.inArray( ui.item.value, namesArray );
      selectedStopName = ui.item.value;
      stopCoords = stops[selectedStopIndex].Coordinate;
      console.log( "Selected: " + ui.item.value + " aka " + selectedStopIndex);
      getDirectionOptions();
      $('#direction').on('change', function()
      {
          selectedStopId = this.value;
          getSchedulesOptions(selectedStopId);
      });

      }


  });
}

);



function getDirectionOptions() {
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
      directionsArray = [];
        for (var i = 0; i < data.Stops.length; i++) {

          if (data.Stops[i].Name == selectedStopName && data.Stops[i].Direction != '') {
              console.log(data.Stops[i].Name);
              $("#direction").append('<option value=' + data.Stops[i].Id + '>' + data.Stops[i].Direction + '</option>');
          }

        }

    },
    error: function() {
      console.log('An error has occurred');
    },
  } );
}

function getSchedulesOptions(selectedStopId) {
  $.ajax( {
    url: 'http://api-ext.trafi.com/departures?api_key=sandbox_key_not_for_production',
    dataType: 'json',
    data: {
       stop_id: selectedStopId,
       region: 'vilnius'
    },
    success: function( data ) {
      console.log(data);
      $('#schedules').find('option').remove();
      for (var i = 0; i < data.Schedules.length; i++) {

          console.log(data.Schedules[i].Name);
          $("#schedules").append('<option value=' + data.Schedules[i].ScheduleId + '>' + data.Schedules[i].Name + ' ' + data.Schedules[i].Destination + '</option>');
      }

    },
    error: function() {
      console.log('An error has occurred');
    },
  } );
}

function generateDeparturesOptions() {
  $('#departures').find('option').remove();
  for (var i = 0; i < data.Schedules.length; i++) {

      console.log(data.Schedules[i].Name);
      $("#schedules").append('<option value=' + data.Schedules[i].ScheduleId + '>' + data.Schedules[i].Name + ' ' + data.Schedules[i].Destination + '</option>');
  }
}