<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

if (app()->environment(['local'])) {
    // usleep(900 * 1000);
}

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @method sendData sends the given data in an envelope
     * @param $data array of data to be sent
     * @param array $messages array of messages
     * @param int $httpCode http response code
     * @param array $HTTPCode array of http header to be in the response
     */
    public static function sendData($data, array $messages = [], int $HTTPCode = Response::HTTP_OK, array $headers = [])
    {
        $envelope = [
            'hasError' => false,
            'messages' => $messages,
        ];

        if ($data) {
            $envelope['data'] = $data;
        }

        return response($envelope, $HTTPCode, $headers);
    }


    /**
     * @method sendError sends the given errors in an envelope
     * @param array $messages array of messages
     * @param int $httpCode http response code
     * @param array $HTTPCode array of http header to be in the response
     */

    public static function sendError($messages, $HTTPCode = 500, $headers = [])
    {
        $envelope = [
            'hasError' => true,
            'messages' => $messages
        ];

        return response($envelope, $HTTPCode, $headers);
    }

    public static function logActivity(string $event, string $message, array $properties = null, $supervisor = null)
    {
        if (!$supervisor) {
            $supervisor = request()->user();
        }
        $activityName = $supervisor->name . ' [@' . $supervisor->user_name . ']';
        $activity = activity($activityName)->causedBy($supervisor);

        if ($properties) {
            $activity->withProperties($properties);
        }

        $activity->event($event);
        $activity->log($message);
    }

    public  function logSuccess(string $event, array $properties = null, string $message = '', $supervisor = null)
    {
        return $this->logActivity($event, '[SUCCESS] ' . $message, $properties, $supervisor);
    }

    public function logFail(string $event, array $properties = null, string $message = '', $supervisor = null)
    {
        return $this->logActivity($event, '[Failed] ' . $message, $properties, $supervisor);
    }

    public function logNotFound(string $event, $id, array $properties = null, string $message = '', $supervisor = null)
    {
        if(!$properties) {
            $properties = [];
        }
        $properties['id'] = $id;
        return $this->logFail($event, $properties, $message, $supervisor);
    }
}
