<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Encryption\DecryptException;

class Encrypt
{

    
    protected function getJsonPayload($payload)
    {
        $payload = json_decode(base64_decode($payload), true);
        // dd(openssl_cipher_iv_length($this->cipher));
        // If the payload is not valid JSON or does not have the proper keys set we will
        // assume it is invalid and bail out of the routine since we will not be able
        // to decrypt the given value. We'll also check the MAC for this encryption.
        if (! $this->validPayload($payload)) {
            throw new DecryptException('The payload is invalid.');
        }

        return $payload;
    }

    /**
     * Verify that the encryption payload is valid.
     *
     * @param  mixed  $payload
     * @return bool
     */
    protected function validPayload($payload)
    {
        return is_array($payload) && isset($payload['iv'], $payload['value'], $payload['mac']) &&
               strlen(base64_decode($payload['iv'], true)) === openssl_cipher_iv_length('AES-256-CBC');
    }

    public function decrypt($payload){

        $payload = $this->getJsonPayload($payload);

        $iv = base64_decode($payload['iv']);


        // Here we will decrypt the value. If we are able to successfully decrypt it
        // we will then unserialize it and return it out to the caller. If we are
        // unable to decrypt this value we will throw out an exception message.
        $decrypted = \openssl_decrypt(
            $payload['value'], 'AES-256-CBC', resolve('encrypter')->getKey(), 0, $iv
        );
        // dd($unserialize);
        if ($decrypted === false) {
            throw new DecryptException('Could not decrypt the data.');
        }

        return $decrypted;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try{

            if ($request->getContent()) {
                $requestBody = $this->decrypt($request->getContent());
                $requestObject = json_decode($requestBody,true);
                $request = $request->merge($requestObject);        
            }
        } finally {
            $response = $next($request);
            return response()->json(encrypt($response->content(),false),$response->status());
        }
    }
}
