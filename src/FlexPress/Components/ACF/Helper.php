<?php

namespace FlexPress\Components\ACF;

class Helper
{

    /**
     * @var \SplObjectStorage
     */
    protected $fieldGroups;

    /**
     * @var \SplObjectStorage
     */
    protected $fields;

    public function __construct(
        \SplObjectStorage $fieldGroups,
        \SplObjectStorage $fields,
        array $fieldGroupsArray = array(),
        array $fieldsArray = array()
    ) {

        $this->fields = $fields;
        $this->fieldGroups = $fieldGroups;

        if (!empty($fieldsArray)) {

            foreach ($fieldsArray as $field) {

                if (!$field instanceof AbstractFieldProxy) {

                    $message = "One or more of the fields you have passed to ";
                    $message .= get_class($this);
                    $message .= " does not extend the FieldProxy class.";

                    throw new \RuntimeException($message);

                }

                $this->fields->attach($field);

            }

        }

        if (!empty($fieldGroupsArray)) {

            foreach ($fieldGroupsArray as $fieldGroup) {

                if (!$fieldGroup instanceof AbstractFieldGroup) {

                    $message = "One or more of the fields you have passed to ";
                    $message .= get_class($this);
                    $message .= " does not extend the FieldGroup class.";

                    throw new \RuntimeException($message);

                }

                $this->fieldGroups->attach($fieldGroup);

            }

        }

    }

    /**
     * Registers all the field groups added
     *
     * @author Tim Perry
     */
    public function registerFieldGroups()
    {

        if (!function_exists('register_field_group')) {
            return;
        }

        $this->fieldGroups->rewind();
        while ($this->fieldGroups->valid()) {

            $field = $this->fieldGroups->current();
            register_field_group($field->getConfig());
            $this->fieldGroups->next();

        }

    }
}
