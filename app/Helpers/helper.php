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


if (!function_exists('negative_value')) {
    /**
     * @param int|float $value
     * @param $float
     * @return int|float
     */
    function negative_value(int|float $value, $float = false): int|float
    {
        if ($float) {
            $value = (float) $value;
        }
        return 0 - abs($value);
    }
}

if (!function_exists('toHtmlString')) {
    function toHtmlString($html) {
        return new \Illuminate\Support\HtmlString($html);
    }

}

if (!function_exists('html_attributes_implode')) {
    function html_attributes_implode($attributes) {

        if(count($attributes)) {
            return collect($attributes)->map(function ($value, $key) {
                return is_bool($value) ? ($value ? $key : '') : "{$key}=\"" . e($value) . "\"";
            })->implode(' ');
        }

        return null;
    }

}

if (! function_exists('link_to_route')) {
    function link_to_route($name, $title = null, $parameters = [], $attributes = [])
    {
        // Generate the URL using the named route and parameters
        $url = route($name, $parameters);

        // Translate the title if necessary
        $translatedTitle = __($title);

        // Prepare the attributes as a string
        $attributesString = collect($attributes)
            ->map(function ($value, $key) {
                return is_bool($value) ? ($value ? $key : null) : "{$key}=\"{$value}\"";
            })
            ->filter()
            ->implode(' ');

        // Return the complete anchor tag
        return toHtmlString("<a href=\"{$url}\" {$attributesString}>{$translatedTitle}</a>");
    }
}

