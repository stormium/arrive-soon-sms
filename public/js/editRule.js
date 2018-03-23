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
      stops = data;
      stopCoords = stops[0].Coordinate;
      selectedStopName = stops[0].Name;
      selectedStopIndex = 0;
      stopCoords = stops[0].Coordinate;
      $("#stop").append('<option value="">Select Stop</option>');
      getStopsOptions();
      selectedStopId = rule.stop;
      $("#directions").append('<option value="">Select Direction</option>');
      getDirectionOptions(rule.stop);
      var weekDay = weekDayConverter(rule.weekday);
      selectWeekdayOptionWhenEdit(weekDay);
      selectOffsetOptionWhenEdit(rule.offset);
      $('.objectName').val(rule.objectName);
      $('.iconUrl').val(rule.iconUrl);
      console.log(rule);
      changeListener ();

    },
    error: function() {
       console.log('An error has occurred');
    },
  } );

}();

}

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
    $('#departures').find('option').remove();
    $('#directions').find('option').remove();
    $('input[name=departuresDayOption][value="0"]').prop('checked', true);
    $('.w3-radio').prop('disabled', true);

    selectedStopIndex = jQuery.inArray( ui.item.value, namesArray );
    selectedStopName = ui.item.value;
    stopCoords = stops[selectedStopIndex].Coordinate;
    console.log( "Selected: " + ui.item.value + " aka " + selectedStopIndex);
    $("#stop").append('<option value="">Select Stop according direction</option>');
    getStopsOptions();

    // $("#stop").unbind('change'); - helps to provent double call when page is not refreshed
    $("#stop").unbind('change');
    $('#stop').on('change', function()
    {
        $('#departures').find('option').remove();
        $('#directions').find('option').remove();
        $('input[name=departuresDayOption][value="0"]').prop('checked', true);
        $('.w3-radio').prop('disabled', true);
        selectedStopId = this.value;
        $("#directions").append('<option value="">Select Direction</option>');
        getDirectionOptions(selectedStopId);
    });
    $("#directions").unbind('change');
    $('#directions').on('change', function()
    {
        $('#departures').find('option').remove();
        $('input[name=departuresDayOption][value="0"]').prop('checked', true);
        $('.w3-radio').prop('disabled', false);
        selectedDirectionIndex = $('#directions').prop('selectedIndex');
        selectedDirectionIndex--;
        generateDeparturesOptions(selectedDirectionIndex, 0);
        generateObjectNameInputValue(selectedDirectionIndex);
        generateObjectIconUrlInputValue(selectedDirectionIndex);
    });

    $('.w3-radio').on('change', function()
    {
        var selectedWorkdayOptionValue = $('.w3-radio:checked').val();
        updateDeparturesOptionsWeekdayChanged(selectedWorkdayOptionValue);
    });

    }
});

});

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
              var selected = false;
              if (rule) {
                if (rule.stop == data.Stops[i].Id) {
                  selected = true;
                }
              }
              $("#stop").append($('<option>', {
                value: data.Stops[i].Id,
                text: data.Stops[i].Direction,
                selected: selected
              }));
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
      for (var i = 0; i < data.Schedules.length; i++) {
        var text = data.Schedules[i].Name+' '+data.Schedules[i].Destination;
        var selected = false;
        if (rule) {
          if (rule.scheduleId == data.Schedules[i].ScheduleId) {
            selected = true;
            selectedDirectionIndex = i;
            var weekDay = weekDayConverter(rule.weekday);
            generateDeparturesOptions(selectedDirectionIndex, weekDay);
          }
        }
        $("#directions").append($('<option>', {
          value: data.Schedules[i].ScheduleId,
          text: text,
          selected: selected
        }));
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
    console.log(selectedDirectionIndex);
    console.log(weekDay);
    console.log(directionsArray);
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
        departuresArray =  departuresFulldata.scheduled.days[weekDay].scheduledTimes;
        $('#departures').find('option').remove();
        for (var i = 0; i < departuresArray.length; i++) {
            var selected = false;
            if (rule) {
              var timeWithOffset = timeOffsetConverter (rule.departureAt);
              if (timeWithOffset == departuresArray[i].exactTime) {
                console.log(timeWithOffset);
                console.log(departuresArray[i].exactTime);
                selected = true;
              }
            }
            $("#departures").append($('<option>', {
              value: departuresArray[i].exactTime,
              text: departuresArray[i].exactTime,
              selected: selected
            }));
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

function generateObjectIconUrlInputValue(selectedDirectionIndex) {
  var iconUrl = directionsArray[selectedDirectionIndex].IconUrl;
  $('.iconUrl').val(iconUrl);
}

function weekDayConverter (weekday) {
  var $value = 0;
  if (weekday == 'Workday') {
    $value = 1;
  } else if (weekday == 'Saturday') {
    $value = 2;
  } else if (weekday == 'Sunday') {
    $value = 3;
  }
  return $value;
}

function timeOffsetConverter (timeInUTC) {
  var t = timeInUTC.split(':');
  var minutes = (+t[0]) * 60 + (+t[1]);
  var d = new Date();
  var n = d.getTimezoneOffset();
  minutes = minutes - n;
  var min = minutes % 60;
  var hours = Math.floor(minutes / 60);
  min = (min < 10 ? '0' : '') + min;
  hours = (hours < 10 ? '0' : '') + hours;
  return hours + ':' + min;
}

function selectWeekdayOptionWhenEdit(weekDay) {
  $("input[name='departuresDayOption']").each(function () {
      var $this = $(this).val();
      if ($this == weekDay) {
        $(this).prop('checked', true);
      }
  });
}

function selectOffsetOptionWhenEdit(offsetVal) {
  $("#offset option").each(function () {
      var $this = $(this).val();
      if ($this == offsetVal) {
        $(this).prop('selected', true);
      }
  });
}

function changeListener () {
  $("#stop").unbind('change');
  $('#stop').on('change', function()
  {
      $('#departures').find('option').remove();
      $('#directions').find('option').remove();
      $('input[name=departuresDayOption][value="0"]').prop('checked', true);
      $('.w3-radio').prop('disabled', true);
      selectedStopId = this.value;
      $("#directions").append('<option value="">Select Direction</option>');
      getDirectionOptions(selectedStopId);
  });
  $("#directions").unbind('change');
  $('#directions').on('change', function()
  {
      $('#departures').find('option').remove();
      $('input[name=departuresDayOption][value="0"]').prop('checked', true);
      $('.w3-radio').prop('disabled', false);
      selectedDirectionIndex = $('#directions').prop('selectedIndex');
      selectedDirectionIndex--;
      generateDeparturesOptions(selectedDirectionIndex, 0);
      generateObjectNameInputValue(selectedDirectionIndex);
      generateObjectIconUrlInputValue(selectedDirectionIndex);
  });

  $('.w3-radio').on('change', function()
  {
      var selectedWorkdayOptionValue = $('.w3-radio:checked').val();
      updateDeparturesOptionsWeekdayChanged(selectedWorkdayOptionValue);
  });
}
