<?php
/**
 * Overrides JQueryEngineHelper Class.
 * jQuery Engine Helper for JsHelper
 *
 * PHP Version 5
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 *
 * @modifiedBy:
 * 				Emeka U Echeruo (emeka.echeruo@gmail.com)
 */

class JqueryEngineHelper extends JsBaseEngineHelper {

/**
 * Option mappings for jQuery
 *
 * @var array
 */
	protected $_optionMap = array(
		'request' => array(
			'type' => 'dataType',
			'before' => 'beforeSend',
			'method' => 'type',
			'where' => 'context',
			'code' => 'statusCode',
		),
		'sortable' => array(
			'complete' => 'stop',
		),
		'drag' => array(
			'snapGrid' => 'grid',
			'container' => 'containment',
		),
		'drop' => array(
			'leave' => 'out',
			'hover' => 'over'
		),
		'slider' => array(
			'complete' => 'stop',
			'direction' => 'orientation'
		),
		'validate' => array(
			'ignoreItems' => 'ignore',
			'form' => 'debug',
			'rule' => 'rules',
			'message' => 'messages',
			'group' => 'groups',
			'submit' => 'onsubmit',
			'focus' => 'onfocusout',
			'key' => 'onfocusout',
			'click' => 'onclick',
			'invalid' => 'focusInvalid',
			'cleanup' => 'focusCleanup',
			'item' => 'meta',
			'vaild' => 'validClass',
			'wrap' => 'wrapper',
			'label' => 'errorLabelContainer',
			'error' => 'errorContainer'
		),
		'validator' => array(
			'form' => 'debug',
			'rule' => 'rules',
			'message' => 'messages'
		)

	);

/**
 * Callback arguments lists
 *
 * @var string
 */
	protected $_callbackArguments = array(
		'slider' => array(
			'start' => 'event, ui',
			'slide' => 'event, ui',
			'change' => 'event, ui',
			'stop' => 'event, ui'
		),
		'sortable' => array(
			'start' => 'event, ui',
			'sort' => 'event, ui',
			'change' => 'event, ui',
			'beforeStop' => 'event, ui',
			'stop' => 'event, ui',
			'update' => 'event, ui',
			'receive' => 'event, ui',
			'remove' => 'event, ui',
			'over' => 'event, ui',
			'out' => 'event, ui',
			'activate' => 'event, ui',
			'deactivate' => 'event, ui'
		),
		'drag' => array(
			'start' => 'event, ui',
			'drag' => 'event, ui',
			'stop' => 'event, ui',
		),
		'drop' => array(
			'activate' => 'event, ui',
			'deactivate' => 'event, ui',
			'over' => 'event, ui',
			'out' => 'event, ui',
			'drop' => 'event, ui'
		),
		'request' => array(
			'beforeSend' => 'XMLHttpRequest',
			'error' => 'XMLHttpRequest, textStatus, errorThrown',
			'success' => 'data, textStatus',
			'complete' => 'XMLHttpRequest, textStatus',
			'xhr' => ''
		),
		'dialog' => array(
			'open' => 'event, ui',
			'resize' => 'event, ui',
			'focus' => 'event, ui',
			'drag' => 'event, ui',
			'close' => 'event, ui'
		),
		'validate' => array(
			'invalidHandler' => 'form, validator',
			'submitHandler' => 'form',
			'errorPlacement' => 'error, element',
			'showErrors' => 'errorMap, errorList',
			'highlight' => 'element, errorClass, validClass',
			'unhighlight' => 'element, errorClass, validClass',
			'success' => 'label'
		)
	);

/**
 * The variable name of the jQuery Object, useful
 * when jQuery is put into noConflict() mode.
 *
 * @var string
 */
	public $jQueryObject = '$';
 
/**
 * Constructor
 *
 * ### Settings
 *
 * - `configFile` A file containing an array of tags you wish to redefine.
 *
 * ### Customizing tag sets
 *
 * Using the `configFile` option you can redefine the tag HtmlHelper will use.
 * The file named should be compatible with HtmlHelper::loadConfig().
 *
 * @param View $View The View this helper is being attached to.
 * @param array $settings Configuration settings for the helper.
 */
    public function __construct(View $View, $settings = array()) {

        parent::__construct($View, $settings);
        $this->bufferedMethods = array('event', 'sortable', 'drag', 'drop', 'slider', 'dialog', 'createdialog', 'validate', 'block', 'css', 'attr', 'end');
        
    }    

/**
 * Create javascript selector for a CSS rule
 *
 * @param string $selector The selector that is targeted
 * @param string $type The type of use
 * @return JqueryEngineHelper instance of $this. Allows chained methods.
 */
    public function get($selector) {
        if ($selector == 'window' || $selector == 'document' || $selector == 'data') {
            $this->selection = $this->jQueryObject . '(' . $selector . ')';
        } else {
            $this->selection = $this->jQueryObject . '("' . $selector . '")';
        }
        return $this;
    }

/**
 * passes a block of javascript code to the script cache.
 *
 * @param array $block Javascript code.
 * @return string Block of code.
 */
    public function block($block) {
        return sprintf('%s', $block);
    }

/**
 * Getter and setter for dom manipulation of element attributes.
 * Allows chained methods.
 *
 * @param array $options Options for attr
 * @return string completed attr script.
 */
    public function attr($options = array()) {
        
        if(is_null($this->selection))
            throw new CakeException('missing JqueryEngineHelper::get() method required to start chained method');    
 
        if(!array_key_exists('attributeNames', $options))
            throw new CakeException('options missing attributeNames key is a required parameter');    
        
        if(!(is_string($options['attributeNames']) || is_array($options['attributeNames'])))
            throw new CakeException('options value for attributeNames key must be a string or an array');    
        
        $selection = null;
        if(is_string($options['attributeNames'])) {

            if(array_key_exists('value', $options)) {

                if(!is_string($options['value']))
                    $options['value'] = "";
                
                $selection = sprintf('%s.attr(\'%s\', \'%s\')', $this->selection, $options['attributeNames'], $options['value']);
            } else {
                $selection = sprintf('%s.attr(\'%s\')', $this->selection, $options['attributeNames'] );
            }
            
        } else {
            $selection = sprintf('%s.attr(%s)', $this->selection, $this->object($options['attributeNames']) );
        }

        $this->selection = $selection;

        $chainMethod = (array_key_exists('chain', $options)) ? (boolean) $options['chain'] : false;
        if($chainMethod) {
            return $this;
        }    
        
        return $this->end();
    }    

/**
 * Getter and setter for dom manipulation of style properties.
 * Allows chained methods.
 *
 * @param array $options Options for css
 * @return string completed css script.
 */
    public function css($options = array()) {
        
        if(is_null($this->selection))
            throw new CakeException('missing JqueryEngineHelper::get() method required to start chained method');    

        if(!array_key_exists('propertyNames', $options))
            throw new CakeException('options missing propertyNames key is a required parameter');    
        
        if(!(is_string($options['propertyNames']) || is_array($options['propertyNames'])))
            throw new CakeException('options value for propertyNames key must be a string or an array');
        
        $selection = null;
        if(is_string($options['propertyNames'])) {

            if(array_key_exists('value', $options)) {

                if(!is_string($options['value']))
                    $options['value'] = "";
                
                $selection = sprintf('%s.css(\'%s\', \'%s\')', $this->selection, $options['propertyNames'], $options['value']);
            } else {
                $selection = sprintf('%s.css(\'%s\')', $this->selection, $options['propertyNames'] );        
            }
            
        } else {
            $selection = sprintf('%s.css(%s)', $this->selection, $this->object($options['propertyNames']) );
        }

        $this->selection = $selection;

        $chainMethod = (array_key_exists('chain', $options)) ? (boolean) $options['chain'] : false;
        if($chainMethod) {
            return $this;
        }    
        
        return $this->end();
    }    

/**
 * Allow to stop chaining methods to $selector.
 *
 * @param boolean $display flags to add the end method to the chained method
 * @return string completed end script.
 */
    public function end($display = false) {
         
        if(is_null($this->selection))
            throw new CakeException('missing JqueryEngineHelper::get() method required to start chained method');    
 
        $template = ($display) ? '%s.end();' : '%s;';        
        $selection = sprintf($template, $this->selection);
        $this->selection = null;
        return $selection;
    } 
       
/**
 * passes a block of javascript code to the script cache.
 *
 * @param array $block Javascript code.
 * @return string Block of code.
 */
	public function block($block) {
		return sprintf('%s', $block);
	}

/**
 * Create a Dialog element
 *
 * Requires both Ui.Core and Ui.Dialog to be loaded.
 *
 * @param array $options Array of options for the dialog element.
 * @return string Completed Dialog script.
 * @see JsBaseEngineHelper::dialog() for options list.
 */
	public function dialog($options = array()) {
		$callbacks = array('open', 'resize', 'focus', 'drag', 'close');
		$template = '%s.dialog({%s});';
		return $this->_methodTemplate('dialog', $template, $options, $callbacks);
	}

/**
 * Does form validation
 *
 * Requires both Jquery.Validate and Jquery.Validator to be loaded.
 *
 * @param array $options Array of options for the validate element.
 * @return string Completed Validate script.
 */
	public function validate($options = array()) {
		$callbacks = array('invalidHandler', 'submitHandler', 'errorPlacement', 'showErrors', 'highlight', 'unhighlight');
		$template = '%s.validation({%s});';

		foreach($options['rule'] as $key => $val)
		{

			if(array_key_exists('remote', $val))
				if(array_key_exists('data', $val['remote']))
					$callbacks[] = 'data';

				if(array_key_exists('remote', $val))
					if(array_key_exists('context', $val['remote']))
						$callbacks[] = 'context';

		}

		return $this->_methodTemplate('validate', $template, $options, $callbacks);
	}

/**
 * Add an event to the script cache. Operates on the currently selected elements.
 *
 * ### Options
 *
 * - 'wrap' - Whether you want the callback wrapped in an anonymous function. (defaults true)
 * - 'stop' - Whether you want the event to stopped. (defaults true)
 *
 * @param string $type Type of event to bind to the current dom id
 * @param string $callback The Javascript function you wish to trigger or the function literal
 * @param array $options Options for the event.
 * @return string completed event handler
 */
	public function event($type, $callback, $options = array()) {
		$defaults = array('wrap' => true, 'stop' => true);
		$options = array_merge($defaults, $options);

		$function = 'function (event) {%s}';
		if ($options['wrap'] && $options['stop']) {
			$callback .= "\nreturn false;";
		}
		if ($options['wrap']) {
			$callback = sprintf($function, $callback);
		}
		return sprintf('%s.bind("%s", %s);', $this->selection, $type, $callback);
	}

/**
 * Create a domReady event. For jQuery. This method does not
 * bind a 'traditional event' as `$(document).bind('ready', fn)`
 * Works in an entirely different fashion than  `$(document).ready()`
 * The first will not run the function when eval()'d as part of a response
 * The second will.  Because of the way that ajax pagination is done
 * `$().ready()` is used.
 *
 * @param string $functionBody The code to run on domReady
 * @return string completed domReady method
 */
	public function domReady($functionBody) {
		return $this->jQueryObject . '(document).ready(function () {' . $functionBody . '});';
	}

/**
 * Create an iteration over the current selection result.
 *
 * @param string $callback The function body you wish to apply during the iteration.
 * @return string completed iteration
 */
	public function each($callback) {
		return $this->selection . '.each(function () {' . $callback . '});';
	}

/**
 * Trigger an Effect.
 *
 * @param string $name The name of the effect to trigger.
 * @param array $options Array of options for the effect.
 * @return string completed string with effect.
 * @see JsBaseEngineHelper::effect()
 */
	public function effect($name, $options = array()) {
		$speed = null;
		if (isset($options['speed']) && in_array($options['speed'], array('fast', 'slow'))) {
			$speed = $this->value($options['speed']);
		}
		$effect = '';
		switch ($name) {
			case 'slideIn':
			case 'slideOut':
				$name = ($name == 'slideIn') ? 'slideDown' : 'slideUp';
			case 'hide':
			case 'show':
			case 'fadeIn':
			case 'fadeOut':
			case 'slideDown':
			case 'slideUp':
				$effect = ".$name($speed);";
			break;
		}
		return $this->selection . $effect;
	}

/**
 * Create an $.ajax() call.
 *
 * If the 'update' key is set, success callback will be overridden.
 *
 * @param mixed $url
 * @param array $options See JsHelper::request() for options.
 * @return string The completed ajax call.
 * @see JsBaseEngineHelper::request() for options list.
 */
	public function request($url, $options = array()) {
		$url = $this->url($url);
		$options = $this->_mapOptions('request', $options);

		if (isset($options['data']) && is_array($options['data'])) {
			$options['data'] = $this->_toQuerystring($options['data']);
		}

		$options['url'] = $url;
		if (isset($options['update'])) {
			$wrapCallbacks = isset($options['wrapCallbacks']) ? $options['wrapCallbacks'] : true;
			$success = '';
			if (isset($options['success']) && !empty($options['success'])) {
				$success .= $options['success'];
			}
			$success .= $this->jQueryObject . '("' . $options['update'] . '").html(data);';
			if (!$wrapCallbacks) {
				$success = 'function (data, textStatus) {' . $success . '}';
			}
			$options['statusCode'] = '{}';
			$options['dataType'] = 'html';
			$options['success'] = $success;
			unset($options['update']);
		}
		$callbacks = array('success', 'error', 'beforeSend', 'complete');
		if (!empty($options['dataExpression'])) {
			$callbacks[] = 'data';
			array_remove_item('dataExpression', $options);
		}
		if (!empty($options['contextExpression'])) {
			$callbacks[] = 'context';
			array_remove_item('contextExpression', $options);
		}

		$options = $this->_prepareCallbacks('request', $options);
		$options = $this->_parseOptions($options, $callbacks);

		return $this->jQueryObject . '.ajax({' . $options . '});';
	}

/**
 * Create a sortable element.
 *
 * Requires both Ui.Core and Ui.Sortables to be loaded.
 *
 * @param array $options Array of options for the sortable.
 * @return string Completed sortable script.
 * @see JsBaseEngineHelper::sortable() for options list.
 */
	public function sortable($options = array()) {
		$template = '%s.sortable({%s});';
		return $this->_methodTemplate('sortable', $template, $options);
	}

/**
 * Create a Draggable element
 *
 * Requires both Ui.Core and Ui.Draggable to be loaded.
 *
 * @param array $options Array of options for the draggable element.
 * @return string Completed Draggable script.
 * @see JsBaseEngineHelper::drag() for options list.
 */
	public function drag($options = array()) {
		$template = '%s.draggable({%s});';
		return $this->_methodTemplate('drag', $template, $options);
	}

/**
 * Create a Droppable element
 *
 * Requires both Ui.Core and Ui.Droppable to be loaded.
 *
 * @param array $options Array of options for the droppable element.
 * @return string Completed Droppable script.
 * @see JsBaseEngineHelper::drop() for options list.
 */
	public function drop($options = array()) {
		$template = '%s.droppable({%s});';
		return $this->_methodTemplate('drop', $template, $options);
	}

/**
 * Create a Slider element
 *
 * Requires both Ui.Core and Ui.Slider to be loaded.
 *
 * @param array $options Array of options for the droppable element.
 * @return string Completed Slider script.
 * @see JsBaseEngineHelper::slider() for options list.
 */
	public function slider($options = array()) {
		$callbacks = array('start', 'change', 'slide', 'stop');
		$template = '%s.slider({%s});';
		return $this->_methodTemplate('slider', $template, $options, $callbacks);
	}

/**
 * Serialize a form attached to $selector. If the current selection is not an input or
 * form, errors will be created in the Javascript.
 *
 * @param array $options Options for the serialization
 * @return string completed form serialization script.
 * @see JsBaseEngineHelper::serializeForm() for option list.
 */
	public function serializeForm($options = array()) {
		$options = array_merge(array('isForm' => false, 'inline' => false), $options);
		$selector = $this->selection;
		if (!$options['isForm']) {
			$selector = $this->selection . '.closest("form")';
		}
		$method = '.serialize()';
		if (!$options['inline']) {
			$method .= ';';
		}
		return $selector . $method;
	}
    
/**
 * Helper function to wrap repetitive simple method templating.
 *
 * @param string $method The method name being generated.
 * @param string $template The method template
 * @param array $options Array of options for method
 * @param array $extraSafeKeys Extra safe keys
 * @return string Composed method string
 */
    protected function _methodTemplate($method, $template, $options, $extraSafeKeys = array()) {
        $options = $this->_mapOptions($method, $options);
        $options = $this->_prepareCallbacks($method, $options);
        $callbacks = array_keys($this->_callbackArguments[$method]);
        if (!empty($extraSafeKeys)) {
            $callbacks = array_merge($callbacks, $extraSafeKeys);
        }
        $options = $this->_parseOptions($options, $callbacks);

        return sprintf($template, $this->selection, $options);
    }

}
