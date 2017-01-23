<?php

namespace EBMQ\Base;

class Application
{
    private $sections = [];
    private $fields = [];
    public $isUpdating = false;
    public $isValid = false;
    public $error = [];

    public function __construct(){}

    // Sections
    public function addSection(Section $section)
    {
        return array_push($this->sections, $section);
    }

    public function getSectionByField(String $alias = null)
    {
        foreach ($this->sections as $section) {
            // Get a section where the field belongs
            if ($alias != null) {
                foreach ($section->getFields() as $field) {
                    $field->isUpdating = true;
                    if ($alias == $field->getFieldAttr('alias')) {
                        $section->isUpdating = true;
                        return $section;
                    }
                }
            }
        }
    }

    public function getCurrentSection()
    {
        foreach ($this->sections as $section) {
            if (!$section->isComplete()) {
                return $section;
            }
        }

        return null;
    }

    public function getSections(): array
    {
        return array_filter($this->sections, function($section){
            return $section->isVisible();
        });
    }

    public function isComplete(): bool
    {
        $sections = $this->sections;
        foreach ($sections as $section) {
            if (!$section->isComplete()) {
                return false;
            }
        }

        return true;
    }

    // Fields

    /**
    * @description Feeds the $fields array with Field instances
    * @param The alias of the field, this is required because e.g. ZIP_CODE may be repeated in certain sections
    * @param An eloquent model where the field is going to be saved
    * @return Field::class, allows to concat its methods in the Application definition
    */
    public function addField(String $alias, $model)
    {
        $field = new Field($model);

        $this->fields[$alias] = $field;

        // Assign default column name
        $field->setColumn($alias)
            ->setAlias($alias);

        return $field;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function getField(String $alias): Field
    {
        return $this->fields[$alias];
    }

    // Is updating the application fields values
    public function isUpdating(): bool
    {
        return $this->isUpdating;
    }

    /**
    * @description Loops through each field config array after validation->isValid() and uses each Field save method
    * @param Array $data = Field::getFieldConfig()
    * @return AbstractApplication
    */
    public function save(Array $data = [])
    {
        foreach ($data as $key) {
            try {
                $field = $this->fields[$key['alias']];
                if ($this->isUpdating()) {
                    $field->isUpdating = true;
                }
                $field->setValue($key['value'])->save();
            } catch (\Exception $e) {
                error_log($e->getMessage());
                $this->isValid = false;
                $this->error = [
                    'ERROR' => 'No hemos podido guardar tus datos, intenta de nuevo.',
                    'VALUE' => $key['id'],
                    'FIELD' => $key['id'],
                ];
                return $this;
            }
        }

        $this->isValid = true;
        $this->error = [];
        return $this;
    }

    /**
    * @description Loops through each field config array after validation->isValid() and
    * @description uses each Field validators defined in the Application objects Field::addValidators(ZendValidator)
    * @param Array $data = Field::getFieldConfig()
    * @return AbstractApplication
    */
    public function validate(Array $data = [])
    {
        foreach ($data as $key) {
            try {
                $field = $this->fields[$key['alias']];
                foreach ($field->getValidators() as $validator) {
                    $isInvalid = !$validator->isValid($key['value']);
                    if ($isInvalid) {
                        error_log(current($validator->getMessages()));
                        $this->isValid = false;
                        $this->error = [
                            'ERROR' => current($validator->getMessages()),
                            'VALUE' => $key['value'],
                            'FIELD' => $key['id'],
                        ];
                        return $this;
                    }
                }
            } catch (\Exception $e) {
                error_log($e->getMessage());
                $this->isValid = false;
                $this->error = [
                    'ERROR' => 'No hemos podido guardar tus datos, intenta de nuevo.',
                    'VALUE' => $key['value'],
                    'FIELD' => $key['id'],
                ];
                return $this;
            }
        }

        $this->isValid = true;
        $this->error = [];
        return $this;
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function getError(): array
    {
        return $this->error;
    }
}
