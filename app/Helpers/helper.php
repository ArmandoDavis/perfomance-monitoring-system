<?php
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

if (!function_exists('str_unique')) {
    /**
     * @param int $length
     * @return string
     */
    function str_unique(int $length = 30): string
    {
        $side = rand(0,1);
        $salt = rand(0,9);
        $len = $length - 1;
        $string = \Illuminate\Support\Str::random($len <= 0 ? 7 : $len);
        $separatorPos = (int) ceil($length/4);
        $string = $side === 0 ? ($salt . $string) : ($string . $salt);
        $string = substr_replace($string, '-', $separatorPos, 0);
        return substr_replace($string, '-', -$separatorPos, 0);
    }
}

function user_id(){
    return optional(Auth::user())->id;
}

if (!function_exists('userFullName')) {
    function userFullName() {
        return Auth::user()->name;
    }
}

if (!function_exists('user')) {
    function user() {
        return auth()->user();
    }
}


/*short date format D-M-Y*/
if (! function_exists('short_date_format')) {
    function short_date_format($date)
    {
        if($date){
            return \Carbon\Carbon::parse($date)->format('d-M-Y');
        }else{
            return '';
        }
    }
}

if (! function_exists('short_date_format_with_day')) {
    function short_date_format_with_day($date)
    {
        if($date){
            return \Carbon\Carbon::parse($date)->format('d-M-Y l');
        }else{
            return '';
        }

    }
}


/*Standard format date format Y-m-j for storing in the database*/
if (! function_exists('standard_date_format')) {
    function standard_date_format($date)
    {
        if($date){
            return \Carbon\Carbon::parse($date)->format('Y-n-j');
        }else{
            return '';
        }
    }
}

if (! function_exists('time_date_format')) {
    function time_date_format($date)
    {
        if($date){
            return \Carbon\Carbon::parse($date)->format('H:i:s');
        }else{
            return '';
        }
    }
}

if (!function_exists('formatBytes')) {
    function formatBytes($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        return round($bytes / pow(1024, $pow), $precision) . ' ' . $units[$pow];
    }
}

if (!function_exists('getExpenseBadge')) {
    function getExpenseBadge($status)
    {
        if ($status) {
            return '<span class="badge bg-light-success text-success px-3">'.__('Approved').'</span>';
        }
        return '<span class="badge bg-light-warning text-warning px-3">'.__('Pending').'</span>';
    }
}

if (!function_exists('getStatusBadge')) {
    function getStatusBadge(bool $isActive): string
    {
        if ($isActive) {
            return '<span class="badge bg-primary text-white">'.__('Active').'</span>';
        }
        return '<span class="badge bg-danger text-white">'.__('Inactive').'</span>';
    }
}

if (!function_exists('getBooleanBadge')) {
    function getBooleanBadge(bool $isActive): string
    {
        if ($isActive) {
            return '<span class="badge bg-primary text-white">'.__('Yes').'</span>';
        }
        return '<span class="badge bg-danger text-white">'.__('No').'</span>';
    }
}

if (!function_exists('getStatusLabelBadge')) {
    function getStatusLabelBadge(?string $status): string
    {
        $color = getStatusBadgeColor($status);
        return '<span class="badge bg-' . $color . ' text-white">' . ucfirst($status) . '</span>';
    }
}

if (!function_exists('getStatusBadgeColor')) {
    function getStatusBadgeColor($status)
    {
        if (!$status) {
            return 'secondary'; // fallback color
        }
        return match (strtolower(trim($status))) {
            'backlog' => 'warning',
            'todo' => 'info',
            'done', 'deployed', 'delivered', 'active' => 'success',
            'in progress' => 'primary',
            'failed', 'expired', 'used_up' => 'danger',
            'submitted' => 'secondary',
            default => 'dark',
        };
    }
}

if (!function_exists('getPriorityBadgeColor')) {
    function getPriorityBadgeColor($priority)
    {
        switch (strtolower($priority)) {
            case 'low':
                return 'success';
            case 'medium':
                return 'warning';
            case 'high':
                return 'danger';
            case 'critical':
                return 'dark';
            default:
                return 'info';
        }
    }
}

if (! function_exists('number_2_format')) {
    function number_2_format($value)
    {
        return  number_format( $value , 2, '.' , ',' );
    }
}
