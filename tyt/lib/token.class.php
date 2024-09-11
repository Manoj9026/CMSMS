<?php
class token{
    public static function genarate(){  
        return session::put(config::get('session/token_name'),  md5(uniqid()));
    }
    
    public static function check($token){
        $tokenName = config::get('session/token_name');
        if(session::exists($tokenName)&& $token === session::get($tokenName)){
            session::delete($tokenName);
            return TRUE;
        }else{
            return FALSE;
        }
    }
}