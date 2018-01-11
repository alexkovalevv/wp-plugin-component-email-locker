<?php


class OPanda_EmailLocker_Skips_StatsTable extends OPanda_StatsTable {
    
    public function getColumns() {
        
        return array(
            'index' => array(
                'title' => ''
            ),
            'title' => array(
                'title' => __('Post Title', 'bizpanda-email-locker')
            ),
            'unlock' => array(
                'title' => __('Number of Unlocks', 'bizpanda-email-locker'),
                'hint' => __('The number of unlocks made by visitors.', 'bizpanda-email-locker'),
                'highlight' => true,
                'cssClass' => 'opanda-col-number'
            ),
            'skip-via-timer' => array(
                'title' => __('Skipped by Timer', 'bizpanda-email-locker'),
                'cssClass' => 'opanda-col-number'

            ),
            'skip-via-cross' => array(
                'title' => __('Skipped by Close Icon', 'bizpanda-email-locker'),
                'cssClass' => 'opanda-col-number'
            )
        );
    }   
}

class OPanda_EmailLocker_Skips_StatsChart extends OPanda_StatsChart {
    
    public $type = 'column';
    
    public function getFields() {
        
        return array(
            'aggregate_date' => array(
                'title' => __('Date')
            ),
            'unlock' => array(
                'title' => __('Number of Unlocks', 'bizpanda-email-locker'),
                'color' => '#0074a2'
            ),
            'skip-via-timer' => array(
                'title' => __('Skipped by Timer', 'bizpanda-email-locker'),
                'color' => '#333333'

            ),
            'skip-via-cross' => array(
                'title' => __('Skipped by Close Icon', 'bizpanda-email-locker'),
                'color' => '#dddddd'
            )
        );
    }
}