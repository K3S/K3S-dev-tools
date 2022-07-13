<?php

namespace Application\Form;

use Interop\Container\ContainerInterface;
use Laminas\Form\Element\Csrf;
use Laminas\Form\Element\Submit;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;

final class GenerateQueryResultDataStructureDefinitionFromQueryForm extends Form
{
    const FORM_NAME = 'generate_query_result_data_structure_definition_from_query_form';
    const QUERY_ELEMENT_NAME = 'query';
    const CSRF_ELEMENT_NAME = self::FORM_NAME . '_csrf';

    public function __construct($name = null, array $options = [])
    {
        parent::__construct($name, []);
    }

    public static function fromContainer(ContainerInterface $container): self
    {
        return new self(self::FORM_NAME);
    }

    public function init()
    {
        $this->setAttributes([
            'method' => 'post',
        ]);

        $this->add([
            'name' => self::QUERY_ELEMENT_NAME,
            'type' => Textarea::class,
            'attributes' => [
                'id' => self::QUERY_ELEMENT_NAME,
                'class' => 'form-control',
            ],
            'options' => [
                'label_attributes' => [
                    'class' => 'col-xs-12 col-sm-2 col-md-3 control-label',
                ]
            ]
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