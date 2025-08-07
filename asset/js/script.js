$(document).ready(function(){
  
  // Click event on toggle title
	  $(document).on("click", ".et_pb_toggle_title", function () {
			var parentToggle = $(this).closest('.et_pb_toggle');
			var content = parentToggle.find('.et_pb_toggle_content');

			// Close other open toggles slowly and reset their classes
			$('.et_pb_toggle_content').not(content).slideUp('slow');
			$('.et_pb_toggle').not(parentToggle)
			  .removeClass('et_pb_toggle_open')
			  .addClass('et_pb_toggle_close');

			// Toggle clicked element content slowly
			content.slideToggle('slow');

			// Toggle classes of clicked element
			parentToggle.toggleClass('et_pb_toggle_open et_pb_toggle_close');
	  });
  
	$('.btn-filter').click(function(){
		var search_input = $('#search-input-filter').val();
		if (search_input == "") {
			jQuery(".serach_error_msg").html("This field is required.");
		}else{
			jQuery(".serach_error_msg").empty();
			clearCountryHiglight();
		countryHighlight("countryHighlight_ajax","getSpecficCountryCluster",search_input);
		geoPlace(search_input);
		country_details('country_detailAjax',search_input);
		}
	});
	
	$('.btn-filter-mobile').click(function(){
		var search_input = $('#search-input-filter-mobile').val();
		if (search_input == "") {
			jQuery(".serach_error_msg").html("This field is required.");
		}else{
			jQuery(".serach_error_msg").empty();
			clearCountryHiglight();
		countryHighlight("countryHighlight_ajax","getSpecficCountryCluster",search_input);
		geoPlace(search_input);
		country_details('country_detailAjax',search_input);
		}
	});
	
	$(document).on("click", ".search-icon-map i", function () {
	  jQuery(".sideBarBody").addClass("searchOpen");
	  jQuery(".firstLi").hide();
	  jQuery(".select-countery-modal").hide();
	  jQuery(".secLi").show();
	});
	/************ Mobile****************/
	$(document).on("click", ".mobile_search i", function () {
	  jQuery(".sideBarBody").addClass("searchOpen");
	  jQuery(".firstLi").hide();
	  jQuery(".select-countery-modal").hide();
	  jQuery(".secLi").show();
	});
	
	$('.mobile_country').click(function(){
		$('.select-countery-modal').show();
	});
	/*****************************************************/
	$(document).on("click", ".secLi .fa-window-close", function () {
	  $(".sideBarBody").removeClass("searchOpen");
	  $(".firstLi").show();
	  $(".select-countery-modal").show();
	  $(".secLi").hide();
	  //$('.result').empty();
	});
	
	$('.choose_country').click(function(){
		$('.select-countery-modal').show();
	});
	
	$(document).on("click", ".close-select-modal", function () {
		$(".select-countery-modal").hide();
		//$('.result').empty();
	});
	
	$('.all-countery-select li').click(function(){
		$('.select-countery-modal').slideUp();
		var country = $(this).attr('data-country');
		clearCountryHiglight();
		countryHighlight("countryHighlight_ajax","getSpecficCountryCluster",country);
		//geoPlace(country);
		country_details('country_detailAjax',country);
	});
	
	$('.filter-by-vendors input').click(function(){
		var sanction = $(this).val();
		console.log('sanction: '+sanction);
		$('.result_wrapper').empty();
		map.setZoom(3);
		sanctionAjax(sanction);
	});
	
	/************ Mobile sanction option****************/
	$('.silde-bar-right_mobile .option').click(function(){
		var sanction = $(this).attr('data-val');
		console.log(sanction);
		$('.result_wrapper').empty();
		map.setZoom(3);
		sanctionAjax(sanction);
	});
	/*****************************************************/
});

async function sanctionAjax(sanction){
	const data = await jQuery.ajax({
		url: 'https://map.sanctionsassociation.org/frontend/sanctionAjax.php',
		type: "post",
		data: {
			sanction: sanction,
			//action: 'sanctionAjax'
		},
	});
		clearCountryHiglight();
		countryHighlight("countryHighlight_ajax","getSpecficCountry",data);
}

