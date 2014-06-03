<?php namespace Configuration;

/**
 * @copyright   2014 Indatus
 */

class Environment
{
    protected $name;

    protected $branch;

    protected $user;

    protected $servers;

    /**
     * @param       $branch
     * @param       $user
     * @param array $servers
     */
    function __construct($name, $branch, $user, array $servers)
    {
        $this->name = $name;
        $this->branch = $branch;
        $this->servers = $servers;
        $this->user = $user;
    }

    /**
     * The name of this environment
     *
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * The branch to deploy
     *
     * @return string
     */
    public function branch()
    {
        return $this->branch;
    }

    /**
     * Servers that make up this environment
     *
     * @return array
     */
    public function servers()
    {
        return $this->servers;
    }

    /**
     * User to deploy as
     *
     * @return string
     */
    public function user()
    {
        return $this->user;
    }


} 