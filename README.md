# Pusher Beams - push notifications for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/neo/pusher-beams.svg?style=flat-square)](https://packagist.org/packages/neo/pusher-beams)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/neoighodaro/pusher-beams/master.svg?style=flat-square)](https://travis-ci.org/neoighodaro/pusher-beams)
[![StyleCI](https://styleci.io/repos/65379321/shield)](https://styleci.io/repos/65379321)
[![Quality Score](https://img.shields.io/scrutinizer/g/neoighodaro/pusher-beams.svg?style=flat-square)](https://scrutinizer-ci.com/g/neoighodaro/pusher-beams)

This package makes it easy to send [Pusher push notifications](https://docs.pusher.com/push-notifications) with Laravel (should work with other non-laravel PHP projects). It's based off [this package](https://github.com/laravel-notification-channels/pusher-push-notifications) by Mohamed Said.

## Contents

- [Installation](#installation)
	- [Setting up your Pusher account](#setting-up-your-pusher-account)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

You can install the package via composer:

``` bash
composer require neo/pusher-beams
```
...

### Setting up your Pusher account
...


## Usage

Update your `.env` file with the following keys:

```
PUSHER_BEAMS_SECRET_KEY="PUSHER_BEAMS_SECRET_KEY"
PUSHER_BEAMS_INSTANCE_ID="PUSHER_BEAMS_INSTANCE_ID"
```

> 💡 You need to replace the PUSHER_BEAMS_SECRET_KEY and PUSHER_BEAMS_INSTANCE_ID keys with the keys gotten from your Pusher dashboard.

Open the broadcasting.php file in the config directory and add the following keys to the pusher connection array:

    'connections' => [
        'pusher' => [
            // [...]

            'beams' => [
                'secret_key' => env('PUSHER_BEAMS_SECRET_KEY'),
                'instance_id' => env('PUSHER_BEAMS_INSTANCE_ID'),
            ],

            // [...]
        ],
    ],


Next, create a new notification class where we will add our push notification. In your terminal run the command below to create the class:

    $ php artisan make:notification UserCommented
    
This will create a new `UserCommented` class in the `app/Notifications` directory. Open the file and you can do something similar to this sample class:

    <?php

    namespace App\Notifications;

    use Illuminate\Bus\Queueable;
    use Illuminate\Notifications\Notification;
    use Neo\PusherBeams\PusherBeams;
    use Neo\PusherBeams\PusherMessage;
    use App\User;
    use App\PhotoComment;
    use App\Photo;

    class UserCommented extends Notification
    {
        use Queueable;

        public $user;

        public $comment;

        public $photo;

        public function __construct(User $user, Photo $photo, PhotoComment $comment)
        {
            $this->user = $user;
            
            $this->comment = $comment;
            
            $this->photo = $photo;
        }

        public function via($notifiable)
        {
            return [PusherBeams::class];
        }

        public function toPushNotification($notifiable)
        {
            return PusherMessage::create()
                ->iOS()
                ->sound('success')
                ->title('New Comment')
                ->body("{$this->user->name} commented on your photo: {$this->comment->comment}")
                ->setOption('apns.aps.mutable-content', 1)
                ->setOption('apns.data.attachment-url', $this->photo->image);
        }

        public function pushNotificationInterest()
        {
            $id = $this->photo->id;

            $audience = strtolower($this->user->settings->notification_comments);

            return "photo_{$id}-comment_{$audience}";
        }
    }
    
In the class above we are extending a Notification class and we have implemented the toPushNotification method, which will be used to send the push notification when required. In the via method, we specify what channels we want to send the notification through and in the pushNotificationInterest we specify the interest we want to publish the push notification to.


### Sending to multiple platforms
...

### Routing a message
...

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email neo@creativitykills.co instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Neo Ighodaro](https://github.com/neoighodaro)
- Mohamed Said
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
