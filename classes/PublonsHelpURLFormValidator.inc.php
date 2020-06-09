<?php

/**
 * @defgroup validation
 */

/**
 * @file  plugins/generic/publons/classes/PublonsHelpURLFormValidator.inc.php
 *
 *
 * Copyright (c) 2016 Publons Ltd.
 * Distributed under the GNU GPL v3.
 *
 * @class PublonsHelpURLFormValidator
 * @ingroup validation
 *
 * @validates help url has https at start
 */

import('lib.pkp.classes.validation.Validator');

class PublonsHelpURLFormValidator extends Validator{
    /**
     * Constructor.
     */
    function PublonsHelpURLFormValidator() {
    }

    /**
     * Check whether the given value is valid.
     * @param $value mixed the value to be checked
     * @return boolean
     */
    function isValid($value) {

        if (substr( $value, 0, 19 ) !== "https://publons.com"){
            return false;
        }

        return true;

    }
}

?>
