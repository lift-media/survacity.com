Vue.component('update-profile-details', {
    props: ['user'],

    data() {
        return {
            form: new SparkForm({               
                from_email: '',
                signature: ''
            })
        };
    },

    ready() {
        this.form.from_email = this.user.from_email;
        this.form.signature = this.user.signature;
        //alert(this.user.from_email+ ' : '+this.user.signature);
        //console.log(this.user.from_email);
        //console.log(this.user.signature);
    },

    methods: {
        update() {
            Spark.put('/settings/profile/details', this.form)
                .then(response => {
                    this.$dispatch('updateUser');
                });
        }
    }
});
