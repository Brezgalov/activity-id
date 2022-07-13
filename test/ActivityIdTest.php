<?php

namespace Brezgalov\ActivityId\Tests;

use Brezgalov\ActivityId\ActivityId;

/**
 * Class ActivityIdTest
 * @package Brezgalov\ActivityId\Tests
 */
class ActivityIdTest extends \PHPUnit\Framework\TestCase
{
    public function testEqualSerialization()
    {
        $activity = new ActivityId('test');

        $this->assertEquals($activity->toString(), (string)$activity);
    }

    public function testEmptyBuild()
    {
        $nakedActivity = new ActivityId(null, false);
        $floatActivity = (float)$nakedActivity->toString();

        $this->assertGreaterThan(0, $floatActivity);
        $this->assertLessThan(1, microtime(true) - $floatActivity);
    }

    public function testNoNameBuild()
    {
        $noNameActivity = new ActivityId(null);

        $activityParts = explode(ActivityId::DEFAULT_SEPARATOR, $noNameActivity);

        $this->assertCount(2, $activityParts);
        $this->assertLessThan(1, microtime(true) - (float)$activityParts[0]);
        $this->assertEquals(13, strlen($activityParts[1]));
    }

    public function testNamedBuild()
    {
        $namedActivity = new ActivityId('test');

        $activityParts = explode(ActivityId::DEFAULT_SEPARATOR, $namedActivity);

        $this->assertCount(3, $activityParts);
        $this->assertEquals('test', $activityParts[0]);
        $this->assertLessThan(1, microtime(true) - (float)$activityParts[1]);
        $this->assertEquals(13, strlen($activityParts[2]));
    }

    public function testNotUnique()
    {
        $notUniqueActivity = new ActivityId('test', false);

        $activityParts = explode(ActivityId::DEFAULT_SEPARATOR, $notUniqueActivity);

        $this->assertCount(2, $activityParts);
        $this->assertEquals('test', $activityParts[0]);
        $this->assertLessThan(1, microtime(true) - (float)$activityParts[1]);
    }

    public function testMoreEntropy()
    {
        $entropiedActivity = new ActivityId('test', true, true);

        $activityParts = explode(ActivityId::DEFAULT_SEPARATOR, $entropiedActivity);

        $this->assertCount(3, $activityParts);
        $this->assertEquals('test', $activityParts[0]);
        $this->assertLessThan(1, microtime(true) - (float)$activityParts[1]);
        $this->assertEquals(23, strlen($activityParts[2]));
    }

    public function testNotDefaultSeparator()
    {
        $customSeparatorActivity = new ActivityId('test', true, false, '-');

        $this->assertCount(1, explode(ActivityId::DEFAULT_SEPARATOR, $customSeparatorActivity));
        $this->assertCount(3, explode('-', $customSeparatorActivity));
    }
}