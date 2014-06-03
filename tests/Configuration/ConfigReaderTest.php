<?php namespace Configuration;

use Mockery as m;

class ConfigReaderTest extends \PusherTest
{

    /** @var array */
    protected $config;

    /** @var ConfigReader */
    protected $reader;

    /** @var ConfigLoader */
    protected $loader;

    /** @var \Console\OptionReader */
    protected $optionReader;

    public function setUp()
    {
        parent::setUp();

        $this->config = [
            'scm' => 'git2',
            'rootDirectory' => '/path/to/root',
            'defaultEnvironment' => 'production',
            'directorySeparator' => '_', //yep, it's that kind of OS
            'releases' => 20,
            'application' => [
                'name' => 'myApp'
            ],
            'permissions' => [
                'access' => 755
            ],
            'repository' => [
                'url' => 'http://www.google.com',
                'username' => 'deploy'
            ],
            'environments' => [
                'dev' => [
                    'branch' => 'develop',
                    'user' => 'deploy',
                    'servers' => [
                        'serv1',
                        'serv1'
                    ]
                ]
            ]
        ];

        $this->loader = m::mock('Configuration\ConfigLoader');
        $this->optionReader = m::mock('Console\OptionReader');
        $this->loader
            ->shouldReceive('load')
            ->with($this->optionReader)
            ->once()
            ->andReturn($this->config);

        $this->reader = new ConfigReader($this->loader, $this->optionReader);
    }



    public function testApplicationName()
    {
        //test actual value
        $this->assertEquals($this->config['application']['name'], $this->reader->applicationName());

        //test exception
        $this->setExpectedException('Configuration\ConfigurationException', 'No application name has been specified');
        $reader = $this->getConfigReader();
        $reader->applicationName();
    }



    public function testPermissionsAccess()
    {
        //test actual value
        $this->assertEquals($this->config['permissions']['access'], $this->reader->permissionsAccess());

        //test exception
        $this->setExpectedException('Configuration\ConfigurationException', 'No access permissions have been specified');
        $reader = $this->getConfigReader();
        $reader->permissionsAccess();
    }



    public function testRepositoryUrl()
    {
        //test actual value
        $this->assertEquals($this->config['repository']['url'], $this->reader->repositoryUrl());

        //test exception
        $this->setExpectedException('Configuration\ConfigurationException', 'No repository URL is specified');
        $reader = $this->getConfigReader();
        $reader->repositoryUrl();
    }



    public function testRepositoryUsername()
    {
        //test actual value
        $this->assertEquals($this->config['repository']['username'], $this->reader->repositoryUsername());

        //test exception
        $this->setExpectedException('Configuration\ConfigurationException', 'No repository username is specified');
        $reader = $this->getConfigReader();
        $reader->repositoryUsername();
    }



    public function testNoEnvironments()
    {
        $this->setExpectedException('Configuration\ConfigurationException', 'No environments are configured');
        $reader = $this->getConfigReader();
        $reader->environment('dev-noexist');
    }



    public function testEnvironmentDoesNotExist()
    {
        $environment = 'smoo';
        $this->setExpectedException('Configuration\ConfigurationException', 'The environment "'.$environment.'" does not exist');
        $reader = $this->getConfigReader($this->config);
        $reader->environment($environment);
    }



    public function testEnvironmentWithoutBranch()
    {
        $this->setExpectedException('Configuration\ConfigurationException', 'A branch name is required for the "dev" environment');
        unset($this->config['environments']['dev']['branch']);
        $reader = $this->getConfigReader($this->config);
        $reader->environment('dev');
    }



    public function testEnvironmentWithoutUser()
    {
        $this->setExpectedException('Configuration\ConfigurationException', 'A user is required for the "dev" environment');
        unset($this->config['environments']['dev']['user']);
        $reader = $this->getConfigReader($this->config);
        $reader->environment('dev');
    }



    public function testEnvironmentWithoutServers()
    {
        $this->setExpectedException('Configuration\ConfigurationException', 'No servers have been configured for the "dev" environment');
        unset($this->config['environments']['dev']['servers']);
        $reader = $this->getConfigReader($this->config);
        $reader->environment('dev');
    }



    public function testEnvironment()
    {
        $env = 'dev';
        $environment = $this->reader->environment($env);
        $this->assertInstanceOf('Configuration\Environment', $environment);

        //verify we're passing the right values to the constructor in the right order
        $this->assertEquals($env, $environment->name());
        $this->assertEquals($this->config['environments'][$env]['branch'], $environment->branch());
        $this->assertEquals($this->config['environments'][$env]['user'], $environment->user());
        $this->assertEquals($this->config['environments'][$env]['servers'], $environment->servers());
    }



    public function testScmDriver()
    {
        $this->assertEquals($this->config['scm'], $this->reader->scmDriver());

        //test default value
        $reader = $this->getConfigReader();
        $this->assertEquals('git', $reader->scmDriver());
    }



    public function testRootAppDirectory()
    {
        $this->assertEquals($this->config['rootDirectory'], $this->reader->rootAppDirectory());

        //test default value
        $reader = $this->getConfigReader();
        $this->assertEquals('/var/www', $reader->rootAppDirectory());
    }



    public function testDefaultEnvironment()
    {
        $this->assertEquals($this->config['defaultEnvironment'], $this->reader->defaultEnvironment());

        //test default value
        $reader = $this->getConfigReader();
        $this->assertEquals('dev', $reader->defaultEnvironment());
    }



    public function testDirectorySeparator()
    {
        $this->assertEquals($this->config['directorySeparator'], $this->reader->directorySeparator());

        //test default value
        $reader = $this->getConfigReader();
        $this->assertEquals('/', $reader->directorySeparator());
    }



    public function testKeepReleases()
    {
        $this->assertEquals($this->config['releases'], $this->reader->keepReleases());

        //test default value
        $reader = $this->getConfigReader();
        $this->assertEquals(3, $reader->keepReleases());
    }



    /**
     * Get a config reader that will always throw exceptions
     *
     * @return ConfigReader
     */
    protected function getConfigReader($config = [])
    {
        $loader = m::mock('Configuration\ConfigLoader');
        $loader
            ->shouldReceive('load')
            ->with($this->optionReader)
            ->once()
            ->andReturn($config);

        return new ConfigReader($loader, $this->optionReader);
    }
}