
<ul class="nav nav-tabs scrollable">
    <li class="nav-item">
        <a class="nav-link active save-tab" data-toggle="pill" href="#cmsCategoryInfo">
            {{ __('cms::category.tabs.info') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link save-tab" data-toggle="pill" href="#cmsCategorySeo">
            {{ __('cms::category.tabs.seo') }}
        </a>
    </li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="cmsCategoryInfo">
        <div class="row">
            <div class="col-12">
                @select(['name' => 'parent_id', 'label' => __('cms::category.parent'), 'options' => get_cms_category_parent_options()])
                @input(['name' => 'name', 'label' => __('cms::category.name'), 'require' => true])
                {{--                @tags--}}
                @textarea(['name' => 'description', 'label' => __('cms::category.description')])
                @input(['name' => 'icon', 'label' => __('Icon'), ])
                @input(['name' => 'sort', 'label' => __('Sort'), ])
                @checkbox(['name' => 'is_featured', 'label' => __('cms::category.is_featured') ])
                @checkbox(['name' => 'is_active', 'label' => __('cms::category.is_active'), 'default' => true])
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="cmsCategorySeo">
        @seo
    </div>
</div>
