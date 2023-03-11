<?php

namespace App\Http\Controllers\gov_case;
use App\Http\Controllers\Controller;
use App\Models\gov_case\GovCaseDivision;
use App\Models\gov_case\GovCaseOffice;
use App\Models\gov_case\GovCaseDivisionCategory;
use App\Models\gov_case\GovCaseDivisionCategoryType;
use App\Models\gov_case\GovCaseOfficeType;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class GovCaseSettingsController extends Controller
{
   public function div_category_index(){
   		$data['categories'] = GovCaseDivisionCategory::orderby('id','DESC')->paginate(10);
        $data['page_title'] =   'মামলার ক্যাটেগরির তালিকা';
        return view('gov_case.settings.category_list')->with($data);
   }
   public function div_category_add(){
   		$data['govCaseDiv'] = GovCaseDivision::orderby('id','DESC')->get();
        $data['page_title'] =   'মামলার ক্যাটেগরি এন্ট্রি ফর্ম';
        return view('gov_case.settings.category_create')->with($data);
   }
   public function div_category_store(Request $request){
   		$id = $request->category_id;
   		$request->validate([
            'name_bn' => 'required',
            'name_en' => 'required',
            'gov_case_division_id' => 'required',
            'status' => 'required',
        ]);
        DB::table('gov_case_division_categories')->insert([
           'name_bn' => $request->name_bn,
           'name_en' => $request->name_en,
           'gov_case_division_id' => $request->gov_case_division_id,
           'status' => $request->status,
        ]);
        return redirect()->route('cabinet.settings.category.list')->with('success', 'মামলার ক্যাটেগরির তথ্য সফল ভাবে সংরক্ষণ করা হয়েছে');;
   }
   public function div_category_edit($id){
   		$data['category'] = GovCaseDivisionCategory::where('id',$id)->orderby('id','DESC')->first();
   		$data['govCaseDiv'] = GovCaseDivision::orderby('id','DESC')->get();
        $data['page_title'] =   'মামলার ক্যাটেগরি হালনাগাদ ফর্ম';
        return view('gov_case.settings.category_edit')->with($data);
   }
   public function div_category_update(Request $request){
   		$id = $request->category_id;
   		$request->validate([
            'name_bn' => 'required',
            'name_en' => 'required',
            'gov_case_division_id' => 'required',
            'status' => 'required',
        ]);
        $data = [
           'name_bn' => $request->name_bn,
           'name_en' => $request->name_en,
           'gov_case_division_id' => $request->gov_case_division_id,
           'status' => $request->status,
        ];

        $ID = DB::table('gov_case_division_categories')
                ->where('id', $id)
                ->update($data);
        return redirect()->route('cabinet.settings.category.list')->with('success', 'মামলার ক্যাটেগরির তথ্য সফল ভাবে হালনাগাদ করা হয়েছে');;
   }
   public function div_category_type_index(){
   		$data['categories'] = GovCaseDivisionCategoryType::orderby('id','DESC')->paginate(10);
        $data['page_title'] =   'মামলার শ্রেণীর তালিকা';
        return view('gov_case.settings.category_type_list')->with($data);
   }
   public function div_category_type_add(){
   		$data['govCaseDivCat'] = GovCaseDivisionCategory::orderby('id','DESC')->get();
        $data['page_title'] =   'মামলার শ্রেণী এন্ট্রি ফর্ম';
        return view('gov_case.settings.category_type_create')->with($data);
   }
   public function div_category_type_store(Request $request){
   		$id = $request->category_id;
   		$request->validate([
            'name_bn' => 'required',
            'name_en' => 'required',
            'gov_case_category_id' => 'required',
            'status' => 'required',
        ]);
        DB::table('gov_case_division_categories_types')->insert([
           'name_bn' => $request->name_bn,
           'name_en' => $request->name_en,
           'gov_case_category_id' => $request->gov_case_category_id,
           'status' => $request->status,
        ]);
        return redirect()->route('cabinet.settings.category_type.list')->with('success', 'মামলার শ্রেণীর তথ্য সফল ভাবে সংরক্ষণ করা হয়েছে');;
   }
   public function div_category_type_edit($id){
   		$data['category_type'] = GovCaseDivisionCategoryType::where('id',$id)->orderby('id','DESC')->first();
   		$data['govCaseDivCat'] = GovCaseDivisionCategory::orderby('id','DESC')->get();
        $data['page_title'] =   'মামলার শ্রেণী হালনাগাদ ফর্ম';
        return view('gov_case.settings.category_type_edit')->with($data);
   }
   public function div_category_type_update(Request $request){
   		$id = $request->category_id;
   		$request->validate([
            'name_bn' => 'required',
            'name_en' => 'required',
            'gov_case_category_id' => 'required',
            'status' => 'required',
        ]);
        $data = [
           'name_bn' => $request->name_bn,
           'name_en' => $request->name_en,
           'gov_case_category_id' => $request->gov_case_category_id,
           'status' => $request->status,
        ];

        $ID = DB::table('gov_case_division_categories_types')
                ->where('id', $id)
                ->update($data);
        return redirect()->route('cabinet.settings.category_type.list')->with('success', 'মামলার শ্রেণীর তথ্য সফল ভাবে হালনাগাদ করা হয়েছে');;
   }
   public function office_type_index(){
   		$data['office_types'] = GovCaseOfficeType::orderby('id','DESC')->paginate(10);
        $data['page_title'] =   'অফিসের শ্রেণীর তালিকা';
        return view('gov_case.settings.office_type_list')->with($data);
   }
   public function office_type_add(){
        $data['page_title'] =   'অফিসের শ্রেণী এন্ট্রি ফর্ম';
        return view('gov_case.settings.office_type_create')->with($data);
   }
   public function office_type_store(Request $request){
   		$id = $request->category_id;
   		$request->validate([
            'type_name_bn' => 'required',
            'type_name' => 'required',
        ]);
        DB::table('gov_case_division_categories_types')->insert([
           'type_name_bn' => $request->type_name_bn,
           'type_name' => $request->type_name,
        ]);
        return redirect()->route('cabinet.settings.office_type.list')->with('success', 'অফিসের শ্রেণীর তথ্য সফল ভাবে সংরক্ষণ করা হয়েছে');;
   }
   public function office_type_edit($id){
   		$data['office_type'] = GovCaseOfficeType::where('id',$id)->orderby('id','DESC')->first();
        $data['page_title'] =   'অফিসের শ্রেণী হালনাগাদ ফর্ম';
        return view('gov_case.settings.office_type_edit')->with($data);
   }
   public function office_type_update(Request $request){
   		$id = $request->office_type_id;
   		$request->validate([
            'type_name_bn' => 'required',
            'type_name' => 'required',
        ]);
        $data = [
           'type_name_bn' => $request->type_name_bn,
           'type_name' => $request->type_name,
        ];

        $ID = DB::table('gov_case_office_type')
                ->where('id', $id)
                ->update($data);
        return redirect()->route('cabinet.settings.office_type.list')->with('success', 'অফিসের শ্রেণীর তথ্য সফল ভাবে হালনাগাদ করা হয়েছে');;
   }
}
