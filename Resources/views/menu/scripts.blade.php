<script src="{{ asset('vendor/sortable/sortable.min.js') }}"></script>
<script>
    function updateMenu(ordered_list, parent_id = null) {
        axios
        .post('{{ route('admin.appearance.menus.update-order') }}', {ordered_list, parent_id: parent_id == 'root' ? null : parent_id})
        .then(function(resp) {
            if(resp.data && resp.data.success) {
                console.log(`Saved menu order: ${id} - ${order} - ${parent_id}`);
            }
        })
    }

    (function() {
        "use strict"
        const language  = '{{ request()->getLocale() }}';
        const editItems = document.querySelectorAll(".btn-menu-edit");
        const deleteItems = document.querySelectorAll(".btn-menu-delete");
        const deleteUrl = '{{ route('admin.appearance.menus.destroy') }}';
        const menuElements = {};

        document.querySelectorAll('.menu').forEach(function(menu) {
            menuElements['menu_' + menu.getAttribute('data-parent')] = new Sortable(menu, {
                handle: '.menu-arrow',
                group: 'nested',
                animation: 150,
                fallbackOnBody: true,
                swapThreshold: 0.65,
                onSort: function (event) {
                    const source_parent = event.from;
                    const target_parent = event.to;
                    let parent_id = null;
                    let items = target_parent.querySelectorAll('.list-group-item');

                    if(source_parent && source_parent.querySelectorAll('.list-group-item').length <= 0) {
                        const sortable = menuElements['menu_' + source_parent.getAttribute('data-parent')];
                        if(sortable) {
                            sortable.destroy();
                            source_parent.remove();
                        }
                    }

                    if(source_parent.getAttribute('data-parent') != target_parent.getAttribute('data-parent')) {
                        parent_id = target_parent.getAttribute('data-parent');
                    }

                    const ordered_list = menuElements['menu_' + target_parent.getAttribute('data-parent')].toArray();
                    updateMenu(ordered_list, target_parent.getAttribute('data-parent'));
                },                
            });
        });

        editItems.forEach(function(item) {
            item.addEventListener("click", function(el) {
                const data = JSON.parse(el.currentTarget.getAttribute('data-json'));

                const nameEl = document.querySelector('.form-control[name="name"]');
                const urlEl = document.querySelector('.form-control[name="url"]');
                const parentEl = document.querySelector('.form-select[name="parent_id"]');
                const orderEl = document.querySelector('.form-control[name="order"]');
                const activeEl = document.querySelector('.form-check-input[name="active"]');
                const menuIdEl = document.getElementById('menu_id');

                nameEl.value = data.name[language];
                urlEl.value = data.url;
                parentEl.value = data.parent_id;
                orderEl.value = data.order;
                menuIdEl.value = data.id;
                activeEl.checked = data.active;
            })
        });

        deleteItems.forEach(function(item) {
            item.addEventListener("click", function(el) {
                const id = el.currentTarget.getAttribute('data-id');

                Swal.fire({
                    title: "{{ __('appearance::messages.are_you_sure') }}",
                    text: "{{ __('appearance::messages.delete_confirm') }}",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: "{{ __('appearance::messages.confirm') }}",
                    cancelButtonText: "{{ __('appearance::messages.cancel') }}",
                }).then(function(result) {
                    if(result.isConfirmed) {
                        axios.delete(deleteUrl + `/${id}`)
                        .then(resp => {
                            if(resp.data && resp.data.success) {
                                Notyf.success(`{{ __('appearance::messages.deleted') }}`);
                                document.getElementById('menu_item_' + id).remove();
                            } else {
                                Notyf.error(`{{ __('appearance::messages.something_went_wrong') }}`);
                            }
                        })
                    }
                })
            });
        });
    })()
</script>