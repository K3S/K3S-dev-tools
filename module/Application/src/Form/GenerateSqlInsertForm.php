<?php
declare(strict_types=1);

namespace Application\Form;

use Interop\Container\ContainerInterface;
use Laminas\Form\Element\Csrf;
use Laminas\Form\Element\Submit;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\InputFilter\InputFilterPluginManager;

final class GenerateSqlInsertForm extends Form
{
    const FORM_NAME = 'generate_sql_insert_form';
    const CSRF_ELEMENT_NAME = self::FORM_NAME . '_csrf';

    public function __construct($name, array $options = [])
    {
        parent::__construct($name, $options);
    }

    public static function fromContainer(ContainerInterface $container): self
    {
        $form = new self(self::FORM_NAME);
        $inputFilterPluginManager = $container->get(InputFilterPluginManager::class);
        $form->getFormFactory()->getInputFilterFactory()->setInputFilterManager(
            $inputFilterPluginManager
        );

        return $form;
    }

    public function init()
    {
        $this->setAttributes([
            'method' => 'post',
        ]);

        $this->add([
            'name' => 'query',
            'type' => Textarea::class,
            'attributes' => [
                'rows' => '150',
                'cols' => '150',
                'style' => 'font-family: monospace, monospace; white-space: pre-wrap; height: 100vh',
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'type' => Submit::class,
            'attributes' => [
                'value' => 'Generate',
            ],
        ]);

        $this->add([
            'name' => self::CSRF_ELEMENT_NAME,
            'type' => Csrf::class,
        ]);
    }
}