<?php

namespace App\Exports;

use App\File;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FilesExport implements FromQuery
{

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function query()
    {
        return File::query()->Where('id', $this->id)->select('csv_data');
    }
}
