<?php
/**
 * DokuWiki Plugin newsticker (Syntax Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Michael Große <grosse@cosmocode.de>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

class syntax_plugin_newsticker_ticker extends DokuWiki_Syntax_Plugin {
    /**
     * @return string Syntax mode type
     */
    public function getType() {
        return 'container';
    }

    /**
     * @return string Paragraph type
     */
    public function getPType() {
        return 'block';
    }
    /**
     * @return int Sort order - Low numbers go before high numbers
     */
    public function getSort() {
        return 100;
    }

    /**
     * Connect lookup pattern to lexer.
     *
     * @param string $mode Parser mode
     */
    public function connectTo($mode) {
        $this->Lexer->addEntryPattern('<newsticker>',$mode,'plugin_newsticker_ticker');
    }

    public function postConnect() {
        $this->Lexer->addExitPattern('</newsticker>','plugin_newsticker_ticker');
    }

    /**
     * Handle matches of the newsticker syntax
     *
     * @param string $match The match of the syntax
     * @param int    $state The state of the handler
     * @param int    $pos The position in the document
     * @param Doku_Handler    $handler The handler
     * @return array Data for the renderer
     */
    public function handle($match, $state, $pos, Doku_Handler &$handler){
        if ($state !== 3) {
            return array();
        }
        $data = array();
        $lines = explode("\n",$match);
        $lines = $this->cleanData($lines);
        foreach ($lines as $newsItem) {
            $instructions = p_get_instructions($newsItem);
            $render = p_render('xhtml',$instructions, $info);
            $render = trim($render);
            $render = ltrim($render,'<p>');
            $render = rtrim($render,'</p>');
            $data[] = $render;
        }

        return $data;
    }

    public function cleanData($data) {
        $cleanedData = array();
        foreach ($data as $item) {
            $item = trim($item);
            if (!empty($item)) {
                $cleanedData[] = $item;
            }
        }
        return $cleanedData;
    }

    /**
     * Render xhtml output or metadata
     *
     * @param string         $mode      Renderer mode (supported modes: xhtml)
     * @param Doku_Renderer  $renderer  The renderer
     * @param array          $data      The data from the handler() function
     * @return bool If rendering was successful.
     */
    public function render($mode, Doku_Renderer &$renderer, $data) {
        if($mode != 'xhtml') return false;
        if (empty($data)) return false;

        $renderer->doc .= '<div id="plugin-newsticker" class="ticking">';
        $renderer->doc .= '<ul id="tickerlist">';
        foreach ($data as $index => $newsItem) {
            $renderer->doc .= '<li><div class="li">';
            $renderer->doc .= $newsItem;
            $renderer->doc .= '<span class="newsticker-counter">'. ($index+1) . '/' . count($data) . '</span>';
            $renderer->doc .= "</div></li>";
        }
        $renderer->doc .= "</ul>";
        $renderer->doc .= '<div class="no ticker-buttons">';
        $renderer->doc .= '<button class="plugin_newsticker" type="button" id="plugin_newsticker_unticker" title="' . $this->getLang('previous') . '">' . ''  . '</button>';
        $renderer->doc .= '<button class="plugin_newsticker" type="button" id="plugin_newsticker_ticker" title="' . $this->getLang('next') . '">' . '' . '</button>';
        $renderer->doc .= '</div>';
        $renderer->doc .= '</div>';

        return true;
    }
}

// vim:ts=4:sw=4:et:
