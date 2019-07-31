# Laravel Dusk troubleshooting

These are common problems I've faced when running Laravel Dusk from within Laravel Homestead.

## Update Homestead
At first, consider following these steps
- Update Vagrant and Virtualbox
- Update vagrant box with `vagrant box update`
- Recreate box with `vagrant destroy -f && vagrant up`
- Try to run all tests with `composer test`

## Failed to connect
If you run Dusk tests: 
```
Failed to connect to localhost port 9515: Connection refused
```

If you call the chrome driver directly using `vendor/laravel/dusk/bin/chromedriver-linux`:
```
error while loading shared libraries: libnss3.so: cannot open shared object file: No such file or directory
```

Solution
- run `sudo apt-get update`
- run `sudo apt-get install -y libnss3 chromium-browser`


## Chrome version mismatch
Problem looks like this:
```
Facebook\WebDriver\Exception\SessionNotCreatedException: session not created: This version of ChromeDriver only supports Chrome version 76
  (Driver info: chromedriver=76.0.3809.68 (420c9498db8ce8fcd190a954d51297672c1515d5-refs/branch-heads/3809@{#864}),platform=Linux 4.15.0-55-generic x86_64)
```

Solution
- run `chromium-browser --version` and check the major version, e.g. `75`
- then install the correct driver with `php artisan dusk:chrome-driver 75`
