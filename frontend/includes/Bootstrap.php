<?php

include("includes/Cfg.php");
error_reporting(E_ALL);
ini_set('display_errors', 0);

/** lets define some constants from values set in config.ini because they are used every now and then */

define("ROOTPATH", str_replace('/frontend/includes','',__DIR__));
const TMPPATH = ROOTPATH."/tmp";
define("DATABASE", Cfg::read('db','database'));
const DATABASEDIR = ROOTPATH ."/db/". DATABASE;
define("TEMPLATEDIR", ROOTPATH ."/frontend/tmpl/". Cfg::read('web', 'template'));
define("TEMPLATEDIRHTML", str_replace(array('/frontend', ROOTPATH), '', TEMPLATEDIR));
define("LANGUAGE", Cfg::read('web','language'));
define("HTTPHOST", Cfg::read('default','http'));
$plantActive = Cfg::read('plant','active');
define("HIDDEN", ($plantActive === true) ? "" : "hidden");
define("PROJECTACTIVE", $plantActive === true);

/**
 * the bootstrap class. used to bootstrap the webfrontend
 * sets custom errorhandler
 * loads neccessary classes
 *
 * calls Bootstrap::boot() at the end of the file, to boot the frontend by including the Bootstrap file. no hassle
 */
class Bootstrap
{
    /** @var string the root path of the project */
    public const ROOTPATH = ROOTPATH;
    /** @var string the tmp path of the project */
    public const TMPPATH = TMPPATH;
    /** @var bool|string  */
    public const DB = DATABASE;
    /** @var string path/to/database */
    public const DBDIR = DATABASEDIR;
    /** @var string template directory as seen from system */
    public const TEMPLATEDIR = TEMPLATEDIR;
    /** @var string templatedir as seen from http */
    public const TEMPLATEDIRHTML = TEMPLATEDIRHTML;
    /** @var bool|string  */
    public const LANGUAGE = LANGUAGE;
    /** @var bool|string  */
    public const HTTPHOST = HTTPHOST;
    /** @var bool|string to hide things on the frontend when project is not running  */
    public const HIDDEN = HIDDEN;
    /** @var bool true when yes */
    public const PROJECTACTIVE = PROJECTACTIVE;

    /**
     * boots the webfrontend
     *
     * @return void
     */
    static function boot(): void
    {
        if (is_bool(self::ROOTPATH) || is_bool(self::DB) || is_bool(self::LANGUAGE) || is_bool(self::HTTPHOST))
        { self::error("Bootstrap: problem reading config.ini"); }

        self::includes();
        self::language();
        Session::start();
    }

    /**
     * loads the includes neccessary for the frontend
     * @return void
     */
    static function includes(): void
    {
        include("includes/enum.php");
        include("includes/Template.php");
        include("includes/Session.php");
    }

    /**
     * loads the language as set in config.ini
     * @return void
     */
    static function language(): void
    {
        (!empty($_POST["web"]["language"])) ? include(self::ROOTPATH . "/lang/".$_POST["web"]["language"].".inc.php") : include(self::ROOTPATH . "/lang/" . self::LANGUAGE . ".inc.php");
    }

    /**
     * includes error page
     * @param $error string error reported by the exception
     * @return never
     */
    static function error(string $error): never
    {
        $message = array();
        $message['text'] = $error;
        include("includes/Logger.php");
        Logger::write(LoggerAction::error, $message);
        include(self::TEMPLATEDIR."/error-tmpl.php");
        die();
    }

}

try { Bootstrap::boot(); }
catch (Exception $e) { Bootstrap::error($e); }
