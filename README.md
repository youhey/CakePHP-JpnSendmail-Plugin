# CakePHP-JpnSendmail-Plugin

PHPのマルチバイト環境（特に日本語）に特化している多機能なメール送信モジュール「Qdmail」を、CakePHPから簡単に利用するためのプラグインです。

## 使用例

メールを送信したいクラスで「JpnSendmail.SmtpSender」コンポーネントを有効にします。
下記はメールを送信する単純なロジックです。

    class ExampleController extends AppController {
        public $components = array(
                'JpnSendmail.SmtpSender',
            );
    
        public function beforeFilter() {
            parent::beforeFilter();
    
            $from    = 'info@example.com';
            $name    = 'Web Master';
            $replyto = 'webmaster@example.com';
            Configure::write('JpnSendmail.mailFrom', $from);
            Configure::write('JpnSendmail.mailName', $name);
            Configure::write('JpnSendmail.mailReplyto', $replyto);
        }
    
        public function sendmail($username, $email) {
            // username...
            // email...
    
            $template = 'secret'
            $secretId = 'hogehoge';
            $content  = array(
                    'SecretId' => $secretId,
                    'UserName' => $username,
                );
    
            $this->SmtpSender->sendmail($email, $subject, $content, $template);
        }
    }

また、上記で設定した「secret」のビューも作成します。
app/views/elements/email/text/secret.ctp

    <?php echo Set::extract('UserName', $content) ?>様
    
    お申込日：<?php echo date('Y年n月j日 H:i:s').PHP_EOL ?>
    
    メールを送るからよろしくね。
    
    秘密のID：<?php echo Set::extract('SecretId', $content).PHP_EOL ?>
    大事にしてね。
