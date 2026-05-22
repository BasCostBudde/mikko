<?php

namespace App;

class ProductionFileSystem implements FileSystem
{

    // return true if filename exists
    public function exists($filename): bool
    {
        return file_exists($filename);
    }

    // return true if filename can be created and written to
    public function creatable($filename): bool
    {
        return touch($filename);
    }

    // return false if something went wrong during writing ("disk full")
    public function write($filename, $contents): bool
    {
        return file_put_contents($filename, $contents);
    }

}
