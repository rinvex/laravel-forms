# Rinvex Forms

**Rinvex Forms** is a dynamic form builder for Laravel, it's like the missing gem, the possibilities of using it are endless! With flexible API and powerful features you can build almost every kind of complex form with ease.

[![Packagist](https://img.shields.io/packagist/v/rinvex/laravel-forms.svg?label=Packagist&style=flat-square)](https://packagist.org/packages/rinvex/laravel-forms)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/rinvex/laravel-forms.svg?label=Scrutinizer&style=flat-square)](https://scrutinizer-ci.com/g/rinvex/laravel-forms/)
[![Travis](https://img.shields.io/travis/rinvex/laravel-forms.svg?label=TravisCI&style=flat-square)](https://travis-ci.org/rinvex/laravel-forms)
[![StyleCI](https://styleci.io/repos/138185596/shield)](https://styleci.io/repos/138185596)
[![License](https://img.shields.io/packagist/l/rinvex/laravel-forms.svg?label=License&style=flat-square)](https://github.com/rinvex/laravel-forms/blob/develop/LICENSE)


## Installation

1. Install the package via composer:
    ```shell
    composer require rinvex/laravel-forms
    ```

2. Publish resources (migrations and config files):
    ```shell
    php artisan rinvex:publish:forms
    ```

3. Execute migrations via the following command:
    ```shell
    php artisan rinvex:migrate:forms
    ```

4. Done!


## Warning

⚠️ This documentation ins INCOMPLETE! Please use on your own, or wait until it's ready! ⚠️

## Usage

To add forms to other entities, simply use `\Rinvex\Forms\Traits\HasForms` trait in the model. Example: you may have `Event` model, that requires registration form, then confirmation form, and every form fields differs from an event to another, in this case you can attach `From` models to your `Event` models using that trait.

To add form responses to users, simply use `\Rinvex\Forms\Traits\HasFormResponses` trait in the model. Example: you most probably will allow your users to fill forms while signed in, in such case you need to attach their responses to their accounts for later retrieval, and you can do so using that trait. This will attach `FormResponse` models to your user models `User`.

### Manage your forms

```php
// Get instance of your parent model (ex. `Event`)
$event = new \App\Models\Event::find(1);

// Create multiple new forms
$event->forms()->createMany([
    [...],
    [...],
    [...],
]);

// Find an existing form
$form = app('rinvex.forms.form')->find(1);

// Update an existing form
$form->update([
    'name' => 'Contact Us',
]);

// Delete form
$form->delete();

// Alternative way of form deletion
$event->forms()->where('id', 123)->first()->delete();

// Get attached forms collection
$event->forms;

// Get attached forms query builder
$event->forms();
```


## Changelog

Refer to the [Changelog](CHANGELOG.md) for a full history of the project.


## Support

The following support channels are available at your fingertips:

- [Chat on Slack](https://bit.ly/rinvex-slack)
- [Help on Email](mailto:help@rinvex.com)
- [Follow on Twitter](https://twitter.com/rinvex)


## Contributing & Protocols

Thank you for considering contributing to this project! The contribution guide can be found in [CONTRIBUTING.md](CONTRIBUTING.md).

Bug reports, feature requests, and pull requests are very welcome.

- [Versioning](CONTRIBUTING.md#versioning)
- [Pull Requests](CONTRIBUTING.md#pull-requests)
- [Coding Standards](CONTRIBUTING.md#coding-standards)
- [Feature Requests](CONTRIBUTING.md#feature-requests)
- [Git Flow](CONTRIBUTING.md#git-flow)


## Security Vulnerabilities

If you discover a security vulnerability within this project, please send an e-mail to [help@rinvex.com](help@rinvex.com). All security vulnerabilities will be promptly formed.


## About Rinvex

Rinvex is a software solutions startup, specialized in integrated enterprise solutions for SMEs established in Alexandria, Egypt since June 2016. We believe that our drive The Value, The Reach, and The Impact is what differentiates us and unleash the endless possibilities of our philosophy through the power of software. We like to call it Innovation At The Speed Of Life. That’s how we do our share of advancing humanity.


## License

This software is released under [The MIT License (MIT)](LICENSE).

(c) 2016-2021 Rinvex LLC, Some rights reserved.