async function country_details(url,country){
	const data = await jQuery.ajax({
		url: 'https://map.sanctionsassociation.org/frontend/country_detailAjax.php',
		type: "post",
		data: {
			country: country,
			//action: url
		},
		dataType: "json",
	});
	var html = [];
	var pdf = '';
	
	if(data.usa_sanctions){	
		html.push('<div class="et_pb_module et_pb_toggle et_pb_toggle_item et_us et_pb_toggle_close et_pb_style">');
		html.push('<h4 class="et_pb_toggle_title">United States (US) sanctions concerning '+country+'</h4>');
		html.push('<div class="et_pb_toggle_content" style="display: none;">'+data.usa_sanctions);
		if (data.usa_sanctions_source) {
			const sources = data.usa_sanctions_source.split('\n');
			html.push('<p>Source:</p>');
			sources.forEach(source => {
				const trimmed = source.trim();
				if (trimmed) {
					html.push('<p><a href="' + trimmed + '" target="_blank" rel="noopener noreferrer">' + trimmed + '</a></p>');
				}
			});
		}
		html.push('</div>');
		html.push('</div>');
	}
	if(data.eu_sanctions){		
		html.push('<div class="et_pb_module et_pb_toggle et_pb_toggle_item et_eu et_pb_toggle_close et_pb_style">');
		html.push('<h4 class="et_pb_toggle_title">European Union (EU) sanctions concerning '+country+'</h4>');
		html.push('<div class="et_pb_toggle_content" style="display: none;">'+data.eu_sanctions);
		if (data.eu_sanctions_source) {
			const sources = data.eu_sanctions_source.split('\n');
			html.push('<p>Source:</p>');
			sources.forEach(source => {
				const trimmed = source.trim();
				if (trimmed) {
					html.push('<p><a href="' + trimmed + '" target="_blank" rel="noopener noreferrer">' + trimmed + '</a></p>');
				}
			});
		}
		html.push('</div>');
		html.push('</div>');
	}
	if(data.uk_sanctions){	
		html.push('<div class="et_pb_module et_pb_toggle et_pb_toggle_item et_uk et_pb_toggle_close et_pb_style">');
		html.push('<h4 class="et_pb_toggle_title">United Kingdom (UK) sanctions concerning '+country+'</h4>');
		html.push('<div class="et_pb_toggle_content" style="display: none;">'+data.uk_sanctions);
		if (data.uk_sanctions_source) {
			const sources = data.uk_sanctions_source.split('\n');
			html.push('<p>Source:</p>');
			sources.forEach(source => {
				const trimmed = source.trim();
				if (trimmed) {
					html.push('<p><a href="' + trimmed + '" target="_blank" rel="noopener noreferrer">' + trimmed + '</a></p>');
				}
			});
		}
		html.push('</div>');
		html.push('</div>');
	}
	if(data.un_sanctions){
		html.push('<div class="et_pb_module et_pb_toggle et_pb_toggle_item et_un et_pb_toggle_close et_pb_style">');
		html.push('<h4 class="et_pb_toggle_title">United Nations (UN) sanctions concerning '+country+'</h4>');
		html.push('<div class="et_pb_toggle_content" style="display: none;">'+data.un_sanctions);
		if (data.un_sanctions_source) {
			const sources = data.un_sanctions_source.split('\n');
			html.push('<p>Source:</p>');
			sources.forEach(source => {
				const trimmed = source.trim();
				if (trimmed) {
					html.push('<p><a href="' + trimmed + '" target="_blank" rel="noopener noreferrer">' + trimmed + '</a></p>');
				}
			});
		}
		html.push('</div>');
		html.push('</div>');
	}
	if(data.canada_sanctions){
		html.push('<div class="et_pb_module et_pb_toggle et_pb_toggle_item et_ca et_pb_toggle_close et_pb_style">');
		html.push('<h4 class="et_pb_toggle_title">Canada sanctions concerning'+country+'</h4>');
		html.push('<div class="et_pb_toggle_content" style="display: none;">'+data.canada_sanctions);
		if (data.canada_sanctions_source) {
			const sources = data.canada_sanctions_source.split('\n');
			html.push('<p>Source:</p>');
			sources.forEach(source => {
				const trimmed = source.trim();
				if (trimmed) {
					html.push('<p><a href="' + trimmed + '" target="_blank" rel="noopener noreferrer">' + trimmed + '</a></p>');
				}
			});
		}
		html.push('</div>');
		html.push('</div>');
	}
	if(data.switzerland_sanctions){		
		html.push('<div class="et_pb_module et_pb_toggle et_pb_toggle_item et_sw et_pb_toggle_close et_pb_style">');
		html.push('<h4 class="et_pb_toggle_title">Switzerland sanctions concerning '+country+'</h4>');
		html.push('<div class="et_pb_toggle_content" style="display: none;">'+data.switzerland_sanctions);
		if (data.switzerland_sanctions_source) {
			const sources = data.switzerland_sanctions_source.split('\n');
			sources.forEach(source => {
				const trimmed = source.trim();
				if (trimmed) {
					html.push('<p>Source: <a href="' + trimmed + '" target="_blank" rel="noopener noreferrer">' + trimmed + '</a></p>');
				}
			});
		}
		html.push('</div>');
		html.push('</div>');
	}
	if(data.pdf != null){
		pdf = '<p><a href="https://map.sanctionsassociation.org/uploads/worldmap/'+data.pdf+'">Download Sanctions File</a><p>';
	}
		var arrayString= html.join();
		var str2 = arrayString.replace(/,/g," "); 
		$('.result_wrapper').empty().append('<div class="result"><div><p><strong>Overview of current sanctions measures against '+country+'</strong></p>'+pdf+'</div>'+str2+'</div>');
}


