<?php
    header("Content-type: text/css; charset: UTF-8");

    //default fonts
    $font_family = 'Arial, sans-serif';
	$font_weight = '400';
	$font_size = '12px';
	$line_height = '1em';
    
	//default colors
    $brand_color = '#01375d';  //base blue
    $brand_color_minor = '#809bae';  //lighter grayish blue

    $neutral_background = '#d3d3d3';  //light gray
    $neutral_background_light = '#e4e4e4';  //lighter grayish
    $neutral_background_dark = '';
    
    $text_color_light = '#ffffff';  //white
    $text_color_dark = $brand_color;
    
    $alert_color = 'red';
?>


/****************************************/
/********** Time Tracker Menu **********/
/***************************************/
.tt-menu-heading {
    margin-top: 20px;
    margin-bottom: -10px;
}

/***********************************/
/********** Page Template **********/
/***********************************/

/*** contains sidebar and page content ***/
div#tt-all-content {
    max-width: 100%;
    margin-bottom: 10px;
}

/** */.tt-page-template div#tt-content.site-content {
    margin-bottom: 10px;
}**/

div#tt-primary, div#tt-sidebar, {
    display: inline-block;
}

/*** page content to the side of sidebar ***/
div#tt-primary {
  float: left;
  width: 75%;
  max-width: 75%;
}

div#tt-content, div#tt-primary:after, div#tt-sidebar:after, div#tt-mobile-menu:after {
    clear:both;
}

/**********************************/
/********** Page Header **********/
/*********************************/
header.page-header.tt-page-header {
  padding: 0 0 10px 0;
  overflow: hidden;
  margin-bottom: 0;
}

h1.page-title.tt-page-title {
  padding: 20px;
  float: left;
  margin-block-start: 0px;
  margin-block-end: 0px;
}

.tt-header-button {
  margin-top: 20px;
  margin-right: 20px;
  float: right;
}

.page-title.tt-page-title:after, .tt-header-button:after {
  clear: both;
}

header.page-header.tt-page-header::after {
  content: "";
  clear:both;
}

/*** content below the page title ***/
#tt-main.site-main.tt-main {
    margin-left: 20px;
    margin-right: 20px;
}

/*****************************/
/********** Sidebar **********/
/*****************************/
div.tt-sidebar {
  float:left;
  width: 15%;
  background-color: <?php echo $neutral_background; ?>;
  padding: 20px;
  margin-top: 20px;
  margin-right: 20px;
  max-width: 300px;
}

.tt-sidebar-header {
  font-size: 1.3em;
  display: block;
  padding: 5px 0;
}

.tt-sidebar-hr {
  margin: 10px;
}

.tt-mobile-menu, .tt-mobile-menu a.tt-sidebar-button, .tt-mobile-menu .tt-sidebar-header, .tt-mobile-menu .tt-sidebar-hr {
	text-align: center;
}

.tt-mobile-menu-button {
    -moz-appearance: button;
    -webkit-appearance: button;
    appearance: button;
    padding: 5px 20px;
    background-color: <?php echo $brand_color; ?>;
    box-shadow: 5px 5px 8px #888888;
    color: <?php echo $text_color_light; ?>;
    font-size: 1.2em;
    line-height: 1.2em;
    text-decoration: none;
	text-align: center;
    border: none;
}

.tt-mobile-menu-button:after {
  clear:both;
}

div #tt-nav-links {
  display: none;
  margin-top: 20px;
  background-color: lightgray;
}

@media only screen and (min-width: 768px) {
	.tt-mobile-menu {
		display: none;
	}
}

@media only screen and (max-width: 768px) {
	div.tt-sidebar {
		display: none;
  	}

	.content-area {
		width: 100%;
		margin: 0px;
		border: 0px;
		padding: 0px;
	}

	div#primary.content-area.tt-content {
		width: 100%;
	}

	.content-area .site {
		margin:0px;
	}

	/** */.full-width-content .container.grid-container {
		padding: 10px;
	}**/

	h1.page-title.tt-page-title {
		padding-left: 0px;
	}

    header.page-header.tt-page-header {
      padding-top: 0px;
      padding-bottom: 0px;
	}
}

/*****************************/
/********** Buttons **********/
/****************************/
.tt-buton a {
    margin: 10px 10px 10px 20px;
    display: block;
    text-decoration: none;
}

.tt-button a:hover {
    text-decoration: none;
}

.tt-button:after, a.tt-sidebar-button:after {
    clear:both;
}

