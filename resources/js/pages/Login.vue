<template>
  <v-app>
  <v-container>
    <v-card>
      <v-tabs
        center-active
        dark
      >
        <v-tab @click="tab = 1">Login</v-tab>
        <v-tab @click="tab = 2">Register</v-tab>
      </v-tabs>
    </v-card>
    <v-form
      ref="loginForm"
      v-show="tab === 1"
      @submit.prevent="login"
    >
      <v-container>
        <v-row>
          <v-col
            cols="12"
            md="4"
          >
            <v-text-field
              v-model="loginForm.email"
              label="email"
              type="email"
              :rules="loginEmailRules"
              required
            ></v-text-field>
          </v-col>

          <v-col
            cols="12"
            md="4"
          >
            <v-text-field
              v-model="loginForm.password"
              label="password"
              type="password"
              :rules="loginPasswordRules"
              required
            ></v-text-field>
          </v-col>
          <v-col
            cols="12"
            md="4"
          >
          <v-btn type="submit" block dark>
            Login
          </v-btn>
          </v-col>
        </v-row>
      </v-container>
    </v-form>
    <v-form
      ref="registerForm"
      v-show="tab === 2"
      @submit.prevent="register"
    >
      <v-container>
        <v-row>
          <v-col
            cols="12"
            md="3"
          >
            <v-text-field
              v-model="registerForm.name"
              label="name"
              type="text"
              :rules="registerNameRules"
              required
            ></v-text-field>
          </v-col>

          <v-col
            cols="12"
            md="3"
          >
            <v-text-field
              v-model="registerForm.email"
              label="email"
              type="email"
              :rules="registerEmailRules"
              required
            ></v-text-field>
          </v-col>

          <v-col
            cols="12"
            md="3"
          >
            <v-text-field
              v-model="registerForm.password"
              label="password"
              type="password"
              :rules="registerPasswordRules"
              required
            ></v-text-field>
          </v-col>

          <v-col
            cols="12"
            md="3"
          >
            <v-text-field
              v-model="registerForm.password_confirmation"
              label="password(確認)"
              type="password"
              :rules="registerPasswordConfirmationRules"
              required
            ></v-text-field>
          </v-col>

          <v-col
            cols="12"
            md="3"
          >
          <v-btn type="submit" block dark>
            Register
          </v-btn>
          </v-col>
        </v-row>
      </v-container>
    </v-form>
  </v-container>
</v-app>
</template>

<script>
  export default {
    data () {
      return {
        tab: 1,
        loginForm: {
          email: '',
          password: '',
        },
        registerForm: {
          name: '',
          email: '',
          password: '',
          password_confirmation: ''
        },
        loginEmailRules: [
          v => !!v || 'メールアドレスは必須項目です',
          v => /.+@.+\..+/.test(v) || 'メールアドレスの形式で記入してください',
        ],
        loginPasswordRules: [
          v => !!v || 'パスワードは必須項目です',
          v => v.length >= 8 || 'パスワードは８文字以上で記入してください',
        ],
        registerNameRules: [
          v => !!v || '名前は必須項目です',
        ],
        registerEmailRules: [
          v => !!v || 'メールアドレスは必須項目です',
          v => /.+@.+\..+/.test(v) || 'メールアドレスの形式で記入してください',
        ],
        registerPasswordRules: [
          v => !!v || 'パスワードは必須項目です',
          v => v.length >= 8 || 'パスワードは８文字以上で記入してください',
        ],
        registerPasswordConfirmationRules: [
          v => !!v || 'パスワード(確認)は必須項目です',
          v => v === this.registerForm.password || 'パスワードが一致しません'
        ],
      }
    },
    computed: {
      apiStatus () {
        return this.$store.state.auth.apiStatus
      },
      loginErrors () {
        return this.$store.state.auth.loginErrorMessages
      },
      registerErrors () {
        return this.$store.state.auth.registerErrorMessages
      }
    },
    methods: {
      async login () {
        await this.$store.dispatch('auth/login', this.loginForm)

        if(this.apiStatus) {
          this.$router.push('/')
        }
      },
      async register () {
        await this.$store.dispatch('auth/register', this.registerForm)

        if(this.apiStatus) {
          this.$router.push('/')
        }
      },
      clearError () {
        this.$store.commit('auth/setLoginErrorMessages', null)
        this.$store.commit('auth/setRegisterErrorMessages', null)
      }
    },
    created () {
      this.clearError()
    }
  }
</script>
