<?php

namespace LarvaCMS\WebDriver\Chrome;

use LarvaCMS\WebDriver\OperatingSystem;
use RuntimeException;
use Symfony\Component\Process\Process;

class ChromeProcess
{
    /**
     * The path to the Chromedriver.
     *
     * @var string
     */
    protected $driver;

    /**
     * Create a new ChromeProcess instance.
     *
     * @param  string  $driver
     * @return void
     *
     * @throws \RuntimeException
     */
    public function __construct($driver = null)
    {
        $this->driver = $driver;

        if (! is_null($driver) && realpath($driver) === false) {
            throw new RuntimeException("Invalid path to Chromedriver [{$driver}].");
        }
    }

    /**
     * Build the process to run Chromedriver.
     *
     * @param  array  $arguments
     * @return Process
     */
    public function toProcess(array $arguments = []): Process
    {
        if ($this->driver) {
            return $this->process($arguments);
        }

        if ($this->onWindows()) {
            $this->driver = realpath(__DIR__.'/../../bin/chromedriver-win.exe');
        } elseif ($this->onMac()) {
            $this->driver = realpath(__DIR__.'/../../bin/chromedriver-mac');
        } else {
            $this->driver = realpath(__DIR__.'/../../bin/chromedriver-linux');
        }

        return $this->process($arguments);
    }

    /**
     * Build the Chromedriver with Symfony Process.
     *
     * @param  array  $arguments
     * @return Process
     */
    protected function process(array $arguments = []): Process
    {
        return new Process(
            array_merge([realpath($this->driver)], $arguments), null, $this->chromeEnvironment()
        );
    }

    /**
     * Get the Chromedriver environment variables.
     *
     * @return array
     */
    protected function chromeEnvironment(): array
    {
        if ($this->onMac() || $this->onWindows()) {
            return [];
        }

        return ['DISPLAY' => $_ENV['DISPLAY'] ?? ':0'];
    }

    /**
     * Determine if Dusk is running on Windows or Windows Subsystem for Linux.
     *
     * @return bool
     */
    protected function onWindows(): bool
    {
        return OperatingSystem::onWindows();
    }

    /**
     * Determine if Dusk is running on Mac.
     *
     * @return bool
     */
    protected function onMac(): bool
    {
        return OperatingSystem::onMac();
    }
}
