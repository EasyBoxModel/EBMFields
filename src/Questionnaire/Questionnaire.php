<?php

namespace EBMQ\Questionnaire;

use EBMQ\Field\Field;

class Questionnaire
{
    private $formFields = [];
    public $isUpdating = false;
    public $isValid = false;
    public $error = [];

    /**
    * @description Feeds the $formFields array with Field instances
    * @param The alias of the field, this is required because e.g. ZIP_CODE may be repeated in certain sections
    * @param An eloquent model where the field is going to be saved
    * @return Field::class, allows to concat its methods in the Application definition
    */
    public function addField(String $alias, $model)
    {
        $field = new Field($model);

        $this->formFields[$alias] = $field;

        // Assign default column name
        $field->setColumn($alias)
            ->setAlias($alias);

        return $field;
    }

    public function getFields(): array
    {
        return $this->formFields;
    }

    public function getField(String $alias): Field
    {
        return $this->formFields[$alias];
    }

    public function isUpdating(): bool
    {
        return $this->isUpdating;
    }

    /**
    * @description Loops through each field config array after validation->isValid() and uses each Field save method
    * @param Array $data = Field::getFieldConfig()
    * @return AbstractQuestionnaire
    */
    public function save(Array $data = [])
    {
        foreach ($data as $key) {
            try {
                $field = $this->formFields[$key['alias']];
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
    * @return AbstractQuestionnaire
    */
    public function validate(Array $data = [])
    {
        foreach ($data as $key) {
            try {
                $field = $this->formFields[$key['alias']];
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
