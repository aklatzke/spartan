jQuery(function($){
	// the wordpress admin area is weird about checkboxes and
	// won't send a value across for checked checkboxes unless they have
	// a predefined value
	// this mitigates that issue by forcing checkboxes to have a boolean value
	$("input[type=checkbox]").on("click", function(){
		// Despite the above text, this line is required if you create a custom
		// taxonomy because it is treated differently in the admin for
		// some reason. Just check against the page's id in the admin and return.
		//
		// if( $("#taxonomy-exampleName") ) return;

		$(this).val( Boolean( $(this).is(":checked") ) );
	});
})