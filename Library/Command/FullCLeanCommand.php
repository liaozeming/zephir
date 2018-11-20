<?php

/**
 * This file is part of the Zephir.
 *
 * (c) Zephir Team <team@zephir-lang.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zephir\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Zephir\Command\FullCLeanCommand
 *
 * Cleans any object files created by the extension (including files generated by phpize).
 *
 * @package Zephir\Command
 */
class FullCLeanCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('fullclean')
            ->setDescription('Cleans any object files created by the extension (including files generated by phpize)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->filesystem->clean();

        /**
         * TODO: Consider replace clean script by php iterator
         * TODO: Do we need a batch file for Windows like "clean"?
         */
        if ($this->environment->isWindows()) {
            system('cd ext && nmake clean-all');
            system('cd ext && phpize --clean');
        } else {
            system('cd ext && make clean > /dev/null');
            system('cd ext && phpize --clean > /dev/null');
            // TODO: This file contains duplicated commands
            system('cd ext && ./clean > /dev/null');
        }

        return 0;
    }
}