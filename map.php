<?php
$allowed_domains = ['sanctionsassociation.site-ym.com'];

// Get the referer domain
$referer = parse_url($_SERVER['HTTP_REFERER'] ?? '', PHP_URL_HOST);

// Block if referer is missing or not in allowed list
if (!$referer || !in_array($referer, $allowed_domains)) {
	header('Location:https://map.sanctionsassociation.org');
    /* http_response_code(403);
    echo "Access denied: You are not authorized to view this content."; */
    exit;
}

#header("Content-Security-Policy: frame-ancestors https://example1.com https://example2.com https://example3.com");
header("Content-Security-Policy: frame-ancestors https://sanctionsassociation.org https://resources.sanctionsassociation.org https://sanctionsassociation.site-ym.com");

require_once('connection.php');
	
function country($conn){ 
	$query = "SELECT * FROM wp_world_map order by country";
	$result  = mysqli_query($conn, $query);
	return $result;

}
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
/* Remove the background shadow and padding of the default InfoWindow */
.gm-style-iw {
  padding: 0 !important;
  overflow: visible !important;
}

/* Remove the white background and shadow */
.gm-style-iw-d {
  overflow: visible !important;
  padding: 0 !important;
}

/* Remove the close button background box */
.gm-style-iw-chr {
  display: none !important;
}

.gm-ui-hover-effect {
  display: none !important;
}

.custom-tooltip {
  background: #ffffff;
  padding: 10px 14px;
  border-radius: 6px;
  font-size: 14px;
  color: #333;
  font-family: Arial, sans-serif;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.25);
  white-space: nowrap;
  max-width: 200px;
}

.custom-tooltip .tooltip-title {
  font-weight: bold;
  color: #007bff;
}



	body {
    font-family: Open Sans,Arial,sans-serif;
    font-size: 14px;
    color: #666;
    background-color: #fff;
    line-height: 1.7em;
    font-weight: 500;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
.breadcrumbs ul {
    margin: 0;
    padding: 0;
}	
div#filter-checkbox {
    position: absolute;
    top: 25px;
    right: 20px;
    color: #999;
    background: #222;
    background: #fafafaf2;
    border-radius: 2px;
    box-shadow: 0 2px 10px #999;
    min-width: 449px;
    z-index: 99;
    display: flex;
}
.customize-support div#filter-checkbox {
    top: 201px;
}

.filter-by-vendors .form-group label:before {
  content: "";
  -webkit-appearance: none;
  background-color: transparent;
  padding: 5px;
  border-radius: 50%;
  display: inline-block;
  position: relative;
  vertical-align: middle;
  cursor: pointer;
  margin-right: 8px;
  top: -1px;
}
.filter-by-vendors .form-group label[for=usa_sanction]:before {
  border: 1px solid #18059954;
  box-shadow: inset 0 0 0 0px #18059954, 0 0 0 4px #18059954;
}
.filter-by-vendors .form-group label[for=usa_sanction]:after {
  background: #1f01ef;
  border: 1px solid #18059954;
  box-shadow: inset 0 0 0 0px #18059954, 0 0 0 4px #18059954;
}

.filter-by-vendors .form-group label[for=eu_sanction]:before {
  border: 1px solid #65cde8;
  box-shadow: inset 0 0 0 0px #65cde8, 0 0 0 4px #65cde8;
}
.filter-by-vendors .form-group label[for=eu_sanction]:after {
  background: #35b7d9;
  border: 1px solid #65cde8;
  box-shadow: inset 0 0 0 0px #65cde8, 0 0 0 4px #65cde8;
}

