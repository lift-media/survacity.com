Vue.component('update-profile-details', {
    props: ['user'],

    data() {
        return {
            form: new SparkForm({
                age: ''
            })
        };
    },

    ready() {
        this.form.age = this.user.age;
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
