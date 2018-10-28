<template>
    <v-list
            subheader
    >
        <v-subheader>{{ items.length > 0 ? `${items.length} Items` : 'No items added' }}</v-subheader>

        <v-list-tile v-for="item in itemsArray" :key="item.id" @click="">
            <v-list-tile-action>
                <v-checkbox v-model="itemsStates" :value="item.id" @change="handleUpdate(item.id)"></v-checkbox>
            </v-list-tile-action>

            <v-list-tile-content @click="">
                <v-list-tile-title>{{ item.title }}</v-list-tile-title>
            </v-list-tile-content>
        </v-list-tile>
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
                itemsStates: []
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
                        console.log(response);
                    }
                }, error => {
                    console.log(error);
                });
            },

            getCheckboxState(id) {
                return this.itemsStates.indexOf(id) !== -1;
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
                this.items.push(item);
            });
        }
    }
</script>
