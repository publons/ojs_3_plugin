<?php

/**
 * @defgroup plugins_generic_publons
 */
 
/**
 * @file plugins/generic/publons/index.php
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @ingroup plugins_generic_publons
 * @brief Wrapper for Publons plugin.
 *
 */

require_once('PublonsPlugin.inc.php');

return new PublonsPlugin();

?>
