<?php
/* Copyright (c) 1998-2016 ILIAS open source, Extended GPL, see docs/LICENSE */

/** @noinspection PhpIncludeInspection */
require_once './Services/WorkflowEngine/interfaces/ilActivity.php';
/** @noinspection PhpIncludeInspection */
require_once './Services/WorkflowEngine/interfaces/ilNode.php';

/**
 * Class ilStaticMethodCallActivity
 *
 * This activity calls a given static method with a reference to itself as
 * and a given array as parameters.
 *
 * @author Maximilian Becker <mbecker@databay.de>
 * @version $Id$
 *
 * @ingroup Services/WorkflowEngine
 */
class ilStaticMethodCallActivity implements ilActivity, ilWorkflowEngineElement
{
    /** @var ilWorkflowEngineElement $context Holds a reference to the parent object. */
    private $context;

    /** @var string $include_file Filename and path of the class to be loaded. */
    private string $include_file = '';

    /**
     * Holds the value of the method name to be called.
     * E.g. ilHaumichblau::BuyAPony -> no parentheses.
     *
     * @var string $class_and_method_name Class::Method without parentheses.
     */
    private string $class_and_method_name = '';

    /** @var array $parameters Holds an array with params to be passed as second argument to the method. */
    private array $parameters;

    /** @var array $outputs Holds a list of valid output element IDs passed as third argument to the method. */
    private array $outputs = [];

    /** @var string $name */
    protected string $name;

    /**
     * Default constructor.
     *
     * @param ilNode $context
     */
    public function __construct(ilNode $context)
    {
        $this->context = $context;
    }

    /**
     * Sets the name of the file to be included prior to calling the method..
     * @param string $filename Name of the file to be included.
     * @return void
     *@see $include_file
     */
    public function setIncludeFilename(string $filename) : void
    {
        $this->include_file = $filename;
    }

    /**
     * Returns the currently set filename of the classfile to be included.
     *
     * @return string
     */
    public function getIncludeFilename() : string
    {
        return $this->include_file;
    }

    /***
     * Sets the class- and methodname of the method to be called.
     * E.g. ilPonyStable::getPony
     * @param string $name Classname::Methodname.
     * @return void
     *@see $method_name
     */
    public function setClassAndMethodName(string $name) : void
    {
        $this->class_and_method_name = $name;
    }

    /**
     * Returns the currently set class- and methodname of the method to be called.
     *
     * @return string
     */
    public function getClassAndMethodName() : string
    {
        return $this->class_and_method_name;
    }

    /**
     * Sets an array with params for the method. This will be set as second
     * parameter.
     * @param array $params Array with parameters.
     * @return void
     */
    public function setParameters(array $params) : void
    {
        $this->parameters = $params;
    }

    /**
     * Returns the currently set parameters to be passed to the method.
     *
     * @return array
     */
    public function getParameters() : array
    {
        return $this->parameters;
    }

    /**
     * @return array
     */
    public function getOutputs() : array
    {
        return $this->outputs;
    }

    /**
     * @param array $outputs
     */
    public function setOutputs(array $outputs) : void
    {
        $this->outputs = $outputs;
    }

    /**
     * Executes this action according to its settings.
     * @return void
     *@todo Use exceptions / internal logging.
     */
    public function execute():void
    {
        /** @noinspection PhpIncludeInspection */
        require_once './' . $this->include_file;
        $name = explode('::', $this->class_and_method_name);

        $list = (array) $this->context->getContext()->getInstanceVars();
        $params = array();
        foreach ($this->parameters as $key => $parameter) {
            $set = false;
            foreach ($list as $instance_var) {
                if ($instance_var['id'] == $parameter) {
                    $set = true;
                    $role = $instance_var['role'];
                    if ($instance_var['reference'] == true) {
                        foreach ($list as $definitions) {
                            if ($definitions['id'] == $instance_var['target']) {
                                $role = $definitions['role'];
                            }
                        }
                    }
                    $params[$role] = $this->context->getContext()->getInstanceVarById($parameter);
                }
            }
            if (!$set) {
                $params[$parameter] = $parameter;
                $params[$key] = $parameter;
            }
        }

        /** @var array $return_value */
        $return_value = call_user_func_array(
            array($name[0], $name[1]),
            array($this, array($params, $this->outputs))
        );
        foreach ((array) $return_value as $key => $value) {
            $this->context->getContext()->setInstanceVarById($key, $value);
        }
    }

    /**
     * Returns a reference to the parent node.
     *
     * @return ilNode Reference to the parent node.
     */
    public function getContext()
    {
        return $this->context;
    }

    public function setName($name) : void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }
}