let blueStyle = [
  {
    featureType: "water",
    elementType: "geometry",
    stylers: [
      {
        color: "#ededed",
      },
      {
        visibility: "on",
      },
      {
        saturation: -100,
      },
      {
        lightness: 70,
      },
    ],
  },
  {
    featureType: "landscape",
    stylers: [
      {
        visibility: "on",
      },
      {
        lightness: 10,
      },
      {
        saturation: -100,
      },
    ],
  },
  {
    featureType: "administrative",
    elementType: "geometry",
    stylers: [
      {
        visibility: "off",
      },
    ],
  },
  {
    featureType: "poi",
    elementType: "geometry",
    stylers: [
      {
        visibility: "off",
      },
    ],
  },
  {
    featureType: "administrative.province",
    elementType: "geometry",
    stylers: [
      {
        visibility: "on",
      },
    ],
  },
  {
    featureType: "administrative.province",
    elementType: "labels",
    stylers: [
      {
        visibility: "off",
      },
    ],
  },
  {
    featureType: "administrative.country",
    elementType: "labels",
    stylers: [
      {
        visibility: "off",
      },
    ],
  },
  {
    featureType: "administrative.locality",
    elementType: "labels",
    stylers: [
      {
        visibility: "off",
      },
    ],
  },
  {
    featureType: "administrative.neighborhood",
    elementType: "labels",
    stylers: [
      {
        visibility: "off",
      },
    ],
  },
  {
    featureType: "road.local",
    elementType: "geometry",
    stylers: [
      {
        saturation: -100,
      },
      {
        lightness: -72,
      },
      {
        visibility: "on",
      },
    ],
  },
  {
    featureType: "road.local",
    elementType: "labels",
    stylers: [
      {
        visibility: "on",
      },
      {
        invert_lightness: !0,
      },
      {
        gamma: 9.99,
      },
      {
        saturation: -100,
      },
      {
        lightness: 10,
      },
    ],
  },
  {
    featureType: "road.arterial",
    elementType: "geometry",
    stylers: [
      {
        visibility: "on",
      },
      {
        saturation: -100,
      },
      {
        lightness: -72,
      },
    ],
  },
  {
    featureType: "road.arterial",
    elementType: "labels",
    stylers: [
      {
        visibility: "on",
      },
      {
        saturation: -100,
      },
      {
        invert_lightness: !0,
      },
    ],
  },
  {
    featureType: "road.highway",
    elementType: "geometry",
    stylers: [
      {
        visibility: "off",
      },
    ],
  },
  {
    featureType: "road.highway",
    elementType: "labels",
    stylers: [
      {
        visibility: "off",
      },
    ],
  },
  {
    featureType: "transit",
    elementType: "labels",
    stylers: [
      {
        visibility: "off",
      },
    ],
  },
  {
    featureType: "transit",
    elementType: "geometry",
    stylers: [
      {
        visibility: "off",
      },
    ],
  },
  {
    featureType: "poi.park",
    elementType: "labels",
    stylers: [
      {
        visibility: "simplified",
      },
    ],
  },
  {
    featureType: "poi.business",
    stylers: [
      {
        visibility: "off",
      },
    ],
  },
  {
    featureType: "poi.government",
    stylers: [
      {
        visibility: "off",
      },
    ],
  },
  {
    featureType: "poi.medical",
    stylers: [
      {
        visibility: "off",
      },
    ],
  },
  {
    featureType: "poi.place_of_worship",
    stylers: [
      {
        visibility: "off",
      },
    ],
  },
  {
    featureType: "poi.school",
    stylers: [
      {
        visibility: "off",
      },
    ],
  },
  {
    featureType: "poi",
    stylers: [
      {
        visibility: "off",
      },
    ],
  },
  {
    featureType: "water",
    elementType: "labels",
    stylers: [
      {
        visibility: "off",
      },
      {
        saturation: -100,
      },
      {
        lightness: -66,
      },
    ],
  },
];
/******************************Map Style End ********************************/

