<?php

namespace Application\Form;

use Interop\Container\ContainerInterface;
use Laminas\Form\Element\Csrf;
use Laminas\Form\Element\Submit;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\InputFilterPluginManager;

final class GenerateTableDataGatewayServiceProgramForm extends Form
{
    const FORM_NAME = 'generate_table_data_gateway_service_program_form';
    const LIBRARY_ELEMENT_NAME = 'library';
    const TABLE_ELEMENT_NAME = 'element';
    const CSRF_ELEMENT_NAME = self::FORM_NAME . '_csrf';

    public function __construct($name = null, array $options = [])
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
            'name' => self::LIBRARY_ELEMENT_NAME,
            'type' => Text::class,
            'attributes' => [
                'id' => self::LIBRARY_ELEMENT_NAME,
                'class' => 'form-control',
            ],
            'options' => [
                'label_attributes' => [
                    'class' => 'col-xs-12 col-sm-2 col-md-3 control-label',
                ],
            ],
        ]);

        $this->add([
            'name' => self::TABLE_ELEMENT_NAME,
            'type' => Text::class,
            'attributes' => [
                'id' => self::TABLE_ELEMENT_NAME,
                'class' => 'form-control',
            ],
            'options' => [
                'label_attributes' => [
                    'class' => 'col-xs-12 col-sm-2 col-md-3 control-label',
                ],
            ],
        ]);

        $this->add([
            'name' => self::CSRF_ELEMENT_NAME,
            'type' => Csrf::class,
        ]);

        $submitButton = new Submit('submit');
        $submitButton->setValue('Submit');
        $this->add($submitButton);
    }
}