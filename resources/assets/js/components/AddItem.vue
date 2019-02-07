<template>
    <v-layout row justify-center>
        <v-dialog v-model="dialog" persistent max-width="500px">
            <v-btn
                    fixed
                    dark
                    fab
                    bottom
                    right
                    color="pink"
                    slot="activator"
            >
                <v-icon>add</v-icon>
            </v-btn>
            <v-card>
                <v-card-title>
                    <span class="headline">Add item</span>
                </v-card-title>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex>
                                <v-text-field label="Title" required v-model="item.title" autofocus></v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" flat @click.native="dialog = false">Close</v-btn>
                    <v-btn color="blue darken-1" flat @click.native="save()">Save</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-layout>
</template>

<script>
    import { events } from './utils/events';
    import { Message } from './utils/message';
    import { Sync } from './utils/sync';

    export default {
        props:{
            user: {
                required: true,
                type: Object
            },

            urls: {
                required: true,
                type: Object
            }
        },

        data() {
            return {
                dialog: false,
                item: {
                    id: 0,
                    title: '',
                    state: 0,
                    user_provider_id: this.user.provider_id,
                    status: 'pending'
                }
            }
        },

        methods: {
            save() {
                Message.postMessage({
                    type: 'item-save',
                    item: this.item
                }, (e) => {
                    events.$emit('ItemsList::refresh');
                });

                Sync.sync('sync-pending-items');

                // this.$http.post(
                //     this.urls.itemSave,
                //     { data: this.item },
                //     { headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }}
                // ).then(response => {
                //     if (response.body.success) {
                //         this.item.id = response.body.data.id;
                //
                //         events.$emit('AddItem::itemAdded', this.item);
                //         this.dialog = false;
                //     }
                // }, error => {
                //     console.log(error);
                // });
            }
        },

        watch: {
            dialog(newVal) {
                if (newVal) {
                    this.item.title = '';
                }
            }
        }
    }
</script>
