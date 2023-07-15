
# Yii2 Forum Extension
This Yii2 extension offers a simple set of forum features that make it easy to integrate a full-featured forum into your web application.

⚠️ This extension is currently **WIP**. Feel free to _Watch_ this repo for the first release or [contribute](https://github.com/2rats/yii2-forum#contributions). Thank you. ⚠️

## Requirements

 - PHP 7.4 or higher
 - Yii2 Framework
 - User table migration

## Instalation
This extension is designed to be used out of the box. Once you have installed the necessary migrations and configured the module in your application structure, you'll be ready to go.

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

```
$ composer require 2rats/yii2-forum "@dev"
```
### Apply database migrations


#### 1. User table 
First of all you have to setup and apply your **User table** migration using:
```
$ php yii migrate
```
#### 2. Extension
Then you have to to apply migrations of this extension:

```
$ php yii migrate --migrationPath=@rats/forum/migrations
```

## Contributions
Contributions are highly appreciated. If you would like to contribute, please fork the repository, make your changes, and submit a pull request or open an issue.

## Authors

- [@kazda01](https://www.github.com/kazda01)
- [@mifka01](https://www.github.com/mifka01)

## License

[BSD 3-Clause License](https://github.com/2rats/yii2-forum/blob/main/LICENSE)


