<template>
<b-navbar-nav v-if="this.auth.role_id == 1">
  <b-nav-item-dropdown text="Cadastros" right>
    <b-dropdown-item href="/stocks">Ações</b-dropdown-item>
    <b-dropdown-item href="/brokers">Corretoras</b-dropdown-item>
    <b-dropdown-item href="/users">Usuários</b-dropdown-item>
  </b-nav-item-dropdown>
  <b-nav-item-dropdown text="Cotações" right>
    <b-dropdown-item href="/dailyquotes">Diárias</b-dropdown-item>
    <b-dropdown-item href="/monthlyquotes">Mensais</b-dropdown-item>
  </b-nav-item-dropdown>
</b-navbar-nav>
</template>

<script>
export default {
  props: ['auth'],
  data() {
    return {
    }
  },
  methods: {

  }
}
</script>
