<?php
/* SVN FILE: $Id$ */
/**
 * Sorter.SorterComponent
 * ソート変更コンポーネント
 *
 * PHP versions 4 and 5
 *
 * @copyright     COPYRIGHTS (C) 2011 Web-Promotions Limited. All Rights Reserved.
 * @link          http://www.web-prom.net/
 * @package       cake
 * @version       $Revision$
 * @modifiedby    $LastChangedBy$
 * @lastmodified  $Date$
 * @license
 *
 * @author        Hideyuki Kagasawa. (kagasawa@web-prom.net)
 */
class SorterComponent extends Component {

    public $components = array('Session');
    private $_controller;
    private $_model;

    public function initialize($controller, $settings = array()) {
    }
    public function startup($controller) {
        $this->_controller =& $controller;

        if ( !method_exists($controller, $controller->action) && method_exists($this, $controller->action) ) {
            $this->_model = ClassRegistry::init($controller->modelClass);
            call_user_func(array($this, $controller->action), $controller->params['pass'][0]);
        }
    }

    /**
     * sortup
     *
     * @param <type> $id
     */
    public function sortup($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for ModelClass'));
            $this->_controller->redirect(array('action'=>'index'));
        }
        if ( $this->_model->sortup($id) ) {
            $this->Session->setFlash(__('Success sortup'));
        } else {
            $this->Session->setFlash(__('The ModelClass could not be sortup. Please, try again.'));
        }
        $this->_controller->redirect(array('action' => 'index'));
    }

    /**
     * sortdown
     *
     * @param <type> $id
     */
    public function sortdown($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for ModelClass'));
            $this->_controller->redirect(array('action'=>'index'));
        }
        if ( $this->_model->sortdown($id) ) {
            $this->Session->setFlash(__('Success sortdown'));
        } else {
            $this->Session->setFlash(__('The ModelClass could not be sortdown. Please, try again.'));
        }
        $this->_controller->redirect(array('action' => 'index'));
    }

}

