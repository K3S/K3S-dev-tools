<?php
namespace Application\Validator;

use Interop\Container\ContainerInterface;
use Laminas\Validator\AbstractValidator;

final class SourceMemberExistsValidator extends AbstractValidator
{
    const SOURCE_MEMBER_NOT_FOUND = 'userNameNotFound';

    protected $messageTemplates = [
        self::SOURCE_MEMBER_NOT_FOUND => 'This source member does not exist',
    ];



    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    /**
     * @param ContainerInterface $container
     * @return static
     */
    public static function fromContainer(ContainerInterface $container): self
    {
        return new self();
    }

    /**
     * @param array $value
     * @return bool|void
     */
    public function isValid($value)
    {
        // $value should be an array with three keys: library, sourcePhysicalFile, and sourceMember
        if (!array_key_exists('library', $value)) {
            throw new \Exception('Array must contain a library key');
        }

        if (!array_key_exists('sourcePhysicalFile', $value)) {
            throw new \Exception('Array must contain a sourcePhysicalFile key');
        }

        if (!array_key_exists('sourceMember', $value)) {
            throw new \Exception('Array must contain a sourceMember key');
        }

        $value['library'] = pathinfo($value['library'], PATHINFO_FILENAME);
        $value['sourcePhysicalFile'] = pathinfo($value['sourcePhysicalFile'], PATHINFO_FILENAME);
        $value['sourceMember'] = pathinfo($value['sourceMember'], PATHINFO_FILENAME);

        $file = '/QSYS.LIB/' . $value['library'] . '.LIB/' . $value['sourcePhysicalFile'] . '.FILE/' . $value['sourceMember'] . '.MBR';
        // /QSYS.LIB/ACS_5DEV.LIB/QRPGLESRC.FILE/FILENAME.MBR

        if (!file_exists($file)) {
            $this->error(self::SOURCE_MEMBER_NOT_FOUND);
            return false;
        }

        return true;
    }
}