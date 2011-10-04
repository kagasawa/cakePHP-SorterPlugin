<?php
/* SVN FILE: $Id$ */
/**
 * SorterBehavior
 * ソートカラム制御用Behavior
 *
 * PHP versions 4 and 5
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
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
class SorterBehavior extends ModelBehavior {

    /**
     * settings
     *
     * @var <array>
     */
    protected $_settings = array(
        'column' => 'sort',     // ソート対象のカラム
        'conditions' => array(),
    );

    /**
     * setup
     *
     * @access public
     * @param <type> $model
     * @param <type> $settings 
     */
    public function setup($model, $settings = array()){
        $this->setSettings($settings);
    }

    public function setSettings($settings = array()) {
        $this->_settings = Set::merge($this->_settings, $settings);
    }

    /**
     * sortdown
     *
     * @access public
     * @param <type> $model
     * @param <type> $id
     * @return <type>
     */
    public function sortup($model, $id=null) {

        $query = array(
            'conditions' => $this->_settings['conditions'],
        );
        $query['conditions'] = Set::merge($query['conditions'], array(
                $model->alias.'.'.$model->primaryKey => $id,
            )
        );
        $data = $model->find('first', $query);
        if ( empty($data) ) {
            return false;
        }

        $column = $this->_settings['column'];

        $sortNo = $data[$model->alias][$column] - 1;

        $query = array(
            'conditions' => $this->_settings['conditions'],
        );
        $query['conditions'] = Set::merge($query['conditions'], array(
                $model->alias.'.'.$this->_settings['column'] => $sortNo,
            )
        );
        $_replace = $model->find('first', $query);

        if ( !empty($_replace) ) {
            $_data = array(
                $model->primaryKey => $data[$model->alias][$model->primaryKey],
                $column => $_replace[$model->alias][$column]
            );
            if ( $model->save($_data) ) {
                $_data = array(
                    $model->primaryKey => $_replace[$model->alias][$model->primaryKey],
                    $column => $data[$model->alias][$column]
                );
                $model->save($_data);
            } else {
                return false;
            }
        }
        return true;
    }

    /**
     * sortdown
     *
     * @access public
     * @param <type> $model
     * @param <type> $id
     * @return <type>
     */
    public function sortdown($model, $id=null) {

        $query = array(
            'conditions' => $this->_settings['conditions'],
        );
        $query['conditions'] = Set::merge($query['conditions'], array(
                $model->alias.'.'.$model->primaryKey => $id,
            )
        );
        $data = $model->find('first', $query);
        if ( empty($data) ) {
            return false;
        }

        $column = $this->_settings['column'];

        $sortNo = $data[$model->alias][$column] + 1;

        $query = array(
            'conditions' => $this->_settings['conditions'],
        );
        $query['conditions'] = Set::merge($query['conditions'], array(
                $model->alias.'.'.$this->_settings['column'] => $sortNo,
            )
        );
        $_replace = $model->find('first', $query);

        if ( !empty($_replace) ) {
            $_data = array(
                $model->primaryKey => $data[$model->alias][$model->primaryKey],
                $column => $_replace[$model->alias][$column]
            );
            if ( $model->save($_data) ) {
                $_data = array(
                    $model->primaryKey => $_replace[$model->alias][$model->primaryKey],
                    $column => $data[$model->alias][$column]
                );
                $model->save($_data);
            } else {
                return false;
            }
        }
        return true;
    }

    /**
     * beforeSave
     *
     * @access public
     * @param <type> $model 
     */
    public function beforeSave($model) {
        // 新規登録かつソートカラムが未セットの場合
        if ( empty($model->data[$model->alias][$model->primaryKey]) && empty($model->data[$model->alias][$this->_settings['column']]) ) {
            // 既にセットされているソートNoの最大値を取得

            $query = array(
                'conditions' => $this->_settings['conditions'],
            );
            $maxField = $model->alias.'.'.$this->_settings['column'];
            $query['fields'] = "MAX({$maxField}) as max_number";

            $result = $model->find('first', $query);
            $maxNumber = 0;
            if ( !empty($result[0]['max_number']) ) {
                $maxNumber = $result[0]['max_number'];
            }

            // 最大値+1をセット
            $model->data[$model->alias][$this->_settings['column']] = $maxNumber+1;
        }
        
        return true;
    }

}

?>
