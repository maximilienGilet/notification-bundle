<h1 align="center">mgilet/notification-bundle</h1>

<p align="center">
An easy yet powerful notification bundle for Symfony
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

Create and manage notifications in an efficient way.

Symfony support :
  * 2.7.x (bundle version 2.x)
  * 2.8.x (bundle version 2.x)
  * 3.x
  * 4.x

## NOW SUPPORTS SYMFONY FLEX !

Version 3.0 out now.

## Features

- Easy setup
- Easy to use
- Powerful notification management
- Simple Twig render methods
- Fully customizable
- Multiple notifiables entities
- No bloated dependencies (little requirements)

Notice: Only Doctrine ORM is supported for now.

## Upgrade from 3.x

### Custom Notification entity usage

If you use a custom Notification entitiy you must remove the `resolve_target_entities` directive from the doctrine configuration and add a configuration file.

### Example

After adding the customized notification class as in #39 just add a configuration file

`config/packages/mgilet_notification.yaml`

```yaml
mgilet_notification:
  notification_class: App\Entity\MyCustomNotification
```



## Installation & usage

This bundle is available on [packagist](https://packagist.org/packages/mgilet/notification-bundle).

Add notification-bundle to your project :

```bash
$ composer require mgilet/notification-bundle
```

**See [documentation](Resources/doc/index.rst) for next steps**

### Basic usage

```php
class MyController extends Controller
{

    ...

    public function sendNotification(Request $request)
    {
      $manager = $this->get('mgilet.notification');
      $notif = $manager->createNotification('Hello world!');
      $notif->setMessage('This a notification.');
      $notif->setLink('https://symfony.com/');
      // or the one-line method :
      // $manager->createNotification('Notification subject', 'Some random text', 'https://google.fr/');

      // you can add a notification to a list of entities
      // the third parameter `$flush` allows you to directly flush the entities
      $manager->addNotification(array($this->getUser()), $notif, true);

      ...
    }
```

## Translations

For now this bundle is only translated to de, en, es, fa, fr, it, nl, pt_BR, pl.

Help me improve this by submitting your translations.

## Community

You can help make this bundle better by contributing (every pull request will be considered) or submitting an issue.

Enjoy and share if you like it.

## Licence
MIT