if (! function_exists('includeRouteFiles')) {
    function includeRouteFiles($folder)
    {
        try {
            $rdi = new recursiveDirectoryIterator($folder);
            $it = new recursiveIteratorIterator($rdi);

            while ($it->valid()) {
                if (! $it->isDot() && $it->isFile() && $it->isReadable() && $it->current()->getExtension() === 'php') {
                    require $it->key();
                }

                $it->next();
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}


if (!function_exists('getFallbackLocale')) {

    /**
     * Get the fallback locale
     *
     * @return \Illuminate\Foundation\Application|mixed
     */
    function getFallbackLocale() {
        return config('app.fallback_locale');
    }

}

if (!function_exists('getLanguageBlock')) {

    /**
     * Get the language block with a fallback
     *
     * @param $view
     * @param array $data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function getLanguageBlock($view, $data = []) {
        $components = explode("lang", $view);
        $current = $components[0] . "lang." . app()->getLocale() . "." . $components[1];
        $fallback = $components[0] . "lang." . getFallbackLocale() . "." . $components[1];

        if (view()->exists($current)) {
            return view($current, $data);
        } else {
            return view($fallback, $data);
        }
    }
}

if (! function_exists('access')) {
    /**
     * Access (lol) the Access:: facade as a simple function.
     */
    function access()
    {
        return app('access');
    }
}

if (! function_exists('sysdef')) {
    /**
     * Access (lol) the Access:: facade as a simple function.
     */
    function sysdef()
    {
        return app('sysdef');
    }
}

if (! function_exists('code_value')) {
    /**
     * Access (lol) the Access:: facade as a simple function.
     */
    function code_value()
    {
        return app('code_value');
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

function isAdmin(){
    return Auth::user()->is_super_admin;
}

function initials() {
    $fullName = userFullName();
    $nameParts = explode(' ', trim($fullName));

    return count($nameParts) > 2
        ? strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1))
        : strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));
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

if (! function_exists('timestamp_date_format')) {
    function timestamp_date_format($date)
    {
        if($date){
            return \Carbon\Carbon::parse($date)->format('Y-m-d H:i:s');
        }else{
            return null;
        }
    }
}
/*Month Year date format D-M-Y*/
if (! function_exists('month_year_date_format')) {
    function month_year_date_format($date)
    {
        if($date){
            return \Carbon\Carbon::parse($date)->format('M-Y');
        }else{
            return '';
        }
    }
}

/*Month Year date format D-M-Y*/
if (! function_exists('month_day_year_date_format')) {
    function month_day_year_date_format($date)
    {

        if($date){
            return \Carbon\Carbon::parse($date)->format('m/d/Y');
        }else{
            return '';
        }
    }
}
/*day month date format*/
if (! function_exists('day_month_date_format')) {
    function day_month_date_format($date)
    {
        if($date){
            return \Carbon\Carbon::parse($date)->format('d-M');
        }else{
            return '';
        }
    }
}

/*Day Month format D-M-Y*/
if (! function_exists('day_name_month_date_format')) {
    function day_name_month_date_format($date)
    {
        if($date){
            return \Carbon\Carbon::parse($date)->format('j-M l');
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
if (! function_exists('get_subday')) {
    function get_subday($date)
    {
        return standard_date_format(\Carbon\Carbon::parse($date)->subDay());
    }
}

if (! function_exists('old_datepicker_format')) {
    function old_datepicker_format($date)
    {
        if($date){
            return \Carbon\Carbon::parse($date)->format('Y-m-d');
        }else{
            return '';
        }
    }
}
/*start date format D-M-Y*/
if (! function_exists('start_of_the_current_month')) {
    function start_of_the_current_month()
    {
        return \Carbon\Carbon::parse(getTodayDate())->startOfMonth();
    }
}

/*end month date format D-M-Y*/
if (! function_exists('end_of_the_current_month')) {
    function end_of_the_current_month()
    {
        return \Carbon\Carbon::parse(getTodayDate())->endOfMonth();
    }
}

/*start date format D-M-Y*/
if (! function_exists('start_of_the_current_year')) {
    function start_of_the_current_year()
    {
        return \Carbon\Carbon::parse(getTodayDate())->startOfYear();
    }
}

/*end month date format D-M-Y*/
if (! function_exists('end_of_the_current_year')) {
    function end_of_the_current_year()
    {
        return \Carbon\Carbon::parse(getTodayDate())->endOfYear();

    }
}

/*start date format D-M-Y*/
if (! function_exists('start_of_the_last_year')) {
    function start_of_the_last_year()
    {
        return \Carbon\Carbon::parse(getTodayDate())->subYearNoOverflow()->startOfYear();

    }
}

/*end month date format D-M-Y*/
if (! function_exists('end_of_the_last_year')) {
    function end_of_the_last_year()
    {
        return \Carbon\Carbon::parse(getTodayDate())->subYearNoOverflow()->endOfYear();
    }
}

if (! function_exists('getTodayDate')) {

    function getTodayDate()
    {
        return \Carbon\Carbon::now()->format('Y-n-j');

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

if (! function_exists("remove_all_white_spaces")) {
    function remove_all_white_spaces($value) {
        $value =  preg_replace('/\s+/', '', $value );
        return $value;
    }
}


if (! function_exists("remove_extra_white_spaces")) {
    function remove_extra_white_spaces($value) {
        $value =  preg_replace('/\s+/', ' ', $value );
        $value = remove_first_this_char($value,' ');
        $value = remove_last_this_char($value,' ');
        return $value;
    }
}

if (! function_exists("remove_all_special_chars")) {
    function remove_all_special_chars($value) {
        $value = str_replace(' ', '-', $value); // Replaces all spaces with hyphens.
        $value =  preg_replace('/[^A-Za-z0-9\-]/', '', $value); // Removes special chars.
        $value = str_replace('-', ' ', $value);
        return $value;
    }
}

if (! function_exists("remove_filter_url")) {
    function remove_filter_url($filterName): string
    {
        $query = request()->query();
        unset($query[$filterName]);
        return url()->current() . (count($query) ? '?' . http_build_query($query) : '');
    }
}

/*Set Side Bar Active link*/
if (!function_exists('setSideBarActive')) {
    function setSideBarActive($path)
    {
        return Request::is($path . '*') ? ' class=nav-expanded nav-active nav-collapsed' :  '';
    }
}

if (!function_exists('setSideBarActiveUrl')) {
    function setSideBarActiveUrl($path)
    {
        $current_url = URL::current();
        return (url($path) == $current_url) ? ' class="nav-expanded nav-active"' :  '';
    }
}

if (!function_exists('setSideBarActiveUrlWithPar')) {
    function setSideBarActiveUrlWithPar($path, $par_name, $par_value)
    {
        $current_url = URL::current();
        $url_par_value = $_GET[$par_name] ?? null;
        return ((url($path) == $current_url) && $url_par_value == $par_value) ? ' class="nav-expanded nav-active"' :  '';
    }
}


if (!function_exists('setSideBarActiveUrlMultiple')) {
    function setSideBarActiveUrlMultiple(array $path_array)
    {
        foreach($path_array as $key => $path)
        {
            if(url($path) == URL::current()){
                return (url($path) == URL::current()) ? ' class="nav-expanded nav-active active"' :  '';
            }
        }
    }
}

if (!function_exists('setSideBarChildActiveUrl')) {
    function setSideBarChildActiveUrl($parent_menu)
    {
        $current_url = URL::current();
        switch(($parent_menu) )
        {
            case 'donor':
                if($current_url == url('donor/dashboard')) {
                    return  ' nav-expanded nav-active"';
                }
                break;

            default:
                return '';
                break;
        }
    }
}

if (!function_exists('isActive')) {
    function isActive($pattern) {
        return request()->routeIs($pattern) ? 'active' : '';
    }
}

if (!function_exists('isShow')) {
    function isShow($pattern) {
        return request()->routeIs($pattern) ? 'show' : '';
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
            return '<span class="badge bg-primary text-white">'.__('label.yes').'</span>';
        }
        return '<span class="badge bg-danger text-white">'.__('label.no').'</span>';
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


/**
 * Convert a number to its word equivalent (e.g. 1234 => "one thousand two hundred thirty-four").
 */
if (!function_exists('number_to_words')) {
    function number_to_words($number)
    {
        $number = floatval($number);

        // Separate whole and decimal parts
        $whole = floor($number);
        $decimal = round(($number - $whole) * 100);

        $formatter = new \NumberFormatter('en', \NumberFormatter::SPELLOUT);
        $words = $formatter->format($whole);

        // Handle decimal part (like cents)
        if ($decimal > 0) {
            $words .= ' and ' . $formatter->format($decimal) . ' cents';
        }

        return trim($words);
    }
}
