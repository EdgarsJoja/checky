<template>
    <v-list
            subheader
    >
        <v-subheader>{{ items.length > 0 ? `${items.length} Items` : 'No items added' }}</v-subheader>

        <v-list-tile v-for="item in itemsArray" :key="item.id" @click="">
            <v-list-tile-action>
                <v-checkbox v-model="itemsStates" :value="item.id" @change="handleUpdate(item.id)"></v-checkbox>
            </v-list-tile-action>

            <v-list-tile-content @click="toggleItemActions(item)">
                <v-list-tile-title>{{ item.title }}</v-list-tile-title>
            </v-list-tile-content>
        </v-list-tile>

        <v-bottom-sheet v-model="actionsSheet">
            <v-list>
                <v-list-tile>
                    <v-list-tile-action>
                        <v-icon color="indigo">delete</v-icon>
                    </v-list-tile-action>

                    <v-list-tile-content @click="deleteItem(activeItem)">
                        <v-list-tile-title>Delete</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
            </v-list>
        </v-bottom-sheet>
    </v-list>
</template>

<script>
    import { events } from './utils/events';

    export default {
        props: {
            items: {
                required: true,
                type: Array,
                default() {
                    return []
                }
            },

            urls: {
                required: true,
                type: Object
            }
        },

        data() {
            return {
                itemsArray: this.items,
                itemsStates: [],
                actionsSheet: false,
                activeItem: false
            }
        },

        methods: {
            handleUpdate(id) {
                const state = this.getCheckboxState(id);

                this.$http.post(
                    this.urls.itemUpdate,
                    {
                        id: id,
                        state: state
                    },
                    { headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }}
                ).then(response => {
                    if (response.body.success) {
                        // Do success
                    }
                }, error => {
                    console.log(error);
                });
            },

            getCheckboxState(id) {
                return this.itemsStates.indexOf(id) !== -1;
            },

            toggleItemActions(item) {
                if (!this.actionsSheet) {
                    this.activeItem = item;
                } else {
                    this.activeItem = false;
                }

                this.actionsSheet = !this.actionsSheet;
            },

            deleteItem(item) {
                this.$http.post(
                    this.urls.itemDelete,
                    {id: item.id},
                    { headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }}
                ).then(response => {
                    if (response.body.success) {
                        // Do success
                        const deletedItemIndex = this.items.indexOf(item);

                        if (deletedItemIndex > -1) {
                            this.items.splice(deletedItemIndex, 1);
                        }

                        this.toggleItemActions();
                    }
                }, error => {
                    console.log(error);
                });
            }
        },

        created() {
            this.itemsStates = this.items.map(item => {
                if (item.state) {
                    return item.id
                }
            });
        },

        mounted() {
            events.$on('AddItem::itemAdded', item => {
                // Add copy if item, not item itself
                this.items.push(Object.assign({}, item));

                events.$emit('Notifications::addMessage', {
                    message: 'Item added'
                });
            });
        }
    }
</script>
