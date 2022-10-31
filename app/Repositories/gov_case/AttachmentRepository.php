<?php
/**
 * Created by PhpStorm.
 * User: destructor
 * Date: 11/29/2017
 * Time: 9:53 PM
 */
namespace App\Repositories\gov_case;

use App\Appeal;
use App\Models\Attachment;
use App\Models\GccAttachment;
use App\Models\CauseList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


class AttachmentRepository
{
    public static function storeAttachment($appName, $caseId, $request)
    {
        if($request->file_name != NULL){
            foreach($request->file_type as $key => $val)
            {
                $filePath = "uploads/" . $appName ."/attachment/";
                if($request->file_name[$key] != NULL){
                    $otherfileName = 'govCaseNo_' . $caseId.'_'.time().'.'.rand(5,9999).'.'.$request->file_name[$key]->extension();
                    $request->file_name[$key]->move(public_path($filePath), $otherfileName);
                }
                $attachment = new Attachment();
                $attachment->gov_case_id = $caseId;
                $attachment->file_type = $request->file_type[$key];
                $attachment->file_name = $filePath.$otherfileName;
                $attachment->file_submission_date = date('Y-m-d H:i:s');
                $attachment->created_at = date('Y-m-d H:i:s');
                $attachment->created_by = userInfo()->id;
                $attachment->updated_at = date('Y-m-d H:i:s');
                $attachment->updated_by = userInfo()->id;
                $attachment->save();
            }
        }
    }
    public static function storeSingleAttachment($path, $file, $caseId)
    {
        if($file != NULL){
            $fileName = $caseId.'_'.time().'_'.rand(5,9999).'.'.$file->extension();
            $file->move(public_path($path), $fileName);
            return $path .'/'. $fileName;
        }
        return null;
    }
    public static function storeSF_SingleAttachment($pathFileName, $caseID)
    {
        $attachment = new Attachment();
        $attachment->gov_case_id = $caseID;
        $attachment->file_type = 'SF';
        $attachment->file_category = 'SF';
        $attachment->file_name = $pathFileName;
        $attachment->file_submission_date = date('Y-m-d H:i:s');
        $attachment->created_at = date('Y-m-d H:i:s');
        $attachment->created_by = userInfo()->id;
        $attachment->updated_at = date('Y-m-d H:i:s');
        $attachment->updated_by = userInfo()->id;
        $attachment->save();
    }

    public static function getAttachmentListByAppealId($appealId)
    {
        $attachmentList=DB::connection('mysql')
            ->table('gcc_cause_lists')
            ->join('gcc_attachments', 'gcc_cause_lists.id', '=', 'gcc_attachments.cause_list_id')
            ->where('gcc_attachments.appeal_id',$appealId )
            ->get();
        return $attachmentList;
    }

    public static function getAttachmentListByAppealIdAndCauseListId($appealId,$causeListId)
    {
        // $attachmentList=DB::connection('appeal')
        $attachmentList=DB::connection('mysql')
            ->table('gcc_cause_lists')
            ->join('gcc_attachments', 'gcc_cause_lists.id', '=', 'gcc_attachments.cause_list_id')
            ->where('gcc_attachments.appeal_id',$appealId )
            ->where('gcc_cause_lists.id',$causeListId )
            ->get();
        return $attachmentList;
    }

    public static function getAttachmentListByPaymentId($paymentId){
        $attachmentList=DB::connection('appeal')
            ->table('attachments')
            ->where('attachments.payment_id',$paymentId )
            ->get();
        return $attachmentList;
    }

    public static function deleteFileByFileID($fileID)
    {
        $attachment=Attachment::find($fileID);
        if ($attachment !== false) {
            if ($attachment->delete() === false) {
                $messages = $attachment->getMessages();
                foreach ($messages as $message) {
                    echo $message, "\n";
                }
                return false;
            } else {
                unlink(public_path($attachment->file_name));
                return true;
            }
        }
    }

    public static function getGUID()
    {
        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        }

        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

    public static function storeAttachmentOnPayment($appName, $appealId, $paymentId, $captions)
    {
        $image = array(".jpg", ".jpeg", ".gif", ".png", ".bmp");
        $document = array(".doc", ".docx");
        $pdf = array(".pdf");
        $excel = array(".xlsx", ".xlsm", ".xltx", ".xltm");
        $text = array(".txt");
        $i = 0;

        foreach ($_FILES["files"]["name"] as $key => $file) {
            $tmp_name = $_FILES["files"]["tmp_name"][$key]['someName'];
            $fileName = $_FILES["files"]["name"][$key]['someName'];
            $fileCategory = $captions[$i]['someCaption'];

            if ($fileName != "" && $fileCategory != null) {
                $fileName = strtolower($fileName);
                $fileExtension = '.' . pathinfo($fileName, PATHINFO_EXTENSION);

                $fileContentType = "";
                if (in_array($fileExtension, $image)) {
                    $fileContentType = 'IMAGE';
                }
                if (in_array($fileExtension, $document)) {
                    $fileContentType = 'DOCUMENT';
                }
                if (in_array($fileExtension, $pdf)) {
                    $fileContentType = 'PDF';
                }
                if (in_array($fileExtension, $excel)) {
                    $fileContentType = 'EXCEL';
                }
                if (in_array($fileExtension, $text)) {
                    $fileContentType = 'TEXT';
                }

                $fileName = self::getGUID() . $fileExtension;
                if ($fileContentType != "") {
                    $caseYear ='APPEAL - '. date('Y');
                    $appealID = 'AppealID - '.$appealId;
                    $causeListID = 'PaymentID - '.$paymentId;

                    $attachmentUrl = config('app.attachmentUrl');

                    $filePath = $attachmentUrl . $appName . '/' . $caseYear  . '/' . $appealID . '/' .$causeListID. '/';
                    if (!is_dir($filePath)) {
                        mkdir($filePath, 0777, true);
                    }
                    $attachment = new Attachment();
                    $attachment->appeal_id = $appealId;
                    $attachment->payment_id = $paymentId;
                    $attachment->file_type = $fileContentType;
                    $attachment->file_category = $fileCategory;
                    $attachment->file_name = $fileName;
                    $attachment->file_path = $appName . '/' . $caseYear . '/' .$appealID. '/' .$causeListID. '/';
                    $attachment->file_submission_date = date('Y-m-d H:i:s');
                    $attachment->created_at = date('Y-m-d H:i:s');
                    $attachment->created_by = Session::get('userInfo')->username;
                    $attachment->updated_at = date('Y-m-d H:i:s');
                    $attachment->updated_by = Session::get('userInfo')->username;
                    $attachment->save();
                    move_uploaded_file($tmp_name, $filePath . $fileName);
                }
            }
            $i++;
        }
    }

}