.filter-by-vendors .form-group label[for=no_sanction]:before {
  border: 1px solid #00800054;
  box-shadow: inset 0 0 0 0px #00800054, 0 0 0 4px #00800054;
}
.filter-by-vendors .form-group label[for=no_sanction]:after {
  background: #008000;
  border: 1px solid #00800054;
  box-shadow: inset 0 0 0 0px #00800054, 0 0 0 4px #00800054;
}
.filter-by-vendors .form-group label[for=uk_sanction]:before {
  border: 1px solid #ff990054;
  box-shadow: inset 0 0 0 0px #ff990054, 0 0 0 4px #ff990054;
}
.filter-by-vendors .form-group label[for=uk_sanction]:after {
   background: #ff9900;
  border: 1px solid #ff990054;
  box-shadow: inset 0 0 0 0px #dfdfdf, 0 0 0 4px #ff990054;
}
.filter-by-vendors .form-group label[for=un_sanction]:before {
   border: 1px solid #ffff0054;
  box-shadow: inset 0 0 0 0px #ffff0054, 0 0 0 4px #ffff0054;
}
.filter-by-vendors .form-group label[for=un_sanction]:after {
 background: #bfbf00;
  border: 1px solid #ffff0054;
  box-shadow: inset 0 0 0 0px #ffff0054, 0 0 0 4px #ffff0054;
}
.filter-by-vendors .form-group label[for=canada_sanction]:before {
 border: 1px solid #80008054;
  box-shadow: inset 0 0 0 0px #80008054, 0 0 0 4px #80008054;
}
.filter-by-vendors .form-group label[for=canada_sanction]:after {
   background: #800080;
  border: 1px solid #80008054;
  box-shadow: inset 0 0 0 0px #80008054, 0 0 0 4px #80008054;
}
.filter-by-vendors .form-group label[for=switzerland_sanction]:before {
    border: 1px solid #28272754;
  box-shadow: inset 0 0 0 0px #28272754, 0 0 0 4px #28272754;
}
.filter-by-vendors .form-group label[for=switzerland_sanction]:after {
 background: #282727;
  border: 1px solid #28272754;
  box-shadow: inset 0 0 0 0px #28272754, 0 0 0 4px #28272754;
}
.filter-by-vendors .form-group label[for=multiple_sanction]:before {
  border: 1px solid #ff000054;
  box-shadow: inset 0 0 0 0px #ff000054, 0 0 0 4px #ff000054;
}
.filter-by-vendors .form-group label[for=multiple_sanction]:after {
  background: #ff0000;
  border: 1px solid #ff000054;
  box-shadow: inset 0 0 0 0px #ff000054, 0 0 0 4px #ff000054;
}
.filter-by-vendors .form-group input {
  padding: 0;
  height: initial;
  width: initial;
  margin-bottom: 0;
  display: none;
  cursor: pointer;
}

.filter-by-vendors .form-group label {
  position: relative;
  cursor: pointer;
  margin-right: 4px;
}

