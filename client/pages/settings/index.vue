<template>
  <div>
    settings/index.vue
  </div>
</template>

<script>
export default {
  middleware: 'auth',

  mounted () {
    this.$router.push('/settings/profile')
  },
}
</script>

<style>
</style>
