function initMap() {
    var bounds  = new google.maps.LatLngBounds();
  
    var map = new google.maps.Map(document.getElementById('tripplan-map'), {
      
    });

    var infowindow = new google.maps.InfoWindow({
        // content: contentString
    });

    function generateContent(name, url, duration, props){
        var result = "";

        if (name){
            if (url){
                result += '<h3 class="tripplan-map-poi-info-title"><a href="' + url + '" target="_blank">' + name + '</a></h3>';
            } else {
                result += '<h3 class="tripplan-map-poi-info-title">' + name + '</h3>';
            }
        }

        if (duration){
            result += '<div class="tripplan-map-poi-info-duration">' + duration + '</div>';
        }

        if (props){
            result += '<div class="tripplan-map-poi-info-props">' + props + '</div>';
        }

        return  result;
    }

    jQuery(".tripplan-poi").each(function(index){
        var name = jQuery(this).find('.tripplan-poi-name').text();
        var longitude = parseFloat(jQuery(this).data("long"));
        var latitude = parseFloat(jQuery(this).data("lat"));
        var url = jQuery(this).data("url");
        var props = jQuery(this).data("props");
        var duration = jQuery(this).data("duration");
        var pos = {lat: latitude, lng: longitude};
        var marker = new google.maps.Marker({
            position: pos,
            map: map,
            label: ""+(index+1),
          });
        bounds.extend(new google.maps.LatLng(latitude, longitude));
        marker.addListener('click', function() {
            infowindow.setContent(generateContent(name, url, duration, props));
            infowindow.open(map, marker);
        });
    });

    map.fitBounds(bounds); 
    map.panToBounds(bounds); 
    
    var defaultZoom;

    jQuery(".tripplan-trip-poi").click(function(e) {
        e.preventDefault();
        var longitude = parseFloat(jQuery(this).data("long"));
        var latitude = parseFloat(jQuery(this).data("lat"));
        var location = new google.maps.LatLng(latitude, longitude);
        map.panTo(location);
        if (!defaultZoom){
            defaultZoom = map.getZoom();
        }
        map.setZoom(defaultZoom+4);
    })

    var centerControlDiv = document.createElement('div');
    var centerControl = new CenterControl(centerControlDiv, map);

    function CenterControl(controlDiv, map) {
        // Set CSS for the control border.
        var controlUI = document.createElement('div');
        controlUI.style.backgroundColor = '#fff';
        controlUI.style.border = '2px solid #fff';
        controlUI.style.borderRadius = '3px';
        controlUI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
        controlUI.style.cursor = 'pointer';
        controlUI.style.marginBottom = '22px';
        controlUI.style.textAlign = 'center';
        controlUI.title = 'Click to recenter the map';
        controlDiv.appendChild(controlUI);
        // Set CSS for the control interior.
        var controlText = document.createElement('div');
        controlText.style.color = 'rgb(25,25,25)';
        controlText.style.fontSize = '14px';
        controlText.style.lineHeight = '10px';
        controlText.style.paddingLeft = '2px';
        controlText.style.paddingRight = '2px';
        controlText.innerHTML = '<img src="' + tripplanScript.pluginsUrl + '/images/ic_filter_center_focus_black_24px.svg" />';
        controlUI.appendChild(controlText);

        // Setup the click event listeners: simply set the map to Chicago.
        controlUI.addEventListener('click', function() {
            map.fitBounds(bounds); 
            map.panToBounds(bounds); 
        });

        }

    centerControlDiv.index = 1;
    map.controls[google.maps.ControlPosition.BOTTOM_CENTER].push(centerControlDiv);
}

jQuery(document).ready(function($){
    jQuery('.tripplan-checkbox').click(function(e){
        var currentIndex = jQuery(this).data("index");
        var toValue = jQuery(this).prop("checked")
        jQuery(".tripplan-checkbox").each(function(){
            var i = jQuery(this).data("index");
            if (i=== currentIndex){
                jQuery(this).prop("checked", toValue);
            }
        });
    });

    jQuery('.tripplan-poi-tag').click(function(e){
        e.preventDefault();
    });

});

function commandTripPlan(event, command, id, source, url){
    event.preventDefault();
    var tasks = "";
    jQuery("#" + id + " input:checkbox:checked").each(function(index, element){
        tasks += jQuery(this).parent().attr('id') + ",";
    });
    var elem = event.target;
    if (elem.tagName === "IMG"){
        elem = elem.parentElement;
    }
    var dest = getDestinationAPI(elem.href) + command + "?id=" + id + source + "&ids=" + tasks + "&url=" + url;
    var win = window.open(dest, '_blank');
    if (win) {
        win.focus();
    } else {
        window.location.href = dest;
    }
}


function getDestinationAPI(data) {
    var    a= document.createElement('a');
    a.href = data;
    return "https://api." + a.hostname + "/";
}

