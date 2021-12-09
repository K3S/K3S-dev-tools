<?php
declare(strict_types=1);

namespace Application\Controller;

use Application\Form\GenerateSqlFileForGithubIssueForm;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Form\FormElementManager;
use Laminas\Form\FormInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Psr\Container\ContainerInterface;

final class GenerateSqlFileForGithubIssueController extends AbstractActionController
{
    /**
     * @var AdapterInterface|Adapter
     */
    private AdapterInterface $adapter;

    /**
     * @var FormInterface
     */
    private FormInterface $form;

    /**
     * GenerateSqlFileForGithubIssueController constructor.
     * @param AdapterInterface $adapter
     * @param FormInterface $form
     */
    public function __construct(
        AdapterInterface $adapter,
        FormInterface $form
    ) {
        $this->adapter = $adapter;
        $this->form = $form;
    }

    public static function fromContainer(ContainerInterface $container): self
    {
        return new self(
            $container->get(Adapter::class),
            $container->get(FormElementManager::class)->get(GenerateSqlFileForGithubIssueForm::class)
        );
    }

    public function indexAction()
    {
        // @todo: make dev library destination an input parameter

        $viewModel = new ViewModel();
        $viewModel->setTemplate('application/generate-sql-file-for-github-issue.phtml');
        $viewModel->setVariable('form', $this->form);
        $output = '';
        if ($this->getRequest()->isPost()) {

            $input = $this->params()->fromPost();

            $this->form->setData($input);
            if (!$this->form->isValid()) {
                $viewModel->setVariable('form', $this->form);
                return $viewModel;
            }

            $output = $this->generateOutput(
                $this->formatInput(
                    $this->form->getData()
                )
            );
        }

        $viewModel->setVariable('output', $output);

        return $viewModel;
    }

    /**
     * @param array $input
     * @return array
     */
    private function formatInput(array $input): array
    {
        $objects = explode("\n", str_replace("\r", "", $input['objects']));

        $formattedInput = [
            'developmentLibrary' => $input['developmentLibrary'],
        ];
        foreach ($objects as $object) {
            $info = $this->getObjectInfo($object);
            $formattedInput['objects'][] = [
                'name' => $object,
                'baseName' => str_replace('AR_', '', $object),
                'type' => $info['type'],
                'text' => $info['text'],
            ];
        }

        return $formattedInput;
    }

    private function generateOutput(array $input): string
    {
        $output = $this->generateSetUpSection($input);
        $objects = $input['objects'];

        // Compile commands

        // Dev environment
        $output .= '<br><br>--------------------------------------------------------------------------------<br>';
        $output .= '-- In Development<br>';
        $output .= '--------------------------------------------------------------------------------<br><br>';

        $output .= '-- Service Program(s)<br>';
        $output .= '-- API Program(s)<br>';

        foreach($objects as $object) {
            $output .= sprintf('-- %s', $object['name'] . '<br>');
            $output .= sprintf('CL: CRTSQLRPGI OBJ(ACS_5OBJ/%s) SRCFILE(%s/QRPGLESRC) COMMIT(*NONE) OPTION(*EVENTF *XREF) DBGVIEW(*SOURCE) TGTRLS(V7R2M0) TEXT(\'%s\');<br><br>', $object['name'], $input['developmentLibrary'], $object['text']);
        }

        // ITE environment
        $output .= '<br><br>--------------------------------------------------------------------------------<br>';
        $output .= '-- Integrated Testing Environment<br>';
        $output .= '--------------------------------------------------------------------------------<br><br>';

        $output .= '-- Service Program(s)<br>';
        foreach ($objects as $object) {
            if ($object['type'] !== '*SRVPGM') {
                continue;
            }

            $output .= sprintf('-- %s', $object['name']) . '<br>';
            $output .= sprintf('CL: RMVBNDDIRE BNDDIR(K3SDIR) OBJ(%s);', $object['name']) . '<br>';
            $output .= sprintf('CL: DLTOBJ OBJ(WEB_5TST/%s) OBJTYPE(*SRVPGM);', $object['name']) . '<br>';
            $output .= sprintf('CL: DLTOBJ OBJ(WEB_5TST/%s) OBJTYPE(*MODULE);', $object['name']) . '<br>';
            $output .= sprintf('CL: CPYSRCF FROMFILE(%s/QRPGLESRC) TOFILE(WEB_5TDV/QRPGLESRC) FROMMBR(%s) TOMBR(%s);', $input['developmentLibrary'], $object['name'], $object['name']) . '<br>';
            $output .= sprintf('CL: CPYSRCF FROMFILE(%s/QRPGLESRC) TOFILE(WEB_5TDV/QRPGLESRC) FROMMBR(%s_H) TOMBR(%s_H);<br>', $input['developmentLibrary'], $object['baseName'], $object['baseName']);
            $output .= sprintf('CL: CRTSQLRPGI OBJ(WEB_5TST/%s) SRCFILE(WEB_5TDV/QRPGLESRC) OBJTYPE(*MODULE) DBGVIEW(*SOURCE) TGTRLS(V7R2M0);<br>', $object['name']);
            $output .= sprintf('CL: CPYSRCF FROMFILE(ACS_5DEV/QSRVSRC) TOFILE(WEB_5TDV/QSRVSRC) FROMMBR(<srvpgm_name>_B) TOMBR(<srvpgm_name>_B);<br>');
            $output .= sprintf('CL: CRTSRVPGM SRVPGM(WEB_5TST/AR_<srvpgm_name>) MODULE(WEB_5TST/AR_<srvpgm_name>) SRCFILE(WEB_5TDV/QSRVSRC) SRCMBR(<srvpgm_name>_B) BNDDIR(K3SDIR) TEXT(\'<srvpgm_text>\');<br>');
            $output .= sprintf('CL: ADDBNDDIRE BNDDIR(K3SDIR) OBJ((<srvpgm_name>));<br>');
        }


        return $output;
    }

