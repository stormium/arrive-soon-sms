"use strict";
var stops = {};
var namesArray = [];
var selectedStopIndex;
var selectedStopName;
var selectedStopId;
var stopCoords = {};

var directionsArray = [];
var selectedDirectionIndex;
var departuresFulldata = {};
var departuresArray = [];

var stopsArray = [];

$( function () {
  if (rule) {

var return_first = function () {
  var searchValue = rule.searchValue;
  console.log(rule);
  var tmp = null;
  $.ajax( {
    url: 'http://api-ext.trafi.com/locations?region=vilnius&api_key=4194f417c45ce354aa7994dcd6594cc7',
    async:true,
    dataType: 'json',
    data: {
       q: searchValue
    },
    success: function( data ) {
      stopsArray.push( data );
    },
    error: function() {
       console.log('An error has occurred');
    },
  } );

}();
  stops = stopsArray[0];
  console.log(stopsArray);

  stopCoords = stops[0].Coordinate;
  selectedStopName = stops[0].Name;
  selectedStopIndex = 0;
  stopCoords = stops[0].Coordinate;
  console.log( "Selected: " + selectedStopName + " aka " + selectedStopIndex);
  getStopsOptions();
  selectStopOptionFromEditData(rule.stop);

}

});



      // getStopsOptions();
      //
      // // $("#stop").unbind('change'); - helps to provent double call when page is not refreshed
      // $("#stop").unbind('change');
      // $('#stop').on('change', function()
      // {
      //     $('#departures').find('option').remove();
      //     $('#directions').find('option').remove();
      //     $('input[name=departuresDayOption][value="0"]').prop('checked', true);
      //     $('.w3-radio').prop('disabled', true);
      //     selectedStopId = this.value;
      //     $("#directions").append('<option value="">Select Direction</option>');
      //     getDirectionOptions(selectedStopId);
      // });
      // $("#directions").unbind('change');
      // $('#directions').on('change', function()
      // {
      //     $('#departures').find('option').remove();
      //     $('input[name=departuresDayOption][value="0"]').prop('checked', true);
      //     $('.w3-radio').prop('disabled', false);
      //     selectedDirectionIndex = $('#directions').prop('selectedIndex');
      //     selectedDirectionIndex--;
      //     generateDeparturesOptions(selectedDirectionIndex, 0);
      //     generateObjectNameInputValue(selectedDirectionIndex);
      // });
      //
      // $('.w3-radio').on('change', function()
      // {
      //     var selectedWorkdayOptionValue = $('.w3-radio:checked').val();
      //     updateDeparturesOptionsWeekdayChanged(selectedWorkdayOptionValue);
      // });



