<script>
    import Selectize from 'vue2-selectize'
    export default {
        components: {
            Selectize
        },
        mounted() {
            console.log('Order-form mounted.');
        },
        created: function(){
            let self = this;
            self.initFormData();
            self.initInternalData();
        },
        props: ['input'],
        data: function () {
            return {
                settings: {},
                formData: {
                    partner_id: '',
                    products: {
                        current: [],
                        for_add: []
                    },
                    client_email: '',
                    status: '',
                },
                internal: {
                    product_src: [],
                    product_selected: '',
                    selectize: {
                        settings: {}
                    },
                    isActiveForm: true
                },
            }
        },
        methods: {
            initFormData: function () {

                let self = this;
                if (self.input){
                    _.forEach(self.formData, function (val, key) {
                        if (key in self.input){
                            self.formData[key] = self.input[key];
                        }
                    });
                }
            },
            initInternalData: function(){

                let self = this;
                if (self.input){
                    _.forEach(self.internal, function (val, key) {
                        if (key in self.input){
                            self.internal[key] = self.input[key];
                        }
                    });
                }
            },
            removeProduct: function (src, alias, val) {

                let self = this;
                _.forEach(src, function (product, key) {
                    if (product[alias] == val){
                        console.log('Y', product);
                        self.$nextTick(function () {
                            src.splice(key, 1);
                        });
                    }
                });
            },
            addProduct: function (){

                let self = this;
                if (self.internal.product_selected){
                    self.$nextTick(function () {
                        let productToAdd = _.find(self.internal.product_src, function (o) {
                            return o.product_id == self.internal.product_selected;
                        });
                        let isExistInContainer = _.find(self.formData.products.for_add, {'product_id': productToAdd.product_id});
                        if ( productToAdd && !isExistInContainer ){
                            self.formData.products.for_add.push(productToAdd);
                        }
                    });
                }
            },
            submitForm: function () {
                let self = this;
                let action = self.$refs['form'].action;
                self.$validator.validateAll().then((result) => {
                    if (result) {
                        self.internal.isActiveForm = false;
                        apiForm.formSubmit(action, self.formData).then(function (response) {
                            if (response.data && response.data.success && response.data.url){
                                window.location.href = response.data.url;
                            } else {
                                self.internal.isActiveForm = true;
                            }
                        }).catch(function (e) {
                            console.error('form save error:', e);
                            self.internal.isActiveForm = true;
                        })
                    }
                });
            }
        },
        computed: {
            sum: function () {
                let self = this;
                let sum = 0;
                _.forEach(self.formData.products, function (container) {
                    _.forEach(container, function (product) {
                        sum += product.quantity * product.price;
                    });
                });
                return sum;
            }
        }
    }
    const apiForm = {
        formSubmit: function (action, formData) {
            return axios.post(action, formData);
        }
    };
</script>