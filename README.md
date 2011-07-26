# SortPlugin for CakePHP #

一覧に上下のリンクを追加して並び順を入れ替えるplug-inです。

## Installation ##

First, put `sorter’ directory on app/plugins in your CakePHP application.

1, app/plugins 以下に sorter のディレクトリ名で配置します。

Second, The sorting column is added to the table.

2, テーブルにソートカラムを追加します。

ALTER TABLE `Tables` ADD `sort` INT NULL ;

Third, Behavior is defined.

3, Behaviorを定義します。

    <?php
        class Table extends Model {
            var $actsAs = array(
                'Sorter.Sorter' => array(
                    'column' => 'sort'
                )
            );

Four, Component is defined.

4, Componentを定義します。

    <?php
        class HogeController extends Controller {
            var $components = array('Sorter.Sorter');
        }


## Example ##

view.ctp

    <a href="<?php echo $this->Html->url(array('action'=>'sortup', $data['Hoge']['id'])); ?>">sortup</a>
    <a href="<?php echo $this->Html->url(array('action'=>'sortdown', $data['Hoge']['id'])); ?>">sortdown</a>

## Dynamic Conditions ##

A dynamic condition can be given to the behavior.

ビヘイビアに動的な条件を付与することも出来ます。

    <?php
        class HogeController extends Controller {
            var $components = array('Sorter.Sorter');

            public function beforeFilter() {
                parent::beforeFilter();
                $this->Hoge->attachSorterBehavior(array(
                        'Hoge.member_id' => $this->_memberId,
                    )
                );
            }
        }

    <?php
        class Table extends Model {
            var $actsAs = array();
            public function attachSorterBehavior($conditions) {
                $this->Behaviors->attach('Sorter.Sorter', array(
                        'column' => 'sort',
                        'conditions' => $conditions,
                    )
                );
            }

