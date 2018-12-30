<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-03-17
 * Time: 11:26
 */

namespace Qin\Console\Controller;

use Inhere\Console\Controller;
use Inhere\Console\Utils\Helper;
use Qin\Component\SimpleTemplate;
use Toolkit\Cli\Highlighter;
use Inhere\Validate\Validation;
use Inhere\Console\IO\Input;
use Inhere\Console\IO\Output;
use Toolkit\Sys\Sys;

/**
 * Class GeneratorController
 * @package Qin\Console\Controllers
 *
 */
class GenController extends Controller
{
    // group name
    protected static $name = 'gen';

    protected static $description = 'Generate some common application template classes[<cyan>built-in</cyan>]';

    /** @var array Some template vars. */
    private $tplVars = [];

    /** @var string Default template path. */
    private $defaultTplPath;

    /** @var callable */
    protected $pathResolver;

    protected function init()
    {
        $this->defaultTplPath = \alias('@mco') . '/res/templates';
        $this->pathResolver = 'alias';
    }

    public static function commandAliases(): array
    {
        return [
            'ac' => 'alone',
            'gc' => 'group',
            'cmd' => 'alone',
            'command' => 'alone',
            'c' => 'controller',
            'ctrl' => 'controller',
            'm' => 'model',
            'l' => 'logic',
            'lgc' => 'logic',
        ];
    }

