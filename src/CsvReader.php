<?php

namespace Src;

class CsvReader
{
    public static function read(string $filename): array
    {
        $rows = [];
        $file = fopen($filename, 'r');

        while (($data = fgetcsv($file)) !== false) {
            $rows[] = [
                'operationDate' => $data[0],
                'userId' => (int) $data[1],
                'userType' => $data[2],
                'operationType' => $data[3],
                'amount' => (float) $data[4],
                'currency' => $data[5],
            ];
        }

        fclose($file);
        return $rows;
    }
}