.filter-by-vendors .form-group input:checked + label:after {
  content: "";
  display: block;
  position: absolute;
  top: 4px;
  left: 0px;
  width: 10px;
  height: 10px;
  border-radius: 50%;
  content: '\a0';
}
.et_pb_toggle_content ul {
    padding: 0 !important;
    margin: 0 !important;
}
.map-location-form-wrap {
    position: absolute;
    top: 100px;
    right: 20px;
    color: #999;
    background: #222;
    background: #fafafaf2;
    border-radius: 2px;
    box-shadow: 0 2px 10px #999;
    width: 522px;
    z-index: 99;
    padding-bottom: 21px;
    padding: 13px;
}
.customize-support .map-location-form-wrap {
    top: 270px;
}
ul.footer_linked li {
    display: inline-block;
    padding: 0 9px;
}
ul.footer_linked {
    text-align: center;
}
ul.footer_linked li a {
    color: #fff;
    font-size: 14px;
}
.speaker_div form input.speaker_search {
    background: #fff;
    border: 1px solid #021b47;
    border-radius: 4px 0px 0px 4px;
    padding: 8px 4px;
}
.speaker_div form button.search_btn {
    background: #021b47;
    border: 1px solid #021b47;
    color: #fff;
    text-transform: uppercase;
    font-weight: 600;
    padding: 8px 13px;
    border-radius: 0px 4px 4px 0px;
}
.result a {
    word-break: break-all;
}
filter-checkbox {
    position: fixed;
    top: 10px;
    right: 10px;
    color: #999;
    background: #222;
    background: #fafafaf2;
    border-radius: 2px;
    box-shadow: 0 2px 10px #999;
    width: 350px;
    z-index: 99;
}
.filter-by-vendors {
    padding: 16px 15px;
}
.filter-by-vendors .form-group {
    display: inline-block;
    vertical-align: middle;
    padding: 0 9px;
}
.filter-by-vendors .form-group input {
    padding: 0;
    height: initial;
    width: initial;
    margin-bottom: 0;
    display: none;
    cursor: pointer;
}
.filter-by-vendors .form-group label {
    position: relative;
    cursor: pointer;
    margin-right: 4px;
}
.filter-by-vendors .form-group label:before {
    content: "";
    -webkit-appearance: none;
    background-color: transparent;
    padding: 5px;
    border-radius: 50%;
    display: inline-block;
    position: relative;
    vertical-align: middle;
    cursor: pointer;
    margin-right: 8px;
    top: -1px;
}
#left-area ul, .entry-content ul, .et-l--body ul, .et-l--footer ul, .et-l--header ul {
    list-style-type: disc;
    padding: 1px 0 0px 0em !important;
}
.breadcrumbs {
    border-bottom: 4px solid #999;
    margin: 0.41667em auto 0.83333em;
    overflow: hidden;
    padding: 0.83333em 0;
    position: relative;
}
.breadcrumbs li.firstLi span {
    margin-left: 5px;
}
li.firstLi {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}
.map-location .breadcrumbs ul li a {
    cursor: pointer;
    font-size: 15px !important;
    font-weight: 400;
    height: 25px;
    color: #003169 !important;
    line-height: 2.08333em;
    margin: 0 0.41667em 0 0;
    overflow: hidden;
    padding: 0 1.25em 0 0;
    text-decoration: none;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    justify-content: start;
}
	.fa, .fab, .fal, .far, .fas {
    -moz-osx-font-smoothing: grayscale;
    -webkit-font-smoothing: antialiased;
    display: inline-block;
    font-style: normal;
    font-variant: normal;
    text-rendering: auto;
    line-height: 1;
}
.search-icon-map i.fa.fa-search {
    width: 21px;
    height: 21px;
    border: 2px solid;
    border-radius: 50%;
    font-size: 13px;
    text-align: center;
    margin-right: 8px;
    line-height: 20px;
    color: #b9b9b9;
    vertical-align: super;
}
div#mapWrapperSection {
    display: none;
}
.map-location.countery-region .result::-webkit-scrollbar-track
{
    box-shadow: inset 0 0 5px grey;
    border-radius: 10px;
}
.map-location.countery-region .result::-webkit-scrollbar-thumb
{
    background: #021b47;
    border-radius: 10px;
}
.map-location.countery-region .result::-webkit-scrollbar-thumb:hover
{
    background: #021b47;
}
.map-location.countery-region .result::-webkit-scrollbar {
    width: 5px;
}
.map-location .breadcrumbs ul li a {
    cursor: pointer;
    font-size: 15px !important;
    font-weight: 400;
    height: 25px;
    color: #003169 !important;
    line-height: 2.08333em;
    margin: 0 0.41667em 0 0;
    overflow: hidden;
    padding: 0 1.25em 0 0;
    text-decoration: none;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    justify-content: start;
}
li {
    list-style: none;
}
.select-countery-modal{
    position: absolute;
    top: 63px;
    left: 0;
    right: 0;
    margin: 0 auto;
    background: #fff;
    padding: 16px 0 0;
    z-index: 9;
}
.map-location-form-wrap h2 {
    color: #666;
    display: inline-block;
    font-size: 15px !important;
    font-weight: 400;
    margin: -4px 0 11px;
    padding: 0 0 0 15px;
    text-transform: uppercase;
}
a.close-select-modal {
    color: #003169;
    cursor: pointer;
    text-decoration: none;
    text-transform: uppercase;
    font-size: 12px;
    text-align: right;
    float: right;
    margin-right: 16px;
}
.shortname-nav {
    border-top: 5px solid #999;
    margin: 0 14px;
}
.shortname-nav a {
    background: #eee;
    color: #909498;
    font-size: 13px;
    font-weight: 400;
    text-decoration: none;
    height: auto;
    margin: 11px 0;
    padding: 6px 17px;
    display: inline-block;
    text-align: center;
    text-transform: uppercase;
}
.all-countery-select{
    padding: 0 14px;
    height: 415px;
    overflow-y: scroll;
    background: hsla(0, 0%, 98%, 0.95);
    border-radius: 2px;
    box-shadow: 0px 4px 4px 0px #c1c1c1;
}
.all-countery-select ul li, .all-single-factory ul li {
    color: #003169;
    cursor: pointer;
    text-decoration: none;
    text-transform: uppercase;
    font-size: 13px;
    font-weight: 400;
    border-top: 1px solid #484848;
    padding: 13px 0;
}
.filter-close {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 5px;
}
form#search_form {
    position: relative;
}
.filter-close h3 {
    font-size: 14px;
    color: #666;
    margin: 0;
}
i.fa.fa-window-close {
    cursor: pointer;
    z-index: 9999;
    position: relative;
}
.input-search-filter {
    display: flex;
    align-items: center;
    width: 100%;
    margin-bottom: 6px;
}
.input-search-filter .form-control {
    display: block;
    width: 100%;
    padding: 1px 5px;
    font-size: 12px;
    line-height: 2;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0rem;
    margin-bottom: 0;
    margin-right: 8px;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
.input-search-filter .btn-filter {
    color: #fff;
    background-color: #003169;
    border-color: #003169;
    margin-left: 0;
    margin-top: 0;
    text-transform: uppercase;
    margin-bottom: 0;
    border-radius: 0;
    padding: 1.9px 5px;
    font-size: 12px;
    line-height: 2;
    border: 0;
    height: 27.95px;
}
.serach_error_msg {
    font-size: 11px;
    color: #f00;
}
.et_us:before,.et_eu:before,.et_uk:before,.et_un:before,.et_ca:before,.et_sw:before {
    width: 16px;
    height: 16px;
    border-radius: 100%;
    position: absolute;
    content: "";
    left: -23px;
	    top: 3px;
}
h4.et_pb_toggle_title {
    font-size: 15px;
    margin: 8px 0;
}
.et_us ,.et_eu,.et_uk,.et_un,.et_ca,.et_sw{
    position: relative;
    margin-left: 23px;
}
.et_us:before {
    background: #7166bf;
}
.et_eu:before{
    background: #008000;
}
.et_uk:before{
    background: #ff9900;
}
.et_un:before{
    background: #ffff00;
}
.et_ca:before{
    background: #800080;
}
.et_sw:before{
    background: #282727;
}
.result {
    max-height: 700px;
    overflow-y: auto;
}
.et_pb_toggle_open h4.et_pb_toggle_title:after {
    content: "\f106";
    font-weight: 900;
    font-family: "Font Awesome 5 Free" !important;
    margin-left: 5px;
    position: absolute;
    font-size: 14px;
    cursor: pointer;
    top: 2px;
}
.et_pb_toggle_close h4.et_pb_toggle_title:after {
    content: "\f078";
    font-weight: 900;
    font-family: "Font Awesome 5 Free" !important;
    margin-left: 5px;
    position: absolute;
    font-size: 11px;
    cursor: pointer; 
}
.result_wrapper .et_pb_style:after {
    background: #fff;
    position: absolute;
    width: 10px;
    height: 10px;
    content: "";
    left: -20px;
    border-radius: 100%;
    top: 6px;
}
h4.et_pb_toggle_title {
    font-size: 15px;
    margin: 8px 0 0;
    position: relative;
    cursor: pointer;
}
h4.et_pb_toggle_title:hover {
    color: #021b47;
}

.main.main_mobile {
    display: none;
}
.et_pb_toggle_close .et_pb_toggle_content {
  display: none;
}

.et_pb_toggle_open .et_pb_toggle_content {
  display: block;
}


@media only screen and (max-width: 1600px) {
    .map-location-form-wrap {
        top: 100px;
        width: 500px;
    }
   .et_pb_row_1_tb_header .et_pb_column {
        width: 100%;
        margin-bottom: 0;
    }
    div#filter-checkbox {
        top: 25px;
        min-width: 1014px;
        width: 1014px;
    }
    .filter-by-vendors .form-group label {
        margin-right: 0px;
        font-size: 11px;
    }
    .filter-by-vendors .form-group {
        display: inline-block;
        vertical-align: middle;
        padding: 0 4px;
    }
    .filter-by-vendors {
        padding: 16px 8px;
    }
    .filter-by-vendors .form-group label:before {
        padding: 3px;
        border-radius: 100%;
    }
    .main.main_mobile {
        position: absolute;
        top: 100px;
        right: 10px;
        color: #999;
        background: #222;
        background: #fafafaf2;
        border-radius: 4px;
        box-shadow: 0 2px 10px #999;
        min-width: 339px;
        z-index: 1;
        display: block;
        padding: 6px 8px;
    }
    .customize-support .main.main_mobile {
        top: 190px;
    }
    .main.main_mobile ul li {
        display: flex;
        align-items: center;
    }
    .main.main_mobile ul li.secLi {
        display: inline-block;
        align-items: center;
        position: absolute;
        left: 0;
        background: #fff;
        right: 0;
        margin: 0 auto;
        width: 100%;
        padding: 8px 14px;
        top: 0;
        z-index: 9999;
        border-bottom: 4px solid #999;
    }
    h4.et_pb_toggle_title {
        font-size: 13px;
        margin: 2px 0 0;
    }

    .main_mobile .map-location-form-wrap {
        display: block;
        top: 56px;
        width: auto;
        z-index: 9999;
    }
    .et_pb_toggle_open h4.et_pb_toggle_title:after ,.et_pb_toggle_close h4.et_pb_toggle_title:after{
        right: 0;
        top: 0;
    }
    .all-countery-select ul, .all-countery-select ul li {
        display: inline-block !important;
        width: 100% !important;
    }
    .result ul, .result ul li {
        display: inline-block !important;
        width: 100% !important;
    }
    .main_mobile .map-location-form-wrap {
        display: block;
        top: 56px;
        width: auto;
    }
    .main_mobile .result_wrapper .result {
        position: absolute;
        right: 0;
        color: #999;
        background: #fafafaf2;
        border-radius: 2px;
        box-shadow: 0 2px 10px #999;
        z-index: 99;
        padding: 13px;
        top: 56px;
        left: 0;
    }
/*
    div#filter-checkbox {
        display: none;
    }
*/
/*
    .map-location-form-wrap {
        display: none;
    }
*/
	.main.main_mobile {
    display: none;
}
    .silde-bar-right_mobile {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .main.main_mobile ul {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 56px;
    }
    .silde-bar-right_mobile select {
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 3px 5px;
    }
    .custom-select {
        position: relative;
        width: 74%;
        z-index: 9999;
    }
    .select-box {
        border: 1px solid #ccc;
        display: flex;
        align-items: center;
        padding: 4px 6px;
        cursor: pointer;
    }
      .custom-select .selected {
          flex: 1;
          display: flex;
          align-items: center;
      }
      .custom-select .arrow {
        margin-left: auto;
      }
      .options-container {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        background: #fff;
        border: 1px solid #ccc;
      }
      .custom-select .option {
        padding: 4px 4px 4px 24px;
        display: flex;
        align-items: center;
        cursor: pointer;
        position: relative;
    }
    .selected span {
        margin-right: 16px;
    }
      .option:hover {
        background-color: #f2f2f2;
      }
      .custom-select .fa-angle-down {
        margin-left: 5px;
      }
      .custom-select.open .options-container {
        display: block;
      }
      .custom-select span::before {
        position: absolute;
        content: "";
        width: 13px;
        height: 13px;
        border-radius: 100%;
        left: 6px;
        margin: -6px 0;
    }
      .custom-select .usa_san::before {
        background-color: #180599;
      }
      .custom-select .eu_san::before {
        background-color: #008000;
      }
      .custom-select .uk_san::before {
        background-color: #ff9900;
      }
      .custom-select .un_san::before {
        background-color: #ffff00;
      }
      .custom-select .ca_san::before {
        background-color: #800080;
      }
      .custom-select .sw_san::before {
        background-color: #282727;
      }
      .custom-select .multiple_san::before {
        background-color: #ff0000;
      }
      .et_pb_menu_0_tb_header .mobile_nav .mobile_menu_bar:before, .et_pb_menu_0_tb_header .et_pb_menu__icon.et_pb_menu__search-button, .et_pb_menu_0_tb_header .et_pb_menu__icon.et_pb_menu__close-search-button, .et_pb_menu_0_tb_header .et_pb_menu__icon.et_pb_menu__cart-button {
        color: #021b47 !important;
    }
}



@media only screen and (max-width: 980px) {
    .map-location-form-wrap {
        top:130px;
        width: 450px;
    }
.main.main_mobile {
     display: block; 
}
    #menu_show_open ul#mobile_menu1 
{
    display: block;
}
.all-countery-select {
    overflow-x: hidden;
}
#menu_show_open span.mobile_menu_bar 
 {
    display: none;
}
#menu_show_open ul#mobile_menu1 {
    display: block;
    border: 0;
}
#menu_show_open ul#mobile_menu1 li a {
    margin: 0;
    padding: 0;
    text-align: center;
}
.et_pb_row.et_pb_row_0_tb_footer.et_pb_row--with-menu {
    padding: 0 !important;
}
    .header_top .et_pb_column {
        margin-bottom: 6px;
    }
   .et_pb_row_1_tb_header .et_pb_column {
        width: 100%;
        margin-bottom: 0;
    }
