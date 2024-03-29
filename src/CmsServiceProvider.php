<?php

namespace Module\Cms;

use DnSoft\Acl\Facades\Permission;
use DnSoft\Core\Events\CoreAdminMenuRegistered;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Event;
use Module\Cms\Events\CmsAdminMenuRegistered;
use Module\Cms\Models\Category;
use Module\Cms\Models\Page;
use Module\Cms\Models\Post;
use Module\Cms\Repositories\CategoryRepository;
use Module\Cms\Repositories\CategoryRepositoryInterface;
use Module\Cms\Repositories\PageRepository;
use Module\Cms\Repositories\PageRepositoryInterface;
use Module\Cms\Repositories\PostRepository;
use Module\Cms\Repositories\PostRepositoryInterface;
use DnSoft\Core\Support\BaseModuleServiceProvider;
use Module\Cms\Events\ViewPostEvent;
use Module\Cms\Listeners\ViewPostListener;
use Module\Cms\Services\PageService;

class CmsServiceProvider extends BaseModuleServiceProvider
{
  public function getModuleNamespace(): string
  {
    return 'cms';
  }

  public function register()
  {
    parent::register();

    $this->app->singleton(CategoryRepositoryInterface::class, function () {
      return new CategoryRepository(new Category());
    });

    $this->app->singleton(PostRepositoryInterface::class, function () {
      return new PostRepository(new Post());
    });

    $this->app->singleton(PageRepositoryInterface::class, function () {
      return new PageRepository(new Page());
    });

    $this->app->singleton('cms.page', PageService::class);

    $this->mergeConfigFrom(realpath(__DIR__ . '/../config/cms.php'), 'cms');
  }

  public function boot()
  {
    parent::boot(); // TODO: Change the autogenerated stub

    $this->registerPermissions();

    $this->registerAdminMenu();

    require_once __DIR__ . '/../helpers/helpers.php';

    $this->publishes([
      __DIR__ . '/../public' => public_path('vendor/cms'),
    ], 'cms-module');

    $this->publishes([
      __DIR__.'/../config/cms.php' => config_path('cms.php'),
    ], 'cms-config');

    Event::listen(ViewPostEvent::class, ViewPostListener::class);

    AliasLoader::getInstance()->alias('Page', \Module\Cms\Facades\Page::class);
  }

  public function registerPermissions()
  {
    Permission::add('cms.admin.category.index', __('cms::permission.category.index'));
    Permission::add('cms.admin.category.create', __('cms::permission.category.create'));
    Permission::add('cms.admin.category.edit', __('cms::permission.category.edit'));
    Permission::add('cms.admin.category.destroy', __('cms::permission.category.destroy'));

    Permission::add('cms.admin.post.index', __('cms::permission.post.index'));
    Permission::add('cms.admin.post.create', __('cms::permission.post.create'));
    Permission::add('cms.admin.post.edit', __('cms::permission.post.edit'));
    Permission::add('cms.admin.post.destroy', __('cms::permission.post.destroy'));

    Permission::add('cms.admin.page.index', __('cms::permission.page.index'));
    Permission::add('cms.admin.page.create', __('cms::permission.page.create'));
    Permission::add('cms.admin.page.edit', __('cms::permission.page.edit'));
    Permission::add('cms.admin.page.destroy', __('cms::permission.page.destroy'));
  }

  public function registerAdminMenu()
  {
    Event::listen(CoreAdminMenuRegistered::class, function ($menu) {
      $menuContent = $menu->add('Content', [])->data('order', 2000)->data('icon', 'fa fa-bookmark');
      if (!get_setting_value_by_name('disable_mega_menu')) {
        $menuContent->prepend('<i class="far fa-file-alt"></i>');
      }

      $menu->add('Category', [
        'route' => 'cms.admin.category.index',
        'parent' => $menu->content->id,
      ])->nickname('cms_category')->data('order', 2)->prepend('<i class="fas fa-check-double"></i>');

      $menu->add('Post', [
        'route' => 'cms.admin.post.index',
        'parent' => $menu->content->id
      ])->nickname('cms_post')->data('order', 3)->prepend('<i class="far fa-file-word"></i>');

      $menu->add('Page', [
        'route' => 'cms.admin.page.index',
        'parent' => $menu->content->id
      ])->nickname('cms_page')->data('order', 4)->prepend('<i class="fab fa-playstation"></i>');

      $menu->add('Post Attribute', [
        'route' => 'cms.admin.post-attribute.index',
        'parent' => $menu->content->id
      ])->nickname('cms_post_attribute')->data('order', 3)->prepend('<i class="fab fa-500px"></i>');

      event(CmsAdminMenuRegistered::class, $menu);
    });
  }
}
