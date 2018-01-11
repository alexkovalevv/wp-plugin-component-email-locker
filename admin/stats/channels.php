<?php


class OPanda_EmailLocker_Channels_StatsTable extends OPanda_StatsTable {
    
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
            'unlock-via-form' => array(
                'title' => __('Via Opt-In Form', 'bizpanda-email-locker'),
                'cssClass' => 'opanda-col-number'
            ),
            'unlock-via-facebook' => array(
                'title' => __('Via Facebook', 'bizpanda-email-locker'),
                'cssClass' => 'opanda-col-number'
            ),
            'unlock-via-google' => array(
                'title' => __('Via Google', 'bizpanda-email-locker'),
                'cssClass' => 'opanda-col-number'
            ),
            'unlock-via-linkedin' => array(
                'title' => __('Via LinkedIn', 'bizpanda-email-locker'),
                'cssClass' => 'opanda-col-number'
            )
        );
    }
}

class OPanda_EmailLocker_Channels_StatsChart extends OPanda_StatsChart {
    
    public $type = 'line';
    
    public function getFields() {
        
        return array(
            'aggregate_date' => array(
                'title' => __('Date', 'bizpanda-email-locker')
            ),
            'unlock-via-form' => array(
                'title' => __('Via Opt-In Form', 'bizpanda-email-locker'),
                'color' => '#31ccab'
            ),
            'unlock-via-facebook' => array(
                'title' => __('Via Facebook', 'bizpanda-email-locker'),
                'color' => '#7089be'
            ),
            'unlock-via-google' => array(
                'title' => __('Via Google', 'bizpanda-email-locker'),
                'color' => '#e26f61'
            ),
            'unlock-via-linkedin' => array(
                'title' => __('Via LinkedIn', 'bizpanda-email-locker'),
                'color' => '#006080'
            ) 
        );
    }
}