.main.main_mobile {
    top: 33px;
    width: 220px;
    min-width: 50%;
}
   div#filter-checkbox, .map-location-form-wrap {
 display: none;
}
    .mobile_column .et_pb_column {
        /*width: 50% !important;*/
    }
    footer.et-l.et-l--footer {
        position: relative;
        z-index: -1;
    }
.main.main_mobile ul li.secLi {
    width: 92%;
}
}



@media only screen and (max-width: 600px) {
   div#filter-checkbox {
    top: 25px;
    min-width: 60%;
    width: 350px;
}

.map-location-form-wrap {
    display: none;
}
.main.main_mobile {
    display: block;
}
    .main.main_mobile {
        top: 15px;
    }
.main.main_mobile {
    width: 330px;
    min-width: 90%;
}
form#search_form {
    width: 96%;
}
.main.main_mobile ul li.secLi {
    width: 90%;
}
    .mobile_column .et_mobile_nav_menu {
        margin-top: -25px !important;
    }
        button.gm-control-active.gm-fullscreen-control {
        position: absolute !important;
        bottom: 0 !important;
        right: 0 !important;
        top: inherit !important;
        left: 0;
    }
   
    .gmnoprint {
        left: inherit !important;
        right: 0;
        bottom: 13px;
        top: inherit !important;
    }
    .custom-select {
        position: relative;
        width: 74%;
        z-index: 999;
    }
   .et_pb_row_1_tb_header .et_pb_column {
        width: 100%;
        margin-bottom: 0;
    }
    div#filter-checkbox {
        top: 174px;
        min-width: 324px;
        width: 210px;
    }
    .et_pb_toggle_close h4.et_pb_toggle_title:after,.et_pb_toggle_open h4.et_pb_toggle_title:after {
        right: 0;
        top: 0;
    }
    .customize-support .main.main_mobile {
        top: 180px;
    }
  }
