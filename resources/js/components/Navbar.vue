<template>
  <div>
    <v-app-bar
      dense
      dark
    >
      <v-btn  to="/">Vue Calendar</v-btn>

      <v-spacer></v-spacer>

      <span v-if="isLogin">
        {{ username }}
      </span>

      <v-btn v-else to="/login">
        Login / Register
      </v-btn>

      <v-menu
        left
        bottom
      >

      </v-menu>
    </v-app-bar>
  </div>
</template>

<script>
export default {
  computed: {
    isLogin () {
      return this.$store.getters['auth/check']
    },
    username () {
      return this.$store.getters['auth/username']
    }
  }
}
</script>
