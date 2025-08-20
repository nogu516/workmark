環境構築

Docker ビルド

・https://github.com/nogu516/workmark.git

・docker-compose up -d --build

Laravel 環境構築

・docker-compose exec php bash

・composer install

・.env.example ファイルから.env を作成し、環境変数を変更

・php artisan key:generate

・php artisan migrate

・php artisan db:seed

開発環境

・ログイン画面　　　　： http://localhost/login

・会員登録画面　　： http://localhost/register

・phpMyAdmin 　　　　: http://localhost:8080

使用技術 ・PHP :8.2.28 ・Laravel:11.0 ・MySQL :8.0.26 ・nginx :1.21.1 ・jquery

ER 図 ・新模擬案件2 のテーブル仕様書