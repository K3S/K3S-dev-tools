<?php

namespace Console\Command;

use Interop\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class PromoteToTesting extends Command
{
    public function __construct($name = null)
    {
        parent::__construct($name);
    }

    public static function fromContainer(ContainerInterface $container): self
    {
        return new self();
    }

    public function configure()
    {
        $this->setName('devtools:promote-to-testing')
            ->setDescription('Promotes source member from develoopment to testing')
            ->setHelp('This command allows you to promote a source member to testing')
            ->addArgument(
                'user',
                InputArgument::REQUIRED,
                'Which developer is running this command?'
            )->addArgument(
                'sourcePath',
                InputArgument::REQUIRED,
                'What is the full path for the source file?'
            )->addArgument(
                'destinationDirectory',
                InputArgument::REQUIRED,
                'What is the path to the testing directory?'
            );
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $sourcePath = $input->getArgument('sourcePath');
        $sourceFile = basename($sourcePath);

        $destinationDirectory = $input->getArgument('destinationDirectory');
        $destinationFile = $destinationDirectory . '/qrpglesrc/' . $sourceFile;

        $command = 'cp ' . $sourcePath . ' ' . $destinationFile;

        system($command);

        $output->writeln("File $sourceFile has been promoted to testing");

        return 1;
    }
}