    /**
     * Generate console command class
     * @usage {fullCommand} NAME SAVE_DIR [--option ...]
     * @arguments
     *  name       The class name, don't need suffix and ext.(eg. <info>demo</info>)
     *  dir        The class file save dir(default: <info>@app/Console/Command</info>)
     * @options
     *  -y, --yes BOOL             No need to confirm when performing file writing. default is: <info>False</info>
     *  -o, --override BOOL        Force override exists file. default is: <info>False</info>
     *  -n, --namespace STRING     The class namespace. default is: <info>App\Console\Command</info>
     *  --vars STRING              Add some custom variables. format is k:v(eg --vars=name:value,var1:val1)
     *  --suffix STRING            The class name suffix. default is: <info>Command</info>
     *  --preview BOOL             Preview class code before generate file(<info>false</info>)
     *  --tpl-file STRING          The template file name. default is: <info>alone-command.stub</info>
     *  --tpl-dir STRING           The template file dir path.(default: mco/res/templates)
     * @example
     *  <info>{fullCommand} demo</info>        Gen DemoCommand class to `@app/Console/Command`
     * @param Input $in
     * @param Output $out
     * @return int
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function aloneCommand(Input $in, Output $out): int
    {
        list($config, $data) = $this->collectInfo($in, $out, [
            'namespace' => 'App\\Console\\Command',
            'suffix' => 'Command',
            'filename' => 'alone-command',
        ]);

        return $this->writeFile('@app/Console/Command', $data, $config, $out);
    }

    /**
     * Generate console controller class
     * @usage {fullCommand} NAME SAVE_DIR [--option ...]
     * @arguments
     *  name       The class name, don't need suffix and ext.(eg. <info>demo</info>)
     *  dir        The class file save dir(default: <info>@app/Console/Controller</info>)
     * @options
     *  -y, --yes BOOL             No need to confirm when performing file writing. default is: <info>False</info>
     *  -o, --override BOOL        Force override exists file. default is: <info>False</info>
     *  -n, --namespace STRING     The class namespace. default is: <info>App\Console\Controller</info>
     *  --vars STRING              Add some custom variables. format is k:v(eg --vars=name:value,var1:val1)
     *  --suffix STRING            The class name suffix. default is: <info>Controller</info>
     *  --preview BOOL             Preview class code before generate file(<info>false</info>)
     *  --tpl-file STRING          The template file name. default is: <info>group-command.stub</info>
     *  --tpl-dir STRING           The template file dir path.(default: mco/res/templates)
     * @example
     *  <info>{fullCommand} demo</info>     Gen DemoController class to `@app/Console/Controller`
     * @param Input $in
     * @param Output $out
     * @return int
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function groupCommand(Input $in, Output $out): int
    {
        list($config, $data) = $this->collectInfo($in, $out, [
            'namespace' => 'App\\Console\\Controller',
            'suffix' => 'Controller',
            'filename' => 'group-command',
        ]);

        return $this->writeFile('@app/Console/Controller', $data, $config, $out);
    }

    /**
     * Generate HTTP controller class
     * @usage {fullCommand} NAME SAVE_DIR [--option ...]
     * @arguments
     *  name       The class name, don't need suffix and ext.(eg. <info>demo</info>)
     *  dir        The class file save dir(default: <info>@app\Http\Controller</info>)
     * @options
     *  -y, --yes BOOL             No need to confirm when performing file writing. default is: <cyan>False</cyan>
     *  -o, --override BOOL        Force override exists file. default is: <cyan>False</cyan>
     *  -n, --namespace STRING     The class namespace. default is: <cyan>App\Http\Controller</cyan>
     *  --rest BOOL                The class will contains CURD action. default is: <cyan>False</cyan>
     *  --vars STRING              Add some custom variables. format is k:v(eg --vars=name:value,var1:val1)
     *  --prefix STRING            The route prefix for the controller. default is class name
     *  --preview BOOL             Preview class code before generate file(<cyan>true</cyan>)
     *  --suffix STRING            The class name suffix. default is: <cyan>Controller</cyan>
     *  --action-suffix STRING     The controller action method suffix(@todo <cyan>Action</cyan>)
     *  --tpl-file STRING          The template file name. default is: <cyan>TYPE-controller.stub</cyan>
     *  --tpl-dir STRING           The template file dir path.(default: @mco/res/templates)
     * @example
     *  <info>{fullCommand} demo --prefix /demo -y</info>          Gen DemoController class to `@app\Http\Controller`
     *  <info>{fullCommand} user --prefix /users --rest</info>     Gen UserController class to `@app\Http\Controller`(RESTFul type)
     * @return int
     * @param Input $in
     * @param Output $out
     * @return int
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function controllerCommand(Input $in, Output $out): int
    {
        list($config, $data) = $this->collectInfo($in, $out, [
            'suffix' => 'Controller',
            'namespace' => 'App\\Http\\Controller',
            'filename' => 'http-controller',
        ]);

        $prefix = $in->getOpt('prefix') ?: $data['name'];
        $data['prefix'] = '/' . \trim($prefix, '/ ');

        if ($in->getOpt('rest', false)) {
            $config['filename'] = 'rest-controller';
        }

        return $this->writeFile('@app\Http\Controller', $data, $config, $out);
    }

    /**
     * Generator a logic class of the project
     * @usage {fullCommand} NAME SAVE_DIR [--option ...]
     * @arguments
     *  name       The class name, don't need suffix and ext.(eg. <info>demo</info>)
     *  dir        The class file save dir(default: <info>@app\Logic</info>)
     * @options
     *  -y, --yes BOOL             No need to confirm when performing file writing. default is: <cyan>False</cyan>
     *  -o, --override BOOL        Force override exists file. default is: <cyan>False</cyan>
     *  -n, --namespace STRING     The class namespace. default is: <cyan>App\Logic</cyan>
     *  --vars STRING              Add some custom variables. format is k:v(eg --vars=name:value,var1:val1)
     *  --preview BOOL             Preview class code before generate file(<cyan>true</cyan>)
     *  --suffix STRING            The class name suffix. default is: <cyan>Logic</cyan>
     *  --tpl-file STRING          The template file name. default is: <cyan>TYPE-controller.stub</cyan>
     *  --tpl-dir STRING           The template file dir path.(default: @mco/res/templates)
     * @example
     *  <info>{fullCommand} demo --prefix /demo -y</info>          Gen DemoController class to `@app\Logic`
     *  <info>{fullCommand} user --prefix /users --rest</info>     Gen UserController class to `@app\Logic`(RESTFul type)
     * @return int
     * @param Input $in
     * @param Output $out
     * @return int
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function logicCommand(Input $in, Output $out): int
    {
        list($config, $data) = $this->collectInfo($in, $out, [
            'suffix' => 'Logic',
            'namespace' => 'App\\Logic',
            'filename' => 'logic-class',
        ]);

        $name = $data['name'];
        $data['upperName'] = \strtoupper($name);
        $data['modelClass'] = \ucfirst($name) . 'Model';
        $data['fullModelClass'] = 'App\\Model\\Database\\' . $data['modelClass'];

        return $this->writeFile('@app\Logic', $data, $config, $out);
    }

    /**
     * Generate a model class for the application
     * @usage {fullCommand} NAME SAVE_DIR [--option ...]
     * @arguments
     *  name       The class name, don't need suffix and ext.(eg. <cyan>demo</cyan>)
     *  dir        The class file save dir(default: <cyan>@app\Model\Database</cyan>)
     *
     * @options
     *  --type STRING              The model class type. allow: data,db(<cyan>data</cyan>)
     *                             data: it is a php data model
     *                             db: it is a database table data model
     *
     *  --fields STRING            Define the model fields. when the argument "--type=data"
     *                             format - filed1,type,trans;filed2=DEFAULT_VALUE,type,trans;filed3,type,trans
     *                             e.g. fields="username,string,Username;password,string,Password;role,int,Role Type;"
     *
     *  -d, --db STRING            The database service name in the app container(<cyan>db</cyan>)
     *  -y, --yes BOOL             Do not ask anything(<cyan>False</cyan>)
     *  -o, --override BOOL        Force override exists file. default is: <cyan>False</cyan>
     *  --preview BOOL             Preview class code before generate file(<cyan>true</cyan>)
     *  --rules BOOL               Generate field validate rules(<cyan>false</cyan>)
     *  --suffix STRING            The class name suffix. default is: <cyan>Model</cyan>
     *  --tpl-file STRING          The template file name. default is: <cyan>TYPE-model.stub</cyan>
     *  --tpl-dir STRING           The template file dir path.(<cyan>@mco/res/templates</cyan>)
     *  --default-type STRING      The default data type name, allow: int, string.(<cyan>string</cyan>)
     *
     * @example
     *  {fullCommand} user --fields="username;role,int,Role Type;"
     * @param \inhere\console\io\Input $input
     * @param \inhere\console\io\Output $output
     * @return int
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function modelCommand(Input $input, Output $output): int
    {
        list($config, $data) = $this->collectInfo($input, $output, [
            'suffix' => 'Model',
            'namespace' => 'App\\Model\\Database',
            'filename' => 'database-model',
        ]);

        $types = ['data', 'db'];
        $dataTypes = ['int', 'string'];
        $v = Validation::check($input->getOptions(), [
            // ['name', 'required', 'msg' => 'the argument "name" is required. please input by name=VALUE'],
            [
                'type',
                'in',
                $types,
                'default' => 'data',
                'msg' => 'the option "--type" only allow: ' . implode(',', $types)
            ],
            [
                'default-type',
                'in',
                $dataTypes,
                'default' => 'string',
                'msg' => 'the option "--default-type" only allow: ' . implode(',', $dataTypes)
            ],
            [
                'fields',
                'required',
                'when' => function ($data) {
                    return !isset($data['type']) || $data['type'] === 'data';
                },
                'msg' => 'The option "fields" cannot be empty, when "--type=data"(is default value)'
            ],
        ]);

        if ($v->fail()) {
            $output->liteError($v->firstError());

            return -1;
        }

        $name = $data['name'];
        $type = $v->getSafe('type');
        $defaultType = $v->getSafe('default-type');

        $useDb = $type === 'db';
        $dbService = $v->getSafe('db', 'db');
        $fields = \trim($v->getSafe('fields'), '; ');

        $data = \array_merge($data, [
            'db' => $useDb ? $dbService : null,
            'table' => $useDb ? $name : null,
            // 'methods' => '',
            'fields' => $fields,

            'rules' => '',
            'columns' => '',
            'properties' => '',
            'translates' => '',
            'defaultData' => '',
        ]);

        $rules = [];
        $data['fullCommand'] = $input->getFullScript();

        $fields = \explode(';', trim($fields, '; '));
        $indent = \str_repeat(' ', 12);
        $indent8 = \str_repeat(' ', 8);

        foreach ($fields as $value) {
            if (!$value) {
                continue;
            }

            $info = \explode(',', \trim($value, ','));

            if (!$info || !$info[0]) {
                continue;
            }

            $type = $defaultType;
            $field = \trim($info[0]);

            if (\strpos($field, '=')) {
                list($field, $value) = \explode('=', $field);
                $value = \is_numeric($value) ? $value : "'$value'";
                $data['defaultData'] .= "\n{$indent8}'{$field}' => {$value},";
            }

            if (isset($info[1])) {
                $type = \strpos($info[1], 'int') !== false ? 'int' : 'string';
            }

            $trans = isset($info[2]) ? trim($info[2]) : ucfirst($field);

            $rules[$type][] = $field;

            $data['columns'] .= "\n{$indent}'{$field}' => ['{$type}'],";
            $data['translates'] .= "\n{$indent}'{$field}' => '{$trans}',";
            $data['properties'] .= "\n * @property $type \${$field}";
        }

        foreach ($rules as $type => $list) {
            $fieldStr = \implode(',', $list);
            $data['rules'] .= "\n{$indent}['{$fieldStr}', '{$type}'],";
        }

        // $this->appendTplVars($data);

        return $this->writeFile('@app\Model\Database', $data, $config, $output);
    }

    /**
     * Create controller,logic,model classes
     * @usage {fullCommand} NAME [--option ...]
     * @arguments
     *  name       The class name, don't need suffix and ext.(eg. <info>demo</info>)
     * @options
     *  -y, --yes BOOL             No need to confirm when performing file writing. default is: <cyan>False</cyan>
     *  -o, --override BOOL        Force override exists file. default is: <cyan>False</cyan>
     *
     *  --rest BOOL                The class will contains CURD action. default is: <cyan>False</cyan>
     *  --prefix STRING            The route prefix for the controller. default is name
     *
     *  --fields STRING            Define the model fields. when the argument "--type=data"
     *                             format - filed1,type,trans;filed2=DEFAULT_VALUE,type,trans;filed3,type,trans
     *                             e.g. fields="username,string,Username;password,string,Password;role,int,Role Type;"
     *
     *  --vars STRING              Add some custom variables. format is k:v(eg --vars=name:value,var1:val1)
     *  --tpl-dir STRING           The template file dir path.(default: @mco/res/templates)
     * @example
     *  <info>{fullCommand} demo --prefix demo --fields="id,int;status,int;name;alias;createAt,int" --rest</info>
     *
     * Will Generate: DemoController, DemoLogic, DemoModel
     * @param Input $input
     * @param Output $output
     * @return int
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function clmCommand(Input $input, Output $output): int
    {
        $script = $input->getScript();
        $workDir = $input->getPwd();

        // check arg
        $name = $input->getRequiredArg(0);

        // generate model
        if ($fields = $input->getLongOpt('fields')) {
            $genModelCmd = \sprintf('php %s gen:model %s -y --fields="%s"', $script, $name, $fields);
            $this->write("RUN > $genModelCmd");
            list($code, , $error) = Sys::run($genModelCmd, $workDir);

            if ($code !== 0) {
                $output->liteError("Run the generate model command is failure! Err: $error");

                return $code;
            }
        }

        // generate controller
        $rest = $input->getBoolOpt('rest');
        $prefix = $input->getLongOpt('prefix', '');
        $genCtrlCmd = \sprintf(
            'php %s gen:controller %s -y --prefix="%s"%s',
            $script,
            $name,
            $prefix,
            $rest ? ' --rest' : ''
        );

        $this->write("RUN > $genCtrlCmd");
        list($code, , $error) = Sys::run($genCtrlCmd, $workDir);

        if ($code !== 0) {
            $output->liteError("Run the generate controller command is failure! Err: $error");

            return $code;
        }

        // generate logic
        $genLogicCmd = \sprintf('php %s gen:logic %s -y', $script, $name);

        $this->write("RUN > $genLogicCmd");
        list($code, , $error) = Sys::run($genLogicCmd, $workDir);

        if ($code !== 0) {
            $output->liteError("Run the generate logic command is failure! Err: $error");

            return $code;
        }

        $output->success("OK, The controller,logic,model classes create successful for '$name'!");

        return 0;
    }

    /**
     * @param Input $in
     * @param Output $out
     * @param array $defaults
     * @return array
     */
    private function collectInfo(Input $in, Output $out, array $defaults = []): array
    {
        $config = [
            'filename' => $in->getOpt('tpl-file') ?: $defaults['filename'],
            'directory' => $in->getOpt('tpl-dir') ?: $this->defaultTplPath,
        ];

        if (!$name = $in->getArg(0)) {
            $name = $in->read('Please input class name(no suffix and ext. eg. test): ');
        }

        if (!$name) {
            $out->writeln('<error>No class name input! Quit</error>', true, 1);
        }

        $sfx = $in->getOpt('suffix') ?: $defaults['suffix'];
        $data = [
            'name' => $name,
            'suffix' => $sfx,
            'namespace' => $in->sameOpt(['n', 'namespace']) ?: $defaults['namespace'],
            'className' => \ucfirst($name) . $sfx,
        ];

        return [$config, $data];
    }

