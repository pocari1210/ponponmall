<h1>◆ponponmall◆</h1>

<p>stripeのライブラリを活用し、モール形式のネットショップを構築しました。</p>
<a href ="http://18.182.16.40/ponponmall/public/">サイトはこちら</a><br><br>

<h3>使用したライブラリ</h3>
php:8.02<br>
Laravel/Mix<br>
breeze:1<br>
stripe:10.12<br>
intervention/image:2.7<br>

<h2>◆admin◆</h2>
admin@admin.com<br>
password123<br><br>

<h3>★shop一覧ページ★</h3>
登録されているshop,shopを登録することができます<br><br>

<img src = "https://github.com/pocari1210/ponponmall/assets/98627989/9e98abf4-1279-495b-99ec-73500a93c297" width = 300px height=200px>
<img src = "https://github.com/pocari1210/ponponmall/assets/98627989/5a9ab551-d882-4360-980a-d109e1ce0fd0" width = 300px height=200px>
<br>

<h3>★期限切れShop管理ページ★</h3>
shop一覧ページで削除ボタンを押したものが登録され、期限切れShop管理ページで<br>
完全削除、復元を行うことができます。(ソフトデリート機能)<br><br>
<img src = "https://github.com/pocari1210/ponponmall/assets/98627989/10dc9772-72c4-4912-bdc5-e012ab67dc09" width = 300px height=200px><br>

<h2>◆owner◆</h2>
test1@test.com<br>
password123<br><br>

<h3>★shop一覧ページ★</h3>
Shopのトップ画像、説明を編集することができます<br>
<img src = "https://github.com/pocari1210/ponponmall/assets/98627989/92b2deca-f4ec-4c2f-bdd5-8c8a39c41357" width = 300px height=200px><br><br>

<h3>★画像管理ページ★</h3>
商品画像を登録し、管理を行うことができます。<br><br>
<img src = "https://github.com/pocari1210/ponponmall/assets/98627989/e3df81f6-13bb-42f6-a217-ca28239ec317" width = 300px height=200px>
<img src = "https://github.com/pocari1210/ponponmall/assets/98627989/a1755cf3-cf21-4f24-ba1a-4537d9641b24" width = 300px height=200px><br><br>

<h3>★画像管理ページ★</h3>
商品名、説明、在庫、カテゴリーを入力し、商品を登録することができます<br>
商品の画像は、画像管理ページから選択し、5枚まで登録できる仕様になっています。<br><br>
<img src = "https://github.com/pocari1210/ponponmall/assets/98627989/e9b73b04-f175-4762-bcd3-595e30af2a1b" width = 300px height=200px>
<img src = "https://github.com/pocari1210/ponponmall/assets/98627989/62d671ff-6cb9-496c-b287-b84be4d95a51" width = 300px height=200px><br><br>

<h2>◆user◆</h2>
user@user.com<br>
password123<br>

<h3>★ホームページ★</h3>
登録された商品が陳列されており、カテゴリー、キーワード、おすすめ順、表示件数の検索機能を<br>
実装しました<br><br>

<img src = "https://github.com/pocari1210/ponponmall/assets/98627989/fee4418e-009c-45bf-a3d4-e701bcf74e61" width = 300px height=200px><br><br>

商品を選択したら、商品の詳細ページに進み、カートに追加し、商品を購入することが<br>
できます(stripe決済)<br><br>
<img src = "https://github.com/pocari1210/ponponmall/assets/98627989/a309a8f2-41d4-4bbf-97ef-1cc8c62c543e" width = 300px height=200px>
<img src = "https://github.com/pocari1210/ponponmall/assets/98627989/a14a5a53-765a-4138-be50-093bc66003aa" width = 300px height=200px><br><br>

<h2>作成した背景</h2>
以前ASPのネットショップの営業をしていたことがあり、<br>
自分自身もネットショップを構築をしていみたいと思い、<br>
ネットショップを作成しました。

<h2>コンセプト</h2>
下記の3つのグループを作成し、マルチログイン機能にて、<br>
ログインできる仕様にしました。<br><br>

・admin(サイトの管理者)<br>
主にownerの管理をし、ownerの追加、softDelete機能を実装し、<br>
運営者の登録、契約期間の過ぎたshopを論理削除できる仕様になっています。<br>

・owner(ショップの運営者)<br>
商品画像の管理、販売する商品の登録、編集を行える仕様になっています。<br>
4枚まで一つの商品に対し、画像を登録する子ができ、カルーセルで画像を<br>
スライドショーで閲覧させることができます。
<br>

・user(商品の購入をできるアカウント)<br>
商品の表示件数やカテゴリーの絞り込み、商品の検索機能を実装しました。<br>
カートページに進み、決済が完了をしたら、商品注文の完了メールが送信され、<br>
購入された在庫数が減る仕様となっています。

<h2>身についたこと・理解できたこと</h2>

・Laravelを使用する上で、swiper.jsやstripe、micromodalなどのライブラリの導入方法の
理解に繋がった<br>

・Eloquantを用い、データベースの情報の表示方法を身に着けることができた。<br>

・楽観的ロックを学習し、同じタイミングで別のユーザーが処理を実行をした際の、
処理の方法を身に着けることができた。

・scopeを用いて、カテゴリーや、表示件数の絞り込み、キーワードw抽出した<br>
検索機能の実装方法を身に着けることができた。

<h2>今後の課題点(できるようになりたいこと)</h2>

・AWSでデプロイを行いましたが、サーバーエラー(500)が出てしまうことがあるので、<br>
問題なくサイトを開けるようにしたい<br>

・adminページで手動でowner情報を作成をしている仕様だが<br>
サブスクリプションの機能を実装し、自動でownerを登録できるようにする<br>

・adminページにて主導でownerを論理削除をする仕様だが、<br>
契約期間がすぎたら、自動で契約削除ページに移行させるようにしたい<br>

・メール機能では、商品が購入をされた際、ownerとuserに非同期でメールが<br>
送信をされる仕様になっているが、php artisan queue:workのコマンドで<br>
たまったメールを送信する流れになっているので、<br>
supervisorで監視をできるようにしたい<br>

・micromodalのポップアップと画像が重なってしまっているので、<br>
CSSの学習も行い、不具合を修正していきたい<br>

・ajaxを用いたAPIの情報の活用方法もみにつけたいので、<br>
javascriptの学習も行っていきたい。<br>

・会員グループや、ポイント機能も実装し、会員グループごとに<br>
ポイントの還元率の設定、割引率などの変更をできるようにしたい<br>
