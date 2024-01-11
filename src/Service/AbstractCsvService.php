<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Exception;

abstract class AbstractCsvService
{
    protected mixed $file;

    /**
     * @throws Exception
     */
    public function __construct(
        protected string $filename,
        protected EntityManagerInterface $entityManager,
        protected PrepareTown $prepareTown = new PrepareTown()
    ) {
        $this->verifyFilename();
        //on ouvre le fichier en mode lecture
        $this->file = fopen($this->getFilename(), "r");
    }

    /**
     * @throws Exception
     */
    public function verifyFilename(): void
    {
        if (!is_file($this->getFilename())) {
            fclose($this->file);
            throw new Exception('File not found.' . PHP_EOL .
                'Verify your sources directory or yours parameters in config.yalm');
        }
    }

    public function getFilename(): string
    {
        return dirname('.', 2) . $this->filename;
    }
}
