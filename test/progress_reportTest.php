<?php


class progress_reportTest extends PHPUnit_Framework_TestCase {
    public function test_event_input() {
        $sample_progress_report = new progress_report(array(), '', '');
        $this->assertEquals(2, 1);
    }
}
