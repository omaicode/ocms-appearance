export default {
    emits: ['click'],
    props: ['items', 'level'],
    setup (props, ctx) {
        const onClick = (e, partial, item_idx, level) => {
            ctx.emit("click", {event: e, partial, item_idx, level});
        };

        const onChildClick = (data) => {
            ctx.emit("click", data);
        }

        return {
            props,
            onClick,
            onChildClick
        }
    },
    template: `
        <ul class="partial-list">
            <li class="partial-list--item" v-for="(partial, idx) in props.items" :key="idx">
                <div 
                    class="partial-item-content"
                    :class="{'is-directory': partial.is_dir, open: partial.is_open}" 
                    @click="onClick($event, partial, idx, props.level || 0)"
                >
                    {{partial.name}}
                </div>
                <template v-if="partial.is_dir && partial.childrens && partial.childrens.length > 0">
                    <Tree :items="partial.childrens" :level="(props.level || 0) + 1" @click="onChildClick" v-if="partial.is_open"/>
                </template>
            </li>
        </ul>
    `
}