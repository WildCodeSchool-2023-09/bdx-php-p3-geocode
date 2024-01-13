<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Exception;

abstract class AbstractCsvService
{
    protected mixed $file;

    abstract public function verifyFirstLineFile(string $firstLine): void;
    abstract protected function getColumns(array $array): array;
    abstract public function verifyData(array $data): object;
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
    public function read(): array
    {
        $header = trim(fgets($this->file));
        $this->verifyFirstLineFile($header);
        $header = explode(';', $header);
        $rows = [];
        while (($data = fgetcsv($this->file, null, ";")) !== false) {
            if ($data) {
                $row = array_combine($header, $data);
                $rows[] =  $this->getColumns($row);
            }
        }
        fclose($this->file);
        return $rows;
    }

    /**
     * @throws Exception
     */
    public function saveInDatabase(): void
    {
        $rows = $this->read();
        foreach ($rows as $row) {
            $object = $this->verifyData($row);
            $this->entityManager->persist($object);
        }

        $this->entityManager->flush();

        fclose($this->file);
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
