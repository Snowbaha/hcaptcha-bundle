HCaptcha bundle for Symfony 4+
============

Basically, this bundle brings into your Symfony website a new Form type, namely
HCaptchaType, that is used to display and validate a CAPTCHA served by
https://www.hcaptcha.com.


Installation
----------


### Applications that use Symfony Flex

Open a command console, enter your project directory and execute:

```console
$ composer require meteo-concept/hcaptcha-bundle
```

### Applications that don't use Symfony Flex

#### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require meteo-concept/hcaptcha-bundle
```

#### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    MeteoConcept\HCaptchaBundle\MeteoConceptHCaptchaBundle::class => ['all' => true],
];
```

Configuration
------

Configure the bundle, for instance in `config/packages/meteo_concept_hcaptcha.yml`:

```yaml
parameters:
    hcaptcha_site_key: '%env(resolve:HCAPTCHA_SITE_KEY)%'
    hcaptcha_secret: '%env(resolve:HCAPTCHA_SECRET)%'

meteo_concept_h_captcha:
  hcaptcha:
    site_key: '%hcaptcha_site_key%'
    secret: '%hcaptcha_secret%'
```

with the corresponding change in `.env`:

```ini
HCAPTCHA_SITE_KEY="10000000-ffff-ffff-ffff-000000000001"
HCAPTACHA_SECRET="0x0000000000000000000000000000000000000000"
```

The site key and secret are the values hCaptcha gives you at https://dashboard.hcaptcha.com. The global configuration makes all captchas use the same site key by default but it's possible to change it in the definition of each form.
The values shown here are dummy values usable for integration testing (https://docs.hcaptcha.com/#integrationtest). Put the real values in `.env.local` (at least, the secret, the site key is public).

Usage
------

Configure Twig to load the specific template for the hCaptcha widget (or provide your own).

```yaml
twig:
    ...
    form_themes:
        - '@MeteoConceptHCaptcha/hcaptcha_form.html.twig'
        - ...
```

Step 5
------

Use the captcha in your forms:

```php
<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use MeteoConcept\HCaptchaBundle\Form\HCaptchaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
            ])
            ->add('email', TextType::class, [
                'label' => 'Email',
            ])
            ->add('message', TextareaType::class, [
                'label' => 'How can we help you ?',
            ])
            ->add('captcha', HCaptchaType::class, [
                'label' => 'Anti-bot test',
                // optionally: use a different site key than the default one:
                'hcaptcha_site_key' => '10000000-ffff-ffff-ffff-000000000001',
            ])
        ;
    }
}
```

By default, the HCaptchaFormType class validates the field againt constraints `NotBlank` and `IsValidCaptcha` (a new constraint installed with this bundle whose validator makes the CAPTCHA check by calling the hCaptcha API). You can override this set of constraints by passing the `constraints` option to the form builder. Also, HCaptchaFormType fields are passed `'mapped' => false` by default since it doesn't make much sense to persist CAPTCHA values.
