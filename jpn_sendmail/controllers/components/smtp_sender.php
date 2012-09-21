<?php
/**
 * Qdmailコンポーネントをラッピングした日本語メール配信ライブラリ
 * 
 * <p>組み込んでいるQdmailはプラグイン化するために改修しています。</p>
 * <p><del>MTAは「qmail」を使う前提でカスタマイズしています。</del><br />
 * MTAは「Postfix」を使う前提で再度調整しています。</p>
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

// デフォルトの設定です。
// Controler::beforeFilter などで上書きしてください。
Configure::load('JpnSendmail.jpn_sendmail');
Configure::load('JpnSendmail.smtp');

/**
 * メールをSMTP送信
 * 
 * @author IKEDA Youhei <youhey.ikeda@gmail.com>
 */
class SmtpSenderComponent extends Object {

    /** メールの設定 */
    const 
        EMAIL_CHARSET  = 'iso-2022-jp', 
        EMAIL_ENCODING = '7bit';

    /**
     * 使用するコンポーネント
     * 
     * @var array
     */
    public $components = array('JpnSendmail.Qdmail');

    /**
     * コンポーネントの初期化
     * 
     * <p>Qdmailモジュールを必要最小限のみ設定する。</p>
     * 
     * @param  Controller $controller
     * @return void
     */
    public function startup(Controller $controller) {
        $this->Controller = $controller;
        $this->Qdmail->startup($controller);
        $this->Qdmail->message_id = false;

        $this->Qdmail->errorDisplay(false);
        $this->Qdmail->smtp(true);

        $smtp = $this->Qdmail->smtpObject();
        $smtp->error_display = false;
    }

    /**
     * メールを送信する
     * 
     * <p>QdmailのメールレンダリングがDebugKitの拡張とバッティングする。<br />
     * デバッグモードでエラーがになるので、必要部分のみ設定を変える</p>
     * 
     * @param string $email メール送信先
     * @param strin g$subject メール件名
     * @param mixed $content テンプレートに差し替える本文
     * @param string|null $template テンプレート
     * @return boolean 送信結果（SMTPへの中継に成功すればTRUE）
     * @link http://hal456.net/qdmail/
     * @link http://hal456.net/qdmail/cakebase
     * @link http://hal456.net/qdmail/smtp
     * @link http://hal456.net/qdsmtp/
     */
    public function sendmail($email, $subject, $content, $template = null)
    {
        $encoding = Configure::read('App.encoding');

        $buf = $this->Qdmail->Controller->view;
        if ($buf !== 'View') {
            $this->Qdmail->Controller->view = 'View';
        }

        $setting = $this->buildSmtpSettings();
        $this->Qdmail->smtpServer($setting);
        $this->Qdmail->to($email);
        $this->Qdmail->subject($subject);
        $this->Qdmail->cakeText($content, 
                                $template, 
                                null, 
                                $encoding, 
                                self::EMAIL_CHARSET, 
                                self::EMAIL_ENCODING);

        $from    = Configure::read('JpnSendmail.mailFrom');
        $name    = Configure::read('JpnSendmail.mailName');
        $replyto = Configure::read('JpnSendmail.mailReplyto');
        if (!empty($name)) {
            $this->Qdmail->from($from, $name);
        } else {
            $this->Qdmail->from($from);
        }
        $this->Qdmail->replyto($replyto);

        $result = $this->Qdmail->send();
        if ($result === false) {
            $message = 'SendmailComponent can not send email.';
            $this->log($message, LOG_ERROR);
        }

        $this->Qdmail->Controller->view = $buf;

        return 
            ($result === true);
    }

    /**
     * メール送信のためにSMTPの設定を組み立てる
     * 
     * @return array SMTPからのメール送信情報
     */
    private function buildSmtpSettings() {
        $host     = Configure::read('JpnSendmail.smtpHost');
        $port     = Configure::read('JpnSendmail.smtpPort');
        $protocol = Configure::read('JpnSendmail.smtpProtocol');
        $from     = Configure::read('JpnSendmail.mailReplyto');
        $setting  = array(
                'host'     => $host, 
                'port'     => $port, 
                'protocol' => $protocol, 
                'from'     => $from, 
            );

        return $setting;
    }
}
