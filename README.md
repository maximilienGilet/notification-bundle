<h1 align="center">mgilet/notification-bundle</h1>

<p align="center">
A simple Symfony bundle to notify user
<br>
<br>
<a href="https://packagist.org/packages/mgilet/notification-bundle"><img src="https://poser.pugx.org/mgilet/notification-bundle/v/stable" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/mgilet/notification-bundle"><img src="https://poser.pugx.org/mgilet/notification-bundle/v/unstable" alt="Latest Unstable Version"></a>
<a href="https://packagist.org/packages/mgilet/notification-bundle"><img src="https://poser.pugx.org/mgilet/notification-bundle/downloads" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/mgilet/notification-bundle"><img src="https://poser.pugx.org/mgilet/notification-bundle/license" alt="License"></a>
</p>

<p align="center">
<a href="https://insight.sensiolabs.com/projects/697abbcc-4b15-418a-a6c9-e662787fed48"><img src="https://insight.sensiolabs.com/projects/697abbcc-4b15-418a-a6c9-e662787fed48/big.png" alt="SensioLabsInsight"></a>
</p>

<p align="center"><img src="http://i.imgur.com/07OcF6c.gif" alt="mgilet/notificationBundle"></p>

Create and manage user notifications in an efficient way.

Symfony support :
  * 2.7.x
  * 2.8.x
  * 3.x
 
Bootstrap > 3.x highly recommended

## Features

- Easy notification management
- Simple Twig render method
- Pretty Twig template (dropdown using Bootstrap 3)
- Fully customizable
- Easy setup
- No bloated dependencies (little requirements)

Notice: Only Doctrine ORM is supported for now.



## Installation & usage

### Installation

This bundle is available on [packagist](https://packagist.org/packages/mgilet/notification-bundle).

Notice : The bundle is actually in alpha state (no major issue encountered)

In order to install it, add the following line in your composer.json

```json
// composer.json

...
"require": {
 "mgilet/notification-bundle": "dev-master",
},
```

Then perform a 

```bash
$ composer install
```

This will install the latest commited version of the master branch.

When a stable version will come out you will just have to enter the following command:

```bash
$ composer require mgilet/notification-bundle
```

See [documentation](Resources/doc/index.rst) for next steps

### Basic usage

```php
class DefaultController extends Controller
{

    ...

    /**
     * @Route("/send-notification", name="send_notification")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function sendNotification(Request $request)
    {
        $manager = $this->get('mgilet.notification');
        $notif = $manager->generateNotification('Hello world !');
        $notif
         ->setMessage('This a notification.')
         ->setLink('http://symfony.com/');
        $manager->addNotification($this->getUser(), $notif);

        // or the one-line method :
        // $manager->createNotification($this->getUser(), 'Notification subject','Some random text','http://google.fr');

        return $this->redirectToRoute('homepage');
    }
```

See [HERE](Resources/doc/usage.rst) for more

## Translations

For now this bundle is only translated to de, en, es, fr, it.

Help me improve this by submitting your translations.

## Community

You can help make this bundle better by contributing (every pull request will be considered) or submitting an issue.

Enjoy and share if you like it.

## Licence
MIT
