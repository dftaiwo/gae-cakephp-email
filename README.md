# gae-cakephp-email

A Mail Transport Class written for [CakePHP] (http://cakephp.org/) + [Google App Engine] (https://developers.google.com/appengine/docs/php) Setups.

One of the challenges when deploying CakePHP on Google App Engine for PHP 
is the fact that you cannot send out mails using the normal CakeEmail class.

So I wrote this class to wrap around the Google App Engine Message class so it seamlessly works

## Installation + Configuration

1. Clone this repo

2. Copy the Network folder to /app/Lib/

3. Lastly, update /app/Config/email.php to have at least the following

```php
<?php
 
class EmailConfig {

	public $default = array(
		'transport' => 'GAE',
	);

	 
}

```


Finish!