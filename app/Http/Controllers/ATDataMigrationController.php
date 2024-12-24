<?php

namespace App\Http\Controllers;

use App\Http\Resources\NoticeResource;
use App\Imports\GovCaseImport;
use App\Models\Notice;
use App\Repositories\gov_case\GovCaseRegisterRepository;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ATDataMigrationController extends Controller
{
    public function create()
    {
        $data = [
            'data_migration_file' => null,
        ];
        $data['page_title'] = 'প্রশাসনিক ট্রাইব্যুনাল ডাটা মাইগ্রেশন ফাইল এন্ট্রি';
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
                    // dd(array_combine($importData[0][0], $row));
                    $caseId = GovCaseRegisterRepository::storeDataMigrationGovCase(array_combine($importData[0][0], $row));
                    GovCaseRegisterRepository::storeDataMigrationBadi(array_combine($importData[0][0], $row), $caseId);
                    GovCaseRegisterRepository::storeDataMigrationMainBibadi(array_combine($importData[0][0], $row), $caseId);

                }
            }

            return redirect()->route('dashboard')->with('success', 'Data has been successfully migrated.');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Data migration failed: ' . $e->getMessage()], 500);
        }
    }

}
