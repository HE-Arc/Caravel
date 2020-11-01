//
// Bootstrap Datepicker
//

'use strict';

var Datepicker = (function() {

    // Variables

    var $datepicker = $('.datepicker');


    // Methods

    function init($this) {
        var options = {
            disableTouchKeyboard: true,
            autoclose: true,
            startDate: 0,
        };

        $this.datepicker(options);
    }


    // Events

    if ($datepicker.length) {
        $datepicker.each(function() {
            init($(this));
        });
    }

})();