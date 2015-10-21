<?php
/**
 * DokuWiki Plugin oauth (Action Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Michael Große <grosse@cosmocode.de>
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

class action_plugin_newsticker extends DokuWiki_Action_Plugin {

    function register(Doku_Event_Handler $controller) {
        $controller->register_hook('DOKUWIKI_STARTED', 'AFTER',  $this, 'jsinfo_addConf');
    }
    function jsinfo_addConf(&$event, $param) {
        global $JSINFO;
        $JSINFO['plugin']['newsticker']['duration'] = $this->getConf('duration');
    }
}
