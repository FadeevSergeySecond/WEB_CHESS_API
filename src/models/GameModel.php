<?php

class GameModel
{

    /*
     * jsonExample
     * {
     *  "from": {
     *      "line": int fron 1 to 8
     *      "colomn": string [a .. h]
     *  }
     *  "to": {
     *      "line": int fron 1 to 8
     *      "colomn": string [a .. h]
     *  }
     * }
     * */

    // checks that the string is json
    public static function isJSON($string)
    {
        return is_string($string) && (is_object(json_decode($string)) || is_array(json_decode($string)));
    }

    // Checks input json for validity
    public static function validateInput($data)
    {
        try {
            if (!GameModel::isJSON($data)) {
                return [
                    'ok' => false,
                    'message' => 'no json input',
                ];
            }
            $data = json_decode($data, true);

            if (count($data) != 2) {
                return [
                    'ok' => false,
                    'message' => 'Invalid json. Pairs key value is not two',
                ];
            }

            if ($data['from'] == null || $data['to'] == null) {
                return [
                    'ok' => false,
                    'message' => 'Invalid json. Json must contain a key \'from\' and \'to\'',
                ];
            }

            if (!is_array($data['from']) || !is_array($data['to'])) {
                return [
                    'ok' => false,
                    'message' => 'Invalid json. ',
                ];
            }

            if (count($data['from']) != 2 || count($data['to']) != 2) {
                return [
                    'ok' => false,
                    'message' => 'Invalid json',
                ];
            }

            if ($data['from']['line'] == null || $data['from']['column'] == null ||
                $data['to']['line'] == null || $data['to']['column'] == null) {
                return [
                    'ok' => false,
                    'message' => 'Invalid json',
                ];
            }

            if (!is_int($data['from']['line']) ||
                $data['from']['line'] < 1 ||
                $data['from']['line'] > 8 ||
                !is_string($data['from']['column']) ||
                strlen($data['from']['column']) != 1 ||
                ord($data['from']['column']) < 97 ||
                ord($data['from']['column']) > 104) {

                return [
                    'ok' => false,
                    'message' => 'Invalid start call',
                ];
            }

            if (!is_int($data['to']['line']) ||
                $data['to']['line'] < 1 ||
                $data['to']['line'] > 8 ||
                !is_string($data['to']['column']) ||
                strlen($data['to']['column']) != 1 ||
                ord($data['to']['column']) < 97 ||
                ord($data['to']['column']) > 104) {

                return [
                    'ok' => false,
                    'message' => 'Invalid finish call',
                ];
            }

            return [
                'ok' => true,
                'message' => 'string valid',
            ];
        } catch (Exception $e) {
            return [
                'ok' => false,
                'message' => $e->getMessage() . "\n",
            ];
        }
    }
}