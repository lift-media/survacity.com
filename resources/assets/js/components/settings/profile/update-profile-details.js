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
		var fromEmail = this.user.from_email;
		if(fromEmail != null)
		{
			$('#lblEmail').html("You are Connected as "+fromEmail+'<a href="/google" class="btn-connect btn btn-success">Connect a Different Gmail Account</a>');
		}else{
			$('#lblEmail').html('You are not connected <a href="/google" class="btn-connect btn btn-success">Connect a Gmail Account</a>');
		}
        this.form.from_email = this.user.from_email;
        this.form.signature = this.user.signature;        
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
