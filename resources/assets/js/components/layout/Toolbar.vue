<template>
    <v-toolbar color="indigo" dark fixed app>
        <v-toolbar-side-icon @click.stop="toggleSidebar()"></v-toolbar-side-icon>
        <v-toolbar-title>Application</v-toolbar-title>
    </v-toolbar>
</template>

<script>
    import { events } from '../utils/events';

    export default {
        methods: {
            toggleSidebar() {
                events.$emit('Sidebar::toggle');
            }
        }
    }
</script>
