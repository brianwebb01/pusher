<?php namespace Parse;

class ParsingException extends \ErrorException
{
    /** @var array */
    protected $details;

    /**
     * @return array
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @param array $details
     */
    public function setDetails(array $details)
    {
        $this->details = $details;
    }

} 