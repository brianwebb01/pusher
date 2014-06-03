<?php namespace Configuration;

use Console\OptionReader;
use InvalidArgumentException;

/**
 * @copyright   2014 Indatus
 */

class ConfigReader
{

    /** Config filename */
    const FILENAME = 'deployment.json';

    /** @var \Seld\JsonLint\JsonParser */
    protected $jsonParser;

    /** @var array */
    protected $config = [];

    public function __construct(ConfigLoader $loader, OptionReader $optionReader)
    {
        $this->configLoader = $loader;

        $this->config = $loader->load($optionReader);
    }

    /**
     * Get the application name
     *
     * @return string
     * @throws ConfigurationException
     */
    public function applicationName()
    {
        if (isset($this->config['application']['name'])) {
            return $this->config['application']['name'];
        }

        throw new ConfigurationException('No application name has been specified');
    }

    /**
     * Get the default permissions to apply
     *
     * @return string
     * @throws ConfigurationException
     */
    public function permissionsAccess()
    {
        if (isset($this->config['permissions']['access'])) {
            return $this->config['permissions']['access'];
        }

        throw new ConfigurationException('No access permissions have been specified');
    }

    /**
     * Get the repository URL to deploy from
     *
     * @return string
     * @throws ConfigurationException
     */
    public function repositoryUrl()
    {
        if (isset($this->config['repository']['url'])) {
            return $this->config['repository']['url'];
        }

        throw new ConfigurationException('No repository URL is specified');
    }

    /**
     * Get the repository username
     *
     * @return string
     * @throws ConfigurationException
     */
    public function repositoryUsername()
    {
        if (isset($this->config['repository']['username'])) {
            return $this->config['repository']['username'];
        }

        throw new ConfigurationException('No repository username is specified');
    }

    /**
     * Get the specified environment
     *
     * @param $name
     *
     * @return Environment
     * @throws ConfigurationException
     */
    public function environment($name)
    {
        if (!isset($this->config['environments']) || !is_array($this->config['environments'])) {
            throw new ConfigurationException('No environments are configured');
        }

        if (!isset($this->config['environments'][$name])) {
            throw new ConfigurationException('The environment "'.$name.'" does not exist');
        }

        $env = (array)$this->config['environments'][$name];

        //@debate maybe this validation should be inside Environment?  I hate to put throwing an exception inside a constructor...
        //ensure the user specified a branch, user and servers
        if (!isset($env['branch'])) {
            throw new ConfigurationException('A branch name is required for the "'.$name.'" environment');
        }

        if (!isset($env['user'])) {
            throw new ConfigurationException('A user is required for the "'.$name.'" environment');
        }

        if (!isset($env['servers']) || !is_array($env['servers']) || count($env['servers']) == 0) {
            throw new ConfigurationException('No servers have been configured for the "'.$name.'" environment');
        }

        return new Environment($name, $env['branch'], $env['user'], $env['servers']);
    }

    /**
     * Get the source control management driver
     *
     * @return string
     */
    public function scmDriver()
    {
        if (isset($this->config['scm'])) {
            return $this->config['scm'];
        }

        return "git";
    }

    /**
     * Get the root directory where all applications are deployed to
     *
     * @return string
     */
    public function rootAppDirectory()
    {
        if (isset($this->config['rootDirectory'])) {
            return $this->config['rootDirectory'];
        }

        return "/var/www";
    }

    /**
     * Get the default environment to deploy
     *
     * @return string
     */
    public function defaultEnvironment()
    {
        if (isset($this->config['defaultEnvironment'])) {
            return $this->config['defaultEnvironment'];
        }

        return "dev";
    }

    /**
     * Get the directory separator of the destination
     *
     * @return string
     */
    public function directorySeparator()
    {
        if (isset($this->config['directorySeparator'])) {
            return $this->config['directorySeparator'];
        }

        return "/";
    }

    /**
     * Get the number of releases to keep
     *
     * @return string
     */
    public function keepReleases()
    {
        if (isset($this->config['releases'])) {
            return $this->config['releases'];
        }

        return 3;
    }

}
