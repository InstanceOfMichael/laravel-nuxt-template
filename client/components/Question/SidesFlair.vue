<template lang="pug">
  //- span(v-if="item.sides_type === 0" v-show="false")
  span.badge.badge-secondary
    template(v-if="item.sides_type === 0") No sides allowed
    template(v-else-if="item.sides_type === 1") Answers from any side
    template(v-else-if="item.sides_type === 2") Allowed sides list
    template(v-else) Invalid Sides Type
</template>

<script>
export default {
  name: 'QuestionSidesFlair',
  props: {
    item: {
      type: Object,
      required: true,
    },
  },
}
</script>
