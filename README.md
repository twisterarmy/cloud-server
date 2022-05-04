# cloud-server
Multi-account instance allow users to interact with the twister network through web service.

### nginx

```
location @extensionless-php {
  rewrite ^/(.*)$ /index.php?_route_=$1 last;
}
```
