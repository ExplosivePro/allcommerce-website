<template>
    <div class="row" >
        <div class="col-md-12 form-group">
            <div class="preview-pane" v-show="status === 'success'">
                <label>Preview</label>
                <div class="col-md-12">
                    <img class="img-avatar sample-img" :src="getImage" style="width: 40%;">
                </div>
            </div>

            <label class="required">{{ label }}</label>
            <div class="input-group column">
                <div class="input-group-prepend" style="cursor:pointer;" @click="toggleFieldMode()"><span class="input-group-text" :class="iconBg"><i :class="iconSelect"></i></span></div>
                <input class="form-control" type="text" v-model="inputField" name="profile_image" required :placeholder="placeholder" @click="pickFile" :disabled="loading" @change="loadUrl()">
            </div>
            <small class="help-block">{{ getIconHint }}</small>

            <div class="hidden">
                <input type="file" ref="file" accept="image/*" v-on:change="postUpload()" id="filer" />
                <input type="hidden" v-model="imgPath" />
            </div>
            <sweet-modal icon="warning" ref="warning" modal-theme="dark" overlay-theme="dark" >
                Only Image File Types Are Permitted. Please Try Again.
            </sweet-modal>
            <sweet-modal icon="warning" ref="size" modal-theme="dark" overlay-theme="dark" >
                Only Files under {{ limit }}MB Are Allowed. Please Try Again.
            </sweet-modal>
        </div>
    </div>
</template>

