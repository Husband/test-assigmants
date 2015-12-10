<?php

namespace FunctionalTests;

/**
 * Created by PhpStorm.
 * User: SergeyS
 * Date: 30.10.2015
 * Time: 16:46
 */
class DbiTester
{
    protected $db;

    /**
     * DbiTest constructor.
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function query($sql)
    {
        $result = $this->db->query($sql);
        return $result;
    }

    public function close()
    {
        $this->db->close();
    }
}