<template>
<div>
    <form>
        <div class="container" id="info">
            <div class="row">
                <div class="col-md-6">
                    <h1>Personlige oplysninger</h1>
                    <hr>
                    <p>Grundlæggende oplysninger som f.eks. det navn og email du er registreret med</p>
                </div>
                <div class="col-md-6">
                    <img src="@/assets/icons8-male_user.png" class="img-fluid">
                </div>
            </div>
        </div>
        
        <div class="form-row">

            <div class="form-group col-md-6">
                <label for="inputFirstName">Navn</label>
                <input type="text" class="form-control" id="inputFirstName" v-model="user.firstName">
            </div>

            <div class="form-group col-md-6">
                <label for="inputLastName">Efternavn</label>
                <input type="text" class="form-control" id="inputLastName" v-model="user.lastName">
            </div>
        </div>

            <div class="form-group">
                <label for="inputAddress">Adresse</label>
                <input type="text" class="form-control" id="inputAddress" readonly v-model="user.address">
            </div>

            <div class="form-group">
                <label for="inputEmail">Email</label>
                <input type="text" class="form-control" id="inputEmail" v-model="user.email">
            </div>

            <div class="form-group">
                <label for="inputPhoneNumber">Telefon</label>
                <input type="text" class="form-control" id="inputPhoneNumber" v-model="user.phoneNumber">
            </div>

            <div class="container">
                <div class="row">
                    <div class="col">
                        <button type="button" class="btn btn-success" @click="saveUser()" data-toggle="modal" data-target="#modalSave">Gem ændringer</button>
                    </div>
                </div>
            </div>
    </form>
            <div class="modal fade" id="modalSave" role="dialog">
                <div class="modal-dialog">
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                        <strong>Succes!</strong> Dine oplysninger er nu ændret!
                    </div>
                </div>
            </div>
</div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

    export default {
        name: 'user-details',
        data() {
            return {
                user: {}
            }
        },
        created() {
            this.$store.dispatch('retrieveUser')
            this.user = this.getUserDetails
        },
        computed: {
            ...mapGetters(['getUserDetails'])
        },
        methods: {
            ...mapActions(['updateUser']),
            saveUser() {
                this.updateUser(this.user)
            }
        }
    }
</script>

<style lang="scss" scoped>

#info {
    padding: 25px 25px;
}

label {
    float: left;
    padding-left: 4px;
    margin: 2px;
}

form {
    display: grid;
    justify-content: center;
    align-content: center;
}

</style>