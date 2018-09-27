# Qin

```php
$rootDir = dirname(__DIR__);
$qin = Qin::newApp($rootDir);
$qin = Qin::newServer($rootDir);
$qin->loadConfig();

$qin->run(":8090");
```