let countries = [];
//  let markers;
let markerCluster;
let gmarkers = [];
let flightPath;

let map;
let CheckProgress = 0;

function mapConfig() {
  let mapOptions = {
    zoom: 3,
    minZoom: 1,
    center: new google.maps.LatLng(20.5937, 78.9629),
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    styles: blueStyle,
    fullscreenControl: true,
    fullscreenControlOptions: {
      position: google.maps.ControlPosition.TOP_LEFT,
    },
    zoomControl: true,
    zoomControlOptions: {
      position: google.maps.ControlPosition.BOTTOM_LEFT,
    },
    streetViewControl: false,
    StreetViewControlOptions: {
      position: google.maps.ControlPosition.TOP_LEFT,
    },
  };

  map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
}
mapConfig();

countryHighlight("countryHighlight_ajax", "getAllSpecficCountry", '');
async function countryHighlight(url, type, getData) {
	const data = await jQuery.ajax({
		url: 'https://map.sanctionsassociation.org/frontend/'+url+'.php',
		type: "post",
		data: {
			type: type,
			getData: getData,
		},
		dataType: "json",
	});
	Highlight(data);
}

function Highlight(data) {
  jQuery.each(data, function (id, country) {
    var countryCoords;
    var ca;
    var co;

    // Check if 'Polygon' exists inside the XML
    if (country.xml && country.xml.Polygon) {
      // Handle multiple polygons (multi-geometry)
      var ccArray = [];

      // Ensure country.xml.Polygon is treated as an array
      var polygons = Array.isArray(country.xml.Polygon)
        ? country.xml.Polygon
        : [country.xml.Polygon];

      polygons.forEach(function (polygon) {
        countryCoords = [];

        if (
          polygon.outerBoundaryIs &&
          polygon.outerBoundaryIs.LinearRing &&
          polygon.outerBoundaryIs.LinearRing.coordinates
        ) {
          co = polygon.outerBoundaryIs.LinearRing.coordinates.split(" ");

          for (var i in co) {
            ca = co[i].split(",");
            if (ca.length >= 2) {
              countryCoords.push(new google.maps.LatLng(ca[1], ca[0]));
            }
          }

          ccArray.push(countryCoords);
        }
      });

      createCountry(ccArray, country);
    } else if (
      country.xml &&
      country.xml.outerBoundaryIs &&
      country.xml.outerBoundaryIs.LinearRing &&
      country.xml.outerBoundaryIs.LinearRing.coordinates
    ) {
      // Handle single polygon (non-multi)
      countryCoords = [];

      co = country.xml.outerBoundaryIs.LinearRing.coordinates.split(" ");

      for (var j in co) {
        ca = co[j].split(",");
        if (ca.length >= 2) {
          countryCoords.push(new google.maps.LatLng(ca[1], ca[0]));
        }
      }

      createCountry(countryCoords, country);
    }
  });

  showCountries();
}



