<?php

namespace App\Business;

trait Business
{

    protected $repository;
    protected $repositoryClass;

    public function __construct()
    {
        $this->repositoryClass = '\App\Repositories\\' . str_replace('App\Business\\', '', get_class($this));
        if (class_exists($this->repositoryClass)) {
            $this->repository = new $this->repositoryClass();
        }
    }
    
    public function __call(string $name, array $arguments): object
    {
        if (!method_exists($this, $name)) {
            if (!method_exists($this->repository, $name)) {
                throw new \Exception($name . ' não existe no business');
            }
            return call_user_func_array(array($this->repository, $name), $arguments);
        }
        return parent::_call($name, $arguments);
    }
}
