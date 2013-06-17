

This is an add-on to CakePHP framework that allows for client-side validation.

CakePHP already has a built in data validation which is an important part of its utility core. 
In the example below the $validate array is added to the User Model with validation rules that 
allows you to check if user enters more than 50 characters and if the user the field is empty, 
also allows you choose what error message should be displayed if the field is not valid.

The add-on allows you to use it in a relatively similar manner. 

Usage for $validation options:

	$forms - array - ids of the forms that the elements to be validated are anchored to
	$message - array - error messages per rules
	$rules - array - list of validation rules.

	echo $this->Form->input($fieldName, $options = array(), $validation = array());

Here is an example below:

	echo $this->Form->create('User');
	echo $this->Form->input('User.name', array(), array('forms' => array('UserLoginForm'), 'messages' => array('required' => 'Please User Name is required', 'maxlength' => 'Cannot be more than 50 characters'), 'rules' => array('required' => true, 'maxlength' => 50) ) );
	echo $this->Form->end('submit', array('UserLoginForm') );

