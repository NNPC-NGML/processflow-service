<?php

namespace App\Http\Middleware;

use Closure;
use Skillz\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UsersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = (new UserService)->getRequest('get', 'scope/user');

        if (!$response->ok()) {
            abort(401, 'unauthorized');
        }
        $userData = $response->json();

        // Create a user instance with the returned data
        $user = new \App\Models\User($userData);

        // Set the user in Auth
        Auth::setUser($user);

        // Add this line to bind the user to the request
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        return $next($request);
    }
}



//FIXME: to cache a user
// <?php

// namespace App\Http\Middleware;

// use Closure;
// use Skillz\UserService;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Cache;

// class UsersMiddleware
// {
//     /**
//      * Handle an incoming request.
//      *
//      * @param \Illuminate\Http\Request $request
//      * @param \Closure $next
//      * @return mixed
//      */
//     public function handle(Request $request, Closure $next)
//     {
//         $user = $request->user();

//         // Check if the user's scope is cached
//         $userScope = Cache::get('user_scope_' . $user->id, null);

//         if ($userScope === null) {
//             // If the scope is not cached, fetch it from the UserService
//             $userService = new UserService();
//             $response = $userService->getRequest('get', 'scope/user');

//             if ($response->ok()) {
//                 $userScope = $response->json();

//                 // Cache the user's scope for 1 hour
//                 Cache::put('user_scope_' . $user->id, $userScope, now()->addHours(1));
//             } else {
//                 abort(401, 'unauthorized');
//             }
//         }

//         return $next($request);
//     }
// }