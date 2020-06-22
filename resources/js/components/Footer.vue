<template>
    <v-footer
      padless
      class="font-weight-medium"
      dark
    >
      <v-col
        class="text-center"
        cols="12"
      >
        <v-btn v-if="! isLogin" to="/login">
          Login / Register
        </v-btn>
      </v-col>

      <v-col
        class="text-center"
        cols="12"
      >
        <v-btn v-if="isLogin" @click="logout">
          Logout
        </v-btn>
      </v-col>
    </v-footer>
</template>

<script>
export default {
  computed: {
    isLogin () {
      return this.$store.getters['auth/check']
    },
    apiStatus () {
      return this.$store.state.auth.apiStatus
    }
  },
  methods: {
    async logout() {
      await this.$store.dispatch('auth/logout')

      if(this.apiStatus) {
        this.$router.push('/login')
      }
    }
  }
}
</script>
