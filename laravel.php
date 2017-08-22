#6 setting up queue
===================================
php artisan queue:table
php artisan queue:failed-table
=> in .env file   
  QUEUE_DRIVER=database

#7 break up navigation | view composer
==========================================
=> in route/web.php
  Route::get('/channel/{channel}/edit', 'someFunction');
Create a View composer in App\Http\ViewComposer
<?php
namespace App\Http\ViewComposer;
use Illuminate\View\View;
use Auth;
class NavigationComposer {
  public function compose(View $view) {
    $view->with('channel', Auth::user()->auth);
  }
}

?>

php artisan make:serviceProvider NavigationServiceProvider

//register NavigationComposer in NavigationServiceProvider
<?php
class NavigationServiceProvider extends serviceProvider {
  public function boot () {
    view()->composer(
      'layouts.dashboard._somepartials',
      \App\Http\ViewComposer\NavigationComposer::class
      );
  }
}
 ?>
 add Navigation service provider in config/app.php

 #08-channel-setting || Route model binding
 =============================================
 Route::get('/channel/{channel}/edit', 'someFunction');
 Route::put('/channel/{channel}/edit', 'someFunction');
 {{method_field('PUT')}}
in Model to change the getRouteKeyName
<?php 
  class User extends Model {
    public function getRouteKeyName() {
      return 'slug';
    }
  }
 ?>

input-group>input-group-addon
{{config(app.url)}} //.env APP_URL

php artisan make:request ChannelUpdateRequest
<?php 
class ChannelUpdateRequest extends Request {
  public function rules(){
    $channelId = '';
    return [
      'slug' => 'required|alpha_num|unique:slug,users,' . $channelId,
      'description' => 'required'
    ];
  }
}

 ?>

//AuthServiceProvider has policies
only authorize user can update a post to do so we need to make policy
php artisan make:policy ChannelPolicy
<?php 
class ChannelPolicy {
  public function update(User $user, Channel $channel) 
  {
    return $user->id == $channel->user_id;
  }
}
 ?>
//In AuthServiceProvider
protected $policies = [
  'App\User\' => 'App\Policies\ChannelPolicy'
];
or
protected $policies = [
        Post::class => PostPolicy::class,
    ];
















