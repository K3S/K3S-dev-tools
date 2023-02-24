<?php

namespace Console\Command;

use Interop\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ToolkitApi\Toolkit;
use ToolkitApi\ToolkitInterface;

final class PromoteToTestingAndCompile extends Command
{
    private ToolkitInterface $toolkit;

    /**
     * @param ToolkitInterface $toolkit
     * @param string|null $name
     */
    public function __construct(ToolkitInterface $toolkit, string $name = null)
    {
        $this->toolkit = $toolkit;
        parent::__construct($name);
    }

    /**
     * @param ContainerInterface $container
     * @return static
     */
    public static function fromContainer(ContainerInterface $container): self
    {
        return new self(
            $container->get(Toolkit::class)
        );
    }

    public function configure()
    {
        $this->setName('devtools:promote-to-testing-and-compile')
            ->setDescription('Promotes source member from development to testing and compiles to ITE')
            ->setHelp('This command allows you to promote a file to the testing environment and compile it in one step')
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
        // Set appropriate library list
        $this->toolkit->ClCommand('CHGLIBL LIBL(QGPL QTEMP WEB_5TST WEB_5TDV WEB_5MOD WEB_5DTA WEB_5QRY WEB_5OBJ WEB_5DEV WEB_5SRC WEB_5WEB)');

        $promoteCommand = $this->getApplication()->find('devtools:promote-to-testing');
        $returnCode = $promoteCommand->run(new ArrayInput([
            'user' => $input->getArgument('user'),
            'sourcePath' => $input->getArgument('sourcePath'),
            'destinationDirectory' => $input->getArgument('destinationDirectory'),
        ]), $output);

        //$iteDirectory = '/usr/local/k3s/utilities/k3s-replenish-apis/qrpglesrc';

        $path_parts = pathinfo($input->getArgument('sourcePath'));
        $sourceFileName = $input->getArgument('destinationDirectory') . '/qrpglesrc/' . $path_parts['filename'];

//        $command = "CRTSQLRPGI OBJ(WEB_5TST/" . $path_parts['filename'] . ") SRCSTMF('$sourceFileName" . "." . $path_parts['extension'] . "') CLOSQLCSR(*ENDMOD) OPTION(*EVENTF) DBGVIEW(*SOURCE) TGTRLS(*CURRENT)";
        $command = "CRTSQLRPGI OBJ(ACS_5OBJ/" . $path_parts['filename'] . ") SRCSTMF('$sourceFileName" . "." . $path_parts['extension'] . "') COMMIT(*NONE) OPTION(*EVENTF *XREF *SEQSRC) TGTRLS(V7R3M0) CLOSQLCSR(*ENDMOD) DBGVIEW(*SOURCE) COMPILEOPT('TGTCCSID(*JOB)')";

//        $rows = $this->toolkit->CLInteractiveCommand($command);
//        if (!$rows) {
//            $output->writeln($this->toolkit->getLastError());
//        } else {
//            $output->writeln($rows);
//        }




        $output->writeln($command);
        $clOutput = $this->toolkit->ClCommandWithOutput($command);
        $output->writeln($clOutput);




        return 1;
    }
}