
# Yii2 Forum Extension
This Yii2 extension offers a simple set of forum features that make it easy to integrate a full-featured forum into your web application.

⚠️ This extension is currently **WIP**. Feel free to _Watch_ this repo for the first release or [contribute](https://github.com/2rats/yii2-forum#contributions). Thank you. ⚠️

## Requirements

 - PHP 7.4 or higher
 - Yii2 Framework
 - Yii2 RBAC
 - User table migration

## Instalation
This extension is designed to be used out of the box. Once you have installed the necessary migrations and configured the module in your application structure, you'll be ready to go.

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

```
composer require 2rats/yii2-forum "@dev"
```

### Apply database migrations
#### 1. User table and RBAC
First of all you have to setup and apply your **User table** and **RBACK** migration using:
```
php yii migrate
php yii migrate --migrationPath=@yii/rbac/migrations
```
#### 2. Extension
Then you have to to apply migrations of this extension:

```
php yii migrate --migrationPath=@rats/forum/migrations
php yii migrate --migrationPath=@rats/forum/migrations/rbac
```

### Modify database connection
This extension parses text emoji to unicode symbols (for example ':D' => '😃'). For proper saving to the database, it is necessary to have the correct charset both in the database settings and in the connection to the database in the `config/db.php` configuration file.

For example, you can use **utf8mb4** charset.

```php
<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=test_db_name',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
];
```

## Configuration

### Module configuration

Configuration options of the module in `config/web.php`.

| option      | default value       | description                                                                      |
|-------------|---------------------|----------------------------------------------------------------------------------|
| userClass   | `'app\models\User'` | Class that represents app User. <br>The class must extend `\yii\db\ActiveRecord` |
| forumLayout | `'forum'`           | Layout that is used in forum sites                                               |
| adminLayout | `'admin'`           | Layout that is used in administration of the forum                               |

### Parameters configuration

Module behavior can also be modified by adding parameters in your configuration file `config/params.php`.

| option                | default value                                                                | description                                                                                                                                                                                                               |
|-----------------------|------------------------------------------------------------------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| migrationTableOptions | `'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB'` \| `null` | `$options` for [createTable() function](https://www.yiiframework.com/doc/api/2.0/yii-db-migration#createTable()-detail). <br>By default, it sets charset and engine for the mysql database driver, `null` for the others. |
| userTableName         | `'user'`                                                                     | Table name of the Class, that represents app User.                                                                                                                                                                        |

## Contributions
Contributions are highly appreciated. If you would like to contribute, please fork the repository, make your changes, and submit a pull request or open an issue.

## Authors

- [@kazda01](https://www.github.com/kazda01)
- [@mifka01](https://www.github.com/mifka01)

## License

[BSD 3-Clause License](https://github.com/2rats/yii2-forum/blob/main/LICENSE)


