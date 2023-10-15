<?php

namespace App\Http\Controllers\gov_case;

use App\Http\Controllers\Controller;
use App\Models\CaseRegister;
use App\Models\District;
use App\Models\gov_case\GovCaseOffice;
use App\Models\gov_case\GovCaseOfficeType;
use App\Models\Message;
use App\Models\Office;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GovCaseMessageController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:recent_messages', ['only' => ['messages_recent']]);

    }

    public function messages()
    {
        //     $roleID = Auth::user()->role_id;
        //     $officeInfo = user_office_info();

        //     if($roleID == 1 || $roleID == 2 || $roleID == 3 || $roleID == 4 ){
        //         $users= DB::table('users')
        //             ->orderBy('id','DESC')
        //             ->join('roles', 'users.role_id', '=', 'roles.id')
        //             ->join('office', 'users.office_id', '=', 'office.id')
        //             ->select('users.*', 'roles.name', 'office.office_name_bn')
        //             ->where('users.is_gov', 1);
        //             // ->paginate(10);
        //     }else{
        //         $users= DB::table('users')
        //             ->orderBy('id','DESC')
        //             ->join('roles', 'users.role_id', '=', 'roles.id')
        //             ->join('office', 'users.office_id', '=', 'office.id')
        //             ->select('users.*', 'roles.name', 'office.office_name_bn')
        //             ->where('users.is_gov', 1);
        //             // ->paginate(10);
        //     }

        //     // ?division=3&district=38&upazila=121

        //     if(!empty($_GET['division'])) {
        //         $users->where('office.division_id','=',$_GET['division']);
        //     }
        //     if(!empty($_GET['district'])) {
        //         $users->where('office.district_id','=',$_GET['district']);
        //     }
        //     if(!empty($_GET['upazila'])) {
        //         $users->where('office.upazila_id','=',$_GET['upazila']);
        //     }

        //     // return $users->toSql();
        //     $users = $users->paginate(10);
        //     $page_title = 'ব্যবহারকারীর তালিকা';
        //    // return $users;
        //     return view('gov_case.messages.list', compact('page_title','users'))
        //     ->with('i', (request()->input('page',1) - 1) * 10);

        session()->forget('currentUrlPath');
        session()->put('currentUrlPath', request()->path());

        $role = array('1', '27');
        $roleID = Auth::user()->role_id;
        $officeInfo = user_office_info();
        $data['office_types'] = GovCaseOfficeType::orderby('id', 'ASC')->get();

        //Add Conditions
        $query = DB::table('users')->orderBy('id', 'DESC')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->join('gov_case_office', 'users.office_id', '=', 'gov_case_office.id')
            ->select('users.*', 'roles.name as roleName', 'gov_case_office.office_name_bn')
            ->where('users.is_gov', 1);

        if (!empty($_GET['office_id'])) {
            $query->where('users.office_id', '=', $_GET['office_id']);
        }
        if (!empty($_GET['role'])) {
            $query->where('users.role_id', '=', $_GET['role']);
        }

        $data['users'] = $query->paginate(10)->withQueryString();

        $data['user_role'] = DB::table('roles')->select('id', 'name')
            ->whereNotIn('id', $role)
            ->where('is_gov', 1)
            ->orderBy('sort_order', 'ASC')
            ->get();

        $data['ministries'] = GovCaseOffice::where('level', 1)->get();
        $data['divOffices'] = GovCaseOffice::where('level', 3)->get();
        // return $data;
        $data['page_title'] = 'ব্যাবহারকারীর তালিকা';

        return view('gov_case.messages.list')
            ->with($data);

    }
    public function messages_recent()
    {

        $user = Auth::user();

        $msgs = Message::select(DB::raw('id, user_sender, user_receiver, max(id) as mid'))
            ->orderby('mid', 'DESC')
            ->where('user_sender', [Auth::user()->id])
            ->orWhere('user_receiver', [Auth::user()->id])
            ->Where('msg_reqest', 0)
            ->groupby(['user_receiver', 'user_sender'])
            ->get();

        // $query = DB::table('messages')
        // ->select(DB::raw('id, user_sender, user_receiver, max(id) as mid', 'roles.name as roleName', 'gov_case_office.office_name_bn'))
        // ->orderBy('mid', 'DESC')
        // ->join('roles', 'users.role_id', '=', 'roles.id')
        // ->join('gov_case_office', 'users.office_id', '=', 'gov_case_office.id')
        // ->select('users.*', 'roles.name as roleName', 'gov_case_office.office_name_bn')
        // ->where('users.is_gov', 1);

// return  $msgs;

        $arr = [];
        foreach ($msgs as $mes) {
            if (in_array($mes->user_sender, $arr) || in_array($mes->user_receiver, $arr)) {
                continue;
            } else {
                if ($mes->user_sender == Auth::user()->id) {
                    array_push($arr, $mes->user_receiver);
                } else {
                    array_push($arr, $mes->user_sender);
                }
            }
        }

        $data['users'] = DB::table('users')
            ->whereIn('id', $arr)
            ->orderByRaw(DB::raw('FIELD(id,' . implode(",", $arr) . ')'))
            // ->join('roles', 'users.role_id', '=', 'roles.id')
            // ->join('gov_case_office', 'users.office_id', '=', 'gov_case_office.id')
            // ->select('users.*', 'roles.name as roleName', 'gov_case_office.office_name_bn')
            ->paginate(15);
        //    return $data['users'];
        $data['page_title'] = 'সাম্প্রতিক বার্তা';

        return view('gov_case.messages.recent')->with($data)
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function messages_request()
    {
        $data['msg_request'] = Message::orderby('id', 'DESC')
        // ->select('user_sender', 'user_receiver', 'msg_reqest')
            ->Where('user_receiver', [Auth::user()->id])
            ->Where('msg_reqest', 1)
            ->groupby('user_sender')
            ->paginate(15);

        $data['page_title'] = 'নতুন বার্তা অনুরোধ';
        // return $data;

        return view('gov_case.messages.request')->with($data)
            ->with('i', (request()->input('page', 1) - 1) * 10);

        // return view('gov_case.messages.single', compact('page_title','user', 'messages'));
    }
    public function messages_single(Request $request, $user_id)
    {
        // return 'minar';
        $data['user'] = User::findOrFail($user_id);
        $data['messages'] = Message::orderby('id', 'DESC')
            ->whereIn('user_sender', [Auth::user()->id, $user_id])
            ->whereIn('user_receiver', [Auth::user()->id, $user_id])
            ->paginate(20);
        if ($request->ajax()) {
            $returnHTML = view('gov_case.messages.ajaxMsg')->with($data)->render();
            return response()->json($returnHTML, 200);
            // return response()->json(['success'=>'Data is successfully added','sfdata'=>'Data is successfully added', 'html' => $returnHTML]);

            // return $returnHTML;
        }
        $msgSeen = Message::orderby('id', 'DESC')
            ->select('id', 'receiver_seen', 'seen_at')
            ->where('user_sender', $user_id)
            ->where('user_receiver', Auth::user()->id)
            ->where('receiver_seen', 0)
            ->get();

        if (count($msgSeen) != 0) {
            foreach ($msgSeen as $msgSee) {
                $msg = Message::findOrFail($msgSee->id);
                $msg->receiver_seen = 1;
                $msg->seen_at = Carbon::now()->toDateTimeString();
                $msg->save();
            }
        }

        $data['page_title'] = 'বার্তা বিনিময়';

        return view('gov_case.messages.single')->with($data);
    }

    public function messages_remove($message_id)
    {
        $messages = Message::findOrFail($message_id);
        $messages->msg_remove = 1;
        $messages->save();

        return redirect()->back()->with(['success' => 'আপনার বার্তাটি সফলভাবে রিমুভ করা হয়েছে']);
    }

    public function messages_send(Request $request)
    {
        // return $request->all();
        $validator = Validator::make($request->all(), [
            'messages' => 'required',
        ],
            [
                'messages.required' => 'বার্তা তৈরী করুন!',
            ]);

        if ($validator->fails()) {
            return redirect()->back()->with(['error' => $validator->errors()->first()]);
        }

        //save message
        foreach ($request->receiver as $receiver) {
            //find old msg request if have
            $OldMsgReq = Message::where('user_sender', $receiver)
                ->where('user_receiver', Auth::user()->id)
                ->where('msg_reqest', 1)
                ->get();
            //update old msg request to - not msg request
            if (count($OldMsgReq) != 0) {
                foreach ($OldMsgReq as $oMsg) {
                    $msg = Message::findOrFail($oMsg->id);
                    $msg->msg_reqest = 0;
                    $msg->save();
                }
            }
            //check is msg request?
            $IsMsgReq = Message::where('user_sender', $receiver)
                ->where('user_receiver', Auth::user()->id)
                ->first();
            //save new message
            $message = new Message();
            $message->messages = $request->messages;
            $message->user_sender = Auth::user()->id;
            $message->user_receiver = $receiver;
            $message->msg_reqest = $IsMsgReq != null ? 0 : 1;
            $message->ip_info = request()->ip();
            $message->save();
        }
        if ($request->case_id) {
            return redirect()->route('case.details', $request->case_id)->with(['success' => 'আপনার বার্তাটি সফলভাবে পাঠানো হয়েছে']);
        }
        return redirect()->back()->with(['success' => 'আপনার বার্তাটি সফলভাবে পাঠানো হয়েছে']);
    }

    public function messages_group(Request $request)
    {
        $case_id = $request->c;
        $case = CaseRegister::findOrFail($case_id);
        $data['users'] = User::with('office')
            ->whereHas('office', function ($query) use ($case) {
                // $query->where('id', 7860);
                $query->where('district_id', $case->district_id);
                // $query->where('upazila_id', $case->upazila_id);
            })
            ->get();

        $data['page_title'] = 'গ্রুপ বার্তা বিনিময়';
        return view('gov_case.messages.group')->with($data);
    }

    public function script(Request $request)
    {
        $mk = 'khalid';

        $offices = Office::where('office_name_bn', 'জেলা প্রশাসকের কার্যালয়')->get();
        foreach ($offices as $office) {
            $office->office_name_bn = 'জেলা প্রশাসকের কার্যালয়, ' . $office->district->district_name_bn;
            if ($office->save()) {
                $mk = 'Success';
            }
        }

        // $divs = Division::all();
        // foreach($divs as $div){
        //    $office = new Office();
        //    $office->division_id = $div->id;
        //    $office->district_id = null;
        //    $office->upazila_id = null;
        //    $office->level = 2;
        //    $office->office_name_bn = 'বিভাগীয় ভূমি কমিশনারের কার্যালয়, ' . $div->division_name_bn;
        //    $office->status = 1;
        //    if($office->save()){
        //         $mk = 'Success';
        //    }
        // }

        return $mk;
    }
}
