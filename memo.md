### 取り組んだ学習ページ

https://en-ambi.com/itcontents/entry/2020/10/20/103000/#%E3%82%B5%E3%83%B3%E3%83%97%E3%83%AB1-Hello-API%E3%81%AE%E5%AE%9F%E8%A3%85

### 環境構築参考

https://qiita.com/hitotch/items/2e816bc1423d00562dc2

まず先に、ほぼこの通りにやってnginxとLaravel11とmysqlの環境構築した。

* PHPには下記の2種類がある
  * Apacheと合わせて使うモジュールタイプ
  * nginxと合わせて使うCGIタイプ
    * CGIタイプはphp-fpmが主流

### 通常稼働とtest用でDBの接続先を変えたい

今回の手順では通常使用のdbとtest用のdbをhost自体分けている。

* テスト用のmysqlの用意はリポジトリのdocker-compose.ymlを見ればわかる状態。
* テストDBにマイグレーションをかける方法
  * https://hiroto-k.hatenablog.com/entry/2016/03/25/004800
* テスト実行時のDB接続先を変更する方法
  * phpunit.xmlに <server name="DB_HOST" value="mysql-test"/> を追加

可能ならDBホストは同じで、1つのMySQL上にテスト用のDBスキーマを用意した方が良さそう

### テストコード実行ごとにDBを綺麗に保つ方法

* DatabaseTransactionsとRefreshDatabaseがある。
* https://qiita.com/Kakky08/items/23bba14d58b9acd52da8
* テーブルやテスト対象の箇所が増えた時にテストの実行都度すべてのテーブルを作り直すのは非効率的なので、DatabaseTransactionsが良さそう

### テストでModelのクラスをfactoryで生成したい

* Modelクラスでuse HasFactory;する必要がある



Laravelのmiddlewareのapiを指定すると何が起きている？

* auth()->factory()->getTTL() * 60 でfactory()のところでエラーになる
  * 参考にしているのはlaravel10の手順で、laravel11だとできなくなってる？
    * ログインユーザに情報を見せるだけの箇所だから、設定ファイルからTTLの情報を表示するように対応
* 未認証で認証が必要なページにアクセス時、Route [auth] not defined.という500エラーになった
  * 未認証時のアクセスをauthという名前付きルートにリダイレクトしようとしている
  * そもそもapiリクエストなのでリダイレクトは不要
    * リクエスト側のヘッダーに Accept: application/json ヘッダーを付けてやれば、Unauthenticatedという内容のjsonが返却される
* Contollerでmiddlewareを設定する方法でexcept及びonlyにして、メソッド単位でmiddlewareの適用を調整しようとしたけどなぜか効かず、すべてのメソッドにmiddlewareが適用されてしまったのでapi.webファイルでルートに対して個別にmiddlewareを適用した


# DB操作・Eloquent

```
// クエリログを有効化して問題を検出
\DB::enableQueryLog();
// コードの実行
\DB::getQueryLog(); // 発行されたクエリを確認
```

* プロパティアクセスでリレーション先のデータを取得
  * $user->posts->first()
* リレーションメソッドでリレーション先のテーブルに対して操作
  * $user->posts()->createMany([]);


# メール送信

* Amazon WorkMail
  * リージョンはオレゴン
  * Organization作成
    * Email Domain
      * Existing Route 53 domain を選択
    * Route 53 hosted zone は任意のRoute53に登録してあるdomainを選択
    * AliasはwebメーラのURLに含まれる文字列。domainの前半などで良さそう
  * メールのドメイン設定
    * 左メニューからOrganizations選択、Domains選択、追加したdomainを選択。Improved security detailsで2項目（spfとDMARCに関するレコード）がMissingになっていることを確認
      * Route53の該当ホストゾーンの設定を開いて、TXTレコードを追加する
        * WorkMailの画面をリロードして、MissingだったのがVerifiedになっていればOK
    * 
  * メールユーザ追加
    * Organizationを選択した状態で左メニューのUsersを選択し、Add Userを押す
    * User detailで、Username, Display name, Email address(domain含む)を入力し、passwordを設定
  * メール送受信テスト
    * WorkMail標準のwebメーラーで確認
      * https://acai-blog.awsapps.com/mail
    * Thunderbirdをインストールして確認
      * IMAPの設定値（公式）
        * https://docs.aws.amazon.com/ja_jp/workmail/latest/userguide/using_IMAP.html
      * 受信サーバー
        * imap.mail.us-west-2.awsapps.com（オレゴンのホスト名）
        * ポート番号993
      * 送信サーバー
        * smtp.mail.us-west-2.awsapps.com（オレゴンのホスト名）
        * ポート番号465
      * ユーザー名はメールアドレスを設定

* SES
  * 左メニュー「設定を始める」から、メールアドレスを検証、送信ドメインを検証、テストEメールの送信、本番アクセスをリクエストの順に試す
  * AWSのサポートから、sesをどのような用途で使うのか確認したい旨のメッセージが来るので、aws上のメッセージサポート画面で返信する
    * 「主にアプリケーション開発の学習用として、アプリケーションからの通知用に使うことを想定しています。 送信数はそれほど多くならないかと思います。」という内容でひとまず解除してもらえました。
  * SESアクセスができるIAMアカウントを作成
  * composer require aws/aws-sdk-php でaws操作用のsdkをインストール
  * config/services.phpのsesのkeyを他のawsサービスと被らない様に修正
  * .envファイルに設定追加・修正
    * MAIL_FROM_ADDRESSを修正
      * 追加
        * AWS_SES_ACCESS_KEY_ID=xxxxxxxxxxxxxxxxxxxxxxxxxx
        * AWS_SES_SECRET_ACCESS_KEY=xxxxxxxxxxxxxxxxxxxxxxxxxx
        * AWS_SES_DEFAULT_REGION=us-west-2（オレゴン）
    * MAIL_MAILER=ses に変更
    * MAIL_FROM_ADDRESSのdomainより前の部分は、sesなら存在しない「noreply」等でもメール送信できる
  * tinkerでメール送信
    *  Mail::raw('test from laravel & ses', function($message) { $message->to('宛先アドレス')->subject('test'); });

# testコード

### よく使うステータスコード

assertOk() =>	200 OK（ページが問題無く表示された）
assertRedirect() =>	301, 302 などのリダイレクト。
assertForbidden() =>	403 Forbidden
assertNotFound() =>	404 Not Found
assertUnprocessable() =>	422 Unprocessable Entity（validationエラー）
assertNoContent() =>	204 No Content

### AssertableJson（FluentなAssert）

要素の有無について厳しくチェックするのがデフォルトなので、AssertExactJsonに近いが、etc()を使うことで省略もできる柔軟性を持つ。
jsonに対して配列（json）をぶつけてAssertするのではなく、Jsonの内容を検索するような形でAssertする。

### テストDB準備の手順

https://zenn.dev/nshiro/books/laravel-11-test/viewer/15_db-ready

### faker

書き方がバージョンで異なる

```
// 昔
$faker->name
$this->faker->name

// 少し昔
$this->faker->name()

// 今
fake()->name()
```

以前いた現場の方針だと、fakerは画面用のseederで使って、テストコードでは一意に定まるように使わないほうがよい。

### 実行に時間がかかるテストを見つける

```
php artisan test --profile
```