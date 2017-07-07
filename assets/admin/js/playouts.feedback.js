"use strict";

window.jQuery = window.$ = jQuery;

var Pl_feedback = {

	start: function() {

		this.bind();

	}

	,bind: function() {

		$('#the-list').find('[data-slug="peenapo-layouts"] span.deactivate a').on('click', Pl_feedback.on_deactivate_click);

	}

	,on_deactivate_click: function(e) {

		e.preventDefault();

		/*$.ajax({
            type: 'POST',
            url: playouts_admin_feedback.ajax,
            data: $('#pl-feedback-form').serialize(),
            dataType: 'json',
            success: function( response ) {

                console.log( response );

            }
        });*/

	}

}

Pl_feedback.start();
