<?php
/**
 * SVG Icon Helper
 * Returns SVG icons as strings for inline use
 */

class IconHelper
{
    /**
     * Book icon for Books category
     */
    public static function book(string $class = ''): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '">' .
               '<path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/>' .
               '</svg>';
    }

    /**
     * Film icon for Movies category
     */
    public static function film(string $class = ''): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '">' .
               '<rect width="18" height="18" x="3" y="3" rx="2"/>' .
               '<path d="M7 3v18"/>' .
               '<path d="M3 7.5h4"/>' .
               '<path d="M3 12h18"/>' .
               '<path d="M3 16.5h4"/>' .
               '<path d="M17 3v18"/>' .
               '<path d="M17 7.5h4"/>' .
               '<path d="M17 16.5h4"/>' .
               '</svg>';
    }

    /**
     * Music icon for Music category
     */
    public static function music(string $class = ''): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '">' .
               '<path d="M9 18V5l12-2v13"/>' .
               '<circle cx="6" cy="18" r="3"/>' .
               '<circle cx="18" cy="16" r="3"/>' .
               '</svg>';
    }

    /**
     * Bell icon for notifications
     */
    public static function bell(string $class = ''): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '">' .
               '<path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>' .
               '<path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>' .
               '</svg>';
    }

    /**
     * Message square icon for Suggestions
     */
    public static function messageSquare(string $class = ''): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '">' .
               '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>' .
               '</svg>';
    }

    /**
     * User icon for account
     */
    public static function user(string $class = ''): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '">' .
               '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>' .
               '<circle cx="12" cy="7" r="4"/>' .
               '</svg>';
    }

    /**
     * Log in icon
     */
    public static function logIn(string $class = ''): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '">' .
               '<path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>' .
               '<polyline points="10 17 15 12 10 7"/>' .
               '<line x1="15" x2="3" y1="12" y2="12"/>' .
               '</svg>';
    }

    /**
     * Log out icon
     */
    public static function logOut(string $class = ''): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '">' .
               '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>' .
               '<polyline points="16 17 21 12 16 7"/>' .
               '<line x1="21" x2="9" y1="12" y2="12"/>' .
               '</svg>';
    }

    /**
     * User plus icon for register
     */
    public static function userPlus(string $class = ''): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '">' .
               '<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>' .
               '<circle cx="8.5" cy="7" r="4"/>' .
               '<line x1="20" x2="20" y1="8" y2="14"/>' .
               '<line x1="23" x2="17" y1="11" y2="11"/>' .
               '</svg>';
    }

    /**
     * Mail icon for email
     */
    public static function mail(string $class = ''): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '">' .
               '<rect width="20" height="16" x="2" y="4" rx="2"/>' .
               '<path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>' .
               '</svg>';
    }

    /**
     * Lock icon for password
     */
    public static function lock(string $class = ''): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '">' .
               '<rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>' .
               '<path d="M7 11V7a5 5 0 0 1 10 0v4"/>' .
               '</svg>';
    }

    /**
     * User circle icon for username
     */
    public static function userCircle(string $class = ''): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '">' .
               '<circle cx="12" cy="12" r="10"/>' .
               '<circle cx="12" cy="10" r="3"/>' .
               '<path d="M7 20.662V19a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v1.662"/>' .
               '</svg>';
    }

    /**
     * Library icon for Media Library branding
     */
    public static function library(string $class = ''): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '">' .
               '<path d="m16 6 4 14"/>' .
               '<path d="M12 6v14"/>' .
               '<path d="M8 8v12"/>' .
               '<path d="M4 4v16"/>' .
               '</svg>';
    }

    /**
     * Eye icon for show password
     */
    public static function eye(string $class = ''): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '">' .
               '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>' .
               '<circle cx="12" cy="12" r="3"/>' .
               '</svg>';
    }

    /**
     * Eye off icon for hide password
     */
    public static function eyeOff(string $class = ''): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '">' .
               '<path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/>' .
               '<path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/>' .
               '<path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>' .
               '<line x1="2" x2="22" y1="2" y2="22"/>' .
               '</svg>';
    }

    /**
     * Star icon for ratings
     */
    public static function star(string $class = ''): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '">' .
               '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>' .
               '</svg>';
    }

    /**
     * Heart icon for favorites
     */
    public static function heart(string $class = ''): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '">' .
               '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>' .
               '</svg>';
    }

    /**
     * Key icon for password
     */
    public static function key(string $class = ''): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '">' .
               '<circle cx="7.5" cy="15.5" r="5.5"/>' .
               '<path d="m21 2-9.6 9.6"/>' .
               '<path d="m15.5 7.5 3 3L22 7l-3-3"/>' .
               '</svg>';
    }

    /**
     * Settings icon
     */
    public static function settings(string $class = ''): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '">' .
               '<path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.47a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/>' .
               '<circle cx="12" cy="12" r="3"/>' .
               '</svg>';
    }

    /**
     * Search icon
     */
    public static function search(string $class = ''): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '">' .
               '<circle cx="11" cy="11" r="8"/>' .
               '<path d="m21 21-4.3-4.3"/>' .
               '</svg>';
    }

    /**
     * History/clock icon for recently viewed
     */
    public static function history(string $class = ''): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '">' .
               '<path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>' .
               '<path d="M3 3v5h5"/>' .
               '<path d="M12 7v5l4 2"/>' .
               '</svg>';
    }

    /**
     * Bookmark icon for watchlist
     */
    public static function bookmark(string $class = ''): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '">' .
               '<path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/>' .
               '</svg>';
    }

    /**
     * Activity/trending icon
     */
    public static function activity(string $class = ''): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '">' .
               '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>' .
               '</svg>';
    }

    /**
     * Calendar icon for reservations
     */
    public static function calendar(string $class = ''): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '">' .
               '<rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>' .
               '<line x1="16" x2="16" y1="2" y2="6"/>' .
               '<line x1="8" x2="8" y1="2" y2="6"/>' .
               '<line x1="3" x2="21" y1="10" y2="10"/>' .
               '</svg>';
    }

    /**
     * Dollar sign icon for revenue
     */
    public static function dollarSign(string $class = ''): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '">' .
               '<line x1="12" x2="12" y1="2" y2="22"/>' .
               '<path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>' .
               '</svg>';
    }

    /**
     * File text icon for invoices
     */
    public static function fileText(string $class = ''): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '">' .
               '<path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/>' .
               '<polyline points="14 2 14 8 20 8"/>' .
               '<line x1="16" x2="8" y1="13" y2="13"/>' .
               '<line x1="16" x2="8" y1="17" y2="17"/>' .
               '<line x1="10" x2="8" y1="9" y2="9"/>' .
               '</svg>';
    }
}
