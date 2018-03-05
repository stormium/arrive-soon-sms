
var stops = {};
var namesArray = [];
var selectedStopIndex;
var selectedStopName;
var selectedStopId;
var stopCoords = {};

var directionsArray = [];
var selectedDirectionIndex;
var departuresFulldata = {};

$( function searchStop() {
  $( "#search" ).autocomplete({

    source: function( request, response ) {
      $.ajax( {
        url: 'http://api-ext.trafi.com/locations?region=vilnius&api_key=4194f417c45ce354aa7994dcd6594cc7',
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
      $('#stop').find('option').remove();
      $('.w3-radio').prop('disabled', true);

      selectedStopIndex = jQuery.inArray( ui.item.value, namesArray );
      selectedStopName = ui.item.value;
      stopCoords = stops[selectedStopIndex].Coordinate;
      console.log( "Selected: " + ui.item.value + " aka " + selectedStopIndex);
      $("#stop").append('<option value="">Select Stop according direction</option>');
      getStopsOptions();

      // if ($('#departures option').length == 0) {
      //     console.log('byb');
      //     $('.w3-radio').prop('disabled', true);
      // }

      $('#stop').on('change', function()
      {
          $('#departures').find('option').remove();
          $('.w3-radio').prop('disabled', true);
          selectedStopId = this.value;
          getDirectionOptions(selectedStopId);
          generateDeparturesOptions(0, 0);
      });

      $('#directions').on('change', function()
      {
          $('#departures').find('option').remove();
          $('.w3-radio').prop('disabled', false);
          selectedDirectionIndex = $('#directions').prop('selectedIndex');
          generateDeparturesOptions(selectedDirectionIndex, 0);
      });

      $('.w3-radio').on('change', function()
      {
          var selectedWorkdayOptionValue = $('.w3-radio:checked').val();
          generateDeparturesOptions(selectedDirectionIndex, selectedWorkdayOptionValue);
      });

      }


  });
}

);

function getStopsOptions() {
  $.ajax( {
    url: 'http://api-ext.trafi.com/stops/nearby?api_key=4194f417c45ce354aa7994dcd6594cc7',
    dataType: 'json',
    data: {
       lat: stopCoords.Lat,
       lng: stopCoords.Lng,
       radius: 200
    },
    success: function( data ) {
      console.log(data);

        for (var i = 0; i < data.Stops.length; i++) {

          if (data.Stops[i].Name == selectedStopName && data.Stops[i].Direction != '') {
              console.log(data.Stops[i].Name);
              $("#stop").append('<option value=' + data.Stops[i].Id + '>' + data.Stops[i].Direction + '</option>');
          }

        }

    },
    error: function() {
      console.log('An error has occurred');
    },
  } );
}

function getDirectionOptions(selectedStopId) {
  $.ajax( {
    url: 'http://api-ext.trafi.com/departures?api_key=4194f417c45ce354aa7994dcd6594cc7',
    dataType: 'json',
    data: {
       stop_id: selectedStopId,
       region: 'vilnius'
    },
    success: function( data ) {
      //console.log(data);
      directionsArray = data.Schedules;
      $('#directions').find('option').remove();
      for (var i = 0; i < data.Schedules.length; i++) {

          //console.log(data.Schedules[i].Name);
          $("#directions").append('<option value=' + data.Schedules[i].ScheduleId + '>' + data.Schedules[i].Name + ' ' + data.Schedules[i].Destination + '</option>');
      }

    },
    error: function() {
      console.log('An error has occurred');
    },
  } );
}

function generateDeparturesOptions(selectedDirectionIndex, weekDay) {
  $('#departures').find('option').remove();

    if (!$.isEmptyObject(departuresFulldata)) {
      console.log(departuresFulldata);
    departuresArray = departuresFulldata.scheduled.days[weekDay].scheduledTimes;
    $('#departures').find('option').remove();
    for (var i = 0; i < departuresArray.length; i++) {

        $("#departures").append('<option value=' + departuresArray[weekDay].exactTime + '>' + departuresArray[weekDay].exactTime + '</option>');
    }
    } else {
      var scheduleId = directionsArray[selectedDirectionIndex].ScheduleId;
      var trackId = directionsArray[selectedDirectionIndex].TrackId;

      var url= 'https://www.trafi.com/api/times/vilnius/scheduled?scheduleId=' +  scheduleId + '&trackId=' + trackId + '&stopId=' + selectedStopId;
      url = 'proxy.php?url='+url;

      $.ajax( {
        type : "GET",
        url: url,
        dataType: 'json',
        success: function( data ) {
          departuresFulldata = data;
          departuresArray = departuresFulldata.scheduled.days[weekDay].scheduledTimes;
          console.log(departuresArray);
          $('#departures').find('option').remove();
          for (var i = 0; i < departuresArray.length; i++) {

              $("#departures").append('<option value=' + departuresArray[weekDay].exactTime + '>' + departuresArray[weekDay].exactTime + '</option>');
          }

        },
        error: function() {
          console.log('An error has occurred');
        },
      } );
    }



}
