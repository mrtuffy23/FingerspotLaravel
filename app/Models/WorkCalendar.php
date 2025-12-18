<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class WorkCalendar extends Model
{
    protected $fillable = ['date','description','type'];
    
    protected $casts = [
        'date' => 'date',
    ];

    public static function isWorkday(Carbon $date)
    {
        $record = self::where('date', $date->toDateString())->first();

        if ($record) {
            return $record->type === 'workday';
        }

        // If no record, check if it's weekend
        return !$date->isWeekend();
    }

    public static function isHoliday(Carbon $date)
    {
        $record = self::where('date', $date->toDateString())->first();

        if ($record) {
            return in_array($record->type, ['national_holiday', 'collective_leave']);
        }

        return false;
    }
}
