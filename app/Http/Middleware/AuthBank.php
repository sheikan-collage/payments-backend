<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\OpeningHours\OpeningHours;

class AuthBank
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    private const VALID_BANKS = [
        'fcb' => [
            'is_active' => true,
            'valid_ip_addresses' => [
                "127.0.0.1",
            ],
            'secret' => '4guJXs922WsU6D4XdddVBSnNCfkbnUdV',
            'active_hours' => [
                'monday'     => ['07:00-15:00'],
                'tuesday'    => ['07:00-15:00'],
                'wednesday'  => ['07:00-15:00'],
                'thursday'   => ['07:00-15:00'],
                'friday'     => [],
                'saturday'   => [],
                'sunday'     => ['07:00-15:00'],
                'exceptions' => [
                    // '2016-11-11' => ['09:00-12:00'],
                    // '2016-12-25' => [],
                    // '01-01'      => [],                // Recurring on each 1st of January
                    // '12-25'      => ['09:00-12:00'],   // Recurring on each 25th of December
                ],
            ]
        ]
    ];
    public function handle(Request $request, Closure $next)
    {

        // 1. check if connection is secure in production mode
        if (!app()->environment(['local']) and $request->secure()) {
            dd($request->secure(), app()->environment(['local']));
            return Controller::sendError('Terminated: must use HTTPS', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // 2. check if bank id is valid
        $bank_id = $request->route()->parameter('bank_id');
        if (!array_key_exists($bank_id, static::VALID_BANKS)) {
            return Controller::sendError('Unauthenticated: wrong ID or secret', Response::HTTP_UNAUTHORIZED);
        }

        $bank = static::VALID_BANKS[$bank_id];

        // 3. check if bank secret is matching the given token
        $request_secret_header = $request->header('Authorization');
        if (!$request_secret_header || $request_secret_header != $bank['secret']) {
            return Controller::sendError('Unauthenticated: wrong ID or secret', Response::HTTP_UNAUTHORIZED);
        }

        // 4. check if the request ip is valid ip address for this bank
        $request_ip = $request->ip();
        if (!in_array($request_ip, $bank['valid_ip_addresses'])) {
            return Controller::sendError("Forbidden: your IP ($request_ip) is not authorized to access this server", Response::HTTP_FORBIDDEN);
        }

        // 5. check if bank account is active
        if (!$bank['is_active']) {
            return Controller::sendError('Terminated: InActive bank', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // 6. check if request time is between active hours for the bank
        $now = Carbon::now();
        $openingHours = OpeningHours::create($bank['active_hours']);

        $range = $openingHours->currentOpenRange($now);

        if (!$range) {
            return Controller::sendError("Forbidden: inactive hours", Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}