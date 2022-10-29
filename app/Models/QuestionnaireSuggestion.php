<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionnaireSuggestion extends Model
{
    use HasFactory;

    protected $fillable = [
        "session_id",
        "service_pack_id",
    ];

    public function session()
    {
        return $this->belongsTo(QuestionnaireSession::class, "session_id", "id");
    }

    public function servicePack()
    {
        return $this->belongsTo(ServicePack::class, "service_pack_id", "id");
    }
}
