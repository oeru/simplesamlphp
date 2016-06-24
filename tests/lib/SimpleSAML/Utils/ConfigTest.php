<?php

/**
 * Tests for SimpleSAML\Utils\Config
 */
class Utils_ConfigTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test default config dir with not environment variable
     */
    public function testDefaultConfigDir()
    {
        // clear env var
        putenv('SIMPLESAMLPHP_CONFIG_DIR');
        $configDir = \SimpleSAML\Utils\Config::getConfigDir();

        $this->assertEquals($configDir, dirname(dirname(dirname(dirname($_SERVER['DOCUMENT_ROOT'])))) . '/config');
    }

    /**
     * Test valid dir specified by env var overrides default config dir
     */
    public function testEnvVariableConfigDir()
    {
        putenv('SIMPLESAMLPHP_CONFIG_DIR=' . $_SERVER['DOCUMENT_ROOT']);
        $configDir = \SimpleSAML\Utils\Config::getConfigDir();

        $this->assertEquals($configDir, $_SERVER['DOCUMENT_ROOT']);
    }

    /**
     * Test invalid dir specified by env var results in a thrown exception
     */
    public function testInvalidEnvVariableConfigDirThrowsException()
    {
        // I used a random hash to ensure this test directory is always invalid
        $invalidDir = $_SERVER['DOCUMENT_ROOT'] . '/e9826ad19cbc4f5bf20c0913ffcd2ce6';
        putenv('SIMPLESAMLPHP_CONFIG_DIR=' . $invalidDir);

        $this->setExpectedException(
            'InvalidArgumentException',
            'Config directory specified by environment variable SIMPLESAMLPHP_CONFIG_DIR is not a directory.  ' .
            'Given: "' . $invalidDir . '"'
        );

        \SimpleSAML\Utils\Config::getConfigDir();
    }
}
