<template lang="pug">
  div
    | default comment show
</template>

<script>
export default {
  props: {
    comment: {
      type: Object,
      required: true,
    },
  },
}
</script>
