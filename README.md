# PHP バッチ処理管理システム（process-manager）

PHP 8.4 + Docker で構築された、CLIベースのバッチ処理管理ツールです。  
タスクを逐次または並列で実行し、将来的にはプロセス数の制御・ログ管理・タイムアウト制御も行えるように設計されています。

---

## 🐳 環境構成

- PHP: 8.4 CLI
- Docker / docker-compose
- 拡張: `pcntl`（並列処理で使用予定）

---

## 📂 ディレクトリ構成

```
.
├── bin/
│ └── run.php # 実行エントリポイント
├── config/
│ └── tasks.php # 実行するタスクの一覧
├── tasks/
│ ├── TaskInterface.php # タスク共通インターフェース
│ └── HelloTask.php # サンプルタスク
├── Dockerfile
├── docker-compose.yml
└── Makefile
```

---

## 🚀 セットアップ手順

Makefileに記載したコマンドを実行することで環境が立ち上がります。
