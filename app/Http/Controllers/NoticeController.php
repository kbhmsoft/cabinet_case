<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use App\Http\Requests\StoreNoticeRequest;
use App\Http\Requests\UpdateNoticeRequest;
use App\Http\Resources\NoticeResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NoticeController extends Controller
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
        // Assuming you have some default data to pass to the view
        $data = [
            'notice_pdf' => null, // or get the existing PDF data from your database
        ];

        return view('notice.create', compact('data'));
    }

    public function store(StoreNoticeRequest $request)
    {
        $notice_pdf_path = '';

        if ($request->hasFile('notice_pdf')) {

            $notice_pdf_path = $request->file('notice_pdf')->store('notice', 'public');
        }

        $data = [
            'title'         => $request->title,
            'notice_pdf'    => $notice_pdf_path,
            'date'          => $request->date,
            'status'        => $request->status,
        ];

        $notice = Notice::create($data);

        if (!$notice) {
            return redirect()->back()->with('error', 'বিজ্ঞপ্তি তৈরি করার সমস্যা ছিল৷');
        }

        return redirect()->route('notices.index')->with('success', 'সফলভাবে, আপনার বিজ্ঞপ্তি তৈরি করা হয়েছে।');
    }


    public function show($id)
    {
        $notices = Notice::latest()->paginate(10);

        return view('notice.show', compact('notices'));
    }


    public function edit(Notice $notice)
    {
        $data = $notice;
        return view('notice.edit', compact('data'));
    }



    public function update(UpdateNoticeRequest $request, Notice $notice)
    {
        // Retrieve the current PDF path
        $currentPdfPath = $notice->notice_pdf;

        if ($request->hasFile('notice_pdf')) {
            // Remove the old file first
            Storage::delete($currentPdfPath);

            // Upload and store the new PDF
            $newPdfPath = $request->file('notice_pdf')->store('notice', 'public');
        } else {
            // Keep the current PDF path if no new file is uploaded
            $newPdfPath = $currentPdfPath;
        }

        // Update the notice attributes
        $notice->update([
            'title'         => $request->title,
            'notice_pdf'    => $newPdfPath,
            'date'          => $request->date,
            'status'        => $request->status,
        ]);

        // Check if the update was successful
        if ($notice->wasChanged()) {
            return redirect()->route('notices.index')->with('success', 'সফলভাবে, আপনার বিজ্ঞপ্তি আপডেট করা হয়েছে।');
        } else {
            return redirect()->back()->with('error', 'দুঃখিত, বিজ্ঞপ্তি আপডেট করার সময় একটি সমস্যা হয়েছে।');
        }
    }

    public function destroy($id)
    {
        $resource = Notice::findOrFail($id);
        $resource->delete();

        return redirect()->route('notices.index')->with('সাফল্য', 'বিজ্ঞপ্তি সফলভাবে মুছে ফেলা হয়েছে');
    }
}