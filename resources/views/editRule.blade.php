<!DOCTYPE html>
<html>
<title>W3.CSS Template</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
html,body,h1,h2,h3,h4,h5 {font-family: "Raleway", sans-serif}
</style>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
    var searchValue = '{{ $rule->search_value }}';
    // $.getScript('{{ URL::to('js/editRule.js') }}');
</script>
<script type="text/javascript" src="{{ URL::to('js/editRule.js') }}"></script>
{{-- <script type="text/javascript" src="{{ URL::to('js/autofill.js') }}"></script> --}}
<link rel="stylesheet" href="{{ URL::to('css/custom.css') }}">



<body class="w3-light-grey">
  <div class="info" id="info">

  </div>

<!-- Top container -->
<div class="w3-bar w3-top w3-black w3-large" style="z-index:4">
  <button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-light-grey" onclick="w3_open();"><i class="fa fa-bars"></i>  Menu</button>
  <span class="w3-bar-item w3-right">Logo</span>
</div>

<!-- Sidebar/menu -->
<nav class="w3-sidebar w3-collapse w3-white w3-animate-left" style="z-index:3;width:300px;" id="mySidebar"><br>
  <div class="w3-container w3-row">
    <div class="w3-col s4">
      <img src="/w3images/avatar2.png" class="w3-circle w3-margin-right" style="width:46px">
    </div>
    <div class="w3-col s8 w3-bar">
      <span>Welcome, <strong>Mike</strong></span><br>
      <a href="#" class="w3-bar-item w3-button"><i class="fa fa-envelope"></i></a>
      <a href="#" class="w3-bar-item w3-button"><i class="fa fa-user"></i></a>
      <a href="#" class="w3-bar-item w3-button"><i class="fa fa-cog"></i></a>
    </div>
  </div>
  <hr>
  <div class="w3-container">
    <h5>Dashboard</h5>
  </div>
  <div class="w3-bar-block">
    <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu"><i class="fa fa-remove fa-fw"></i>  Close Menu</a>
    <a href="#" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa-users fa-fw"></i>  Overview</a>
    <a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-eye fa-fw"></i>  Views</a>
    <a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-users fa-fw"></i>  Traffic</a>
    <a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-bullseye fa-fw"></i>  Geo</a>
    <a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-diamond fa-fw"></i>  Orders</a>
    <a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-bell fa-fw"></i>  News</a>
    <a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-bank fa-fw"></i>  General</a>
    <a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-history fa-fw"></i>  History</a>
    <a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-cog fa-fw"></i>  Settings</a><br><br>
  </div>
</nav>


<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<!-- !PAGE CONTENT! -->
<div class="w3-main w3-padding-32" style="margin-left:300px;margin-top:43px;">

  <!-- Header -->
<div class="w3-panel w3-pale-green">
  <h3><i class="fa fa-envelope fa-fw"></i>SMS notification rule setup</h3>
</div>

<form class="w3-container w3-card-4" novalidate method="POST" action="{{ route('rule_store') }}">
  {{ csrf_field() }}
  <label class="w3-text-teal"><b>Search Stop</b></label>
  <input class="w3-input w3-border w3-light-grey w3-animate-input" type="text" style="width:30%" id="search" name="search" value="{{ $rule->search_value }}">

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
  <button type="submit" class="w3-btn w3-blue-grey">Create</button>
</form>

  <hr>

  <!-- Footer -->
  <footer class="w3-container w3-padding-16 w3-light-grey">
    <h4>FOOTER</h4>
    <div class="trafi">
      <p><a href="https://www.trafi.com">Powered by TRAFI</a></p>
      <img style="max-width:120px" src="{{ URL::to('js/59b979e35daa830001026d48_Logo.svg') }}" alt="">

    </div>
  </footer>

  <!-- End page content -->
</div>

<script>
// Get the Sidebar
var mySidebar = document.getElementById("mySidebar");

// Get the DIV with overlay effect
var overlayBg = document.getElementById("myOverlay");

// Toggle between showing and hiding the sidebar, and add overlay effect
function w3_open() {
    if (mySidebar.style.display === 'block') {
        mySidebar.style.display = 'none';
        overlayBg.style.display = "none";
    } else {
        mySidebar.style.display = 'block';
        overlayBg.style.display = "block";
    }
}

// Close the sidebar with the close button
function w3_close() {
    mySidebar.style.display = "none";
    overlayBg.style.display = "none";
}
</script>

</body>
</html>
