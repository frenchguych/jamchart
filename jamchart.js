(function($) {

    'use strict';

    $(document).ready(function() {
        jQuery.get(
            "rest/data", {}, function(data, textStatus, jqXHR) {
                console.log(data);
            }
        );
    });

})(window.jQuery);