<template>
    <v-navigation-drawer
            v-model="drawer"
            fixed
            app
    >
        <v-list dense>
            <v-list-tile @click="handleAccountClick()">
                <v-list-tile-action>
                    <v-icon>account_circle</v-icon>
                </v-list-tile-action>

                <v-list-tile-content>
                    <v-list-tile-title v-if="options.authorized">{{ user.name }}</v-list-tile-title>
                    <v-list-tile-sub-title v-if="options.authorized">{{ user.email }}</v-list-tile-sub-title>
                    <v-list-tile-title v-else>Login</v-list-tile-title>
                </v-list-tile-content>

                <v-list-tile-action @click="logout()" v-if="options.authorized">
                    <v-icon>exit_to_app</v-icon>
                </v-list-tile-action>
            </v-list-tile>
        </v-list>
    </v-navigation-drawer>
</template>

<script>
    import { events } from '../utils/events';

    export default {
        props: {
            user: {
                required: false,
                type: [Object, Array]
            },

            urls: {
                required: true,
                type: Object
            },

            options: {
                required: true,
                type: Object
            }
        },

        data:() => ({
            drawer: false
        }),

        methods: {
            handleAccountClick() {
                if (this.options.authorized) {
                    // Add logic
                } else {
                    window.location.replace(this.urls.login);
                }
            },

            logout() {
                window.location.replace(this.urls.logout);
            }
        },

        mounted() {
            events.$on('Sidebar::toggle', () => {
                this.drawer = !this.drawer;
            });
        }
    }
</script>
