@php
    $positions = \Modules\Appearance\Enums\MenuPositionEnum::asSelectArray();
@endphp

<style>
    .list-group-item:not(:first-child) {
        border-top: 0 !important; 
    }
</style>

<div class="row">
    <div class="col-12 col-xl-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('appearance::messages.menus') }}</h5>
                    <div>
                        <select 
                            class="form-select form-select-sm" 
                            name="position" 
                            onchange="location.href = '{{ request()->route('admin.appearance.menus.index') }}?position=' + this.value"
                            style="min-width: 160px"
                        >
                            @foreach($positions as $value => $name)
                            <option value="{{ $value }}" @if(request()->query('position', 0) == $value)selected @endif>
                                {{ __('appearance::messages.'.\Illuminate\Support\Str::slug($name)) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush p-2 menu" data-parent="root">
                @forelse ($menus as $menu)
                    @component('appearance::menu.menu-item', ['menu' => $menu])@endcomponent
                @empty
                    <li class="list-group-item hover-none border-light rounded-none">
                        <div class="alert alert-info text-center">
                            {{ __('appearance::messages.no_menu_item') }}
                        </div>
                    </li>
                @endforelse
                </ul>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-6 col-lg-6">
        <form method="POST" action="{{ route('admin.appearance.menus.store') }}">
            @csrf
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('appearance::messages.create_or_edit_menu') }}</h5>
                    </div>
                </div>
                <div class="card-body">
                    <input type="hidden" name="menu_id" id="menu_id">
                    <input type="hidden" name="position" id="position" value="{{ $position }}">
                    <x-forms::group
                        mode="input"
                        :label="__('appearance::messages.form.menu_name')"
                        :placeholder="__('appearance::messages.form.menu_name_placeholder')"
                        name="name"
                        :value="old('name', '')"
                        required
                    />
                    <x-forms::group
                        mode="input"
                        :label="__('appearance::messages.form.menu_url')"
                        :placeholder="__('appearance::messages.form.menu_url_placeholder')"
                        name="url"
                        :value="old('url', '')"
                        required
                    />
                    <x-forms::group
                        mode="select"
                        :label="__('appearance::messages.form.menu_parent')"
                        name="parent_id"
                    >
                        <option value="">Root</option>
                        @foreach($root_menus as $menu)
                            <option value="{{ $menu->id }}" @if($menu->id == old('parent_id'))selected @endif>
                                {{ $menu->name }}
                            </option>
                        @endforeach
                    </x-forms::group>
                    <x-forms::group
                        mode="input"
                        type="number"
                        :label="__('appearance::messages.form.menu_order')"
                        name="order"
                        :value="old('order', 0)"
                    />
                    <x-forms::group
                        mode="switch"
                        name="active"
                        :label="__('appearance::messages.form.menu_active')"
                        :checked="old('active', 'on') == 'on'"
                    />
                    <button type="submit" class="btn btn-success w-100 text-white">
                        <i class="fas fa-save"></i>
                        @lang('appearance::messages.save')
                    </button>                    
                </div>
            </div>
        </form>
    </div>    
</div>