// } else {
  // $( function searchStop() {
  //   $( "#search" ).autocomplete({
  //
  //     source: function( request, response ) {
  //       $.ajax( {
  //         url: 'http://api-ext.trafi.com/locations?region=vilnius&api_key=4194f417c45ce354aa7994dcd6594cc7',
  //         dataType: 'json',
  //         data: {
  //            q: request.term
  //         },
  //         success: function( data ) {
  //           namesArray = [];
  //           for (var i = 0; i < data.length; i++) {
  //             namesArray.push(data[i].Name);
  //           }
  //           response( namesArray );
  //           stops = data;
  //           },
  //         error: function() {
  //
  //            console.log('An error has occurred');
  //         },
  //       } );
  //     },
  //     minLength: 3,
  //     select: function( event, ui ) {
  //       $('#stop').find('option').remove();
  //       $('#departures').find('option').remove();
  //       $('#directions').find('option').remove();
  //       $('input[name=departuresDayOption][value="0"]').prop('checked', true);
  //       $('.w3-radio').prop('disabled', true);
  //
  //       selectedStopIndex = jQuery.inArray( ui.item.value, namesArray );
  //       selectedStopName = ui.item.value;
  //       stopCoords = stops[selectedStopIndex].Coordinate;
  //       console.log( "Selected: " + ui.item.value + " aka " + selectedStopIndex);
  //       $("#stop").append('<option value="">Select Stop according direction</option>');
  //       getStopsOptions();
  //
  //       // $("#stop").unbind('change'); - helps to provent double call when page is not refreshed
  //       $("#stop").unbind('change');
  //       $('#stop').on('change', function()
  //       {
  //           $('#departures').find('option').remove();
  //           $('#directions').find('option').remove();
  //           $('input[name=departuresDayOption][value="0"]').prop('checked', true);
  //           $('.w3-radio').prop('disabled', true);
  //           selectedStopId = this.value;
  //           $("#directions").append('<option value="">Select Direction</option>');
  //           getDirectionOptions(selectedStopId);
  //       });
  //       $("#directions").unbind('change');
  //       $('#directions').on('change', function()
  //       {
  //           $('#departures').find('option').remove();
  //           $('input[name=departuresDayOption][value="0"]').prop('checked', true);
  //           $('.w3-radio').prop('disabled', false);
  //           selectedDirectionIndex = $('#directions').prop('selectedIndex');
  //           selectedDirectionIndex--;
  //           generateDeparturesOptions(selectedDirectionIndex, 0);
  //           generateObjectNameInputValue(selectedDirectionIndex);
  //       });
  //
  //       $('.w3-radio').on('change', function()
  //       {
  //           var selectedWorkdayOptionValue = $('.w3-radio:checked').val();
  //           updateDeparturesOptionsWeekdayChanged(selectedWorkdayOptionValue);
  //       });
  //
  //       }
  //   });
  // }
  //
  // );
// }


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
        for (var i = 0; i < data.Stops.length; i++) {

          if (data.Stops[i].Name == selectedStopName && data.Stops[i].Direction != '') {
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

      directionsArray = [];
      directionsArray = data.Schedules;
      console.log(directionsArray);
      for (var i = 0; i < data.Schedules.length; i++) {
        $("#directions").append('<option value=' + data.Schedules[i].ScheduleId + '>' + data.Schedules[i].Name + ' ' + data.Schedules[i].Destination + '</option>');
        //change background color of direction according transport type
        var bColor = data.Schedules[i].Color;
        $("#directions option:last-of-type").css("background-color","#" + bColor + "");
      }
    },
    error: function() {
      console.log('An error has occurred');
    },
  } );
}

function generateDeparturesOptions(selectedDirectionIndex, weekDay) {
  $('#departures').find('option').remove();

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
        $('#departures').find('option').remove();
        for (var i = 0; i < departuresArray.length; i++) {

            $("#departures").append('<option value=' + departuresArray[i].exactTime + '>' + departuresArray[i].exactTime + '</option>');
        }
      },
      error: function() {
        console.log('An error has occurred');
      },
    } );
  }


function updateDeparturesOptionsWeekdayChanged(weekDay) {
  $('#departures').find('option').remove();
  if (departuresFulldata.scheduled.days[weekDay] != null) {
    departuresArray = departuresFulldata.scheduled.days[weekDay].scheduledTimes;
    for (var i = 0; i < departuresArray.length; i++) {

        $("#departures").append('<option value=' + departuresArray[i].exactTime + '>' + departuresArray[i].exactTime + '</option>');
    }
  } else if (departuresFulldata.scheduled.days[weekDay] == null) {
    $("#departures").append("<option value=''>no departures found</option>");
  }

}

function generateObjectNameInputValue(selectedDirectionIndex) {
  var name = directionsArray[selectedDirectionIndex].Name;
  $('.objectName').val(name);
}

function selectStopOptionFromEditData (stopId) {

    var value = stopId;
          $("#stop option").each(function(){
          console.log('radau');
          if($(this).val()==value){ // EDITED THIS LINE

            $(this).attr("selected","selected");
        }
    });
}
