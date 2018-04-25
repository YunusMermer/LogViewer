<?php namespace Arcanedev\LogViewer\Tests\Commands;

use Arcanedev\LogViewer\Tests\TestCase;
use Arcanedev\LogViewer\Contracts\LogViewer;

/**
 * Class     ClearLogsCommandTest
 *
 * @package  Arcanedev\LogViewer\Tests\Commands
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ClearLogsCommandTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */
    private $logViewer;

    protected function setUp()
    {
        parent::setUp();

        $this->logViewer = app(LogViewer::class);

        $this->setupEnvironment();
    }
    
    /**
     * resets the log path to logs since we changed it temporarily
     * 
     */
    protected function tearDown()
    {
        config(['log-viewer.storage-path' => storage_path('logs')]);

        parent::tearDown();
    }
    
    /* -----------------------------------------------------------------
    |  Tests
    | -----------------------------------------------------------------
    */
    
    /** @test */
    
    public function it_can_delete_all_log_files()
    {
        static::assertEquals(0, $this->logViewer->count());

        $this->createDummyLog(date('Y-m-d'), 'custom-logs');

        static::assertEquals(1, $this->logViewer->count());
        
        static::assertSame(0, $this->artisan('log-viewer:clear'));

        static::assertEquals(0, $this->logViewer->count());
    }
    
    /**
     * Sets the log storage path temporarily to a new directory
     * 
     */
    
    private function setupEnvironment()
    {
        if (!file_exists($path = storage_path('custom-logs'))) 
        {
            mkdir($path, 0777, true);
        }

        $this->logViewer->setPath($path);

        config(['log-viewer.storage-path' => $path]);
    }
}
