<?php

namespace Bolt\Extension\Teneleven\Less;

use Bolt\BaseExtension;

class Extension extends BaseExtension
{
    public function initialize()
    {
        if (strtolower(php_sapi_name()) === 'cli') {
            return;
        }

        foreach ($this->getConfig()['convert'] as $input => $output) {
            $input = $this->app['paths']['web'] . $this->app['paths']['theme'] . $input;
            $output = $this->app['paths']['web'] . $this->app['paths']['theme'] . $output;
            $hashId = 'bot-extension-less.teneleven.' . $input;

            if (!file_exists(dirname($output))) {
                throw new \RuntimeException('Directory ' . dirname($output) . ' doesn\'t exists', 1);
            } elseif (!is_dir(dirname($output))) {
                throw new \RuntimeException('File ' . dirname($output) . ' is not directory', 1);
            } else {
                if (file_exists($output) && !is_writeable($output)) {
                    throw new \RuntimeException('File ' . $output . ' is not writeable', 1);
                } elseif (!file_exists($output) && !is_writeable(dirname($output))) {
                    throw new \RuntimeException('Directory ' . dirname($output) . ' is not writeable', 1);
                }
            }

            $lastChangeInCache = $this->app['cache']->fetch($hashId);
            $lastChange = filemtime($input);

            if ($lastChangeInCache !== $lastChange) {
                $command = escapeshellcmd($this->getConfig()['bin']) . ' '
                         . escapeshellarg($input) . ' '
                         . escapeshellarg($output) . ' --verbose '
                         . '&& chmod 775 ' . escapeshellarg($output);

                exec($command, $output, $returnVar);

                if ($returnVar !== 0) {
                    $message = 'Couldn\'t execute command: ' . "\n" . $command;
                    $message .= "\n" . 'Return code: ' . (string) $returnVar;
                    if (!empty($output)) {
                        $message .= "\n" . 'Message:' . "\n" . implode("; ", $output);
                    }
                    throw new \RuntimeException($message, 1);
                } else {
                    $this->app['cache']->save($hashId, $lastChange);
                }
            }
        }
    }

    public function getName()
    {
        return "LESS";
    }

}