</style>
<!--link href="/asset/css/frontend_style.css"-->    
	<!-------- Start Map Js  ------------>
<!--script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBK_qYJyr2uEbIAhufq9j1180cSKmmNk0U&libraries=drawing&callback=Function.prototype"></script-->

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBK_qYJyr2uEbIAhufq9j1180cSKmmNk0U"></script>

<div id="map-canvas" style="width: 100%; height: 100vh; position: relative; overflow: hidden;"></div>
<div class="main main_mobile">
	<div class="silde-bar-right_mobile">
	<div class="custom-select">
	<div class="select-box">
	<div class="selected">Select options <div class="arrow"><i class="fa fa-angle-down" aria-hidden="true"></i></div></div>
	<div class="options-container">
	<div class="option" data-val="no_sanction"><span class="no_san"></span>USA only Sanctions</div>
	<div class="option" data-val="usa_sanction"><span class="usa_san"></span>USA only Sanctions</div>
	<div class="option" data-val="eu_sanction"><span class="eu_san"></span>EU only Sanctions</div>
	<div class="option" data-val="uk_sanction"><span class="uk_san"></span>UK only Sanctions</div>
	<div class="option" data-val="un_sanction"><span class="un_san"></span>UN only Sanctions</div>
	<div class="option" data-val="canada_sanction"><span class="ca_san"></span>Canada only Sanctions</div>
	<div class="option" data-val="switzerland_sanction"><span class="sw_san"></span>Switzerland only Sanctions</div>
	<div class="option" data-val="multiple_sanction"><span class="multiple_san"></span>Multiple only Sanctions</div>
	</div>
	</div>
	</div>
	<ul>
		<li class="mobile_search"><i class="fa fa-search" aria-hidden="true"></i></li>
		<li class="mobile_country"><img src="https://map.sanctionsassociation.org/asset/images/world-scope-icon-disabled.png"></li>
		<li class="secLi" style="display: none;">
				<div class="filter-close">
					<h3>Search</h3>
					<i class="fa fa-window-close" aria-hidden="true"></i>
				</div>
				<form id="search_form" >
					<div class="input-search-filter">
						<input type="search" placeholder="Search" class="form-control" id="search-input-filter-mobile">
						<button type="button" class="btn btn-filter btn-filter-mobile">Search</button>
					</div>
					<div class="serach_error_msg"></div>
				</form>
				<div class="filter-search-data"></div>
			</li>
	</ul>
	</div>
	<div class="sideBarBody">
		<div class="search">
			<div class="select-countery-modal map-location-form-wrap" style="display:none;">
				<h2>Select a Country/Region</h2>
				<a href="javascript:void(0);" class="close-select-modal">Close X</a>
				<div class="shortname-nav">
					<a href="javascript:void(0);">By Name</a>
				</div>
				<div class="all-countery-select">
					<ul>
					<?php foreach (country($conn) as $country) { ?>
						<li data-country="<?php echo $country['country'];  ?>"><?php echo $country['country'];  ?></li>
					<?php } ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="result_wrapper"></div>
