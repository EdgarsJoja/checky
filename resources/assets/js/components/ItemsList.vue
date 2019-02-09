<template>
    <v-list
            subheader
    >
        <v-subheader>{{ itemsArray.length > 0 ? `${itemsArray.length} Items` : 'No items added' }}</v-subheader>

        <v-list-tile v-for="(item, index) in itemsArray" :key="item.uuid" @click="">
            <v-list-tile-action>
                <v-checkbox v-model="itemsStates" :value="item.uuid" @change="handleUpdate(item)"></v-checkbox>
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
    import { Message } from './utils/message';
    import { Sync } from './utils/sync';

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
            handleUpdate(item) {
                item.state = this.getCheckboxState(item.uuid);

                Message.openChannel({
                    type: 'item-update',
                    item: item
                }, (e) => {
                    events.$emit('ItemsList::refresh');
                    Sync.sync('sync-pending-items');
                });

                // this.$http.post(
                //     this.urls.itemUpdate,
                //     {
                //         id: id,
                //         state: state
                //     },
                //     { headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }}
                // ).then(response => {
                //     if (response.body.success) {
                //         // Do success
                //     }
                // }, error => {
                //     console.log(error);
                // });
            },

            getCheckboxState(uuid) {
                return this.itemsStates.indexOf(uuid) !== -1;
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
                Message.openChannel({
                    type: 'item-delete',
                    item: item
                }, (e) => {
                    this.refreshItems();
                    this.toggleItemActions();
                });

                // this.$http.post(
                //     this.urls.itemDelete,
                //     {id: item.id},
                //     { headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }}
                // ).then(response => {
                //     if (response.body.success) {
                //         // Do success
                //         const deletedItemIndex = this.items.indexOf(item);
                //
                //         if (deletedItemIndex > -1) {
                //             this.items.splice(deletedItemIndex, 1);
                //         }
                //
                //         this.toggleItemActions();
                //     }
                // }, error => {
                //     console.log(error);
                // });
            },

            refreshItems() {
                Message.openChannel({
                    type: 'get-items'
                }, (e) => {
                    this.itemsArray = e.data.items;
                });
            }
        },

        created() {
            this.items.map(item => {
                if (item.state) {
                    this.itemsStates.push(item.uuid);
                }
            });

            Sync.sync('sync-db-items');
            Sync.sync('sync-pending-items');
        },

        mounted() {
            events.$on('ItemsList::refresh', () => {
                this.refreshItems();
            });

            events.$emit('ItemsList::refresh');
        }
    }
</script>
