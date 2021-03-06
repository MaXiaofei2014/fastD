<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Console;


use FastD\Utils\FileObject;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Config
 * @package FastD\Console
 */
class Config extends Command
{
    public function configure()
    {
        $this->setName('config:dump');
        $this->addArgument('name', InputArgument::OPTIONAL, 'file name');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return mixed
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getArgument('name')) {
            $file = app()->getPath() . '/config/' . $input->getArgument('name') . '.php';
            $config = load($file);
            $output->writeln('file: ' . $file);
            $output->writeln('<info>' . Yaml::dump($config) . '</info>');
            return 0;
        }
        $table = new Table($output);
        $rows = [];
        $table->setHeaders(array('File', 'Config', 'Owner', 'Modify At',));

        foreach (glob(app()->getPath() . '/config/*') as $file) {
            $file = new FileObject($file);
            $config = load($file->getPathname());
            $rows[] = [
                $file->getFilename(),
                count(array_keys($config)) . ' Keys',
                posix_getpwuid($file->getOwner())['name'],
                date('Y-m-d H:i:s', $file->getMTime()),
            ];
        }

        $table->setRows($rows);
        $table->render();
    }
}