<?php
/**
 * Qdmailコンポーネントをラッピングした日本語メール配信ライブラリ
 * 
 * PHP versions >= 5.2
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 * @since   JpnSendmail 1.0
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::import('Controller', 'Controller', false);
App::import('Component', array('JpnSendmail.SmtpSender', 'JpnSendmail.Qdmail'));

Mock::generate('QdmailComponent');

class SmtpSenderTestController extends Controller {
    public 
        $uses = array(), 
        $params = array();
}

/**
 * メールをSMTP送信するコンポーネントのテストケース
 * 
 * @author IKEDA Youhei <youhey.ikeda@gmail.com>
 */
class SmtpSenderComponentTest extends CakeTestCase {

    public 
        $autoFixtures = false, 
        $fixtures     = array();

    private $encoding = null;

    public function startTest() {
        $this->Qdmail = new MockQdmailComponent;
        $this->Qdmail->message_id = null;

        $this->Qdmail->setReturnValue('startup', '');
        $this->Qdmail->setReturnValue('errorDisplay', '');
        $this->Qdmail->setReturnValue('smtp', '');
        $this->Qdmail->setReturnValue('smtpServer', '');
        $this->Qdmail->setReturnValue('from', '');
        $this->Qdmail->setReturnValue('replyto', '');

        $this->Qdmail->setReturnValue('to', '');
        $this->Qdmail->setReturnValue('subject', '');
        $this->Qdmail->setReturnValue('cakeText', '');
        $this->Qdmail->setReturnValueAt(0, 'send', true, '1回目は送信成功');
        $this->Qdmail->setReturnValueAt(1, 'send', false, '2回目は送信成功');
    }
    public function endTest() {
        $this->Qdmail = null;
        ClassRegistry::flush();
    }

    public function test：startupメソッドをテスト() {
        $controller = new SmtpSenderTestController();

        $setting = array('host' => 'localhost', 'port' => '25', 'protocol' => 'SMTP');

        $this->Qdmail->expectOnce('startup', array($controller));
        $this->Qdmail->expectOnce('errorDisplay', array(false));
        $this->Qdmail->expectOnce('smtp', array(true));
        $this->Qdmail->expectOnce('smtpServer', array($setting));

        $SmtpSender = new SmtpSenderComponent;
        $SmtpSender->Qdmail = $this->Qdmail;
        $SmtpSender->startup($controller);

        $result   = $SmtpSender->Controller;
        $expected = $controller;
        $this->assertIdentical($expected, $result);
    }

    public function test：sendmailメソッドをテスト() {
        $送信先 = 'postmaster@example.com';
        $件名   = 'テストメール';
        $本文   = 'これはテストメールです';

        $this->Qdmail->expectOnce('to', array($送信先));
        $this->Qdmail->expectOnce('subject', array($件名));
        $this->Qdmail->expectOnce('cakeText', array($本文, null, null, 'UTF-8', 'iso-2022-jp', '7bit'));

        $SmtpSender = new SmtpSenderComponent;
        $SmtpSender->Qdmail = $this->Qdmail;
        $SmtpSender->Qdmail->Controller = new Controller;

        // 一回目は送信成功
        $result = $SmtpSender->sendmail($送信先, $件名, $本文);
        $this->assertTrue($result);
    }

    public function test：sendmailメソッドの送信可否をテスト() {
        $送信先 = 'postmaster@example.com';
        $件名   = 'テストメール';
        $本文   = 'これはテストメールです';

        $SmtpSender = new SmtpSenderComponent;
        $SmtpSender->Qdmail = $this->Qdmail;
        $SmtpSender->Qdmail->Controller = new Controller;

        // 一回目は送信成功
        $result = $SmtpSender->sendmail($送信先, $件名, $本文);
        $this->assertTrue($result);

        // 二回目は送信失敗
        $result = $SmtpSender->sendmail($送信先, $件名, $本文);
        $this->assertFalse($result);
    }
}