let active = "";
function showCountries() {
  var infoWindow = new google.maps.InfoWindow();

  for (var i = 0; i < countries.length; i++) {
    countries[i].setMap(map);

    if (countries[i].single == "one") {
      countries[i].setOptions({
        fillColor: "rgb( 0, 200, 234, 0.4 )",
        fillOpacity: 0.7,
        strokeColor: "#00c8ea",
        strokeOpacity: 0.6,
        strokeWeight: 2,
      });

      google.maps.event.addListener(countries[i], "mouseover", function (event) {
        this.setOptions({
          fillColor: "rgb( 0, 200, 234, 0.4 )",
          fillOpacity: 0.7,
          strokeColor: "rgb( 0, 200, 234, 0.4 )",
          strokeOpacity: 1,
          strokeWeight: 2,
        });

        // ✅ Show title in hover
        //infoWindow.setContent(this.title || "Untitled");
		infoWindow.setContent(`
		  <div class="custom-tooltip">
			<div class="tooltip-title">${this.title}</div>
		  </div>
		`);
        infoWindow.setPosition(event.latLng);
        infoWindow.open(map);

        console.log("Hovered (single): " + this.title);
      });

      google.maps.event.addListener(countries[i], "mouseout", function () {
        this.setOptions({
          fillColor: "rgb( 0, 200, 234, 0.4 )",
          fillOpacity: 0.6,
          strokeColor: "#00c8ea",
          strokeOpacity: 1,
          strokeWeight: 2,
        });
        infoWindow.close();
      });

    } else {
      countries[i].setOptions({
        fillColor: countries[i].countryColor,
        fillOpacity: 0.6,
      });

      google.maps.event.addListener(countries[i], "mouseover", function (event) {
        this.setOptions({
          fillColor: this.countryColorHover,
          fillOpacity: 0.7,
          strokeColor: this.countryColor,
          strokeOpacity: 1,
          strokeWeight: 2,
        });

        // ✅ Show title in hover
        //infoWindow.setContent(this.title || "Untitled");
		infoWindow.setContent(`
		  <div class="custom-tooltip">
			<div class="tooltip-title">${this.title}</div>
		  </div>
		`);
        infoWindow.setPosition(event.latLng);
        infoWindow.open(map);
      });

      google.maps.event.addListener(countries[i], "mouseout", function () {
        if (active.title == this.title) {
          this.setOptions({
            fillColor: this.countryColorHover,
            fillOpacity: 0.7,
            strokeColor: this.countryColor,
            strokeOpacity: 1,
            strokeWeight: 2,
          });
        } else {
          this.setOptions({
            fillColor: this.countryColor,
            fillOpacity: 0.6,
            strokeColor: this.countryColorHover,
            strokeOpacity: 0,
            strokeWeight: 0,
          });
        }
        infoWindow.close();
      });

      google.maps.event.addListener(countries[i], "click", function (event) {
        if (this.clickStatus == "off") {
          if (typeof active.title !== "undefined") {
            active.setOptions({
              fillColor: active.countryColor,
              fillOpacity: 0.6,
              strokeColor: active.countryColorHover,
              strokeOpacity: 0,
              strokeWeight: 0,
            });
          }

          if (active.title == this.title) {
            this.setOptions({
              fillColor: this.countryColorHover,
              fillOpacity: 0.7,
              strokeColor: this.countryColor,
              strokeOpacity: 1,
              strokeWeight: 2,
            });
          } else {
            active = this;
          }
        }

        clearCountryHiglight();
        countryHighlight("countryHighlight_ajax", "getSpecficCountryCluster", this.title);
        country_details('country_detailAjax', this.title);
      });
    }
  }
}