a.tt-sidebar-button, a.tt-header-button, button.end-work-timer, input[type="submit"].tt-form-button,
.tt-form input[type="submit"], .tt-form button {
    -moz-appearance: button;
    -webkit-appearance: button;
    appearance: button;
    padding: 5px 10px;
    background-color: <?php echo $brand_color; ?>;
    box-shadow: 5px 5px 8px #888888;
    color: <?php echo $text_color_light; ?>;
    font-size: 1.2em;
    line-height: 1.2em;
    text-decoration: none;
    display: block;
    border: none;
}

input[type="submit"].tt-form-button.tt-inline-button, .tt-form input[type="submit"].tt-inline-button {
    margin-right: 10px;
    margin-left: 10px;
    display: inline-block;
}

a.tt-sidebar-button:visited, a.tt-header-button:visited, input[type="submit"].tt-form-button:visited {
    color: <?php echo $text_color_light; ?>;
    text-decoration: none;
}

button.end-work-timer:hover, a.tt-sidebar-button:hover, a.tt-header-button:hover, input[type="submit"].tt-form-button:hover, 
.tt-form input[type="submit"]:hover, .tt-form button:hover {
    background-color: <?php echo $brand_color_minor; ?>;
    color: <?php echo $text_color_dark; ?>;
    text-decoration: none;
    font-weight: bold;
    border: none;
}

a.tt-sidebar-button {
    width: 85%; /*of sidebar*/
    margin: 10px auto;
}

button.clear-error {
    background-color: <?php echo $neutral_background; ?>;
    color: <?php echo $text_color_dark; ?>;
    padding: 5px 10px;
    font-size: 0.8em;
    border-radius: 0;
    margin-left: 10px;
    box-shadow: 5px 5px 8px #888888;
}

button.clear-error:hover {
    background-color: <?php echo $brand_color; ?>;
    color: <?php echo $text_color_light; ?>;
    font-weight: bold;
}

/*** Delete Confirmation Buttons and Text ***/
.tt-delete-confirmation-button {
    text-decoration: none;
    background-color: <?php echo $brand_color; ?>;
    padding: 10px;
    margin: 20px;
    width: 100px;
    box-shadow: 5px 5px 8px #888888;
}

.tt-delete-confirmation-button:hover, .tt-delete-confirmation-button:focus {
    background-color: rgba(255,0,0,1);
    text-decoration: none;
}

.tt-buttons-inline {
    display: inline-block;
}

.tt-delete-confirm-msg {
    display: block;
    width: 75%;
    border: 1px solid lightgray;
    padding: 20px;
}

/********** Button to Start Work Timer **********/
/********** Button to View Task Detail **********/
.start-work-timer, .open-task-detail, .tt-table-button {
    width: 100%;
    padding: 0.2em;
    background-color: <?php echo $brand_color; ?>;
    margin-right: 0.1em;
    margin-bottom: 0.1em;
    font-size: 0.8em;
    font-weight: normal;
    text-decoration: none;
    display: block;
    box-shadow: 3px 3px 4px #888888;
}

.start-work-timer:hover, .open-task-detail:hover, .tt-table-button:hover, .tt-table-button:focus  {
    padding: 2.5px;
    background-color: <?php echo $brand_color_minor; ?>;
    color: <?php echo $text_color_dark; ?>;
    text-decoration: none;
    font-weight: bold;
}

/********** General Buttons Mid-Page **********/
.end-work-timer, .tt-midpage-button {
    padding: 5px;
    color: <?php echo $text_color_light; ?>;
    background-color: <?php echo $brand_color_minor; ?>;
    margin-bottom: 10px;
}

.end-work-timer:hover, .tt-midpage-button:hover {
    color: <?php echo $text_color_light; ?>;
    background-color: <?php echo $brand_color; ?>;
    text-decoration: none;
}

/********** Page Navigation Buttons **********/
div.tt-pagination-nav {
    margin-top: 10px;
    margin-bottom: 10px;
}

.tt-pagination-nav a, .tt-pagination-nav .current {
    -moz-appearance: button;
    -webkit-appearance: button;
    appearance: button;
    padding: 5px 10px;
	margin: 5px;
    box-shadow: 5px 5px 8px #888888;
    font-size: 1.1em;
    line-height: 1.1em;
    text-decoration: none;
}

.tt-pagination-nav a {
    background-color: <?php echo $brand_color; ?>;
    color: <?php echo $text_color_light; ?>;
}

.tt-pagination-nav a:hover {
	color: <?php echo $brand_color_minor; ?>;
}

.tt-pagination-nav .current {
    background-color: <?php echo $brand_color_minor; ?>;
    color: <?php echo $text_color_dark; ?>;
}

@media only screen and (max-width: 768px) {
  a.tt-sidebar-button, a.tt-header-button, button.end-work-timer, button.clear-error, button.tt-export-pending-time {
    padding: 5px;
  }

  .tt-buton a {
	border-radius: 0;
  }
} 


