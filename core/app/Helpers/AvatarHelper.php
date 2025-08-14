<?php

namespace App\Helpers;

class AvatarHelper
{
    /**
     * Get random avatar for user
     */
    public static function getRandomUserAvatar($userId = null)
    {
        $avatars = [
            'user-1.svg',
            'user-2.svg', 
            'user-3.svg',
            'user-4.svg',
            'user-5.svg',
            'user-6.svg',
            'user-7.svg',
            'user-8.svg',
            'user-9.svg',
            'user-10.svg'
        ];

        // Use user ID to ensure consistent avatar for same user
        $index = $userId ? ($userId % count($avatars)) : array_rand($avatars);
        return asset('assets/images/avatars/' . $avatars[$index]);
    }

    /**
     * Get random avatar for company
     */
    public static function getRandomCompanyAvatar($companyId = null)
    {
        $avatars = [
            'company-1.svg',
            'company-2.svg',
            'company-3.svg', 
            'company-4.svg',
            'company-5.svg',
            'company-6.svg',
            'company-7.svg',
            'company-8.svg',
            'company-9.svg',
            'company-10.svg'
        ];

        // Use company ID to ensure consistent avatar for same company
        $index = $companyId ? ($companyId % count($avatars)) : array_rand($avatars);
        return asset('assets/images/avatars/' . $avatars[$index]);
    }

    /**
     * Generate SVG avatar with initials and random colors
     */
    public static function generateInitialsAvatar($name, $id = null)
    {
        $colors = [
            ['bg' => '#FF6B6B', 'text' => '#FFFFFF'], // Red
            ['bg' => '#4ECDC4', 'text' => '#FFFFFF'], // Teal
            ['bg' => '#45B7D1', 'text' => '#FFFFFF'], // Blue
            ['bg' => '#96CEB4', 'text' => '#FFFFFF'], // Green
            ['bg' => '#FFEAA7', 'text' => '#2D3436'], // Yellow
            ['bg' => '#DDA0DD', 'text' => '#FFFFFF'], // Plum
            ['bg' => '#98D8C8', 'text' => '#2D3436'], // Mint
            ['bg' => '#F7DC6F', 'text' => '#2D3436'], // Light Yellow
            ['bg' => '#BB8FCE', 'text' => '#FFFFFF'], // Light Purple
            ['bg' => '#85C1E9', 'text' => '#2D3436'], // Light Blue
        ];

        // Get initials
        $words = explode(' ', trim($name));
        $initials = '';
        
        if (count($words) >= 2) {
            $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        } else {
            $initials = strtoupper(substr($words[0], 0, 2));
        }

        // Select color based on ID or random
        $colorIndex = $id ? ($id % count($colors)) : array_rand($colors);
        $color = $colors[$colorIndex];

        // Generate SVG
        $svg = '
        <svg width="100" height="100" xmlns="http://www.w3.org/2000/svg">
            <circle cx="50" cy="50" r="50" fill="' . $color['bg'] . '"/>
            <text x="50" y="50" font-family="Arial, sans-serif" font-size="32" font-weight="bold" 
                  text-anchor="middle" dominant-baseline="central" fill="' . $color['text'] . '">
                ' . $initials . '
            </text>
        </svg>';

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    /**
     * Get user avatar with fallback
     */
    public static function getUserAvatar($user)
    {
        // If user has uploaded avatar
        if ($user->image) {
            $rootAssetsPath = base_path('../assets/images/user/profile/' . $user->image);
            if (file_exists($rootAssetsPath)) {
                return asset('assets/images/user/profile/' . $user->image);
            }
        }

        // If user has firstname/lastname, generate initials avatar
        if ($user->firstname || $user->lastname) {
            $name = trim(($user->firstname ?? '') . ' ' . ($user->lastname ?? ''));
            if ($name) {
                return self::generateInitialsAvatar($name, $user->id);
            }
        }

        // If user has username, generate initials avatar
        if ($user->username) {
            return self::generateInitialsAvatar($user->username, $user->id);
        }

        // Fallback to random avatar
        return self::getRandomUserAvatar($user->id);
    }

    /**
     * Get company avatar with fallback
     */
    public static function getCompanyAvatar($company)
    {
        // If company has uploaded image
        if ($company->image) {
            // Try multiple paths for different environments
            $paths = [
                base_path('../assets/images/company/' . $company->image), // Local development
                base_path('public/assets/images/company/' . $company->image), // Laravel public
                public_path('assets/images/company/' . $company->image), // Laravel public helper
                base_path('../../assets/images/company/' . $company->image), // Production relative
            ];
            
            foreach ($paths as $path) {
                if (file_exists($path)) {
                    return asset('assets/images/company/' . $company->image);
                }
            }
            
            // If file doesn't exist locally but we're on production, return the URL directly
            if (app()->environment('production') || app()->environment('staging')) {
                return asset('assets/images/company/' . $company->image);
            }
        }

        // If company has name, generate initials avatar
        if ($company->name) {
            return self::generateInitialsAvatar($company->name, $company->id);
        }

        // Fallback to random company avatar
        return self::getRandomCompanyAvatar($company->id);
    }
} 