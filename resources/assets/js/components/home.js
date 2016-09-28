Vue.component('home', {
    props: ['user'],

    ready() {
		
		//console.log(Spark.state.currentTeam.name);
		//console.log(Spark.state.teams);
		//console.log(Spark.state.user.name);
				
		this.$http.get('/team/name')
			.then(response => {					
					console.log(response.data);
				});
		this.$http.get('api/test')
			.then(response => {
					console.log(response.data);
				});
				
    },
    computed: {
			upperName() {
				return this.user.name.toUpperCase();
			}	
	}
});