/****************************/
/******** Accordions *******/
/***************************/
.tt-accordion {
  background-color: <?php echo $brand_color; ?>;
  color: <?php echo $text_color_light; ?>;
  cursor: pointer;
  padding: 18px;
  width: 100%;
  text-align: left;
  border: none;
  outline: none;
  transition: 0.4s;
  font-size: 1.2em;
  line-height: 1.2em;
  text-decoration: none;
}

.separate-containers .site-main>button.tt-accordion.active {
  margin-bottom: 0px;
}

.tt-accordion.active, .tt-accordion:hover, .tt-accordion:focus {
    background-color: <?php echo $brand_color_minor; ?>;
    color: <?php echo $text_color_dark; ?>;
    text-decoration: none;
    font-weight: bold;
    border: none;
}

.tt-accordion-panel {
  padding: 0 15px;
  background-color: white;
  display: none;
  overflow: hidden;
  border-width: 1px;
  border-style: solid;
  border-color: <?php echo $neutral_background; ?>;
}


/****************************/
/********** Tables **********/
/****************************/
.tt-table {
  	display: table;  
  	max-width: 100%;
    margin-top: 0;
    table-layout: fixed;
}

.tt-table td {
    position: inherit;
	padding: 4px;
    border-collapse: collapse; 
	font-family: <?php echo $font_family; ?>;
	font-weight: <?php echo $font_weight; ?>;
	font-size: <?php echo $font_size; ?>;
	line-height: <?php echo $line_height; ?>;
	vertical-align: initial;
}

.tt-table th {
    font-weight: bold;
    position: sticky;
    background-color: <?php echo $brand_color; ?>; 
    color: <?php echo $text_color_light; ?>;
    vertical-align: bottom;
}

.tt-table td.not-editable {
    background-color: <?php echo $neutral_background_light; ?>;
}

.divider-row {
    height: 10px;
    background-color:  <?php echo $brand_color; ?>;
}

.tt-total-row, .tt-header-row {
    font-weight: bold;
}

/********** Table Column Widths **********/
.tt-col-width-five-pct {
	width: 5%;
}

.tt-col-width-ten-pct {
	width: 10%;
}

.tt-col-width-fifteen-pct {
	width: 15%;
}

.tt-col-width-thirty-pct {
	width: 30%;
}

.tt-even-columns-2 td {
    width: 50%;
}

.tt-even-columns-3 td {
    width: 33%;
}

.tt-even-columns-4 td {
    width: 25%;
}

.tt-even-columns-5 td {
    width: 20%;
}

.tt-even-columns-6 td {
    width: 16%;
}

.tt-even-columns-7 td {
    width: 14%;
}

.tt-even-columns-8 td {
    width: 12%;
}

.tt-even-columns-9 td {
    width: 11%;
}

.tt-even-columns-10 td {
    width: 10%;
}

.tt-even-columns-11 td {
    width: 9%;
}

.tt-even-columns-12 td {
    width: 8%;
}

/********** Project List Table **********/
.project-list-table td#status-header-row {
    background-color: <?php echo $neutral_background_light; ?>;
    font-weight: bold;
}

/********** Monthly and Yearly Summary Tables **********/
.monthly-summary-table td, .yearly-summary-table td {
  padding: 10px;
}

/********** Open To Do List Table **********/
td#time-worked div {
    margin: 0 auto;
}

td#time-worked.over-time-estimate  {
    color: red;
    font-weight: bold;
}

td#due-date.late-date {
    background-color: rgba(255, 51, 0, 0.5);  //transparent red
    color: #000000;
}

td#due-date.soon-date {
    background-color: rgba(255, 153, 51,0.5);  //transparent orange
    color: #000000;
}

td.on-hold-date {
    background-color: rgba(136, 132, 126,0.5);  //transparent gray
    color: #000000;
}

/***************************/
/********** Forms **********/
/***************************/
.tt-form {
    padding-top: 10px;
}

.tt-form p {
    margin-bottom: 0;
    overflow: hidden;
}

.tt-form input[type="text"], .tt-form input[type="password"], .tt-form input[type="email"], .tt-form input[type="url"],
.tt-form input[type="date"], .tt-form input[type="month"], .tt-form input[type="time"], .tt-form input[type="datetime"],
.tt-form input[type="datetime-local"], .tt-form input[type="week"], .tt-form input[type="number"],
.tt-form input[type="search"], .tt-form input[type="tel"], .tt-form input[type="color"], .tt-form textarea, .tt-form select {
    padding: 5px;
    border: 1px solid <?php echo $neutral_background; ?>;
    margin-bottom: 10px; 
}

//** inline forms **/
.tt-form {
    display: inline-block;
    width: 100%;
}

.tt-form-row {
    display: flex;
    width: 100%;
}

