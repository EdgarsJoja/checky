<template>
    <v-snackbar
            v-model="snackbar"
            :bottom="y === 'bottom'"
            :left="x === 'left'"
            :multi-line="mode === 'multi-line'"
            :right="x === 'right'"
            :timeout="timeout"
            :top="y === 'top'"
            :vertical="mode === 'vertical'"
    >
        {{ text }}
        <v-btn
                color="pink"
                flat
                @click="snackbar = false"
        >
            Close
        </v-btn>
    </v-snackbar>
</template>

<script>
    import { events } from './events';

    export default {
        data () {
            return {
                snackbar: false,
                y: 'bottom',
                x: null,
                mode: '',
                timeout: 4000,
                text: 'Message'
            }
        },

        mounted() {
            events.$on('Notifications::addMessage', data => {
                this.text = data.message;
                this.snackbar = true;
            });
        }
    }
</script>
