(function(D, d, $) {
    var frontends = function() {
        // Detect change on the client selector
        $('select#client_id').on('change', function () {
            var clientId = $(this).val().trim();

            if (clientId !== "NULL") {
                // Load in the clients services
                $.ajax ({
                    url: "/api/client/" + clientId + "/services",
                    type: "GET",
                    dataType: "json",
                    contentType: "application/json",
                    complete: function (xhr, textStatus) {
                        console.log(xhr);
                        console.log(textStatus);
                        console.log(xhr.responseText);

                        if (xhr.status !== 200) {
                            alert("An error occurred: " + xhr.status + " " + xhr.responseText);
                        } else {
                            var data;
                            if ("responseJSON" in xhr) {
                                data = xhr.responseJSON;

                                // Clear out the service selector
                                $('select#service_id').empty();
                                $('select#service_id').append(
                                    $('<option></option>', {
                                        "value": "NULL",
                                        text: "Please select a service"
                                    })
                                );

                                $.each(data.data, function (index, element) {
                                    $('select#service_id').append(
                                        $('<option></option>', {
                                            "value": element.id,
                                            text: element.name
                                        })
                                    );
                                });

                                $('select#service_id').prop('disabled', false);
                            }
                        }
                    }
                });
            }
        });
    };

    D.frontends = new frontends();
}(dashboard, document, jQuery));