<?php

namespace App\Http\Controllers;

use App\Imports\ContactsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\ContactImportRequest;
use App\Http\Requests\FileImportRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Exports\FilesExport;
use Maatwebsite\Excel\Concerns\Importable;

use Illuminate\Http\Request;

use App\Contact;
use App\File;

class ContactController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = Contact::latest()->paginate(10);
        return view('contacts', compact('data'));
    }

    public function files()
    {
        $files = File::latest()->paginate(10);
        return view('files', compact('files'));
    }

    public function downloadFile($file_id)
    {
        $file = File::find($file_id);
        return Storage::disk('s3')->download('csv_imports/' . $file->file_name);
    }

    public function parseImport(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|max:2048|mimes:csv,txt',
        ]);

        $path = $request->file('csv_file')->getRealPath();
        $data = array_map('str_getcsv', file($path));

        if (count($data) > 0) {

            $file = $request->file('csv_file');
            $latest_id = File::latest()->first();
            $file_id = $latest_id ? $latest_id->id + 1 : 1;
            $user_id = Auth::id();
            $file_name = 'csv_file_' . $user_id . '_' . $file_id . '.csv';
            $file->storeAs('csv_imports', $file_name, 's3');

            if ($request->has('header')) {
                $csv_header_fields = [];
                foreach ($data[0] as $key => $value) {
                    $csv_header_fields[] = $value;
                }
                $csv_data = array_slice($data, 1, 3);
            } else {
                $csv_data = array_slice($data, 0, 3);
            }

            $csv_data_file = File::create([
                'file_name' => $file_name,
                'url' => Storage::disk('s3')->url($file_name),
                'status' => 'ON HOLD',
                'user_id' => $user_id
            ]);
        } else {
            return 'vacio';
        }

        return view('import_fields', compact('csv_header_fields', 'csv_data', 'csv_data_file'));
    }

    public function processImport(Request $request)
    {
        $id = $request->csv_data_file_id;
        $file = File::find($id);
        $file->status = 'IN PROCESS';
        $file->save();
        $startRow = $request->startRow;
        $file_name = $file->file_name;
        $fields_order = [];
        $db_fields = config('app.db_fields');

        foreach ($request->fields as $key => $value)
        {
            $fields_order[$db_fields[$value]] = $key;
        }

        $import = new ContactsImport($fields_order, $startRow);

        $import->import('csv_imports/' . $file_name, 's3', \Maatwebsite\Excel\Excel::CSV);

        $failures = [];
        
        foreach ($import->failures() as $index => $failure) {
            $failures[$index]['row'] = $failure->row(); // row that went wrong
            $failures[$index]['attribute'] = $failure->attribute(); // either heading key (if using heading row concern) or column index
            $failures[$index]['error'] = $failure->errors(); // Actual error messages from Laravel validator
        }

        $countFailures = count($failures);
        
        //dd($countFailures, $import->getRowCount());

        if ($countFailures > 0)
        {
            if ($import->getRowCount() == 0)
            {
                $file->status = 'FAILED';
                $file->save();
                return redirect('contacts')->with('failed', 'File Failed. 0 Contacts imported.');
            }
            
            $file->status = 'FINISHED';
            $file->save();
            return redirect('contacts')->with('failures', $failures);
        }

        $file->status = 'FINISHED';
        $file->save();

        return redirect('contacts')->with('success', 'File succesfully imported!');
    }
}
