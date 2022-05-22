<?php

define('cookieParams', array(
    'lifetime' => time() + 600,
    'path' => '/',
    'domain' => Cfg::read('default', 'hostname'),
    'secure' => false,
    'httponly' => true,
    'samesite' => 'strict'));

class Session
{
    /**
     * returns array of session cookie parameters
     * @return array{lifetime: int, path: string, domain: string, secure: bool, httponly: bool, samesite: string}
     */

    const cookieParams = cookieParams;

    /**
     * checks user and pass, starts a session and sets $_SESSION["login"] true
     * @param string $user username
     * @param string $pass password
     * @return bool true on success, false on fail
     */
    static function login(string $user, string $pass): bool
    {
        $credentials = json_decode(file_get_contents(Cfg::read('default','rootpath')."/.passwd"),true);
        if (!class_exists('Logger')) { include("includes/Logger.php"); }
        if (($user == $credentials["user"]) && (password_verify($pass,$credentials["pass"]))) {
            self::start();
            $_SESSION["login"] = true;
            session_write_close();
            Logger::write(LoggerAction::session,array('text' => 'user login ('.$_SERVER['REMOTE_ADDR'].')'));
            if (password_needs_rehash($credentials["pass"],PASSWORD_DEFAULT)) {
                self::passwd($pass,true);
            }

            return true;
        }
        else { Logger::write(LoggerAction::session,array('text' => 'login error ('.$_SERVER['REMOTE_ADDR'].')')); return false; }
    }

    /**
     * changes user/pass and writes it to the password file and makes an entry in the log
     * @param string $pass password
     * @return array returnMessage
     */
    static function passwd(string $pass, bool $rehash=false): array
    {
        $credentials = json_decode(file_get_contents(Cfg::read('default','rootpath')."/.passwd"),true);
        $hash = password_hash($pass,PASSWORD_DEFAULT);
        $json = array(
            'user' => $credentials['user'],
            'pass' => $hash);
        try { file_put_contents(Cfg::read('default','rootpath')."/.passwd",json_encode($json)); }
        catch (Exception $e) { $return = array("messageType" => 'error', "messageText" => $e->getMessage()); }
        if (!isset($e)) {
            if (!class_exists('Logger')) { include("includes/Logger.php"); }
            if ($rehash === true) {
                Logger::write(LoggerAction::rehashPassword);
                $return = array("messageType" => 'success', "messageText" => _MESSAGE_PASSWORD_REHASH);
            }
            else {
                Logger::write(LoggerAction::changePassword);
                $return = array("messageType" => 'success', "messageText" => _MESSAGE_SUCCESSFUL);
            }
        }
        return $return;
    }

    /**
     * logout the user by unsetting $_SESSION, setting a new cookie lifetime and destroying the session
     *
     * @return void
     */
    static function logout(): void
    {
        unset($_SESSION);
        session_destroy();
        if (!class_exists('Logger')) { include("includes/Logger.php"); }
        Logger::write(LoggerAction::session,array('text' => 'user logout ('.$_SERVER['REMOTE_ADDR'].')'));
        setcookie(session_name(), '', time()-100000);
    }

    /**
     * Starts the session and sets the delete_time - the time when the session should be regenerated
     * @return void
     */
    static function start(): void
    {
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_set_cookie_params(self::cookieParams);
            session_start();
            if (empty($_SESSION["delete_time"])) { $_SESSION["delete_time"] = time(); session_write_close(); }
        }
        if (!empty($_SESSION["delete_time"]) && $_SESSION["delete_time"] < time() -180 && !empty($_SESSION["login"])) { self::regenerateId(); }
    }

    /**
     * generates a new session id
     * @return void
     */
    static function regenerateId(): void
    {
        $login = $_SESSION["login"];

        session_destroy();

        session_commit();
        session_id(session_create_id());
        session_start();

        $_SESSION["login"] = $login;
        $_SESSION["delete_time"] = time();
        session_write_close();
    }

}