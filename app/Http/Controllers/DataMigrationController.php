<?php

namespace App\Http\Controllers;

use App\Http\Resources\NoticeResource;
use App\Imports\GovCaseImport;
use App\Models\Notice;
use App\Repositories\gov_case\GovCaseRegisterRepository;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DataMigrationController extends Controller
{
    public function index(Request $request)
    {
        $query = Notice::query();

        if ($request->search) {
            $query->where('title', 'LIKE', "%{$request->search}%")
                ->orWhere('link', 'LIKE', "%{$request->search}%");
        }

        if ($request->wantsJson()) {
            $notices = $query->status()->get();

            if ($notices->isNotEmpty()) {
                return response()->json([
                    'status' => true,
                    'message' => 'তথ্য সফলভাবে দেখায়।',
                    'code' => 200,
                    'data' => NoticeResource::collection($notices),
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'তথ্য পাওয়া যায়নি',
                    'code' => 404,
                    'data' => null,
                ], 404);
            }
        }

        $data = Notice::latest()->paginate(10);
        $rank = $data->firstItem();

        $notice = Notice::latest()->first();
        $latestNotice = Notice::latest()->first(); //notice with latest date

        return view('notice.index', compact('data', 'rank', 'notice', 'latestNotice'));
    }

    public function create()
    {
        $data = [
            'data_migration_file' => null,
        ];

        return view('gov_case.data-migration.create', compact('data'));
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
