<?php
/*
Router::add(
    [
        /*
         * Namespace = Posts
         * Controller = Posts
         * Method = defaultMethodName
         * HTTP = defaults
        new Route('posts'),

         /*
         * Namespace = Content
         * Controller = Directory
         * Method = defaultMethodName
         * HTTP = GET
        (new Route('directory'))
            ->httpMethods('GET')
            ->namespace('Content')
            ->controller('Directory'),

        /*
         * Namespace = Content
         * Controller = Directory
         * Method = test
         * HTTP = GET
        (new Route('directory/test'))
            ->httpMethods('GET')
            ->namespace('Content')
            ->controller('directory')
            ->method('test'),

        /*
         * Namespace = Content
         * Controller = Directory
         * Method = text
         * HTTP = GET
         * Parametters = an int after direcotry/ in match
        new Route('directory/(:num)', 'GET', 'Content\\Directory#text', function() { return Auth::check(); }),
      
        /*
         * Namespace = Toasts
         * Controller = Toasts
         * Method = the string after toasts/ in url
         * HTTP = defaults
        new Route('toasts/(:method)'), 

        /*
         * HTTP = GET
         * Declared handler function
        new Route('admin/my-content(:num)', 'GET', function($id) {
            echo 'My-content : '.$id;
        }),
                
        /*
         * Generate Routes :
         * new Route('articles/', 'GET', 'Articles\\Articles#getAll'
         * new Route('articles/(:num)', 'GET', 'Articles\\Articles#get'
         * new Route('articles/(:num)', 'POST', 'Articles\\Articles#update'
         * new Route('articles/(:num)', 'DELETE', 'Articles\\Articles#delete'
         * new Route('articles/', 'PUT', 'Articles\\Articles#add'
        new Ressource('articles'),
                
        /*
         * Namespace = the first string in url
         * Controller = the first string in url
         * Method = the second string in url
         * Parametters = everything after the second string / in url
         * HTTP = defaults
        new Route('(:controller)/(:method)/(:all?)')
    ]
);
        
/*
 * Define the defaut route to use 
Router::defaultRoute('directory/(:num)');

/*
 * Get the a relative or full url generated with parametters 
Router::url('directory/(:num)', array(2), true);

/*
 * Apply a filter to all routes
Router::filter(function() {
    Found the url lang and set I18n
});

/*
 * Apply a filter to routes that match /^admin/
Router::filter('/^admin/', function() {
    return Auth::check();
});

/*
 * Make the route to match the valid route and execute it
 * A specific route can be passed and the http method too
Router::match();
Router::match('my-content');
Router::match('directory/2', 'DELETE');
 * 
 */