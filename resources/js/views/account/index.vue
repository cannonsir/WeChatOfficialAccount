<template>
  <div>
    <pre>{{ account }}</pre>
  </div>
</template>

<script>
export default {
  name: 'AccountIndex',
  props: {
    account: {
      type: Object,
      required: true
    }
  }
}
</script>

<style scoped>

</style>
