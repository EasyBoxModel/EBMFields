# EBM Fields

The Easy Box Model Fields package helps you build forms connected to your PHP backend __faster__.

## Usage

Set up an application made out of fields and sections associated with a DB model:

```php
class UserApplication extends AbstractApplication
{
    public function __construct(Int $id)
    {
        $this->addFields($id);
        $this->addSections();
    }

    public function addFields(Int $id)
    {
        $user = User::find($id);

        $this->addField('username', $user)
            ->setValue();

        // You can set a specific save strategy for a field
        $this->addField('email', $user)
            ->setSaveStrategy(array(UserStrategy::class, 'resetEmailVerificationDate'))
            ->setValue();

        // You can set a select, checkbox or radio field options with your preferred key/value structure
        $this->addField('place_of_birth_id', $user)
            ->setValue()
            ->setOptions(Option::getLuPlaceOfBirth());
    }

    public function addSections()
    {
        $username = $this->getField('username');
        $username->setLabel('Username');

        $email = $this->getField('email');
        $email->setLabel('Email');

        $this->addSection(new SectionOne([
            $username,
            $email,
            ], $this));

        $place_of_birth_id = $this->getField('place_of_birth_id');
        $place_of_birth_id->setLabel('Please select your place of birth');

        $this->addSection(new SectionTwo([
            $place_of_birth_id,
            ], $this));
    }
}
```

Build a section made out of fields

```html
<h1><?= $section->getName() ?></h1>

<form method="POST">
    <fieldset class="form-group">
        <?= $section->getField('username') ?>
    </fieldset>

    <fieldset class="form-group">
        <?= $section->getField('email') ?>
    </fieldset>
</form>
```

Use the section fields in a HTML template or component:

```html
<my-template v-ref:myTemplate></my-template>

<template name="my-template">
    <div>
        <label for="<?= $field->getFieldAttr('id') ?>"><?= $field->getFieldAttr('label') ?></label>
        <input type="text" 
            name="<?= $field->getFieldAttr('id') ?>" 
            value="<?= $field->getFieldAttr('value') ?>"
            placeholder="<?= $field->getFieldAttr('placeholder') ?>">
            v-model="fieldConfig.value"
    </div>
</template>

<script>
    let Field = Vue.extend({
        template: '#my-template',
        data(){
            return {
                fieldConfig: <?= $field->getFieldConfig() ?>
            }
        }
    });
</script>
```

Catch the field data, validate and save

```php
class MyController
{
    public function save(Request $request)
    {
        $data = (array) $request->getPost();
        $alias = end($data)['alias'];

        $application = new MyApplication;
        $section = $application->getSectionByFieldAlias($alias);

        $validation = $section->validate($data);
        if (!$validation->isValid()) {
            return $error;
        }

        $storage = $section->save($data);
        if (!$storage->isValid()) {
            return $error;
        }
    }
}
```

## Testing

- Run a `composer install` command
- Run a `phpunit tests` command