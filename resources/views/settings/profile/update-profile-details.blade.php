<update-profile-details :user="user" inline-template>
     <div class="panel panel-default">
        <div class="panel-heading">Email Setting</div>

        <div class="panel-body">
            <!-- Success Message -->
            <div class="alert alert-success" v-if="form.successful">
                Email setting has been updated!
            </div>

            <form class="form-horizontal" role="form">
                <!-- From Email -->
                <div class="form-group" :class="{'has-error': form.errors.has('from_email')}">
                    <label class="col-md-4 control-label">Sent From Email</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="from_email" v-model="form.from_email">

                        <span class="help-block" v-show="form.errors.has('from_email')">
                            @{{ form.errors.get('from_email') }}
                        </span>
                    </div>
                </div>
                
                <!-- From Email -->
                <div class="form-group" :class="{'has-error': form.errors.has('signature')}">
                    <label class="col-md-4 control-label">Email Signature</label>

                    <div class="col-md-6">
                        <textarea  class="form-control" name="signature" id ="" v-model="form.signature"></textarea>

                        <span class="help-block" v-show="form.errors.has('signature')">
                            @{{ form.errors.get('signature') }}
                        </span>
                    </div>
                </div>

                <!-- Update Button -->
                <div class="form-group">
                    <div class="col-md-offset-4 col-md-6">
                        <button type="submit" class="btn btn-primary"
                                @click.prevent="update"
                                :disabled="form.busy">

                            Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</update-profile-details>
