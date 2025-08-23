<?php

if (!function_exists('diffForHumansVi')) {
    /**
     * Convert diffForHumans to Vietnamese
     * 
     * @param \Carbon\Carbon $date
     * @return string
     */
    function diffForHumansVi($date)
    {
        try {
            // Thử sử dụng locale vi trước
            if (method_exists($date, 'locale')) {
                $result = $date->locale('vi')->diffForHumans();
                
                // Nếu vẫn hiển thị tiếng Anh, chuyển đổi manual
                if (preg_match('/\b(ago|from now|second|minute|hour|day|week|month|year)\b/i', $result)) {
                    return convertTimeToVietnamese($result);
                }
                
                return $result;
            }
        } catch (Exception $e) {
            // Fallback to manual conversion
        }
        
        // Manual conversion
        $now = now();
        $diffInSeconds = $now->diffInSeconds($date, false);
        $diffInMinutes = $now->diffInMinutes($date, false);
        $diffInHours = $now->diffInHours($date, false);
        $diffInDays = $now->diffInDays($date, false);
        $diffInWeeks = $now->diffInWeeks($date, false);
        $diffInMonths = $now->diffInMonths($date, false);
        $diffInYears = $now->diffInYears($date, false);
        
        $prefix = $diffInSeconds < 0 ? '' : 'sau ';
        $suffix = $diffInSeconds < 0 ? ' trước' : '';
        
        $absDiff = abs($diffInSeconds);
        $absMinutes = abs($diffInMinutes);
        $absHours = abs($diffInHours);
        $absDays = abs($diffInDays);
        $absWeeks = abs($diffInWeeks);
        $absMonths = abs($diffInMonths);
        $absYears = abs($diffInYears);
        
        if ($absDiff < 60) {
            return $prefix . 'vài giây' . $suffix;
        } elseif ($absMinutes < 60) {
            return $prefix . $absMinutes . ' phút' . $suffix;
        } elseif ($absHours < 24) {
            return $prefix . $absHours . ' giờ' . $suffix;
        } elseif ($absDays < 7) {
            return $prefix . $absDays . ' ngày' . $suffix;
        } elseif ($absWeeks < 4) {
            return $prefix . $absWeeks . ' tuần' . $suffix;
        } elseif ($absMonths < 12) {
            return $prefix . $absMonths . ' tháng' . $suffix;
        } else {
            return $prefix . $absYears . ' năm' . $suffix;
        }
    }
}

if (!function_exists('convertTimeToVietnamese')) {
    /**
     * Convert English time strings to Vietnamese
     * 
     * @param string $timeString
     * @return string
     */
    function convertTimeToVietnamese($timeString)
    {
        $replacements = [
            // Time units
            'second' => 'giây',
            'seconds' => 'giây', 
            'minute' => 'phút',
            'minutes' => 'phút',
            'hour' => 'giờ',
            'hours' => 'giờ',
            'day' => 'ngày',
            'days' => 'ngày',
            'week' => 'tuần',
            'weeks' => 'tuần',
            'month' => 'tháng',
            'months' => 'tháng',
            'year' => 'năm',
            'years' => 'năm',
            
            // Time indicators
            'ago' => 'trước',
            'from now' => 'sau',
            'in' => 'sau',
            
            // Articles
            'a ' => '1 ',
            'an ' => '1 ',
            
            // Numbers in words
            'one' => '1',
            'two' => '2',
            'three' => '3',
            'four' => '4',
            'five' => '5',
            'six' => '6',
            'seven' => '7',
            'eight' => '8',
            'nine' => '9',
            'ten' => '10',
        ];
        
        foreach ($replacements as $english => $vietnamese) {
            $timeString = str_ireplace($english, $vietnamese, $timeString);
        }
        
        // Clean up extra spaces
        $timeString = preg_replace('/\s+/', ' ', $timeString);
        $timeString = trim($timeString);
        
        return $timeString;
    }
}
?> 