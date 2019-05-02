let requestAnswer   = $('.request-answer');
let responseText 	= $('#responseText');
let responseButton  = $('#responseButton');

responseText.focus();

responseButton.click(function() {

	if(responseText.val().trim() != '') {

		$.get("api/answer.php", { request: responseText.val() },
			function(data) {

	  			requestAnswer.append('\n[Siz]: ' + responseText.val() +
	  							  	 '\n[Naname]: ' + data.response + '\n');
	  			responseText.val('');
	  			responseText.focus();
				requestAnswer.scrollTop(requestAnswer.prop('scrollHeight'));
				  
			}
		);

	}

});

responseText.keypress(function(event) {
	
	if(event.which == 13) {

		event.preventDefault();
		responseButton.click();

	}

});
