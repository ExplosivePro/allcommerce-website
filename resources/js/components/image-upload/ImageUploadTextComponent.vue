<template>
    <image-field
        :loading="loading"
        :status="status"
        :url="url"
        :newhint="newhint"
        :limit="limit"
        @url="setSuccess"
        @upload="postFileToVapor"
    ></image-field>
</template>

<script>
    import ImageField from "../../screens/image-upload/imageUploadField";
    export default {
        name: "ImageUploadTextComponent",
        props: ['limit', 'image'],
        components: {
            ImageField
        },
        data() {
            return {
                loading: false,
                uploadProgress: 0,
                status: 'ready',
                url: '',
                newhint: ''
            };
        },
        methods: {
            setSuccess() {
                this.status = 'success';
            },
            postFileToVapor(payload) {
                let _this = this;
                this.loading = true;
                this.status = 'ready';

                Vapor.store(payload.file, {
                    progress: progress => {
                        _this.uploadProgress = Math.round(progress * 100);
                        _this.newhint = 'Uploading... ('+_this.uploadProgress+'%)'
                        console.log(_this.uploadProgress+'%');
                    }
                }).then(response => {
                    console.log('S3 response - ', response);

                    _this.postFileToServer(response, payload.file);
                })
                .catch(function(e){
                    _this.status = 'failed'
                    _this.loading = false;
                    console.log('FAILURE!!', e);

                    let msg = e.response.data.message;
                    let code = e.response.status
                    let text = e.response.statusText

                    console.log(`Received a ${code} status - ${text} with the following message: '${msg}'`);
                });
            },
            postFileToServer(response, file) {
                let _this = this;
                _this.status = 'processing';
                axios.post('/access/user/image/upload', {
                    uuid: response.uuid,
                    key: response.key,
                    bucket: response.bucket,
                    name: file.name,
                    content_type: file.type,
                })
                .then(res => {
                    console.log('server call response - ', res);

                    if('data' in res) {
                        let data = res['data'];

                        if('success' in data) {
                            if(data['success']) {
                                _this.url = data['url'];
                                $('.img-avatar').attr('src', data['url']);
                                $('.sample-img').attr('src', data['url']);
                                _this.status = 'success';
                                $("html, body").animate({ scrollTop: 0 }, "slow");
                            }
                            else {
                                _this.status = 'failed';
                            }
                        }
                        else {
                            _this.status = 'failed';
                        }
                    }
                    else {
                        _this.status = 'failed';
                    }

                    _this.newhint = '';
                    _this.loading = false;
                })
                .catch(function(e){
                    _this.status = 'failed'
                    _this.loading = false;
                    _this.newhint = '';
                     console.log('FAILURE!!', e);
                    // let msg = e.response.data.message;
                    // let code = e.response.status
                    // let text = e.response.statusText

                    //console.log(`Received a ${code} status - ${text} with the following message: '${msg}'`);
                });
            }
        },
        mounted() {
            console.log('Passed in image - ' + this.image);
            if(this.image !== '') {

                this.$nextTick(() => {
                    // this.init();
                    this.url = this.image;
                    this.status = 'success';
                });

            }
        }
    }
</script>

<style scoped>

</style>
