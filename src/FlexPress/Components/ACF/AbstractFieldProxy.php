<?php

namespace FlexPress\Components\ACF;

abstract class AbstractFieldProxy {

    /**
     * @var \Pimple
     */
    protected $dic;

    /**
     * @param \Pimple $dic
     */
    public function __construct(\Pimple $dic)
    {
        $this->dic = $dic;
        add_action('acf/register_fields', array($this, 'registerField'));
    }

    /**
     *
     * Creates the instance of the field, causing
     * the field to register itself
     *
     * @author Tim Perry
     *
     */
    public function registerField()
    {
        $this->dic[$this->getDICName()];
    }

    /**
     *
     * Returns the string name for the field in your dic
     * this is the string value you have stored the field
     * under in the dic
     *
     * @return mixed
     * @author Tim Perry
     *
     */
    abstract protected function getDICName();


} 