<?php
/**
 * Copyright 2015 Google Inc. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Google\Cloud\Samples\Language;

use Google\Cloud\Storage\StorageClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command line utility for the Natural Language APIs.
 *
 * Usage: php language.php syntax
 */
class AnalyzeSyntaxCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('syntax')
            ->setDescription('Analyze some natural language text.')
            ->setHelp(<<<EOF
The <info>%command.name%</info> command analyzes text using the Google Cloud Natural Language API.

    <info>php %command.full_name% Text to analyze.</info>

    <info>php %command.full_name% gs://my_bucket/file_with_text.txt</info>

EOF
            )
            ->addArgument(
                'content',
                InputArgument::IS_ARRAY | InputArgument::REQUIRED,
                'Text or path to Cloud Storage file'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $content = implode(' ', $input->getArgument('content'));
        if (preg_match('/gs:\/\/([a-z|0-9|\.|-]+)\/(\S+)/', $content, $matches)) {
            $storage = new StorageClient();
            $content = $storage->bucket($matches[1])->object($matches[2]);
        }
        $result = analyze_syntax($content);
        print_annotation($result);
    }
}
