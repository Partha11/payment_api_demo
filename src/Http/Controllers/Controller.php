<?php

namespace App\Http\Controllers;

use Monolog\Handler\StreamHandler;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Monolog\Level;
use Monolog\Logger;

abstract class Controller {
    
    protected Logger $logger;
    protected Filesystem $filesystem;
    protected LocalFilesystemAdapter $adapter;

    public function __construct() {

        $this->logger = new Logger('app');
        $this->adapter = new LocalFilesystemAdapter(getcwd() . '/storage/files');
        $this->logger->pushHandler(new StreamHandler(getcwd() . '/storage/logs/app.log', Level::Debug));

        $this->filesystem = new Filesystem($this->adapter);
    }
}
