(function(D, d, $) {
    var deletion = function() {
        // Detect change on a deletion checkbox
        $('form .deletion-checkbox').on('change', function (event) {

            var $form = $(this).closest('form');
            var $submit = $form.find('input[type="submit"]');

            if ($(this).is(':checked')) {
                // Remove the disabled state from the confirmation button
                $submit.prop('disabled', false);
                $submit.prev('label').removeClass('disabled');
            } else {
                $submit.prop('disabled', true);
                $submit.prev('label').addClass('disabled');
            }
        });
    };

    D.deletion = new deletion();
}(dashboard, document, jQuery));