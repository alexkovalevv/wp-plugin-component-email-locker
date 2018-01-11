<?php


class OPanda_EmailLocker_Summary_StatsTable extends OPanda_StatsTable {
    
    public function getColumns() {
        
        return array(
            'index' => array(
                'title' => ''
            ),
            'title' => array(
                'title' => __('Post Title', 'bizpanda-email-locker')
            ),
            'impress' => array(
                'title' => __('Impressions', 'bizpanda-email-locker'),
                'cssClass' => 'opanda-col-number'
            ),
            'unlock' => array(
                'title' => __('Number of Unlocks', 'bizpanda-email-locker'),
                'hint' => __('The number of unlocks made by visitors.', 'bizpanda-email-locker'),
                'highlight' => true,
                'cssClass' => 'opanda-col-number'
            ),
            'conversion' => array(
                'title' => __('Conversion', 'bizpanda-email-locker'),
                'hint' => __('The ratio of the number of unlocks to impressions, in percentage.', 'bizpanda-email-locker'),
                'cssClass' => 'opanda-col-number'
            )
        );
    }
}

class OPanda_EmailLocker_Summary_StatsChart extends OPanda_StatsChart {
    
    public function getSelectors() {
        return null;
    }
    
    public function getFields() {
        
        return array(
            'aggregate_date' => array(
                'title' => __('Date', 'bizpanda-email-locker')
            ),
            'unlock' => array(
                'title' => __('Number of Unlocks', 'bizpanda-email-locker'),
                'color' => '#0074a2'
            )
        );
    }
}