    private function generateSetUpSection(array $input)
    {
        $objects = $input['objects'];

        $output = '';
        $output .= '--------------------------------------------------------------------------------<br>';
        $output .= '-- Set Up<br>';
        $output .= '--------------------------------------------------------------------------------<br>';

        foreach ($objects as $object) {
            $dependencies = $this->getObjectDependencies($object['name']);

            $output .= '------------------------------------------<br>';
            $output .= '-- Dependencies <br>';
            $output .= '------------------------------------------<br>';
            foreach ($dependencies as $dependency) {
                $output .= sprintf('CL: CPYSRCF FROMFILE(%s/QRPGLESRC) TOFILE(ACS_5DEV/QRPGLESRC) FROMMBR(%s) TOMBR(%s);<br>', $dependency['library'], $dependency['name'], $dependency['name']);
            }

            $output .= '<br>------------------------------------------<br>';
            $output .= '-- Source member <br>';
            $output .= '------------------------------------------<br>';
            $output .= sprintf('CL: CPYSRCF FROMFILE(WEB_5DEV/QRPGLESRC) TOFILE(ACS_5DEV/QRPGLESRC) FROMMBR(%s) TOMBR(%s);<br>', $object['name'], $object['name']);
        }

        return $output;
    }

    private function getObjectInfo(string $object): array
    {
        $sql = new Sql($this->adapter);
        $select = $sql->select(
            new TableIdentifier('PROGRAM_INFO', 'QSYS2')
        );
        $select->columns(['OBJECT_TYPE', 'TEXT_DESCRIPTION']);
        $select->where([
            'PROGRAM_NAME' => $object,
            'PROGRAM_LIBRARY' => 'WEB_5OBJ',
        ]);

        $statement = $sql->prepareStatementForSqlObject($select);
        /** @var Result $results */
        $results = $statement->execute();
        $result = $results->current();

        return [
            'type' => $result['OBJECT_TYPE'],
            'text' => $result['TEXT_DESCRIPTION'],
        ];
    }

    /**
     * @param string $object
     * @return array
     */
    private function getObjectDependencies(string $object): array
    {
        $dependencies = [];
        $sql = "SELECT * FROM TABLE(QSYS2.IFS_READ(PATH_NAME => '/QSYS.LIB/WEB_5DEV.LIB/QRPGLESRC.FILE/$object.MBR',
                                   END_OF_LINE => 'CRLF')) where ucase(line) like '%/COPY%'";
        $results = $this->adapter->query($sql)->execute();

        foreach ($results as $result) {

            $parsedLine = explode('/copy', $result['LINE']);
            if (count($parsedLine) === 1) {
                $parsedLine = explode('/COPY', $result['LINE']);
            }

            $memberName = strtoupper(trim($parsedLine[1]));

            $dependencies[] = [
                'name' => $memberName,
                'library' => (str_contains($memberName, 'K3S_')) ? 'K3S_5SRC' : 'WEB_5DEV',
            ];
        }

        return $dependencies;
    }
}