<script>
    export default {
        name: "imageUploadField",
        props: ['loading', 'status', 'url', 'newhint', 'limit'],
        watch: {
            url(uri) {
                this.inputField = uri;
            },
            mode(mode) {
                if(mode === 'url') {
                    this.iconSelect = 'fad fa-link'
                    this.iconBg = 'bg-link'
                    this.placeholder = 'Paste an Image URL Here!'
                    this.label = 'Image (via URL)'
                    this.iconHint = 'Paste a URL into the text field or Tap the Link Icon to Switch to Upload Mode.'
                }
                else {
                    this.iconSelect = 'fad fa-upload';
                    this.iconBg = 'bg-warning'
                    this.placeholder = 'Click Me to Open the File Picker!'
                    this.label = 'Image (via File Upload)'
                    this.iconHint = 'Tap the text field to upload an image or Tap the Upload Icon to Switch to URL Mode.'
                }
            },
            file($localPath) {
                let fileType = this.$refs.file.files[0].type;
                if(!fileType.includes('image/')) {
                    this.$refs.warning.open();
                    this.iconHint = 'Please upload image files only!'
                    this.iconBg = 'bg-dark'
                    this.iconSelect = 'las la-spider faa-horizontal animated'
                }
                else {
                    let fileSize = this.$refs.file.files[0].size;
                    let mb = Math.round((fileSize/1048576))

                    if(mb < this.limit) {
                        let payload = {
                            localPath: $localPath,
                            file: this.$refs.file.files[0]
                        };
                        this.$emit('upload', payload);
                        this.iconSelect = 'las la-spinner faa-spin animated'
                        this.iconBg = 'bg-info'
                        this.label = 'Processing Image! Hang-on a Sec!'
                        this.iconHint = 'Free Tip - Once Uploaded, check out the preview at the top!'
                        this.placeholder = 'Loading! Please wait...'
                    }
                    else {
                        this.$refs.size.open();
                        this.iconHint = 'File Limit is '+this.limit+' MB.'
                        this.iconBg = 'bg-orange'
                        this.iconSelect = 'fad fa-weight-hanging faa-vertical faa-slow animated'
                    }
                }
            },
            status(status) {
                switch(status) {
                    case 'processing':
                        this.iconSelect = 'fad fa-tire-rugged faa-spin animated'
                        this.iconBg = 'bg-primary'
                        this.label = 'Validating Image! Just a Little Longer!'
                        this.placeholder = 'Still Loading! Please wait...'
                        break;

                    case 'success':
                        this.iconBg = 'bg-success'
                        this.iconSelect = 'fad fa-thumbs-up'

                        if(this.mode === 'url') {
                            this.placeholder = 'Paste an Image URL Here!'
                            this.label = 'Image (via URL)'
                            this.iconHint = 'Paste a URL into the text field or Tap the Link Icon to Switch to Upload Mode.'
                        }
                        else {
                            this.placeholder = 'Click Me to Open the File Picker!'
                            this.label = 'Image (via File Upload)'
                            this.iconHint = 'Tap the text field to upload an image or Tap the Upload Icon to Switch to URL Mode.'
                        }
                        break;

                    case 'failed':
                        this.iconBg = 'bg-danger'
                        this.iconSelect = 'fad fa-poo faa-tada animated'
                        this.label = 'Upload Failed. Please Try Again.'

                        if(this.mode === 'url') {
                            this.placeholder = 'Paste an Image URL Here!'
                            this.label = 'Image (via URL)'
                            this.iconHint = 'Paste a URL into the text field or Tap the Link Icon to Switch to Upload Mode.'
                        }
                        else {
                            this.placeholder = 'Click Me to Open the File Picker!'
                            this.iconHint = 'Tap the text field to upload an image or Tap the Upload Icon to Switch to URL Mode.'
                        }
                        break;
                }
            },
        },
        data() {
            return {
                mode: 'url',
                label: 'Image (via URL)',
                iconSelect: 'fad fa-link',
                iconBg: 'bg-link',
                placeholder: 'Paste an Image URL Here!',
                iconHint: 'Paste a URL into the text field or Tap the Link Icon to Switch to Upload Mode.',
                imgPath: '',
                file: '',
                inputField: ''
            };
        },
        computed: {
            getImage() {
                let rs = this.inputField

                if(this.url !== '') {
                    rs = this.inputField;
                }

                return rs;
            },
            getIconHint() {
                let r = this.iconHint;

                if(this.newhint !== '') {
                    r = this.newhint + ' '+ this.iconHint;
                }

                return r;
            }
        },
        methods: {
            toggleFieldMode() {
                if(!this.loading) {
                    if(this.mode === 'url') {
                        this.mode = 'upload'
                    }
                    else {
                        this.mode = 'url';
                    }
                }
            },
            postUpload() {
                this.file = this.$refs.file.files[0];
            },
            pickFile() {
                if(this.mode === 'upload') {
                    $('#filer').click();
                }
            },
            loadUrl() {
                if(this.mode === 'url')
                {
                    if(this.inputField === '') {
                        this.iconSelect = 'fad fa-link'
                        this.iconBg = 'bg-link'
                        this.placeholder = 'Paste an Image URL Here!'
                        this.label = 'Image (via URL)'
                        this.iconHint = 'Paste a URL into the text field or Tap the Link Icon to Switch to Upload Mode.'
                    }
                    else if(this.inputField.match(/\.(jpeg|jpg|gif|png)$/) != null) {
                        let pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
                            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
                            '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
                            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
                            '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
                            '(\\#[-a-z\\d_]*)?$','i');

                        if(!!pattern.test(this.inputField)) {
                            this.iconBg = 'bg-success'
                            this.iconSelect = 'fad fa-thumbs-up'
                            this.iconHint = 'If the pic shows up, you are good to go!';
                            $('.img-avatar').attr('src', this.inputField);
                            $('.sample-img').attr('src', this.inputField);
                            this.$emit('url');
                        }
                        else {
                            this.iconHint = 'Invalid URL. Key typing or try again'
                            this.iconBg = 'bg-danger'
                            this.iconSelect = 'las la-thumbs-down'
                        }
                    }
                    else {
                        this.iconHint = 'Invalid URL. Key typing or try again'
                        this.iconBg = 'bg-danger'
                        this.iconSelect = 'las la-thumbs-down'
                    }
                }
                else {
                    this.iconBg = 'bg-yellow'
                    this.iconSelect = 'fad fa-bomb faa-burst animated';
                    this.placeholder = 'Nice Try.'
                    this.iconHint = 'That action is not allowed.'

                    let _this = this;
                    setTimeout(() => {
                        _this.iconSelect = 'fad fa-upload';
                        _this.iconBg = 'bg-warning'
                        _this.placeholder = 'Click Me to Open the File Picker!'
                        _this.iconHint = 'Tap the text field to upload an image or Tap the Upload Icon to Switch to URL Mode.'
                    }, 1500)

                    this.inputField = '';
                }
            },
        }
    }
</script>

<style scoped>
    @media screen {
        .bg-link {
            background: #384c74;
            color: #fff
        }

        .bg-warning {
            color: #000;
        }

        .hidden {
            display: none;
        }

        .preview-pane {
            width: 100%;
            text-align: center;
            padding-bottom: 2em;
        }

        .input-group-text.bg-dark {
            color: red;
        }

        .input-group-text.bg-orange {
            color: darkslategray;
        }
    }
</style>
