<?php
/**
 * (c) Mantas Vaitkūnas <manvait@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MantasVaitkunas\VertexSmsPhpImplementation\Tests\Integration;

use MantasVaitkunas\VertexSmsPhpImplementation\Sender;

class SenderTest extends \PHPUnit_Framework_TestCase
{
    public function testSendSuccessfully()
    {
        $apiToken = '';
        require_once 'credentials.php';
        $sender = new Sender($apiToken);
        $sender->setTo('37088888888');
        $sender->setFrom('TestSender');
        $sender->setMessage('This is test message');
        $sender->setTestMode(1);

        $this->assertSame(0, strpos($sender->send(), 'HTTP/1.1 200 OK'));
    }

    public function testSenderThrowsException()
    {
        $this->setExpectedException(\Exception::class, 'Parameters: $to, $from, $message must be set.');
        $sender = new Sender('Any token');
        $sender->send();
    }
}
