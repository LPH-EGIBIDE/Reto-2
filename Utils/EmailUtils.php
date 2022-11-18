<?php

namespace Utils;

use Entities\UserEntity;
use Exceptions\PostException;

class EmailUtils
{
    private string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @param string $to
     * @param string $subject
     * @param string $message
     * @return void
     * @throws PostException
     */

    public function sendEmail(string $to, string $subject, string $message): void
    {
        $curl = curl_init();
        // Send post request to private API x-www-form-urlencoded
        $fields = [
            "apiKey" => $this->apiKey,
            "to" => $to,
            "subject" => $subject,
            "message" => $message
        ];

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://imaleex.com/email/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($fields),
            CURLOPT_HTTPHEADER => [
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        if ($err) {
            if (DEBUG_MODE)
                throw new PostException("cURL Error #:" . $err);

            throw new PostException("Error al enviar el email");
        } else {
            $response = json_decode($response, true);
            if (isset($response["error"])) {
                throw new PostException($response["error"]);
            }
        }
    }

    /**
     * @throws PostException
     */
    public function sendLoginEmail(UserEntity $user)
    {
        $this->sendEmail($user->getEmail(), "Inicio de sesion", "Se ha iniciado sesion en tu cuenta de WTFAQ desde una nueva ubicacion con la direccion IP ${_SERVER["REMOTE_ADDR"]}. Si no has sido tu, cambia tu contraseña lo antes posible.");
    }

    /**
     * @throws PostException
     */
    public function sendMfaEmail(UserEntity $user)
    {
        $this->sendEmail($user->getEmail(), "Codigo de verificacion", "Tu código de verificacion es: " . $user->getMfaData());
    }


}