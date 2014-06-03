<?php namespace Configuration;

use Console\OptionReader;
use Parse\ParsingException;
use Seld\JsonLint\JsonParser;

/**
 * @copyright   2014 Indatus
 */

class ConfigLoader
{

    /** @var \Seld\JsonLint\JsonParser */
    protected $jsonParser;

    function __construct(JsonParser $jsonParser)
    {
        $this->jsonParser = $jsonParser;
    }

    /**
     * Load json configuration files
     *
     * @param OptionReader $optionReader
     *
     * @return array
     * @throws \Parse\ParsingException
     */
    public function load(OptionReader $optionReader)
    {
        try {

            $config = $this->jsonParser->parse($optionReader->configPath());

        } catch (\Seld\JsonLint\ParsingException $e) {

            // users of this library shouldn't care what json parser we use, so lets
            // wrap the exception with our own and rethrow it as if it came from the
            // library we're using
            $exception = new ParsingException($e->getMessage(), $e->getCode(), 1, $e->getFile(), $e->getLine(), $e->getPrevious());
            $exception->setDetails($e->getDetails());

            throw $exception;
        }

        return $config;
    }

    /**
     * Gets the path to the default config
     *
     * @return string
     */
    public function defaultConfigPath()
    {
        return __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.ConfigReader::FILENAME;
    }

}
