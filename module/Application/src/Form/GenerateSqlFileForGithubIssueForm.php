<?php
declare(strict_types=1);

namespace Application\Form;

use Laminas\Form\Element;
use Laminas\Form\Element\Csrf;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\InputFilter\InputFilterPluginManager;
use Psr\Container\ContainerInterface;

final class GenerateSqlFileForGithubIssueForm extends Form
{
    const FORM_NAME = 'generate_sql_file_for_github_issue_form';
    const OBJECTS_ELEMENT_NAME = 'objects';
    const DEV_LIBRARY_ELEMENT_NAME = 'developmentLibrary';
    const SOURCE_FILE_ELEMENT_NAME = 'sourceFile';
    const CSRF_ELEMENT_NAME = self::FORM_NAME . '_csrf';
    private string $developmentLibrary;
    private string $developmentSourcePhysicalFile;


    public function __construct($name, string $developmentLibrary, string $developmentSourcePhysicalFile)
    {
        parent::__construct($name, []);
        $this->developmentLibrary = $developmentLibrary;
        $this->developmentSourcePhysicalFile = $developmentSourcePhysicalFile;
    }


    public static function fromContainer(ContainerInterface $container): self
    {
        $defaultsConfig = $container->get('config')['defaults'];

        $form = new self(
            self::FORM_NAME,
            $defaultsConfig['development_library'],
            $defaultsConfig['development_source_physical_file']
        );
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

        // Objects
        $this->add([
            'name' => self::OBJECTS_ELEMENT_NAME,
            'type' => Textarea::class,
            'attributes' => [
                'id' => self::OBJECTS_ELEMENT_NAME,
                'class' => 'form-control',
            ],
            'options' => [
                'label_attributes' => [
                    'class' => 'col-xs-12 col-sm-2 col-md-3 control-label',
                ],
            ],

        ]);

        // Development Library
        $this->add([
            'name' => self::DEV_LIBRARY_ELEMENT_NAME,
            'type' => Element\Text::class,
        ]);
        $this->get(self::DEV_LIBRARY_ELEMENT_NAME)->setValue($this->developmentLibrary);

        // Dev Library Source File
        $this->add([
            'name' => self::SOURCE_FILE_ELEMENT_NAME,
            'type' => Element\Text::class,
        ]);
        $this->get(self::SOURCE_FILE_ELEMENT_NAME)->setValue($this->developmentSourcePhysicalFile);

        $this->add([
            'name' => 'submit',
            'type' => Element\Submit::class,
        ]);

        $this->add([
            'name' => self::CSRF_ELEMENT_NAME,
            'type' => Csrf::class,
        ]);

        $submitButton = new Element\Submit('submit');
        $submitButton->setValue('Submit');
        $this->add($submitButton);
    }
}