</div>
<div class="main">
	<div class="silde-bar-right">
	<div id="filter-checkbox" class="filter-checkbox">
		<div class="filter-by-vendors">
			<div class="form-group">
				<input type="radio" id="no_sanction" name="type" value="no_sanction">
				<label for="no_sanction">No Sanctions</label>
			</div>
			<div class="form-group">
				<input type="radio" id="usa_sanction" name="type" value="usa_sanction">
				<label for="usa_sanction">USA only Sanctions</label>
			</div>
			<div class="form-group">
				<input type="radio" id="eu_sanction" name="type" value="eu_sanction">
				<label for="eu_sanction">EU only Sanctions</label>
			</div>
			<div class="form-group">
				<input type="radio" id="uk_sanction" name="type" value="uk_sanction">
				<label for="uk_sanction">UK only Sanctions</label>
			</div>
			<div class="form-group">
				<input type="radio" id="un_sanction" name="type" value="un_sanction">
				<label for="un_sanction">UN only Sanctions</label>
			</div>
			<div class="form-group">
				<input type="radio" id="canada_sanction" name="type" value="canada_sanction">
				<label for="canada_sanction">Canada only Sanctions</label>
			</div>
			<div class="form-group">
				<input type="radio" id="switzerland_sanction" name="type" value="switzerland_sanction">
				<label for="switzerland_sanction">Switzerland only Sanctions</label>
			</div>
			<div class="form-group">
				<input type="radio" id="multiple_sanction" name="type" value="multiple_sanction">
				<label for="multiple_sanction">Multiple only Sanctions</label>
			</div>
		</div>
	</div> <!--filter-checkbox-->
	<div class="map-location-form-wrap">
	<div class="map-location countery-region">
	<div class="breadcrumbs">
		<ul>
			<li class="firstLi">
				<a href="javascript:void(0);">
					<div class="search-icon-map">
						<i class="fa fa-search" aria-hidden="true"></i>
					</div>
					<img src="https://map.sanctionsassociation.org/asset/images/world-scope-icon-disabled.png">
					<span class="choose_country">Choose Country / Region</span>
				</a>
			</li>
			<li class="secLi" style="display: none;">
				<div class="filter-close">
					<h3>Search</h3>
					<i class="fa fa-window-close" aria-hidden="true"></i>
				</div>
				<form id="search_form" >
					<div class="input-search-filter">
						<input type="search" placeholder="Search" class="form-control" id="search-input-filter">
						<button type="button" class="btn btn-filter">Search</button>
					</div>
					<div class="serach_error_msg"></div>
				</form>
				<div class="filter-search-data"></div>
			</li>
		</ul>
	</div>
	<div class="sideBarBody">
		<div class="search">
			<div class="select-countery-modal" style="display:none;">
				<h2>Select a Country/Region</h2>
				<a href="javascript:void(0);" class="close-select-modal">Close X</a>
				<div class="shortname-nav">
					<a href="javascript:void(0);">By Name</a>
				</div>
				<div class="all-countery-select">
					<ul>
					<?php foreach (country($conn) as $country) { ?>
						<li data-country="<?php echo $country['country']; ?>"><?php echo $country['country']; ?></li>
					<?php } ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
	</div>
	<div class="result_wrapper"></div>
	</div>	 			
	</div> <!--map-location-form-wrap-->
	</div>  <!--silde-bar-right-->
</div> <!--Main div-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript" src="https://map.sanctionsassociation.org/asset/js/script.js"></script>