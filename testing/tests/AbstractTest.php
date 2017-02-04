<?php

/* 
 * 
 */

namespace iRAP\Queues\Testing;

abstract class AbstractTest
{
    protected $m_passed = false;
    protected $m_errorMessage = "";
    
    
    /**
     * Run the test.
     * If any exception is thrown then the test is considered a failure.
     */
    protected abstract function test();
    
    
    
    public function run()
    {
        $this->cleanDatabase();
        
        try
        {
            $this->test();
        } 
        catch (Exception $ex) 
        {
            $this->m_passed = false;
            $this->m_errorMessage = $ex->getMessage();
        }
    }
    
    # Accessors
    public final function getPassed() { return $this->m_passed; }
    public final function getErrorMessage() { return $this->m_errorMessage; }
}

