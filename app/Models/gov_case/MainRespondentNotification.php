<?php

namespace App\Models\gov_case;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MainRespondentNotification extends Model
{
    use HasFactory;
    protected $table = 'main_respondent_notifications';
    public $timestamps = true;

    protected $fillable = [
       'gov_case_id',
       'case_no',
       'previous_office_id',
       'new_office_id',
       'is_shown'
    ];

    public function officeName(){
        return $this->hasOne(GovCaseOffice::class, 'doptor_office_id', 'new_office_id');
    }




}
