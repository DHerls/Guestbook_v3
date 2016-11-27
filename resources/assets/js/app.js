
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */


require.ensure([],function(){
    require.include('./bootstrap');
    require.include('./jquery.notifyBar');
    require('./scrollToTop');
    require('./sortsearchtable');
},"index");

require.ensure([],function(){
    require.include('./bootstrap');
    require.include('./jquery.notifyBar');
    require('./newmember');
},"new");

