<?php namespace Console;

use Configuration\ConfigReader;

/**
 * Class for reading optional command line parameters
 */
class OptionReader
{

    /**
     * Command line parameters
     * @var $options array
     */
    protected $options;

    function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * Get the architecture to be generated
     *
     * @return string
     */
    public function configPath()
    {
        return $this->hasConfigPath() ? realpath($this->options['config']) : getcwd().DIRECTORY_SEPARATOR.ConfigReader::FILENAME;
    }

    /**
     * Did the user specify a config path?
     *
     * @return bool
     */
    public function hasConfigPath()
    {
        return array_key_exists('config', $this->options) && !is_null($this->options['config']);
    }
} 