/*
 * easyValidate, a form validation jquery plugin
 * By Alex Gill, www.apgdesign.co.uk
 * Version 1.0.1
 * Copyright 2011 APGDESIGN
 * Updated 30/12/2011
 * Free to use under the MIT License
 * http://www.opensource.org/licenses/mit-license.php
*/
(function($) {
		  
	// DEFINE METHOD
	$.fn.easyValidate = function(settings){
	
		// DEFAULT OPTIONS
		var config = {
			promptPosition : 'topRight',
			ajaxSubmit : false,
			ajaxSubmitFile: ""
		};
		
		// EXTEND OPTIONS
		var settings = $.extend(config, settings);
		
		return this.each(function(){
			
			// SET VARIABLES
			var promptText = "";
			var isError = false;
			var button;
			var form = $(this).find('form');
			
			// IF AJAX, BUILD AJAX PROMPTS
			if( settings.ajaxSubmit ){
				_buildAjaxPrompts();
				var ajaxError = $('.ajaxError');
				var ajaxSuccess = $('.ajaxSuccess');
				var ajaxLoading = $('.ajaxLoading');
			}
			
			// GET ALL FORM ELEMENTS
			var elements = $(this).find('input, textarea, radio, checkbox');	
			elements.each(function(){
				if( $(this).attr('type') == 'submit'){
					button = $(this);	
				}
			});									
			
			// BUTTON LISTENER
			button.click(function(e){
				elements.each(function(){
					var elementTagName = this.tagName;
					var elementType = $(this).attr('type');
					if( elementTagName == 'INPUT' && elementType == 'text' || elementTagName == 'TEXTAREA' ){
						_getRules( $(this) );
					}
				});
				if( _isValid() ){
					_formSubmit();
				}
				e.preventDefault();
			});
			
			// FOCUS LISTERNER
			elements.each(function(){
				$(this).blur(function(){
					_getRules( $(this) );				  
				});									   
			});
			
			// GET RULES FROM CLASS NAME
			function _getRules(element){
				var rulesParsed = element.attr('class');
				if(rulesParsed){
					var rules = rulesParsed.split(' ');
					_validate(element, rules);
				}
			};
			
			// APPLY RULES TO EACH ELEMENT
			function _validate(element, rules){
				
				// RESET VALUES FOR EACH ELEMENT
				promptText = "";
				isError = false;
				
				// LOOP RULES FOR EACH ELEMENT
				for(var i=0; i<rules.length; i++){
					if(rules[i] == 'required'){
						_required(element);
					}
					if(rules[i] == 'email'){
						_email(element);	
					}
				}
		
				// BUILD PROMPT IF RULE FAILS
				if(isError){
					_buildPrompt(element, promptText);
				} else {
					_removePrompt(element);						
				}
			};
			
			// RULE: REQUIRED FIELD
			function _required(element){
				if( ! element.val() ){
					isError = true;
					promptText = promptText + 'This field is required <br />';
				}
			};
			
			// RULE: VALID EMAIL STRING REQUIRED
			function _email(element){
				var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
				if (!filter.test( element.val() )) {
					isError = true;
					promptText = promptText + 'Please provide a valid email address';
				}
			};
			
			// RETURNS FORM VALIDATION STATUS
			function _isValid(){
				if( isError ){
					return false;
				} else {
					return true;
				}
			};
			
			// BUILDS DYNAMIC ERROR PROMPT
			function _buildPrompt(element, prompText){
				
				// REMOVE ALL EXISTING PROMPTS ON INIT
				_removePrompt(element);
				
				// CREATE ERROR WRAPPER
				var divFormError = $('<div></div>');
				$(divFormError).addClass('formError');
				$(divFormError).addClass( 'formError'+$(element).attr('name') );
				$('body').append(divFormError);

				// CREATE ERROR CONTENT
				var formErrorContent = $('<div></div>');
				$(formErrorContent).addClass('formErrorContent');
				$(divFormError).append(formErrorContent);
				$(formErrorContent).html(promptText);
				
				// CREATE ERROR ARROW
				var formErrorArrow = $('<div></div>');
				$(formErrorArrow).html('<div class="line10"><!-- --></div><div class="line9"><!-- --></div><div class="line8"><!-- --></div><div class="line7"><!-- --></div><div class="line6"><!-- --></div><div class="line5"><!-- --></div><div class="line4"><!-- --></div><div class="line3"><!-- --></div><div class="line2"><!-- --></div><div class="line1"><!-- --></div>');
				$(formErrorArrow).addClass('formErrorArrow');
				$(divFormError).append(formErrorArrow);
				
				// GET EACH ELEMENTS DIMENSIONS AND POSITION
				var fieldWidth = element.width();
				var fieldHeight = element.height();
				var fieldTopPosition = element.offset().top;
				var fieldLeftPosition = element.offset().left;
				
				// GET PROMPT POSITION DEPENDENT ON SETTINGS
				if( settings.promptPosition == 'topRight'){
					fieldTopPosition = fieldTopPosition - divFormError.height() +20;
					fieldLeftPosition = fieldLeftPosition + fieldWidth +20;
				}
				if( settings.promptPosition == 'topLeft'){
					fieldTopPosition = fieldTopPosition - divFormError.height();
					fieldLeftPosition = fieldLeftPosition;
				}
				
				// DEFINE LAYOUT WITH CSS
				$(divFormError).css({
					position : 'absolute',
					top : fieldTopPosition,
					left : fieldLeftPosition,
					opacity : 0
				});
				
				// SHOW PROMPT
				return $(divFormError).animate({
					opacity : 0.8
				});
				
			};
			
			// REMOVE PROMPT
			function _removePrompt(element){
				$('body').find( '.formError'+$(element).attr('name') ).remove();
			};
			
			// SUBMIT FORM
			function _formSubmit(){
				
				if( settings.ajaxSubmit ){
					
					ajaxLoading.ajaxStart(function(){
						$(this).show();						   
					});
					ajaxLoading.ajaxStop(function(){
						$(this).hide();						   
					});
					
					// SETUP AJAX
					$.ajax({
						type: 'POST',
						url: settings.ajaxSubmitFile,
						async: true,
						data : form.serialize(),
						success: function(data){
							ajaxSuccess.html(data).show('medium');
							form.hide();
						},
						error : function(xhr){
							ajaxError.html('Status: ' + xhr.status).show('medium');
						}
					});
					
				} else {
					form.submit();
				}
			};
			
			// BUILD AJAX PROMPTS
			function _buildAjaxPrompts(){

				var ajaxErrorDiv = $('<div></div>');
				ajaxErrorDiv.addClass('ajaxError');	
				form.after(ajaxErrorDiv);
				_centerPrompt(ajaxErrorDiv);

				var ajaxSuccessDiv = $('<div></div>');
				ajaxSuccessDiv.addClass('ajaxSuccess');
				form.after(ajaxSuccessDiv);
				_centerPrompt(ajaxSuccessDiv);
				
				var ajaxLoadingDiv = $('<div></div>');
				ajaxLoadingDiv.addClass('ajaxLoading');
				form.after(ajaxLoadingDiv);
				_centerPrompt(ajaxLoadingDiv);

			};
			
			// CENTER PROMPTS ON SCREEN
			function _centerPrompt(notification){
				var windowWidth = $(window).width();
				var windowHeight = $(window).height();
				var scrollTop = $('body').scrollTop();
				var notificationWidth = notification.outerWidth();
				var notificationHeight = notification.outerHeight();
				var positionTop;
				
				if(scrollTop > 0){
					positionTop = scrollTop;	
				} else {
					positionTop = (windowHeight - notificationHeight) / 2;
				}
				
				notification.css({
					"position" : "absolute",
					"top": positionTop,
					"right": (windowWidth - notificationWidth) / 2
				});
			};
			
		});
		
	};	
			
})(jQuery);