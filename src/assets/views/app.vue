<template>
    <div class="content-body">
        <form class="form" @submit.prevent="submit">
            <div class="form__item">
                <label for="url" class="form__item-label">Your URL</label>
                <input id="url" name="url" type="text" class="form__item-input"
                       v-model="fields.url"
                       placeholder="Enter site's url">
            </div>

            <div class="form__item">
                <button v-show="success" @click="copyContent" type="button" class="form__item-btn cut">Copy</button>
                <button type="submit" class="form__item-btn copy">Cut URL</button>
            </div>
            <div v-show="success" class="form__item">
                <label for="shortURl" class="form__item-label">Your shortcut url</label>
                <input id="shortURl" name="shortUrl" type="text" class="form__item-input"
                       v-if="shortcutUrl"
                       :value="shortcutUrl" readonly>
            </div>

            <div v-show="error" class="error" v-if="error">
                {{ error }}
            </div>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            clipboard: null,
            shortcutUrl: null,
            fields: {},
            success: false,
            error: null,
        }
    },
    methods: {
        copyContent() {
            this.clipboard = new ClipboardJS(".form__item-btn", {
                target: function () {
                    return document.getElementById("shortURl");
                }
            });
            this.clipboard.on("success", function (e) {
            });
            const clipboard = require("clipboard");
            this.clipboard = new clipboard(".form__item-btn", {
                target: function () {
                    return document.getElementById("shortURl");
                }
            });
        },
        submit() {
            const regex = /^(https?:\/\/)?([a-zA-Z0-9.-]+)\.([a-zA-Z]{2,6})(\/.*)?$/;
            const url = this.fields.url;
            if (regex.test(url)) {
                axios.post('/api/encode', this.fields).then(response => {
                    this.fields = {url: response.data.url};
                    this.success = true;
                    this.error = null;
                    this.shortcutUrl = response.data.url;
                }).catch(() => {
                    this.success = false;
                    this.error = 'Error';
                    console.log('Error');
                });
            } else {
                this.success = null;
                this.error = 'Wrong url';
                console.log('Wrong url');
            }
        }
    },
}
</script>
