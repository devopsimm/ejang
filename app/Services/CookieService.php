<?php
namespace App\Services;

use Illuminate\Support\Facades\Cookie;

class CookieService
{
    public function setCookie($name, $value, $minutes = 60)
    {
        return Cookie::queue($name, $value, $minutes);
    }

    public function getCookie($name)
    {
        return Cookie::get($name);
    }

    public function checkCookie($name)
    {
        return Cookie::has($name);
    }

    public function deleteCookie($name)
    {
        return Cookie::queue(Cookie::forget($name));
    }

    public function getAllCookies()
    {
        return $_COOKIE;
    }
}
