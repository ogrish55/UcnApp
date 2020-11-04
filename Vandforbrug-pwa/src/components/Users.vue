<template>
  <div class="overall">
    <div class="central">
      <div style="background-color: beige" class="big-border">
        <h1 style="padding-bottom: 40px">Users</h1>
        <ul>
          <li style="margin-top: 1px" class="user-border" v-for="person in info" :key="person.email">
            Name: {{ person.firstName }}
            <br>
            Email: {{ person.email }}
            <br>
            Phone: {{ person.phoneNumber }}
            <br>
            <br>
          </li>
        </ul>
      </div>
    </div>
    <div style="border-style: double; max-height: 350px ;">
      <form style="max-height: 175px">
        <h1> Add User </h1>
        <div style="margin: 0 10px 0 10px">
          <div style="margin-bottom: 3px">
            <input type="text" placeholder="Name.." v-model="firstName">
            <input type="email" placeholder="Email.." v-model="email">
          </div>
          <div>
            <input type="tel" placeholder="Phone.." v-model="phoneNumber">
            <input type="password" placeholder="Password.." v-model="password">
          </div>
        </div>
        <div style="margin-top: 40px">
          <button type="submit" @click.stop.prevent="checkForm()">Submit</button>
        </div>
        <div style="margin-top: 15px" v-if="errors.length">
          <b>Please correct the following error(s):</b>
          <ul>
            <li style="background-color: cyan" v-for="error in errors" v-bind:key="error.message">{{ error }}</li>
          </ul>
        </div>
      </form>
    </div>
  </div>

</template>

<script>
import axios from 'axios'

export default {
  name: 'Users',
  data () {
    return {
      info: null,
      errors: [],
      firstName: null,
      email: null,
      phoneNumber: null,
      password: null
    }
  },
  methods: {
    goHome () {
      this.$router.push('/')
    },
    fetchUsers () {
      axios
        .get('http://backend.test/api/users')
        .then(response => {
          this.info = response.data
        })
    },
    checkForm: function (e) {
      if (this.firstName && this.phoneNumber && this.email && this.password) {
        console.log('Validation true')
        axios({
          method: 'post',
          url: 'http://backend.test/api/users/',
          data: {
            firstName: this.firstName,
            lastName: 'Test',
            email: this.email,
            phoneNumber: this.phoneNumber,
            password: this.password
          }
        })
          .then(this.fetchUsers)
          .catch(err => {
            console.log(err)
          })
      } else {
        console.log('Validation failed')
      }

      this.errors = []

      if (!this.firstName) {
        this.errors.push('Name required.')
      }
      if (!this.email) {
        this.errors.push('Email required.')
      }
      if (!this.phoneNumber) {
        this.errors.push('Phone required.')
      }
      if (!this.password) {
        this.errors.push('Password required')
      }
    }
  },

  mounted () {
    this.fetchUsers()
  }

}
</script>
<style scoped>

.big-border {
  display: flex;
  flex-direction: column;
  flex-wrap: wrap;
  border-style: double;
  justify-content: flex-start;
}

.user-border {
  border-style: ridge;
}

ul {
  list-style: none;
  padding: 0;
}

.central {
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  margin-right: 45px;
}

ul {
  margin-bottom: 0px;
}

.overall {
  display: flex;
  justify-content: center;
}

li {
  background-color: whitesmoke;
}
</style>
