<?php

namespace Livramatheus\PlanetgameBack\Core\Enums;

class Message {

    const DB_ERROR             = "Something went wrong with the database";
    const UNKNOWN_ERROR        = "Something wrong happened, try again later";
    const BAD_REQUEST_ERROR    = "Something went wrong with your request";
    const CREDENTIALS_ERROR    = "You don't have the credentials for this operation";
    const MISSING_PARAMS_ERROR = "Missing parameters or actions";
    const ITM_NOT_FOUND_NOTICE = "Notice: Item Not Found";
    const BAD_LANG_ERROR       = "A bad language was used in user input";

}