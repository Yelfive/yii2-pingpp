Description
===========

This is just a yii2 wrapper for Ping++([Ping Plus Plus](http://www.pingxx.com))

Usage
-----

### Configure
```php
    
    # maim-local.php
    
    return [
        // other config ...
        'components' =>  [
            // other components
            'pingpp' => [
                'class' => 'fk\pingpp\Component',
                'apiKey' => 'sk_test_SCiTC8sd8j2lk34bP',
                'appId' => 'app_Cu1azP0y5Gst2mx03Y',
            ],
            // other components
        ]
        // other config ...
        
    ]

```

### Call

```php
    Yii::$app->pingpp->charge();
    Yii::$app->pingpp->transfer();
```
    
### Especially

Many params can be passed by calling `setXXX()`    

For Example

```php
    Yii::$app->pingpp
        ->setClientIp('127.0.0.1')
        ->setChannel('wx_pub')
        ->setApiKey('sk_test_123ksdfj')
        ->charge($orderNo);
```
For more details, please refer to `fk\pingpp\Component`