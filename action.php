<?php
/**
 * DokuWiki Plugin avoidsearchbot (Action Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  LefTobno <dragonbird.tonbo@gmail.com>
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

class action_plugin_avoidsearchbot extends DokuWiki_Action_Plugin {

  /**
   * Registers a callback function for a given event
   *
   * @param Doku_Event_Handler $controller DokuWiki's event controller object
   * @return void
   */
  public function register(Doku_Event_Handler $controller) {

    $controller->register_hook('TPL_METAHEADER_OUTPUT', 'BEFORE', $this, 'handle_avoidsearchbot');
    $controller->register_hook('ACTION_HEADERS_SEND', 'BEFORE', $this, 'handle_avoidsearchbot');

  }

  /**
   * [Custom event handler which performs action]
   *
   * @param Doku_Event $event  event object by reference
   * @param mixed      $param  [the parameters passed as fifth argument to register_hook() when this
   *                           handler was registered]
   * @return void
   */

  public function handle_avoidsearchbot(Doku_Event &$event, $param) {
    global $ID;
    
    $pagename = cleanID($ID);
    
    if ((bool) preg_match('/'. trim($this->getConf('regex_excludes')) .'/', $pagename)) return false;
    
    if ($event->name == 'TPL_METAHEADER_OUTPUT') {
      $key = array_search(
        array( 'name'=>'robots', 'content'=>'index,follow'),
        $event->data['meta']
      );
      if ($key !== false) {
          $event->data['meta'][$key] = array( 'name'=>'robots', 'content'=>'noindex,follow');
      }
    } else if ($event->name == 'ACTION_HEADERS_SEND') {
      $event->data[] = 'X-Robots-Tag: noindex';
    }
  }

}

// vim:ts=4:sw=4:et:
