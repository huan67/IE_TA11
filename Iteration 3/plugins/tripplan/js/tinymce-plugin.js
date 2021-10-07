var tripPlanMap;
var tripPlanMarker
var tripPlanAutocomplete;
var tripPlanPluginUrl;

(function () {
    var loadedLibrary = false
    var myLatLng = {
        // show Amsterdam, The Netherlands -> Home of Checklist.com
        lat: 52.3702157,
        lng: 4.895167899999933,
    };

    function loadLibrary() {
        if (!loadedLibrary) {
            var key = tripplan_obj.googleMapsAPIKey;
            var script = document.createElement("script");
            script.type = "text/javascript";
            script.async = true;
            script.defer = true;
            script.type = "text/javascript";
            script.src = 'https://maps.googleapis.com/maps/api/js?key=' + key + '&libraries=places&callback=initTripPlanMap';
            document.body.appendChild(script);
            var style = document.createElement("style");
            style.textContent = '.pac-container { z-index: 9999999 !important; }';
            document.body.appendChild(style);
            loadedLibrary = true
        } else {
            initTripPlanMap()
        }
    }

    function isNumeric(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }

    window.initTripPlanMap = function () {
        tripPlanMap = new google.maps.Map(document.getElementById('map'), {
            center: myLatLng,
            zoom: 17
        });

        tripPlanMarker = new google.maps.Marker({
            position: myLatLng,
            map: tripPlanMap,
            draggable: true,
            title: tripplan_obj.markerText,
        });
        google.maps.event.addListener(tripPlanMarker, 'dragend', updateLatLong)

        initAutocomplete();
    }

    function initAutocomplete() {
        // Create the autocomplete object, restricting the search to geographical
        // location types.
        tripPlanAutocomplete = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
            {types: ['geocode', 'establishment']});

        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        tripPlanAutocomplete.addListener('place_changed', fillInAddress);

        // prevent enter from submit
        var inputField = document.getElementById('autocomplete');
        google.maps.event.addDomListener(inputField, 'keydown', function (event) {
            if (event.keyCode === 13) {
                event.stopPropagation();
            }
        });
    }

    function fillInAddress() {
        tripPlanMarker.setVisible(false);
        var place = tripPlanAutocomplete.getPlace();
        if (!place.geometry) {
            // TODO: notify user
            // window.alert("Autocomplete's returned place contains no geometry");
            return;
        }

        // If the place has a geometry, then present it on a map.
        if (place.geometry.viewport) {
            tripPlanMap.fitBounds(place.geometry.viewport);
        } else {
            tripPlanMap.setCenter(place.geometry.location);
            tripPlanMap.setZoom(17); // Why 17? Because it looks good.
        }
        tripPlanMarker.setIcon(/** @type {google.maps.Icon} */ ({
            url: tripPlanPluginUrl + '/../images/red.png',
            size: new google.maps.Size(71, 71),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(17, 34),
            scaledSize: new google.maps.Size(35, 35)
        }));
        tripPlanMarker.setPosition(place.geometry.location);
        tripPlanMarker.setVisible(true);
        updateLatLong()
        document.getElementById('placeId').value = place.place_id;
    }

    function updateLatLong() {
        document.getElementById('lat').value = tripPlanMarker.getPosition().lat();
        document.getElementById('lng').value = tripPlanMarker.getPosition().lng();
    }

    /**
     * get value of specific attribute
     * @param text
     * @param attrName
     * @returns {string}
     */
    function getAttr(text, attrName) {
        attrName = new RegExp(attrName + '=\"([^\"]+)\"', 'g').exec(text);
        return attrName ? window.decodeURIComponent(attrName[1]) : '';
    };

    /**
     * convert shortcode to html code
     * @param {String} identifier is used revert from html to shortcode
     * @param {String} data shortcode attributes as string. E.g: [tp-poi att1="x" att2="y"] --> data is `att1="x" att2="y"`
     * @param {String} content shortcode content
     * @returns {string}
     */
    function html(identifier, data, content) {
        var attr = data
        data = window.encodeURIComponent(data);
        // content = window.encodeURIComponent(content);

        var regex = /presentation="([^"]*)/g;
        var arr = regex.exec(attr)
        var presentation = arr[1] ? arr[1] : '<h3>';
        var style = ''
        if (presentation === 'span') {
            style = ' style="display: inline;"'
        }

        return '<' + presentation + style + '>' +
            '<a class="mceItem ' + identifier + '" ' + 'data-tp-attr="' + data + '" data-mce-resize="false" data-mce-placeholder="1">' + content + '</a>'
            + '</' + presentation + '>';
    }

    function replaceShortcodes(content) {
        //match [tp-poi(attr)](con)[/tp-poi]
        return content.replace(/\[tp-poi([^\]]*)]([^\[]*)\[\/tp-poi]/g, function (all, attr, content) {
            return html('tp-poi', attr, content);
        });
    }

    function restoreShortcodes(content) {
        content = content.replace(/(?:<(?:span|h[1-6])[^>]*>)*<a ([^>]+)>([^<]*)<\/a>(?:<\/(?:span|h[1-6])>)*/g, function (match, attrs, content) {
            var data = getAttr(attrs, 'data-tp-attr');

            if (data) {
                data = data.trim();
                return '[tp-poi ' + data + ']' + content + '[/tp-poi]';
            }
            return match;
        });
        return content
    }

    tinymce.create('tinymce.plugins.tripplan', {
        init: function (editor, url) {
            tripPlanPluginUrl = url;
            //add popup
            editor.addCommand('tripplanPOI', function (ui, v) {
                //setup defaults
                var name = '';
                var lng = 4.895167899999933;
                var lat = 52.3702157;
                var placeId = '';
                var url = '';
                var durationQty = '';
                var durationUnit = '';
                var presentation = 'h3';

                if (v.name)
                    name = v.name;
                if (v.lng)
                    lng = v.lng;
                if (v.lat)
                    lat = v.lat;
                if (v.placeId)
                    placeId = v.placeId;
                if (v.url)
                    url = v.url;
                if (v.durationQty)
                    durationQty = v.durationQty;
                if (v.durationUnit)
                    durationUnit = v.durationUnit;
                if (v.presentation)
                    presentation = v.presentation;

                editor.windowManager.open({
                    title: 'Add POI (Point Of Interest)',
                    body: [
                        {
                            type: 'label',
                            text: 'Start typing the name of the POI and select it from the drop down',
                            minWidth: 600,
                        },
                        {
                            type: 'textbox',
                            name: 'name',
                            label: 'Name',
                            placeholder: 'The name of your POI',
                            required: true,
                            value: name,
                            id: 'autocomplete',
                        },
                        {
                            type: 'textbox',
                            name: 'longitude',
                            required: true,
                            hidden: 'true',
                            value: lng,
                            id: 'lng',
                        },
                        {
                            type: 'textbox',
                            name: 'latitude',
                            hidden: 'true',
                            required: true,
                            value: lat,
                            id: 'lat',
                        },
                        {
                            type: 'textbox',
                            name: 'placeId',
                            hidden: 'true',
                            value: placeId,
                            id: 'placeId',
                        },
                        {
                            type: 'container',
                            name: 'map',
                            html: '<div id="map" style="height: 250px;"></div>'
                        },
                        {
                            type: 'textbox',
                            name: 'url',
                            label: 'Website',
                            placeholder: 'http://... optional link to the website of this POI',
                            required: false,
                            value: url,
                        },
                        {
                            type: 'container',
                            layout: 'flex',
                            direction: 'row',
                            spacing: 20,
                            label: 'Suggested Visit Duration',
                            items: [
                                {
                                    type: 'textbox',
                                    subtype: 'number',
                                    name: 'duration_qty',
                                    required: false,
                                    length: 5,
                                    value: durationQty,
                                    min: 0,
                                    max: 33,
                                },
                                {
                                    name: 'duration_unit',
                                    type: 'listbox',
                                    subtype: 'text',
                                    required: true,
                                    value: durationUnit,
                                    values: [
                                        {text: 'Hours', value: 'h'},
                                        {text: 'Days', value: 'd'},
                                    ],
                                    flex: 1,
                                }
                            ]
                        },
                        {
                            type: 'listbox',
                            name: 'presentation',
                            label: 'Presentation',
                            value: presentation,
                            values: [
                                {text: 'Inline', value: 'span'},
                                {text: 'Heading 1', value: 'h1'},
                                {text: 'Heading 2', value: 'h2'},
                                {text: 'Heading 3', value: 'h3'},
                                {text: 'Heading 4', value: 'h4'},
                                {text: 'Heading 5', value: 'h5'},
                                {text: 'Heading 6', value: 'h6'},
                            ],
                            required: true,
                            // value: 'h3'
                        },
                    ],
                    onopen: function (e) {
                        if (!isNumeric(lat)) {
                            lat = 52.3702157
                        }
                        if (!isNumeric(lng)) {
                            lng = 4.895167899999933
                        }

                        myLatLng = {
                            lat: parseFloat(lat),
                            lng: parseFloat(lng),
                        }
                        loadLibrary();
                    },
                    onsubmit: function (e) {
                        var duration = "";
                        if (e.data.duration_qty) {
                            duration = ' duration="' + e.data.duration_qty + e.data.duration_unit + '"';
                        }
                        ;
                        var url = "";
                        if (e.data.url) {
                            url = ' url="' + e.data.url + '"';
                        }
                        ;
                        shortcode = '[tp-poi long="' + e.data.longitude + '" lat="'
                            + e.data.latitude + '" placeId="' + e.data.placeId + '" presentation="' + e.data.presentation
                            + '" durationQty="' + e.data.duration_qty + '" durationUnit="' + e.data.duration_unit
                            + '" props=""' + url + duration + ']' + e.data.name + '[/tp-poi]'
                        ;

                        if (e.data.presentation == 'span' &&
                            (
                                ['H1', 'H2', 'H3', 'H4', 'H5', 'H6'].indexOf(editor.selection.getNode().nodeName) >= 0
                                || ['H1', 'H2', 'H3', 'H4', 'H5', 'H6'].indexOf(editor.selection.getNode().parentNode.nodeName) >= 0
                            )
                        ) {
                            shortcode = '<p>' + shortcode + '</p>';
                        }

                        editor.execCommand('mceInsertContent', 0, shortcode);
                    }
                });
            });
            editor.addButton('tripplanMenu', {
                type: 'menubutton',
                text: tripplan_obj.tripplanBtn,
                menu: [
                    {
                        text: tripplan_obj.tripplanBtnPOI,
                        onclick: function () {

                            var name = editor.selection.getContent();
                            editor.execCommand('tripplanPOI', '', {
                                name: name,
                            });

                        }
                    },
                    {
                        text: tripplan_obj.tripplanBtnPlan,
                        onclick: function () {
                            editor.windowManager.open({
                                title: 'Add Trip Plan',
                                body: [
                                    {
                                        type: 'label',
                                        text: 'Enter the name of your Trip and which heading to use for presentation',
                                        minWidth: 600,
                                    },
                                    {
                                        type: 'textbox',
                                        name: 'name',
                                        label: 'Name',
                                        placeholder: 'Name of the trip',
                                    },
                                    {
                                        type: 'listbox',
                                        name: 'heading',
                                        label: 'Heading',
                                        values: [
                                            {text: 'Heading 1', value: 'h1'},
                                            {text: 'Heading 2', value: 'h2'},
                                            {text: 'Heading 3', value: 'h3'},
                                            {text: 'Heading 4', value: 'h4'},
                                            {text: 'Heading 5', value: 'h5'},
                                            {text: 'Heading 6', value: 'h6'},
                                        ],
                                        value: 'h3'
                                    },
                                ],
                                onsubmit: function (e) {
                                    // var selected_text = editor.selection.getContent();
                                    shortcode = '[tp-trip name="' + e.data.name + '" heading="' + e.data.heading + '"][/tp-trip]';
                                    editor.execCommand('mceInsertContent', 0, shortcode);
                                }
                            });
                        }
                    },
                    {
                        text: tripplan_obj.tripplanBtnMap,
                        onclick: function () {
                            shortcode = '[tp-map height="500px" /]';
                            editor.execCommand('mceInsertContent', 0, shortcode);
                        }
                    }
                ]
            });

            //replace from shortcode to an placeholder image
            editor.on('BeforeSetcontent', function (event) {
                event.content = replaceShortcodes(event.content);
            });

            editor.on('GetContent', function (event) {
                event.content = restoreShortcodes(event.content);
            });

            //open popup on placeholder double click
            editor.on('DblClick', function (e) {
                var cls = e.target.className.indexOf('tp-poi');
                if (e.target.nodeName == 'A' && e.target.className.indexOf('tp-poi') > -1) {
                    var attrs = e.target.attributes['data-tp-attr'].value;
                    attrs = window.decodeURIComponent(attrs);

                    tinyMCE.activeEditor.selection.select(e.target);
                    var name = editor.selection.getContent();
                    editor.execCommand('tripplanPOI', '', {
                        name: e.target.innerHTML,
                        lng: getAttr(attrs, 'long'),
                        lat: getAttr(attrs, 'lat'),
                        placeId: getAttr(attrs, 'placeId'),
                        url: getAttr(attrs, 'url'),
                        durationQty: getAttr(attrs, 'durationQty'),
                        durationUnit: getAttr(attrs, 'durationUnit'),
                        presentation: getAttr(attrs, 'presentation'),
                    });
                }
            });
        },

        createControl: function (n, cm) {
            return null;
        },

        getInfo: function () {
            return {
                longname: 'TripPlan Plugin',
                author: 'Checklist',
                authorurl: 'https://checklist.com',
                infourl: 'https://checklist.com/publishers/',
                version: "1.0"
            };
        }
    });

    // Register plugin
    tinymce.PluginManager.add('tripplan', tinymce.plugins.tripplan);
})();