.tt-col-right {
    float: right;
}

.tt-col-left {
    float: left;
}

.tt-col-middle {
    margin: 0 auto;
}

.tt-col-right:after {
    clear: both;
}

.tt-form .tt-form-element.tt-one-third, .tt-form .tt-form-element.tt-two-thirds {
    display: inline-block;
}

.tt-form .tt-form-element.tt-one-third {
    width: 33%;
}

.tt-form label, .tt-form select, .tt-form input:not(input[type="submit"], input[type="datetime"], span>input), .tt-form span {
    display: inline-block;
    width: 80%;
    margin-right: 20px;
}

.tt-form input[type="submit"], .tt-form input[type="datetime"] {
    float: left;
    width: 20%;
}

.tt-form input[type-="datetime"] {
    width: 20%;
}

.tt-form input[type="submit"]:after, .tt-form input[type="datetime"]:after {
    clear:both;
}


.tt-form .tt-form-element.tt-two-thirds {
    width: 66%;
}

@media only screen and (max-width: 768px) {
	.tt-col-right, .tt-col-left, .tt-col-middle, .tt-form .tt-form-element.tt-one-third, .tt-form .tt-form-element.tt-one-third label,
    .tt-form .tt-form-element.tt-two-thirds, .tt-form .tt-form-element.tt-two-thirds label,
    .tt-form .tt-form-element.tt-one-third input, .tt-form .tt-form-element.tt-one-third select,
    .tt-form .tt-form-element.tt-two-thirds input, .tt-form .tt-form-element.tt-two-thirds select {
        clear: both;
		display: block;
        width: 100%
	}
}

/********** Form for Filtering **********/
.filter-time-form {
	border: 1px solid lightgray;
	padding: 10px;
	margin: 20px 0;
}

.filter-time-form form, .filter-time-form p {
	display: inline-block;
	padding-left: 10px;
	padding-right: 10px;
	margin-bottom: 0px;
}

.filter-time-form .wpcf7-date {
	padding-top: 5px;
	padding-bottom: 5px;
    margin-bottom: 20px;
}

.filter-time-form #task-name {
    max-width: 700px;
    margin-bottom: 20px;
}

/*********************************/
/**********Tool Tips**********/
/*********************************/
.tool-tip:before {
    content: " ? ";
    padding: 0 5px;
    border-width: 1px;
    border-style: dotted;
    border-radius: 100%;
    background-color: rgba(0,0,0,0.5);
    margin: 0 5px;
    vertical-align: top;
    font-size: 1.2rem;
}

.tool-tip {
    display: inline-block;
    position: relative;
}

.tool-tip:hover:before {
    color: white;
    background-color: rgba(0,0,0,0.8);
    border-color: white;
}

.tool-tip:hover .tool-tip-text {
    visibility: visible;
}

.tool-tip-text {
    visibility: hidden;
}

.tool-tip-text {
    visibility: hidden;
    position: absolute;
    background-color: lightgray;
    border: 1px solid darkgray;
    border-radius: 5px;
    color: black;
    top: 15px;
    left: 40px;
    padding: 3px;
    min-width: 150px;
    text-transform: none;
    font-size: 1.1rem;
    line-height: 1.1rem;
    font-weight: normal;
    text-align: left;
}


/***************************/
/********** Misc**********/
/***************************/
.float-right {
    float:right;
}

.float-right::after {
    content: "";
    clear:both;
}

.tt-align-right {
    text-align: right;
}

.tt-align-center {
    text-align: center;
}

.bold-font {
    font-weight: bold;
}

.no-border-radius {
    border-radius: 0;
}


/*********************************/
/********** Admin Area **********/
/********************************/
#tt-options > input[type="submit"] {
    -webkit-appearance: button;
    -moz-appearance: button;
    appearance: button;
    font-size: 1.2em;
    font-weight: bold;
    padding: 5px 10px;
}

#tt-options {
    margin-left: 20px;
    margin-top: 20px;
}

.tt-admin-section, .tt-indent {
    padding-left: 20px;
}

.tt-admin-to-front.button-primary {
    margin-left: 40px;
}


/*********************************/
/**********Error Messages**********/
/*********************************/
.error-message, .tt-important, .tt-warning {
    font-size: 1.2em;
    font-weight: bold;
    color: <?php echo $alert_color; ?>;
    padding-left: 20px;
    padding-top: 20px;
}

div#no-client-alert, div#no-task-alert {
    padding: 20px;
    border: 2px solid <?php echo $alert_color; ?>;
}

#delete-confirm > button {
    background-color: <?php echo $alert_color; ?>;
    border-color: <?php echo $alert_color; ?>;
}

#tt-delete-confirmation-result {
    color: red;
    padding: 10px 0;
}