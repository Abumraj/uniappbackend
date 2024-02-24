<?php

namespace App\Imports;

use App\Models\Video;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
class VideosImport implements ToModel,  WithBatchInserts, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Video([
            'chapter_id' => $row['chapter_id'],
            'name'  => $row['name'],
            'description' => $row['description'],
            'url' => $row['url'],

        ]);
    }

    public function headingRow(): int
    {
        return 1;
    }

    public function batchSize(): int
    {
        return 1000;
    }
}
