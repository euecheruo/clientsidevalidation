/*!
 * JQuery Address
 *
 * Copyright 2012, Emeka Echeruo
 *
 * Depends:
 *	jquery.js (core)
 *	jquery.validate.js
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 */

/**
 * Handles client-side validation.
 *
 * @param mixed $options
 * @return void
 */
	$.fn.validation = function($options) {
		var options, fieldSelector, form = $(this);

		$options['errorPlacement'] = function(error, element) {

							        	var error_div = $('<div></div>', { class: 'error-message', html: error.html() });

							        	if(!element.parent().hasClass('error'))
								        	element.parent().addClass(element.attr('class') + ' error');

										if(!element.next().hasClass('error-message'))
											element.after(error_div);

										if(!error.hasClass('hide'))
										{
											error.addClass('hide');
											error.data('attach', element);
											error_div.after(error);
										}

							        };

		$options['success'] = function(label) {

									var element = label.data('attach');

									if(element.parent().hasClass('error'))
										element.parent().removeClass(element.attr('class') + ' error');

									if(element.next().hasClass('error-message'))
										element.next().remove();

									label.remove();

							  };

	 	$options['submitHandler'] = function(form) {
   										form.submit();
 									};

		form.validate($options);
		$.addValidationRules($options);
	};

/**
 * Add validation rules.
 *
 * @param mixed $options
 * @return void
 */
	$.addValidationRules = function($options) {

		if($options.hasOwnProperty('rules'))
		{
			for (field in $options['rules'])
			{
	  			fieldSelector = $.getValiationSelector(field);
				var fieldRule = {};

	  			if(typeof $options['rules'][field] == 'object')
	  			{
					for (rules in $options['rules'][field])
					{
						var rule = new String(rules).toLowerCase();
		  				if(!fieldSelector.hasClass(rule))
		  					fieldSelector.addClass(rule);

						if(typeof $options['rules'][field][rules] != 'undefined')
		  					fieldRule[rules] = $options['rules'][field][rules];

						if($options.hasOwnProperty('messages'))
						{
							if($options['messages'].hasOwnProperty(field))
								fieldRule['messages'] = $options['messages'][field];
						}
	  				}
	  			} else if(typeof $options['rules'][field] == 'string') {

					var rule = new String($options['rules'][field]).toLowerCase();
	  				if(!fieldSelector.hasClass(rule))
	  					fieldSelector.addClass(rule);

					if(typeof $options['rules'][field] != 'undefined')
	  					fieldRule[$options['rules'][field]] = $options['rules'][field];

					if($options.hasOwnProperty('messages'))
					{
						if($options['messages'].hasOwnProperty(field))
							fieldRule['messages'] = $options['messages'][field];
					}
	  			}
				fieldSelector.rules('add', fieldRule);

	  		}
		}

	};

/**
 * Remove validation rules.
 *
 * @param mixed $options
 * @return void
 */
	$.removeValidationRules = function($options) {

		if($options.hasOwnProperty('rules'))
		{
			for (field in $options['rules'])
			{
	  			fieldSelector = $.getValiationSelector(field);
				var fieldRule = "";

	  			if(typeof $options['rules'][field] == 'object')
	  			{
					for (rules in $options['rules'][field])
					{
						if(typeof $options['rules'][field][rules] != 'undefined')
		  					fieldRule += " " + $options['rules'][field][rules];
	  				}
	  			} else if(typeof $options['rules'][field] == 'string') {

					if(typeof $options['rules'][field] != 'undefined')
	  					fieldRule += " " + $options['rules'][field];

	  			}
				fieldSelector.rules('remove', fieldRule);

	  		}
		}

	};

/**
 * get selector.
 *
 * @param string field
 * @return element selector
 */
	$.getValiationSelector = function(field) {
 		return ($("#" + field).length == 0) ? $(field) : $("#" + field);
	};
