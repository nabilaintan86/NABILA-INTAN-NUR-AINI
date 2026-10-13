<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Menerima satu role maupun banyak role sekaligus, sehingga route bisa
     * menuliskan `role:staff` atau `role:super_admin,staff`. Fitur yang harus
     * bisa dipakai staff dan Super Admin (mis. Network Tools) cukup memakai
     * bentuk kedua; fitur khusus Super Admin tetap memakai `role:super_admin`.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // If user is not authenticated, redirect to login.
        if (! auth()->check()) {
            return redirect()->guest(route('login'));
        }

        // Laravel memecah parameter middleware pada tanda koma, tetapi nilai
        // yang masih mengandung koma (mis. dipanggil dinamis) juga dipecah di
        // sini supaya daftar role selalu konsisten.
        $allowed = [];

        foreach ($roles as $role) {
            foreach (explode(',', $role) as $part) {
                $part = trim($part);

                if ($part !== '') {
                    $allowed[] = $part;
                }
            }
        }

        // Tanpa daftar role, cukup terautentikasi. Dengan daftar role, user
        // wajib salah satu; selain itu 403.
        if ($allowed !== [] && ! in_array(auth()->user()->role, $allowed, true)) {
            abort(403);
        }

        return $next($request);
    }
}
