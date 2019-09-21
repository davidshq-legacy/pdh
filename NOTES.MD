# NOTES
PDH attempts to provide a concise, focused, best practices, opinioniated guide to building a WordPress plugin. In order to accomplish this numerous alternative ways of accomplishing specific tasks are left aside. In the notes section we attempt to address some of these additional topics. You'll find references to notes throughout the code referenced in this fashion (#nnn) - e.g., (#100).

- (#001) - Relevant PHP built-in functions include: isset(), function_exists(), class_exists(), and defined().
- (#002) - The handbook includes two more examples:
    - https://jjj.blog/2012/12/slash-architecture-my-approach-to-building-wordpress-plugins/
    - https://iandunn.name/content/presentations/wp-oop-mvc/mvc.php#/
- (#003) The handbook includes additional references but they do not appear to be actively maintained and I wouldn't recommend:
    - WordPress Plugin Boilerplate (2014): https://github.com/claudiosanches/wordpress-plugin-boilerplate
    - WP Skeleton Plugin (2014): https://github.com/ptahdunbar/wp-skeleton-plugin
- (#004) Why?
- (#005) Built-in PHP functions include: isset(), empty(), mb_strlen(), strlen(), preg_match(), strpos(), count(), in_array()
- (#006) An old, outdated practice is to pass a PHP file path as the $menu_slug with a null $function, don't do this. See the top-level-menus page for an example.
- (#007) One could handle the submission of forms on options pages manually but why would one when one can use the Settings API?