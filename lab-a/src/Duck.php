<?php // src/Duck.php
namespace Azakrzewski\LabComposer;

use Psr\Log\LoggerInterface;

class Duck
{
    /** @var LoggerInterface */
    private $logger;

    public function __construct(?LoggerInterface $logger = null)
    {
        $this->logger = $logger;
        if ($this->logger) {
            $this->logger->info("Duck created.");
        }
    }
    public function quack()
    {
        if ($this->logger) {
            $this->logger->debug("Quack() executed.");
        }
        echo "Quack\n";
    }
}