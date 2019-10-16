(function(D, d, $) {
    var forms = function() {
        // Detect form submission
        $('form *[type="submit"]').on("click", function (event) {
            event.preventDefault();
            event.stopPropagation();

            // Load the parent form
            var $form = $(this).closest("form");

            var valid = true;
            var details = {};
            $form.find('input, select').each(function (index, element) {
                var $element = $(element);
                var required = false;
                var value = null;
                var name = $element.attr("name");
                var type = $element.attr("type");

                // Check if required
                if ($element.prop('required')) {
                    required = true;
                }

                // Split by type
                // TODO - Include client side validation
                if (type === "checkbox") {
                    // Check if this is checked
                    if ($element.is(':checked')) {
                        if ($element.attr('value')) {
                            value = $element.val();
                        } else {
                            value = "checked";
                        }
                    }
                } else if ((type === "text") || (type === "email") || (type === "date") || (type === "time")) {
                    value = $element.val().trim();
                } else if (type === "submit") {
                    value = "submit";
                } else if ($element.is("select")) {
                    value = $element.val().trim();

                    if (value === "NULL") {
                        value = null;
                    }
                } else {
                    console.log("Unsupported Input Type:");
                    console.log($element);
                }

                if ((value === undefined) || (value === null) || (value === "")) {
                    if (required) {
                        valid = false;
                    }
                }

                details[name] = value;
            });

            if (!valid) {
                // Form is invalid -> Do not submit.
                // TODO - Include error display messaging
                alert("Form incorrectly filled. Please try again.");
            } else {
                // Get the form details
                var method = "get";
                if ($form.attr("method")) {
                    method = $form.attr("method");
                }

                var action = null;
                if ($form.attr("action")) {
                    action = $form.attr("action");
                }

                if ((action === undefined) || (action === null) || (action === "") || (action === "#")) {
                    alert("No form action");
                } else {
                    // Submit the form via Ajax
                    console.log(details);
                    $.ajax ({
                        url: action,
                        type: method,
                        dataType: "json",
                        contentType: "application/json",
                        data: JSON.stringify(details),
                        beforeSend: function () {
                            // Disable the submit button
                            $form.find('*[type="submit"]').prop("disabled", true);
                        },
                        complete: function (xhr, textStatus) {
                            console.log(xhr);
                            console.log(textStatus);
                            console.log(xhr.responseText);

                            var alertRequired = false;
                            var success = false;
                            var message = "Internal Server Error";

                            // Parse the response
                            switch (xhr.status) {
                                case 200:
                                    success = true;
                                    message = "OK";
                                    break;
                                case 201:
                                    success = true;
                                    message = "Created";
                                    break;
                                case 202:
                                    success = true;
                                    message = "Accepted";
                                    break;
                                case 204:
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
                                case 405:
                                    alertRequired = true;
                                    message = "Method Not Allowed";
                                    break;
                                case 406:
                                    alertRequired = true;
                                    message = "Not Acceptable";
                                    break;
                                case 408:
                                    alertRequired = true;
                                    message = "Request Timeout";
                                    break;
                                case 409:
                                    alertRequired = true;
                                    message = "Conflict";
                                    break;
                                case 500:
                                    alertRequired = true;
                                    message = "Internal Server Error";
                                    break;
                                case 501:
                                    alertRequired = true;
                                    message = "Not Implemented";
                                    break;
                                case 502:
                                    alertRequired = true;
                                    message = "Bad Gateway";
                                    break;
                                case 503:
                                    alertRequired = true;
                                    message = "Service Unavailable";
                                    break;
                                default :
                                    alertRequired = true;
                                    message = "Internal Server Error";
                                    break;
                            }

                            var data;
                            if ("responseJSON" in xhr) {
                                data = xhr.responseJSON;

                                if ("message" in data) {
                                    message = data.message;
                                }
                            }

                            if (alertRequired) {
                                // TODO - Correctly display error messaging
                                alert(message);
                            }

                            // Check if a redirection location has been passed
                            if (data && ("location" in data)) {
                                console.log(data.location);
                                window.location.replace(data.location);
                            }

                            $form.find('*[type="submit"]').prop("disabled", false);
                        }
                    });
                }
            }
        });
    };

    D.forms = new forms();
}(dashboard, document, jQuery));