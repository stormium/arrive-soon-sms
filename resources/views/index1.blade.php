@extends('layouts.app_original')
@section('content')


  <!-- Header -->
<div class="w3-panel w3-pale-green">
  <h3><i class="fa fa-envelope fa-fw"></i>SMS notification rule setup</h3>
</div>

<form class="w3-container w3-card-4" novalidate method="POST" action="{{ route('rule_store') }}">
  {{ csrf_field() }}
  <label class="w3-text-teal"><b>Search Stop</b></label>
  <input class="w3-input w3-border w3-light-grey w3-animate-input" type="text" style="width:30%" id="search" name="searchValue">

  <label for="stop" class="w3-text-teal"><b>Stop</b></label><br>
  <select class="w3-select w3-border w3-light-grey w3-animate-input" name="stop" id="stop" style="width:30%">
  </select>
  @if ($errors->has('stop'))
    <div class="w3-panel w3-orange w3-round">
      <p>{{ $errors->first('stop') }}</p>
    </div>
  @endif
  <br>
  <label for="directions" class="w3-text-teal"><b>Direction</b></label><br>
  <select class="w3-select w3-border w3-light-grey w3-animate-input" name="directions" id="directions" style="width:30%">
  </select>
  @if ($errors->has('directions'))
    <div class="w3-panel w3-orange w3-round">
      <p>{{ $errors->first('directions') }}</p>
    </div>
  @endif
  <br>
  <label for="departures" class="w3-text-teal"><b>Departures</b></label><br>
  <select class="w3-select w3-border w3-light-grey w3-animate-input" name="departures" id="departures" style="width:30%">
  </select>
  @if ($errors->has('departures'))
    <div class="w3-panel w3-orange w3-round">
      <p>{{ $errors->first('departures') }}</p>
    </div>
  @endif
  <p>
  <input class="w3-radio" type="radio" name="departuresDayOption" value="0" checked>
  <label>Today</label></p>
  <p>
  <input class="w3-radio" type="radio" name="departuresDayOption" value="1">
  <label>Workday</label></p>
  <p>
  <input class="w3-radio" type="radio" name="departuresDayOption" value="2">
  <label>Saturday</label></p>
  <p>
  <input class="w3-radio" type="radio" name="departuresDayOption" value="3">
  <label>Sunday</label></p>
  <label for="offset" class="w3-text-teal"><b>Generate SMS before:</b></label><br>
  <select class="w3-select w3-border w3-light-grey w3-animate-input" name="offset" id="offset" style="width:30%">
    <option value="5">5 min</option>
    <option value="6">6 min</option>
    <option value="7">7 min</option>
    <option value="8">8 min</option>
    <option value="9">9 min</option>
    <option value="10">10 min</option>
    <option value="15">15 min</option>
    <option value="20">20 min</option>
  </select>
  @if ($errors->has('offset'))
    <div class="w3-panel w3-orange w3-round">
      <p>{{ $errors->first('offset') }}</p>
    </div>
  @endif
  <br>
  <input class="objectName" type="hidden" name="objectName" value="">
  <input class="iconUrl" type="hidden" name="iconUrl" value="">
  <button type="submit" class="w3-btn w3-blue-grey">Create</button>
</form>

<hr>
<div class="w3-card-4 w3-pale-green w3-container">
  <h3><i class="fa fa-list fa-fw"></i>My Rules</h3>
  <table class="w3-table mylist">
  <tr>
    <th>Direction</th>
    <th>Stop</th>
    <th>Weekday</th>
    <th>Departure</th>
    <th>Actions</th>
  </tr>
  @foreach ($myRules as $item)
    <tr>
      <td><img src="https://cdn.trafi.com/icon.ashx?size=64&style=v2&src=transport/{{ $item->icon_url }}" class="w3-circle" style="width:32px"></td>
      <td>{{ $item->search_value }}</td>
      <td>{{ $item->weekday }}</td>
      <td>{{ $item->departure_at }}</td>
      <td>
        <a href="{{ route('editRule',$item->id) }}"><button class="w3-btn w3-white w3-border w3-border-teal w3-round w3-padding-small w3-small">Edit</button></a>
      </td>
      <td>
        <a href="{{ route('removeRule',$item->id) }}"><button class="w3-btn w3-white w3-border w3-border-red w3-round w3-padding-small w3-small">X</button></a>
      </td>
    </tr>
  @endforeach
  </table>
</div>
@endsection