/*function showCountries() {
	var infoWindow = new google.maps.InfoWindow();
	
  for (var i = 0; i < countries.length; i++) {
    countries[i].setMap(map);
    if (countries[i].single == "one") {
      countries[i].setOptions({
        fillColor: "rgb( 0, 200, 234, 0.4 )",
        fillOpacity: 0.7,
        strokeColor: "#00c8ea",
        strokeOpacity: 0.6,
        strokeWeight: 2,
      });

      google.maps.event.addListener(countries[i], "mouseover", function () {
        this.setOptions({
          fillColor: "rgb( 0, 200, 234, 0.4 )",
          fillOpacity: 0.7,
          strokeColor: "rgb( 0, 200, 234, 0.4 )",
          strokeOpacity: 1,
          strokeWeight: 2,
        });
		// Show title in hover
        infoWindow.setContent(this.title);
        infoWindow.setPosition(event.latLng);
        infoWindow.open(map);
		console.log('if: '+this.title);
      });

      google.maps.event.addListener(countries[i], "mouseout", function () {
        this.setOptions({
          fillColor: "rgb( 0, 200, 234, 0.4 )",
          fillOpacity: 0.6,
          strokeColor: "#00c8ea",
          strokeOpacity: 1,
          strokeWeight: 2,
        });
		infoWindow.close();
      });
    } else {
      countries[i].setOptions({
        fillColor: countries[i].countryColor, // give country color
        fillOpacity: 0.6,
      });

      google.maps.event.addListener(countries[i], "mouseover", function () {
        this.setOptions({
          fillColor: this.countryColorHover,
          fillOpacity: 0.7,
          strokeColor: this.countryColor,
          strokeOpacity: 1,
          strokeWeight: 2,
        });
		// Show title in hover
        infoWindow.setContent(this.title);
        infoWindow.setPosition(event.latLng);
        infoWindow.open(map);
		console.log('else '+this.title);
      });

      google.maps.event.addListener(countries[i], "mouseout", function () {
        if (active.title == this.title) {
          this.setOptions({
            fillColor: this.countryColorHover,
            fillOpacity: 0.7,
            strokeColor: this.countryColor,
            strokeOpacity: 1,
            strokeWeight: 2,
          });
        } else {
          this.setOptions({
            fillColor: this.countryColor,
            fillOpacity: 0.6,
            strokeColor: this.countryColorHover,
            strokeOpacity: 0,
            strokeWeight: 0,
          });
        }
		infoWindow.close();
      });

      var disableListener = true;
      google.maps.event.addListener(countries[i], "click", function (event) {
        if (this.clickStatus == "off") {
          if (typeof active.title !== "undefined") {
            active.setOptions({
              fillColor: active.countryColor,
              fillOpacity: 0.6,
              strokeColor: active.countryColorHover,
              strokeOpacity: 0,
              strokeWeight: 0,
            });
          }

          if (active.title == this.title) {
            this.setOptions({
              fillColor: this.countryColorHover,
              fillOpacity: 0.7,
              strokeColor: this.countryColor,
              strokeOpacity: 1,
              strokeWeight: 2,
            });
          } else {
            active = this;
          }
        }
		clearCountryHiglight();
		countryHighlight("countryHighlight_ajax","getSpecficCountryCluster",this.title);
		//geoPlace(this.title);
		country_details('country_detailAjax',this.title);
      });
    }
  }
}*/

function createCountry(coords, country) {
  console.log('country: ' + (country.title || country.country || 'Unknown'));

  var currentCountry = new google.maps.Polygon({
    paths: coords,
    title: country.title || country.country || 'Unknown Country', // ✅ fixed
    countryColorHover: country.countryColorHover || "#666666",
    countryColor: country.countryColor || "#cccccc",
    clickStatus: country.clickStatus || "off",
    single: country.single || "",
    code: country.iso || "",
    strokeOpacity: 0,
    fillOpacity: 0,
  });

  countries.push(currentCountry);
}

/* function createCountry(coords, country) {
	console.log('country: '+country.title);
  var currentCountry = new google.maps.Polygon({
    paths: coords,
    //strokeColor: 'white',
    //title: country.country,
    title: 'title',
    countryColorHover: country.countryColorHover,
    countryColor: country.countryColor,
    clickStatus: country.clickStatus,
    single: country.single,
    code: country.iso,
    strokeOpacity: 0,
    //strokeWeight: 1,
    //fillColor: country['color'], // can be used as default color
    fillOpacity: 0,
  });

  countries.push(currentCountry);
} */

function clearCountryHiglight() {
  for (var i = 0; i < countries.length; i++) {
    let option2 = {
      fillColor: "#000000",
      fillOpacity: 0.001,
      strokeColor: "#000000",
      strokeOpacity: 1,
      strokeWeight: 0,
    };

    countries[i].setOptions(option2);

    google.maps.event.addListener(countries[i], "mouseover", function () {
      this.setOptions({ visible: false });
    });

    google.maps.event.addListener(countries[i], "mouseout", function () {
      this.setOptions({ visible: false });
    });
  }
  countries = [];
}

