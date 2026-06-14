<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Dtr;
use Carbon\Carbon;

class DTRUploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('excel_file');

        $spreadsheet = IOFactory::load($file->getPathname());

        $sheet = $spreadsheet->getActiveSheet();

        $rows = $sheet->toArray();

        foreach (array_slice($rows, 1) as $row) {

            // skip empty rows
            if (empty($row[0]) || empty($row[1])) {
                continue;
            }

            Dtr::create([
                'employee_number' => trim($row[0]),

                'date' => !empty($row[1])
                    ? Carbon::parse($row[1])->format('Y-m-d')
                    : null,

                'time_in' => !empty($row[2])
                    ? date('H:i:s', strtotime($row[2]))
                    : null,

                'time_out' => !empty($row[3])
                    ? date('H:i:s', strtotime($row[3]))
                    : null,

                'cutoff' => !empty($row[4])
                    ? Carbon::parse($row[4])->format('Y-m-d')
                    : null,
            ]);
        }

        return back()->with('success', 'DTR imported successfully!');
    }
}