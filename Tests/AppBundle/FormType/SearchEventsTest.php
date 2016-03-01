<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 26/02/2016
 * Time: 16:32
 */

namespace Tests\AppBundle\FormType;


use AppBundle\FormType\SearchEvents;

class SearchEventsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SearchEvents
     */
    private $searchEvents;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->searchEvents = new SearchEvents();
    }

    public function testSetName()
    {
        $this->searchEvents->setName("test");
        $this->assertEquals("test", $this->searchEvents->getName());
    }

}
