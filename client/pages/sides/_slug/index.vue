<template lang="pug">
  div
    | default claim show
</template>

<script>
export default {
  props: {
    claim: {
      type: Object,
      required: true,
    },
  },
}
</script>
