# gae-cakephp-email

A Mail Transport Class written for [CakePHP] (http://cakephp.org/) + [Google App Engine] (https://developers.google.com/appengine/docs/php) Setups.

One of the challenges when deploying CakePHP on Google App Engine for PHP 
is the fact that you cannot send out mails using the normal [CakeEmail] (http://book.cakephp.org/2.0/en/core-utility-libraries/email.html) class.

So I wrote this class to wrap around the Google App Engine [Message] (https://cloud.google.com/appengine/docs/php/mail/) class so it seamlessly works.

## Installation + Configuration

1. [Download the latest code] (https://github.com/dftaiwo/gae-cakephp-email/archive/master.zip) or clone this repo

2. Copy the Network folder from the zip to /app/Lib/

3. Lastly, update /app/Config/email.php to have at least the following

```php
<?php
 
class EmailConfig {

	public $default = array(
		'transport' => 'GAE',
	);

	 
}

```
And use the following when creating an instance of the [CakeEmail] (http://book.cakephp.org/2.0/en/core-utility-libraries/email.html) class in your code:

```php
<?php
$emailObj = new CakeEmail('default');

```


Finish!