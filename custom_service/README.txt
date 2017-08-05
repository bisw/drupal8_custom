#custom_service

1. Get the module and put it within modules/custom or sites/all/module/custom folder.
2. Enable the module and it's dependency modules also.
3. Go to the service api config page where all the api are listed. http://example.com/admin/config/services/rest.
4. Enable 'Api Key Resource' api and configure it. Select method as 'GET', format as 'json' and choose any authentication method. here if we use the basic_auth authentication method, set drupal username and password in the header.
5. Set the permission for required user to access the 'Api key Resourse'.
6. Do not forget to clear cache.
