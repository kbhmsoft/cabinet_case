<?php

namespace App\Http\Controllers;

use App\Imports\GovCaseImport;
use App\Repositories\gov_case\AdministrativeTribrunalRepository;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AATDataMigrationController extends Controller
{
    public function create()
    {
        $data = [
            'data_migration_file' => null,
        ];
        $data['page_title'] = 'আপিল প্রশাসনিক ট্রাইব্যুনাল ডাটা মাইগ্রেশন ফাইল এন্ট্রি';
        return view('gov_case.data-migration.at_create', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'data_migration_file' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('data_migration_file');

        try {
            $importData = Excel::toArray(new GovCaseImport, $file);

            foreach ($importData[0] as $key => $row) {
                if ($key === 0) {
                    continue;
                }
                if (is_array($row) && !empty(array_filter($row))) {
                    $caseId = AdministrativeTribrunalRepository::storeDataMigrationGovCase(array_combine($importData[0][0], $row));
                    AdministrativeTribrunalRepository::storeDataMigrationBadi(array_combine($importData[0][0], $row), $caseId);
                    AdministrativeTribrunalRepository::storeDataMigrationMainBibadi(array_combine($importData[0][0], $row), $caseId);
                }
            }

            return redirect()->route('dashboard')->with('success', 'Data has been successfully migrated.');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Data migration failed: ' . $e->getMessage()], 500);
        }
    }

}