    /**
     * @param string $defaultDir
     * @param array $data
     * @param array $config
     * @param Output $out
     * @return int
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    private function writeFile(string $defaultDir, array $data, array $config, Output $out): int
    {
        // $out->writeln("Some Info: \n" . \json_encode($config, \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES));
        $out->writeln("Class data: \n" . \json_encode($data, \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES));

        if (!$saveDir = $this->getArg(1)) {
            $saveDir = $defaultDir;
        }

        list($data['date'], $data['time']) = \explode(' ', \date('Y-m-d H:i'));
        $saveDir = $this->resolvePath($saveDir);

        $yes = $this->input->sameOpt(['y', 'yes'], false);
        $file = $saveDir . '/' . $data['className'] . '.php';

        $out->writeln("Target File: <info>$file</info>\n");

        $parser = new SimpleTemplate($config);

        $preview = $this->input->boolOpt('preview');
        $content = $parser->render($data);

        // preview
        if ($preview || (!$yes && $this->confirm('Do you want preview class code'))) {
            $highContent = Highlighter::create()->highlight($content);
            $this->output->write("\n$highContent\n");
        }

        if (\file_exists($file)) {
            $override = $this->input->sameOpt(['o', 'override']);

            if (null === $override) {
                if (!$yes && !$this->confirm('Target file has been exists, override?', false)) {
                    $out->writeln(' Quit, Bye!');

                    return 0;
                }
            } elseif (!$override) {
                $out->writeln(' Quit, Bye!');

                return 0;
            }
        }

        // check save dir
        if (!\file_exists($saveDir)) {
            if (!$yes && !$this->confirm('Target file dir is not exists! Create it')) {
                $out->write(' Quit, Bye!');

                return 0;
            }

            Helper::mkdir($saveDir);
        }

        if (!$yes && !$this->confirm('Now, will write content to file, ensure continue?')) {
            $out->writeln(' Quit, Bye!');

            return 0;
        }


        if ($ok = $parser->render($data, $file)) {
            $out->writeln('<success>OK, write successful!</success>');
        } else {
            $out->writeln('<error>NO, write failed!</error>');
        }

        return 0;
    }

    /**
     * @param string $path
     * @return string
     */
    public function resolvePath(string $path): string
    {
        if ($cb = $this->pathResolver) {
            return $cb($path);
        }

        return $path;
    }

    /**
     * @return callable
     */
    public function getPathResolver(): callable
    {
        return $this->pathResolver;
    }

    /**
     * @param callable $pathResolver
     */
    public function setPathResolver(callable $pathResolver)
    {
        $this->pathResolver = $pathResolver;
    }

    /**
     * @param array $data
     */
    protected function appendTplVars(array $data)
    {
        foreach ($data as $key => $value) {
            $this->tplVars[$key] = $value;
        }
    }
}
