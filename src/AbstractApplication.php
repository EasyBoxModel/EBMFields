<?php

namespace EBMApp\Base;

abstract class AbstractApplication
{
    private $sections = [];
    private $fields = [];
    public $isUpdating = false;
    public $isValid = true;
    public $error = [];

    abstract public function addFields(Int $id);

    abstract public function addSections();

    // Sections
    public function addSection(Section $section)
    {
        return array_push($this->sections, $section);
    }

    public function getSectionByFieldAlias(String $alias = null)
    {
        $sections = $this->getSections();
        foreach ($sections as $section) {
            // Get a section where the field belongs
            foreach ($section->getFields() as $field) {
                if ($alias == $field->getFieldAttr('alias')) {
                    return $section;
                }
            }
        }

        return null;
    }

    public function getCurrentSection()
    {
        $sections = $this->getSections();
        foreach ($sections as $section) {
            if (!$section->isComplete()) {
                return $section;
            }
        }

        return null;
    }

    public function getSections(): array
    {
        return $this->sections;
    }

    public function isComplete(): bool
    {
        $sections = $this->getSections();
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
        $field = new Field;
        $field->setModel($model);
        $field->setColumn($alias)
            ->setAlias($alias);

        $this->fields[$alias] = $field;
        
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
                $field = $this->getField($key['alias']);
                $field->setValue($key['value'])->save();
            } catch (\Exception $e) {
                return $this->setError([
                    'ERROR' => 'No hemos podido guardar tus datos, intenta de nuevo.',
                    'VALUE' => $key['id'],
                    'FIELD' => $key['id'],
                ], $e);
            }
        }

        return $this;
    }

    private function setError(Array $error = [], \Exception $e = null)
    {
        if ($e) {
            error_log($e->getMessage());
        }

        $this->isValid = false;
        $this->error = $error;

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
                $field = $this->getField($key['alias']);
                $validators = $field->getValidators();
                foreach ($validators as $validator) {
                    $isInvalid = !$validator->isValid($key['value']);
                    if ($isInvalid) {
                        error_log(current($validator->getMessages()));
                        return $this->setError([
                            'ERROR' => current($validator->getMessages()),
                            'VALUE' => $key['value'],
                            'FIELD' => $key['id'],
                        ]);
                    }
                }
            } catch (\Exception $e) {
                return $this->setError([
                    'ERROR' => 'No hemos podido guardar tus datos, intenta de nuevo.',
                    'VALUE' => $key['value'],
                    'FIELD' => $key['id'],
                ], $e);
            }
        }

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
