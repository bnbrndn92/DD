(function(D, d, $) {
    var managementHome = function() {
        /**
         * @var {bool}
         */
        var processing = false;

        /**
         * @var {int}
         */
        var clientId = null;

        /**
         * @var {int}
         */
        var serviceId = null;

        /**
         * createTile()
         * @type {Function}
         *
         * Creates a resource tile for use by services and frontends
         *
         * @param {int} id
         * @param {string} text
         * @param {string} link
         * @param {string} safe
         *
         * @return {Object}
         */
        var createTile = (function (id, text, link, safe) {
            return $('<li></li>', {
                        "class": "list-group-item"
            }).append(
                $('<div></div>', {
                    "class": "row"
                }).append([
                    $('<div></div>', {
                        "class": "col-10 text-left",
                    }).append(
                        $('<a></a>', {
                            href: "#",
                            "class": "text-secondary text-left",
                            text: text,
                        }).data("safe", safe)
                            .data("id", id)

                    ),
                    $('<div></div>', {
                        "class" : "col-2 pl-0 text-right"
                    }).append(
                        $('<a></a>', {
                            href: link,
                            "class": "",
                        }).append(
                            $('<i></i>', {
                                "class": "",
                                "style": "font-style: normal;",
                            }).html("&#9881;")
                        )
                    )
                ])
            );
        });

        /**
         * getServices()
         * @type {Function}
         *
         * @param {int} id
         *
         * @return {object|bool}
         */
        var getServices = (function (id) {
             if (!processing) {
                 processing = true;

                 $.ajax ({
                     url: "/services/client/current/" + id,
                     type: "GET",
                     dataType: "json",
                     contentType: "application/json",
                     beforeSend: function () {
                         // Disable any buttons
                     },
                     complete: function (xhr, textStatus) {
                         // Process the data
                         var alertRequired = false;
                         var success = false;
                         var message = "Internal Server Error";

                         // Parse the response
                         switch (xhr.status) {
                             case 200:
                                 success = true;
                                 message = "OK";
                                 break;
                             case 204 :
                                 success = true;
                                 message = "No Content";
                                 break;
                             case 400:
                                 alertRequired = true;
                                 message = "Bad Request";
                                 break;
                             case 401:
                                 alertRequired = true;
                                 message = "Unauthorized";
                                 break;
                             case 403:
                                 alertRequired = true;
                                 message = "Forbidden";
                                 break;
                             case 404:
                                 alertRequired = true;
                                 message = "Not Found";
                                 break;
                             case 500:
                                 alertRequired = true;
                                 message = "Internal Server Error";
                                 break;
                         }

                         var data;
                         if ("responseJSON" in xhr) {
                             data = xhr.responseJSON;

                             if ("message" in xhr) {
                                 message = xhr.message;
                             }
                         }

                         if (alertRequired) {
                             alert(message);
                         }

                         if ((data !== undefined) && (data !== null) && (typeof data === 'object') && ("data" in data)) {
                             var $tiles = $('<ul></ul>', {
                                 "class": "list-group mb-3 rounded-0",
                             });
                             $.each(data.data, function (index, element) {
                                 // Construct the tile
                                 var $tile = createTile(element.id, element.name, "/management/service/" + element.id, element.safeName);

                                 $tiles.append($tile);
                             });

                             $tiles.insertBefore($('#management-services-list .btn'));
                         } else {
                             $('#management-services-list .alert').removeClass('d-none');
                         }

                         $('#management-services-list h3').html("Services");

                         processing = false;
                     }
                 });
             } else {
                 console.log("AJAX request in progress");
             }
        });

        // Detect click on a client selector button
        $('body').on('click', '#management-clients-list ul a.text-left', function (event) {
            event.preventDefault();
            event.stopPropagation();

            // Pull the data from this button
            var id = $(this).data('id');

            // Adjust the display
            $('#management-services-list .alert').addClass('d-none');
            $('#management-services-list ul').remove();
            $('#management-services-list h3').html("Loading...");

            clientId = parseInt(id);
            getServices(id);

            $('#management-services-list .btn').removeClass('disabled');
        });

        // Detect click on a service selector button
        $('body').on('click', '#management-services-list ul a.text-left', function (event) {
            event.preventDefault();
            event.stopPropagation();

            // Pull the data from this button
            var id = $(this).data('id');

            $('#management-frontends-list .alert').addClass('d-none');
            $('#management-frontends-list ul').remove();
            $('#management-frontends-list h3').html("Loading...");

            serviceId = parseInt(id);
            // TODO - Populate

            $('#management-frontends-list .btn').removeClass('disabled');
        });

        // Detect click on a frontend selector button
        $('body').on('click', '#management-frontends-list ul a.text-left', function (event) {
            event.preventDefault();
            event.stopPropagation();

            // TODO - Populate
        });
    };

    D.managementHome = new managementHome();
}(dashboard, document, jQuery));