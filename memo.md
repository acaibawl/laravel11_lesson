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