var geocoder = null;

function geoPlace(address, singleMarker) {
  geocoder = new google.maps.Geocoder();

  findAddress(address, singleMarker);

  function findAddress(address, singleMarker) {
    var address = address;
    geocoder.geocode(
      {
        address: address,
      },
      function (results, status) {
        google.maps.event.addListenerOnce(map, "center_changed", centerChanged);
        google.maps.event.addListenerOnce(map, "bounds_changed", centerChanged);
        if (singleMarker != "singleMarker") {
          if (results != null) {
            map.fitBounds(results[0].geometry.viewport);
            map.setZoom(5);
          }
        }
      }
    );
  }
   function centerChanged() {
    if (map.getCenter().lat() < -85) {
      google.maps.event.addListenerOnce(map, "center_changed", function () {});
      map.setCenter(new google.maps.LatLng(-75, 0));
    }
  }
}

  //Add MArker function
  var infoWindow = new google.maps.InfoWindow({
    content: "",
  });

  function addMarkers(props) {
    var latLng = new google.maps.LatLng(
      parseFloat(props.coords.lat),
      parseFloat(props.coords.lng)
    );

    var marker = new google.maps.Marker({
      position: latLng,
      map: map,
      icon: {
        url: props.iconImage,
        //scaledSize: new google.maps.Size(40, 40)
      },
    });

    if (markers[0].status == "specificDis") {
      var contentString =
        '<div class="info__content__dis" data-id="' +
        props.id +
        '" data-country="' +
        props.F_country +
        '"><h6 id="firstHeading">' +
        props.content +
        "</h6></div>";

      var infowindow = new google.maps.InfoWindow({
        content: contentString,
      });
      infowindow.open(map, marker);
    }

    // needed to make Spiderfy work
    if (props.status !== "") {
      oms.addMarker(marker);
    }

    if (props.id) {
      marker.addListener("click", function () {
        // Click Marker
        infoWindow.setContent(
          "<div class='infoWindow'><strong>" +
            props.content +
            "</strong>" +
            props.status +
            "</div>"
        );
        infoWindow.open(map, marker);
        if (props.singleMarker != "singleMarker") {
          loadingShow();
          setTimeout(function () {
            loadingHide();
            jQuery(".secLi .fa-window-close").click();
          }, 2500);

          if (typeof props.check == "undefined") {
            sideBarHeader("getSingleFactory", props.F_country, null);
          } else {
            sideBarHeader("getSingleFactory", props.F_country, "goBackSearch");
          }
          AjaxGetSpecficCountry("getSingleFactory", props.id, null, null);
        }

        if (props.type == "industry") {
          industryDetailsAjax(props.id);
        }
      });
      marker.setPosition(latLng);
    }

    if (typeof props.carterList_parentId !== "undefined") {
      for (var l = 0; l < props.childFactory_latLng.length; l++) {
        allVendor = new google.maps.LatLng(
          parseFloat(props.coords.lat),
          parseFloat(props.coords.lng)
        );

        allFactory = {
          lat: parseFloat(props.childFactory_latLng[l].lat),
          lng: parseFloat(props.childFactory_latLng[l].lng),
        };

        flightPath = allFactory.polyline = new google.maps.Polyline({
          path: [allVendor, allFactory],
          strokeColor: "black",
          strokeOpacity: 0.8,
          strokeWeight: 3,
          geodesic: true,
          map: map,
          polylineID: i,
        });
        line.push(flightPath);
        flightPath.setMap(map);
      }
    }
    return marker;
  }



  $(document).ready(function() {
		const selectBoxes = $('.custom-select');
		selectBoxes.each(function() {
		const selectBox = $(this);
		const selected = selectBox.find('.selected');
		const options = selectBox.find('.option');
		selectBox.on('click', function() {
		selectBox.toggleClass('open');
		});
		options.each(function(index) {
		const option = $(this);
		option.on('click', function() {
		selected.html(option.html());
		});
		});
		});
		
		// Close options container when clicking outside of it
		$(document).on('click', function(event) {
		if (!$(event.target).closest('.custom-select').length) {
		$('.options-container').removeClass('open');
		$('.select-box').removeClass('open');
		}
		});
    });
/******************************End Clear Countries Higlight *****************************/