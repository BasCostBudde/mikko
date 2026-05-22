<?php

namespace App;

interface FileSystem
{

    // return true if filename exists
    public function exists($filename): bool;
    // return true if filename can be created and written to
    public function creatable($filename): bool;
    // return false if something went wrong during writing ("disk full")
    public function write($filename, $contents): bool;

}
