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
        protected string $sortingIndex1,
        protected string $sortingIndex2,
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

            usort($rows, fn($row1, $row2) => strcmp(
                $row1[$this->sortingIndex1] . $row1[$this->sortingIndex2],
                $row2[$this->sortingIndex1] . $row2[$this->sortingIndex2]
            ));

        $previousObject = null;
        $counter = 0;
        foreach ($rows as $row) {
            try {
                $object = $this->verifyData($row);

                if ($previousObject != $object) {
                    $this->entityManager->persist($object);
                    $counter += 1;
                    $previousObject = $object;
                }
            } catch (Exception $exception) {
                echo($exception->getMessage());
            }
            if ($counter > 100) {
                $this->entityManager->flush();
                usleep(500);
                $this->entityManager->clear();
                $counter = 0;
            }
        }
        $this->entityManager->flush();
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
