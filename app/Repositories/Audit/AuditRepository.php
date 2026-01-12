<?php

namespace App\Repositories\Audit;

use App\Repositories\BaseRepository;
use Spatie\Activitylog\Models\Activity;

class AuditRepository extends BaseRepository
{
    const MODEL = Activity::class;
    public function getAllForDt() {
        return Activity::with(['subject'])->select('activity_log.*');
    }

    public function getMyLogsForDt() {
        return Activity::where('causer_id', user_id())->with(['subject', 'causer'])->select('activity_log.*');
    }

    public function getLatestLogs() {
        return [
            'logs' => Activity::with('causer')->latest()->limit(5)->get(),
        ];
    }
}
