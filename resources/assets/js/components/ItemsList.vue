<template>
    <v-list
            subheader
    >
        <v-subheader>Items</v-subheader>

        <v-list-tile v-for="item in items" :key="$index" @click="">
            <v-list-tile-action>
                <v-checkbox v-model="itemsStates" :value="item.id"></v-checkbox>
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
            }
        },

        data() {
            return {
                itemsStates: []
            }
        },

        methods: {

        },

        mounted() {
            this.items = [
                {
                    id: 0,
                    title: 'Item 1'
                },
                {
                    id: 1,
                    title: 'Item 2'
                }
            ]
        }
    }
</script>
