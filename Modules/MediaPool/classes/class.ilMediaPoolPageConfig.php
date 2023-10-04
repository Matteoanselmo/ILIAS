<?php

/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

include_once("./Services/COPage/classes/class.ilPageConfig.php");

/**
 * Media pool page configuration
 *
 * @author Alex Killing <alex.killing@gmx.de>
 * @version $Id$
 * @ingroup ModulesMediaPool
 */
class ilMediaPoolPageConfig extends ilPageConfig
{
    /**
     * Init
     */
    public function init()
    {
        /* 20231004 - Impostata a True la variabile per rendere modificabili le aree di link anche 
        in immagini incluse in snippet e non solo a sé stanti. Questa proprietà viene letta in 
        /server/test6/public_html/ilias/Services/COPage/classes/class.ilPageEditorGUI.php (r. 354) - Mod by OC */

        //$this->setEnableInternalLinks(false);
        $this->setEnableInternalLinks(true);
        /* 20231004 - fine */

        $this->setPreventHTMLUnmasking(false);
        $this->setMultiLangSupport(true);
    }
}
