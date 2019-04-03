<?php

namespace App\Http\Utils;


class Response
{
    private $success;
    private $entities;
    private $count;
    private $message;
    private $error;

    /**
     * Response constructor.
     */
    public function __construct()
    {

    }

    /**
     * @return boolean
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * @param boolean $success
     */
    public function setSuccess($success): void
    {
        $this->success = $success;
    }

    /**
     * @return array
     */
    public function getEntities()
    {
        return $this->entities;
    }

    /**
     * @param array $entities
     */
    public function setEntities($entities): void
    {
        $this->entities = $entities;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param int $count
     */
    public function setCount($count): void
    {
        $this->count = $count;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param string $error
     */
    public function setError($error): void
    {
        $this->error = $error;
    }

    public function toArray() {
        $arr['success'] = $this->success;
        if($this->entities !== null) {
            $arr['entities'] = [$this->entities];
            $arr['count'] = count($arr['entities']);
        }
        if($this->message !== null) {
            $arr['message'] = $this->message;
        }
        if($this->error !== null) {
            $arr['error'] = $this->error;
        }
        return $arr;
    }


}