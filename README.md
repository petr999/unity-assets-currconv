# Reference purpose

The cbr.ru's official quotes are only for reference. You should not want to deal based on them.

As such the money format is not what the user should expect from this service. The output is float-point numeric as it is.

# Run and use

Having `docker-compose`, run it:

```
docker-compose up
```

Then try it:

```
curl -v 'http://localhost:8000/api/v0/curr/conv?baseCurr=RUB&targCurr=USD&baseSum=12'
```

The arguments are: `baseCurr` for base currency, `targCurr` for target currency, `baseSum` for sum in a base currency to be converted - all mandatory.

# Test

Functional tests are included. Easiest way to run them is:

```
docker exec -it -u 1000:1000 unity-assets-currconv_unity_assets_currconv_1 bash -lc 'cd /app/myapp && PATH="/opt/bitnami/php/bin:${PATH}"; ./bin/phpunit'
```
